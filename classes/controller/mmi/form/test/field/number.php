<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for number input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Number extends Controller_MMI_Form_Test_Field
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * Test number input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'number';

		$settings = array
		(
			'_label' => 'Number 1',
			'_namespace' => 'mmi',
			'class' => 'number',
			'id' => 'number1',
			'required' => 'required',
			'step' => 10,
			'value' => 20,
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (step 10)');
		}

		$settings = array_merge($settings, array
		(
			'_after' => 100,
			'_before' => 0,
			'_label' => 'Number 2',
			'id' => 'number2',
			'max' => 100,
			'min' => 0,
			'required' => FALSE,
			'step' => 2,
			'value' => '',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (min 0; max 100; step 2)');
		}

		$settings = array_merge($settings, array
		(
			'_before' => 1000,
			'_label' => 'Number 3',
			'id' => 'number3',
			'min' => 1000,
			'step' => 100,
		));
		unset($settings['_after'], $settings['max']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (min 1000; step 100)');
		}

		$settings = array_merge($settings, array
		(
			'_after' => 0,
			'_label' => 'Number 4',
			'id' => 'number4',
			'max' => 0,
			'required' => FALSE,
			'step' => 25,
		));
		unset($settings['_before'], $settings['min']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (max 0; step 25)');
		}
	}
} // End Controller_MMI_Form_Test_Field_Number
