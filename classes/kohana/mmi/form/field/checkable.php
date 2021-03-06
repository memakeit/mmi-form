<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Checkable input field (checkboxes and radio buttons).
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
abstract class Kohana_MMI_Form_Field_Checkable extends MMI_Form_Field
{
	/**
	 * Reset the form field.
	 *
	 * @return	void
	 */
	public function reset()
	{
		$this->_errors = array();
		$this->_state = MMI_Form::STATE_INITIAL | MMI_Form::STATE_RESET;
	}

	/**
	 * Load the post data.
	 *
	 * @return	void
	 */
	protected function _load_post_data()
	{
		if ( ! $this->_posted)
		{
			return;
		}

		if ( ! empty($_POST))
		{
			$meta = $this->_meta;
			$original = Arr::get($meta, 'original');
			$posted = $this->_apply_filters(Arr::get($_POST, $this->name(), ''));
			$this->_meta['posted'] = $posted;
			$this->_meta['updated'] = ($original !== $posted);

			$is_group = Arr::get($meta, 'is_group', FALSE);
			if ($is_group)
			{
				$this->_attributes['value'] = $original;
			}
			else
			{
				$this->_attributes['value'] = $posted;
			}
		}
		$this->_post_data_loaded = TRUE;
		$this->_state |= MMI_Form::STATE_POSTED;
	}

	/**
	 * Get the view parameters.
	 *
	 * @return	array
	 */
	protected function _get_view_parms()
	{
		$parms = parent::_get_view_parms();
		$is_group = Arr::get($this->_meta, 'is_group', FALSE);
		$value = trim(strval(Arr::get($parms['attributes'], 'value', '')));
		if ( ! $is_group AND $value === '')
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
	 * Determine if an input is checked.
	 *
	 * @return	boolean
	 */
	protected function _checked()
	{
		$checked = FALSE;
		$is_group = Arr::get($this->_meta, 'is_group', FALSE);
		if ( ! $is_group AND $this->_post_data_loaded AND ! ($this->_state & MMI_Form::STATE_RESET))
		{
			$value = trim(strval(Arr::get($_POST, $this->id(), '')));
			$checked = ($value !== '');
		}
		else
		{
			$checked = Arr::get($this->_attributes, 'checked', FALSE);
		}
		return $checked;
	}
} // End Kohana_MMI_Form_Field_Checkable
