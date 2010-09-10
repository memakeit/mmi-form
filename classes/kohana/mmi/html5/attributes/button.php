<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML5 button attributes.
 *
 * @package		MMI Form
 * @category	HTML5
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_HTML5_Attributes_Button
{
	/**
	 * @var array HTML5 button attributes (including events)
	 */
	protected static $_attributes;

	/**
	 * @var array HTML5 button-specific attributes
	 */
	public static $_attr_button = array
	(
		'autofocus',
		'disabled',
		'form',
		'formaction',
		'formenctype',
		'formmethod',
		'formnovalidate',
		'formtarget',
		'name',
		'type',
		'value',
	);

	/**
	 * Get the valid HTML5 button attributes.
	 *
	 * @return	array
	 */
	public static function get()
	{
		(self::$_attributes === NULL) AND self::$_attributes = array_values(array_unique(array_merge
		(
			MMI_HTML5_Attributes::get(),
			self::$_attr_button
		)));
		return self::$_attributes;
	}
} // End Kohana_MMI_HTML5_Attributes_Button
