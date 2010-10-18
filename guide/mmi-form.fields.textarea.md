# Textarea Fields

Textarea fields provide the following methods to get and set meta options.

* `double_encode($value)`
* `text($value)`

If no parameter is provided, the current value is returned.
These methods correspond to the meta options listed below.

## Configuration

Textarea fields support the following additional meta options:

* `_double_encode` (bool) encode existing HTML characters?
* `_text` (string) the text to display between the opening and closing textarea tags

## Test Controllers

A test controller is located in `classes/controller/mmi/form/test/field/textarea`.
