<?php

/**
 * Plugin Name: Navi
 * Plugin URI:  https://github.com/log1x/navi
 * Description: A developer-friendly alternative to the WordPress NavWalker.
 * Version:     3.0.0
 * Author:      Brandon Nifong
 * Author URI:  https://github.com/log1x
 */

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    return;
}

require_once $composer;

add_filter('after_setup_theme', function () {
    if (! function_exists('Roots\bootloader')) {
        return;
    }

    $app = Roots\bootloader()->getApplication();

    $app->register(Log1x\Navi\Providers\NaviServiceProvider::class);

    $app->alias('navi', Log1x\Navi\Facades\Navi::class);
}, 20);
