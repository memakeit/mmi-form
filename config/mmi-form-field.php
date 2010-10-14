<?php defined('SYSPATH') or die('No direct script access.');

// Field-specific configuration
$checkable = array
(
	'_label' => array('_after' => '', '_before' => ''),
	'_order' => array(MMI_Form::ORDER_FIELD, MMI_Form::ORDER_LABEL, MMI_Form::ORDER_ERROR),
);
$no_error = array
(
	'_after' => PHP_EOL.'</div>',
	'_order' => array(MMI_Form::ORDER_LABEL, MMI_Form::ORDER_FIELD),
);
$simple = array
(
	'_before' => '<div>',
	'_after' => '</div>',
	'_order' => array(MMI_Form::ORDER_FIELD),
);

return array
(
	'button' => $simple,
	'checkbox' => $checkable,
	'datalist' => array
	(
		'_before' => PHP_EOL,
		'_after' => PHP_EOL,
		'_order' => array(MMI_Form::ORDER_FIELD),
	),
	'hidden' => $simple,
	'radio' => $checkable,
	'keygen' => $no_error,
	'meter' => $no_error,
	'output' => $no_error,
	'progress' => $no_error,
	'reset' => $simple,
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
