<?php

namespace Log1x\Navi;

class Navi
{
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
                        'active' => $item->current
                    ];
                })
        );
    }

    /**
     * Returns the menu item's parent if it exists.
     *
     * @param  WP_Post $item
     * @return integer|boolean
     */
    protected function hasParent($item)
    {
        return $item->menu_item_parent != 0 ? $item->menu_item_parent : false;
    }

    /**
     * Build a multi-dimensional array containing children nav items.
     *
     * @param  object  $items
     * @param  integer $parent
     * @param  array   $branch
     * @return array
     */
    protected function tree($items, $parent = 0, $branch = [])
    {
        foreach ($items as $item) {
            if ($item->parent == $parent) {
                $children = $this->tree($items, $item->id);

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
     * Build an object containing our navigation.
     *
     * @param  int|string|WP_Term $menu
     * @return object
     */
    public function build($menu = 'primary_navigation')
    {
        if (is_string($menu)) {
            $menu = get_nav_menu_locations()[$menu] ?? [];
        }

        if (empty($menu)) {
            return;
        }

        return $this->parse(
            wp_get_nav_menu_items($menu)
        );
    }
}
