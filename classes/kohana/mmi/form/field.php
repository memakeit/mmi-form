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
	 * @var string the HTTP method
	 */
	protected static $_method;

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
	 * @var array the HTML attributes
	 */
	protected $_attributes = array();

	/**
	 * @var array the validation errors
	 */
	protected $_errors = array();

	/**
	 * @var MMI_Form the form instance
	 */
	protected $_form;

	/**
	 * @var array the label default settings
	 */
	protected $_label_defaults;

	/**
	 * @var array the associated meta data
	 */
	protected $_meta = array();

	/**
	 * @var boolean was the form data loaded?
	 */
	protected $_post_data_loaded = FALSE;

	/**
	 * @var boolean was form data posted?
	 */
	protected $_posted = FALSE;

	/**
	 * @var integer the current field state
	 */
	protected $_state = MMI_Form::STATE_INITIAL;

	/**
	 * Get the request method.
	 * Set whether to use HTML5 markup.  Set whether data was posted.
	 * Initialize the options.
	 *
	 * @param	array	an associative array of field options
	 * @return	void
	 */
	public function __construct($options = array())
	{
		$method = self::get_method();
		$options['_method'] = $method;

		$this->_init_options($options);
		$this->_posted = (strcasecmp($method, 'post') === 0);
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
			if (strcasecmp($name, 'value') === 0)
			{
				return strval(Arr::get($this->_attributes, 'value', ''));
			}
			return Arr::get($this->_attributes, $name);
		}

		if (strcasecmp($name, 'value') === 0)
		{
			$original = Arr::get($this->_meta, 'original');
			$value = $this->_value_as_string($value);
			$this->_meta['updated'] = ($original !== $value);
		}
		$this->_attributes[$name] = $value;
		return $this;
	}

	/**
 	 * Return the current and original value.
	 *
	 * @return	array
	 */
	public function diff()
	{
		return array
		(
			'original'	=> Arr::get($this->_meta, 'original'),
			'value'		=> $this->value(),
		);
	}

	/**
	 * Get or set an error.
	 * If no parameters are specified, all error messages are returned.
	 * If a message is specified, it is added to the error collection.
	 * This method is chainable when setting a value.
	 *
	 * @param	string	the error message
	 * @return	mixed
	 */
	public function error($msg = NULL)
	{
		$num_args = func_num_args();
		if ($num_args === 0)
		{
			return $this->_errors;
		}

		if (isset($msg) AND ! in_array($msg, $this->_errors))
		{
			$this->_errors[] = $msg;
		}
		return $this;
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
	 * Get or set the field id.
	 * This method is chainable when setting a value.
	 *
	 * @param	string	the field id
	 * @return	mixed
	 */
	public function id($value = NULL)
	{
		if (func_num_args() === 0)
		{
			$id = trim(strval(Arr::get($this->_attributes, 'id', '')));
			if ($id !== '')
			{
				$namespace = Arr::get($this->_meta, 'namespace', '');
				$id = self::field_id($id, $namespace);
			}
			return $id;
		}
		$this->_attributes['id'] = $value;
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
			if (strcasecmp($name, 'error') === 0)
			{
				return $this->_error_meta();
			}
			if (strcasecmp($name, 'label') === 0)
			{
				return $this->_label_meta();
			}
			if (strcasecmp($name, 'rules') === 0)
			{
				$this->_finalize_rules();
			}
			return Arr::get($this->_meta, $name);
		}

		$this->_meta[$name] = $value;
		return $this;
	}

	/**
	 * Get or set the field name.
	 * This method is chainable when setting a value.
	 *
	 * @param	string	the field name
	 * @return	mixed
	 */
	public function name($value = NULL)
	{
		if (func_num_args() === 0)
		{
			$name = trim(strval(Arr::get($this->_attributes, 'name', '')));
			if ($name !== '')
			{
				$namespace = Arr::get($this->_meta, 'namespace', '');
				$name = self::field_name($name, $namespace);
			}
			return $name;
		}
		$this->_attributes['name'] = $value;
		return $this;
	}

	/**
 	 * Get or set whether the field is required.
	 * This method is chainable when setting a value.
	 *
	 * @param	boolean	is the field required?
	 * @return	mixed
	 */
	public function required($value = NULL)
	{
		$attributes = $this->_attributes;
		if (func_num_args() === 0)
		{
			$required = Arr::get($attributes, 'required');
			return ( ! empty($required));
		}
		elseif ($value)
		{
			$this->_attributes['required'] = 'required';
		}
		elseif (array_key_exists('required', $attributes))
		{
			unset($this->_attributes['required']);
		}
		return $this;
	}

	/**
 	 * Get whether the field value has been updated.
	 *
	 * @return	string
	 */
	public function updated()
	{
		return Arr::get($this->_meta, 'updated', FALSE);
	}

	/**
 	 * Get or set a field value.
	 * If no value is specified, the current value is returned.
	 * This method is chainable when setting a value.
	 *
	 * @param	mixed	the value to set
	 * @return	string
	 */
	public function value($value = NULL)
	{
		if (func_num_args() === 0)
		{
			return Arr::get($this->_attributes, 'value', '');
		}
		return $this->attribute('value', $value);
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
	 * Reset the form field.
	 *
	 * @return	void
	 */
	public function reset()
	{
		$this->value(Arr::get($this->_meta, 'default', ''));
		$this->_errors = array();
		$this->_state = MMI_Form::STATE_INITIAL | MMI_Form::STATE_RESET;
	}

	/**
	 * Check whether the form field is valid.
	 *
	 * @return	void
	 */
	public function valid()
	{
		if ($this instanceof MMI_Form_Field_NonValidating)
		{
			return TRUE;
		}

		if ( ! $this->_posted)
		{
			return TRUE;
		}
		elseif ( ! $this->_post_data_loaded)
		{
			$this->_load_post_data();
			$this->_finalize_rules();
		}

		$attributes = $this->_attributes;
		$meta = $this->_meta;
		$name = MMI_Form::clean_id($this->name());
		$label = Arr::get($this->_label_meta(), 'html');
		$value = Arr::get($attributes, 'value', '');

		// Add validation settings
		$validate = Validate::factory(array($name => $value));
		$callbacks = Arr::get($meta, 'callbacks', array());
		foreach ($callbacks as $callback)
		{
			$method = array_shift($callback);
			$parms = array_shift($callback);
			if ( ! is_array($parms))
			{
				$parms = array();
			}
			$validate->callback($name, $method, $parms);
		}
		$validate->filters($name, Arr::get($meta, 'filters', array()));
		$validate->label($name, $label);
		$validate->rules($name, Arr::get($meta, 'rules', array()));

		if ( ! $validate->check())
		{
			$file = MMI_Form_Messages::get_path();
			$translate = MMI_Form_Messages::translate();
			$this->_errors = $validate->errors($file, $translate);
		}
		$this->_state |= MMI_Form::STATE_VALIDATED;
		return (count($this->_errors) === 0);
	}

	/**
	 * Initialize the field options.
	 * Separate the meta data from the HTML attributes.
	 *
	 * @param	array	an associative array of field options
	 * @return	void
	 */
	protected function _init_options($options)
	{
		if ( ! is_array($options))
		{
			$options = array();
		}
		if (array_key_exists('_scalar', $options))
		{
			unset($options['_scalar']);
		}

		// Ensure the type settings
		if ( ! isset($options['_type']))
		{
			$options['_type'] = Arr::get($options, 'type', 'input');
		}
		if ( ! isset($options['type']))
		{
			$options['type'] = Arr::get($options, '_type', 'text');
		}

		// Get the CSS class
		$class = $this->_combine_value($options, 'class');

		// Merge the user-specified and config settings
		$options = $this->_merge_options($options);

		// Set the label defaults
		$label_defaults = $this->_get_form_meta('label', array());
		$label_type_specific = Arr::path(self::get_config(TRUE), $options['type'].'._label', array());
		$this->_label_defaults = Arr::merge($label_defaults, $label_type_specific);

		// Set the CSS class
		if ( ! empty($class))
		{
			$options['class'] = $class;
		}

		// Ensure values are cast to strings
		$value = Arr::get($options, 'value', '');
		$options['value'] = $this->_value_as_string($value);

		// Set defaults
		if ( ! array_key_exists('_default', $options))
		{
			$options['_default'] = $value;
		}
		if ( ! array_key_exists('_original', $options))
		{
			$options['_original'] = $value;
		}
		$options['_updated'] = FALSE;

		// Process the required attribute
		$required = Arr::get($options, 'required', FALSE);
		if ( ! array_key_exists('required', $options))
		{
			if (Arr::get($options, '_required', FALSE))
			{
				$options['required'] = 'required';
			}
		}
		if ( ! $required AND array_key_exists('required', $options))
		{
			unset($options['required']);
		}

		// Ensure the field has an id attribute
		$id = trim(strval(Arr::get($options, 'id', '')));
		$name = trim(strval(Arr::get($options, 'name', '')));
		if ($id === '' AND $name !== '')
		{
			$options['id'] = $name;
		}
		elseif ($name === '' AND $id !== '')
		{
			$options['name'] = $id;
		}
		if ($id === '')
		{
			$options['id'] = str_replace('.', '', microtime(TRUE));
			$options['_id_generated'] = TRUE;
		}
		if ($name === '')
		{
			$options['name'] = $options['id'];
		}

		// Separate the meta data from the HTML attributes
		$attributes = array();
		$meta = array();
		foreach ($options as $name => $value)
		{
			$name = trim($name);
			if (substr($name, 0, 1) === '_')
			{
				$meta[trim($name, '_')] = $value;
			}
			else
			{
				$attributes[$name] = $value;
			}
		}
		$this->_attributes = $attributes;
		$this->_meta = $meta;
	}

	/**
	 * Merge the user-specified and config settings.
	 * 	 *
	 * @param	array	an associative array of field options
	 * @return	array
	 */
	protected function _merge_options($options)
	{
		if ( ! is_array($options))
		{
			$options = array();
		}

		// Ensure field sub-arrays are properly merged
		$config_default = $this->_get_form_meta('field', array());
		$config_type = self::get_config()->get($options['type'], array());
		foreach (array('_callbacks', '_filters', '_rules') as $name)
		{
			$value = Arr::get($options, $name, array());
			if ( ! empty($value))
			{
				$value_default = Arr::get($config_default, $name, array());
				$value_type = Arr::get($config_type, $name, array());
				$options[$name] = array_merge($value_default, $value_type, $value);
			}
		}
		return array_merge($config_default, $config_type, $options);
	}


	/**
	 * Cast a value to a string.  If the value is an array, each array value is cast to a string.
	 *
	 * @param	mixed	the value to cast to a string
	 * @return	mixed
	 */
	protected function _value_as_string($input)
	{
		if (is_scalar($input))
		{
			$input = strval($input);
		}
		elseif (is_array($input))
		{
			foreach($input as $name => $value)
			{
				if (is_array($value))
				{
					$value = $this->_value_as_string($value);
				}
				elseif (is_scalar($input))
				{
					$value = strval($value);
				}
				$input[$name] = $value;
			}
		}
		return $input;
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
		$defaults = $this->_get_form_meta('field', array());
		$type = Arr::get($options, 'type', 'text');
		$type_specific = $config->get($type, array());
		$value =
			Arr::get($defaults, $key, '').' '.
			Arr::get($type_specific, $key, '').' '.
			Arr::get($options, $key, '')
		;
		$value = trim(preg_replace('/\s+/', ' ', $value));

		// Remove duplicates
		if ($value !== '')
		{
			$value = array_unique(explode(' ', $value));
			$value = implode(' ', $value);
		}
		return $value;
	}

	/**
	 * Load the post data.
	 *
	 * @return	void
	 */
	protected function _load_post_data()
	{
		if ($this instanceof MMI_Form_Field_NonPosting)
		{
			$this->_post_data_loaded = TRUE;
			$this->_state |= MMI_Form::STATE_POSTED;
			return;
		}

		if ( ! $this->_posted)
		{
			return;
		}

		if ( ! empty($_POST))
		{
			$name = MMI_Form::clean_id($this->name());
			$original = Arr::get($this->_meta, 'original');
			$posted = $this->_apply_filters(Arr::get($_POST, $name, ''));
			$this->_meta['posted'] = $posted;
			$this->_meta['updated'] = ($original !== $posted);
			$this->_attributes['value'] = $posted;
		}
		$this->_post_data_loaded = TRUE;
		$this->_state |= MMI_Form::STATE_POSTED;
	}

	/**
	 * Apply the filters to the field value.
	 *
	 * @param	mixed	the field value
	 * @return	mixed
	 */
	protected function _apply_filters($value)
	{
		// Process array values
		if (is_array($value))
		{
			foreach ($value as $idx => $val)
			{
				$value[$idx] = $this->_apply_filters($val);
			}
			return $value;
		}

		$filters = Arr::get($this->_meta, 'filters', array());
		if (empty($filters))
		{
			return $value;
		}

		foreach ($filters as $filter => $params)
		{
			// Add the field value to the parameters
			if ( ! isset($params))
			{
				$params = array();
			}
			array_unshift($params, $value);

			if (strpos($filter, '::') === FALSE)
			{
				// Use a function call
				$function = new ReflectionFunction($filter);

				// Call $function($this[$field], $param, ...) with Reflection
				$value = $function->invokeArgs($params);
			}
			else
			{
				// Split the class and method of the rule
				list($class, $method) = explode('::', $filter, 2);

				// Use a static method call
				$method = new ReflectionMethod($class, $method);

				// Call $class::$method($this[$field], $param, ...) with Reflection
				$value = $method->invokeArgs(NULL, $params);
			}
		}
		return $value;
	}

	/**
	 * Perform any pre-rendering logic.
	 *
	 * @return	void
	 */
	protected function _pre_render()
	{
		if ($this->_posted AND ! $this->_post_data_loaded)
		{
			$this->_load_post_data();
		}
		$this->_state |= MMI_Form::STATE_PRE_RENDERED;
	}

	/**
	 * Get the error label settings.
	 *
	 * @return	array
	 */
	protected function _error_meta()
	{
		$error = Arr::get($this->_meta, 'error', array());
		$error['for'] = $this->id();
		$error['_html'] = implode('<br />', $this->_errors);
		return $error;
	}

	/**
	 * Get the label settings.
	 *
	 * @return	array
	 */
	protected function _label_meta()
	{
		$label = Arr::get($this->_meta, 'label', array());

		if ( ! is_array($label))
		{
			$label = array_merge($this->_label_defaults, array('_html' => $label));
		}
		$label['for'] = $this->id();
		$label['_html'] = trim(strval(Arr::get($label, '_html', '')));
		return $label;
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
			// Use the default view
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
		$id = trim(strval(Arr::get($attributes, 'id', '')));
		$name = trim(strval(Arr::get($attributes, 'name', '')));
		if ($name === '' AND $id !== '')
		{
			$attributes['name'] = $id;
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

		// Do not include generated id's in the HTML output
		$id_generated = Arr::get($meta, 'id_generated', FALSE);
		if ($id_generated)
		{
			unset($attributes['id']);
		}
		else
		{
			$id = $this->id();
			$attributes['id'] = $id;
			$name = trim(strval(Arr::get($this->_attributes, 'name', '')));
			if ($name === '')
			{
				$attributes['name'] = $id;
			}
			else
			{
				$attributes['name'] = $this->name();
			}
		}

		// Process the value
		$is_group = ($this instanceof MMI_Form_Field_Group);
		$value = Arr::get($attributes, 'value');
		if (is_null($value) OR ( ! $is_group AND ! is_scalar($value)))
		{
			$attributes['value'] = '';
		}

		// If a rule for max-length exists, use it to set the attribute
		if (in_array('maxlength', $allowed))
		{
			$rules = Arr::get($meta, 'rules', array());
			if (array_key_exists('max_length', $rules))
			{
				$max_length = array_values($rules['max_length']);
				$attributes['maxlength'] = $max_length[0];
			}
		}

		// If a title is not set, use the description if present
		$description = trim(strval(Arr::get($meta, 'description', '')));
		$title = trim(strval(Arr::get($attributes, 'title', '')));
		if ($title === '' AND $description !== '')
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
		if ($this->_get_form_meta('html5', TRUE))
		{
			return MMI_HTML5_Attributes_Input::get($type);
		}
		return MMI_HTML4_Attributes_Input::get($type);
	}

	/**
	 * Finalize validation rules.
	 *
	 * @return	void
	 */
	protected function _finalize_rules()
	{
		$attributes = $this->_attributes;
		$rules = Arr::get($this->_meta, 'rules');
		if ( ! is_array($rules))
		{
			$rules = array();
		}

		// Process required attribute
		$required = Arr::get($attributes, 'required');
		if ( ! empty($required))
		{
			$rules['not_empty'] = NULL;
		}

		// Process pattern attribute
		$pattern = trim(strval(Arr::get($attributes, 'pattern', '')));
		if ($pattern !== '')
		{
			if (substr($pattern, 0, 1) !== '/')
			{
				$pattern = '/'.$pattern.'/';
			}
			$rules['regex'] = array($pattern);
		}

		// Process rules that are executed even when the value is empty
		$found = array_intersect(array_keys($rules), self::$_empty_rules);
		if (count($found) > 0)
		{
			$rules['not_empty'] = NULL;
		}

		// Process rules that have a UTF8 parameter
		$utf8_rules = self::$_utf8_rules;
		if ($this->_get_form_meta('unicode', FALSE))
		{
			foreach ($rules as $name => $parms)
			{
				if (empty($parms) AND in_array($name, $utf8_rules))
				{
					$rules[$name] = array(TRUE);
				}
			}
		}
		$this->_meta['rules'] = $rules;
	}

	/**
	 * Retrieve a meta value from the associated form.
	 *
	 * @param	string	the meta name
	 * @param	mixed	the default value
	 * @return	mixed
	 */
	protected function _get_form_meta($name, $default = NULL)
	{
		$form = $this->form();
		if ($form instanceof MMI_Form)
		{
			$value = $form->meta($name);
		}
		if ( ! isset($value))
		{
			$value = $default;
		}
		return $value;
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
	 * Get the HTTP method.
	 *
	 * @return	string
	 */
	public static function get_method()
	{
		(self::$_method === NULL) AND self::$_method = Arr::get($_SERVER, 'REQUEST_METHOD', '');
		return self::$_method;
	}

	/**
	 * Create a form field instance.
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
		}
		if (empty($type))
		{
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
		$type = str_replace('-', '', $type);

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
			if (class_exists('MMI_Log'))
			{
				MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			}
			throw new Kohana_Exception($msg);
		}
		return new $class($options);
	}

	/**
	 * Get the field id.
	 *
	 * @param	string	the field id
	 * @param	string	the field namespace
	 * @return	string
	 */
	public static function field_id($id, $namespace = NULL)
	{
		$id = MMI_Form::clean_id($id);
		$namespace = MMI_Form::clean_id($namespace);
		if ($namespace === '')
		{
			return $id;
		}
		if (substr($namespace, -1) === '_')
		{
			return $namespace.$id;
		}
		return $namespace.'_'.$id;
	}

	/**
	 * Get the field name.
	 *
	 * @param	string	the field name
	 * @param	string	the field namespace
	 * @return	string
	 */
	public static function field_name($name, $namespace = NULL)
	{
		$name = preg_replace('/[^-a-z\d_\[\]]/i', '', $name);
		$namespace = MMI_Form::clean_id($namespace);
		if ($namespace === '')
		{
			return $name;
		}
		if (substr($namespace, -1) === '_')
		{
			return $namespace.$name;
		}
		return $namespace.'_'.$name;
	}
} // End Kohana_MMI_Form_Field
