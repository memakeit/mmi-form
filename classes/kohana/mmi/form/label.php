<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A form label.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Label
{
	/**
	 * @var Kohana_Config the label configuration
	 */
	protected static $_config;

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
		return $view->set($parms)->render();
	}

	/**
	 * Merge the user-specified and config file settings.
	 * Separate the meta data from the HTML attributes.
	 *
	 * @param	array	an associative array of label options
	 * @return	void
	 */
	protected function _init_options($options)
	{
		if ( ! is_array($options))
		{
			$options = array();
		}

		// Get the CSS class
		$class = $this->_combine_value($options, 'class');

		// Merge the user-specified and config settings
		$options = array_merge(self::get_config(), $options);

		// Set the CSS class
		if ( ! empty($class))
		{
			$options['class'] = $class;
		}

		// Separate the meta data from the HTML attributes
		foreach ($options as $name => $value)
		{
			$name = trim($name);
			if (substr($name, 0, 1) === '_')
			{
				$this->_meta[trim($name, '_')] = $value;
			}
			else
			{
				$this->_attributes[$name] = $value;
			}
		}
	}

	/**
	 * Combine default values from the config file with a user-specified value.
	 * When multiple values are found, they are appended in order from most
	 * general (config file) to most specific (user-specified).
	 *
	 * @param	array	the user-specified settings
	 * @param	string	the key of the value being combined
	 * @return	string
	 */
	protected function _combine_value($options, $key)
	{
		$value =
			Arr::get(self::get_config(), $key, '').' '.
			Arr::get($options, $key, '').' '
		;
		$value = trim(preg_replace('/\s+/', ' ', $value));

		// Remove duplicates
		if ( ! empty($value))
		{
			$value = array_unique(explode(' ', $value));
			$value = implode(' ', $value);
		}
		return $value;
	}

	/**
	 * Get the view path.
	 *
	 * @return	string
	 */
	protected function _get_view_path()
	{
		$meta = $this->_meta;
		$dir = Arr::get($meta, 'view_path', 'mmi/form');
		$file = Arr::get($meta, 'view', 'label');
		if ( ! Kohana::find_file('views/'.$dir, $file))
		{
			// Use the default view
			$file = 'label';
		}
		return $dir.'/'.$file;
	}

	/**
	 * Get the view parameters.
	 *
	 * @return	array
	 */
	protected function _get_view_parms()
	{
		$attributes = $this->_get_view_attributes();
		$meta = $this->_meta;
		$id = Arr::get($attributes, 'id');
		$namespace = Arr::get($meta, 'namespace');
		$id = MMI_Form_Field::field_id($id, $namespace);
		if ( ! empty($id))
		{
			$attributes['id'] = $id;
		}

		return array
		(
			'after'			=> Arr::get($meta, 'after', ''),
			'attributes'	=> $attributes,
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
			return MMI_HTML5_Attributes_Label::get();
		}
		return MMI_HTML4_Attributes_Label::get();
	}

	/**
	 * Get the label configuration settings.
	 *
	 * @return	array
	 */
	public static function get_config()
	{
		(self::$_config === NULL) AND self::$_config = Kohana::config('mmi-form')->get('_label', array());
		return self::$_config;
	}

	/**
	 * Create a form label instance.
	 *
	 * @param	array	an associative array of label options
	 * @return	MMI_Form_Label
	 */
	public static function factory($options = array())
	{
		return new MMI_Form_Label($options);
	}
} // End Kohana_MMI_Form_Label
