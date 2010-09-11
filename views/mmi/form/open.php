<?php defined('SYSPATH') or die('No direct script access.');

echo
	PHP_EOL.PHP_EOL.$before.PHP_EOL.
	'<!-- begin form -->'.PHP_EOL.
	Form::open($action, $attributes).PHP_EOL.
	$after.PHP_EOL
;
