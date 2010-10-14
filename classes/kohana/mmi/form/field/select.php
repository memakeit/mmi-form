<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Select field.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Select extends MMI_Form_Field_Selectable
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
		$options['_type'] = 'select';
		parent::__construct($options);
	}

	/**
	 * Get or set the whether to insert a blank option (value = '').
	 * If the value is FALSE, no blank option is displayed.
	 * If the value is TRUE, an empty string is used as the blank option name.
	 * If the value is a string, the string is used as the blank option name.
	 * This method is chainable when setting a value.
	 *
	 * @param	mixed	include a blank option? (or specify the blank option name)
	 * @return	mixed
	 */
	public function blank_option($value = NULL)
	{
		if (func_num_args() === 0)
		{
			return $this->meta('blank_option');
		}
		return $this->meta('blank_option', $value);
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
			$multiple = Arr::get($this->_attributes, 'multiple', FALSE);
			if ( ! empty($multiple))
			{
				$name .= '[]';
			}
			return $name;
		}
		return parent::name($value);
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
			$name = MMI_Form::clean_id($this->name());
			$original = Arr::get($this->_meta, 'original');
			$posted = $this->_apply_filters(Arr::get($_POST, $name, ''));
			$this->_meta['posted'] = $posted;
			$this->_meta['selected'] = $posted;
			$this->_meta['updated'] = ($original !== $posted);
			$this->_attributes['value'] = $posted;
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
		$attributes = $parms['attributes'];
		$meta = $this->_meta;
		$parms['options'] = $this->_options();

		$multiple = Arr::get($attributes, 'multiple', FALSE);
		if (empty($multiple))
		{
			if (isset($parms['attributes']['multiple']))
			{
				unset($parms['attributes']['multiple']);
			}
			$parms['attributes']['name'] = $this->name();
		}
		else
		{
			$parms['attributes']['multiple'] = 'multiple';
			$parms['attributes']['name'] = $this->name();
			$parms['attributes']['size'] = Arr::get($attributes, 'size', 5);
		}
		return $parms;
	}

	/**
	 * Get the options, adding a blank option if necessary.
	 *
	 * @return	void
	 */
	protected function _options()
	{
		$meta = $this->_meta;
		$blank_option = Arr::get($meta, 'blank_option', FALSE);
		$choices = Arr::get($meta, 'choices', array());
		if ($blank_option !== FALSE)
		{
			if ($blank_option === TRUE)
			{
				$blank_option = '';
			}
			if ( ! in_array($blank_option, $choices))
			{
				Arr::unshift($choices, '', $blank_option);
			}
		}
		$selected = Arr::get($meta, 'selected', '');
		return $this->_options_html($choices, $selected);
	}

	/**
	 * Get the HTML attributes allowed.
	 *
	 * @return	array
	 */
	protected function _get_allowed_attributes()
	{
		if ($this->_get_form_meta('html5', TRUE))
		{
			return MMI_HTML5_Attributes_Select::get();
		}
		return MMI_HTML4_Attributes_Select::get();
	}

	/**
	 * Finalize validation rules.
	 *
	 * @return	void
	 */
	protected function _finalize_rules()
	{
		$multiple = Arr::get($this->_attributes, 'multiple', FALSE);
		if (empty($multiple))
		{
			// Min, max, and range item rules are only valid for select multiple so remove them
			$rules = Arr::get($this->_meta, 'rules', array());
			$names = MMI_Form_Rule_MinMax_Items::get_rule_names();
			foreach ($names as $name)
			{
				if (array_key_exists($name, $rules))
				{
					unset($rules[$name]);
				}
			}
			$this->_meta['rules'] = $rules;
		}
		else
		{
			MMI_Form_Rule_MinMax_Items::init($this);
		}
		parent::_finalize_rules();
	}
} // End Kohana_MMI_Form_Field_Select
