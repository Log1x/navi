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
