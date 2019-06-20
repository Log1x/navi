<?php

namespace Log1x\Navi;

use Illuminate\Support\Facades\Facade;

class NaviFacade extends Facade
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
