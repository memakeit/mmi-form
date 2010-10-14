# MMI Form Module

This module is for generating form markup in either HTML4 or HTML5.
By default, HTML5 elements and attributes are generated.

The advantages of using this module include:

* in browsers that do not support the new input types (email, tel, url, etc) or new input
attributes (pattern, required, etc) server-side validation rules are *automatically* generated
* with the jQuery form validation plugin enabled, client-side validation is generated using the
server-side validation rules

This module provides the following plugins:

* CSRF
* reCAPTCHA
* jQuery form validation

This module provides the following filters:

* an HTML Purifier filter (requires shadowhand's [purifier](http://github.com/shadowhand/purifier) module)

## Dependencies

* [mmi-core](http://github.com/memakeit/mmi-core) (only for the test controllers)

## Workflow

* create a form object (essential)
* add plugins (optional)
* add form fields (highly recommended)
* validate and/or render the form

## Usage:

## Notes

**Namespaces**


## Configuration

## Test Controllers
Test controllers are located in `classes/controller/mmi/form/test`.



classRuleSettings: {
	required: {required: true},
	email: {email: true},
	url: {url: true},
	date: {date: true},
	dateISO: {dateISO: true},
	dateDE: {dateDE: true},
	number: {number: true},
	numberDE: {numberDE: true},
	digits: {digits: true},
	creditcard: {creditcard: true}
},
