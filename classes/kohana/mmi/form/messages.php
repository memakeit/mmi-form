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
