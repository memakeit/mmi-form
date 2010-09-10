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
	 * @var array HTML5 inputs that support the width attribute
	 */
	protected static $_attr_width = array('image');

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
	 * @var array HTML5 inputs that support the min attribute
	 */
	protected static $_attr_min = array
	(
		'date', 'datetime', 'datetime-local', 'month', 'time', 'week',
		'number',
		'range',
	);
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
	 * @var array HTML5 inputs that support the multiple attribute
	 */
	protected static $_attr_multiple = array
	(
		'email',
		'file',
	);

	/**
	 * @var array HTML5 inputs that support the novalidate attribute
	 */
	protected static $_attr_novalidate = array
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
			'autocomplete',
			'formaction',
			'formenctype',
			'formmethod',
			'formnovalidate',
			'formtarget',
			'height',
			'list',
			'max',
			'min',
			'multiple',
			'novalidate',
			'pattern',
			'placeholder',
			'required',
			'step',
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
			$custom
		)));
		return self::$_attributes[$type];
	}
} // End Kohana_MMI_HTML5_Attributes_Input
