# jQuery Validation Plugin

Usage of the jQuery validation plugin is slightly more complicated. It is accomplished by:

1. adding the [jQuery library](http://docs.jquery.com/Downloading_jQuery#Download_jQuery) to
the page

	`echo HTML::script('<your path>/jquery-1.4.2.min.js').PHP_EOL;`

2. adding the [jQuery plugin](http://bassistance.de/jquery-plugins/jquery-plugin-validation/) to
the page (either `jquery.validate.min.js` or `jquery.validate.js` can be used; both are located
in the media directory)

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

## Configuration

The configuration file for the jQuery validation plugin is named
`mmi-form-plugin-jquery-validation.php`.

An array of plugin [options](http://docs.jquery.com/Plugins/Validation/validate#toptions) can be
specified using the `options` key. Reasonable defaults are supplied by the
`MMI_Form_Plugin_JQuery_Validation::get_default_config($debug, $error_class, $valid_class)` method.

If the form supports Unicode (set via the form's `unicode` meta property), the plugin will
validate form fields using Unicode ranges whenever possible. The Unicode ranges are specified
using the `unicode` key. Reasonable defaults are supplied by the
`MMI_Form_Plugin_JQuery_Validation::get_default_unicode_ranges()` method.
