<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for grouped input (checkbox and radio button) generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Group extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test grouped input (checkbox and radio button) generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		// Checkboxes
		$type = 'checkbox';
		$settings = array
		(
			'_choices' => array
			(
				'cb1' => 'value 1',
				'cb2' => 'value 2',
				'cb3' => 'value 3',
				'cb4' => 'value 4',
			),
			'_namespace' => 'mmi',
			'class' => 'checkbox',
			'id' => 'checkbox1',
			'name' => 'cbgroup1',
			'value' => 'value 1',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form
			->fieldset_open(array('_legend' => 'CB Group 1 (one value)'))
			->add_field($field)
			->fieldset_close()
		;
		MMI_Debug::dump($field->render(), $type.' (one value)');

		$settings = array_merge($settings, array
		(
			'id' => 'checkbox2',
			'name' => 'cbgroup2',
			'value' => array('value 2', 'value 4'),
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form
			->fieldset_open(array('_legend' => 'CB Group 2 (multiple values)'))
			->add_field($field)
			->fieldset_close()
		;
		MMI_Debug::dump($field->render(), $type.' (multiple values)');

		// Radio buttons
		$type = 'radio';
		$settings = array
		(
			'_choices' => array
			(
				'rb1' => 'value 1',
				'rb2' => 'value 2',
				'rb3' => 'value 3',
				'rb4' => 'value 4',
			),
			'_namespace' => 'mmi',
			'class' => 'radio',
			'id' => 'radio1',
			'name' => 'rbgroup1',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form
			->fieldset_open(array('_legend' => 'RB Group 1'))
			->add_field($field)
			->fieldset_close()
		;
		MMI_Debug::dump($field->render(), $type);

		$settings = array_merge($settings, array
		(
			'id' => 'radio2',
			'name' => 'rbgroup2',
			'value' => 'value 2',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form
			->fieldset_open(array('_legend' => 'RB Group 2'))
			->add_field($field)
			->fieldset_close()
		;
		MMI_Debug::dump($field->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_Group
