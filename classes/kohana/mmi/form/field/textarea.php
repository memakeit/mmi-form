<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Textarea field.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Textarea extends MMI_Form_Field
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
		$options['_type'] = 'textarea';
		parent::__construct($options);
	}

	/**
	 * Get or set whether to double encode the textarea text.
	 *
	 * @param	boolean	double encode the text?
	 * @return	mixed
	 */
	public function double_encode($value = NULL)
	{
		if (func_num_args() === 0)
		{
			return $this->meta('double_encode');
		}
		return $this->meta('double_encode', $value);
	}

	/**
	 * Get or set the textarea text.
	 *
	 * @param	string	the text
	 * @return	mixed
	 */
	public function text($value = NULL)
	{
		if (func_num_args() === 0)
		{
			return $this->meta('text');
		}
		return $this->meta('text', $value);
	}

	/**
	 * Get the view parameters.
	 *
	 * @return	array
	 */
	protected function _get_view_parms()
	{
		$parms = parent::_get_view_parms();
		$meta = $this->_meta;
		$parms['double_encode'] = Arr::get($meta, 'double_encode', FALSE);
		$parms['text'] = Arr::get($meta, 'text', Arr::get($this->_attributes, 'value', ''));
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
			return MMI_HTML5_Attributes_Textarea::get();
		}
		return MMI_HTML4_Attributes_Textarea::get();
	}
} // End Kohana_MMI_Form_Field_Textarea
