<?php defined('SYSPATH') or die('No direct script access.');

// jQuery validation plugin configuration
return array
(
	'options' => MMI_Form_Plugin_JQuery_Validation::get_default_config(TRUE, 'error', 'success'),
	'unicode' => array
	(
		'dashes'		=> '\u002d\u00ad',
		'letters'		=> '\u0041-\u005a\u0061-\u007a\u00aa\u00b5\u00ba\u00c0-\u00d6\u00d8-\u00f6\u00f8-\u024f',
		'numbers'		=> '\u0030-\u0039\u00b2\u00b3\u00b9\u00bc-\u00be',
		'spaces'		=> '\u0020\u00a0',
		'underscores'	=> '\u0029\u005d\u007d',
	),
);
