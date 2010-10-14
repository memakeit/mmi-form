# MMI Form Module

This module is for generating form markup in either HTML4 or HTML5.
By default, HTML5 elements and attributes are generated.

The advantages of using this module include:

* forms and form fields are easy to create and configure
* checkbox and radio button groups are automatically created when an array of choices is
specified
* CSRF and CAPTCHA functionality can each be implemented with 1 line of code.
* in browsers that do not support the new input types (email, tel, url, etc) or new input
attributes (pattern, required, etc), the corresponding server-side validation rules are
*automagically* generated
* with the jQuery validation plugin enabled, client-side validation script is generated
from the server-side validation rules

## Dependencies

* [mmi-core](http://github.com/memakeit/mmi-core) (only for the test controllers)
* shadowhand's [purifier](http://github.com/shadowhand/purifier) (if using the purify filter)

## Workflow

* create a form object (essential)
* add plugins (optional)
* add form fields (highly recommended)
* validate and/or render the form

## Forms

**Configuration**


## Form Fields

**Configuration**


## Plugins

**Configuration**


## Notes

**The Accept Attribute**

The `file` input's `accept` attribute has different implementations depending on the browser. The
most common implementation is no implementation. Opera 10 filters the files displayed in the open
file dialog when a MIME type is specified. However, MIME types break the jQuery plugin's
validation of the `accept` attribute. If a MIME type is specified, jQuery validation is disabled.
The jQuery plugin correctly validates the `accept` attribute when a file extension (ex: `pdf` or
`gif|png`) is specified (see [plugin documentation](http://docs.jquery.com/Plugins/Validation/Methods/accept)).

**The Pattern Attribute**

**The Min, Max, and Step Attributes**

The `date`, `datetime`, `datetime-local`, and `week` input types support server-side
validation of the `step` attribute *only if the value can be converted to a timestamp*.
The UNIX timestamp range is from 13 Dec 1901 20:45:54 UTC to 19 Jan 2038 03:14:07 UTC
(see [Year 2038 Problem](http://en.wikipedia.org/wiki/Year_2038_problem)). Dates outside
this range support `min` and `max` server-side validation (using `DateTime` objects),
but `step` validation is *not implemented*. Since the `month` and `time` input types do not use
timestamps, they support server-side validation of the `step` attribute for all values.

The `numeric` and `range` input types support server-side validation of the `min`, `max`, and `step`
attributes.

**jQuery Rules Generated from Field Class Names**

The [jQuery form validation plugin](http://docs.jquery.com/Plugins/Validation) automatically
generates rules from a field's CSS classes. The following CSS classes generate validation rules:

* `required` => `{required: true}`,
* `email` => `{email: true}`,
* `url` => `{url: true}`,
* `date` => `{date: true}`,
* `dateISO` => `{dateISO: true}`,
* `dateDE` => `{dateDE: true}`,
* `number` => `{number: true}`,
* `numberDE` => `{numberDE: true}`,
* `digits` => `{digits: true}`,
* `creditcard` => `{creditcard: true}`

**Checkbox and Radio Button Groups**

While thought was given to wrapping checkbox and radio button groups in `fieldset` tags, they are
wrapped in `div` tags.


## Test Controllers
Test controllers are located in `classes/controller/mmi/form/test`.

## Inspiration &amp; Credits
