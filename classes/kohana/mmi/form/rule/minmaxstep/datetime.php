<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Validation rules to enforce min, max, and step attributes of date-time types.
 *
 * @package		MMI Form
 * @category	rule
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Rule_MinMaxStep_DateTime
{
	/**
	 * Create the min, max, and step rules.
	 *
	 * @param	MMI_Form_Field	a form field object
	 * @return	void
	 */
	public static function init(MMI_Form_Field $field, $mode = 'date')
	{
		$class = 'MMI_Form_Rule_MinMaxStep_DateTime';
		$mode = strtolower($mode);

		$attributes = $field->attribute();
		$meta = $field->meta();
		$callbacks = Arr::get($meta, 'callbacks', array());
		if ( ! is_array($callbacks))
		{
			$callbacks = array();
		}
		$rules = Arr::get($meta, 'rules', array());
		if ( ! is_array($rules))
		{
			$rules = array();
		}

		// Process the max and min attributes
		$min = strval(Arr::get($attributes, 'min', ''));
		$max = strval(Arr::get($attributes, 'max', ''));
		if ( ! empty($min) AND ! empty($max))
		{
			$callbacks['range_'.$mode] = array
			(
				array($class, 'range'), array('min' => $min, 'max' => $max, 'mode' => $mode)
			);
		}
		elseif ( ! empty($min))
		{
			$callbacks['min_'.$mode] = array
			(
				array($class, 'min'), array('min' => $min, 'mode' => $mode)
			);
		}
		elseif ( ! empty($max))
		{
			$callbacks['max_'.$mode] = array
			(
				array($class, 'max'), array('max' => $max, 'mode' => $mode)
			);
		}

		// Process the step attribute
		$step = strval(Arr::get($attributes, 'step', ''));
		if ( ! empty($step))
		{
			$callbacks['step_'.$mode] = array
			(
				array($class, 'step'), array('min' => $min, 'max' => $max, 'step' => $step, 'mode' => $mode)
			);
		}
		$field->meta('callbacks', $callbacks);
		$field->meta('rules', $rules);
	}

	/**
	 * Test if a value is within a range.
	 *
	 * @param 	Validate	the validation object
	 * @param	string		the field name
	 * @param	array		the validation parameters
	 * @return	boolean
	 */
	public static function range(Validate $validate, $field, $parms = array())
	{
		$mode = strtolower(Arr::get($parms, 'mode', 'date'));
		$class = 'MMI_Form_Rule_MinMaxStep_'.ucfirst($mode);

		$value = strval(Arr::get($_POST, $field, ''));
		$value_set = FALSE;
		if ( ! empty($value))
		{
			$value = call_user_func(array($class, 'get_value'), $value);
			$value_set = TRUE;
		}
		$method = ($value instanceof DateTime) ? 'get_value_dt' : 'get_value';

		$min = strval(Arr::get($parms, 'min', ''));
		$min_orig = $min;
		if ( ! empty($min))
		{
			$min = call_user_func(array($class, $method), $min);
		}
		$max = strval(Arr::get($parms, 'max', ''));
		$max_orig = $max;
		if ( ! empty($max))
		{
			$max = call_user_func(array($class, $method), $max);
		}

		if (is_numeric($min) AND is_numeric($max) AND is_numeric($value) AND $value >= $min AND $value <= $max)
		{
			return TRUE;
		}
		elseif ($min instanceof DateTime AND $max instanceof DateTime AND $value instanceof DateTime AND $value >= $min AND $value <= $max)
		{
			return TRUE;
		}

		if ($value_set)
		{
			$validate->error($field, 'range', array($min_orig, $max_orig));
		}
		return FALSE;
	}

	/**
	 * Test if a value is less than or equal to a maximum value.
	 *
	 * @param 	Validate	the validation object
	 * @param	string		the field name
	 * @param	array		the validation parameters
	 * @return	boolean
	 */
	public static function max(Validate $validate, $field, $parms = array())
	{
		$mode = strtolower(Arr::get($parms, 'mode', 'date'));
		$class = 'MMI_Form_Rule_MinMaxStep_'.ucfirst($mode);

		$value = strval(Arr::get($_POST, $field, ''));
		$value_set = FALSE;
		if ( ! empty($value))
		{
			$value = call_user_func(array($class, 'get_value'), $value);
			$value_set = TRUE;
		}
		$method = ($value instanceof DateTime) ? 'get_value_dt' : 'get_value';

		$max = strval(Arr::get($parms, 'max', ''));
		$max_orig = $max;
		if ( ! empty($max))
		{
			$max = call_user_func(array($class, $method), $max);
		}

		if (is_numeric($max) AND is_numeric($value) AND $value <= $max)
		{
			return TRUE;
		}
		elseif ($max instanceof DateTime AND $value instanceof DateTime AND $value <= $max)
		{
			return TRUE;
		}

		if ($value_set)
		{
			$validate->error($field, 'cust_max', array($max_orig));
		}
		return FALSE;
	}

	/**
	 * Test if a value is greater than or equal to a minimum value.
	 *
	 * @param 	Validate	the validation object
	 * @param	string		the field name
	 * @param	array		the validation parameters
	 * @return	boolean
	 */
	public static function min(Validate $validate, $field, $parms = array())
	{
		$mode = strtolower(Arr::get($parms, 'mode', 'date'));
		$class = 'MMI_Form_Rule_MinMaxStep_'.ucfirst($mode);

		$value = strval(Arr::get($_POST, $field, ''));
		$value_set = FALSE;
		if ( ! empty($value))
		{
			$value = call_user_func(array($class, 'get_value'), $value);
			$value_set = TRUE;
		}
		$method = ($value instanceof DateTime) ? 'get_value_dt' : 'get_value';

		$min = strval(Arr::get($parms, 'min', ''));
		$min_orig = $min;
		if ( ! empty($min))
		{
			$min = call_user_func(array($class, $method), $min);
		}

		if (is_numeric($min) AND is_numeric($value) AND $value >= $min)
		{
			return TRUE;
		}
		elseif ($min instanceof DateTime AND $value instanceof DateTime AND $value >= $min)
		{
			return TRUE;
		}

		if ($value_set)
		{
			$validate->error($field, 'cust_min', array($min_orig));
		}
		return FALSE;
	}

	/**
	 * Test if a value is evenly divisible by the step value.
	 *
	 * @param 	Validate	the validation object
	 * @param	string		the field name
	 * @param	array		the validation parameters
	 * @return	boolean
	 */
	public static function step(Validate $validate, $field, $parms = array())
	{
		$mode = strtolower(Arr::get($parms, 'mode', 'date'));
		$class = 'MMI_Form_Rule_MinMaxStep_'.ucfirst($mode);

		$value = strval(Arr::get($_POST, $field, ''));
		$value_set = FALSE;
		if ( ! empty($value))
		{
			$value = call_user_func(array($class, 'get_value'), $value);
			$value_set = TRUE;
		}
		$method = ($value instanceof DateTime) ? 'get_value_dt' : 'get_value';

		$max = strval(Arr::get($parms, 'max', ''));
		$min = strval(Arr::get($parms, 'min', ''));
		$base = NULL;
		if ( ! empty($min))
		{
			$base = call_user_func(array($class, $method), $min);
		}
		elseif ( ! empty($max))
		{
			$base = call_user_func(array($class, $method), $max);
		}
		if ( ! isset($base))
		{
			$method = ($value instanceof DateTime) ? 'get_default_min_dt' : 'get_default_min';
			$base = call_user_func(array($class, $method), NULL);
		}

		$step = Arr::get($parms, 'step');
		if (is_numeric($step) AND is_numeric($value) AND is_numeric($base))
		{
			if (call_user_func_array(array($class, 'valid_step'), array($value, $base, $step)))
			{
				return TRUE;
			}
		}
		elseif (is_numeric($step) AND $value instanceof DateTime AND $base instanceof DateTime)
		{
			if (call_user_func_array(array($class, 'valid_step_dt'), array($value, $base, $step)))
			{
				return TRUE;
			}
		}

		if ($value_set)
		{
			$info = array();
			if (method_exists($class, 'format_step') AND ! empty($step))
			{
				$info = call_user_func(array($class, 'format_step'), $step);
			}
			$step_label = Arr::get($info, 'label', $mode);
			$step_qty = Arr::get($info, 'qty', $step);
			$validate->error($field, 'cust_step', array($step_qty, Inflector::plural($step_label, $step_qty)));
		}
		return FALSE;
	}

	/**
	 * Convert the value to a timestamp.
	 * If a value can not be converted to a timestamp, a DateTime object is returned.
	 *
	 * @param	string	a valid date time format (ex. 1990-12-31T23:59:60Z, 1996-12-19T16:39:57-08:00)
	 * @return	mixed
	 */
	public static function get_value($value)
	{
		$output = strtotime($value);
		if ( ! is_numeric($output))
		{
			$output = self::get_value_dt($value);
		}
		return $output;
	}

	/**
	 * Convert the value to a DateTime object.
	 *
	 * @param	string	a valid date time format (ex. 1990-12-31T23:59:60Z, 1996-12-19T16:39:57-08:00)
	 * @return	DateTime
	 */
	public static function get_value_dt($value)
	{
		return new DateTime($value);
	}

	/**
	 * Get the default minimum value (as a timestamp).
	 *
	 * @return	integer
	 */
	public static function get_default_min()
	{
		return self::get_value('1970-01-01T00:00:00Z');
	}

	/**
	 * Get the default minimum value (as a DateTime object).
	 *
	 * @return	DateTime
	 */
	public static function get_default_min_dt()
	{
		return self::get_value_dt('1970-01-01T00:00:00Z');
	}

	/**
	 * Check whether the step interval is valid.
	 * The comparison is done using timestamp values.
	 *
	 * @param	integer	the value
	 * @param	integer	the base value for calculations
	 * @param	integer	the step amount
	 * @return	boolean
	 */
	public static function valid_step($value, $base, $step)
	{
		$span = abs($value - $base);
		$div = $span / $step;
		return (ceil($div) == $div);
	}

	/**
	 * Check whether the step interval is valid.
	 * The comparison is done using DateTime values.
	 *
	 * @param	DateTime	the value
	 * @param	DateTime	the base value for calculations
	 * @param	integer	the step amount
	 * @return	boolean
	 */
	public static function valid_step_dt($value, $base, $step)
	{
		if (class_exists('MMI_Log'))
		{
			MMI_Log::log_info(__METHOD__, __LINE__, 'Unable to calculate step interval using DateTime objects');
		}
		return TRUE;
	}

	/**
	 * Get the step label and quantity.
	 *
	 * @param	mixed	the step interval (int|float)
	 * @return	array
	 */
	public static function format_step($step)
	{
		if ( ! ctype_digit(strval($step)))
		{
			return array('label' => 'second', 'qty' => $step);
		}

		if ($step >= Date::DAY)
		{
			$div = $step / Date::DAY;
			if (ceil($div) == $div)
			{
				return array('label' => 'day', 'qty' => $div);
			}
		}
		if ($step >= Date::HOUR)
		{
			$div = $step / Date::HOUR;
			if (ceil($div) == $div)
			{
				return array('label' => 'hour', 'qty' => $div);
			}
		}
		if ($step >= Date::MINUTE)
		{
			$div = $step / Date::MINUTE;
			if (ceil($div) == $div)
			{
				return array('label' => 'minute', 'qty' => $div);
			}
		}
		return array('label' => 'second', 'qty' => $step);
	}
} // End Kohana_MMI_Form_Rule_MinMaxStep_DateTime
