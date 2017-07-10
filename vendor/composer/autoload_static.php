<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticIniteffec1bf4b25c5836f07203d69d63933
{
    public static $files = array (
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        'a0edc8309cc5e1d60e3047b5df6b7052' => __DIR__ . '/..' . '/guzzlehttp/psr7/src/functions_include.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
        '5255c38a0faeba867671b61dfda6d864' => __DIR__ . '/..' . '/paragonie/random_compat/lib/random.php',
        '2c102faa651ef8ea5874edb585946bce' => __DIR__ . '/..' . '/swiftmailer/swiftmailer/lib/swift_required.php',
        '1d1d744f1bd36f7044f0f268edf1c6a2' => __DIR__ . '/../..' . '/api/core/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'Zend\\Stdlib\\' => 12,
            'Zend\\Db\\' => 8,
        ),
        'T' => 
        array (
            'Twig\\' => 5,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
        ),
        'L' => 
        array (
            'League\\OAuth2\\Client\\' => 21,
            'League\\OAuth1\\' => 14,
            'League\\Flysystem\\' => 17,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
        'D' => 
        array (
            'Directus\\Installation\\' => 22,
            'Directus\\Customs\\Hooks\\' => 23,
            'Directus\\Customs\\Hasher\\' => 24,
            'Directus\\Customs\\Embed\\Provider\\' => 32,
            'Directus\\API\\Routes\\' => 20,
            'Directus\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Zend\\Stdlib\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-stdlib/src',
        ),
        'Zend\\Db\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-db/src',
        ),
        'Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/twig/twig/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'League\\OAuth2\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/oauth2-client/src',
            1 => __DIR__ . '/..' . '/league/oauth2-github/src',
            2 => __DIR__ . '/..' . '/league/oauth2-facebook/src',
            3 => __DIR__ . '/..' . '/league/oauth2-google/src',
        ),
        'League\\OAuth1\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/oauth1-client/src',
        ),
        'League\\Flysystem\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/flysystem/src',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
        'Directus\\Installation\\' => 
        array (
            0 => __DIR__ . '/../..' . '/installation/includes',
        ),
        'Directus\\Customs\\Hooks\\' => 
        array (
            0 => __DIR__ . '/../..' . '/customs/hooks',
        ),
        'Directus\\Customs\\Hasher\\' => 
        array (
            0 => __DIR__ . '/../..' . '/customs/hashers',
        ),
        'Directus\\Customs\\Embed\\Provider\\' => 
        array (
            0 => __DIR__ . '/../..' . '/customs/embeds',
        ),
        'Directus\\API\\Routes\\' => 
        array (
            0 => __DIR__ . '/../..' . '/api/routes',
        ),
        'Directus\\' => 
        array (
            0 => __DIR__ . '/../..' . '/api/core/Directus',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Twig_Extensions_' => 
            array (
                0 => __DIR__ . '/..' . '/slim/extras/Views/Extension',
            ),
            'Twig_' => 
            array (
                0 => __DIR__ . '/..' . '/twig/twig/lib',
            ),
            'Task' => 
            array (
                0 => __DIR__ . '/..' . '/directus/migrations/lib',
            ),
        ),
        'S' => 
        array (
            'Slim\\Extras' => 
            array (
                0 => __DIR__ . '/..' . '/slim/extras',
            ),
            'Slim' => 
            array (
                0 => __DIR__ . '/..' . '/slim/slim',
            ),
        ),
        'R' => 
        array (
            'Ruckusing' => 
            array (
                0 => __DIR__ . '/..' . '/directus/migrations/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticIniteffec1bf4b25c5836f07203d69d63933::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticIniteffec1bf4b25c5836f07203d69d63933::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticIniteffec1bf4b25c5836f07203d69d63933::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
