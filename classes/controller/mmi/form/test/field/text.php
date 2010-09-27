<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for text input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Text extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test text input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'text';

		$settings = array
		(
			'_label' => 'Text 1',
			'_namespace' => 'mmi',
			'class' => 'text',
			'id' => 'text1',
			'maxlength' => 10,
			'required' => 'required',
			'value' => 'text 1',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);

		$settings = array_merge($settings, array
		(
			'_label' => 'Text 2',
			'id' => 'text2',
			'maxlength' => 20,
			'required' => FALSE,
			'value' => 'text 2',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_Text
