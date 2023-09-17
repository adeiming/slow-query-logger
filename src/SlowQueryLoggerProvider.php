<?php

namespace Adeiming\SlowQueryLogger;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class SlowQueryLoggerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {        
        $this->publishes([
            __DIR__.'/../config/slow-query.php' => config_path('slow-query.php')
        ]);
        
        DB::listen(function($query){
            $timeout = config('slow-query.minimum_timeout');
            
            if($query->time > $timeout){
                $pdo = $query->connection->getPdo();
                $data = $query->sql;
                foreach ($query->bindings ?? [] as $key => $binding) {
                    $regex = is_numeric($key)
                        ? "/(?<!\?)\?(?=(?:[^'\\\']*'[^'\\']*')*[^'\\\']*$)(?!\?)/"
                        : "/:{$key}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";
    
                    if (!is_int($binding) && !is_float($binding)) {
                        if ($pdo) {
                            try {
                                $binding = $binding instanceof DateTime
                                    ? Carbon::parse($binding)->format('Y-m-d H:i:s')
                                    : $binding;
    
                                $binding = $pdo->quote((string) $binding);
                            } catch (\Exception $e) {
                                $binding = $this->emulateQuote($binding);
                            }
                        } else {
                            $binding = $this->emulateQuote($binding);
                        }
                    }
                    
                    $data = preg_replace($regex, addcslashes($binding, '$'), $data, 1);
                }
    
                if ($query->time > $timeout) {
                    $badge = 'error';
                }elseif ($query->time > config('slow-query.maximum_timeout')) {
                    $badge = 'warning';
                }else{
                    $badge = 'info';
                }
    
                Log::channel(config('slow-query.log_channel'))->{$badge}(request()->url(), [$data, $query->time]);
            }
        });
    }

    private function emulateQuote($value)
    {
        $search = ["\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a"];
        $replace = ["\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z"];

        return "'" . str_replace($search, $replace, (string) $value) . "'";
    }
}
