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
	 * @var array HTML4 inputs that support the accept attribute
	 */
	protected static $_attr_accept = array('file');

	/**
	 * @var array HTML4 inputs that support the alt attribute
	 */
	protected static $_attr_alt = array('image');

	/**
	 * @var array HTML4 inputs that support the checked attribute
	 */
	protected static $_attr_checked = array('checkbox', 'radio');

	/**
	 * @var array HTML4 inputs that support the disabled attribute
	 */
	protected static $_attr_disabled = array
	(
		'button',
		'checkbox',
		'file',
		'image',
		'password',
		'radio',
		'reset',
		'submit',
		'text',
	);

	/**
	 * @var array HTML4 inputs that support the maxlength attribute
	 */
	protected static $_attr_maxlength = array('password', 'text');

	/**
	 * @var array HTML4 inputs that support the readonly attribute
	 */
	protected static $_attr_readonly = array('password', 'text');

	/**
	 * @var array HTML4 inputs that support the size attribute
	 */
	protected static $_attr_size = array
	(
		'button',
		'checkbox',
		'file',
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

	/**
	 * @var array HTML4 inputs that support the value attribute
	 */
	protected static $_attr_value = array
	(
		'button',
		'checkbox',
		'hidden',
		'image',
		'password',
		'radio',
		'reset',
		'submit',
		'text',
	);

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
			// Default type to text
			$type = 'text';
		}
		if (isset(self::$_attributes[$type]))
		{
			return self::$_attributes[$type];
		}

		$attr_names = array
		(
			'accept',
			'alt',
			'checked',
			'disabled',
			'maxlength',
			'readonly',
			'size',
			'src',
			'value',
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
			array('name', 'type'),
			$custom
		)));
		return self::$_attributes[$type];
	}

	/**
	 * Get the valid HTML4 input types.
	 *
	 * @return	array
	 */
	public static function types()
	{
		return self::$_types;
	}
} // End Kohana_MMI_HTML4_Attributes_Input
