<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Validation rules to enforce min, max, and step attributes of date input types.
 *
 * @package		MMI Form
 * @category	rule
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Rule_MinMaxStep_Date
{
	/**
	 * Convert the value to a timestamp.
	 * If a value can not be converted to a timestamp, a DateTime object is returned.
	 *
	 * @param	string	the year, month and day (ex. 2010-09-31)
	 * @return	mixed
	 */
	public static function get_value($value)
	{
		if (preg_match('/(\d{4})-(\d{2})-(\d{2})/i', $value, $matches))
		{
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			$output = gmmktime(0, 0, 0, $month, $day, $year);
			if ( ! is_numeric($output))
			{
				$output = self::get_value_dt($value);
			}
			return $output;
		}
		return NULL;
	}

	/**
	 * Convert the value to a DateTime object.
	 *
	 * @param	string	the year, month and day (ex. 2010-09-31)
	 * @return	DateTime
	 */
	public static function get_value_dt($value)
	{
		if (preg_match('/(\d{4})-(\d{2})-(\d{2})/i', $value, $matches))
		{
			return new DateTime($value.' UTC');
		}
		return NULL;
	}

	/**
	 * Get the default minimum value (as a timestamp).
	 *
	 * @return	integer
	 */
	public static function get_default_min()
	{
		return self::get_value('1970-01-01');
	}

	/**
	 * Get the default minimum value (as a DateTime object).
	 *
	 * @return	DateTime
	 */
	public static function get_default_min_dt()
	{
		return self::get_value_dt('1970-01-01');
	}

	/**
	 * Check whether the step interval is valid.
	 * The comparison is done using timestamp values.
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
	 * Check whether the step interval is valid.
	 * The comparison is done using DateTime values.
	 *
	 * @param	DateTime	the value
	 * @param	DateTime	the base value for calculations
	 * @param	integer	the step amount
	 * @return	boolean
	 */
	public static function valid_step_dt($value, $base, $step)
	{
		if (class_exists('MMI_Log'))
		{
			MMI_Log::log_info(__METHOD__, __LINE__, 'Unable to calculate step interval using DateTime objects');
		}
		return TRUE;
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
			return (Date::DAY * $step);
		}
		return NULL;
	}

	/**
	 * Get the step label and quantity.
	 *
	 * @param	integer	the step interval
	 * @return	array
	 */
	public static function format_step($step)
	{
		return array('label' => 'day', 'qty' => $step);
	}
} // End Kohana_MMI_Form_Rule_MinMaxStep_Date
