<?php

namespace Log1x\Navi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Log1x\Navi\Navi build(string $menu = 'primary_navigation')
 * @method static mixed get(string $key = null, mixed $default = null)
 * @method static bool isEmpty()
 * @method static bool isNotEmpty()
 *
 * @see \Log1x\Navi\Navi
 * @see \Illuminate\Support\Fluent
 */
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
