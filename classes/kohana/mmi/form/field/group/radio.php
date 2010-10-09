<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Radio group.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Group_Radio extends MMI_Form_Field_Group
{
	/**
	 * @var integer the number of id's generated
	 */
	protected static $_rb_count = 0;

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
		$options['type'] = 'radio';
		parent::__construct($options);
	}

	/**
	 * Get the item id.
	 *
	 * @return	string
	 */
	protected function _get_item_id()
	{
		return MMI_Form::clean_id('rb_id_'.self::$_rb_count++);
	}
} // End Kohana_MMI_Form_Field_Group_Radio
