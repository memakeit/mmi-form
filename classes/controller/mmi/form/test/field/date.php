<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for date input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Date extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test date input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'date';

		$settings = array
		(
			'_label' => 'Date 1',
			'_namespace' => 'mmi',
			'class' => 'date',
			'id' => 'date1',
			'required' => 'required',
			'step' => 3,
			'value' => '1970-01-01',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (step 3)');

		$settings = array_merge($settings, array
		(
			'_after' => '2010-12-31',
			'_before' => '2010-09-01',
			'_label' => 'Date 2',
			'id' => 'date2',
			'max' => '2010-12-31',
			'min' => '2010-09-01',
			'required' => FALSE,
			'step' => 1,
			'value' => '',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (min 2010-09-01; max 2010-12-31; step 1)');

		$settings = array_merge($settings, array
		(
			'_before' => '2010-06-01',
			'_label' => 'Date 3',
			'id' => 'date3',
			'min' => '2010-06-01',
			'step' => 2,
		));
		unset($settings['_after'], $settings['max']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (min 2010-06-01; step 2)');

		$settings = array_merge($settings, array
		(
			'_after' => '2010-12-31',
			'_label' => 'Date 4',
			'id' => 'date4',
			'max' => '2010-12-31',
			'step' => 4,
		));
		unset($settings['_before'], $settings['min']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (max 2010-12-31; step 4)');
	}
} // End Controller_MMI_Form_Test_Field_Date
