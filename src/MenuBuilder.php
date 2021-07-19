<?php

namespace Log1x\Navi;

class MenuBuilder
{
    /**
     * The current menu.
     *
     * @var array
     */
    protected $menu = [];

    /**
     * The attributes map.
     *
     * @var array
     */
    protected $attributes = [
        'active' => 'current',
        'activeAncestor' => 'current_item_ancestor',
        'activeParent' => 'current_item_parent',
        'classes' => 'classes',
        'dbId' => 'db_id',
        'description' => 'description',
        'id' => 'ID',
        'label' => 'title',
        'objectId' => 'object_id',
        'parent' => 'menu_item_parent',
        'slug' => 'post_name',
        'target' => 'target',
        'title' => 'attr_title',
        'url' => 'url',
        'xfn' => 'xfn',
    ];

    /**
     * The disallowed classes.
     *
     * @var array
     */
    protected $disallowedClasses = [
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
     * Build a filtered array of objects containing the navigation menu items.
     *
     * @param  array $menu
     * @return array
     */
    public function build($menu)
    {
        $this->menu = $this->filter((array) $menu);

        if (empty($this->menu)) {
            return;
        }

        return $this->tree(
            $this->map($this->menu)
        );
    }

    /**
     * Filter the menu item's into a prepared collection.
     *
     * @param  array $menu
     * @return \Illuminate\Support\Collection
     */
    protected function filter($menu = [])
    {
        $menu = array_filter($menu, function ($item) {
            return is_a($item, 'WP_Post') || is_a($item, 'WPML_LS_Menu_Item');
        });

        if (empty($menu)) {
            return;
        }

        _wp_menu_item_classes_by_context($menu);

        return array_map(function ($item) {
            $classes = array_filter($item->classes, function ($class) {
                return ! in_array($class, $this->disallowedClasses);
            });

            $item->classes = is_array($classes) ? implode(' ', $classes) : $classes;

            foreach ($item as $key => $value) {
                if (empty($value)) {
                    $item->{$key} = false;
                }
            }

            return $item;
        }, $menu);
    }

    /**
     * Map the menu item properties into a fluent object.
     *
     * @param  array $menu
     * @return \Illuminate\Support\Collection
     */
    protected function map($menu = [])
    {
        return array_map(function ($item) {
            $result = [];

            foreach ($this->attributes as $key => $value) {
                $result[$key] = $item->{$value};
            }

            return (object) $result;
        }, $menu);
    }

    /**
     * Build a multi-dimensional array containing children menu items.
     *
     * @param  object $items
     * @param  int    $parent
     * @param  array  $branch
     * @return array
     */
    protected function tree($items, $parent = 0, $branch = [])
    {
        foreach ($items as $item) {
            if (
                $item->parent === false && $parent === 0 ||
                strcmp($item->parent, $parent) === 0
            ) {
                $children = $this->tree($items, $item->id);
                $item->children = ! empty($children) ? $children : [];

                $branch[$item->id] = $item;
                unset($item);
            }
        };

        return $branch;
    }
}
