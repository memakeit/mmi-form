<?php defined('SYSPATH') or die('No direct script access.');

echo
	$before.
	'<output'.HTML::attributes($attributes).'>'.
	HTML::chars($text, $double_encode).
	'</output>'.
	$after
;

