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
	 * Convert the representation of a week to a timestamp.
	 *
	 * @param	string	the string representation of the week (ex. 2010-W09)
	 * @return	integer
	 */
	public static function get_value($value)
	{
		if (preg_match('/(\d{4})-W(\d{2})/i', $value, $matches))
		{
			$year = $matches[1];
			$week = $matches[2];
			return mktime(0, 0, 0, 1, 1, $year) + (Date::WEEK * intval($week));
		}
		return NULL;
	}

	/**
	 * Caclulate the week step interval.
	 *
	 * @param	integer	the step interval
	 * @return	integer
	 */
	public static function get_step($step)
	{
		if (is_numeric($step))
		{
			return (Date::WEEK * $step);
		}
		return NULL;
	}
} // End Kohana_MMI_Form_Rule_MinMaxStep_Week
