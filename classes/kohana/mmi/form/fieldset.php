<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A fieldset.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
abstract class Kohana_MMI_Form_FieldSet
{
	/**
	 * @var Kohana_Config the field configuration
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
	 * @param	array	an associative array of field options
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
	 *
	 * @return	mixed
	 */
	public function attribute()
	{
		$num_args = func_num_args();
		if ($num_args === 0)
		{
			return $this->_attributes;
		}

		$args = func_get_args();
		$key = $args[0];
		if ($num_args === 1)
		{
			return Arr::get($this->_attributes, $key);
		}

		if ($this->_state ^ MMI_Form::STATE_FROZEN)
		{
			$this->_attributes[$key] = $args[1];
			return $this;
		}
		else
		{
			$msg = 'Attributes can only be set when the form is in its initial state.';
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}
	}

	/**
 	 * Get or set field meta data.
	 * If no parameters are specified, all meta data is returned.
	 * If a key is specified, it is used to retrieve the meta data value.
	 * If a key and value are specified, they are used to set a meta data value.
	 *
	 * @return	mixed
	 */
	public function meta()
	{
		$num_args = func_num_args();
		if ($num_args === 0)
		{
			return $this->_meta;
		}

		$args = func_get_args();
		$key = $args[0];
		if ($num_args === 1)
		{
			return Arr::get($this->_meta, $key);
		}

		if ($this->_state ^ MMI_Form::STATE_FROZEN)
		{
			$this->_meta[$key] = $args[1];
			return $this;
		}
		else
		{
			$msg = 'Meta data can only be set when the form is in its initial state.';
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}
	}

	/**
	 * Generate the HTML.
	 *
	 * @return	string
	 */
	public function render()
	{
		$path = $this->_get_view_path();
		if (isset(self::$_view_cache[$path]))
		{
			$view = clone self::$_view_cache[$path];
		}
		if ( ! isset($view))
		{
			$view = View::factory($path);
			self::$_view_cache[$path] = $view;
		}

		$parms = $this->_get_view_parms();
		return $view->set($parms)->render();
	}

	/**
	 * Freeze the form fields, preventing further modifications.
	 *
	 * @return	void
	 */
	public function freeze()
	{
		$this->_finalize_rules();
		$this->_state |= Jelly_Form::STATE_FROZEN;
	}

	/**
	 * Reset the form field.
	 *
	 * @return	void
	 */
	public function reset()
	{
		$type = Arr::get($this->_attributes, 'type', 'text');
		if ( ! in_array($type, array('checkbox', 'radio')))
		{
			$this->value = Arr::get($this->_meta, 'default', '');
		}
		$this->_state |= Jelly_Form::STATE_RESET;
	}

	/**
	 * Add an error message for the form field.
	 *
	 * @param	string	the error message
	 * @return	void
	 */
	public function add_error($msg = '')
	{
		if ( ! empty($msg) AND ! in_array($msg, $this->_errors))
		{
			$this->_errors[] = $msg;
		}
	}

	/**
	 * Merge the user-specified and config file settings.
	 * Separate the meta data from the HTML attributes.
	 *
	 * @return	void
	 */
	protected function _init_options($options)
	{
		if ( ! is_array($options))
		{
			$options = array();
		}

		// Set defaults
		if (empty($options['_default']))
		{
			$options['_default'] = Arr::get($options, 'value', '');
		}
		if (empty($options['_type']))
		{
			$options['_type'] = Arr::get($options, 'type', 'input');
		}
		if (empty($options['type']))
		{
			$options['type'] = Arr::get($options, '_type', 'text');
		}

		// Get the CSS class
		$class = $this->_combine_value($options, 'class');

		// Merge the user-specified and config settings
		$config = self::get_config();
		$defaults = $config->get('_defaults', array());
		$type_specific = $config->get($options['type'], array());
		$options = array_merge($defaults, $type_specific, $options);

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
		$config = self::get_config();
		$defaults = $config->get('_defaults', array());
		$type = Arr::get($options, 'type', 'text');
		$type_specific = $config->get($type, array());
		$value =
			Arr::get($defaults, $key, '').' '.
			Arr::get($type_specific, $key, '').' '.
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
		$dir = Arr::get($meta, 'view_path', 'mmi/form/field');
		$file = Arr::get($meta, 'view', Arr::get($meta, 'type', 'input'));
		if ( ! Kohana::find_file('views/'.$dir, $file))
		{
			// Use the default view if the type-specific view is not found
			$file = 'input';
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
		$id = $this->_get_id();
		$attributes['id'] = $id;
		$attributes['name'] = $id;

		$value = Arr::get($attributes, 'value');
		if (is_null($value))
		{
			$attributes['value'] = '';
		}

		$meta = $this->_meta;
		return array
		(
			'after'			=> Arr::get($meta, 'after', ''),
			'attributes'	=> $attributes,
			'before'		=> Arr::get($meta, 'before', ''),
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

		// If a rule for max-length exists, use it to set the attribute
		if (in_array('max_length', $allowed))
		{
			$rules = Arr::get($meta, 'rules', array());
			if (array_key_exists('max_length', $rules))
			{
				$max_length = array_values($rules['max_length']);
				$attributes['maxlength'] = $max_length[0];
			}
		}

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
		$type = Arr::get($this->_attributes, 'type');
		if ($this->_html5)
		{
			return MMI_HTML5_Attributes_Input::get($type);
		}
		return MMI_HTML4_Attributes_Input::get($type);
	}

	/**
	 * Get the field id.
	 *
	 * @return	string
	 */
	protected function _get_id()
	{
		$id = Arr::get($this->_attributes, 'id');
		$namespace = Arr::get($this->_meta, 'namespace');
		return self::get_field_id($id, $namespace);
	}

	/**
	 * Finalize validation rules.
	 *
	 * @return	void
	 */
	protected function _finalize_rules()
	{
		$rules = Arr::get($this->_meta, 'rules');
		if ( ! (is_array($rules) AND count($rules) > 0))
		{
			return;
		}

		// Process rules that are executed even when the value is empty
		$found = array_intersect(array_keys($rules), self::$_empty_rules);
		if (count($found) > 0)
		{
			$rules['not_empty'] = NULL;
		}

		// Process rules that have a UTF8 parameter
		$utf8_rules = self::$_utf8_rules;
		if ($this->_form->unicode())
		{
			foreach ($rules as $name => $rule)
			{
				if (empty($rule) AND in_array($name, $utf8_rules))
				{
					$rules[$name] = array(TRUE);
				}
			}
		}
		$this->_meta['rules'] = $rules;
	}

//	/**
//	 * Generate the label HTML.
//	 *
//	 * @param   array   the view data
//	 * @return  string
//	 */
//	protected function _label($data = array())
//	{
//		$file = self::_get_view_path().'label';
//		$view = (isset(self::$_view_cache[$file])) ? (clone self::$_view_cache[$file]) : (NULL);
//		if (empty($view))
//		{
//			$view = View::factory($file);
//			self::$_view_cache[$file] = $view;
//		}
//		return $view->set($data)->render();
//	}
//
//	/**
//	 * Generate the error HTML.
//	 *
//	 * @param   array   the view data
//	 * @return  string
//	 */
//	protected function _error($data = array())
//	{
//		$file = self::_get_view_path().'error';
//		$view = (isset(self::$_view_cache[$file])) ? (clone self::$_view_cache[$file]) : (NULL);
//		if (empty($view))
//		{
//			$view = View::factory($file);
//			self::$_view_cache[$file] = $view;
//		}
//		return $view->set($data)->render();
//	}

	/**
	 * Generate the field id.  Include the namespace if one is specified.
	 *
	 * @param	string	the field id
	 * @param	string	the field namespace
	 * @return	string
	 */
	public static function get_field_id($id, $namespace = NULL)
	{
		$id = MMI_Form::clean_id($id);
		if (empty($namespace))
		{
			return $id;
		}
		return $namespace.'_'.$id;
	}

	/**
	 * Get the field configuration settings.
	 *
	 * @param	boolean	return the configuration as an array?
	 * @return	mixed
	 */
	public static function get_config($as_array = FALSE)
	{
		(self::$_config === NULL) AND self::$_config = Kohana::config('mmi-form-field');
		$config = self::$_config;
		if ($as_array)
		{
			$config = $config->as_array();
		}
		return $config;
	}

	/**
	 * Create a field instance.
	 *
	 * @param	string	the field type
	 * @param	array	an associative array of field options
	 * @return	MMI_Form_Field
	 */
	public static function factory($type, $options = array())
	{
		// Get the choices and field type
		$choices = NULL;
		if (is_array($options) AND count($options) > 0)
		{
			$choices = Arr::get($options, '_choices');
			$temp = Arr::get($options, 'type');
			if ( ! empty($temp))
			{
				// Use type specified in the options
				$type = strtolower(trim($temp));
			}
		}
		if (empty($type))
		{
			$type = 'text';
		}

		// Set the class name
		$class = 'MMI_Form_Field_';
		if ( ! empty($choices) AND ($type === 'checkbox' OR $type === 'radio'))
		{
			$class .= 'Group_';
		}
		if ( ! class_exists($class.ucfirst($type)) AND in_array($type, MMI_HTML5_Attributes_Input::types()))
		{
			$options['_type'] = 'input';
			$options['type'] = $type;
			$type = 'input';
		}
		$class .= ucfirst($type);

		if ( ! class_exists($class))
		{
			$msg = $class.' field does not exist.';
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}
		return new $class($options);
	}



	/**
	 * Add an opening fieldset tag to the form.
	 * This method is chainable.
	 *
	 * @param   string  the legend text
	 * @param   array   field-specific options
	 * @return  MMI_Form
	 */
	public function begin_fieldset($legend = '', $options = array())
	{
		if ( ! empty($legend))
		{
			$legend = '<legend>'.$legend.'</legend>';
		}
		else
		{
			$legend = '';
		}

		$attributes = self::attributes($options);
		$this->add_field
		(
			array
			(
				'name'      => uniqid('fieldset_'),
				'type'      => 'html',
				'html'      => '<fieldset'.HTML::attributes($attributes).'>'.$legend,
			)
		);
		return $this;
	}

	/**
	 * Add a closing fieldset tag to the form.
	 * This method is chainable.
	 *
	 * @return  MMI_Form
	 */
	public function end_fieldset()
	{
		$this->add_field
		(
			array
			(
				'name'      => uniqid('fieldset_'),
				'type'      => 'html',
				'html'      => '</fieldset>',
			)
		);
		return $this;
	}
} // End Kohana_MMI_Form_FieldSet
