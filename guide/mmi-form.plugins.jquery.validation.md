# jQuery Validation Plugin

Usage of the jQuery validation plugin is slightly more involved. It is accomplished by:

1. adding the [jQuery library](http://docs.jquery.com/Downloading_jQuery#Download_jQuery) to
the page

	`echo HTML::script('<your path>/jquery-1.4.2.min.js').PHP_EOL;`

2. adding the [validation plugin](http://bassistance.de/jquery-plugins/jquery-plugin-validation/)
to the page. Either `jquery.validate.min.js` or `jquery.validate.js` can be used. Both are located
in the media directory.

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
		'required' => 'required'
	));`

6. adding a submit button

	`$form->add_submit();`

7. generating the validation JavaScript

	`$js = $form->jqv_get_validation_js();`

8. including the validation JavaScript in the page

	`echo '<script type="text/javascript">'.$js.'</script>';`

## Configuration

The configuration file for the jQuery validation plugin is named
`mmi-form-plugin-jquery-validation.php`.

An array of plugin [options](http://docs.jquery.com/Plugins/Validation/validate#toptions) can be
specified using the `options` key. Reasonable defaults are supplied by the
`MMI_Form_Plugin_JQuery_Validation::get_default_config($debug, $error_class, $valid_class)` method.

When specified ,the plugin will validate fields using Unicode ranges. The Unicode ranges
are specified using the `unicode` key. Reasonable defaults are supplied by the
`MMI_Form_Plugin_JQuery_Validation::get_default_unicode_ranges()` method.

## Notes

### Custom Messages

A custom validation message can be specified using the field's `title` attribute.

### The Accept Attribute

The implementation of the `file` input's `accept` attribute varies by browser.
The most common implementation is no implementation. If a MIME type is specified, Opera 10
filters the files displayed in the open file dialog. However, MIME types break the jQuery
validation of the `accept` attribute. If a MIME type is specified, jQuery validation
is disabled. The plugin correctly validates the `accept` attribute when a file extension
(`pdf`, `gif|png`, etc) is specified
(see [plugin documentation](http://docs.jquery.com/Plugins/Validation/Methods/accept)).

### The Pattern Attribute

The implementation of the `pattern` attribute also varies by browser. Chrome 6 and Opera 10
both support in browser `pattern` validation. If the pattern is `\d+`, Chrome 6 requires an
input value of all digits (interpreting `\d+` as `^\d+$`). On the other hand, Opera 10 requires
a value that contains 1 or more digits but that can contain other characters. The jQuery plugin
uses the same logic as Opera 10.

To ensure consistent validation, use `^` and `$` to mark the start and end of the string
being validated.



### Rules Generated from Field Class Names

The jQuery form validation generates rules from a field's CSS classes.
The following CSS classes generate validation rules:

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

## Test Controllers

A test controller is located in `classes/controller/mmi/form/test/plugin/jquery`.
