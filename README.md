# MMI Form Module

This module is for generating form markup in either HTML4 or HTML5.
By default, HTML5 elements and attributes are generated.

This module provides the following plugins:

* CSRF
* reCAPTCHA
* jQuery form validation

This module provides the following filters:
	* an HTML Purifier filter (requires shadowhand's [purifier](http://github.com/shadowhand/purifier.git) module)

## Dependencies

* [mmi-core](http://github.com/memakeit/mmi-core) (only for the test controllers)

## Workflow

* create a form object
* add plugins
* add form fields
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
