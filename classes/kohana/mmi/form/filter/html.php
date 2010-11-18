<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Filter text using HTML Purifier.
 *
 * @package		MMI Form
 * @category	filter
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 * @link 		http://github.com/shadowhand/purifier
 * @link		http://htmlpurifier.org/
 */
class Kohana_MMI_Form_Filter_HTML
{
	/**
	 * @var array an associative array of cached configuration settings
	 */
	protected static $_config_cache;

	/**
	 * @var HTMLPurifier singleton instance of the HTML Purifier object
	 */
	protected static $_htmlpurifier;

	/**
	 * Remove broken HTML and XSS from a string using HTMLPurifier.
	 *
	 * @param	mixed	the text to clean (array|string)
	 * @param	mixed	the HTML Purifier options
	 * @return	mixed
	 */
	public static function purify($html, $options = NULL)
	{
		if (empty($html))
		{
			return $html;
		}

		// Load HTML Purifier
		$purifier = self::_htmlpurifier();

		// Process the configuration
		if ($options instanceof HTMLPurifier_Config)
		{
			$config = $options;
		}
		else
		{
			if ( ! is_array($options) AND ! empty($options) AND is_string($options))
			{
				$options = array('HTML.Allowed' => $options);
			}
			if (is_array($options))
			{
				// Create the configuration object
				$cache_key = md5(serialize($options));
				$config = Arr::get(self::$_config_cache, $cache_key);
				if ( ! $config instanceof HTMLPurifier_Config)
				{
					$config = HTMLPurifier_Config::createDefault();
					$config->loadArray($options);
					self::$_config_cache[$cache_key] = $config;
				}
			}
			else
			{
				$config = NULL;
			}
		}

		// Process arrays
		if (is_array($html))
		{
			foreach ($html as $name => $value)
			{
				// Recursively purify arrays
				$html[$name] = self::purify($value, $config);
			}
			return $html;
		}

		// Clean the HTML and return it
		return $purifier->purify($html, $config);
	}

	/**
	 * Returns the singleton instance of HTML Purifier. If no instance has
	 * been created, a new instance will be created. Configuration options
	 * for HTML Purifier can be set in `APPPATH/config/purifier.php` in the
	 * "settings" key.
	 *
	 * @return	HTMLPurifier
	 */
	protected static function _htmlpurifier()
	{
		if ( ! self::$_htmlpurifier)
		{
			// Ensure shadowhand's purifier module is loaded
			$module_name = 'purifier';
			$module_path = MODPATH.$module_name;
			$modules = Kohana::modules();
			if ( ! in_array($module_path, $modules, TRUE))
			{
				$modules[$module_name] = $module_path;
				Kohana::modules($modules);
			}

			if (Kohana::config('purifier.preload'))
			{
				// Load all of the HTML Purifier classes.
				// This increases performance with a slight hit to memory usage.
				require_once Kohana::find_file('vendor', 'htmlpurifier/library/HTMLPurifier.includes');
			}

			// Load the HTML Purifier auto loader
			require_once Kohana::find_file('vendor', 'htmlpurifier/library/HTMLPurifier.auto');

			// Create the configuration object
			if (is_array($settings = Kohana::config('purifier.settings')))
			{
				$cache_key = md5(serialize($settings));
				$config = Arr::get(self::$_config_cache, $cache_key);
				if ( ! $config instanceof HTMLPurifier_Config)
				{
					$config = HTMLPurifier_Config::createDefault();
					$config->loadArray($settings);
					self::$_config_cache[$cache_key] = $config;
				}
			}
			else
			{
				$config = NULL;
			}

			// Create the purifier instance
			self::$_htmlpurifier = new HTMLPurifier($config);
		}
		return self::$_htmlpurifier;
	}
} // End Kohana_MMI_Form_Filter_HTML
