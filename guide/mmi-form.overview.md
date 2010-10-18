# Overview

The form, field, fieldset, and label objects can be configured using the `attribute` and
`meta` methods. Both methods are used to get and set options.

The `attribute` method gets and sets HTML attributes for a tag.

* `$field->attribute('id', 'text1')` sets the id attribute
* `$field->attribute('id')` gets the id attribute
* `$field->attribute()` gets all attributes

The `meta` method gets and sets meta options for an object.

* `$field->meta('label', 'text field 1')` sets the label
* `$field->meta('label')` gets the label
* `$field->meta()` gets all meta options

**Note:** When using the `meta` method, the name does _not_ include an underscore `_` prefix.
However when passing in an array of options to a constructor or factory method, an underscore
prefix is necessary to distinguish meta settings from attributes.

## Configuration

Configuration of forms and fields is done using an associative array of options.

Option names that start with an underscore (`_namespace`, `_rules`, etc) are used as
meta information.

Options that do _not_ start with an underscore (`class`, `id`, etc) are HTML attributes.
Whether an HTML attribute is output depends on whether it is valid for the input type
and HTML version. The HTML version is specified using the `_html5` form option.

## Workflow

* create a form object (essential)
* add plugins (optional)
* add form fields (highly recommended)
* validate and/or render the form

The following code demonstrates a typical workflow.

	$form = MMI_Form::factory(array
	(
		'_auto_validate' => FALSE,
		'_namespace' => 'mmi',
		'id' => 'form1',
	));
	$form->add_field('text', array
	(
		'_label' => 'First Name',
		'_namespace' => 'mmi',
		'id' => 'text1',
		'required' => 'required',
	));
	$form->add_submit('Testing ...');

	if ($_POST)
	{
		if ($form->valid())
		{
			$form->reset();
		}
		else
		{
			// Invalid logic here
		}
	}
	echo $form->render();
