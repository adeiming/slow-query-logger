<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcd365e1ac8534c6eae55965dab369003
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Adeiming\\SlowQueryLogger\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Adeiming\\SlowQueryLogger\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcd365e1ac8534c6eae55965dab369003::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcd365e1ac8534c6eae55965dab369003::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitcd365e1ac8534c6eae55965dab369003::$classMap;

        }, null, ClassLoader::class);
    }
}
