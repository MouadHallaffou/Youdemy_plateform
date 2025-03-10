<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3f873c1014cf0204b56e26a02b6712fd
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3f873c1014cf0204b56e26a02b6712fd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3f873c1014cf0204b56e26a02b6712fd::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3f873c1014cf0204b56e26a02b6712fd::$classMap;

        }, null, ClassLoader::class);
    }
}
