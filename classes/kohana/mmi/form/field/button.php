<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Button button.
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
	 * Get the view parameters.
	 *
	 * @return	array
	 */
	protected function _get_view_parms()
	{
		$parms = parent::_get_view_parms();
		$parms['text'] = Arr::get($this->_meta, 'text', Arr::get($this->_attributes, 'value', ''));
		return $parms;
	}
} // End Kohana_MMI_Form_Field_Button
