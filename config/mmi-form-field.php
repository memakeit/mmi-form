<?php defined('SYSPATH') or die('No direct script access.');

// Field-specific configuration
return array
(
	'_defaults' => array
	(
		'_order' => array(MMI_Form::ORDER_LABEL, MMI_Form::ORDER_FIELD, MMI_Form::ORDER_ERROR),
		'class' => 'fld',
	),
	'button' => array
	(
		'_before' => '<div>',
		'_after' => '</div>',
		'_order' => array(MMI_Form::ORDER_FIELD),
	),
	'checkbox' => array
	(
		'_group' => array
		(
			'_before' => '<div class="cbg">',
			'_after' => '</div>',
			'_order' => array(MMI_Form::ORDER_FIELD, MMI_Form::ORDER_LABEL),
			'_field'=> array
			(
				'_before' => '',
				'_after' => '',
			),
			'_error' => array(),
			'_label' => array
			(
				'_before' => '',
				'_after' => '<br />',
			),
		)
	),
	'datalist' => array
	(
		'_before' => PHP_EOL,
		'_after' => PHP_EOL,
		'_order' => array(MMI_Form::ORDER_FIELD),
	),
	'hidden' => array
	(
		'_before' => '<div>',
		'_after' => '</div>',
		'_order' => array(MMI_Form::ORDER_FIELD),
	),
	'radio' => array
	(
		'_group' => array
		(
			'_before' => '<div class="rbg">',
			'_after' => '</div>',
			'_order' => array(MMI_Form::ORDER_FIELD, MMI_Form::ORDER_LABEL),
			'_field' => array
			(
				'_before' => '',
				'_after' => '',
			),
			'_error' => array(),
			'_label' => array
			(
				'_before' => '',
				'_after' => '<br />',
			)
		)
	),
	'keygen' => array
	(
		'_after' => PHP_EOL.'</div>',
		'_order' => array(MMI_Form::ORDER_LABEL, MMI_Form::ORDER_FIELD),
	),
	'meter' => array
	(
		'_after' => PHP_EOL.'</div>',
		'_order' => array(MMI_Form::ORDER_LABEL, MMI_Form::ORDER_FIELD),
	),
	'output' => array
	(
		'_after' => PHP_EOL.'</div>',
		'_order' => array(MMI_Form::ORDER_LABEL, MMI_Form::ORDER_FIELD),
	),
	'progress' => array
	(
		'_after' => PHP_EOL.'</div>',
		'_order' => array(MMI_Form::ORDER_LABEL, MMI_Form::ORDER_FIELD),
	),
	'reset' => array
	(
		'_before' => '<div>',
		'_after' => '</div>',
		'_order' => array(MMI_Form::ORDER_FIELD),
	),
	'submit' => array
	(
		'_before' => '<div>',
		'_after' => '</div>',
		'_order' => array(MMI_Form::ORDER_FIELD),
	),
	'textarea' => array
	(
		'_before' => '<br />'.PHP_EOL,
		'_after' => PHP_EOL.'</div>',
		'_error' => array('_after' => ''),
		'_order' => array(MMI_Form::ORDER_LABEL, MMI_Form::ORDER_ERROR, MMI_Form::ORDER_FIELD),
		'cols' => 80,
		'rows' => 8
	),
);
