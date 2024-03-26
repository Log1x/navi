<?php

namespace Log1x\Navi\Providers;

use Illuminate\Support\ServiceProvider;
use Log1x\Navi\Console\NaviMakeCommand;
use Log1x\Navi\Navi;

class NaviServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('navi', fn () => Navi::make());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                NaviMakeCommand::class,
            ]);
        }
    }
}
