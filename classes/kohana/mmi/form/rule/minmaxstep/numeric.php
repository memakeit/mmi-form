<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Validation rules to enforce min, max, and step attributes of numeric types.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Rule_MinMaxStep_Numeric
{
	/**
	 * Create the min, max, and step rules.
	 *
	 * @param	MMI_Form_Field	a form field object
	 * @return	void
	 */
	public static function init(MMI_Form_Field $field)
	{
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
		if (is_numeric($min) AND is_numeric($max))
		{
			$step = Arr::get($attributes, 'step');
			$rules['range'] = array($min, $max);
		}
		elseif (is_numeric($min))
		{
			$step = Arr::get($attributes, 'step');
			$callbacks['min_numeric'] = array
			(
				array('MMI_Form_Rule_MinMaxStep_Numeric', 'min'), array('min' => $min)
			);
		}
		elseif (is_numeric($max))
		{
			$step = Arr::get($attributes, 'step');
			$callbacks['max_numeric'] = array
			(
				array('MMI_Form_Rule_MinMaxStep_Numeric', 'max'), array('max' => $max)
			);
		}

		// Process the step attribute
		if (isset($step))
		{
			$callbacks['step_numeric'] = array
			(
				array('MMI_Form_Rule_MinMaxStep_Numeric', 'step'), array('max' => $max, 'min' => $min, 'step' => $step)
			);
		}
		$field->meta('callbacks', $callbacks);
		$field->meta('rules', $rules);
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
		$max = Arr::get($parms, 'max');
		$value = Arr::get($_POST, $field);
		if (is_numeric($max) AND is_numeric($value))
		{
			if ($value <= $max)
			{
				return TRUE;
			}
			$validate->error($field, 'max_numeric', array($max));
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
		$min = Arr::get($parms, 'min');
		$value = Arr::get($_POST, $field);
		if (is_numeric($min) AND is_numeric($value))
		{
			if ($value >= $min)
			{
				return TRUE;
			}
			$validate->error($field, 'min_numeric', array($min));
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
		$max = Arr::get($parms, 'max');
		$min = Arr::get($parms, 'min');
		$step = Arr::get($parms, 'step');
		$value = Arr::get($_POST, $field);
		if (is_numeric($step) AND is_numeric($value))
		{
			if (is_numeric($min) AND $value >= $min)
			{
				$value = $value - $min;
			}
			elseif (is_numeric($max) AND $value <= $max)
			{
				$value = $max - $value;
			}
			$div = $value / $step;
			if (ceil($div) == $div)
			{
				return TRUE;
			}
			$validate->error($field, 'step_numeric', array($step));
		}
		return FALSE;
	}
} // End Kohana_MMI_Form_Rule_MinMaxStep_Numeric
