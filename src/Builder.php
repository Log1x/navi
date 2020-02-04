<?php

namespace Log1x\Navi;

use Illuminate\Support\Str;

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
     * @param  array $menu
     * @return array
     */
    public function build($menu)
    {
        $this->menu = $this->filter($menu);

        if ($this->menu->isEmpty()) {
            return;
        }

        return $this->tree(
            $this->map($this->menu)->toArray()
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
        $menu = collect($menu)->filter(function ($item) {
            return $item instanceof \WP_Post;
        })->all();

        _wp_menu_item_classes_by_context($menu);

        return collect($menu)->map(function ($item) {
            $item->classes = collect($item->classes)->filter(function ($class) {
                return ! Str::contains($class, $this->classes);
            })->implode(' ');

            foreach ($item as $key => $value) {
                if (! $value) {
                    $item->{$key} = false;
                }
            }

            return $item;
        });
    }

    /**
     * Map the menu item properties into a fluent object.
     *
     * @param  array $menu
     * @return \Illuminate\Support\Collection
     */
    protected function map($menu = [])
    {
        return collect($menu)->map(function ($item) {
            return (object) collect($this->attributes)
                ->flatMap(function ($value, $key) use ($item) {
                    return [$key => $item->{$value}];
                })->all();
        });
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
            if ($item->parent == $parent) {
                $children = $this->tree($items, $item->id);
                $item->children = ! empty($children) ? $children : [];

                $branch[$item->id] = $item;
                unset($item);
            }
        };

        return $branch;
    }
}
