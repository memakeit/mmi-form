<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML5 textarea attributes.
 *
 * @package		MMI Form
 * @category	HTML5
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_HTML5_Attributes_Textarea
{
	/**
	 * @var array HTML5 textarea attributes (including events)
	 */
	protected static $_attributes;

	/**
	 * @var array HTML5 textarea-specific attributes
	 */
	public static $_attr_textarea = array
	(
		'autofocus',
		'cols',
		'disabled',
		'form',
		'maxlength',
		'name',
		'placeholder',
		'readonly',
		'required',
		'rows',
		'wrap',
	);

	/**
	 * Get the valid HTML5 textarea attributes.
	 *
	 * @return	array
	 */
	public static function get()
	{
		(self::$_attributes === NULL) AND self::$_attributes = array_values(array_unique(array_merge
		(
			MMI_HTML5_Attributes::get(),
			self::$_attr_textarea
		)));
		return self::$_attributes;
	}
} // End Kohana_MMI_HTML5_Attributes_Textarea
