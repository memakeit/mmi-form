# MMI Form Module

This module is for generating form markup in either HTML4 or HTML5.
By default, HTML5 elements and attributes are generated.

Notable features of this module include:

* included plugins:
    * CSRF
    * reCAPTCHA
	* a jQuery form validation
* included filters:
	* an HTML Purifier filter

## Dependencies

* [mmi-core](http://github.com/memakeit/mmi-core) (only used in the test controllers)

## Usage

## Test Controllers
Simple test controllers are located in `classes/controller/mmi/form/test`.


## Notes

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
