<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit141e34667b968f94d51a64b088827a03
{
    public static $files = array (
        '241d2bc730dc592f76425fee315639b6' => __DIR__ . '/..' . '/globalcitizen/php-iban/oophp-iban.php',
        'ac2da84b5f360a33c0c760ac23936bfb' => __DIR__ . '/..' . '/globalcitizen/php-iban/php-iban.php',
        '7d5e26177ec0c967a595fc634f0744a3' => __DIR__ . '/..' . '/bliskapaczkapl/bliskapaczka-api-client/src/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'B' => 
        array (
            'Bliskapaczka\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Bliskapaczka\\' => 
        array (
            0 => __DIR__ . '/..' . '/bliskapaczkapl/bliskapaczka-api-client/src/Bliskapaczka',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit141e34667b968f94d51a64b088827a03::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit141e34667b968f94d51a64b088827a03::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
