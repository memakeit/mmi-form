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
	 * Convert the value to a timestamp.
	 *
	 * @param	string	the year and week (ex. 2010-W09)
	 * @return	integer
	 */
	public static function get_value($value)
	{
		if (preg_match('/(\d{4})-W(\d{2})/i', $value, $matches))
		{
			$year = $matches[1];
			$week = $matches[2];
			return strtotime("{$year}W{$week} UTC");
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
		return self::get_value('1970-W01');
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
		$span = abs($value - $base);
		$div = $span / self::get_step($step);
		return (is_int($span) AND ceil($div) == $div);
	}

	/**
	 * Get the step interval.
	 *
	 * @param	integer	the step amount
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
