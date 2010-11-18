<?php defined('SYSPATH') or die('No direct script access.');

// MMI form jQuery validation plugin configuration
return array
(
	'options' => MMI_Form_Plugin_JQuery_Validation::get_default_config
	(
		Kohana::$environment !== Kohana::PRODUCTION,
		'error',
		'success'
	),
	'unicode' => MMI_Form_Plugin_JQuery_Validation::get_default_unicode_ranges(),
);
