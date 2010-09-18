<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A form element.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
abstract class Kohana_MMI_Form_Element
{
	// Abstract methods
	abstract protected function _init_options($options);
	abstract protected function _combine_value($options, $key);
	abstract protected function _get_view_path();

	/**
	 * @var array the HTML attributes
	 */
	protected $_attributes = array();

	/**
	 * @var boolean use HTML5 markup?
	 */
	protected $_html5;

	/**
	 * @var array the associated meta data
	 */
	protected $_meta = array();

	/**
	 * Set whether to use HTML5 markup.
	 * Initialize the options.
	 *
	 * @param	array	an associative array of label options
	 * @return	void
	 */
	public function __construct($options = array())
	{
		$this->_html5 = MMI_Form::html5();
		$this->_init_options($options);
	}

	/**
	 * Get or set an HTML attribute.
	 * If no parameters are specified, all attributes are returned.
	 * If a key is specified, it is used to retrieve the attribute value.
	 * If a key and value are specified, they are used to set an attribute value.
	 * This method is chainable when setting a value.
	 *
	 * @param	string	the name of the attribute to get or set
	 * @param	mixed	the value of the attribute to set
	 * @return	mixed
	 */
	public function attribute($name = NULL, $value = NULL)
	{
		$num_args = func_num_args();
		if ($num_args === 0)
		{
			return $this->_attributes;
		}

		if ($num_args === 1)
		{
			return Arr::get($this->_attributes, $name);
		}
		$this->_attributes[$name] = $value;
		return $this;
	}

	/**
 	 * Get or set field meta data.
	 * If no parameters are specified, all meta data is returned.
	 * If a key is specified, it is used to retrieve the meta data value.
	 * If a key and value are specified, they are used to set a meta data value.
	 * This method is chainable when setting a value.
	 *
	 * @param	string	the name of the meta data to get or set
	 * @param	mixed	the value of the meta data to set
	 * @return	mixed
	 */
	public function meta($name = NULL, $value = NULL)
	{
		$num_args = func_num_args();
		if ($num_args === 0)
		{
			return $this->_meta;
		}

		if ($num_args === 1)
		{
			return Arr::get($this->_meta, $name);
		}
		$this->_meta[$name] = $value;
		return $this;
	}

	/**
	 * Generate the HTML.
	 *
	 * @return	string
	 */
	public function render()
	{
		$this->_pre_render();
		$path = $this->_get_view_path();
		$cache = MMI_Form::view_cache($path);
		if (isset($cache))
		{
			$view = clone $cache;
		}
		if ( ! isset($view))
		{
			$view = View::factory($path);
			MMI_Form::view_cache($path, $view);
		}
		$parms = $this->_get_view_parms();
		$this->_state |= MMI_Form::STATE_RENDERED;
		return $view->set($parms)->render();
	}

	/**
	 * Perform any pre-rendering logic.
	 *
	 * @return	void
	 */
	protected function _pre_render()
	{
		$this->_state |= MMI_Form::STATE_PRE_RENDERED;
	}

	/**
	 * Get the view parameters.
	 *
	 * @return	array
	 */
	protected function _get_view_parms()
	{
		$meta = $this->_meta;
		return array
		(
			'after'			=> Arr::get($meta, 'after', ''),
			'attributes'	=> $this->_get_view_attributes(),
			'before'		=> Arr::get($meta, 'before', ''),
			'html'			=> Arr::get($meta, 'html'),
		);
	}

	/**
	 * Remove invalid attributes and return an array of valid attributes.
	 *
	 * @return	array
	 */
	protected function _get_view_attributes()
	{
		$allowed = $this->_get_allowed_attributes();
		$attributes = $this->_attributes;
		$meta = $this->_meta;

		// If a title is not set, use the meta description if present
		$description = Arr::get($meta, 'description');
		$title = Arr::get($attributes, 'title');
		if (empty($title) AND ! empty($description))
		{
			$attributes['title'] = $description;
		}
		return array_intersect_key($attributes, array_flip($allowed));
	}

	/**
	 * Get the HTML attributes allowed.
	 *
	 * @return	array
	 */
	protected function _get_allowed_attributes()
	{
		if ($this->_html5)
		{
			return MMI_HTML5_Attributes::get();
		}
		return MMI_HTML4_Attributes::get();
	}
} // End Kohana_MMI_Form_Label
