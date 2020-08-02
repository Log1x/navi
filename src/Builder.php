<?php

namespace Log1x\Navi;

class Builder
{
    /**
     * The current menu.
     *
     * @var array
     */
    protected $menu = [];

    /**
     * Attributes Map
     *
     * @var array
     */
    protected $attributes = [
        'active'         => 'current',
        'activeAncestor' => 'current_item_ancestor',
        'activeParent'   => 'current_item_parent',
        'classes'        => 'classes',
        'dbId'           => 'db_id',
        'description'    => 'description',
        'id'             => 'ID',
        'label'          => 'title',
        'objectId'       => 'object_id',
        'parent'         => 'menu_item_parent',
        'slug'           => 'post_name',
        'target'         => 'target',
        'title'          => 'attr_title',
        'url'            => 'url',
        'xfn'            => 'xfn',
    ];

    /**
     * Blacklisted Classes
     *
     * @var array
     */
    protected $classes = [
        'current-menu',
        'current_page',
        'sub-menu',
        'menu-item',
        'menu_item',
        'page-item',
        'page_item',
    ];

    /**
     * Build a filtered array of objects containing the navigation menu items.
     *
     * @param array $menu
     *
     * @return array
     */
    public function build($menu)
    {
        $this->menu = $this->filter($menu);

        if (empty($this->menu)) {
            return;
        }

        return $this->tree(
            $this->map($this->menu)
        );
    }

    /**
     * Filter the menu items into a prepared collection.
     *
     * @param array $menu
     *
     * @return array
     */
    protected function filter($menu = [])
    {
        $menu = array_filter($menu, function ($item) {
            return $item instanceof \WP_Post;
        });

        _wp_menu_item_classes_by_context($menu);

        return array_map(function ($item) {
            $item->classes = join(' ', array_filter($item->classes, function ($class) {
                return false === strpos($class, $this->classes);
            }));

            foreach ($item as $key => $value) {
                if (! $value) {
                    $item->{$key} = false;
                }
            }

            return $item;
        }, $menu);
    }

    /**
     * Map the menu item properties into a fluent object.
     *
     * @param array $menu
     *
     * @return array
     */
    protected function map($menu = [])
    {
        return array_map(function ($item) {
            $collect = [];
            foreach ($this->attributes as $key => $value) {
                $collect[$key] = $item->{$value};
            }

            return (object)$collect;
        }, $menu);
    }

    /**
     * Build a multi-dimensional array containing children menu items.
     *
     * @param array $items
     * @param int   $parent
     * @param array $branch
     *
     * @return array
     */
    protected function tree($items, $parent = 0, $branch = [])
    {
        foreach ($items as $item) {
            if ($item->parent == $parent) {
                $children       = $this->tree($items, $item->id);
                $item->children = ! empty($children) ? $children : [];

                $branch[$item->id] = $item;
                unset($item);
            }
        };

        return $branch;
    }
}
