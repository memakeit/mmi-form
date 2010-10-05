<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Selectable input field (datalist and select).
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
abstract class Kohana_MMI_Form_Field_Selectable extends MMI_Form_Field
{
	// Abstract methods
	abstract protected function _options();

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

		$value = strval(Arr::get($options, 'value', ''));
		if ( ! array_key_exists('_selected', $options) AND ! empty($value))
		{
			$options['_selected'] = $value;
		}
		elseif (array_key_exists('_selected', $options))
		{
			$selected = Arr::get($options, '_selected');
			if ( ! array_key_exists('_default', $options))
			{
				$options['_default'] = $selected;
			}
			if ( ! array_key_exists('_original', $options))
			{
				$options['_original'] = $selected;
			}
		}
		parent::__construct($options);
	}

	/**
	 * Get or set the options.
	 * This method is chainable when setting a value.
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
	 * This method is chainable.
	 *
	 * @param	mixed	the value (string for an option; array for an optgroup)
	 * @param	string	the name
	 * @return	MMI_Form_Field_Selectable
	 */
	public function add_option($value, $name)
	{
		$this->_meta['choices'][$value] = $name;
		return $this;
	}

	/**
	 * Remove an option or option group.
	 * This method is chainable.
	 *
	 * @param	string	the value (or optgroup label)
	 * @return	MMI_Form_Field_Selectable
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
	 * This method is chainable.
	 *
	 * @return	MMI_Form_Field_Selectable
	 */
	public function clear_options()
	{
		return $this->meta('choices', array());
	}

	/**
	 * Get or set the selected options.
	 * This method is chainable when setting a value.
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
	 * Reset the form field.
	 *
	 * @return	void
	 */
	public function reset()
	{
		parent::reset();
		$this->meta('selected', Arr::get($this->_meta, 'default', ''));
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
	 * Generate the options HTML.
	 *
	 * @param	array	available options
	 * @param	mixed	selected option, or an array of selected options
	 * @return	string
	 */
	protected function _options_html(array $options = NULL, $selected = NULL)
	{
		if ( ! is_array($selected))
		{
			if ($selected === NULL)
			{
				// Use an empty array
				$selected = array();
			}
			else
			{
				// Convert the selected options to an array
				$selected = array((string) $selected);
			}
		}

		if (empty($options))
		{
			// There are no options
			$options = '';
		}
		else
		{
			foreach ($options as $value => $name)
			{
				if (is_array($name))
				{
					// Create a new optgroup
					$group = array('label' => $value);

					// Create a new list of options
					$_options = array();

					foreach ($name as $_value => $_name)
					{
						// Force value to be string
						$_value = (string) $_value;

						// Create a new attribute set for this option
						$option = array('value' => $_value);

						if (in_array($_value, $selected))
						{
							// This option is selected
							$option['selected'] = 'selected';
						}

						// Change the option to the HTML string
						$_options[] = '<option'.HTML::attributes($option).'>'.HTML::chars($_name, FALSE).'</option>';
					}

					// Compile the options into a string
					$_options = PHP_EOL.implode(PHP_EOL, $_options).PHP_EOL;

					$options[$value] = '<optgroup'.HTML::attributes($group).'>'.$_options.'</optgroup>';
				}
				else
				{
					// Force value to be string
					$value = (string) $value;

					// Create a new attribute set for this option
					$option = array('value' => $value);

					if (in_array($value, $selected))
					{
						// This option is selected
						$option['selected'] = 'selected';
					}

					// Change the option to the HTML string
					$options[$value] = '<option'.HTML::attributes($option).'>'.HTML::chars($name, FALSE).'</option>';
				}
			}

			// Compile the options into a single string
			return PHP_EOL.implode(PHP_EOL, $options).PHP_EOL;
		}
	}
} // End Kohana_MMI_Form_Field_Selectable
