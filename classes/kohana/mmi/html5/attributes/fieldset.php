<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML5 fieldset attributes.
 *
 * @package		MMI Form
 * @category	HTML5
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_HTML5_Attributes_Fieldset
{
	/**
	 * @var array HTML5 fieldset attributes (including events)
	 */
	protected static $_attributes;

	/**
	 * @var array HTML5 fieldset-specific attributes
	 */
	public static $_attr_fieldset = array
	(
		'disabled',
		'form',
		'name',
	);

	/**
	 * Get the valid HTML5 fieldset attributes.
	 *
	 * @return	array
	 */
	public static function get()
	{
		(self::$_attributes === NULL) AND self::$_attributes = array_values(array_unique(array_merge
		(
			MMI_HTML5_Attributes::get(),
			self::$_attr_fieldset
		)));
		return self::$_attributes;
	}
} // End Kohana_MMI_HTML5_Attributes_Fieldset
