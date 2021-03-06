<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Submit button.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Submit extends MMI_Form_Field implements MMI_Form_Field_NonValidating, MMI_Form_Field_NonPosting
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
		$options['_type'] = 'input';
		$options['type'] = 'submit';
		parent::__construct($options);
	}
} // End Kohana_MMI_Form_Field_Submit
