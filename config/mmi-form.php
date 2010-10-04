<?php defined('SYSPATH') or die('No direct script access.');

// Form configuration
return array
(
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
		'_before' => '<div>'.PHP_EOL,
		'class' => 'lbl',
	),
	'_open' => array
	(
		'_before' => '_BEFORE_FORM_',
		'_after' => '',
		'class' => 'frm',
	),
	'_close' => array
	(
		'_before' => '',
		'_after' => '_AFTER_FORM_',
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
			'_msg' => array
			(
				'general' => 'There was a problem processing your request. Please try again.',
				'single' => '1 field is invalid. It has been highlighted.',
				'multiple' => '%d fields are invalid. They have been highlighted.',
			),
			'class' => 'error',
		),
		'_file' => 'validate',
		'_translate' => FALSE,
		'class' => 'msg',
		'id' => 'frm_status',
	),

	'_auto_validate'	=> FALSE,
	'_html5'			=> TRUE,
	'_required_symbol'	=> '<strong>*</strong>&nbsp;',
	'_show_messages' 	=> TRUE,
	'_unicode'			=> TRUE,
	'class'				=> 'frm',
);
