<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for submit button generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Submit extends Controller_MMI_Form_Test_Field
{
	/**
	 * @var boolean automatically add a submit button?
	 **/
	protected $_auto_add_submit = FALSE;

	/**
	 * Test submit button generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'submit';

		$settings = array
		(
			'_namespace' => 'mmi',
			'class' => 'submit',
			'id' => 'submit1',
			'value' => 'submit 1',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);

		$settings = array_merge($settings, array
		(
			'id' => 'submit2',
			'value' => 'submit 2',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_Submit
