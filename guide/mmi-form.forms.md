# Forms

The most common method to create a form object is:

	$form = MMI_Form::factory($options);

The `options` parameter contains settings that are merged with those found in the
configuration file. Both attribute and meta settings (prefixed with an `_`) can be specified.
The `options` parameter is optional.

## Configuration

The form configuration file is named `mmi-form.php`.

The meta options are:

* `_auto_validate` (bool) when a form is posted , validate the fields prior to rendering?
* `_close` (array) used to specify HTML before and after the closing form tag
* `_error` (array) error defaults
* `_field` (array) field defaults
* `_fieldset` (array) fieldset defaults
* `_group` (array) group defaults
* `_html5` (bool) render HTML5 output?
* `_messages` (array) message settings
* `_namespace` (string) used as a prefix to the form id and name
* `_label` (array) label defaults
* `_open` (array) used to specify form attributes as well as HTML before and after the
opening form tag
* `_required_symbol` (array) the `_html` and `_placement` of the required symbol
* `_show_messages` (bool) show generated feedback messages?
