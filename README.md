# Navi

![Latest Stable Version](https://img.shields.io/packagist/v/log1x/navi?style=flat-square)
![Build Status](https://img.shields.io/circleci/build/github/Log1x/navi?style=flat-square)
![Total Downloads](https://img.shields.io/packagist/dt/log1x/navi?style=flat-square)

Hate the WordPress NavWalker? Me too.

Navi is a simple package that allows you to return a WordPress menu as an iterable object containing the necessities to build out your menu how you want.

## Requirements

- [Sage](https://github.com/roots/sage) >= 9.0
- [PHP](https://secure.php.net/manual/en/install.php) >= 7.1.3
- [Composer](https://getcomposer.org/download/)

## Installation

Install via Composer:

```bash
$ composer require log1x/navi
```

## Usage

### Basic Usage

By default, Navi returns a [fluent container](https://laravel.com/api/master/Illuminate/Support/Fluent.html) containing your navigation items.

```php
use Log1x\Navi\Navi;

$navigation = (new Navi())->build('primary_navigation');

if ($navigation->isEmpty()) {
  return;
}

return $navigation->toArray();
```

### Sage 10

When using Sage 10, you can take advantage of Navi's Service Provider and Facade to avoid needing to reinitialize the Navi class. Here's an example of adding Navi to a Composer that targets your navigation partial:

```php
# Composers/Navigation.php

<?php

namespace App\Composers;

use Roots\Acorn\View\Composer;
use Log1x\Navi\Facades\Navi;

class Navigation extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.navigation'
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'navigation' => $this->navigation(),
        ];
    }

    /**
     * Returns the primary navigation.
     *
     * @return array
     */
    public function navigation()
    {
        return Navi::build();
    }
}
```

```php
# views/partials/navigation.blade.php

@if ($navigation->isNotEmpty())
  <ul class="my-menu">
    @foreach ($navigation->toArray() as $item)
      <li class="my-menu-item {{ $item->active ? 'active' : '' }}">
        <a href="{{ $item->url }}">
          {{ $item->label }}
        </a>

        @if ($item->children)
          <ul class="my-child-menu">
            @foreach ($item->children as $child)
              <li class="my-child-item {{ $child->active ? 'active' : '' }}">
                <a href="{{ $child->url }}">
                  {{ $child->label }}
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </li>
    @endforeach
  </ul>
@endif
```

### Page Meta Fields

You may find that you need to access meta field values from pages that are in the menu. For this, you can make use of the `object_id` field which is a property of each `$item`.

An example using ACF:

```php
{{ get_field('custom_nav_item_label', $item->object_id) ?: $item->label }}
```

This will use the custom meta value for the navigation item label and fallback to the item label if it's not set.

## Example Output

When calling `build()`, Navi will parse the passed navigation menu and return a fluent container containing your menu items. To return an array of objects, simply call `->toArray()`.

By default, `build()` calls `primary_navigation` which is the default menu theme location on Sage.

```php
array [
  5 => {
    +"active": true
    +"activeAncestor": false
    +"activeParent": false
    +"children": false
    +"classes": "example"
    +"db_id": 5
    +"description": false
    +"id": 5
    +"label": "Home"
    +"object_id": "99"
    +"parent": false
    +"slug": "home"
    +"target": "_blank"
    +"title": false
    +"url": "https://sage.test/"
    +"xfn": false
  }
  6 => {
    +"active": false
    +"activeAncestor": false
    +"activeParent": false
    +"children": array [
      7 => {
        +"active": false
        +"activeAncestor": false
        +"activeParent": false
        +"children": array [
          ...
        ]
        +"classes": false
        +"db_id": 7
        +"description": false
        +"id": 7
        +"label": "Example"
        +"object_id": "101"
        +"parent": 6
        +"slug": "example"
        +"target": false
        +"title": false
        +"url": "#"
        +"xfn": false
      }
    ]
    +"classes": false
    +"db_id": 6
    +"description": false
    +"id": 6
    +"label": "Sample Page"
    +"object_id": "100"
    +"parent": false
    +"slug": "sample-page"
    +"target": false
    +"title": false
    +"url": "https://sage.test/sample-page/"
    +"xfn": false
  }
]
```

That being said, depending on how deep your menu is– you can ultimately just keep looping over `->children` indefinitely.

## Bug Reports

If you discover a bug in Navi, please [open an issue](https://github.com/log1x/navi/issues).

## Contributing

Contributing whether it be through PRs, reporting an issue, or suggesting an idea is encouraged and appreciated.

## License

Navi is provided under the [MIT License](https://github.com/log1x/navi/blob/master/LICENSE.md).
