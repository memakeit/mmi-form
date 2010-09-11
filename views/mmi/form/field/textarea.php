<?php defined('SYSPATH') or die('No direct script access.');

echo $before.'<textarea'.HTML::attributes($attributes).'>'.HTML::chars($text, $double_encode).'</textarea>'.$after;
