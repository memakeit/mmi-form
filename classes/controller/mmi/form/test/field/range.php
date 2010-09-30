<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for range input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Range extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test range input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'range';

		$settings = array
		(
			'_after' => 100,
			'_before' => 0,
			'_label' => 'Range 1',
			'_namespace' => 'mmi',
			'class' => 'range',
			'id' => 'range1',
			'max' => 100,
			'min' => 0,
			'required' => 'required',
			'step' => 10,
			'value' => 20,
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (min 0; max 100; step 10)');

		$settings = array_merge($settings, array
		(
			'_before' => 0,
			'_label' => 'Range 2',
			'id' => 'range2',
			'min' => 0,
			'required' => FALSE,
			'step' => 2,
			'value' => 6,
		));
		unset($settings['_after'], $settings['max']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type. ' (min 0; step 2)');

		$settings = array_merge($settings, array
		(
			'_after' => 100,
			'_label' => 'Range 3',
			'id' => 'range3',
			'max' => 100,
			'required' => FALSE,
			'step' => 25,
			'value' => 6,
		));
		unset($settings['_before'], $settings['min']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type. ' (max 100; step 25)');
	}
} // End Controller_MMI_Form_Test_Field_Range
