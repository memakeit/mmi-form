<?php defined('SYSPATH') or die('No direct script access.');

// Field-specific configuration
return array
(
	'button' => MMI_Form_Field::defaults(MMI_Form_Field::DEFAULTS_FIELD_ONLY),
	'checkbox' => MMI_Form_Field::defaults(MMI_Form_Field::DEFAULTS_CHECKABLE),
	'datalist' => array
	(
		'_before' => PHP_EOL,
		'_after' => PHP_EOL,
		'_order' => array(MMI_Form::ORDER_FIELD),
	),
	'hidden' => MMI_Form_Field::defaults(MMI_Form_Field::DEFAULTS_FIELD_ONLY),
	'radio' => MMI_Form_Field::defaults(MMI_Form_Field::DEFAULTS_CHECKABLE),
	'keygen' => MMI_Form_Field::defaults(MMI_Form_Field::DEFAULTS_NO_ERROR),
	'meter' => MMI_Form_Field::defaults(MMI_Form_Field::DEFAULTS_NO_ERROR),
	'output' => MMI_Form_Field::defaults(MMI_Form_Field::DEFAULTS_NO_ERROR),
	'progress' => MMI_Form_Field::defaults(MMI_Form_Field::DEFAULTS_NO_ERROR),
	'reset' => MMI_Form_Field::defaults(MMI_Form_Field::DEFAULTS_FIELD_ONLY),
	'select' => array
	(
		'_filters' => array(),
	),
	'submit' => array
	(
		'_before' => '<div class="submit">',
		'_after' => '</div>',
		'_order' => array(MMI_Form::ORDER_FIELD),
	),
	'textarea' => array
	(
		'_before' => '<br />'.PHP_EOL,
		'_after' => PHP_EOL.'</div>',
		'_order' => array(MMI_Form::ORDER_LABEL, MMI_Form::ORDER_ERROR, MMI_Form::ORDER_FIELD),
		'_error' => array('_after' => ''),
		'cols' => 80,
		'rows' => 8,
	),
);
