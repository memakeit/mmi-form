<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML4 form attributes.
 *
 * @package		MMI Form
 * @category	HTML4
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_HTML4_Attributes_Form
{
	/**
	 * @var array HTML4 form attributes (including events)
	 */
	protected static $_attributes;

	/**
	 * @var array HTML4 form-specific attributes
	 */
	public static $_attr_form = array
	(
		'accept',
		'accept-charset',
		'action',
		'enctype',
		'method',
		'name',
		'target',
	);

	/**
	 * Get the valid HTML4 form attributes.
	 *
	 * @return	array
	 */
	public static function attributes()
	{
		(self::$_attributes === NULL) AND self::$_attributes = array_values(array_unique(array_merge
		(
			MMI_HTML4_Attributes::get(),
			self::$_attr_form
		)));
		return self::$_attributes;
	}
} // End Kohana_MMI_HTML4_Attributes_Form
