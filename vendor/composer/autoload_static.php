<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd0e15c1202fad603b27b37dfd982e114
{
    public static $prefixLengthsPsr4 = array (
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd0e15c1202fad603b27b37dfd982e114::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd0e15c1202fad603b27b37dfd982e114::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
