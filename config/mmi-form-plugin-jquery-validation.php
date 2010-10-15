<?php defined('SYSPATH') or die('No direct script access.');

// jQuery validation plugin configuration
return array
(
	'options' => MMI_Form_Plugin_JQuery_Validation::get_default_config(TRUE, 'error', 'success'),
	'unicode' => MMI_Form_Plugin_JQuery_Validation::get_default_unicode_ranges(),
);
