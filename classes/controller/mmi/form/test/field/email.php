<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for email input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Email extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test email input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'email';

		$settings = array
		(
			'_label' => 'Email 1',
			'_namespace' => 'mmi',
			'class' => 'email',
			'id' => 'email1',
			'multiple' => 'multiple',
			'pattern' => '[a-z]+@[a-z]+\.com',
			'required' => 'required',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (w/multiple and pattern)');

		$settings = array_merge($settings, array
		(
			'_label' => 'Email 2',
			'_namespace' => 'mmi',
			'class' => 'email',
			'id' => 'email2',
			'required' => FALSE,
		));
		unset($settings['multiple'], $settings['pattern']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (w/out multiple and w/out pattern)');
	}
} // End Controller_MMI_Form_Test_Field_Email
