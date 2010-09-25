<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML5 progress attributes.
 *
 * @package		MMI Form
 * @category	HTML5
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_HTML5_Attributes_Progress
{
	/**
	 * @var array HTML5 progress attributes (including events)
	 */
	protected static $_attributes;

	/**
	 * @var array HTML5 progress-specific attributes
	 */
	public static $_attr_progress = array
	(
		'max',
		'value',
	);

	/**
	 * Get the valid HTML5 progress attributes.
	 *
	 * @return	array
	 */
	public static function get()
	{
		(self::$_attributes === NULL) AND self::$_attributes = array_values(array_unique(array_merge
		(
			MMI_HTML5_Attributes::get(),
			self::$_attr_progress
		)));
		return self::$_attributes;
	}
} // End Kohana_MMI_HTML5_Attributes_Progress
