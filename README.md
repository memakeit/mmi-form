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

## Usage: Form Creation

## Usage: Field Creation

## Usage: Plugins


## Notes

**The Accept Attribute**

**The Pattern Attribute**

**The Step Attribute**

**jQuery Rules Generated from Field Class Names**

**Checkbox and Radio Button Groups**


## Configuration

**Forms**

**Fields**

**Plugins**


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

##Links
