<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form plugin.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
abstract class Kohana_MMI_Form_Plugin
{
	/**
	 * @var MMI_Form the form instance
	 */
	protected $_form;

	/**
	 * @var string the prefix used to call plugin methods from the form.
	 */
	protected $_method_prefix;

	/**
	 * @var array an associative array of plugin options
	 */
	protected $_options = array();

	/**
	 * Initialize the options.
	 *
	 * @param	array	an associative array of plugin options
	 * @return	void
	 */
	public function __construct($options = array())
	{
		$this->_options = $options;
	}

	/**
	 * Get or set the form.
	 * This method is chainable when setting a value.
	 *
	 * @param	MMI_From	a form object
	 * @return	mixed
	 */
	public function form($value = NULL)
	{
		if (func_num_args() === 0)
		{
			$form = $this->_form;
			if ( ! $form instanceof MMI_Form)
			{
				$form = MMI_Form::instance();
			}
			return $form;
		}
		if ($value instanceof MMI_Form)
		{
			$this->_form = $value;
		}
		return $this;
	}

	/**
	 * Check whether the plugin implements a method.
	 *
	 * @param	string	the method name
	 * @return	boolean
	 */
	public function method_exists($method)
	{
		return method_exists($this, $method);
	}

	/**
	 * Get or set the prefix used to call plugin methods from the form.
	 * This method is chainable when setting a value.
	 *
	 * @param	string	the method prefix
	 * @return	mixed
	 */
	public function method_prefix($value = NULL)
	{
		if (func_num_args() === 0)
		{
			return $this->_method_prefix;
		}
		$this->_method_prefix = $value;
		return $this;
	}

	/**
	 * Get the plugin name.
	 *
	 * @param	array	an associative array of plugin options
	 * @return	string
	 */
	public function name()
	{
		return strtolower(str_replace('MMI_Form_Plugin_', '', get_class($this)));
	}

	/**
	 * Create a plugin instance.
	 *
	 * @param	string	the plugin type
	 * @param	array	an associative array of plugin options
	 * @return	MMI_Form_Plugin
	 */
	public static function factory($type, $options = array())
	{
		$class = 'MMI_Form_Plugin_'.ucfirst($type);
		if ( ! class_exists($class))
		{
			$msg = $class.' plugin does not exist.';
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}
		return new $class($options);
	}
} // End Kohana_MMI_Form_Plugin
