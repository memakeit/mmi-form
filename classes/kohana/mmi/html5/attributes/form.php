<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML5 form attributes.
 *
 * @package		MMI Form
 * @category	HTML5
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_HTML5_Attributes_Form
{
	/**
	 * @var array HTML5 form attributes (including events)
	 */
	protected static $_attributes;

	/**
	 * @var array HTML5 form-specific attributes
	 */
	protected static $_attr_form = array
	(
		'accept-charset',
		'action',
		'autocomplete',
		'enctype',
		'method',
		'name',
		'novalidate',
		'target',
	);

	/**
	 * Get the valid HTML5 form attributes.
	 *
	 * @return	array
	 */
	public static function attributes()
	{
		(self::$_attributes === NULL) AND self::$_attributes = array_values(array_unique(array_merge
		(
			MMI_HTML5_Attributes::get(),
			self::$_attr_form
		)));
		return self::$_attributes;
	}
} // End Kohana_MMI_HTML5_Attributes_Form
