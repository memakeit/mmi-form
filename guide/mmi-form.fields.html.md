# HTML Fields

HTML fields provide two methods to set the HTML.

* `html($value)` where `$value` is a string
* `html_callback($value)` where `$value` is a string or an array representing a callback

## Configuration

HTML fields support the following additional meta options:

* `_html` (string|array) the HTML string or a callback to generate the HTML
* `_source` (string) the source (either a callback or a string)

## Test Controllers

A test controller is located in `classes/controller/mmi/form/test/field/html`.
