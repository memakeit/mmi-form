<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML4 input attributes.
 *
 * @package		MMI Form
 * @category	HTML4
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_HTML4_Attributes_Input
{
	/**
	 * @var array HTML4 input attributes (including events)
	 */
	protected static $_attributes = array();

	/**
	 * @var array HTML4 input types
	 */
	protected static $_types = array
	(
		'button',
		'checkbox',
		'file',
		'hidden',
		'image',
		'password',
		'radio',
		'reset',
		'submit',
		'text',
	);

	/**
	 * @var array HTML4 inputs that support the src attribute
	 */
	protected static $_attr_src = array('image');
//accept	attribute is only used with <input type="file">
//alt		attribute is only used with <input type="image">
//checked	attribute is used with <input type="checkbox"> or <input type="radio">
//disabled	attribute will NOT work with <input type="hidden">\
//maxlength	attribute is used with <input type="text"> or <input type="password">
//name
//readonly	attribute can be used with <input type="text"> or <input type="password">
//size		attribute: for <input type="text"> and <input type="password">, the size attribute defines the number of characters that should be visible. For all other input types, size defines the width of the input field in pixels.
//src		attribute is required with <input type="image">
//value		attribute can NOT be used with <input type="file">

	/**
	 * Get the valid HTML4 input field attributes.
	 *
	 * @param	string	the input type
	 * @return	array
	 */
	public static function get($type = 'text')
	{
		$type = strtolower(trim($type));
		if ( ! in_array($type, self::$_types))
		{
			$msg = 'Invalid HTML4 input type: '.$type;
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}
		if (isset(self::$_attributes[$type]))
		{
			return self::$_attributes[$type];
		}

		$attr_names = array
		(
			'src',
		);
		$custom = array();
		foreach ($attr_names as $name)
		{
			$var ='_attr_'.$name;
			if (in_array($type, self::$$var))
			{
				$custom[] = $name;
			}
		}
		self::$_attributes[$type] = array_values(array_unique(array_merge
		(
			MMI_HTML4_Attributes::get(),
			$custom
		)));
		return self::$_attributes[$type];
	}
} // End Kohana_MMI_HTML4_Attributes_Input
