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
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

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
			'_group' => array('_label' => 'CB Group 1'),
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'min_items' => array('min' => 2)
			),
			'class' => 'checkbox',
			'id' => 'checkbox1',
			'name' => 'cbgroup1',
			'required' => TRUE,
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (one value)');
		}

		$settings = array_merge($settings, array
		(
			'_group' => array('_label' => 'CB Group 2'),
			'_rules' => array
			(
				'range_items' => array('min' => 2, 'max' => 3),
			),
			'id' => 'checkbox2',
			'name' => 'cbgroup2',
			'required' => FALSE,
			'value' => array('value 2', 'value 4'),
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (multiple values)');
		}

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
			'_group' => array('_label' => 'RB Group 1'),
			'_namespace' => 'mmi',
			'class' => 'radio',
			'id' => 'radio1',
			'name' => 'rbgroup1',
			'required' => TRUE,
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}

		$settings = array_merge($settings, array
		(
			'_group' => array('_label' => 'RB Group 2'),
			'id' => 'radio2',
			'name' => 'rbgroup2',
			'required' => FALSE,
			'value' => 'value 2',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}
	}
} // End Controller_MMI_Form_Test_Field_Group
