<?php defined('SYSPATH') or die('No direct script access.');

// MMI form configuration
return array
(
	'_field' => array
	(
		'_filters' => array
		(
			'trim' => NULL,
		),
		'_before' => '<br />',
		'_order' => array(MMI_Form::ORDER_LABEL, MMI_Form::ORDER_FIELD, MMI_Form::ORDER_ERROR),
		'class' => 'mmi',
	),

	'_group' => array
	(
		'_before' => '',
		'_after' => '</div>',
		'_filters' => array(),
		'_order' => array(MMI_Form::ORDER_LABEL, MMI_Form::ORDER_ERROR, MMI_Form::ORDER_FIELD),
		'_label' => array
		(
			'_before' => '<div class="mmi group checkable">',
			'_after' => '',
		),
		'_error' => array('_after' => '<br />'),
		'_item'=> array
		(
			'_before' => '',
			'_after' => '',
			'_label' => array('_before' => '', '_after' => '<br />'),
			'_order' => array(MMI_Form::ORDER_FIELD, MMI_Form::ORDER_LABEL),
			'class' => 'group',
		),
	),

	'_error' => array
	(
		'_before' => '',
		'_after' => PHP_EOL.'</div>',
		'class' => 'error',
	),
	'_fieldset' => array
	(
		'class' => 'fs',
	),
	'_label' => array
	(
		'_before' => '<div class="mmi">'.PHP_EOL,
		'_after' => '',
		'class' => 'lbl',
	),
	'_open' => array
	(
		'_before' => '',
		'_after' => '',
	),
	'_close' => array
	(
		'_before' => '',
		'_after' => '',
	),
	'_messages' => array
	(
		'_success' => array
		(
			'_msg' => 'Your request has been processed.',
			'class' => 'success',
		),
		'_failure' => array
		(
			'_msg_general' => 'There was a problem processing your request. Please try again.',
			'_msg_single' => '1 field is invalid. It has been highlighted.',
			'_msg_multiple' => '%d fields are invalid. They have been highlighted.',
			'class' => 'error',
		),
		'_file' => 'validate',
		'_translate' => FALSE,
		'class' => 'msg',
		'id' => 'mmi_frm_status',
	),

	'_auto_validate'	=> FALSE,
	'_html5'			=> TRUE,
	'_required_symbol'	=> array
	(
		'_html' => '&nbsp;<em>(required)</em>',
		'_placement' => MMI_Form::REQ_SYMBOL_AFTER,
	),
	'_show_messages' 	=> TRUE,
	'class'				=> 'mmi',
);
