<?php

namespace Log1x\Navi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Log1x\Navi\Navi build(mixed $menu = null)
 * @method static \Log1x\Navi\Navi withClasses(string|array $classes)
 * @method static \Log1x\Navi\Navi withoutClasses(string|array $classes)
 * @method static \Log1x\Navi\Navi withDefaultClasses()
 * @method static mixed get(string $key = null, mixed $default = null)
 * @method static bool isEmpty()
 * @method static bool isNotEmpty()
 * @method static array all()
 * @method static array toArray()
 * @method static string toJson(int $options = 0)
 *
 * @see \Log1x\Navi\Navi
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
