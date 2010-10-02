<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Validation rules to enforce min, max, and step attributes of date-time types.
 *
 * @package		MMI Form
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
		$min = Arr::get($attributes, 'min');
		$max = Arr::get($attributes, 'max');
		if (isset($min) AND isset($max))
		{
			$callbacks['range_'.$mode] = array
			(
				array($class, 'range'), array('min' => $min, 'max' => $max, 'mode' => $mode)
			);
		}
		elseif (isset($min))
		{
			$callbacks['min_'.$mode] = array
			(
				array($class, 'min'), array('min' => $min, 'mode' => $mode)
			);
		}
		elseif (isset($max))
		{
			$callbacks['max_'.$mode] = array
			(
				array($class, 'max'), array('max' => $max, 'mode' => $mode)
			);
		}

		// Process the step attribute
		$step = Arr::get($attributes, 'step');
		if (isset($step))
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

		$min = Arr::get($parms, 'min');
		$min_orig = $min;
		if (isset($min))
		{
			$min = call_user_func(array($class, 'get_value'), $min);
		}
		$max = Arr::get($parms, 'max');
		$max_orig = $max;
		if (isset($max))
		{
			$max = call_user_func(array($class, 'get_value'), $max);
		}

		$value = Arr::get($_POST, $field);
		if ($value === '')
		{
			$value = NULL;
		}
		else
		{
			$value = call_user_func(array($class, 'get_value'), $value);
		}

		if (is_numeric($min) AND is_numeric($max) AND is_numeric($value) AND $value >= $min AND $value <= $max)
		{
			return TRUE;
		}

		if (isset($value))
		{
			$validate->error($field, 'range', array($min_orig, $max_orig));
		}
		return FALSE;
	}

	/**
	 * Test if a value is less than or equal than a maximum value.
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

		$max = Arr::get($parms, 'max');
		$max_orig = $max;
		if (isset($max))
		{
			$max = call_user_func(array($class, 'get_value'), $max);
		}

		$value = Arr::get($_POST, $field);
		if ($value === '')
		{
			$value = NULL;
		}
		else
		{
			$value = call_user_func(array($class, 'get_value'), $value);
		}

		if (is_numeric($max) AND is_numeric($value) AND $value <= $max)
		{
			return TRUE;
		}

		if (isset($value))
		{
			$validate->error($field, 'cust_max', array($max_orig));
		}
		return FALSE;
	}

	/**
	 * Test if a value is greater than or equal than a minimum value.
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

		$min = Arr::get($parms, 'min');
		$min_orig = $min;
		if (isset($min))
		{
			$min = call_user_func(array($class, 'get_value'), $min);
		}

		$value = Arr::get($_POST, $field);
		if ($value === '')
		{
			$value = NULL;
		}
		else
		{
			$value = call_user_func(array($class, 'get_value'), $value);
		}

		if (is_numeric($min) AND is_numeric($value) AND $value >= $min)
		{
			return TRUE;
		}

		if (isset($value))
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

		$max = Arr::get($parms, 'max');
		$min = Arr::get($parms, 'min');
		$base = NULL;
		if (isset($min))
		{
			$base = call_user_func(array($class, 'get_value'), $min);
		}
		elseif (isset($max))
		{
			$base = call_user_func(array($class, 'get_value'), $max);
		}
		if ( ! isset($base))
		{
			$base = call_user_func(array($class, 'get_default_min'), NULL);
		}

		$value = Arr::get($_POST, $field);
		if ($value === '')
		{
			$value = NULL;
		}
		else
		{
			$value = call_user_func(array($class, 'get_value'), $value);
		}

		$step = Arr::get($parms, 'step');
		if (is_numeric($step) AND is_numeric($value) AND is_numeric($base))
		{
			if (call_user_func_array(array($class, 'valid_step'), array($value, $base, $step)))
			{
				return TRUE;
			}
		}

		if (isset($value))
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
} // End Kohana_MMI_Form_Rule_MinMaxStep_DateTime
