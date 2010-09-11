<?php defined('SYSPATH') or die('No direct script access.');

echo
	$before.
	Form::select($name, $options, $selected, $attributes).
	$after
;
