<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A form field.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
abstract class Kohana_MMI_Form_Field extends MMI_Form_Element
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
	 * @var array the validation errors
	 */
	protected $_errors = array();

	/**
	 * @var boolean was form data posted?
	 */
	protected $_posted = FALSE;

	/**
	 * @var integer the current field state
	 */
	protected $_state = MMI_Form::STATE_INITIAL;

	/**
	 * Set whether to use HTML5 markup.
	 * Initialize the options.
	 * Load post data.
	 *
	 * @param	array	an associative array of field options
	 * @return	void
	 */
	public function __construct($options = array())
	{
		parent::__construct($options);
		$this->_load_post_data();
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
			if ( ! is_scalar($value))
			{
				$msg = 'Only scalar values can be used to set the value of a form field.';
				MMI_Log::log_error(__METHOD__, __LINE__, $msg);
				throw new Kohana_Exception($msg);
			}
			$original = Arr::get($this->_meta, 'original');
			$value = strval($value);
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

		if ( ! empty($msg) AND ! in_array($msg, $this->_errors))
		{
			$this->_errors[] = $msg;
		}
		return $this;
	}

	/**
	 * Get the field id.
	 *
	 * @return	string
	 */
	public function id()
	{
		$id = Arr::get($this->_attributes, 'id', '');
		$namespace = Arr::get($this->_meta, 'namespace', '');
		return self::field_id($id, $namespace);
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
			return Arr::get($this->_meta, $name);
		}

		$this->_meta[$name] = $value;
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
	 * Reset the form field.
	 *
	 * @return	void
	 */
	public function reset()
	{
		$this->value(Arr::get($this->_meta, 'default', ''));
		$this->_state |= MMI_Form::STATE_RESET;
	}

	/**
	 * Check whether the form field is valid.
	 *
	 * @return	void
	 */
	public function valid()
	{
		if ( ! $this->_posted)
		{
			return TRUE;
		}

		$attributes = $this->_attributes;
		$meta = $this->_meta;
		$id = $this->id();
		$label = trim(Arr::get($this->_label_meta(), 'html'), ':');
		$value = Arr::get($attributes, 'value', '');

		// Add validation settings
		$validate = Validate::factory(array($id => $value));
		$validate->callbacks($id, Arr::get($meta, 'callbacks', array()));
		$validate->filters($id, Arr::get($meta, 'filters', array()));
		$validate->label($id, $label);
		$validate->rules($id, Arr::get($meta, 'rules', array()));

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
	 * Merge the user-specified and config file settings.
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

		// Ensure the type settings
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

		// Set defaults
		$value = strval(Arr::get($options, 'value', ''));
		if (empty($options['_default']))
		{
			$options['_default'] = $value;
		}
		$options['_original'] = $value;
		$options['_updated'] = FALSE;
		$options['value'] = $value;

		// Ensure the field has an id attribute
		$id = Arr::get($options, 'id');
		if (empty($id) AND ! empty($options['name']))
		{
			$options['id'] = $options['name'];
		}
		elseif (empty($id))
		{
			$options['id'] = strval(time());
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
			Arr::get($options, $key, '')
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
	 * Load the post data into the models and fields.
	 *
	 * @return	void
	 */
	protected function _load_post_data()
	{
		if ( ! $_POST)
		{
			return;
		}

		$post = Security::xss_clean($_POST);
		if ( ! empty($post))
		{
			$original = Arr::get($this->_meta, 'original');
			$posted = strval(Arr::get($post, $this->id(), ''));
			$this->_meta['posted'] = $posted;
			$this->_meta['updated'] = ($original !== $posted);
			$this->_attributes['value'] = $posted;
		}
		$this->_posted = TRUE;
		$this->_state |= MMI_Form::STATE_POSTED;
	}

	/**
	 * Perform any pre-rendering logic.
	 *
	 * @return	void
	 */
	protected function _pre_render()
	{
		$this->_finalize_rules();
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
		$attributes = $this->_attributes;
		$meta = $this->_meta;
		$label = Arr::get($meta, 'label', array());
		$label['for'] = $this->id();
		$html = Arr::get($label, '_html');
		if (empty($html))
		{
			$html = Arr::get($attributes, 'title');
		}
		if (empty($html))
		{
			$html = Arr::get($meta, 'description');
		}
		if (empty($html))
		{
			$html = Arr::get($attributes, 'placeholder');
		}
		if ( ! empty($html) AND substr($html, -1) !== ':')
		{
			$html .= ':';
		}
		$label['html'] = $html;
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
		$id = $this->id();
		$attributes['id'] = $id;
		$attributes['name'] = $id;

		$value = Arr::get($attributes, 'value');
		if (is_null($value) OR ! is_scalar($value))
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
		if (in_array('maxlength', $allowed))
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
		if (MMI_Form::unicode())
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
		if (empty($namespace))
		{
			return $id;
		}
		return $namespace.'_'.$id;
	}
} // End Kohana_MMI_Form_Field
