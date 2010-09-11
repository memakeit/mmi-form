<?php defined('SYSPATH') or die('No direct script access.');

echo
	$before.
	'<button'.HTML::attributes($attributes).'>'
	.HTML::chars($text, $double_encode).
	'</button>'
	.$after;
