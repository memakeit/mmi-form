<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form generator.
 *
 * @package		MMI Form
 * @category	form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license	http://www.memakeit.com/license
 */
class Kohana_MMI_Form
{
	// Class constants
	const ERROR_GENERAL = '_';
	const ORDER_ERROR = 'err';
	const ORDER_FIELD = 'fld';
	const ORDER_LABEL = 'lbl';
	const STATE_INITIAL = 1;
	const STATE_POSTED = 2;
	const STATE_VALIDATED = 4;
	const STATE_SAVED = 8;
	const STATE_RESET = 16;
	const STATE_FROZEN = 32;
	const STATE_RENDERED = 64;

	/**
	 * @var Kohana_Config form configuration
	 */
	protected static $_config;

	/**
	 * @var array form errors
	 */
	protected $_errors = array();

	/**
	 * @var array form field objects
	 */
	protected $_fields = array();

	/**
	 * @var string the form id
	 */
	protected $_id;

	/**
	 * @var array namespaces
	 */
	protected $_namespaces = array();

	/**
	 * @var array options
	 */
	protected $_options = array();

	/**
	 * @var array form-specific options
	 */
	protected $_options_form = array();

	/**
	 * @var array form plugins
	 */
	protected $_plugins = array();

	/**
	 * @var array post data
	 */
	protected $_post_data = array();

	/**
	 * @var boolean was form data posted?
	 */
	protected $_posted = FALSE;

	/**
	 * @var integer current form state
	 */
	protected $_state = self::STATE_INITIAL;

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

	/**
	 * Initialize form options.
	 *
	 * @param	array	an associative array of form options
	 * @return	void
	 */
	public function __construct($options = array())
	{
		$this->_init_options($options);
		$this->_posted = ( ! empty($_POST));
	}

	/**
	 * Add a plugin.
	 * This method is chainable.
	 *
	 * @param	mixed	the plugin
	 * @param	mixed	the plugin method prefix
	 * @param	array	an associative array of plugin-specific options
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
			// Create the plugin
			$plugin = MMI_Form_Plugin::factory($this, $plugin, $options);
		}
		if ($plugin instanceof MMI_Form_Plugin)
		{
			// Add the plugin
			$plugin_name = MMI_Form_Plugin::get_name(get_class($plugin));
			$this->_plugins[$plugin_name] = array
			(
				'method_prefix'	=> $method_prefix,
				'plugin'		=> $plugin,
			);
		}
		return $this;
	}

	/**
	 * Remove a plugin.
	 * This method is chainable.
	 *
	 * @param	mixed	the plugin name
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
			$plugin = MMI_Form_Plugin::get_name(get_class($plugin));
		}
		if (is_string($plugin))
		{
			if (array_key_exists($plugin, $this->_plugins))
			{
				// Remove the plugin
				unset($this->_plugins[$plugin]);
			}
		}
		return $this;
	}

	/**
	 * Add a field to the form.
	 * Multiple fields can be added by specifying an array of fields, models, and options.
	 * A wildcard ('*') adds all the fields for a given model.
	 * This method is chainable.
	 *
	 * @param	mixed	a Jelly field, an array containing a form-only field specification, or an array of fields
	 * @param	array	an associative array of field-specific options
	 * @return	MMI_Form
	 */
	public function add_field($field, $namespace = NULL)
	{
		if ($this->_state !== self::STATE_INITIAL)
		{
			$msg = 'Fields can only be added when the form is in its initial state.';
			MMI_Log::log_error(__METHOD__, __LINE__, $msg);
			throw new Kohana_Exception($msg);
		}

		$field_name = $field;
		if (is_string($field_name))
		{
			if ( ! $is_form_only_field AND (is_string($model) OR empty($model)))
			{
				$model = Arr::get($this->_models, $model_name);
			}

			if ( ! $is_form_only_field AND ! empty($field_name) AND is_string($field_name))
			{
				// Get the field from model meta information
				$field = $model->meta()->fields($field_name);
			}
		}

		if (is_array($field) AND count($field) > 0)
		{
			if (Arr::is_assoc($field))
			{
				$name = Arr::get($field, 'name');
				$type = Arr::get($field, 'type');
				if (empty($name) AND $type === 'html')
				{
					$field['name'] = uniqid('html_');
				}
				$field_name = MMI_Form_Field::get_field_name($field['name']);
				$options = $this->_parse_field_options($options);
				$this->_fields[MMI_Form_Field::get_field_id($model_name, $field_name)] = MMI_Form_Field::factory($this, $field, $options);
			}
			else
			{
				// Process multiple fields
				for ($i=0; $i<count($field); $i++)
				{
					$item_model = $model;
					if (is_array($model) AND isset($model[$i]) AND ! empty($model[$i]))
					{
						if (is_string($model[$i]) OR $model[$i] instanceof Jelly_Model)
						{
							$item_model = $model[$i];
						}
					}

					$item_options = $options;
					if (is_array($options) AND isset($options[$i]) AND ! empty($options[$i]))
					{
						if (is_array($options[$i]))
						{
							$item_options = $options[$i];
						}
					}
					$this->add_field($field[$i], $item_model, $item_options);
				}
			}
		}
		return $this;
	}

	/**
	 * Remove one or more fields from the form.
	 * A wildcard ('*') removes all the form fields.
	 * This method is chainable.
	 *
	 * @param	mixed		an form field object or an array of form field object
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

		if ($field instanceof MMI_Form_Field)
		{
			if ($field_name === '*' AND isset($namespace))
            {
                // Remove all fields for a namespace
                if (array_key_exists($namespace, $this->_namespaces))
                {
                	foreach ($this->_fields as $field)
                	{
                		if ($field->namespace === $namespace)
                		{
                    		$this->remove_field($field, $namespace);
                		}
                	}
                }
            }
            elseif ($field_name === '*')
            {
            	foreach ($this->_fields as $field)
                {
                    $this->remove_field($field);
                }
            }
            else
            {
                $field_id = Jelly_Form_Field::get_field_id($model_name, $field_name);
                if (array_key_exists($field_id, $this->_fields))
                {
                    unset($this->_fields[$field_id]);
                }
            }
		}
		elseif (is_array($field) AND count($field) > 0)
		{
			// Process multiple fields
			for ($i=0; $i<count($field); $i++)
			{
				if (is_array($model) AND isset($model[$i]) AND ! empty($model[$i]))
				{
					if (is_string($model[$i]) OR $model[$i] instanceof Jelly_Model)
					{
						$item_model = $model[$i];
					}
				}
				$this->remove_field($field[$i], $item_model);
			}
		}
		return $this;
	}

	/**
	 * Validate the form.
	 *
	 * @return  boolean
	 */
	public function valid()
	{
		if ($this->_state ^ self::STATE_POSTED)
		{
			$this->_load_post_data();
		}

		$this->_validate_form_only_fields();
		$this->_state |= self::STATE_VALIDATED;
		return (count($this->_errors) === 0);
	}

	/**
	 * Get one or more form fields.
	 *
	 * @param   string  the field name
	 * @param   string  the model name
	 * @return  array
	 */
	public function fields($field_name = '', $model_name = self::FORM_ONLY_FIELD)
	{
		if (empty($field_name))
		{
			return $this->_fields;
		}
		return Arr::Get($this->_fields, MMI_Form_Field::get_field_id($model_name, $field_name));
	}

	/**
	 * Get validation errors.
	 *
	 * @return  array
	 */
	public function errors()
	{
		return $this->_errors;
	}

	/**
	 * Add a validation error.
	 * This method is chainable.
	 *
	 * @return  MMI_Form
	 */
	public function add_error($field_name, $error)
	{
		$this->_errors[$field_name] = $error;
		return $this;
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
	public function form()
	{
		// Ensure post data is processed
		if ($this->_state ^ self::STATE_POSTED)
		{
			$this->_load_post_data();
		}

		$this->_freeze();
		$frm = array();
		$options = $this->_options_form;

		// Form open tag
		$file = 'jelly/form/open';
		$frm[] = View::factory($file, array
		(
			'before' => Arr::path($options, '_open._before', ''),
			'after' => Arr::path($options, '_open._after', ''),
			'action' => Arr::get($options, 'action', Request::instance()->uri),
			'attributes' => self::attributes($options),
		))->render();

		// Feedback messages
		$frm[] = $this->_get_messages();

		// Form fields
		$errors = $this->_errors;
		$models = $this->_models;
		foreach ($this->_fields as $field)
		{
			foreach ($field->order() as $order_type)
			{
				$field_name = $field->name;
				$field_id = str_replace('[]', '', MMI_Form_Field::get_field_id($model_name, $field_name));
				switch ($order_type)
				{
					case self::ORDER_ERROR:
						$msg = Arr::get($errors, $field_id, '');
						if ( ! empty($msg))
						{
							$field->add_error_message($msg);
						}
						$frm[] = $field->error();
						break;

					case self::ORDER_FIELD:
						$frm[] = $field->input();
						break;

					case self::ORDER_LABEL:
						$frm[] = $field->label();
						break;
				}
			}
		}

		// Form close tag
		$frm[] = ' ';
		$file = 'jelly/form/close';
		$frm[] = View::factory($file, array
		(
			'before' => Arr::path($options, '_close._before', ''),
			'after' => Arr::path($options, '_close._after', ''),
		))->render();

		$this->_state |= self::STATE_RENDERED;
		return implode(PHP_EOL, $frm);
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
	public function unicode()
	{
		return Arr::get($this->_options_form, '_unicode', FALSE);
	}

	/**
	 * Get the formatted error message.
	 *
	 * @param   string  the field label
	 * @param   string  the rule name
	 * @param   array   the rule parameters
	 * @return  string
	 */
	public function format_error_message($field_label, $rule_name, $rule_parms)
	{
		$form_options = $this->_options_form;
		if ($message = Arr::path($form_options, '_messages._custom.'.$rule_name))
		{
			// Found a custom message
		}
		else
		{
			$file = $this->_get_message_file();
			if ($message = Kohana::message($file, $rule_name))
			{
				// Found a default message for this error
			}
			else
			{
				// No message exists, display the path expected
				$message = "{$file}.{$rule_name}";
			}
		}

		$values = array(':field' => $field_label);
		if (is_array($rule_parms) AND count($rule_parms) > 0)
		{
			for ($i=0; $i<count($rule_parms); $i++)
			{
				$parm = $rule_parms[$i];
				$values[':param'.($i + 1)] = (is_array($parm)) ? implode(', ', $parm) : $parm;
			}
		}

		$translate = Arr::path($form_options, '_messages._translate', FALSE);
		if ($translate)
		{
			// Translate the message using the specified language
			$message = __($message, $values, I18n::$lang);
		}
		else
		{
			// Do not translate the message, just replace the values
			$message = strtr($message, $values);
		}
		return $message;
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
		$options = Arr::merge
		(
			self::_get_config(TRUE),
			$options
		);

		$form_id = Arr::path($options, 'form.id');
		if (empty($form_id))
		{
			$form_id = 'jelly_form';
			$options['form']['id'] = $form_id;
		}
		$this->_id = self::clean_id($form_id);

		$options['form']['_required_symbol'] = Arr::path($options, 'form._required_symbol', '');

		$this->_options = $options;
		$this->_options_form = Arr::get($options, 'form', array());
	}

	/**
	 * Parse the field options.
	 *
	 * @param   array   the field options
	 * @return  array
	 */
	protected function _parse_field_options($options)
	{
		if ( ! is_array($options))
		{
			return array();
		}

		$_error = Arr::get($options, '_error', array());
		if (array_key_exists('_error', $options))
		{
			unset($options['_error']);
		}

		$_label = Arr::get($options, '_label', array());
		if (array_key_exists('_label', $options))
		{
			unset($options['_label']);
		}

		// Merge field-specific options with the form options
		return Arr::merge
		(
			$this->_options,
			array('error' => array('_field_specific' => $_error)),
			array('field' => array('_field_specific' => $options)),
			array('label' => array('_field_specific' => $_label))
		);
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
	 * Load the post data into the models and fields.
	 *
	 * @return  void
	 */
	protected function _load_post_data()
	{
		if ($this->_posted)
		{
			$post_data = $this->_xss_clean_array($_POST);
			if ( ! empty($post_data))
			{
				$models = $this->_models;
				foreach ($this->_fields as $name => $field)
				{
					$model_name = $field->model_name;
					$field_name = $field->model_column;
					$field_type = $field->type();
					if ($model_name === MMI_Form::FORM_ONLY_FIELD AND $field_type === 'hidden')
					{
						continue;
					}
					if (in_array($field_type, array('checkbox', 'radio')) AND empty($field->choices))
					{
						continue;
					}
					$value = Arr::get($post_data, MMI_Form_Field::get_form_field_id($model_name, $field_name));
					$field->value = $value;
					$model = Arr::get($models, $model_name);
					if ($model instanceof Jelly_Model)
					{
						$model->$field_name = $value;
					}
				}
			}
			$this->_post_data = $post_data;
			$this->_state |= self::STATE_POSTED;
		}
	}

	/**
	 * Validate the form-only fields.  Validation errors can be retrieved via the errors() method.
	 *
	 * @return  void
	 */
	protected function _validate_form_only_fields()
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
	 * Load the form-only field values into an array.
	 *
	 * @return  array
	 */
	protected function _get_form_only_data()
	{
		$data = array();
		if ($this->_posted)
		{
			$post_data = $this->_post_data;
			foreach ($this->_fields as $name => $field)
			{
				$field_name = $field->name;
				$model_name = $field->model_name;
				if ($model_name === self::FORM_ONLY_FIELD)
				{
					$form_field_id = MMI_Form_Field::get_form_field_id($model_name, $field_name);
					$data[$name] = Arr::get($post_data, $form_field_id, '');
				}
			}
		}
		return $data;
	}

	/**
	 * Freeze the form (and form fields), preventing further modifications.
	 *
	 * @return  void
	 */
	protected function _freeze()
	{
		foreach ($this->_fields as $field)
		{
			$model_name = $field->model_name;
			if ($model_name !== self::FORM_ONLY_FIELD)
			{
				$model = $this->_models[$model_name];
				$field->value = $model->{$field->name};
			}
			$field->freeze();
		}
		$this->_state |= self::STATE_FROZEN;
	}

	/**
	 * Get the message file.  If a language-specific file can be located, it is used.
	 *
	 * @return  string
	 */
	protected function _get_message_file()
	{
		$filename = Arr::path($this->_options_form, '_messages._file', 'validate');
		$lang = str_replace('-', DIRECTORY_SEPARATOR, I18n::$lang);
		$file = $lang.DIRECTORY_SEPARATOR.$filename;

		$path = Kohana::find_file('messages', $file);
		if (empty($path))
		{
			$idx = strpos($lang, DIRECTORY_SEPARATOR);
			if ($idx !== FALSE)
			{
				$lang = substr($lang, 0, $idx);
				$file = $lang.DIRECTORY_SEPARATOR.$filename;
				$path = Kohana::find_file('messages', $file);
			}
		}
		if (empty($path))
		{
			$file = $filename;
			$path = Kohana::find_file('messages', $file);
		}
		return $file;
	}

	/**
	 * Remove XSS from user input.
	 *
	 * @param   array   array to sanitize
	 * @return  array
	 */
	protected function _xss_clean_array($data)
	{
		if ( ! is_array($data))
		{
			$data = array();
		}
		foreach ($data as $name => $value)
		{
			if (is_array($value))
			{
				$data[$name] = $this->_xss_clean_array($value);
			}
			else
			{
				$data[$name] = Security::xss_clean($value);
			}
		}
		return $data;
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
	 * Remove invalid characters from an id attribute.
	 *
	 * @param   string  the original id
	 * @return  string
	 */
	public static function clean_id($id)
	{
		return preg_replace('/[^-a-z\d_]/i', '', $id);
	}

	/**
	 * Get the configuration settings.
	 *
	 * @param   boolean return the configuration as an array?
	 * @return  mixed
	 */
	protected static function _get_config($as_array = FALSE)
	{
		(self::$_config === NULL) AND self::$_config = Kohana::config('mmi-form');
		$config = self::$_config;
		if ($as_array)
		{
			$config = $config->as_array();
		}
		return $config;
	}
} // End Kohana_MMI_Form
