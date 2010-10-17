# MMI Form Module

This module is for generating form markup in either HTML4 or HTML5.
By default, HTML5 elements and attributes are generated.

**Features include:**

* forms and form fields are easy to create and configure
* checkbox and radio button groups are automatically created when an array of choices is
specified
* CSRF and CAPTCHA functionality can each be implemented with 1 line of code.
* in browsers that do not support the new input types (email, tel, url, etc) or new input
attributes (pattern, required, etc), the corresponding server-side validation rules are
generated
* with the jQuery validation plugin enabled, client-side validation script is generated
from the server-side validation rules
* both client and server-side messages are extracted from the same message file
* HTML Purifier is integrated as a filter
* custom validation rules to support server-side validation of the `min`, `max`, and
`step` attributes

## Dependencies

* [mmi-core](http://github.com/memakeit/mmi-core) (only for the test controllers)
* shadowhand's [purifier](http://github.com/shadowhand/purifier) (if using the purify filter)

## Documentation

Further documentation is found in the `guide` directory.

## Test Controllers

Test controllers are located in `classes/controller/mmi/form/test`.

## Inspiration &amp; Credits

* the [Kohana 3 PHP Framework](http://github.com/kohana)
* the jQuery form validation [plugin](http://docs.jquery.com/Plugins/Validation)
* shadowhand's [purifier](http://github.com/shadowhand/purifier) module
* bmidget's [kohana-formo](http://github.com/bmidget/kohana-formo) module is another great option
for Kohana 3 form generation
