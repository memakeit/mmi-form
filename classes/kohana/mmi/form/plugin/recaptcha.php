<?php defined('SYSPATH') or die('No direct script access.');
/**
 * reCAPTCHA plugin.
 *
 * @package		MMI Form
 * @category	plugin
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Plugin_reCAPTCHA extends MMI_Form_Plugin implements MMI_Form_CAPTCHA
{
	/**
	 * @var Kohana_Config the plugin configuration
	 */
	protected static $_config;

	/**
	 * @var string the default theme
	 */
	protected static $_default_theme = 'red';

	/**
	 * @var array valid themes
	 */
	protected static $_valid_themes = array('red', 'white', 'blackglass', 'clean', 'custom');

	/**
	 * Load the reCAPTCHA vendor file.
	 * Ensure a public and private reCAPTCHA key are configured.
	 * Initialize the options.
	 *
	 * @param	array	an associative array of plugin options
	 * @return	void
	 */
	public function __construct($options = array())
	{
		if (Request::$is_ajax)
		{
			return;
		}
		parent::__construct($options);

		// Load the reCAPTCHA vendor file
		require_once Kohana::find_file('vendor', 'recaptcha/recaptchalib');

		// Ensure a public and private key are configured
		$config = array_merge(self::get_config(TRUE), $options);
		$private_key = strval(Arr::get($config, 'private_key', ''));
		$public_key = strval(Arr::get($config, 'public_key', ''));
		if (empty($private_key) OR empty($public_key))
		{
			$msg = 'A public_key and private_key must be specified in the reCAPTCHA configuration file.';
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}

		// Initialize options
		$this->_init_options($options);
	}

	/**
	 * Generate the CAPTCHA HTML.
	 *
	 * @return	string
	 */
	public function html()
	{
		$error = Arr::get($this->_options, '_error');
		$public_key = Arr::get($this->_options, '_public_key');
		$use_ssl = Arr::get($this->_options, '_use_ssl');
		return $this->_get_javascript().recaptcha_get_html($public_key, $error, $use_ssl);
	}

	/**
	 * Is the CAPTCHA response valid?  If not, set an error in the form.
	 *
	 * @return	boolean
	 */
	public function valid()
	{
		if ( ! $_POST)
		{
			return TRUE;
		}

		$error = '';
		$valid = TRUE;
		if (array_key_exists('recaptcha_response_field', $_POST))
		{
			$private_key = Arr::get($this->_options, '_private_key');
			$response = recaptcha_check_answer
			(
				$private_key,
				$_SERVER['REMOTE_ADDR'],
				$_POST['recaptcha_challenge_field'],
				$_POST['recaptcha_response_field']
			);
			if ($response instanceof ReCaptchaResponse)
			{
				$error = $response->error;
				$valid = $response->is_valid;
			}
			if ( ! empty($error))
			{
				$this->_options['_error'] = $error;
			}
		}
		if ( ! $valid)
		{
			$this->form()->error('invalid CAPTCHA response');
		}
		return $valid;
	}

	/**
	 * Merge the user-specified and config file settings.
	 *
	 * @param	array	an associative array of plugin options
	 * @return	void
	 */
	protected function _init_options($options)
	{
		$config = array_merge(self::get_config(TRUE), $options);

		if ( ! isset($options['_lang']))
		{
			$options['_lang'] = Arr::get($config, 'lang', I18n::$lang);
		}
		if ( ! isset($options['_private_key']))
		{
			$options['_private_key'] =  Arr::get($config, 'private_key');
		}
		if ( ! isset($options['_public_key']))
		{
			$options['_public_key'] =  Arr::get($config, 'public_key');
		}
		if ( ! isset($options['_theme']))
		{
			$options['_theme'] = Arr::get($config, 'theme', self::$_default_theme);
		}
		if ( ! isset($options['_use_ssl']))
		{
			$options['_use_ssl'] = Arr::get($config, 'use_ssl', FALSE);
		}
		$this->_options = $options;
	}

	/**
	 * Generate the JavaScript reCAPTCHA options.
	 *
	 * @return	string
	 */
	protected function _get_javascript()
	{
		// Set the theme
		$theme = Arr::get($this->_options, '_theme', self::$_default_theme);
		if ($theme === self::$_default_theme OR ! in_array($theme, self::$_valid_themes))
		{
			$theme = '';
		}
		else
		{
			$theme = " theme: '$theme',";
		}

		// Set the language
		$lang = Arr::get($this->_options, '_lang', I18n::$lang);
		list($lang) = explode('-', $lang);
		return<<<EOJS
<script type="text/javascript">
//<![CDATA[
	var RecaptchaOptions = {{$theme} lang: '$lang' };
//]]>
</script>
EOJS;
	}

	/**
	 * Get the configuration settings.
	 *
	 * @param	boolean	return the configuration as an array?
	 * @return	mixed
	 */
	public static function get_config($as_array = FALSE)
	{
		(self::$_config === NULL) AND self::$_config = Kohana::config('mmi-form-plugin-recaptcha');
		$config = self::$_config;
		if ($as_array)
		{
			$config = $config->as_array();
		}
		return $config;
	}
} // End Kohana_MMI_Form_Plugin_reCAPTCHA
