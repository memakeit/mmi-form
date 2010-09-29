<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Radio button.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Radio extends MMI_Form_Field_Checkable
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

		if (empty($options['_order']))
		{
			$options['_order'] = array(MMI_Form::ORDER_FIELD, MMI_Form::ORDER_LABEL, MMI_Form::ORDER_ERROR);
		}
		$options['_type'] = 'input';
		$options['type'] = 'radio';
		parent::__construct($options);
	}
} // End Kohana_MMI_Form_Field_Radio
