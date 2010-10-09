<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Checkbox group.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Group_Checkbox extends MMI_Form_Field_Group
{
	/**
	 * @var integer the number of id's generated
	 */
	protected static $_cb_count = 0;

	/**
	 * Set default options.
	 *
	 * @param	array	an associative array of field options
	 * @return	void
	 */
	public function __construct($options = array())
	{
		if ( ! is_array($options))
		{
			$options = array();
		}
		$options['_type'] = 'input';
		$options['type'] = 'checkbox';
		parent::__construct($options);
	}

	/**
	 * Get or set the field name.
	 * This method is chainable when setting a value.
	 *
	 * @param	string	the field name
	 * @return	mixed
	 */
	public function name($value = NULL)
	{
		if (func_num_args() === 0)
		{
			$name = parent::name();
			if ($name !== '')
			{
				$name .= '[]';
			}
			return $name;
		}
		return parent::name($value);
	}

	/**
	 * Get the item id.
	 *
	 * @return	string
	 */
	protected function _get_item_id()
	{
		return MMI_Form::clean_id('cb_id_'.self::$_cb_count++);
	}
} // End Kohana_MMI_Form_Field_Group_Checkbox
