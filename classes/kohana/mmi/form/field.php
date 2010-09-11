<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A form field.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
abstract class Kohana_MMI_Form_Field
{
	/**
	 * @var Kohana_Config the field configuration
	 */
	protected static $_config;

	/**
	 * @var array the validation rules that are executed even when a value is empty
	 */
	protected static $_empty_rules = array('matches', 'not_empty');

	/**
	 * @var array the validation rules that have a UTF8 parameter
	 */
	protected static $_utf8_rules = array
	(
		'alpha',
		'alpha_numeric',
		'alpha_dash',
		'digit'
	);

	/**
	 * @var array the view cache
	 */
	protected static $_view_cache = array();

	/**
	 * @var array the HTML attributes
	 */
	protected $_attributes = array();

	/**
	 * @var array the validation errors
	 */
	protected $_errors = array();

	/**
	 * @var boolean use HTML5 markup?
	 */
	protected $_html5;

	/**
	 * @var array the associated meta data
	 */
	protected $_meta = array();

	/**
	 * @var integer the current field state
	 */
	protected $_state = MMI_Form::STATE_INITIAL;

	/**
	 * Set whether to use HTML5 markup.
	 * Separate the meta data from the HTML attributes.
	 *
	 * @param	array	an associative array of field options
	 * @return	void
	 */
	public function __construct($options = array())
	{
		// Configuration
		$config = self::get_config();
		$this->_html5 = $config->get('html5', TRUE);

		if ( ! is_array($options))
		{
			$options = array();
		}

		// Initialize the options
		$this->_init_options($options);
	}

	/**
	 * Merge the user-specified options with the config file defaults.
	 * Initialize the meta data from the HTML attributes.
	 *
	 * @return  void
	 */
	protected function _init_options($options)
	{
		// Get the CSS class
		$class = $this->_combine_value($options, 'class');

		// Merge the options
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
	 * Combine default values in the config file with a user-specified value.
	 * When multiple values are found, they are appended in order from most
	 * general (config file) to most specific (user-specified).
	 *
	 * @param	array	the user-specified settings
	 * @param	string	the name of the value being combined
	 * @return	string
	 */
	protected function _combine_value($options, $name)
	{
		$config = self::get_config();
		$defaults = $config->get('_defaults', array());
		$type_specific = $config->get($options['type'], array());
		$value =
			Arr::get($defaults, $name, '').' '.
			Arr::get($type_specific, $name, '').' '.
			Arr::get($options, $name, '').' ';
		return trim(preg_replace('/\s+/', ' ', $value));
	}

	/**
	 * Get or set an HTML attribute.
	 * If no parameters are specified, all attributes are returned.
	 * If a key parameter is specified, it is used to return an attribute value.
	 * If key and value parameters are specified, they are used to set an attribute value.
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
	 * If a key parameter is specified, it is used to return a meta data value.
	 * If key and value parameters are specified, they are used to set a meta data value.
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
//		$value = Arr::get($attributes, 'value', '');
//		$this->_attributes['value'] = $value;

		$meta = $this->_meta;
		$name = Arr::get($meta, 'name');
		$namespace = Arr::get($meta, 'namespace');
		$this->_attributes['name'] = self::get_field_id($namespace, $name);
		return $this->_input();
	}

	/**
	 * Remove invalid attributes and return the cleaned attributes array.
	 *
	 * @return	array
	 */
	protected function _clean_attributes()
	{
		$allowed = $this->_get_allowed_attributes();
		return array_intersect_key($this->_attributes, array_flip($allowed));
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
	 * Generate the HTML using a view.
	 *
	 * @return	string
	 */
	protected function _input()
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
	 * Get the view path.
	 *
	 * @return	string
	 */
	protected function _get_view_path()
	{
		$dir = 'mmi/form/field';
		$file = $this->_attributes['type'];
		if ( ! Kohana::find_file('views/'.$dir, $file))
		{
			// Use the default view if the type-specific view is not found
			$file = '_input';
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
		$meta = $this->_meta;
		return array
		(
			'after'			=> Arr::get($meta, 'after', ''),
			'attributes'	=> $this->_clean_attributes(),
			'before'		=> Arr::get($meta, 'before', ''),
		);
	}











	/**
	 * Freeze the form fields, preventing further modifications.
	 *
	 * @return	void
	 */
	public function freeze()
	{
		// Is field required?
//		$this->_required = array_key_exists('not_empty', $this->rules);

		$this->_finalize_relationships();
		$this->_finalize_html_rules();
		$this->_finalize_id_and_name();
//		$this->_merge_options();
		$this->_finalize_field_settings();
//		$this->_attributes = Jelly_Form::attributes($this->_options_field);

		$this->_state |= Jelly_Form::STATE_FROZEN;
	}

	/**
	 * Reset the form field.
	 *
	 * @return	void
	 */
	public function reset()
	{
		$type = $this->_attributes['type'];
		if ( ! in_array($type, array('checkbox', 'radio')))
		{
			$this->value = (is_null($this->default)) ? '' : ($this->default);
		}
		$this->_state |= Jelly_Form::STATE_RESET;
	}

	/**
	 * Add an error message for the form field.
	 *
	 * @param	string	the error message
	 * @return	void
	 */
	public function add_error_message($msg = '')
	{
		if ( ! empty($msg) AND ! in_array($msg, $this->_errors))
		{
			$this->_errors[] = $msg;
		}
	}

//	/**
//	 * Load field-specific settings.
//	 *
//	 * @param	mixed	Jelly field or array containing a field specification
//	 * @return	void
//	 */
//	protected function _load_field_specific_settings($field)
//	{
//		if ($this instanceof Jelly_Form_Field_Group)
//		{
//			$this->_is_group = TRUE;
//		}
//
//		if ($this->_type === 'checkbox' AND ! $this->_is_group AND empty($value))
//		{
//			$this->value = 1;
//		}
//	}

	/**
	 * Finalize the rules.
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

//		if (array_key_exists('matches', $rules) AND count($rules['matches']) === 1)
//		{
//			// ensure the name of the field to match contains a model name prefix
//			$parms = array($this->model_name.'.'.$this->rules['matches'][0]);
//			if ($rules['matches'] !== $parms)
//			{
//				$rules['matches'] = $parms;
//			}
//		}

		// Process rules that are executed even when the value is empty
		$rule_names = array_keys($rules);
		$found = array_intersect($rule_names, self::$_empty_rules);
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

		// Process max-length
		if (array_key_exists('max_length', $rules))
		{
			$max_length = array_values($rules['max_length']);
			$this->_attributes['maxlength'] = $max_length[0];
		}
	}

	/**
	 * Finalize the field name and id.
	 *
	 * @return  void
	 */
	protected function _finalize_id_and_name()
	{
		$name = self::get_field_id($this->model_name, $this->name);
		$attributes = $this->_attributes;

		$temp = Arr::get($attributes, 'id');
		if (empty($temp))
		{
			$this->_attributes['id'] = MMI_Form::clean_id($name);
		}

		$temp = Arr::get($attributes, 'name');
		if (empty($temp))
		{
			$this->_attributes['name'] = $name;
		}
	}

	/**
	 * Make final changes to the field's settings.
	 *
	 * @return  void
	 */
	protected function _finalize_field_settings()
	{
		$description = Arr::get($this->_attributes, 'description');
		$title = Arr::get($this->_attributes, 'title');
		if (empty($title) AND ! empty($description))
		{
			// If title not set, use the meta description as the title
			$this->_attributes['title'] = $description;
		}
	}

	/**
	 * Get the value for the form field.
	 *
	 * @return  mixed
	 */
	protected function _get_value()
	{
		$value = (is_null($this->value)) ? '' : ($this->value);
		if (empty($this->choices) AND (is_array($value) OR is_object($value)))
		{
			$value = serialize($value);
		}
		return $value;
	}

	/**
	 * Generate the label HTML.
	 *
	 * @param   array   the view data
	 * @return  string
	 */
	protected function _label($data = array())
	{
		$file = self::_get_view_path().'label';
		$view = (isset(self::$_view_cache[$file])) ? (clone self::$_view_cache[$file]) : (NULL);
		if (empty($view))
		{
			$view = View::factory($file);
			self::$_view_cache[$file] = $view;
		}
		return $view->set($data)->render();
	}

	/**
	 * Generate the error HTML.
	 *
	 * @param   array   the view data
	 * @return  string
	 */
	protected function _error($data = array())
	{
		$file = self::_get_view_path().'error';
		$view = (isset(self::$_view_cache[$file])) ? (clone self::$_view_cache[$file]) : (NULL);
		if (empty($view))
		{
			$view = View::factory($file);
			self::$_view_cache[$file] = $view;
		}
		return $view->set($data)->render();
	}

	/**
	 * Get the field id used in the HTML.
	 *
	 * @param	string	the field name
	 * @param	string	the field namespace
	 * @return	string
	 */
	public function get_field_id()
	{
		$name = Arr::get($this->_attributes, 'name');
		$namespace = Arr::get($this->_meta, 'namespace');
		return self::get_field_id($name, $namespace);
	}


	/**
	 * Get the field id used in the HTML.
	 *
	 * @param	string	the field name
	 * @param	string	the field namespace
	 * @return	string
	 */
	public static function get_field_id($name, $namespace = NULL)
	{
		if (empty($namespace))
		{
			return $name;
		}
		return $namespace.'_'.$name;
	}

	/**
	 * Get the escaped field name.
	 *
	 * @param	string	the field name
	 * @return	string
	 */
	public static function get_field_name($field_name)
	{
		return preg_replace('/\s+/', '_', $field_name);
	}

	/**
	 * Get the internal id used by the class.
	 *
	 * @param	string	the field name
	 * @param	string	the field namespace
	 * @return	string
	 */
	public static function get_internal_id($name, $namespace = NULL)
	{
		if ( ! isset($namespace))
		{
			$namespace = '';
		}
		return $namespace.'.'.$name;
	}

	/**
	 * Get the form field configuration settings.
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
				$type = strtolower(trim($temp));
			}
		}
		if (empty($type))
		{
			$type = 'text';
		}

		// Set the class name
		$class = 'MMI_Form_Field_';
		if (($type === 'checkbox' OR $type === 'radio') AND ! empty($choices))
		{
			$class .= 'Group_';
		}
		$class .= ucfirst($type);

		// Create the field
		if ( ! class_exists($class))
		{
			$msg = $class.' field does not exist.';
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}
		return new $class($options);
	}
} // End Kohana_MMI_Form_Field
