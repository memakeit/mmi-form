<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Text field.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Text extends MMI_Form_Field
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
		$options['type'] = 'text';
		parent::__construct($options);
	}
} // End Kohana_MMI_Form_Field_Text
