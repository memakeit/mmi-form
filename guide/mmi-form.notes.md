# Notes

## The Accept Attribute

The implementation of the `file` input's `accept` attribute varies by browser.
The most common implementation is no implementation. Opera 10 filters the files displayed
in the open file dialog when a MIME type is specified. However, MIME types break the jQuery
plugin's validation of the `accept` attribute. If a MIME type is specified, jQuery validation
is disabled. The jQuery plugin correctly validates the `accept` attribute when a file extension
(ex: `pdf` or `gif|png`) is specified
(see [plugin documentation](http://docs.jquery.com/Plugins/Validation/Methods/accept)).

## The Pattern Attribute

The implementation of the `pattern` attribute also varies by browser. Chrome 6 and Opera 10
both support in browser validation of the `pattern` attribute. If the pattern is `\d+`,
Chrome 6 requires an input value of all digits (interpreting `\d+` as `^\d+$`). Opera 10
requires an input value that contains 1 or more digits but that can contain other characters.
The jQuery plugin uses the same logic as Opera 10 for validation. To ensure consistent
validation, use the `^` and `$` to mark the start and end of the string being validated.

## The Min, Max, and Step Attributes

The `date`, `datetime`, `datetime-local`, and `week` input types support server-side
validation of the `step` attribute _only if the value can be converted to a timestamp_.
The UNIX timestamp range is from 13 Dec 1901 20:45:54 UTC to 19 Jan 2038 03:14:07 UTC
(see [Year 2038 Problem](http://en.wikipedia.org/wiki/Year_2038_problem)). Dates outside
this range support `min` and `max` server-side validation (using `DateTime` objects),
but `step` validation is _not implemented_. Since the `month` and `time` input types do not
use timestamps for calculations, they support server-side validation of the `step` attribute
for all values.

The `numeric` and `range` input types support server-side validation of the `min`, `max`, and
`step` attributes for all values.

## jQuery Rules Generated from Field Class Names

The jQuery form validation [plugin](http://docs.jquery.com/Plugins/Validation) automatically
generates rules from a field's CSS classes. The following CSS classes generate validation rules:

* `creditcard` => {creditcard: true}
* `date` => {date: true},
* `dateDE` => {dateDE: true},
* `dateISO` => {dateISO: true},
* `digits` => {digits: true},
* `email` => {email: true},
* `number` => {number: true},
* `numberDE` => {numberDE: true},
* `required` => {required: true},
* `url` => {url: true},

## Checkbox and Radio Button Groups

While thought was given to wrapping checkbox and radio button groups in `fieldset` tags, they are
wrapped in `div` tags.
