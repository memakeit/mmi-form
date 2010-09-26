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
	 * @var array the message configuration
	 */
	protected static $_config;

	/**
	 * Get the CSS class for failure messages.
	 *
	 * @return	string
	 */
	public static function class_failure()
	{
		$config = self::get_config();
		return trim(Arr::get($config, 'class', '').' '.Arr::path($config, '_failure.class', ''));
	}

	/**
	 * Get the CSS class for success messages.
	 *
	 * @return	string
	 */
	public static function class_success()
	{
		$config = self::get_config();
		return trim(Arr::get($config, 'class', '').' '.Arr::path($config, '_success.class', ''));
	}

	/**
	 * Get the formatted error message.
	 *
	 * @param	string	the field label
	 * @param	string	the rule name
	 * @param	array	the rule parameters
	 * @return	string
	 */
	public static function format_error_msg($field_label, $rule_name, $rule_parms)
	{
		$file = MMI_Form_Messages::get_path();
		if ($message = Kohana::message($file, $rule_name))
		{
			// Found a default message for this error
		}
		else
		{
			// No message exists, display the path expected
			$message = "{$file}.{$rule_name}";
		}

		// Start the translation values list
		$values = array(':field' => $field_label);
		if (is_array($rule_parms) AND count($rule_parms) > 0)
		{
			foreach ($rule_parms as $key => $value)
			{
				if (is_array($value))
				{
					// All values must be strings
					$value = implode(', ', Arr::flatten($value));
				}

				// Add each parameter as a numbered value, starting from 1
				$values[':param'.($key + 1)] = $value;
			}
		}

		$translate = self::translate();
		if ($translate)
		{
			// Translate the message using the default language
			$message = __($message, $values);
		}
		else
		{
			// Do not translate, just replace the values
			$message = strtr($message, $values);
		}
		return $message;
	}

	/**
	 * Get the message file path.
	 * If a language-specific file can be located, it is used.
	 *
	 * @return	string
	 */
	public static function get_path()
	{
		$filename = Arr::get(self::get_config(), '_file', 'validate');
		$lang = str_replace('-', DIRECTORY_SEPARATOR, I18n::$lang);
		$file = $lang.DIRECTORY_SEPARATOR.$filename;

		$path = Kohana::find_file('messages', $file);
		if (empty($path))
		{
			list($lang) = explode(DIRECTORY_SEPARATOR, $lang);
			$file = $lang.DIRECTORY_SEPARATOR.$filename;
			$path = Kohana::find_file('messages', $file);
		}
		if (empty($path))
		{
			$file = $filename;
			$path = Kohana::find_file('messages', $file);
		}
		if (empty($path))
		{
			$file = NULL;
		}
		return $file;
	}

	/**
	 * Get the failure message for general errors.
	 *
	 * @return	string
	 */
	public static function msg_failure()
	{
		$config = Arr::path(self::get_config(), '_failure._msg', array());
		return Arr::get($config, 'general', 'There was a problem processing your request. Please try again.');
	}

	/**
	 * Get the failure message for are multiple errors.
	 *
	 * @param	integer	the number of errors
	 * @return	string
	 */
	public static function msg_failure_multiple($num_errors)
	{
		$config = Arr::path(self::get_config(), '_failure._msg', array());
		$msg = Arr::get($config, 'multiple', '%d fields are invalid. They have been highlighted.');
		return sprintf($msg, $num_errors);
	}

	/**
	 * Get the failure message for a single error.
	 *
	 * @return	string
	 */
	public static function msg_failure_single()
	{
		$config = Arr::path(self::get_config(), '_failure._msg', array());
		return Arr::get($config, 'single', '1 field is invalid. It has been highlighted.');
	}

	/**
	 * Get the success message.
	 *
	 * @return	string
	 */
	public static function msg_success()
	{
		return Arr::path(self::get_config(), '_success._msg', 'Your request has been processed.');
	}

	/**
	 * Get whether messages should be translated.
	 *
	 * @return	boolean
	 */
	public static function translate()
	{
		return Arr::get(self::get_config(), '_translate', FALSE);
	}

	/**
	 * Get the message configuration settings.
	 *
	 * @return	array
	 */
	public static function get_config()
	{
		(self::$_config === NULL) AND self::$_config = Kohana::config('mmi-form')->get('_messages', array());
		return self::$_config;
	}
} // End Kohana_MMI_Form_Messages