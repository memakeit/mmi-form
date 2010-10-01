<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Validation rules to enforce min, max, and step attributes of month input types.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Rule_MinMaxStep_Month
{
	/**
	 * Convert the representation of a month to a timestamp.
	 *
	 * @param	string	the string representation of the month (ex. 2010-03)
	 * @return	integer
	 */
	public static function get_value($value)
	{
		if (preg_match('/(\d{4})-(\d{2})/i', $value, $matches))
		{
			$year = $matches[1];
			$month = $matches[2];
			return (Date::MONTH * 12 * intval($year)) + (Date::MONTH * intval($month));
		}
		return NULL;
	}

	/**
	 * Calculate the month step interval.
	 *
	 * @param	integer	the step interval
	 * @return	integer
	 */
	public static function get_step($step)
	{
		if (is_numeric($step))
		{
			return (Date::MONTH * $step);
		}
		return NULL;
	}
} // End Kohana_MMI_Form_Rule_MinMaxStep_Month
