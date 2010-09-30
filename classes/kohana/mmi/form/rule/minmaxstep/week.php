<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Validation rules to enforce min, max, and step attributes of week input types.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Rule_MinMaxStep_Week
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

		// Process max and min attributes
		$min = Arr::get($attributes, 'min');
		$max = Arr::get($attributes, 'max');
		if ( ! empty($min) AND ! empty($max))
		{
			$step = Arr::get($attributes, 'step');
			$callbacks['range_week'] = array
			(
				array('MMI_Form_Rule_MinMaxStep_Week', 'range'), array('min' => $min, 'max' => $max)
			);
		}
		elseif ( ! empty($min))
		{
			$step = Arr::get($attributes, 'step');
			$callbacks['min_week'] = array
			(
				array('MMI_Form_Rule_MinMaxStep_Week', 'min'), array('min' => $min)
			);
		}
		elseif ( ! empty($max))
		{
			$step = Arr::get($attributes, 'step');
			$callbacks['max_week'] = array
			(
				array('MMI_Form_Rule_MinMaxStep_Week', 'max'), array('max' => $max)
			);
		}

		// Process step attribute
		if (isset($step))
		{
			$callbacks['step_week'] = array
			(
				array('MMI_Form_Rule_MinMaxStep_Week', 'step'), array('min' => $min, 'max' => $max, 'step' => $step)
			);
		}
		$field->meta('callbacks', $callbacks);
		$field->meta('rules', $rules);
	}

	/**
	 * Test if a week is within a range.
	 *
	 * @param 	Validate	the validation object
	 * @param	string		the field name
	 * @param	array		the validation parameters
	 * @return	boolean
	 */
	public static function range(Validate $validate, $field, $parms = array())
	{
		$min = Arr::get($parms, 'min');
		$min_time = NULL;
		if ( ! empty($min))
		{
			$min_time = self::_get_timestamp($min);
		}
		$max = Arr::get($parms, 'max');
		$max_time = NULL;
		if ( ! empty($max))
		{
			$max_time = self::_get_timestamp($max);
		}
		$value = Arr::get($_POST, $field);
		$value_time = NULL;
		if ( ! empty($value))
		{
			$value_time = self::_get_timestamp($value);
		}

		if (is_numeric($min_time) AND is_numeric($max_time) AND is_numeric($value_time))
		{
			if ($value_time >= $min_time AND $value_time <= $max_time)
			{
				return TRUE;
			}
		}
		$validate->error($field, 'range_week', array($min, $max));
		return FALSE;
	}

	/**
	 * Test if a week is less than or equal than a maximum value.
	 *
	 * @param 	Validate	the validation object
	 * @param	string		the field name
	 * @param	array		the validation parameters
	 * @return	boolean
	 */
	public static function max(Validate $validate, $field, $parms = array())
	{
		$max = Arr::get($parms, 'max');
		$max_time = NULL;
		if ( ! empty($max))
		{
			$max_time = self::_get_timestamp($max);
		}
		$value = Arr::get($_POST, $field);
		$value_time = NULL;
		if ( ! empty($value))
		{
			$value_time = self::_get_timestamp($value);
		}

		if (is_numeric($max_time) AND is_numeric($value_time))
		{
			if ($value_time <= $max_time)
			{
				return TRUE;
			}
		}
		$validate->error($field, 'max_week', array($max));
		return FALSE;
	}

	/**
	 * Test if a week is greater than or equal than a minimum value.
	 *
	 * @param 	Validate	the validation object
	 * @param	string		the field name
	 * @param	array		the validation parameters
	 * @return	boolean
	 */
	public static function min(Validate $validate, $field, $parms = array())
	{
		$min = Arr::get($parms, 'min');
		$min_time = NULL;
		if ( ! empty($min))
		{
			$min_time = self::_get_timestamp($min);
		}
		$value = Arr::get($_POST, $field);
		$value_time = NULL;
		if ( ! empty($value))
		{
			$value_time = self::_get_timestamp($value);
		}

		if (is_numeric($min_time) AND is_numeric($value_time))
		{
			if ($value_time >= $min_time)
			{
				return TRUE;
			}
		}
		$validate->error($field, 'min_week', array($min));
		return FALSE;
	}

	/**
	 * Test if a number is evenly divisible by the step value.
	 *
	 * @param 	Validate	the validation object
	 * @param	string		the field name
	 * @param	array		the validation parameters
	 * @return	boolean
	 */
	public static function step(Validate $validate, $field, $parms = array())
	{
		$min = Arr::get($parms, 'min');
		$min_time = NULL;
		if ( ! empty($min))
		{
			$min_time = self::_get_timestamp($min);
		}
		$max = Arr::get($parms, 'max');
		$max_time = NULL;
		if ( ! empty($max))
		{
			$max_time = self::_get_timestamp($max);
		}
		$value = Arr::get($_POST, $field);
		$value_time = NULL;
		if ( ! empty($value))
		{
			$value_time = self::_get_timestamp($value);
		}

		$step = Arr::get($parms, 'step');
		$step_time = NULL;
		if (is_numeric($step))
		{
			$step_time = Date::WEEK * $step;
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
		$validate->error($field, 'step_week', array($step));
		return FALSE;
	}

	/**
	 * Convert a representation of the week to a timestamp.
	 *
	 * @param	string	the string representation of the week (ex. 2010-W09)
	 * @return	integer
	 */
	protected static function _get_timestamp($value)
	{
		if (preg_match('/(\d{4})-W(\d{2})/i', $value, $matches))
		{
			$year = $matches[1];
			$week = $matches[2];
			return mktime(0, 0, 0, 1, 1, $year) + (Date::WEEK * intval($week));
		}
		return NULL;
	}
} // End Kohana_MMI_Form_Rule_MinMaxStep_Week
