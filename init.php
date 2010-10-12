<?php defined('SYSPATH') or die('No direct script access.');

// Test routes
if (Kohana::$environment !== Kohana::PRODUCTION)
{
	Route::set('mmi/form/test/field', 'mmi/form/test/field/<controller>(/<action>)')
	->defaults(array
	(
		'directory' => 'mmi/form/test/field',
	));
	Route::set('mmi/form/test/form', 'mmi/form/test/form/<controller>(/<action>)')
	->defaults(array
	(
		'directory' => 'mmi/form/test/form',
	));
	Route::set('mmi/form/test/filter', 'mmi/form/test/filter/<controller>(/<action>)')
	->defaults(array
	(
		'directory' => 'mmi/form/test/filter',
	));
	Route::set('mmi/form/test/html4', 'mmi/form/test/html4/<controller>(/<action>)')
	->defaults(array
	(
		'directory' => 'mmi/form/test/html4',
	));
	Route::set('mmi/form/test/html5', 'mmi/form/test/html5/<controller>(/<action>)')
	->defaults(array
	(
		'directory' => 'mmi/form/test/html5',
	));
	Route::set('mmi/form/test/plugin', 'mmi/form/test/plugin/<controller>(/<action>)')
	->defaults(array
	(
		'directory' => 'mmi/form/test/plugin',
	));
}
