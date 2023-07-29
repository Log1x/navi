<?php

/**
 * Plugin Name: Navi
 * Plugin URI:  https://github.com/log1x/navi
 * Description: A developer-friendly alternative to the WordPress NavWalker.
 * Version:     2.0.4
 * Author:      Brandon Nifong
 * Author URI:  https://github.com/log1x
 */

add_filter('after_setup_theme', function () {
    if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
        require_once $composer;
    }

    if (function_exists('Roots\bootloader')) {
        Roots\bootloader(function (Roots\Acorn\Application $app) {
            $app->register(
                Log1x\Navi\Providers\NaviServiceProvider::class
            );

            Roots\Acorn\AliasLoader::getInstance([
                'navi' => Log1x\Navi\Facades\Navi::class
            ])->register();
        });
    }
}, 20);
