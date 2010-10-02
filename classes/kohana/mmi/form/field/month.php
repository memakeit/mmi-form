<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Month field.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Month extends MMI_Form_Field
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
		$options['type'] = 'month';
		parent::__construct($options);
	}

	/**
	 * Finalize validation rules.
	 *
	 * @return	void
	 */
	protected function _finalize_rules()
	{
		$type = Arr::get($this->_attributes, 'type', 'date');
		MMI_Form_Rule_MinMaxStep_DateTime::init($this, $type);
		parent::_finalize_rules();
	}
} // End Kohana_MMI_Form_Field_Month
