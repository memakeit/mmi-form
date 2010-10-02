<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date field.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Date extends MMI_Form_Field
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
		$options['type'] = 'date';
		parent::__construct($options);
	}

	/**
	 * Finalize validation rules.
	 *
	 * @return	void
	 */
	protected function _finalize_rules()
	{
		MMI_Form_Rule_MinMaxStep_DateTime::init($this, 'date');
		parent::_finalize_rules();
	}
} // End Kohana_MMI_Form_Field_Date
