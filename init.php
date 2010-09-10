<?php defined('SYSPATH') or die('No direct script access.');

// Test routes
if (Kohana::$environment !== Kohana::PRODUCTION)
{
	Route::set('mmi/form/test/form', 'mmi/form/test/form/<controller>(/<action>)')
	->defaults(array
	(
		'directory'	=> 'mmi/form/test/form',
	));
	Route::set('mmi/form/test/html', 'mmi/form/test/html/<controller>(/<action>)')
	->defaults(array
	(
		'directory'	=> 'mmi/form/test/html',
	));
}
