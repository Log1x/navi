<?php

namespace Log1x\Navi;

use Log1x\Navi\Exceptions\MenuLifecycleException;

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
     * The disallowed menu classes.
     */
    protected array $disallowedClasses = [
        'current-page',
        'current-menu',
        'menu-item',
        'page-item',
        'sub-menu',
    ];

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

        $this->items = MenuBuilder::make()
            ->withoutClasses($this->disallowedClasses())
            ->build($items ?: []);

        return $this;
    }

    /**
     * Retrieve data from the WordPress menu object.
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
     * Determine if the menu is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->all());
    }

    /**
     * Determine if the menu is not empty.
     */
    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * Retrieve the menu items.
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Retrieve the menu items as an array.
     */
    public function toArray(): array
    {
        return $this->all();
    }

    /**
     * Retrieve the menu items as JSON.
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * The classes to allow on menu items.
     *
     * @throws \Log1x\Navi\Exceptions\MenuLifecycleException
     */
    public function withClasses(string|array $classes): self
    {
        if ($this->menu) {
            throw new MenuLifecycleException('Classes must be set before building the menu.');
        }

        $classes = is_string($classes)
            ? explode(' ', $classes)
            : $classes;

        $this->disallowedClasses = array_diff($this->disallowedClasses, $classes);

        return $this;
    }

    /**
     * The classes to remove from menu items.
     *
     * @throws \Log1x\Navi\Exceptions\MenuLifecycleException
     */
    public function withoutClasses(string|array $classes): self
    {
        if ($this->menu) {
            throw new MenuLifecycleException('Attributes must be set before building the menu.');
        }

        $classes = is_string($classes)
            ? explode(' ', $classes)
            : $classes;

        $this->disallowedClasses = array_unique([
            ...$this->disallowedClasses,
            ...$classes,
        ]);

        return $this;
    }

    /**
     * Allow the disallowed classes on menu items.
     */
    public function withDefaultClasses(): self
    {
        $this->disallowedClasses = [];

        return $this;
    }

    /**
     * Retrieve the disallowed classes.
     */
    protected function disallowedClasses(): array
    {
        return array_merge(...array_map(fn ($class) => [
            $class,
            str_replace('-', '_', $class),
        ], $this->disallowedClasses));
    }

    /**
     * Dynamically retrieve a Navi item.
     */
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }
}
