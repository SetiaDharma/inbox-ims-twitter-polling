<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit617d6c55ee59e0cb4225736d5b850ae9
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Abraham\\TwitterOAuth\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Abraham\\TwitterOAuth\\' => 
        array (
            0 => __DIR__ . '/..' . '/abraham/twitteroauth/src',
        ),
    );

    public static $classMap = array (
        'TwitterAPIExchange' => __DIR__ . '/..' . '/j7mbo/twitter-api-php/TwitterAPIExchange.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit617d6c55ee59e0cb4225736d5b850ae9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit617d6c55ee59e0cb4225736d5b850ae9::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit617d6c55ee59e0cb4225736d5b850ae9::$classMap;

        }, null, ClassLoader::class);
    }
}
