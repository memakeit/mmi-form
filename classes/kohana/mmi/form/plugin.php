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
	 * @var string the plugin namespace
	 */
	protected $_namespace;

	/**
	 * @var array plugin-specific options
	 */
	protected $_options = array();

	/**
	 * Initialize the plugin.
	 *
	 * @param	array	an associative array of plugin options
	 * @return	void
	 */
	public function __construct($options = array())
	{
		$this->_options = $options;
//		$this->_namespace = $namespace;
//		$this->_name = $this->name();
	}

	public function name()
	{
		return get_class($this);
	}

	public function form($form = NULL)
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
		if ($form instanceof MMI_Form)
		{
			$this->_form = $form;
		}
		return $this;
	}

//	/**
//	 * Get the plugin name.
//	 *
//	 * @return  string
//	 */
//	public static function get_name($plugin)
//	{
//		$name = get_class($plugin);
//
//		$search = 'plugin_';
//		$idx = strripos($name, $search);
//		$name = substr($name, $idx + strlen($search));
//		return strtolower($name);
//	}

	/**
	 * Create a plugin instance.
	 *
	 * @param	string      the plugin name
	 * @param	array       the plugin options
	 * @return	Jelly_Plugin
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
