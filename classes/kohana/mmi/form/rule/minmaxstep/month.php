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
	 * Process the value.
	 *
	 * @param	string	the year and month (ex. 2010-03)
	 * @return	integer
	 */
	public static function get_value($value)
	{
		if (preg_match('/(\d{4})-(\d{2})/i', $value, $matches))
		{
			$year = $matches[1];
			$month = $matches[2];
			return (12 * intval($year) + intval($month));
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
		return self::get_value('1970-01');
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
		$div = $span / $step;
		return (is_int($span) AND ceil($div) == $div);
	}
} // End Kohana_MMI_Form_Rule_MinMaxStep_Month
