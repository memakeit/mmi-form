<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Button.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Button extends MMI_Form_Field
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
		if ( ! array_key_exists('_html', $options) AND isset($value))
		{
			$options['_html'] = $value;
		}
		$options['_type'] = 'button';
		if (empty($options['type']))
		{
			$options['type'] = 'button';
		}
		parent::__construct($options);
	}

	/**
	 * Get or set the button HTML.
	 *
	 * @param	string	the HTML
	 * @return	mixed
	 */
	public function html($value = NULL)
	{
		if (func_num_args() === 0)
		{
			return $this->meta('html');
		}
		return $this->meta('html', $value);
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
		$parms['html'] = Arr::get($this->_meta, 'html', '');
		return $parms;
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
			return MMI_HTML5_Attributes_Button::get();
		}
		return MMI_HTML4_Attributes_Button::get();
	}
} // End Kohana_MMI_Form_Field_Button
