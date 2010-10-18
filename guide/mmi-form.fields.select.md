# Select Fields

Select fields provide the following methods to get and set meta options. These methods
also work with the datalist control.

* `blank_option($value)` not available for datalists
* `options($value)`
* `selected($value)`

If no parameter is provided, the current value is returned.
These methods correspond to the meta options listed below.

In addition, the following methods are available to add and remove options.

* `add_option($value, $name)`
* `clear_options()`
* `remove_option($value)`

## Configuration

Select fields support the following additional meta options:

* `_blank_option` (bool|string) if a string, use it as the blank option name; if true, use an empty
string as the blank option name
* `_choices` (bool) an associative array of value => name pairs; if the name is an array, an
`optgroup` is added
* `_selected` (array|string) the selected option value(s)

## Test Controllers

Test controllers are located in:

* `classes/controller/mmi/form/test/field/select`
* `classes/controller/mmi/form/test/field/datalist`
