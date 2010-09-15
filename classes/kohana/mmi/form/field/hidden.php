<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Hidden field.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Hidden extends MMI_Form_Field
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
		$options['_type'] = 'hidden';
		$options['type'] = 'hidden';
		parent::__construct($options);
	}

	/**
	 * Load the post data into the models and fields.
	 *
	 * @return  void
	 */
	protected function _load_post_data()
	{
		if ( ! $_POST)
		{
			return;
		}
		$this->_posted = TRUE;
		$this->_state |= MMI_Form::STATE_POSTED;
	}
} // End Kohana_MMI_Form_Field_Reset
