<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Submit button.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Submit extends MMI_Form_Field
{
	/**
	 * Set the field type.
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

		$options['type'] = 'submit';
		$order = Arr::get($options, '_order');
		if (empty($order))
		{
			$options['_order'] = array(MMI_Form::ORDER_FIELD);
		}
		parent::__construct($options);
	}
} // End Kohana_MMI_Form_Field_Submit
