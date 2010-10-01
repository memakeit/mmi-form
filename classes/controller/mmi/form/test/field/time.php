<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for time input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Time extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test time input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'time';

		$settings = array
		(
			'_after' => '12:00',
			'_before' => '00:00',
			'_label' => 'Time 1',
			'_namespace' => 'mmi',
			'class' => 'time',
			'id' => 'time1',
			'max' => '12:00',
			'min' => '00:00',
			'required' => 'required',
			'step' => Date::MINUTE * 15,
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (min 00:00; max 12:00; step 15)');

		$settings = array_merge($settings, array
		(
			'_before' => '12:00',
			'_label' => 'Time 2',
			'_namespace' => 'mmi',
			'class' => 'time',
			'id' => 'time2',
			'min' => '12:00',
			'required' => FALSE,
			'step' => Date::MINUTE * 30,
			'value' => '11:45',
		));
		unset($settings['_after'], $settings['max']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (min 12:00; step 30)');

		$settings = array_merge($settings, array
		(
			'_after' => '12:00',
			'_label' => 'Time 3',
			'_namespace' => 'mmi',
			'class' => 'time',
			'id' => 'time3',
			'max' => '12:00',
			'required' => FALSE,
			'step' => Date::MINUTE * 60,
			'value' => '13:00',
		));
		unset($settings['_before'], $settings['min']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (max 12:00; step 60)');
	}
} // End Controller_MMI_Form_Test_Field_Time
