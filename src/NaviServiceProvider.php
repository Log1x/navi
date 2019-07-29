<?php

namespace Log1x\Navi;

use Roots\Acorn\ServiceProvider;

class NaviServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('navi', function () {
            return new Navi();
        });
    }
}
