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
	// Field order constants
	const ORDER_ERROR = 'err';
	const ORDER_FIELD = 'fld';
	const ORDER_LABEL = 'lbl';

	// Required symbol placement
	const REQ_SYMBOL_AFTER = 'after';
	const REQ_SYMBOL_BEFORE = 'before';

	// State constants
	const STATE_INITIAL = 1;
	const STATE_POSTED = 2;
	const STATE_VALIDATED = 4;
	const STATE_PRE_RENDERED = 8;
	const STATE_RENDERED = 16;
	const STATE_RESET = 32;

	/**
	 * @var MMI_Form the form instance
	 */
	public static $instance;

	/**
	 * @var Kohana_Config the form configuration
	 */
	protected static $_config;

	/**
	 * @var string the HTTP method
	 */
	protected static $_method;

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

	/**
	 * @var boolean was form data posted?
	 */
	protected $_posted = FALSE;

	/**
	 * @var integer the current form state
	 */
	protected $_state = self::STATE_INITIAL;

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
			if (class_exists('MMI_Log'))
			{
				MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			}
			throw new Kohana_Exception($msg);
		}

		// Create the field object
		if ( ! empty($field) AND is_string($field))
		{
			if ( ! empty($options) AND is_scalar($options))
			{
				$options = array('_scalar' => $options);
			}
			if ( ! is_array($options))
			{
				$options = array();
			}
			$options['type'] = $field;
			$field = MMI_Form_Field::factory($field, $options);
		}

		// Add the namespace
		$namespace = trim(strval($field->meta('namespace')));
		if ($namespace !== '')
		{
			$id = self::clean_id($namespace);
			$namespace = trim($namespace);
			$this->_namespaces[$id] = $namespace;
		}

		// Add the field
		$id = $this->_id_from_field($field);
		$this->_fields[$id] = $field;
		return $this;
	}

	/**
	 * Remove one or more form fields.
	 * This method is chainable.
	 *
	 * To remove a single field:
	 *	- specify a field object (namespace is ignored)
	 *	- specify a field id (namespace is used)
	 *
	 * To remove multiple fields:
	 *	- '*' without a namespace removes all fields
	 *	- '*' with a namespace removes all fields for the namespace
	 *	- an array (of strings) removes multiple fields for a namespace
	 *
	 * @param	mixed	a field identifier, object, or wildcard character (*)
	 * @param	string	the namespace
	 * @return	MMI_Form
	 */
	public function remove_field($field, $namespace = NULL)
	{
		if ($this->_state !== self::STATE_INITIAL)
		{
			$msg = 'Fields can only be removed when the form is in its initial state.';
			if (class_exists('MMI_Log'))
			{
				MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			}
			throw new Kohana_Exception($msg);
		}
		$namespace = trim(strval($namespace));

		// Process a form object
		if ($field instanceof MMI_Form_Field)
		{
			$id = $this->_id_from_field($field);
			if (array_key_exists($id, $this->_fields))
			{
				unset($this->_fields[$id]);
			}
		}

		// Process wildcards
		elseif ($field === '*' AND $namespace === '')
		{
			// Remove all fields
			$this->_fields = array();
		}
		elseif ($field === '*' AND $namespace !== '')
		{
			// Remove all fields for the namespace
			$namespace = self::clean_id($namespace);
			foreach ($this->_fields as $id => $field)
			{
				list($ns) = explode('.', $id);
				if ($ns === $namespace)
				{
					unset($this->_fields[$id]);
				}
			}
		}

		// Process a field name (string)
		elseif ( ! empty($field) AND is_string($field))
		{
			$id = self::clean_id($namespace).'.'.self::clean_id($field);
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

	/**
	 * Add a plugin.
	 * This method is chainable.
	 *
	 * @param	mixed	a MMI_Form_Plugin object or a string specifying the plugin type
	 * @param	string	the plugin method prefix
	 * @param	array	an associative array of plugin options
	 * @return	MMI_Form
	 */
	public function add_plugin($plugin, $method_prefix = NULL, $options = array())
	{
		if ($this->_state !== self::STATE_INITIAL)
		{
			$msg = 'Plugins can only be added when the form is in its initial state.';
			if (class_exists('MMI_Log'))
			{
				MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			}
			throw new Kohana_Exception($msg);
		}

		if (is_string($plugin))
		{
			// Create the plugin object
			$plugin = MMI_Form_Plugin::factory($plugin, $options);
			if ( ! empty($method_prefix))
			{
				if (substr($method_prefix, -1) !== '_')
				{
					$method_prefix .= '_';
				}
				$plugin->method_prefix($method_prefix);
			}
		}
		if ($plugin instanceof MMI_Form_Plugin)
		{
			// Add the plugin
			$id = $plugin->name();
			$method_prefix = $plugin->method_prefix();
			if (empty($method_prefix))
			{
				$plugin->method_prefix($id.'_');
			}
			$this->_plugins[$id] = $plugin;
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
			if (class_exists('MMI_Log'))
			{
				MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			}
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

		if ($this->_state ^ MMI_Form::STATE_PRE_RENDERED)
		{
			$this->_attributes[$name] = $value;
			return $this;
		}
		else
		{
			$msg = 'Attributes can not be set after the form has been rendered.';
			if (class_exists('MMI_Log'))
			{
				MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			}
			throw new Kohana_Exception($msg);
		}
	}

	/**
 	 * Return an list of field values that have changed.
	 *
	 * @return	array
	 */
	public function diff()
	{
		$diff = array();
		foreach ($this->_fields as $id => $field)
		{
			if ($field->updated())
			{
				$diff[$id] = $field->diff();
			}
		}
		return $diff;
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
			$errors = $this->_errors;
			foreach ($this->_fields as $field)
			{
				$errors = Arr::merge($errors, $field->error());
			}
			return $errors;
		}

		if ($this->_state ^ MMI_Form::STATE_PRE_RENDERED)
		{
			if ( ! empty($msg) AND ! in_array($msg, $this->_errors))
			{
				$this->_errors[] = $msg;
			}
			return $this;
		}
		else
		{
			$msg = 'Errors can not be set after the form has been rendered.';
			if (class_exists('MMI_Log'))
			{
				MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			}
			throw new Kohana_Exception($msg);
		}
	}

	/**
	 * Get one or more form fields.
	 *
	 * @param	string	the field id
	 * @param	string	the field namespace
	 * @return	mixed
	 */
	public function field($id = NULL, $namespace = NULL)
	{
		$fields = $this->_fields;
		$id = trim(strval($id));
		$namespace = trim(strval($namespace));
		if ($id !== '')
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
		elseif ($id === '' AND $namespace != '')
		{
			$namespace = self::clean_id($namespace);
			$found = array();
			foreach ($fields as $key => $field)
			{
				list($ns) = explode('.', $key);
				if ($ns === $namespace)
				{
					$idx = $this->_id_from_field($field);
					$found[$idx] = $field;
				}
			}
			return $found;
		}
		else
		{
			return $fields;
		}
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

		if ($this->_state ^ MMI_Form::STATE_PRE_RENDERED)
		{
			$this->_meta[$name] = $value;
			return $this;
		}
		else
		{
			$msg = 'Meta data can not be set after the form has been rendered.';
			if (class_exists('MMI_Log'))
			{
				MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			}
			throw new Kohana_Exception($msg);
		}
	}

	/**
 	 * Get whether any field values have been updated.
	 *
	 * @return	string
	 */
	public function updated()
	{
		foreach ($this->_fields as $field)
		{
			if ($field->updated())
			{
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Get or set a form field value.
	 * This method is chainable when setting a value.
	 *
	 * @param	string	the field id
	 * @param	string	the field namespace
	 * @param	mixed	the value to set
	 * @return	MMI_Form
	 */
	public function value($id, $namespace, $value = NULL)
	{
		$field = $this->field($id, $namespace);
		if ($field instanceof MMI_Form_Field)
		{
			if (func_num_args() === 2)
			{
				return $field->value();
			}

			if ($this->_state ^ MMI_Form::STATE_PRE_RENDERED)
			{
				$field->value($value);
				return $this;
			}
			else
			{
				$msg = 'Field values can not be set after the form has been rendered.';
				if (class_exists('MMI_Log'))
				{
					MMI_Log::log_error(__METHOD__, __LINE__, $msg);
				}
				throw new Kohana_Exception($msg);
			}
		}
	}

	/**
	 * Get or set an item in the view cache.
	 *
	 * @param	string	the view id
	 * @param	View	the view object
	 * @return	mixed
	 */
	public static function view_cache($id, $value = NULL)
	{
		if (func_num_args() === 1)
		{
			return Arr::get(self::$_view_cache, $id);
		}
		self::$_view_cache[$id] = $value;
	}

	/**
	 * Generate the form HTML.
	 *
	 * @return	string
	 */
	public function render()
	{
		$this->_pre_render();

		// Form open tag
		$frm = array($this->_form_open());

		// Feedback messages
		if (Arr::get($this->_meta, 'show_messages', TRUE))
		{
			$frm[] = $this->_messages();
		}

		// Form fields
		foreach ($this->_fields as $field)
		{
			$order = $field->meta('order');
			foreach ($order as $order_type)
			{
				switch ($order_type)
				{
					case self::ORDER_ERROR:
						$options = array_merge(Arr::get($this->_meta, 'error', array()), $field->meta('error'));
						$frm[] = MMI_Form_Label::factory($options)->render();
						break;

					case self::ORDER_FIELD:
						$frm[] = $field->render();
						break;

					case self::ORDER_LABEL:
						$options = $field->meta('label');
						if ($field->required())
						{
							$options['_required'] = TRUE;
						}
						$frm[] = MMI_Form_Label::factory($options)->render();
						break;
				}
			}
		}

		// Form close tag
		$frm[] = $this->_form_close();
		$this->_state |= self::STATE_RENDERED;
		return implode(PHP_EOL, $frm);
	}

	/**
	 * Reset the form.
	 *
	 * @return	void
	 */
	public function reset()
	{
		foreach ($this->_fields as $field)
		{
			$field->reset();
		}
		$this->_state = self::STATE_INITIAL | self::STATE_RESET;
	}

	/**
	 * Check whether the form is valid.
	 *
	 * @return	boolean
	 */
	public function valid()
	{
		if ( ! $this->_posted)
		{
			return TRUE;
		}

		$valid = TRUE;
		foreach ($this->_fields as $field)
		{
			$valid &= $field->valid();
		}
		$this->_state |= self::STATE_VALIDATED;
		return ($valid === 1);
	}

	/**
	 * Add a CAPTCHA to the form.
	 * This method is chainable.
	 *
	 * @param	string	the captcha driver
	 * @param	array	an associative array of plugin options
	 * @return	MMI_Form
	 */
	public function add_captcha($driver = 'recaptcha', $options = array())
	{
		// Create the plugin
		$captcha = MMI_Form_Plugin::factory($driver, $options);

		// Configure the and add the form field
		$options = array_merge($options, array
		(
			'_html'			=> array($captcha, 'html'),
			'_source'		=> MMI_Form_Field_HTML::SRC_CALLBACK,
			'_callbacks'	=> array
			(
				array
				(
					array($captcha, 'valid')
				),
			),
		));
		$this->add_field('html', $options);
		return $this;
	}

	/**
	 * Add CSRF validation to the form.
	 * This method is chainable.
	 *
	 * @param	string	the form field id
	 * @return	MMI_Form
	 */
	public function add_csrf($id = NULL)
	{
		$id = trim(strval($id));
		if ($id === '')
		{
			$id = 'mmi_csrf';
		}
		$this->add_field('hidden', array
		(
			'id' => $id,
			'_rules' => array
			(
				'not_empty'			=> NULL,
				'Security::check'	=> NULL,
			),
			'value' => Security::token(TRUE),
		));
		return $this;
	}

	/**
	 * Add HTML to the form.
	 * This method is chainable.
	 *
	 * @param	string	the HTML string
	 * @return	MMI_Form
	 */
	public function add_html($html)
	{
		return $this->add_field('html', array('_html' => $html));
	}

	/**
	 * Add a submit button to the form.
	 * This method is chainable.
	 *
	 * @param	string	the button text
	 * @param 	array	an associative array of field options
	 * @return	MMI_Form
	 */
	public function add_submit($text = 'Submit', $options = array())
	{
		$options['value'] = $text;
		$this->add_field('submit', $options);
		return $this;
	}

	/**
	 * Add a closing fieldset tag to the form.
	 * This method is chainable.
	 *
	 * @param	array	an associative array of fieldset closing tag options
	 * @return	MMI_Form
	 */
	public function fieldset_close($options = array())
	{
		if ( ! is_array($options))
		{
			$options = array();
		}
		$this->add_field('html', array
		(
			'_html'		=> MMI_Form_FieldSet::factory($options)->close(),
			'_source'	=> MMI_Form_Field_HTML::SRC_STRING,
		));
		return $this;
	}

	/**
	 * Add an opening fieldset tag to the form.
	 * This method is chainable.
	 *
	 * @param	array	an associative array of fieldset opening tag options
	 * @return	MMI_Form
	 */
	public function fieldset_open($options = array())
	{
		if ( ! is_array($options))
		{
			$options = array();
		}
		$this->add_field('html', array
		(
			'_html'		=> MMI_Form_FieldSet::factory($options)->open(),
			'_source'	=> MMI_Form_Field_HTML::SRC_STRING,
		));
		return $this;
	}

	/**
	 * Process method names that do not exist.
	 * Used to call plugin methods.
	 *
	 * @param	string	the method name
	 * @param	array	the method arguments
	 * @return	mixed
	 */
	public function __call($method, $args)
	{
		// Find the plugin
		foreach ($this->_plugins as $name => $plugin)
		{
			$prefix = $plugin->method_prefix();
			if (stripos($method, $prefix) === 0)
			{
				$method = str_replace($prefix, '', $method);
				break;
			}
		}

		// Use reflection to invoke the plugin method
		if ( ! empty($name) AND $plugin->method_exists($method))
		{
			if ( ! is_array($args))
			{
				$args = array();
			}
			$method = new ReflectionMethod($plugin, $method);
			return $method->invokeArgs($plugin, $args);
		}

		// Plugin method not found
		$msg = 'Plugin method not found: '.$method.'.';
		if ( ! empty($name))
		{
			$msg .= ' Plugin name: '.$name;
		}
		MMI_Log::log_error(__METHOD__, __LINE__, $msg);
		throw new Kohana_Exception($msg);
	}

	/**
	 * Initialize the form options.
	 * Separate the meta data from the HTML attributes.
	 *
	 * @param	array	an associative array of form options
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
		$options = $this->_merge_options($options);

		// Set the message options
		MMI_Form_Messages::options(Arr::get($options, '_messages', array()));

		// Set the CSS class
		if ( ! empty($class))
		{
			$options['class'] = $class;
		}

		// Set defaults
		if ( ! array_key_exists('action', $options))
		{
			$options['action'] = Request::instance()->uri;
		}
		if ( ! array_key_exists('_html5', $options))
		{
			$options['_html5'] = TRUE;
		}
		if ( ! array_key_exists('_required_symbol', $options))
		{
			$options['_required_symbol'] = array
			(
				'_html' => '*&nbsp;',
				'_placement' => MMI_Form::REQ_SYMBOL_BEFORE,
			);
		}

		// Ensure the form has an id attribute
		$id = trim(strval(Arr::get($options, 'id', '')));
		if ($id === '')
		{
			$options['id'] = 'mmi_form';
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
	 *
	 * @param	array	an associative array of form options
	 * @return	array
	 */
	protected function _merge_options($options)
	{
		if ( ! is_array($options))
		{
			$options = array();
		}
		$config = MMI_Form::get_config();

		// Ensure form sub-arrays are properly merged
		$field_default = $config->get('_field', array());
		$field = Arr::get($options, '_field', array());
		foreach (array('_error', '_item', '_label') as $name)
		{
			$value = Arr::get($field, $name, array());
			if ( ! empty($value))
			{
				$value_default = Arr::get($field_default, $name, array());
				$field[$name] = array_merge($value_default, $value);
			}
		}
		$options['_field'] = array_merge($field_default, $field);

		$required_symbol = Arr::get($options, '_required_symbol', array());
		if ( ! empty($required_symbol))
		{
			$required_default = $config->get('_required_symbol', array());
			$options['_required_symbol'] = array_merge($required_default, $required_symbol);
		}
		return array_merge($config->as_array(), $options);
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
		$defaults = self::get_config(TRUE);
		$value =
			Arr::get($defaults, $key, '').' '.
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
	 * Generate an id using the id and namespace of a form field object.
	 *
	 * @param	MMI_Form_Field	a form field object
	 * @return	string
	 */
	protected function _id_from_field(MMI_Form_Field $field)
	{
		$id = self::clean_id($field->attribute('id'));
		$namespace = self::clean_id($field->meta('namespace'));
		return $namespace.'.'.$id;
	}

	/**
	 * Perform any pre-rendering logic.
	 *
	 * @return	void
	 */
	protected function _pre_render()
	{
		if ($this->_posted AND Arr::get($this->_meta, 'auto_validate', FALSE))
		{
			// Trigger automatic validation
			$this->valid();
		}
		$this->_state |= self::STATE_PRE_RENDERED;
	}

	/**
	 * Generate the form open tag.
	 *
	 * @return	string
	 */
	protected function _form_open()
	{
		$path = $this->_get_view_path('open');
		$cache = self::view_cache($path);
		if (isset($cache))
		{
			$view = clone $cache;
		}
		if ( ! isset($view))
		{
			$view = View::factory($path);
			self::view_cache($path, $view);
		}
		$parms = $this->_get_view_parms_open();
		return $view->set($parms)->render();
	}

	/**
	 * Generate form status messages.
	 *
	 * @return	string
	 */
	protected function _messages()
	{
		$class = MMI_Form_Messages::class_default();
		$msg = '';
		if ($this->_posted)
		{
			$count_all = count($this->error());
			$count_general = count( $this->_errors);
			if ($count_all > 0)
			{
				if ($count_general === $count_all)
				{
					$msg = MMI_Form_Messages::msg_failure();
				}
				else
				{
					$count = $count_all - $count_general;
					if ($count === 1)
					{
						$msg = MMI_Form_Messages::msg_failure_single();
					}
					else
					{
						$msg = MMI_Form_Messages::msg_failure_multiple($count);
					}
				}
				$class = MMI_Form_Messages::class_failure();
			}
			else
			{
				$msg = MMI_Form_Messages::msg_success();
				$class = MMI_Form_Messages::class_success();
			}
		}

		$html5 = Arr::get($this->_meta, 'html5', TRUE);
		if ($html5)
		{
			$allowed = MMI_HTML5_Attributes::get();
		}
		else
		{
			$allowed = MMI_HTML4_Attributes::get();
		}
		$attributes = array
		(
			'class'	=> $class,
			'id'	=> MMI_Form_Messages::get_status_id(),
		);
		$attributes = array_intersect_key($attributes, array_flip($allowed));
		return '<div'.HTML::attributes($attributes).'>'.$msg.'</div>';
	}

	/**
	 * Generate the form close tag.
	 *
	 * @return	string
	 */
	protected function _form_close()
	{
		$path = $this->_get_view_path('close');
		$cache = self::view_cache($path);
		if (isset($cache))
		{
			$view = clone $cache;
		}
		if ( ! isset($view))
		{
			$view = View::factory($path);
			self::view_cache($path, $view);
		}
		$parms = $this->_get_view_parms_close();
		return $view->set($parms)->render();
	}

	/**
	 * Get the view path.
	 *
	 * @param	string	the view name
	 * @return	string
	 */
	protected function _get_view_path($view_name = 'open')
	{
		$meta = $this->_meta;
		$dir = Arr::get($meta, 'view_path', 'mmi/form');
		$file = Arr::get($meta, 'view', $view_name);
		if ( ! Kohana::find_file('views/'.$dir, $file))
		{
			// Use the default view
			$file = $view_name;
		}
		return $dir.'/'.$file;
	}

	/**
	 * Get the view parameters for the closing tag.
	 *
	 * @return	array
	 */
	protected function _get_view_parms_close()
	{
		$close = Arr::get($this->_meta, 'close', array());
		return array
		(
			'after'		=> Arr::get($close, '_after', ''),
			'before'	=> Arr::get($close, '_before', ''),
		);
	}

	/**
	 * Get the view parameters for the opening tag.
	 *
	 * @return	array
	 */
	protected function _get_view_parms_open()
	{
		$attributes = $this->_get_view_attributes();
		$open = Arr::get($this->_meta, 'open', array());
		return array
		(
			'action'		=> Arr::get($attributes, 'action'),
			'after'			=> Arr::get($open, '_after', ''),
			'attributes'	=> $attributes,
			'before'		=> Arr::get($open, '_before', ''),
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

		// Process the id and namespace
		$id = trim(strval(Arr::get($attributes, 'id', '')));
		if ($id !== '')
		{
			$namespace = Arr::get($this->_meta, 'namespace');
			$attributes['id'] = MMI_Form_Field::field_id($id, $namespace);
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
		$html5 = Arr::get($this->_meta, 'html5', TRUE);
		if ($html5)
		{
			return MMI_HTML5_Attributes_Form::get();
		}
		return MMI_HTML4_Attributes_Form::get();
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
	 * Create a form instance.
	 *
	 * @param	array	an associative array of form options
	 * @return	MMI_Form
	 */
	public static function factory($options = array())
	{
		if ( ! self::$instance)
		{
			self::$instance = new MMI_Form($options);
		}
		return self::$instance;
	}

	/**
	 * Return the singleton instance.
	 *
	 * @return	MMI_Form
	 */
	public static function instance()
	{
		return self::$instance;
	}
} // End Kohana_MMI_Form
