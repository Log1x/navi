<?php

namespace Log1x\Navi\Providers;

use Log1x\Navi\Navi;
use Illuminate\Support\ServiceProvider;

class NaviServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('navi', function () {
            return new Navi();
        });
    }
}
