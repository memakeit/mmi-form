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

Plugins are added to the form with the `add_plugin($plugin, $method_prefix, $options)` method.
The first parameter is a plugin name or a `MMI_Form_Plugin` object. The second parameter is a
plugin prefix which is used to call plugin methods via the form (see the notes about the
jQuery validation plugin for more details). The third parameter is an associative array of plugin
options. The options are ignored if a `MMI_Form_Plugin` object was specified as the first parameter.
The following code shows the two ways a CSRF plugin can be added to a form.

The plugin object is created by the form:

	$form = MMI_Form::factory(array
	(
		'_auto_validate' => TRUE,
		'_namespace' => 'mmi',
		'id' => 'form1',
	));
	$form->add_plugin('csrf', 'csrf', array
	(
		'_namespace' => 'mmi',
		'id' => 'token',
	));
	$form->add_submit('Testing CSRF');

The plugin object is explicitly created:

	$form = MMI_Form::factory(array
	(
		'_auto_validate' => TRUE,
		'_namespace' => 'mmi',
		'id' => 'form1',
	));
	$plugin = MMI_Form_Plugin::factory('csrf', array
	(
		'_namespace' => 'mmi',
		'id' => 'token',
	));
	$form->add_plugin($plugin, 'csrf');
	$form->add_submit('Testing CSRF');

The HTML output is the same for both:

	<form action="/memakeit/mmi/form/test/form/form" method="post" id="mmi_form1" accept-charset="utf-8" class="frm">
	<div id="mmi_frm_status" class="msg"></div>
	<div><input type="hidden" id="mmi_token" name="mmi_token" value="security4cb879ecbd780" class="fld" /></div>
	<div class="submit"><input type="submit" name="12871582528516" value="Testing CSRF" class="fld" /></div>
	</form>

### CAPTCHA Plugin

The form method `add_captcha($driver, $options)` is the easiest way to add a
[CAPTCHA](http://en.wikipedia.org/wiki/CAPTCHA) to a form. A driver for the Google reCAPTCHA
is included. The following PHP specifies a white theme and Spanish as the UI language.

	$form->add_captcha('recaptcha', array
	(
		'_lang' => 'es',
		'_theme' => 'white'
	));

It generates the following HTML.

	<script type="text/javascript">
	//<![CDATA[
		var RecaptchaOptions = { theme: 'white', lang: 'es' };
	//]]>
	</script>
	<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=XXXXXXXXXX"></script>
	<noscript>
		<iframe src="http://www.google.com/recaptcha/api/noscript?k=XXXXXXXXXX" height="300" width="500" frameborder="0"></iframe>
		<br/><textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
		<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
	</noscript>

The configuration file for the reCAPTCHA plugin is named `mmi-form-plugin-recaptcha.php`.
The most important reCAPTCHA options are:

* `_lang` the UI language
* `_private_key` overrides the private key in the config file
* `_public_key` overrides the public key in the config file
* `_theme` valid values are blackglass, clean, custom, red, and white
* `_use_ssl` submit the data using SSL?

### CSRF Plugin

The form method `add_csrf($id, $namespace)` is the easiest way to add
[cross-site request forgery](http://en.wikipedia.org/wiki/Cross-site_request_forgery)
protection to a form. The following PHP

	$form->add_csrf('csrf', 'mmi');

generates a hidden form field used by the CSRF plugin during validation.

	<div><input type="hidden" id="mmi_csrf" name="mmi_csrf" value="security4cb87d8ba5da0" class="fld" /></div>

**Configuration**

There is no separate configuration file for the CSRF plugin. The most important options are:

* `_namespace` used to generate the hidden form field id
* `id` used to generate  the hidden form field id

### jQuery Validation Plugin

Usage of the jQuery validation plugin is slightly more complicated. It is accomplished by:

1. adding the [jQuery library](http://docs.jquery.com/Downloading_jQuery#Download_jQuery) to the page
`echo HTML::script('<your path>/jquery-1.4.2.min.js').PHP_EOL;`

2. adding the jQuery plugin to the page (either `jquery.validate.min.js` or `jquery.validate.js`
can be used; both are located in the media directory)
`echo HTML::script('<your path>/jquery.validate.min.js').PHP_EOL;`

3. creating the form object
`$form = MMI_Form::factory();`

4. adding the plugin to the form
`$form->add_plugin('jquery_validation', 'jqv');`

5. adding fields to the form
`$form->add_field('text', array
(
	'_label' => 'Text 1',
	'_rules' => array('min_length' => array(5)),
	'id' => 'text1',
	'required' => 'required',
));`

6. adding a submit button
`$form->add_submit();`

7. generating the validation JavaScript
`$js = $form->jqv_get_validation_js();`

8. including the validation JavaScript in the page
`echo '<script type="text/javascript">'.$js.'</script>';`

**Notes**
A plugin method is called via the form using the plugin's prefix and method name. The following
code adds the jQuery validation plugin and specifies its prefix as 'jqv'.

	$form->add_plugin('jquery_validation', 'jqv');

To invoke the plugin's `get_validation_js()` method, a form method is called.
The name of the form method is the plugin prefix (`jqv`) + an underscore (`_`) +
the method name (`get_validation_js()`). Behind the scenes, the plugin prefix is used
to retrieve the plugin object. Using the plugin object, the method is called using reflection.

The following code will invoke the plugin's `get_validation_js()` method.

	$js = $form->jqv_get_validation_js();

**Configuration**
The configuration file for the jQuery validation plugin is named
`mmi-form-plugin-jquery-validation.php`.

An array of plugin [options](http://docs.jquery.com/Plugins/Validation/validate#toptions) can be
specified using the `options` key. Reasonable defaults are supplied by the
`MMI_Form_Plugin_JQuery_Validation::get_default_config($debug, $error_class, $valid_class)` method.

If the form supports Unicode (set via the form's `unicode` meta property), the plugin will
validate form fields using Unicode ranges whenever possible. The Unicode ranges are specified
using the `unicode` key. Reasonable defaults are supplied by the
`MMI_Form_Plugin_JQuery_Validation::get_default_unicode_ranges()` method.

## Notes

### The Accept Attribute

The implementation of the `file` input's `accept` attribute varies by browser.
The most common implementation is no implementation. Opera 10 filters the files displayed
in the open file dialog when a MIME type is specified. However, MIME types break the jQuery
plugin's validation of the `accept` attribute. If a MIME type is specified, jQuery validation
is disabled. The jQuery plugin correctly validates the `accept` attribute when a file extension
(ex: `pdf` or `gif|png`) is specified
(see [plugin documentation](http://docs.jquery.com/Plugins/Validation/Methods/accept)).

### The Pattern Attribute

The implementation of the `pattern` attribute also varies by browser. Chrome 6 and Opera 10
both support in browser validation of the `pattern` attribute. If the pattern is `\d+`,
Chrome 6 requires an input value of all digits (interpreting `\d+` as `^\d+$`). Opera 10
requires an input value that contains 1 or more digits but that can contain other characters.
The jQuery plugin uses the same logic as Opera 10 for validation. To ensure consistent
validation, use the `^` and `$` to mark the start and end of the string being validated.

### The Min, Max, and Step Attributes

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

### jQuery Rules Generated from Field Class Names

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

### Checkbox and Radio Button Groups

While thought was given to wrapping checkbox and radio button groups in `fieldset` tags, they are
wrapped in `div` tags.

## Test Controllers
Test controllers are located in `classes/controller/mmi/form/test`.

## Inspiration &amp; Credits

* the [Kohana 3 PHP Framework](http://github.com/kohana)
* the jQuery form validation [plugin](http://docs.jquery.com/Plugins/Validation)
* shadowhand's [purifier](http://github.com/shadowhand/purifier) module
* bmidget's [kohana-formo](http://github.com/bmidget/kohana-formo) module is another great option
for Kohana 3 form generation
