<?php

namespace Log1x\Navi;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

class Navi extends Fluent
{
    /**
     * Build and assign the navigation menu items to the fluent instance.
     *
     * @param  int|string|WP_Term $menu
     * @return $this
     */
    public function build($menu = 'primary_navigation')
    {
        if (is_string($menu)) {
            $menu = Arr::get(get_nav_menu_locations(), $menu, $menu);
        }

        $this->attributes = (new Builder())->build(
            wp_get_nav_menu_items($menu) ?? []
        );

        return $this;
    }

    /**
     * Determine whether the fluent instance is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->attributes);
    }

    /**
     * Determine whether the fluent instance is not empty.
     *
     * @return bool
     */
    public function isNotEmpty()
    {
        return ! empty($this->attributes);
    }
}
