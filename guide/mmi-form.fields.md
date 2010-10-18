# Fields

Fields are added to the form with the `add_field($field, $options)` method.

* The first parameter is a field name or a `MMI_Form_Field` object.
* The second parameter is the field options. Normally the options are an associative array
of attributes and meta information. If a scalar value is specified, it is converted to
an array. The options are ignored if a `MMI_Form_Field` object is specified as the first
parameter.

The following code shows the two ways a text input can be added to a form.

The field object is created via the form.

	$form = MMI_Form::factory(array
	(
		'_auto_validate' => TRUE,
		'_namespace' => 'mmi',
		'id' => 'form1',
	));
	$form->add_field('text', array
	(
		'_label' => 'First Name',
		'_namespace' => 'mmi',
		'id' => 'text1',
	));
	$form->add_submit('Testing Text Field');

The field object is explicitly created.

	$form = MMI_Form::factory(array
	(
		'_auto_validate' => TRUE,
		'_namespace' => 'mmi',
		'id' => 'form1',
	));
	$field = MMI_Form_Field::factory('text', array
	(
		'_label' => 'First Name',
		'_namespace' => 'mmi',
		'id' => 'text1',
	));
	$form->add_field($field);
	$form->add_submit('Testing Text Field');

The HTML output is the same for both.

	<form action="/memakeit/mmi/form/test/form/test" method="post" id="mmi_form1" accept-charset="utf-8" class="frm">
	<div id="mmi_frm_status" class="msg"></div>

	<div>
		<label class="lbl" for="mmi_text1">First Name</label>:
		<input type="text" id="mmi_text1" name="mmi_text1" value="" class="fld" />
		<label class="lbl error" for="mmi_text1"></label>
	</div>

	<div class="submit"><input type="submit" name="1287362847363" value="Testing Text Field" class="fld" /></div>
	</form>

## Configuration

The form configuration file is named `mmi-form-field.php`.

The meta options are:

* `_after` (string) the HTML to display after the field
* `_before` (string) the HTML to display before the field
* `_callbacks` (array) the validation callbacks
* `_error` (array) specifies error label attributes and the HTML to display before and after the
error label tag
* `_filters` (array) the validation filters
* `_label` (string|array) if an array, specifies label attributes and the HTML to display before
and after the label tag. if a string, specifies the label HTML
* `_namespace` (string) used as a prefix to the field id and name
* `_order` (array) the order in which the field and its label and errors are displayed
* `_rules` (array) the validation rules

Additional meta attributes are available for the `button`, `datalist`, `html`, `select`,
and `textarea` fields.
