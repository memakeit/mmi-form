<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Validation rules to enforce min, max, and step attributes of datetime-local input types.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Rule_MinMaxStep_DateTimeLocal
{
	/**
	 * Convert the value to a timestamp.
	 *
	 * @param	string	a valid date time format (ex. 1990-12-31T23:59:60)
	 * @return	integer
	 */
	public static function get_value($value)
	{
		return strtotime($value);
	}

	/**
	 * Get the default minimum value.
	 *
	 * @return	integer
	 */
	public static function get_default_min()
	{
		return self::get_value('1970-01-01T00:00:00');
	}

	/**
	 * Check whether the step interval is valid.
	 *
	 * @param	integer	the value
	 * @param	integer	the base value for calculations
	 * @param	integer	the step amount
	 * @return	boolean
	 */
	public static function valid_step($value, $base, $step)
	{
		// Ensure the same GMT offset is used for bith values
		$value_offset = date('P', $value);
		$base = strtotime(date('Y-m-d G:i:s', $base).' '.$value_offset);

		$span = abs($value - $base);
		$div = $span / $step;
		return (ceil($div) == $div);
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
} // End Kohana_MMI_Form_Rule_MinMaxStep_DateTimeLocal
