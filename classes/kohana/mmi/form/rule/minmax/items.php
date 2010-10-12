<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Validation rules to enforce min, max, and step attributes of numeric input types.
 *
 * @package		MMI Form
 * @category	rule
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Rule_MinMax_Items
{
	/**
	 * Create the min, max, and range rules.
	 *
	 * @param	MMI_Form_Field	a form field object
	 * @return	void
	 */
	public static function init(MMI_Form_Field $field)
	{
		$meta = $field->meta();
		$rules = Arr::get($meta, 'rules', array());

		$min = NULL;
		$max = NULL;
		$names = self::get_rule_names();
		foreach ($names as $name)
		{
			$rule = Arr::get($rules, $name);
			if (is_array($rule))
			{
				$min = Arr::get($rule, 'min');
				$max = Arr::get($rule, 'max');
				unset($rules[$name]);
			}
		}
		$field->meta('rules', $rules);

		$callbacks = Arr::get($meta, 'callbacks', array());
		if ( ! is_array($callbacks))
		{
			$callbacks = array();
		}

		// Process the max and min values
		if (is_numeric($min) AND is_numeric($max))
		{
			$callbacks['range_items'] = array
			(
				array('MMI_Form_Rule_MinMax_Items', 'range'), array('min' => $min, 'max' => $max)
			);
			$field->meta('custom_rules', array('range_items' => array($min, $max)));
		}
		elseif (is_numeric($min))
		{
			$callbacks['min_items'] = array
			(
				array('MMI_Form_Rule_MinMax_Items', 'min'), array('min' => $min)
			);
			$field->meta('custom_rules', array('min_items' => array($min)));
		}
		elseif (is_numeric($max))
		{
			$callbacks['max_items'] = array
			(
				array('MMI_Form_Rule_MinMax_Items', 'max'), array('max' => $max)
			);
			$field->meta('custom_rules', array('max_items' => array($max)));
		}
		$field->meta('callbacks', $callbacks);
	}

	/**
	 * Get the min, max, and range rule names.
	 *
	 * @return	array
	 */
	public static function get_rule_names()
	{
		return array('range_items', 'min_items', 'max_items');
	}

	/**
	 * Test if the number of selected items is within a range.
	 *
	 * @param 	Validate	the validation object
	 * @param	string		the field name
	 * @param	array		the validation parameters
	 * @return	boolean
	 */
	public static function range(Validate $validate, $field, $parms = array())
	{
		$min = Arr::get($parms, 'min');
		$max = Arr::get($parms, 'max');
		$value = Arr::get($_POST, $field);
		$num_items = is_array($value) ? count($value) : NULL;
		if (is_numeric($min) AND is_numeric($max) AND is_numeric($num_items) AND $num_items >= $min AND $num_items <= $max)
		{
			return TRUE;
		}

		if ($value !== '')
		{
			$validate->error($field, 'cust_range_items', array($min, $max));
		}
		return FALSE;
	}

	/**
	 * Test if the number of selected items is less than or equal to a maximum value.
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
		$num_items = is_array($value) ? count($value) : NULL;
		if (is_numeric($max) AND is_numeric($num_items) AND $num_items <= $max)
		{
			return TRUE;
		}

		if ($value !== '')
		{
			$validate->error($field, 'cust_max_items', array($max));
		}
		return FALSE;
	}

	/**
	 * Test if the number of selected items are greater than or equal to a minimum value.
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
		$num_items = is_array($value) ? count($value) : NULL;
		if (is_numeric($min) AND is_numeric($num_items) AND $num_items >= $min)
		{
			return TRUE;
		}

		if ($value !== '')
		{
			$validate->error($field, 'cust_min_items', array($min));
		}
		return FALSE;
	}
} // End Kohana_MMI_Form_Rule_MinMax_Items
