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
		if ( ! empty($min) AND ! empty($max))
		{
			$step = Arr::get($attributes, 'step');
			$callbacks['range_'.$mode] = array
			(
				array($class, 'range'), array('min' => $min, 'max' => $max, 'mode' => $mode)
			);
		}
		elseif ( ! empty($min))
		{
			$step = Arr::get($attributes, 'step');
			$callbacks['min_'.$mode] = array
			(
				array($class, 'min'), array('min' => $min, 'mode' => $mode)
			);
		}
		elseif ( ! empty($max))
		{
			$step = Arr::get($attributes, 'step');
			$callbacks['max_'.$mode] = array
			(
				array($class, 'max'), array('max' => $max, 'mode' => $mode)
			);
		}

		// Process the step attribute
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
		$min_time = NULL;
		if ( ! empty($min))
		{
			$min_time = call_user_func(array($class, 'get_value'), $min);
		}
		$max = Arr::get($parms, 'max');
		$max_time = NULL;
		if ( ! empty($max))
		{
			$max_time = call_user_func(array($class, 'get_value'), $max);
		}
		$value = Arr::get($_POST, $field);
		$value_time = NULL;
		if ( ! empty($value))
		{
			$value_time = call_user_func(array($class, 'get_value'), $value);
		}

		if (is_numeric($min_time) AND is_numeric($max_time) AND is_numeric($value_time))
		{
			if ($value_time >= $min_time AND $value_time <= $max_time)
			{
				return TRUE;
			}
		}
		$validate->error($field, 'range_'.$mode, array($min, $max));
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
		$max_time = NULL;
		if ( ! empty($max))
		{
			$max_time = call_user_func(array($class, 'get_value'), $max);
		}
		$value = Arr::get($_POST, $field);
		$value_time = NULL;
		if ( ! empty($value))
		{
			$value_time = call_user_func(array($class, 'get_value'), $value);
		}

		if (is_numeric($max_time) AND is_numeric($value_time))
		{
			if ($value_time <= $max_time)
			{
				return TRUE;
			}
		}
		$validate->error($field, 'max_'.$mode, array($max));
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
		$min_time = NULL;
		if ( ! empty($min))
		{
			$min_time = call_user_func(array($class, 'get_value'), $min);
		}
		$value = Arr::get($_POST, $field);
		$value_time = NULL;
		if ( ! empty($value))
		{
			$value_time = call_user_func(array($class, 'get_value'), $value);
		}

		if (is_numeric($min_time) AND is_numeric($value_time))
		{
			if ($value_time >= $min_time)
			{
				return TRUE;
			}
		}
		$validate->error($field, 'min_'.$mode, array($min));
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

		$min = Arr::get($parms, 'min');
		$min_time = NULL;
		if ( ! empty($min))
		{
			$min_time = call_user_func(array($class, 'get_value'), $min);
		}
		$max = Arr::get($parms, 'max');
		$max_time = NULL;
		if ( ! empty($max))
		{
			$max_time = call_user_func(array($class, 'get_value'), $max);
		}
		$value = Arr::get($_POST, $field);
		$value_time = NULL;
		if ( ! empty($value))
		{
			$value_time = call_user_func(array($class, 'get_value'), $value);
		}

		$step = Arr::get($parms, 'step');
		$step_time = NULL;
		if (is_numeric($step))
		{
			$step_time = call_user_func(array($class, 'get_step'), $step);
		}

		if (is_numeric($step_time) AND is_numeric($value_time))
		{
			if (is_numeric($min_time) AND $value_time >= $min_time)
			{
				$value_time = $value_time - $min_time;
			}
			elseif (is_numeric($max_time) AND $value_time <= $max_time)
			{
				$value_time = $max_time - $value_time;
			}
			$div = $value_time / $step_time;
			if (ceil($div) == $div)
			{
				return TRUE;
			}
		}
		$validate->error($field, 'step_'.$mode, array($step));
		return FALSE;
	}
} // End Kohana_MMI_Form_Rule_MinMaxStep_DateTime
