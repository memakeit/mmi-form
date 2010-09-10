<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML4 attributes.
 *
 * @package		MMI Form
 * @category	HTML4
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_HTML4_Attributes
{
	/**
	 * @var array HTML4 attributes (including events)
	 */
	protected static $_attributes;

	/**
	 * @var array HTML4 core attributes
	 */
	protected static $_attr_core = array
	(
		'accesskey',
		'class',
		'dir',
		'id',
		'lang',
		'style',
		'tabindex',
		'title',
	);

	/**
	 * Get the valid HTML4 attributes.
	 *
	 * @return	array
	 */
	public static function get()
	{
		(self::$_attributes === NULL) AND self::$_attributes = array_values(array_unique(array_merge
		(
			MMI_HTML4_Events::get(),
			self::$_attr_core
		)));
		return self::$_attributes;
	}
} // End Kohana_MMI_HTML4_Attributes
