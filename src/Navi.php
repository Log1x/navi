<?php

namespace Log1x\Navi;

use ArrayAccess;
use JsonSerializable;
use Log1x\Navi\Contracts\Arrayable;
use Log1x\Navi\Contracts\Jsonable;

class Navi implements Arrayable, ArrayAccess, Jsonable, JsonSerializable
{
    /**
     * The menu object.
     *
     * @var mixed
     */
    protected $menu;

    /**
     * The menu items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Create a new Navi instance.
     *
     * @param  array|object  $items
     * @return void
     */
    public function __construct($items = [])
    {
        foreach ($items as $key => $value) {
            $this->items[$key] = $value;
        }
    }

    /**
     * Build and assign the navigation menu items to the Navi instance.
     *
     * @param  int|string|\WP_Term $menu
     * @return $this
     */
    public function build($menu = 'primary_navigation')
    {
        if (is_string($menu)) {
            $locations = get_nav_menu_locations();

            if (array_key_exists($menu, $locations)) {
                $menu = $locations[$menu];

                if (has_filter('wpml_object_id')) {
                    $menu = apply_filters('wpml_object_id', $menu, 'nav_menu');
                }
            }
        }

        $this->menu = wp_get_nav_menu_object($menu);

        $this->items = (new MenuBuilder())->build(
            wp_get_nav_menu_items($this->menu)
        );

        return $this;
    }

    /**
     * Returns the current navigation menu object.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key = null, $default = null)
    {
        if (! $this->menu) {
            return $default;
        }

        if (! empty($key)) {
            return $this->menu->{$key} ?? $default;
        }

        return $this->menu;
    }

    /**
     * Determine whether the Navi instance is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Determine whether the Navi instance is not empty.
     *
     * @return bool
     */
    public function isNotEmpty()
    {
        return ! $this->isEmpty();
    }

    /**
     * Get the items from the Navi instance.
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Convert the Navi instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the Navi instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Determine if the given offset exists.
     *
     * @param  string  $offset
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  string  $offset
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Set the value at the given offset.
     *
     * @param  string  $offset
     * @param  mixed  $value
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * Unset the value at the given offset.
     *
     * @param  string  $offset
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Handle dynamic calls to the Navi instance to set items.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return $this
     */
    public function __call($method, $parameters)
    {
        $this->items[$method] = count($parameters) > 0 ? $parameters[0] : true;

        return $this;
    }

    /**
     * Dynamically retrieve the value of an attribute.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Dynamically set the value of an attribute.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * Dynamically check if an attribute is set.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Dynamically unset an attribute.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }
}
