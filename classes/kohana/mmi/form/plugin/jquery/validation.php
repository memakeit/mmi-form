<?php defined('SYSPATH') or die('No direct script access.');
/**
 * jQuery form validation plugin.
 *
 * @package		MMI Form
 * @category	plugin
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Plugin_JQuery_Validation extends MMI_Form_Plugin
{
	/**
	 * @var Kohana_Config the plugin configuration
	 */
	protected static $_config;

	/**
	 * @var array the default unicode ranges
	 */
	protected static $_default_unicode_ranges = array
	(
		'dashes'		=> '\u002d\u00ad',
		'letters'		=> '\u0041-\u005a\u0061-\u007a\u00aa\u00b5\u00ba\u00c0-\u00d6\u00d8-\u00f6\u00f8-\u024f',
		'numbers'		=> '\u0030-\u0039\u00b2\u00b3\u00b9\u00bc-\u00be',
		'spaces'		=> '\u0020\u00a0',
		'underscores'	=> '\u0029\u005d\u007d',
	);

	/**
	 * @var array a map of Kohana validation methods and their jQuery equivalents
	 */
	protected static $_rule_map = array
	(
		'credit_card'	=> 'creditcard',
		'digits'		=> 'digit',
		'matches'		=> 'equalTo',
		'max_items'		=> 'maxlength',
		'max_length'	=> 'maxlength',
		'min_items'		=> 'minlength',
		'min_length'	=> 'minlength',
		'not_empty'		=> 'required',
		'range_items'	=> 'rangelength',
	);

	/**
	 * @var array the unicode ranges used to build regular expressions
	 */
	protected static $_unicode_ranges;

	/**
	 * @var array an array of additional validation methods that need to be included
	 */
	protected $_methods = array();

	/**
	 * @var boolean accept unicode input?
	 */
	protected $_unicode = FALSE;

	/**
	 * Initialize the options.
	 *
	 * @param	array	an associative array of plugin options
	 * @return	void
	 */
	public function __construct($options = array())
	{
		if (Request::$is_ajax)
		{
			return;
		}
		parent::__construct($options);

		$this->_unicode = $this->_get_form_meta('unicode', FALSE);
		$options = Arr::merge(self::get_config(TRUE), $options);
		$this->_options = array_intersect_key
		(
			Arr::get($options, 'options', array()),
			$this->_get_valid_options()
		);
	}

	/**
	 * Generate the jQuery validation JavaScript.
	 *
	 * @return	string
	 */
	public function get_validation_js()
	{
		$this->_generate_rules();
		$options = array_diff_assoc($this->_options, $this->_get_default_options());
		$options = $this->_parse_options($options);
		$options = implode(','.PHP_EOL, $options);

		$form_id = '#'.$this->form()->attribute('id');
		return<<<EOJS
var validator;
$(window).load(function(){
	$('{$form_id}').find('label.error').each(function(){
		$(this).attr('generated', true);
	});
	validator = $('{$form_id}').validate({
{$options}
	});
{$this->_get_methods()}
});
EOJS;
	}

	/**
	 * Parse and format the jQuery validation options.
	 *
	 * @param	array	an associative array of validation options
	 * @return	array
	 */
	protected function _parse_options($options)
	{
		$js = array();
		foreach ($options as $name => $value)
		{
			$add_quotes = TRUE;
			if (is_bool($value))
			{
				$add_quotes = FALSE;
				$value = ($value === TRUE) ? 'true' : 'false';
			}
			elseif (is_numeric($value))
			{
				$add_quotes = FALSE;
			}
			elseif (is_string($value))
			{
				$value = $this->_normalize_spaces($value);
				$do_not_quote = array('/\[[\d\s,]+\]/', '/^function/', '/^jQuery.format/');
				foreach ($do_not_quote as $no_quotes)
				{
					if (preg_match($no_quotes, $value) === 1)
					{
						$add_quotes = FALSE;
						break;
					}
				}
			}
			elseif (is_array($value))
			{
				$add_quotes = FALSE;
				$value = '{ '.implode(', ', $this->_parse_options($value)).' }';
			}

			if ($add_quotes)
			{
				$value = "'$value'";
			}
			$js[] = $name.': '.$value;
		}
		return $js;
	}

	/**
	 * Replace multiple whitespace characters with a single space.
	 *
	 * @param	string	the string to normalize
	 * @return	string
	 */
	protected function _normalize_spaces($value)
	{
		if (trim(strval($value)) !== '')
		{
			$value = UTF8::trim(preg_replace('/[\s\n\r\t]+/', ' ', $value));
		}
		return $value;
	}

	/**
	 * Generate the jQuery validation rules.
	 *
	 * @return	void
	 */
	protected function _generate_rules()
	{
		$fields = $this->form()->field();
		$ignore = array('button', 'hidden', 'image', 'reset', 'submit');
		foreach ($fields as $field)
		{
			if ( ! in_array($field->attribute('type'), $ignore))
			{
				$this->_generate_kohana_rules($field);
				$this->_generate_custom_rules($field);
				$this->_process_min_max_attr($field);
			}
		}
	}

	/**
	 * Generate jQuery validation rules that have equivalent Kohana validation rules.
	 *
	 * @param	MMI_Form_Field	the form field object
	 * @return	void
	 */
	protected function _generate_kohana_rules($field)
	{
		$field_name = $this->_get_field_name($field);
		$rules = $field->meta('rules');
		if ( ! is_array($rules))
		{
			$rules = array();
		}
		foreach ($rules as $rule_name => $rule_parms)
		{
			$label = trim(Arr::get($field->meta('label'), 'html'), ':');
			$jquery_rule = $this->_get_jquery_rule_name($rule_name);
			$msg = MMI_Form_Messages::format_error_msg($label, $rule_name, $rule_parms);
			$parms = $this->_parse_rule_parms($rule_name, $rule_parms);

			switch($rule_name)
			{
				// Built-in validation methods
				case 'max_length':
					// This rule is handled by the input attribute maxlength
					break;

				case 'date':
				case 'email':
				case 'matches':
				case 'min_length':
				case 'not_empty':
				case 'range':
				case 'url':
					$this->_options['messages'][$field_name][$jquery_rule] = $msg;
					$this->_options['rules'][$field_name][$jquery_rule] = $parms;
					break;

				// Custom validation methods
				case 'alpha':
				case 'alpha_dash':
				case 'alpha_numeric':
				case 'color':
				case 'decimal':
				case 'exact_length':
				case 'ip':
				case 'numeric':
				case 'phone':
					$this->_methods[] = $jquery_rule;
					$this->_options['rules'][$field_name][$jquery_rule] = $parms;
					break;

				case 'credit_card':
					$extra_rule = 'credit_card_type';
					$this->_methods[] = $extra_rule;
					$this->_options['rules'][$field_name][$extra_rule] = (count($rule_parms) === 1) ? $rule_parms[0] : 'default';
					$this->_options['rules'][$field_name][$jquery_rule] = TRUE;
					break;

				case 'digit':
					if ($this->_unicode)
					{
						$jquery_rule = 'digits_unicode';
						$this->_methods[] = $jquery_rule;
					}
					$this->_options['rules'][$field_name][$jquery_rule] = $parms;
					break;

				case 'regex':
					$this->_methods[] = $jquery_rule;
					$parms = preg_quote(substr($parms, 1, -1));
					$this->_options['rules'][$field_name][$jquery_rule] = $parms;
					break;
			}
		}
	}

	/**
	 * Generate jQuery validation rules from the custom_rules of the form field.
	 *
	 * @param	MMI_Form_Field	the form field object
	 * @return	void
	 */
	protected function _generate_custom_rules($field)
	{
		$field_name = $this->_get_field_name($field);
		$rules = $field->meta('custom_rules');
		if ( ! is_array($rules))
		{
			$rules = array();
		}
		foreach ($rules as $rule_name => $rule_parms)
		{
			$label = trim(Arr::get($field->meta('label'), 'html'), ':');
			$jquery_rule = $this->_get_jquery_rule_name($rule_name);
			$msg = MMI_Form_Messages::format_error_msg($label, 'cust_'.$rule_name, $rule_parms);
			$parms = $this->_parse_rule_parms($rule_name, $rule_parms);

			switch($rule_name)
			{
				case 'max_items':
				case 'min_items':
				case 'range_items':
					$this->_options['messages'][$field_name][$jquery_rule] = $msg;
					$this->_options['rules'][$field_name][$jquery_rule] = $parms;
					break;
			}
		}
	}

	/**
	 * Get the field name, enclosing it in quotes when necessary.
	 *
	 * @param	MMI_Form_Field	the form field object
	 * @return	string
	 */
	protected function _get_field_name($field)
	{
		$field_name = $field->name();
		if (strpos($field_name, '[]') !== FALSE)
		{
			$field_name = "'".$field_name."'";
		}
		return $field_name;
	}

	/**
	 * Get the jQuery validation rule name.
	 *
	 * @param	string	the Kohana validation rule name
	 * @return	string
	 */
	protected function _get_jquery_rule_name($name)
	{
		return Arr::get(self::$_rule_map, $name, $name);
	}

	/**
	 * Parse and return the rule parameter(s).
	 *
	 * @param	string	the rule name
	 * @param	mixed	the rule parameters
	 * @return	mixed
	 */
	protected function _parse_rule_parms($name, $parms)
	{
		if (is_array($parms) AND count($parms) === 1)
		{
			$parms = $parms[0];
		}
		elseif (is_array($parms) AND count($parms) > 1)
		{
			$parms = '['.implode(', ', $parms).']';
		}
		else
		{
			$parms = $this->_get_default_rule_parms($name);
		}
		return $parms;
	}

	/**
	 * Get a rule's default parameters.
	 *
	 * @param	string	the rule name
	 * @return	mixed
	 */
	protected function _get_default_rule_parms($name)
	{
		$default = TRUE;
		$name = strtolower(trim($name));
		switch ($name)
		{
			case 'phone':
				$default = '[7, 10, 11]';
				break;
		}
		return $default;
	}

	/**
	 * Process the min and max attributes.
	 * For numeric input types (number and range), set the validation message.
	 * For date-time input types (date, datetime, datetime-local, month, time, week),
	 * remove the validation rule.  The min and max rules expect numeric data, not a
	 * date-time string.  If the min and max rules are not explicitly removed,
	 * the validation plugin will automatically generate them using the non-numeric
	 * attribute values.
	 *
	 * @return	void
	 */
	protected function _process_min_max_attr($field)
	{
		$id = $field->id();
		$rules = Arr::path($this->_options, 'rules.'.$id, array());
		if (array_key_exists('range', $rules))
		{
			// Do not process min and max when a range rule exists
			return;
		}

		$type_datetime = array('date', 'datetime', 'datetime-local', 'month', 'time', 'week');
		$type_numeric = array('number', 'range');

		$attributes = $field->attribute();
		$type = strtolower(Arr::get($attributes, 'type', 'text'));
		if ( ! in_array($type, $type_datetime) AND ! in_array($type, $type_numeric))
		{
			// Do not process min and max when the input type does not support it
			return;
		}

		foreach (array('min', 'max') as $attr_name)
		{
			$attr_value = trim(strval(Arr::get($attributes, $attr_name, '')));
			if ($attr_value !== '')
			{
				$jquery_rule = $this->_get_jquery_rule_name($attr_name);
				if (is_numeric($attr_value) AND in_array($type, $type_numeric))
				{
					$rule_parms = array($attr_value);
					$label = trim(Arr::get($field->meta('label'), 'html'), ':');
					$msg = MMI_Form_Messages::format_error_msg($label, 'cust_'.$attr_name, $rule_parms);
					$parms = $this->_parse_rule_parms($attr_name, $rule_parms);

					$this->_options['messages'][$id][$jquery_rule] = $msg;
					$this->_options['rules'][$id][$jquery_rule] = $parms;
				}
				elseif (in_array($type, $type_datetime))
				{
					$this->_options['rules'][$id][$jquery_rule] = FALSE;
				}
			}
		}
	}

	/**
	 * Generate the JavaScript for the additional validation methods.
	 *
	 * @return	string
	 */
	protected function _get_methods()
	{
		$methods = array();
		$temp = array_unique($this->_methods);
		if (count($temp) > 0)
		{
			foreach ($temp as $method)
			{
				$name = '_get_'.$method.'_method';
				$methods[] = $this->_normalize_spaces($this->$name());
			}
		}
		return implode(PHP_EOL, $methods);
	}

	/**
	 * Get the alpha validation JavaScript.
	 *
	 * @return	string
	 */
	protected function _get_alpha_method()
	{
		$method = 'alpha';
		if ($this->_unicode)
		{
			$unicode_ranges = self::_get_unicode_ranges();
			$regex = '/^['.$unicode_ranges['letters'].']+$/';
		}
		else
		{
			$regex = '/^[a-z]+$/i';
		}
		$msg = $this->_get_jquery_msg($method);
		return<<<EOJS
jQuery.validator.addMethod('$method', function(value, element, parms)
{
	return this.optional(element) || $regex.test(value);
}, '$msg');
EOJS;
	}

	/**
	 * Get the alpha-dash validation JavaScript.
	 *
	 * @return	string
	 */
	protected function _get_alpha_dash_method()
	{
		$method = 'alpha_dash';
		if ($this->_unicode)
		{
			$unicode_ranges = self::_get_unicode_ranges();
			$regex = '/^['.$unicode_ranges['dashes'].$unicode_ranges['letters'].$unicode_ranges['numbers'].$unicode_ranges['underscores'].']+$/';
		}
		else
		{
			$regex = '/^[\-a-z0-9_]+$/i';
		}
		$msg = $this->_get_jquery_msg($method);
		return<<<EOJS
jQuery.validator.addMethod('$method', function(value, element, parms)
{
	return this.optional(element) || $regex.test(value);
}, '$msg');
EOJS;
	}

	/**
	 * Get the alpha-numeric validation JavaScript.
	 *
	 * @return	string
	 */
	protected function _get_alpha_numeric_method()
	{
		$method = 'alpha_numeric';
		if ($this->_unicode)
		{
			$unicode_ranges = self::_get_unicode_ranges();
			$regex = '/^['.$unicode_ranges['letters'].$unicode_ranges['numbers'].']+$/';
		}
		else
		{
			$regex = '/^[a-z0-9]+$/i';
		}
		$msg = $this->_get_jquery_msg($method);
		return<<<EOJS
jQuery.validator.addMethod('$method', function(value, element, parms)
{
	return this.optional(element) || $regex.test(value);
}, '$msg');
EOJS;
	}

	/**
	 * Get the color validation JavaScript.
	 *
	 * @return	string
	 */
	protected function _get_color_method()
	{
		$method = 'color';
		$msg = $this->_get_jquery_msg($method);
		$regex = '/^#?[0-9a-f]{3}([0-9a-f]{3})?$/i';
		return<<<EOJS
jQuery.validator.addMethod('$method', function(value, element, parms)
{
	return this.optional(element) || $regex.test(value);
}, '$msg');
EOJS;
	}

	/**
	 * Get the credit card type validation JavaScript.
	 *
	 * @return	string
	 */
	protected function _get_credit_card_type_method()
	{
		$method = 'credit_card_type';
		$msg = $this->_get_jquery_msg('credit_card');

		$credit_cards = Kohana::config('credit_cards')->as_array();
		$credit_cards['unknown'] = array();
		$default = '';
		$i = 1;
		$js1 = array();
		$js2 = array();
		foreach ($credit_cards as $name => $settings)
		{
			$num = '0x'.str_pad(dechex($i), 4, '0', STR_PAD_LEFT);
			if (strcasecmp($name, 'default') !== 0)
			{
				$default .= $num.' | ';
				$length = explode(',', Arr::get($settings, 'length', ''));
				$length = (count($length) === 1)
					? ('value.length === '.$length[0])
					: ('jQuery.inArray(value.length, ['.implode(', ', $length).']) > -1');
				$prefix = Arr::get($settings, 'prefix', array());
				$js1[] = "if (parms === '$name') { validTypes |= ".$num.'; }';
				if (strcasecmp($name, 'unknown') === 0)
				{
					$js2[] = "if (validTypes & $num) { /* unknown */ return true; }";
				}
				else
				{
					$js2[] = "if (validTypes & $num && /^($prefix)/.test(value)) { /* $name */ return $length; }";
				}
			}
			$i *= 2;
		}
		$js1[] = "if (parms === 'default') { validTypes = ".trim($default, ' |').'; }';

		$js1 = implode(PHP_EOL."\t", $js1);
		$js2 = implode(PHP_EOL."\t", $js2);

		return<<<EOJS
jQuery.validator.addMethod('$method', function(value, element, parms)
{
	if (/[^0-9-]+/.test(value)) {
		return false;
	}

	value = value.replace(/\D/g, '');
	var validTypes = 0x0000;
	$js1

	$js2
	return false;
}, '$msg');
EOJS;
	}

	/**
	 * Get the decimal validation JavaScript.
	 *
	 * @return	string
	 */
	protected function _get_decimal_method()
	{
		$method = 'decimal';
		$msg = $this->_get_jquery_msg($method);
		$locale = localeconv();
		$decimal_point = preg_quote($locale['decimal_point']);
		return<<<EOJS
jQuery.validator.addMethod('$method', function(value, element, parms)
{
	var digits = '+';
	var places;
	if (jQuery.isArray(parms))
	{
		digits = parseInt(parms[1]);
		digits = ( ! isNaN(digits) && digits > 0) ? '{' + digits + '}' : '+';
		places = '{' + parseInt(parms[0]) + '}';
	}
	else
	{
		places = '{' + parseInt(parms) + '}';
	}
	var temp = '^[0-9]'+digits+'{$decimal_point}[0-9]'+places+'$';
	var regex = new RegExp(temp, 'i');
	return this.optional(element) || regex.test(value);
}, jQuery.validator.format('$msg'));
EOJS;
	}

	/**
	 * Get the digits unicode validation JavaScript.
	 *
	 * @return	string
	 */
	protected function _get_digits_unicode_method()
	{
		$method = 'digits_unicode';
		$msg = $this->_get_jquery_msg('digit');
		$unicode_ranges = self::_get_unicode_ranges();
		$regex = '/^['.$unicode_ranges['numbers'].']+$/';
		return<<<EOJS
jQuery.validator.addMethod('$method', function(value, element, parms)
{
	return this.optional(element) || $regex.test(value);
}, '$msg');
EOJS;
	}

	/**
	 * Get the exact length validation JavaScript.
	 *
	 * @return	string
	 */
	protected function _get_exact_length_method()
	{
		$method = 'exact_length';
		$msg = $this->_get_jquery_msg($method);
		return<<<EOJS
jQuery.validator.addMethod('$method', function(value, element, parms)
{
	value = jQuery.trim(value);
	return this.optional(element) || parseInt(value.length) === parms;
}, jQuery.validator.format('$msg'));
EOJS;
	}

	/**
	 * Get the IP validation JavaScript.
	 *
	 * @return	string
	 */
	protected function _get_ip_method()
	{
		$method = 'ip';
		$msg = $this->_get_jquery_msg($method);
		$ipv4 = '/^((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})$/';
		$ipv6 = '/^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/';
		return<<<EOJS
jQuery.validator.addMethod('$method', function(value, element, parms)
{
	return this.optional(element) || $ipv4.test(value) || $ipv6.test(value);
}, '$msg');
EOJS;
	}

	/**
	 * Get the numeric validation JavaScript.
	 *
	 * @return	string
	 */
	protected function _get_numeric_method()
	{
		$method = 'numeric';
		$msg = $this->_get_jquery_msg($method);
		$locale = localeconv();
		$decimal_point = preg_quote($locale['decimal_point']);
		return<<<EOJS
jQuery.validator.addMethod('$method', function(value, element, parms)
{
	return this.optional(element) || /^-?[\d$decimal_point]+$/.test(value);
}, '$msg');
EOJS;
	}

	/**
	 * Get the phone validation JavaScript.
	 *
	 * @return	string
	 */
	protected function _get_phone_method()
	{
		$method = 'phone';
		$msg = $this->_get_jquery_msg($method);
		return<<<EOJS
jQuery.validator.addMethod('$method', function(value, element, parms)
{
	value = value.replace(/\D/g, '');
	return this.optional(element) || jQuery.inArray(value.length, parms) > -1;
}, '$msg');
EOJS;
	}

	/**
	 * Get the regex validation JavaScript.
	 *
	 * @return	string
	 */
	protected function _get_regex_method()
	{
		$method = 'regex';
		$msg = $this->_get_jquery_msg($method);
		return<<<EOJS
jQuery.validator.addMethod('$method', function(value, element, parms)
{
	var regex = new RegExp(parms, 'i');
	return this.optional(element) || regex.test(value);
}, '$msg');
EOJS;
	}

	/**
	 * Generate a jQuery message from a Kohana validation message.
	 *
	 * @param	string	the rule name
	 * @return	string
	 */
	protected function _get_jquery_msg($name)
	{
		// Replace Kohana validation parms with jQuery parms (:parm1 becomes {0})
		$msg = MMI_Form_Messages::format_error_msg(NULL, $name, NULL);
		if (preg_match_all('/\:param[\d]+/', $msg, $matches) > 0)
		{
			$i = 0;
			foreach ($matches[0] as $name => $value)
			{
				$msg = str_replace($value, '{'.$i++.'}', $msg);
			}
		}
		return $msg;
	}

	/**
	 * Get the default validation options, as defined by the jQuery plugin.
	 *
	 * @return	array
	 */
	protected function _get_default_options()
	{
		return array
		(
			'debug'					=> FALSE,
			'errorClass'			=> 'error',
			'errorContainer'		=> '',
			'errorElement'			=> 'label',
			'errorLabelContainer'	=> '',
			'errorPlacement'		=> '',
			'focusCleanup'			=> FALSE,
			'focusInvalid'			=> TRUE,
			'groups'				=> '',
			'highlight'				=> '',
			'ignore'				=> '',
			'ignoreTitle'			=> FALSE,
			'invalidHandler'		=> '',
			'messages'				=> '',
			'meta'					=> '',
			'onclick'				=> TRUE,
			'onfocusout'			=> TRUE,
			'onkeyup'				=> TRUE,
			'onsubmit'				=> TRUE,
			'rules'					=> '',
			'showErrors'			=> '',
			'submitHandler'			=> '',
			'success'				=> '',
			'unhighlight'			=> '',
			'validClass'			=> 'valid',
			'wrapper'				=> ''
		);
	}

	/**
	 * Get the valid validation options.
	 *
	 * @return	array
	 */
	protected function _get_valid_options()
	{
		$keys = array_keys($this->_get_default_options());
		return array_combine($keys, $keys);
	}


	/**
	 * Get the default JavaScript configuration.
	 *
	 * @param	boolean	enable debugging?
	 * @param	string	the CSS class for error messages
	 * @param	string	the CSS class for valid messages
	 * @return	array
	 */
	public static function get_default_config($debug = FALSE, $error_class = 'error', $valid_class = 'success')
	{
		return array
		(
			'debug'				=> $debug,
			'errorClass'		=> $error_class,
			'errorPlacement'	=> self::get_default_error_placement(),
			'highlight'			=> self::get_default_highlight(),
			'invalidHandler'	=> self::get_default_invalid_handler('#'.MMI_Form_Messages::get_status_id()),
			'submitHandler'		=> self::get_default_submit_handler('#'.MMI_Form_Messages::get_status_id()),
			'success'			=> self::get_default_success(),
			'unhighlight'		=> self::get_default_unhighlight(),
			'validClass'		=> $valid_class,
		);
	}

	/**
	 * Get the default error placement JavaScript.
	 *
	 * @return	string
	 */
	public static function get_default_error_placement()
	{
return<<<EOJS
function(error, element)
{
	if (element.hasClass('group'))
	{
		error.insertAfter(element.parent().find('label.' + validator.settings.errorClass));
	}
	else
	{
		error.insertAfter(element);
	}
}
EOJS;
	}

	/**
	 * Get the default highlight JavaScript.
	 *
	 * @return	string
	 */
	public static function get_default_highlight()
	{
return<<<EOJS
function(element, errorClass, validClass)
{
	var e = $(element);
	if (e.hasClass('group'))
	{
		e.parent().removeClass(validClass).addClass(errorClass);
	}
	else
	{
		e.removeClass(validClass).addClass(errorClass);
	}
}
EOJS;
	}

	/**
	 * Get the default invalid handler JavaScript.
	 *
	 * @param	string	the id of the status element
	 * @return	string
	 */
	public static function get_default_invalid_handler($status_id = '#frm_status')
	{
		$substitute = 9999;
		$msg_single = MMI_Form_Messages::msg_failure_single();
		$msg_multiple = MMI_Form_Messages::msg_failure_multiple($substitute);
return<<<EOJS
function(frm, validator)
{
	var num = validator.numberOfInvalids();
	var s = validator.settings;
	if (num)
	{
		var msg = (parseInt(num) === 1)
			? '{$msg_single}'
			: '{$msg_multiple}'.replace('{$substitute}', num)
		;
		$('{$status_id}').removeClass(s.validClass).addClass(s.errorClass).html(msg).show();
	}
	else
	{
		$('{$status_id}').removeClass(s.errorClass).hide();
	}
}
EOJS;
	}

	/**
	 * Get the default submit handler JavaScript.
	 *
	 * @param	string	the id of the status element
	 * @param	string	the id of the buttons container
	 * @return	string
	 */
	public static function get_default_submit_handler($status_id = '#frm_status', $buttons_id = 'div.submit', $message = 'Submitting ...')
	{
return<<<EOJS
function(frm)
{
	$('{$status_id}').removeClass(validator.settings.errorClass).hide();
	$('{$buttons_id}').replaceWith('<div class="submit">{$message}</div>');
	frm.submit();
}
EOJS;
	}

	/**
	 * Get the default success JavaScript.
	 *
	 * @return	string
	 */
	public static function get_default_success()
	{
return<<<EOJS
function(label)
{
	$(label).addClass(validator.settings.validClass);
}
EOJS;
	}

	/**
	 * Get the default unhighlight JavaScript.
	 *
	 * @return	string
	 */
	public static function get_default_unhighlight()
	{
return<<<EOJS
function(element, errorClass, validClass)
{
	var e = $(element);
	if (e.hasClass('group'))
	{
		e.parent().removeClass(errorClass).addClass(validClass);
	}
	else
	{
		e.removeClass(errorClass).addClass(validClass);
	}
}
EOJS;
	}

	/**
	 * Get the configuration settings.
	 *
	 * @param	boolean	return the configuration as an array?
	 * @return	mixed
	 */
	public static function get_config($as_array = FALSE)
	{
		(self::$_config === NULL) AND self::$_config = Kohana::config('mmi-form-plugin-jquery-validation');
		$config = self::$_config;
		if ($as_array)
		{
			$config = $config->as_array();
		}
		return $config;
	}

	/**
	 * Get the unicode ranges.
	 *
	 * @return	array
	 */
	protected static function _get_unicode_ranges()
	{
		$config = self::get_config(TRUE);
		(self::$_unicode_ranges === NULL) AND self::$_unicode_ranges = Arr::get($config, 'unicode', self::$_default_unicode_ranges);
		return self::$_unicode_ranges;
	}
} // End Kohana_MMI_Form_Plugin_JQuery_Validation
