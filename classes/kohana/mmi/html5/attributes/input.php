<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML5 input attributes.
 *
 * @package		MMI Form
 * @category	HTML5
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_HTML5_Attributes_Input
{
	/**
	 * @var array HTML5 input attributes (including events)
	 */
	protected static $_attributes = array();

	/**
	 * @var array HTML5 input types
	 */
	protected static $_types = array
	(
		'button',
		'checkbox',
		'color',
		'date',
		'datetime',
		'datetime-local',
		'email',
		'file',
		'hidden',
		'image',
		'month',
		'number',
		'password',
		'radio',
		'range',
		'reset',
		'search',
		'submit',
		'tel',
		'text',
		'time',
		'url',
		'week',
	);

	/**
	 * @var array HTML5 inputs that support the accept attribute
	 */
	protected static $_attr_accept = array('file');

	/**
	 * @var array HTML5 inputs that support the alt attribute
	 */
	protected static $_attr_alt = array('image');

	/**
	 * @var array HTML5 inputs that support the autocomplete attribute
	 */
	protected static $_attr_autocomplete = array
	(
		'color',
		'date', 'datetime', 'datetime-local', 'month', 'time', 'week',
		'email',
		'password',
		'range',
		'search',
		'tel',
		'text',
		'url',
	);

	/**
	 * @var array HTML5 inputs that support the autofocus attribute
	 */
	protected static $_attr_autofocus = array
	(
		'button',
		'checkbox',
		'color',
		'date', 'datetime', 'datetime-local', 'month', 'time', 'week',
		'email',
		'file',
		'image',
		'number',
		'password',
		'radio',
		'range',
		'reset',
		'search',
		'submit',
		'tel',
		'text',
		'url',
	);

	/**
	 * @var array HTML5 inputs that support the checked attribute
	 */
	protected static $_attr_checked = array('checkbox', 'radio');

	/**
	 * @var array HTML5 inputs that support the disabled attribute
	 */
	protected static $_attr_disabled = array
	(
		'button',
		'checkbox',
		'color',
		'date', 'datetime', 'datetime-local', 'month', 'time', 'week',
		'email',
		'file',
		'image',
		'number',
		'password',
		'radio',
		'range',
		'reset',
		'search',
		'submit',
		'tel',
		'text',
		'url',
	);

	/**
	 * @var array HTML5 inputs that support the formaction attribute
	 */
	protected static $_attr_formaction = array('image', 'submit');

	/**
	 * @var array HTML5 inputs that support the formenctype attribute
	 */
	protected static $_attr_formenctype = array('image', 'submit');

	/**
	 * @var array HTML5 inputs that support the formmethod attribute
	 */
	protected static $_attr_formmethod = array('image', 'submit');

	/**
	 * @var array HTML5 inputs that support the formnovalidate attribute
	 */
	protected static $_attr_formnovalidate = array('image', 'submit');

	/**
	 * @var array HTML5 inputs that support the formtarget attribute
	 */
	protected static $_attr_formtarget = array('image', 'submit');

	/**
	 * @var array HTML5 inputs that support the height attribute
	 */
	protected static $_attr_height = array('image');

	/**
	 * @var array HTML5 inputs that support the list attribute
	 */
	protected static $_attr_list = array
	(
		'color',
		'date', 'datetime', 'datetime-local', 'month', 'time', 'week',
		'email',
		'number',
		'range',
		'search',
		'tel',
		'text',
		'url',
	);

	/**
	 * @var array HTML5 inputs that support the max attribute
	 */
	protected static $_attr_max = array
	(
		'date', 'datetime', 'datetime-local', 'month', 'time', 'week',
		'number',
		'range',
	);

	/**
	 * @var array HTML5 inputs that support the maxlength attribute
	 */
	protected static $_attr_maxlength = array
	(
		'email',
		'password',
		'search',
		'tel',
		'text',
		'url',
	);

	/**
	 * @var array HTML5 inputs that support the min attribute
	 */
	protected static $_attr_min = array
	(
		'date', 'datetime', 'datetime-local', 'month', 'time', 'week',
		'number',
		'range',
	);

	/**
	 * @var array HTML5 inputs that support the multiple attribute
	 */
	protected static $_attr_multiple = array
	(
		'email',
		'file',
	);

	/**
	 * @var array HTML5 inputs that support the pattern attribute
	 */
	protected static $_attr_pattern = array
	(
		'email',
		'password',
		'search',
		'tel',
		'text',
		'url',
	);

	/**
	 * @var array HTML5 inputs that support the placeholder attribute
	 */
	protected static $_attr_placeholder = array
	(
		'email',
		'password',
		'search',
		'tel',
		'text',
		'url',
	);

	/**
	 * @var array HTML5 inputs that support the readonly attribute
	 */
	protected static $_attr_readonly = array
	(
		'date', 'datetime', 'datetime-local', 'month', 'time', 'week',
		'email',
		'number',
		'password',
		'search',
		'tel',
		'text',
		'url',
	);

	/**
	 * @var array HTML5 inputs that support the required attribute
	 */
	protected static $_attr_required = array
	(
		'checkbox',
		'date', 'datetime', 'datetime-local', 'month', 'time', 'week',
		'email',
		'file',
		'number',
		'password',
		'radio',
		'search',
		'tel',
		'text',
		'url',
	);

	/**
	 * @var array HTML5 inputs that support the size attribute
	 */
	protected static $_attr_size = array
	(
		'email',
		'password',
		'search',
		'tel',
		'text',
		'url',
	);

	/**
	 * @var array HTML5 inputs that support the src attribute
	 */
	protected static $_attr_src = array('image');

	/**
	 * @var array HTML5 inputs that support the step attribute
	 */
	protected static $_attr_step = array
	(
		'date', 'datetime', 'datetime-local', 'month', 'time', 'week',
		'number',
		'range',
	);

	/**
	 * @var array HTML5 inputs that support the value attribute
	 */
	protected static $_attr_value = array
	(
		'button',
		'checkbox',
		'color',
		'date', 'datetime', 'datetime-local', 'month', 'time', 'week',
		'email',
		'hidden',
		'image',
		'number',
		'password',
		'radio',
		'range',
		'reset',
		'search',
		'submit',
		'tel',
		'text',
		'url',
	);

	/**
	 * @var array HTML5 inputs that support the width attribute
	 */
	protected static $_attr_width = array('image');

	/**
	 * Get the valid HTML5 input field attributes.
	 *
	 * @param	string	the input type
	 * @return	array
	 */
	public static function get($type = 'text')
	{
		$type = strtolower(trim($type));
		if ( ! in_array($type, self::$_types))
		{
			$msg = 'Invalid HTML5 input type: '.$type;
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}
		if (isset(self::$_attributes[$type]))
		{
			return self::$_attributes[$type];
		}

		$attr_names = array
		(
			'accept',
			'alt',
			'autocomplete',
			'autofocus',
			'checked',
			'disabled',
			'formaction',
			'formenctype',
			'formmethod',
			'formnovalidate',
			'formtarget',
			'height',
			'list',
			'max',
			'maxlength',
			'min',
			'multiple',
			'pattern',
			'placeholder',
			'readonly',
			'required',
			'size',
			'src',
			'step',
			'value',
			'width',
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
			MMI_HTML5_Attributes::get(),
			array('form', 'name', 'type'),
			$custom
		)));
		return self::$_attributes[$type];
	}

	/**
	 * Get the valid HTML5 input types.
	 *
	 * @return	array
	 */
	public static function types()
	{
		return self::$_types;
	}
} // End Kohana_MMI_HTML5_Attributes_Input
