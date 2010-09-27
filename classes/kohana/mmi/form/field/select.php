<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Select field.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Select extends MMI_Form_Field
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

		$value = Arr::get($options, 'value');
		if ( ! array_key_exists('_selected', $options) AND isset($value))
		{
			$options['_selected'] = $value;
		}
		$options['_type'] = 'select';
		parent::__construct($options);
	}

	/**
	 * Get or set the whether to insert a blank option (value = '').
	 * If the value is FALSE, no blank option is displayed.
	 * If the value is TRUE, an empty string is used as the blank option name.
	 * If the value is a string, the string is used as the blank option name.
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
	 * Get or set the options.
	 *
	 * @param	array	the options
	 * @return	mixed
	 */
	public function options($value = NULL)
	{
		if (func_num_args() === 0)
		{
			return $this->meta('choices');
		}
		return $this->meta('choices', $value);
	}

	/**
	 * Add an option or option group.
	 *
	 * @param	mixed	the value (string for an option; array for an optgroup)
	 * @param	string	the name
	 * @return	MMI_Form_Field_Select
	 */
	public function add_option($value, $name)
	{
		$this->_meta['choices'][$value] = $name;
		return $this;
	}

	/**
	 * Remove an option or option group.
	 *
	 * @param	string	the value (or optgroup label)
	 * @return	MMI_Form_Field_Select
	 */
	public function remove_option($value)
	{
		if (isset($this->_meta['choices'][$value]))
		{
			unset($this->_meta['choices'][$value]);
		}
		return $this;
	}

	/**
	 * Clear the options.
	 *
	 * @return	MMI_Form_Field_Select
	 */
	public function clear_options()
	{
		return $this->meta('choices', array());
	}

	/**
	 * Get or set the selected options.
	 *
	 * @param	mixed	the selected option values (string|array)
	 * @return	mixed
	 */
	public function selected($value = NULL)
	{
		if (func_num_args() === 0)
		{
			return $this->meta('selected');
		}
		return $this->meta('selected', $value);
	}

	/**
	 * Merge the user-specified and config file settings.
	 * Separate the meta data from the HTML attributes.
	 *
	 * @param	array	an associative array of field options
	 * @return	void
	 */
	protected function _init_options($options)
	{
		// Set default and original values
		$selected = Arr::get($options, '_selected', '');
		if ( ! array_key_exists('_default', $options))
		{
			$options['_default'] = $selected;
		}
		if ( ! array_key_exists('_original', $options))
		{
			$options['_original'] = $selected;
		}
		parent::_init_options($options);
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

		$post = Security::xss_clean($_POST);
		if ( ! empty($post))
		{
			$original = Arr::get($this->_meta, 'original');
			$posted = Arr::get($post, $this->id(), '');
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
		$parms['selected'] = Arr::get($meta, 'selected', array());

		$multiple = Arr::get($attributes, 'multiple', FALSE);
		if ($multiple === FALSE)
		{
			$parms['name'] = Arr::get($attributes, 'name', '');
			if (isset($parms['attributes']['multiple']))
			{
				unset($parms['attributes']['multiple']);
			}
		}
		else
		{
			$parms['attributes']['multiple'] = 'multiple';
			$parms['attributes']['size'] = Arr::get($attributes, 'size', 5);
			$parms['name'] = Arr::get($attributes, 'name', '').'[]';
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
		$blank_option = Arr::get($this->_meta, 'blank_option', FALSE);
		$choices = Arr::get($this->_meta, 'choices', array());
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
		return $choices;
	}

	/**
	 * Get the HTML attributes allowed.
	 *
	 * @return	array
	 */
	protected function _get_allowed_attributes()
	{
		if ($this->_html5)
		{
			return MMI_HTML5_Attributes_Select::get();
		}
		return MMI_HTML4_Attributes_Select::get();
	}
} // End Kohana_MMI_Form_Field_Select
