<?php

namespace Log1x\Navi;

use Illuminate\Support\Str;
use Illuminate\Support\Fluent;

class Navi
{
    /**
     * Blacklisted Classes
     *
     * @var array
     */
    protected $classes = [
        'current-menu-ancestor',
        'current-menu-parent',
        'current_page_ancestor',
        'current_page_parent',
        'menu-item',
        'page_item',
        'page-item',
    ];

    /**
     * Parse the array of WP_Post objects returned by wp_get_nav_menu_items().
     *
     * @param  array $items
     * @return object
     */
    protected function parse($items)
    {
        if (! is_array($items)) {
            return;
        }

        _wp_menu_item_classes_by_context($items);

        return $this->tree(
            collect($items)
                ->map(function ($item) {
                    return (object) [
                        'parent' => $this->hasParent($item),
                        'id' => $item->ID,
                        'label' => $item->title,
                        'slug' => $item->post_name,
                        'url' => $item->url,
                        'active' => $item->current,
                        'activeAncestor' => $item->current_item_ancestor,
                        'activeParent' => $item->current_item_parent,
                        'classes' => $this->filterClasses($item->classes),
                        'title' => $item->attr_title,
                        'description' => $item->description,
                        'target' => $item->target,
                        'xfn' => $item->xfn,
                    ];
                })
        );
    }

    /**
     * Returns the menu item's parent if it exists.
     *
     * @param  WP_Post $item
     * @return int|boolean
     */
    protected function hasParent($item)
    {
        return $item->menu_item_parent != 0 ? $item->menu_item_parent : false;
    }

    /**
     * Returns a filtered list of classes.
     *
     * @param  array  $classes
     * @return string
     */
    protected function filterClasses($classes)
    {
        return collect($classes)->filter(function ($class) {
            return ! Str::contains($class, $this->classes);
        })->implode(' ');
    }

    /**
     * Build a multi-dimensional array containing children nav items.
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

                $item->children = [];

                if (! empty($children)) {
                    $item->children = $children;
                }

                $branch[$item->id] = $item;
                unset($item);
            }
        };

        return $branch;
    }

    /**
     * Build a fluent instance containing our navigation.
     *
     * @param  int|string|WP_Term $menu
     * @return Illuminate\Support\Fluent
     */
    public function build($menu = 'primary_navigation')
    {
        if (is_string($menu)) {
            $menu = get_nav_menu_locations()[$menu] ?? [];
        }

        if (empty($menu)) {
            return;
        }

        return new Fluent(
            $this->parse(
                wp_get_nav_menu_items($menu)
            )
        );
    }
}
