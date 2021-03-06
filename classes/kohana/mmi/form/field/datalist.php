<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Data list.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_DataList extends MMI_Form_Field_Selectable implements MMI_Form_Field_NonValidating, MMI_Form_Field_NonPosting
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
		$options['_type'] = 'datalist';
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
		$attributes = $parms['attributes'];
		$meta = $this->_meta;
		$parms['options'] = $this->_options();
		return $parms;
	}

	/**
	 * Get the options.
	 *
	 * @return	void
	 */
	protected function _options()
	{
		$meta = $this->_meta;
		$choices = Arr::get($meta, 'choices', array());
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
			return MMI_HTML5_Attributes_DataList::get();
		}
		return MMI_HTML4_Attributes::get();
	}
} // End Kohana_MMI_Form_Field_DataList
