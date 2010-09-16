<?php defined('SYSPATH') or die('No direct script access.');

// jQuery validation plugin settings
function _get_jquery_options()
{
	$debug = TRUE;
	$error_class = 'error';
	$valid_class = 'success';

	return array
	(
		'debug'				=> $debug,
		'errorClass'		=> $error_class,
		'errorPlacement'	=> MMI_Form_Plugin_JQuery::get_default_error_placement(),
		'validClass'		=> $valid_class,
		'success'			=> MMI_Form_Plugin_JQuery::get_default_success(),
		'highlight'			=> MMI_Form_Plugin_JQuery::get_default_highlight(),
		'unhighlight'		=> MMI_Form_Plugin_JQuery::get_default_unhighlight(),
		'invalidHandler'	=> MMI_Form_Plugin_JQuery::get_default_invalid_handler(),
		'submitHandler'		=> MMI_Form_Plugin_JQuery::get_default_submit_handler(),
	);
}

return array
(
	'options' => _get_jquery_options(),
	'unicode'  => array
	(
		'dashes'		=> '\u002d\u00ad',
		'letters'		=> '\u0041-\u005a\u0061-\u007a\u00aa\u00b5\u00ba\u00c0-\u00d6\u00d8-\u00f6\u00f8-\u024f',
		'numbers'		=> '\u0030-\u0039\u00b2\u00b3\u00b9\u00bc-\u00be',
		'spaces'		=> '\u0020\u00a0',
		'underscores'	=> '\u0029\u005d\u007d',
	),
);
