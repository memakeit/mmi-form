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
		$options['_type'] = 'input';
		$options['type'] = 'checkbox';
		if (empty($options['_order']))
		{
			$options['_order'] = array(MMI_Form::ORDER_FIELD, MMI_Form::ORDER_LABEL, MMI_Form::ORDER_ERROR);
		}
		parent::__construct($options);
	}

	/**
	 * Reset the form field.
	 *
	 * @return	void
	 */
	public function reset()
	{
		$this->_state |= MMI_Form::STATE_RESET;
	}

	/**
	 * Get the view parameters.
	 *
	 * @return	array
	 */
	protected function _get_view_parms()
	{
		$parms = parent::_get_view_parms();
		$value = Arr::get($parms['attributes'], 'value');
		if (empty($value) AND ! $this instanceof MMI_Form_Field_Group)
		{
			$parms['attributes']['value'] = 1;
		}
		if ($this->_checked())
		{
			$parms['attributes']['checked'] = 'checked';
		}
		elseif (isset($parms['attributes']['checked']))
		{
			unset($parms['attributes']['checked']);
		}
		return $parms;
	}

	/**
	 * Determine if a checkbox is checked.
	 *
	 * @return	boolean
	 */
	protected function _checked()
	{
		$checked = FALSE;
		if ($this->_post_data_loaded)
		{
			$temp = Arr::get($_POST, $this->id());
			$checked = ( ! empty($temp));
		}
		else
		{
			$checked = Arr::get($this->_attributes, 'checked', FALSE);
		}
		return $checked;
	}
} // End Kohana_MMI_Form_Field_Checkbox
