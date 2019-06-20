# Navi

Hate the WordPress NavWalker? Me too.

Navi is a simple package that allows you to return a WordPress menu as an iterable object containing the necessities to build out your menu how you want.

## Installation

Install via Composer:

```bash
$ composer require log1x/navi
```

## Usage

### Basic Usage

```php
use Log1x\Navi\Navi;

$navigation = (new Navi())->build('primary_navigation');
```

### Sage 10

When using Sage 10, you can take advantage of Navi's Service Provider and Facade to avoid needing to reinitialize the Navi class.

```php
# Composers/Navigation.php

<?php

namespace App\Composers;

use Roots\Acorn\View\Composer;
use Log1x\Navi\NaviFacade as Navi;

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
     * @param  array $data
     * @param  \Illuminate\View\View $view
     * @return array
     */
    public function with($data, $view)
    {
        return [
            'navigation' => $this->navigation(),
        ];
    }

    /**
     * Returns the primary navigation.
     *
     * @return string
     */
    public function navigation()
    {
        return Navi::build('primary_navigation');
    }
}
```

```php
# views/partials/navigation.blade.php

@if ($navigation)
  <ul class="my-menu">
    @foreach ($navigation as $item)
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

## Example Output

When calling `build()`, it will parse the passed navigation menu and return a simple, nested object of your menu items. By default, `build()` calls `primary_navigation` which is the default menu theme location on Sage.

```php
array [
  24677 => {
    +"parent": false
    +"id": 24677
    +"label": "Home"
    +"slug": "home"
    +"url": "/"
    +"active": true
  }
  24678 => {
    +"parent": false
    +"id": 24678
    +"label": "Blog"
    +"slug": "blog"
    +"url": "#"
    +"active": false
    +"children": array [
      24721 => {
        +"parent": 24678
        +"id": 24721
        +"label": "Example"
        +"slug": "example"
        +"url": "#"
        +"active": false
        +"children": array [
          ...
        ]
      }
    ]
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
