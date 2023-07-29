# Navi

![Latest Stable Version](https://img.shields.io/packagist/v/log1x/navi.svg?style=flat-square)
![Total Downloads](https://img.shields.io/packagist/dt/log1x/navi.svg?style=flat-square)
![Build Status](https://img.shields.io/github/actions/workflow/status/log1x/navi/compatibility.yml?branch=master&style=flat-square)

Hate the WordPress NavWalker? **Me too**.

Navi is a developer-friendly alternative to the NavWalker. Easily build your WordPress menus using an iterable object inside of a template/view.

## Requirements

- [PHP](https://secure.php.net/manual/en/install.php) >= 7.0

## Installation

### Bedrock (or Sage)

Install via Composer:

```bash
$ composer require log1x/navi
```

### Manual

Download the [latest release](https://github.com/Log1x/navi/releases/latest) `.zip` and install into `wp-content/plugins`.

## Usage

Check out the [**examples**](examples) folder to see how to use Navi in your project.

### Basic Usage

```php
<?php

use Log1x\Navi\Navi;

$navigation = (new Navi())->build('primary_navigation');

if ($navigation->isEmpty()) {
  return;
}

return $navigation->toArray();
```

When building the navigation menu, Navi retains the menu object and makes it available using the `get()` method.

By default, `get()` returns the raw[`wp_get_nav_menu_object()`](https://codex.wordpress.org/Function_Reference/wp_get_nav_menu_object) allowing you to access it directly.

Optionally, you may pass a `key` and `default` to call a specific object key with a fallback have it be null, empty, or not set.

```php
$navigation->get()->name;
$navigation->get('name', 'My menu title');
```

### Accessing Page Objects

If your menu item is linked to a page object (e.g. not a custom link) – you can retrieve the ID of the page using the `objectId` attribute.

```php
# Blade
{{ get_post_type($item->objectId) }}

# PHP
<?php echo get_post_type($item->objectId); ?>
```

### Accessing Custom Fields

In a scenario where you need to access a custom field attached directly to your menu item – you can retrieve the ID of the menu item using the `id` attribute.

Below we'll get a label override field attached to our menu [using ACF](https://www.advancedcustomfields.com/resources/adding-fields-menus/) – falling back to the default menu label if the field is empty.

```php
# Blade
{{ get_field('custom_nav_label', $item->id) ?: $item->label }}

# PHP
<?php echo get_field('custom_nav_label', $item->id) ?: $item->label; ?>
```

## Example Output

When calling `build()`, Navi will parse the passed navigation menu and return a fluent container containing your menu items. To return an array of objects, simply call `->toArray()`.

By default, `build()` calls `primary_navigation` which is the default menu theme location on Sage.

```php
array [
  5 => {
    +"active": true
    +"activeAncestor": false
    +"activeParent": false
    +"classes": "example"
    +"dbId": 5
    +"description": false
    +"id": 5
    +"label": "Home"
    +"objectId": "99"
    +"parent": false
    +"slug": "home"
    +"target": "_blank"
    +"title": false
    +"url": "https://sage.test/"
    +"xfn": false
    +"order": 1
    +"parentObjectId": false
    +"children": false
  }
  6 => {
    +"active": false
    +"activeAncestor": false
    +"activeParent": false
    +"classes": false
    +"dbId": 6
    +"description": false
    +"id": 6
    +"label": "Sample Page"
    +"objectId": "100"
    +"parent": false
    +"slug": "sample-page"
    +"target": false
    +"title": false
    +"url": "https://sage.test/sample-page/"
    +"xfn": false
    +"order": 2
    +"parentObjectId": false
    +"children": array [
      7 => {
        +"active": false
        +"activeAncestor": false
        +"activeParent": false
        +"classes": false
        +"dbId": 7
        +"description": false
        +"id": 7
        +"label": "Example"
        +"objectId": "101"
        +"parent": 6
        +"slug": "example"
        +"target": false
        +"title": false
        +"url": "#"
        +"xfn": false
        +"order": 3
        +"parentObjectId": 100
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

If you discover a bug in Navi, please [open an issue](https://github.com/Log1x/navi/issues).

## Contributing

Contributing whether it be through PRs, reporting an issue, or suggesting an idea is encouraged and appreciated.

## License

Navi is provided under the [MIT License](LICENSE.md).
