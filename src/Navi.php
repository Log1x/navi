<?php

namespace Log1x\Navi;

class Navi
{
    /**
     * The menu object.
     */
    protected mixed $menu;

    /**
     * The menu items.
     */
    protected array $items = [];

    /**
     * The default menu.
     */
    protected string $default = 'primary_navigation';

    /**
     * Create a new Navi instance.
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $key => $value) {
            $this->items[$key] = $value;
        }
    }

    /**
     * Make a new Navi instance.
     */
    public static function make(array $items = []): self
    {
        return new static($items);
    }

    /**
     * Build the navigation menu items.
     */
    public function build(mixed $menu = null): self
    {
        $menu = $menu ?? $this->default;

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

        $items = wp_get_nav_menu_items($this->menu);

        $this->items = MenuBuilder::make()->build($items ?: []);

        return $this;
    }

    /**
     * Retrieve the specified key from the WordPress menu object.
     * If no key is specified, the entire menu object will be returned.
     */
    public function get(?string $key = null, mixed $default = null): mixed
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
     * Determine if Navi is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->all());
    }

    /**
     * Determine if Navi is not empty.
     */
    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * Retrieve the Navi items.
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Retrieve the Navi items as an array.
     */
    public function toArray(): array
    {
        return $this->all();
    }

    /**
     * Retrieve the Navi items as JSON.
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Dynamically retrieve a Navi item.
     */
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }
}
