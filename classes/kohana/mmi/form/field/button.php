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

		$options['type'] = 'button';
		$order = Arr::get($options, '_order');
		if (empty($order))
		{
			$options['_order'] = array(MMI_Form::ORDER_FIELD);
		}
		parent::__construct($options);
	}

	/**
	 * Set the HTML to be displayed between the button tags.
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
	 * Get the view parameters.
	 *
	 * @return	array
	 */
	protected function _get_view_parms()
	{
		$parms = parent::_get_view_parms();
		$parms['html'] = Arr::get($this->_meta, 'html', Arr::get($this->_attributes, 'value', ''));
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
		return MMI_HTML5_Attributes_Button::get();
	}
} // End Kohana_MMI_Form_Field_Button
