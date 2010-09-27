<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for password input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Password extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test password input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'password';

		$settings = array
		(
			'_label' => 'Password 1',
			'_namespace' => 'mmi',
			'class' => 'password',
			'id' => 'password1',
			'maxlength' => 5,
			'required' => 'required',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);

		$settings = array_merge($settings, array
		(
			'_label' => 'Password 2',
			'id' => 'password2',
			'maxlength' => 10,
			'required' => FALSE,
			'value' => 'abcde',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_Password
