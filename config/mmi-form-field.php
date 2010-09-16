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
				'_after' => '<br/>',
			),
		)
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
				'_after' => '<br/>',
			)
		)
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
		'cols' => 80,
		'rows' => 8
	),
);
