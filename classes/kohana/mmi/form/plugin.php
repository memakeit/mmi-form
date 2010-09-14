<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Jelly form plugin.
 *
 * @package     Jelly Form
 * @author      Me Make It
 * @copyright   (c) 2010 Me Make It
 * @license     http://www.memakeit.com/license
 */
abstract class Kohana_Jelly_Form_Plugin
{
	/**
	 * @var Jelly_Form the Jelly form instance
	 */
	protected $_form;

	/**
	 * @var string the plugin name
	 */
	protected $_name;

	/**
	 * @var array plugin-specific options
	 */
	protected $_options = array();

	/**
	 * Create a plugin instance.
	 *
	 * @param   Jelly_Form  the Jelly form instance
	 * @param   string      the plugin name
	 * @param   array       the plugin options
	 * @return  Jelly_Plugin
	 */
	public static function factory(Jelly_Form $form, $plugin_name, $options = array())
	{
		$class = 'Jelly_Form_Plugin_'.ucfirst($plugin_name);
		if ( ! class_exists($class))
		{
			$msg = $class.' plugin does not exist.';
			Kohana::$log->add(Kohana::ERROR, '['.__METHOD__.' @ line '.__LINE__.'] '.$msg)->write();
			throw new Kohana_Exception($msg);
		}
		return new $class($form, $options);
	}

	/**
	 * Initialize the plugin.
	 *
	 * @param   Jelly_Form  the Jelly form instance
	 * @param   array       the plugin options
	 * @return  void
	 */
	public function __construct(Jelly_Form $form, $options = array())
	{
		$this->_form = $form;
		$this->_name = self::get_name(get_class($this));
	}

	/**
	 * Get the plugin name.
	 *
	 * @return  string
	 */
	public static function get_name($plugin)
	{
		$name = get_class($plugin);

		$search = 'plugin_';
		$idx = strripos($name, $search);
		$name = substr($name, $idx + strlen($search));
		return strtolower($name);
	}
} // End Kohana_Jelly_Form_Plugin
