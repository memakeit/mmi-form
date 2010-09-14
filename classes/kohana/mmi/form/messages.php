<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form messages.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license	http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Messages
{

	/**
	 * @var Kohana_Config the form configuration
	 */
	protected static $_config;

	/**
	 * @var array the form errors
	 */
	protected $_errors = array();

	/*
	 * Set whether to use HTML5 markup.
	 * Initialize the options.
	 *
	 * @param	array	an associative array of field options
	 * @return	void
	 */
	public function __construct($options = array())
	{
//		$this->_html5 = MMI_Form::html5();
//		$this->_init_options($options);
//		$this->_posted = ( ! empty($_POST));
	}

	/**
	 * Get the formatted error message.
	 *
	 * @param   string  the field label
	 * @param   string  the rule name
	 * @param   array   the rule parameters
	 * @return  string
	 */
	public static function format_error_message($field_label, $rule_name, $rule_parms)
	{
		$config = MMI_Form::get_config()->get('_messages', array());
//		 = $this->_options_form;
		if ($message = Arr::get($config, '_custom.'.$rule_name))
		{
			// Found a custom message
		}
		else
		{
			$file = self::_get_message_file();
			if ($message = Kohana::message($file, $rule_name))
			{
				// Found a default message for this error
			}
			else
			{
				// No message exists, display the path expected
				$message = "{$file}.{$rule_name}";
			}
		}

		$values = array(':field' => $field_label);
		if (is_array($rule_parms) AND count($rule_parms) > 0)
		{
			for ($i=0; $i<count($rule_parms); $i++)
			{
				$parm = $rule_parms[$i];
				$values[':param'.($i + 1)] = (is_array($parm)) ? implode(', ', $parm) : $parm;
			}
		}

		$translate = Arr::get($config, '_translate', FALSE);
		if ($translate)
		{
			// Translate the message using the specified language
			$message = __($message, $values, I18n::$lang);
		}
		else
		{
			// Do not translate the message, just replace the values
			$message = strtr($message, $values);
		}
		return $message;
	}

	/**
	 * Get the message file.  If a language-specific file can be located, it is used.
	 *
	 * @return	string
	 */
	protected static function _get_message_file()
	{
		$config = MMI_Form::get_config()->get('_messages', array());
		$filename = Arr::get($config, '_file', 'validate');
		$lang = str_replace('-', DIRECTORY_SEPARATOR, I18n::$lang);
		$file = $lang.DIRECTORY_SEPARATOR.$filename;

		$path = Kohana::find_file('messages', $file);
		if (empty($path))
		{
			$idx = strpos($lang, DIRECTORY_SEPARATOR);
			if ($idx !== FALSE)
			{
				$lang = substr($lang, 0, $idx);
				$file = $lang.DIRECTORY_SEPARATOR.$filename;
				$path = Kohana::find_file('messages', $file);
			}
		}
		if (empty($path))
		{
			$file = $filename;
			$path = Kohana::find_file('messages', $file);
		}
		return $file;
	}
} // End Kohana_MMI_Form_Messages
