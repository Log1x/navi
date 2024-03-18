<?php

namespace Log1x\Navi;

class MenuBuilder
{
    /**
     * The current menu.
     */
    protected array $menu = [];

    /**
     * The attributes map.
     */
    protected array $attributes = [
        'active' => 'current',
        'activeAncestor' => 'current_item_ancestor',
        'activeParent' => 'current_item_parent',
        'classes' => 'classes',
        'dbId' => 'db_id',
        'description' => 'description',
        'id' => 'ID',
        'label' => 'title',
        'object' => 'object',
        'objectId' => 'object_id',
        'parent' => 'menu_item_parent',
        'slug' => 'post_name',
        'target' => 'target',
        'title' => 'attr_title',
        'type' => 'type',
        'url' => 'url',
        'xfn' => 'xfn',
        'order' => 'menu_order',
    ];

    /**
     * The disallowed menu classes.
     */
    protected array $disallowedClasses = [
        'current-menu',
        'current_page',
        'sub-menu',
        'menu-item',
        'menu-item-type-post_type',
        'menu-item-object-page',
        'menu-item-type-custom',
        'menu-item-object-custom',
        'menu_item',
        'page-item',
        'page_item',
    ];

    /**
     * Make a new Menu Builder instance.
     */
    public static function make(): self
    {
        return new static;
    }

    /**
     * Build the navigation menu.
     */
    public function build(array $menu = []): array
    {
        $this->menu = $this->filter($menu);

        if (! $this->menu) {
            return [];
        }

        $this->menu = array_combine(
            array_column($this->menu, 'ID'),
            $this->menu
        );

        return $this->handle(
            $this->map($this->menu)
        );
    }

    /**
     * Filter the menu items.
     */
    protected function filter(array $menu = []): array
    {
        $menu = array_filter($menu, fn ($item) => is_a($item, 'WP_Post') || is_a($item, 'WPML_LS_Menu_Item'));

        if (! $menu) {
            return [];
        }

        _wp_menu_item_classes_by_context($menu);

        return array_map(function ($item) {
            $classes = array_filter($item->classes, fn ($class) => ! in_array($class, $this->disallowedClasses));

            $item->classes = is_array($classes) ? implode(' ', $classes) : $classes;

            foreach ($item as $key => $value) {
                if (! $value) {
                    $item->{$key} = false;
                }
            }

            return $item;
        }, $menu);
    }

    /**
     * Map the menu items into an object.
     */
    protected function map(array $menu = []): array
    {
        return array_map(function ($item) {
            $result = [];

            foreach ($this->attributes as $key => $value) {
                $result[$key] = $item->{$value};
            }

            $result['parentObjectId'] = ! empty($result['parent']) && ! empty($this->menu[$result['parent']])
                ? $this->menu[$result['parent']]->object_id
                : false;

            return (object) $result;
        }, $menu);
    }

    /**
     * Handle the menu item hierarchy.
     */
    protected function handle(array $items, int $parent = 0): array
    {
        $menu = [];

        foreach ($items as $item) {
            if ($item->parent != $parent) {
                continue;
            }

            $item->children = $this->handle($items, $item->id);

            $menu[$item->id] = $item;

            unset($item);
        }

        return $menu;
    }
}
