<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for select field generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Select extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test select field generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'select';

		$settings = array
		(
			'_label' => 'Select 1',
			'_namespace' => 'mmi',
			'_selected' => 'value2',
			'class' => 'select',
			'id' => 'select1',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$field
			->add_option('value1', 'name1')
			->add_option('value2', 'name2')
			->add_option('value3', array('value3A' => 'name3A', 'value3B' => 'name3B'))
			->add_option('value4', array('value4A' => 'name4A', 'value4B' => 'name4B'))
			->remove_option('value3')
			->blank_option('blank!')
		;
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (multiple FALSE)');

		$settings = array_merge($settings, array
		(
			'_label' => 'Select 2',
			'id' => 'select2',
			'multiple' => 'multiple',
			'size' => 5,
		));
		$field = MMI_Form_Field::factory($type, $settings)->options(array
		(
			'value100' => 'name100',
			'value200' => 'name200',
			'value300' => array('value301' => 'name301', 'value302' => 'name302'),
		));
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (multiple TRUE)');
	}
} // End Controller_MMI_Form_Test_Field_Select
