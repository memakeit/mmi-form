<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Output element.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Output extends MMI_Form_Field implements MMI_Form_Field_NonValidating, MMI_Form_Field_NonPosting
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
		$options['_type'] = 'output';
		parent::__construct($options);
	}

	/**
	 * Get the view parameters.
	 *
	 * @return	array
	 */
	protected function _get_view_parms()
	{
		$attributes = $this->_get_view_attributes();
		$id = trim(strval(Arr::get($attributes, 'id', '')));
		$name = trim(strval(Arr::get($attributes, 'name', '')));
		if ($name === '' AND $id !== '')
		{
			$attributes['name'] = $id;
		}

		$meta = $this->_meta;
		return array
		(
			'after'			=> Arr::get($meta, 'after', ''),
			'attributes'	=> $attributes,
			'before'		=> Arr::get($meta, 'before', ''),
			'html'			=> Arr::get($meta, 'html', ''),
		);
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
			return MMI_HTML5_Attributes_Output::get();
		}
		return MMI_HTML4_Attributes::get();
	}
} // End Kohana_MMI_Form_Field_Output
