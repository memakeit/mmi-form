<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form generator.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license	http://www.memakeit.com/license
 */
class Kohana_MMI_Form
{
	// Class constants
	const ERROR_GENERAL = '_';

	// Field order constants
	const ORDER_ERROR = 'err';
	const ORDER_FIELD = 'fld';
	const ORDER_LABEL = 'lbl';

	// State constants
	const STATE_INITIAL = 1;
	const STATE_POSTED = 2;
	const STATE_VALIDATED = 4;
	const STATE_SAVED = 8;
	const STATE_RESET = 16;
	const STATE_FROZEN = 32;
	const STATE_RENDERED = 64;

	/**
	 * @var Kohana_Config the form configuration
	 */
	protected static $_config;

	/**
	 * @var array the view cache
	 */
	protected static $_view_cache = array();

	/**
	 * @var array the HTML attributes
	 */
	protected $_attributes = array();

	/**
	 * @var array the form errors
	 */
	protected $_errors = array();

	/**
	 * @var array the field objects
	 */
	protected $_fields = array();

	/**
	 * @var boolean use HTML5 markup?
	 */
	protected $_html5;

	/**
	 * @var array the associated meta data
	 */
	protected $_meta = array();

	/**
	 * @var array the form namespaces
	 */
	protected $_namespaces = array();

	/**
	 * @var array the form plugins
	 */
	protected $_plugins = array();

//	/**
//	 * @var array the post data
//	 */
//	protected $_post_data = array();

	/**
	 * @var boolean was form data posted?
	 */
	protected $_posted = FALSE;

	/**
	 * @var integer the current form state
	 */
	protected $_state = self::STATE_INITIAL;

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
		$this->_posted = ( ! empty($_POST));
	}

	/**
	 * Add a plugin.
	 * This method is chainable.
	 *
	 * @param	mixed	a MMI_Form_Plugin object or a string specifying the plugin type
	 * @param	mixed	the plugin method prefix
	 * @param	array	an associative array of plugin options
	 * @return	MMI_Form
	 */
	public function add_plugin($plugin, $method_prefix, $options = array())
	{
		if ($this->_state !== self::STATE_INITIAL)
		{
			$msg = 'Plugins can only be added when the form is in its initial state.';
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}

		if (is_string($plugin))
		{
			// Create the plugin object
			$plugin = MMI_Form_Plugin::factory($plugin, $options);
			$plugin->form($this);
			$plugin->method_prefix($method_prefix);
		}
		if ($plugin instanceof MMI_Form_Plugin)
		{
			// Add the plugin
			$plugin_name = $plugin->name();
			$this->_plugins[$plugin_name] = $plugin;
		}
		return $this;
	}

	/**
	 * Remove a plugin.
	 * This method is chainable.
	 *
	 * @param	mixed	a MMI_Form_Plugin object or a string specifying the plugin type
	 * @return	MMI_Form
	 */
	public function remove_plugin($plugin)
	{
		if ($this->_state !== self::STATE_INITIAL)
		{
			$msg = 'Plugins can only be removed when the form is in its initial state.';
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}

		if ($plugin instanceof MMI_Form_Plugin)
		{
			$plugin = $plugin->name();
		}
		if (is_string($plugin) AND array_key_exists($plugin, $this->_plugins))
		{
			unset($this->_plugins[$plugin]);
		}
		return $this;
	}

	/**
	 * Add a form field.
	 * When a MMI_Form_Field object is specified, the options array is not used.
	 * This method is chainable.
	 *
	 * @param	mixed	a MMI_Form_Field object or a string specifying the field type
	 * @param	array	an associative array of field options
	 * @return	MMI_Form
	 */
	public function add_field($field, $options = array())
	{
		if ($this->_state !== self::STATE_INITIAL)
		{
			$msg = 'Fields can only be added when the form is in its initial state.';
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}

		// Create the field object
		if ( ! empty($field) AND is_string($field))
		{
			$field = MMI_Form_Field::factory($field, $options);
		}

		// Add the namespace
		$namespace = $field->meta('namespace');
		if ( ! empty($namespace))
		{
			$id = self::clean_id($namespace);
			$namespace = trim(strtolower($namespace));
			$this->_namespaces[$id] = $namespace;
		}

//		// Set a reference to the form in the field
//		$field->form($this);

		// Add the field
		$id = $this->_generate_id_from_field($field);
		$this->_fields[$id] = $field;
		return $this;
	}

	/**
	 * Generate an id using the id and namespace settings of a form field object.
	 *
	 * @param	MMI_Form	a MMI_Form_Field object
	 * @return	string
	 */
	protected function _generate_id_from_field(MMI_Form_Field $field)
	{
		$id = self::clean_id($field->attribute('id'));
		$namespace = self::clean_id($field->meta('namespace'));
		return $namespace.'.'.$id;
	}

	/**
	 * Remove one or more form fields.
	 * This method is chainable.
	 *
	 * To remove a single field:
	 *	- specify a field object (namespace parameter is ignored)
	 *	- specify a field id (namespace is used)
	 *
	 * To remove multiple fields:
	 *	- '*' without a namespace removes all fields
	 *	- '*' with a namespace removes all fields for the namespace
	 *	- an array (of strings) removes multiple fields for a namespace
	 *
	 * @param	mixed	a form identifier, object, or wildcard character (*)
	 * @param	string	the namespace
	 * @return	MMI_Form
	 */
	public function remove_field($field, $namespace = NULL)
	{
		if ($this->_state !== self::STATE_INITIAL)
		{
			$msg = 'Fields can only be removed when the form is in its initial state.';
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}

		// Process a form object
		if ($field instanceof MMI_Form_Field)
		{
			$id = $this->_generate_id_from_field($field);
			if (array_key_exists($id, $this->_fields))
			{
				unset($this->_fields[$id]);
			}
		}

		// Process wildcards
		elseif ($field === '*' AND empty($namespace))
		{
			// Remove all fields
			$this->_fields = array();
		}
		elseif ($field === '*' AND ! empty($namespace))
		{
			// Remove all fields for the namespace
			$namespace = self::clean_id($namespace);
			foreach ($this->_fields as $key => $field)
			{
				list($ns) = explode('.', $key);
				if ($ns === $namespace)
				{
					unset($this->_fields[$key]);
				}
			}
		}

		// Process a field name (string)
		elseif ( ! empty($field) AND is_string($field))
		{
			$id = self::clean_id($field);
			$namespace = self::clean_id($namespace);
			$id = $namespace.'.'.$id;
			if (array_key_exists($id, $this->_fields))
			{
				unset($this->_fields[$id]);
			}
		}

		// Process an array of field names (strings)
		elseif (is_array($field) AND count($field) > 0)
		{
			$namespace = self::clean_id($namespace);
			foreach ($field as $item)
			{
				$id = $namespace.'.'.self::clean_id($item);
				if (array_key_exists($id, $this->_fields))
				{
					unset($this->_fields[$id]);
				}
			}
		}
		return $this;
	}

	public static function view_cache($key, $value = NULL)
	{
		if (func_num_args() === 1)
		{
			return Arr::get(self::$_view_cache, $key);
		}
		self::$_view_cache[$key] = $value;
	}


	/**
	 * Validate the form.
	 *
	 * @return	boolean
	 */
	public function valid()
	{
		if ($this->_state ^ self::STATE_POSTED)
		{
			$this->_load_post_data();
		}

		foreach ($this->_fields as $field)
		{
			$field->valid();
		}
		$this->_validate();
		$this->_state |= self::STATE_VALIDATED;
		return (count($this->_errors) === 0);
	}

	/**
	 * Get one or more form fields.
	 *
	 * @param	string  the field id
	 * @param	string  the field namespace
	 * @return	array
	 */
	public function field($id = NULL, $namespace = NULL)
	{
		$fields = $this->_fields;
		if ( ! empty($id))
		{
			$id = self::clean_id($id);
			$namespace = self::clean_id($namespace);
			foreach ($fields as $key => $field)
			{
				list($ns, $_id) = explode('.', $key);
				if ($ns === $namespace AND $_id === $id)
				{
					return $field;
				}
			}
		}
		elseif (empty($id) AND ! empty($namespace))
		{
			$namespace = self::clean_id($namespace);
			$data = array();
			foreach ($fields as $key => $field)
			{
				list($ns) = explode('.', $key);
				if ($ns === $namespace)
				{
					$idx = $this->_generate_id_from_field($field);
					$data[$idx] = $field;
				}
			}
			return $data;
		}
		else
		{
			return $fields;
		}
	}

	/**
	 * Get or set an error.
	 * If no parameters are specified, all error messages are returned.
	 * If a message is specified, it is added to the error collection.
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

		if ($this->_state ^ MMI_Form::STATE_FROZEN)
		{
			if ( ! empty($msg) AND ! in_array($msg, $this->_errors))
			{
				$this->_errors[] = $msg;
			}
			return $this;
		}
		else
		{
			$msg = 'Errors can not be set after the form has been frozen.';
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}
	}

	/**
	 * Set a form field value.
	 * This method is chainable.
	 *
	 * @param   string  the field name
	 * @param   string  the model name
	 * @param   mixed   the value to set
	 * @return  MMI_Form
	 */
	public function set_value($field_name, $value)
	{
		// Set the field value
		$field_id = MMI_Form_Field::get_field_id($model_name, $field_name);
		$field = $this->_fields[$field_id];
		$field->value = $value;
		return $this;
	}

	/**
	 * Save the model(s).
	 *
	 * @return  boolean
	 */
	public function save()
	{
		$this->_state |= self::STATE_SAVED;
		return TRUE;
	}

	/**
	 * Reset the form.
	 *
	 * @return  void
	 */
	public function reset()
	{
		foreach ($this->_fields as $field)
		{
			$field->reset();
		}
		$this->_state |= self::STATE_RESET;
	}

	/**
	 * Generate the form HTML.
	 *
	 * @return  string
	 */
	public function render()
	{
		// Ensure post data is processed
		if ($this->_state ^ self::STATE_POSTED)
		{
			$this->_load_post_data();
		}

		$this->_freeze();
		$frm = array();

		$frm[] = $this->_form_open();
//		// Feedback messages
//		$frm[] = $this->_get_messages();

		// Form fields
		foreach ($this->_fields as $field)
		{
			$order = $field->meta('order');
			foreach ($order as $order_type)
			{
				switch ($order_type)
				{
					case self::ORDER_ERROR:
						$frm[] = MMI_Form_Label::factory($field->meta('error'))->render();
						break;

					case self::ORDER_FIELD:
						$frm[] = $field->render();
						break;

					case self::ORDER_LABEL:
						$frm[] = MMI_Form_Label::factory($field->meta('label'))->render();
						break;
				}
			}
		}

		$frm[] = $this->_form_close();
		$this->_state |= self::STATE_RENDERED;
		return implode(PHP_EOL, $frm);
	}

	protected function _form_open()
	{
		$meta = $this->_meta;
		$open = Arr::get($meta, 'open', array());
		$dir = Arr::get($open, 'view_path', 'mmi/form');
		$file = Arr::get($open, 'view', 'open');
		if ( ! Kohana::find_file('views/'.$dir, $file))
		{
			// Use the default view
			$file = 'open';
		}
		$file = $dir.'/'.$file;

		$attributes = $this->_attributes;
		return View::factory($file, array
		(
			'before' => Arr::get($open, '_before', ''),
			'after' => Arr::path($open, '_after', ''),
			'action' => Arr::get($attributes, 'action', Request::instance()->uri),
			'attributes' => $attributes,
		))->render();
	}

	protected function _form_close()
	{
		$meta = $this->_meta;
		$close = Arr::get($meta, 'close', array());
		$dir = Arr::get($close, 'view_path', 'mmi/form');
		$file = Arr::get($close, 'view', 'close');
		if ( ! Kohana::find_file('views/'.$dir, $file))
		{
			// Use the default view
			$file = 'close';
		}
		$file = $dir.'/'.$file;

		return View::factory($file, array
		(
			'before' => Arr::get($close, '_before', ''),
			'after' => Arr::get($close, '_after', ''),
		))->render();
	}

	/**
	 * Add CSRF validation to the form.
	 * This method is chainable.
	 *
	 * @return  MMI_Form
	 */
	public function add_csrf()
	{
		$this->add_plugin('csrf', 'csrf_');
		return $this;
	}

	/**
	 * Add a CAPTCHA to the form.
	 * This method is chainable.
	 *
	 * @param   string  the captcha driver
	 * @param   array   captcha plugin options
	 * @param   array   field options
	 * @return  MMI_Form
	 */
	public function add_captcha($driver = NULL, $plugin_options = NULL, $field_options = NULL)
	{
		if (empty($driver))
		{
			$driver = 'recaptcha';
		}
		if (empty($plugin_options))
		{
			$plugin_options = array();
		}
		if (empty($field_options))
		{
			$field_options = array();
		}

		$captcha = MMI_Form_Plugin::factory($this, $driver, $plugin_options);
		$this->add_field
		(
			array
			(
				'name'      => $driver,
				'type'      => 'html',
				'html'      => array($captcha, 'html'),
				'source'    => MMI_Form_Field_HTML::SOURCE_CALLBACK,
				'callbacks' => array
				(
					array($captcha, 'valid', NULL),
				),
			),
			self::FORM_ONLY_FIELD,
			$field_options
		);
		return $this;
	}

	/**
	 * Add a submit button to the form.
	 * This method is chainable.
	 *
	 * @param   string  the button text
	 * @param   array   field-specific options
	 * @return  MMI_Form
	 */
	public function add_submit_button($text = 'Submit', $options = array())
	{
		$before = Arr::get($options, '_before');
		if (empty($before))
		{
			$options['_before'] = '<p class="btn">';
		}

		$after = Arr::get($options, '_after');
		if (empty($after))
		{
			$options['_after'] = '</p>';
		}

		$this->add_field
		(
			array
			(
				'name'  => 'submit',
				'type'  => 'submit',
				'value' => $text
			),
			self::FORM_ONLY_FIELD,
			$options
		);
		return $this;
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

	/**
	 * Get the form id.
	 *
	 * @return  string
	 */
	public function id()
	{
		return Arr::get($this->_options_form, 'id');
	}

	/**
	 * Get the symbol used to denote a required form field.
	 *
	 * @return  string
	 */
	public function required_symbol()
	{
		return Arr::get($this->_options_form, '_required_symbol');
	}

	/**
	 * The form accepts unicode input.
	 *
	 * @return  string
	 */
	public static function unicode()
	{
		return self::get_config()->get('_unicode', FALSE);
	}

	/**
	 * Set a variable.
	 *
	 * @param   string  key
	 * @param   mixed   value
	 * @return  void
	 */
	public function __set($key, $value)
	{
		$this->_set($key, $value);
	}

	/**
	 * Process method names that do not exist.
	 * Used to process calls to plugin methods.
	 *
	 * @param   string  the method name
	 * @param   array   the method arguments
	 * @return  mixed
	 */
	public function __call($method, $args)
	{
		$plugin_name = '';
		foreach ($this->_plugins as $name => $plugin)
		{
			$prefix = $plugin['method_prefix'];
			if (stripos($method, $prefix) === 0)
			{
				$plugin_name = $name;
				$method = str_replace($prefix, '', $method);
				break;
			}
		}

		if ( ! empty($plugin_name))
		{
			// Use reflection to invoke the plugin method
			if ( ! is_array($args))
			{
				$args = array();
			}
			$plugin = $this->_plugins[$plugin_name]['plugin'];
			$method = new ReflectionMethod($plugin, $method);
			return $method->invokeArgs($plugin, $args);
		}

		// Plugin method not found
		$msg = 'Plugin method not found: '.$method.'.';
		if ( ! empty($plugin_name))
		{
			$msg .= 'Plugin name: '.$plugin_name.'.';
		}
		Kohana::$log->add(Kohana::ERROR, '['.__METHOD__.' @ line '.__LINE__.'] '.$msg)->write();
		throw new Kohana_Exception($msg);
	}

	/**
	 * Initialize the form options.
	 *
	 * @param   array   form, field, error, and label options
	 * @return  void
	 */
	protected function _init_options($options)
	{
		if ( ! is_array($options))
		{
			$options = array();
		}

		// Set defaults
		$config = self::get_config();
		if (empty($options['id']))
		{
			$options['id'] = 'mmi_frm';
		}
		if (empty($options['_required_symbol']))
		{
			$options['_required_symbol'] = $config->get('_required_symbol', '*');
		}

//		// Get the CSS class
//		$class = $this->_combine_value($options, 'class');

		// Merge the user-specified and config settings
		$defaults = $config->get('_defaults', array());
		$options = array_merge($defaults, $options);

//		// Set the CSS class
//		if ( ! empty($class))
//		{
//			$options['class'] = $class;
//		}

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
	 * Load the post data into the models and fields.
	 *
	 * @return  void
	 */
	protected function _load_post_data()
	{
		if ( ! $this->_posted)
		{
			return;
		}
		$this->_state |= self::STATE_POSTED;
	}

	/**
	 * Validate the form-only fields.  Validation errors can be retrieved via the errors() method.
	 *
	 * @return  void
	 */
	protected function _validate()
	{
		$errors = array();
		$validate = Validate::factory($this->_get_form_only_data());
		foreach ($this->_fields as $name => $field)
		{
			if ($field->model_name === self::FORM_ONLY_FIELD)
			{
				// Add validation settings
				$validate->label($name, $field->label);
				$validate->filters($name, $field->filters);
				$validate->rules($name, $field->rules);
				$validate->callbacks($name, $field->callbacks);
			}
		}

		if ( ! $validate->check())
		{
			foreach ($validate->errors() as $field_name => $error)
			{
				$field = $this->_fields[$field_name];
				$this->_errors[$field_name] = $this->format_error_message($field->label, $error[0], $error[1]);
			}
		}
	}

	/**
	 * Freeze the form (and its fields), preventing further modifications.
	 *
	 * @return	void
	 */
	protected function _freeze()
	{
		foreach ($this->_fields as $field)
		{
			$field->freeze();
		}
		$this->_state |= self::STATE_FROZEN;
	}







/**
	 * Get the form messages.
	 *
	 * @return  string
	 */
	protected function _get_messages()
	{
		$attributes = array();
		$msg_options = Arr::get($this->_options_form, '_messages', array());
		$msg = '';
		$type = '?';

		if (count($msg_options) > 0)
		{
			if ($this->_posted)
			{
				$errors = $this->_errors;
				if (count($errors) > 0)
				{
					$failure_msgs = Arr::path($msg_options, '_failure._msg', array());
					if (array_key_exists(self::ERROR_GENERAL, $errors))
					{
						$msg = Arr::get($failure_msgs, 'general', '');
					}
					else
					{
						$count = count($errors);
						if ($count === 1)
						{
							$msg = Arr::path($failure_msgs, 'single', '');
						}
						else
						{
							$msg = Arr::path($failure_msgs, 'multiple', '');
						}
						$msg = sprintf($msg, $count);
					}
					$type = '_failure';
				}
				else
				{
					$msg = Arr::path($msg_options, '_success._msg', '');
					$type = '_success';
				}
			}

			$attributes = Arr::merge
			(
				self::attributes($msg_options),
				self::attributes(Arr::get($msg_options, $type, array()))
			);
			$attributes['class'] = trim(Arr::get($msg_options, 'class', '').' '.Arr::path($msg_options, $type.'.class', ''));
			$attributes['id'] = Arr::get($this->_options_form, 'id', 'frm').'_status';
		}
		return '<div'.HTML::attributes($attributes).'>'.$msg.'</div>';
	}




	/**
	 * Set a variable.
	 *
	 * @param   string  key
	 * @param   mixed   value
	 * @return  void
	 */
	protected function _set($key, $value)
	{
		if ($this->_state !== self::STATE_INITIAL)
		{
			$msg = 'Values can only be set when the form is in its initial state.';
			Kohana::$log->add(Kohana::ERROR, '['.__METHOD__.' @ line '.__LINE__.'] '.$msg)->write();
			throw new Kohana_Exception($msg);
		}
		else
		{
			$this->$key = $value;
		}
	}




	/**
	 * Remove invalid characters from an id.
	 *
	 * @param	string	the original id
	 * @return	string
	 */
	public static function clean_id($id)
	{
		return preg_replace('/[^-a-z\d_]/i', '', $id);
	}

	/**
	 * Get the form configuration settings.
	 *
	 * @param	boolean	return the configuration as an array?
	 * @return	mixed
	 */
	public static function get_config($as_array = FALSE)
	{
		(self::$_config === NULL) AND self::$_config = Kohana::config('mmi-form');
		$config = self::$_config;
		if ($as_array)
		{
			$config = $config->as_array();
		}
		return $config;
	}

	/**
	 * Retuern whether to use HTML5 markup.
	 *
	 * @return	boolean
	 */
	public static function html5()
	{
		return self::get_config()->get('_html5', TRUE);
	}

	/**
	 * Create a form instance.
	 *
	 * @param	array	an associative array of form options
	 * @return	MMI_Form
	 */
	public static function factory($options = array())
	{
		return new MMI_Form($options);
	}
} // End Kohana_MMI_Form
