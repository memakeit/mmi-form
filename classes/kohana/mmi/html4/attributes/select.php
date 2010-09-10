<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML4 select attributes.
 *
 * @package		MMI Form
 * @category	HTML4
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_HTML4_Attributes_Select
{
	/**
	 * @var array HTML4 select attributes (including events)
	 */
	protected static $_attributes;

	/**
	 * @var array HTML4 select-specific attributes
	 */
	public static $_attr_select = array
	(
		'disabled',
		'multiple',
		'name',
		'size',
	);

	/**
	 * Get the valid HTML4 select attributes.
	 *
	 * @return	array
	 */
	public static function get()
	{
		(self::$_attributes === NULL) AND self::$_attributes = array_values(array_unique(array_merge
		(
			MMI_HTML4_Attributes::get(),
			self::$_attr_select
		)));
		return self::$_attributes;
	}
} // End Kohana_MMI_HTML4_Attributes_Select
