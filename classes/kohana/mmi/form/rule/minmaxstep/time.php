<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Validation rules to enforce min, max, and step attributes of month input types.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Rule_MinMaxStep_Time
{
	/**
	 * Convert the string representation to a timestamp.
	 *
	 * @param	string	the string representation of the time (ex. 11:45, 13:15:01, 08:20:00.25)
	 * @return	integer
	 */
	public static function get_value($value)
	{
		if (preg_match('/(\d{2}):(\d{2}):(\d{2}\.{d}+)/i', $value, $matches))
		{
			$hours = $matches[1];
			$minutes = $matches[2];
			$seconds = $matches[3];
			return (Date::HOUR * intval($hours)) + (Date::MINUTE * intval($minutes) + $seconds);
		}
		if (preg_match('/(\d{2}):(\d{2}):(\d{2})/i', $value, $matches))
		{
			$hours = $matches[1];
			$minutes = $matches[2];
			$seconds = $matches[3];
			return (Date::HOUR * intval($hours)) + (Date::MINUTE * intval($minutes) + intval($seconds));
		}
		elseif (preg_match('/(\d{2}):(\d{2})/i', $value, $matches))
		{
			$hours = $matches[1];
			$minutes = $matches[2];
			return (Date::HOUR * intval($hours)) + (Date::MINUTE * intval($minutes));
		}
		return NULL;
	}

	/**
	 * Calculate the step interval.
	 *
	 * @param	mixed	the step interval (int|float)
	 * @return	mixed
	 */
	public static function get_step($step)
	{
		if (is_numeric($step))
		{
			return $step;
		}
		return NULL;
	}

	/**
	 * Get the step label and quantity.
	 *
	 * @param	mixed	the step interval (int|float)
	 * @return	array
	 */
	public static function format_step($step)
	{
		if ( ! is_int($step))
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
