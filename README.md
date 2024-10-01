# Navi

![Latest Stable Version](https://img.shields.io/packagist/v/log1x/navi.svg?style=flat-square)
![Total Downloads](https://img.shields.io/packagist/dt/log1x/navi.svg?style=flat-square)
![Build Status](https://img.shields.io/github/actions/workflow/status/log1x/navi/main.yml?branch=master&style=flat-square)

Hate the WordPress NavWalker? **Me too**.

Navi is a developer-friendly alternative to the NavWalker. Easily build your WordPress menus using an iterable object inside of a template/view.

## Requirements

- [PHP](https://secure.php.net/manual/en/install.php) >= 8.0

## Installation

### Bedrock (or Sage)

Install via Composer:

```bash
$ composer require log1x/navi
```

### Manual

Download the [latest release](https://github.com/Log1x/navi/releases/latest) `.zip` and install into `wp-content/plugins`.

## Usage

Building your menu can be done by passing your menu location to `Navi::make()->build()`:

```php
use Log1x\Navi\Navi;

$menu = Navi::make()->build('primary_navigation');
```

By default, `build()` uses `primary_navigation` if no menu location is specified.

Retrieving an array of menu items can be done using `all()`:

```php
if ($menu->isNotEmpty()) {
    return $menu->all();
}
```

> [!NOTE]
> Check out the [**examples**](examples) folder to see how to use Navi in your project.

### Menu Item Classes

By default, Navi removes the default WordPress classes from menu items such as `menu-item` and `current-menu-item` giving you full control over your menu markup while still passing through custom classes.

If you would like these classes to be included on your menu items, you may call `withDefaultClasses()` before building your menu:

```php
$menu = Navi::make()->withDefaultClasses()->build();
```

In some situations, plugins may add their own classes to menu items. If you would like to prevent these classes from being added, you may pass an array of partial strings to `withoutClasses()` match against when building.

```php
$menu = Navi::make()->withoutClasses(['shop-'])->build();
```

### Accessing Menu Object

When building the navigation menu, Navi retains the menu object and makes it available using the `get()` method.

By default, `get()` returns the raw [`wp_get_nav_menu_object()`](https://codex.wordpress.org/Function_Reference/wp_get_nav_menu_object) allowing you to access it directly.

```php
$menu->get()->name;
```

Optionally, you may pass a `key` and `default` to call a specific object key with a fallback when the value is blank:

```php
$menu->get('name', 'My menu title');
```

### Accessing Page Objects

If your menu item is linked to a page object (e.g. not a custom link) – you can retrieve the ID of the page using the `objectId` attribute.

Below is an example of getting the post type of the current menu item:

```php
$type = get_post_type($item->objectId)
```

### Accessing Custom Fields

In a scenario where you need to access a custom field attached directly to your menu item – you can retrieve the ID of the menu item using the `id` attribute.

Below we'll get a label override field attached to our menu [using ACF](https://www.advancedcustomfields.com/resources/adding-fields-menus/) – falling back to the default menu label if the field is empty.

```php
$label = get_field('custom_menu_label', $item->id) ?: $item->label;
```

### Acorn Usage

If you are using Navi alongside [Acorn](https://roots.io/acorn/) (e.g. Sage), you may generate a usable view component using Acorn's CLI:

```sh
$ wp acorn navi:make Menu
```

Once generated, you may use the [view component](https://laravel.com/docs/11.x/blade#components) in an existing view like so:

```php
<x-menu name="footer_navigation" />
```

To list all registered locations and their assigned menus, you can use the list command:

```sh
$ wp acorn navi:list
```

## Example Output

When calling `build()`, Navi will retrieve the WordPress navigation menu assigned to the passed location and build out an array containing the menu items.

An example of the menu output can be seen below:

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
    +"object": "page"
    +"objectId": "99"
    +"parent": false
    +"slug": "home"
    +"target": "_blank"
    +"title": false
    +"type": "post_type"
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
    +"object": "page"
    +"objectId": "100"
    +"parent": false
    +"slug": "sample-page"
    +"target": false
    +"title": false
    +"type": "post_type"
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
        +"object": "custom"
        +"objectId": "101"
        +"parent": 6
        +"slug": "example"
        +"target": false
        +"title": false
        +"type": "custom"
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

## Bug Reports

If you discover a bug in Navi, please [open an issue](https://github.com/Log1x/navi/issues).

## Contributing

Contributing whether it be through PRs, reporting an issue, or suggesting an idea is encouraged and appreciated.

## License

Navi is provided under the [MIT License](LICENSE.md).
