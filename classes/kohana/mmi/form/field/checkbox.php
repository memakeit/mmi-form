<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Checkbox.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Checkbox extends MMI_Form_Field
{
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
		$options['type'] = 'checkbox';
		parent::__construct($options);
	}

	/**
	 * Get the view parameters.
	 *
	 * @return	array
	 */
	protected function _get_view_parms()
	{
		$parms = parent::_get_view_parms();
		$parms['checked'] = $this->_checked();
		return $parms;
	}

	/**
	 * Determine if a checkbox is checked.
	 *
	 * @return  boolean
	 */
	protected function _checked()
	{
		$checked = FALSE;
		if ($_POST AND ($this->_state ^ MMI_Form::STATE_RESET))
		{
			$temp = Arr::get($_POST, $this->get_id());
			$checked = ( ! empty($temp));
		}
		else
		{
			$checked = Arr::get($this->_attributes, 'checked', FALSE);
		}
		return $checked;
	}
} // End Kohana_MMI_Form_Field_Checkbox