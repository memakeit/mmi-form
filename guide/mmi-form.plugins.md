## Plugins

Plugins are added to the form with the `add_plugin($plugin, $method_prefix, $options)` method.
The first parameter is a plugin name or a `MMI_Form_Plugin` object. The second parameter is a
plugin prefix which is used to call plugin methods via the form (see the notes below). The
third parameter is an associative array of plugin options. The options are ignored if a
`MMI_Form_Plugin` object was specified as the first parameter. The following code shows the
two ways a CSRF plugin can be added to a form.

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

## Notes

A plugin method is called via the form using the plugin's prefix and method name. The following
code adds the jQuery validation plugin and specifies its prefix as 'jqv'.

	$form->add_plugin('jquery_validation', 'jqv');

The plugin's `get_validation_js()` method is invoked via a form method called
`jqv_get_validation_js()`. This form method name is derived from the following elements:

* the plugin prefix (`jqv`)
* an underscore (`_`)
* the plugin method name (`get_validation_js()`

Behind the scenes, the plugin prefix is used to retrieve the plugin object. Using the plugin
object, the method is called using reflection.

The following code will invoke the plugin's `get_validation_js()` method.

	$js = $form->jqv_get_validation_js();
