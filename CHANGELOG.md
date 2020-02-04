## v1.0.6 (01-04-2020)

### Bug fixes

- Fix the call to Facade in the Composer autoload. :(

## v1.0.5 (01-04-2020)

### Enhancements

- Move the menu builder methods into their own class.
- Refactor the main `Navi` class to extend `Fluent`.
- Add `isEmpty()` and `isNotEmpty()` helper methods to the main `Navi` class.
- Refactor how menu items are filtered and mapped.
- Set `empty` or `null` menu properties to `false`.
- Add `get()` method allowing you to retrieve the properties of the current menu object. (#4)
- Clean up documentation and add an example for `get()`.

### Breaking Changes

- Rename `NaviFacade` to `Navi` and move it into a Facades directory to avoid aliasing it. Please use `Log1x\Navi\Facades\Navi` instead.
- Change `object_id` and `db_id` to `objectId` and `dbId` respectively to remain uniform with the existing camalCased keys

## v1.0.4 (12-08-2019)

### Enhancements

- Add `db_id` and `object_id` (#8)

### Bug fixes

- Add missing `@param` and `@return` to docblocks

## v1.0.3 (09-26-2019)

### Enhancements

- Add missing classes to blacklist. (#3)

## v1.0.2 (09-07-2019)

### Enhancements

- **Breaking Change**: `build()` now returns a [fluent instance](https://laravel.com/api/master/Illuminate/Support/Fluent.html) instead of an array by default. To restore original functionality, simply append `->toArray()` at the end of your `build()`.
- Add `activeAncestor`, `activeParent`, `classes`, `title`, `description`, `target`, and `xfn` values to the item objects. (#2)
- Add `CHANGELOG.md`.

## v1.0.1 (07-04-2019)

### Enhancements

- Set `$item->children` to `[]` by default.

## v1.0.0 (06-20-2019)

Initial release
