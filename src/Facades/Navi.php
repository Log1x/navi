<?php

namespace Log1x\Navi\Facades;

use Illuminate\Support\Facades\Facade;

class Navi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'navi';
    }
}
