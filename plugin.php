<?php

/**
 * Plugin Name: Navi
 * Plugin URI:  https://github.com/log1x/navi
 * Description: A developer-friendly alternative to the WordPress NavWalker.
 * Version:     1.1.0
 * Author:      Brandon Nifong
 * Author URI:  https://github.com/log1x
 */

add_filter('after_setup_theme', function () {
    if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
        require_once $composer;
    }

    if (function_exists('\Roots\app')) {
        \Roots\app()->register(
            Log1x\Navi\Providers\NaviServiceProvider::class
        );
    }
});
