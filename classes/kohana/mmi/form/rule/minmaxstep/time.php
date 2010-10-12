<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Validation rules to enforce min, max, and step attributes of time input types.
 *
 * @package		MMI Form
 * @category	rule
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Rule_MinMaxStep_Time
{
	/**
	 * Process the value.
	 *
	 * @param	string	the time (ex. 11:45, 13:15:01, 08:20:00.25)
	 * @return	mixed
	 */
	public static function get_value($value)
	{
		if (preg_match('/(\d{2}):(\d{2}):(\d{2}\.\d+)/i', $value, $matches))
		{
			$hours = $matches[1];
			$minutes = $matches[2];
			$seconds = $matches[3];
			return (Date::HOUR * intval($hours) + Date::MINUTE * intval($minutes) + $seconds);
		}
		if (preg_match('/(\d{2}):(\d{2}):(\d{2})/i', $value, $matches))
		{
			$hours = $matches[1];
			$minutes = $matches[2];
			$seconds = $matches[3];
			return (Date::HOUR * intval($hours) + Date::MINUTE * intval($minutes) + intval($seconds));
		}
		elseif (preg_match('/(\d{2}):(\d{2})/i', $value, $matches))
		{
			$hours = $matches[1];
			$minutes = $matches[2];
			return (Date::HOUR * intval($hours) + Date::MINUTE * intval($minutes));
		}
		return NULL;
	}

	/**
	 * Get the default minimum value.
	 *
	 * @return	integer
	 */
	public static function get_default_min()
	{
		return self::get_value('00:00');
	}

	/**
	 * Check whether the step interval is valid.
	 *
	 * @param	mixed	the value
	 * @param	mixed	the base value for calculations
	 * @param	mixed	the step amount
	 * @return	boolean
	 */
	public static function valid_step($value, $base, $step)
	{
		$span = $value - $base;
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
} // End Kohana_MMI_Form_Rule_MinMaxStep_Time
