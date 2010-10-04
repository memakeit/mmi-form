<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for datetime-local input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_DateTimeLocal extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test datetime-local input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'datetime-local';

		$settings = array
		(
			'_label' => 'DateTime-Local 1',
			'_namespace' => 'mmi',
			'class' => 'datetime-local',
			'id' => 'datetime-local1',
			'required' => 'required',
			'step' => DATE::MINUTE * 30,
			'value' => '1970-01-01T00:00:00',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (step 30 minutes)');

		$settings = array_merge($settings, array
		(
			'_after' => '2011-01-01T00:00:00',
			'_before' => '2010-09-01T00:00:00',
			'_label' => 'DateTime-Local 2',
			'id' => 'datetime-local2',
			'max' => '2011-01-01T00:00:00',
			'min' => '2010-09-01T00:00:00',
			'required' => FALSE,
			'step' => Date::HOUR * 1.5,
			'value' => '',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (min 2010-09-01T00:00:00; max 2011-01-01T00:00:00; step 1.5 hours)');

		$settings = array_merge($settings, array
		(
			'_before' => '2010-06-01T00:00:00',
			'_label' => 'DateTime-Local 3',
			'id' => 'datetimelocal-3',
			'min' => '2010-06-01T00:00:00',
			'step' => DATE::MINUTE * 15,
		));
		unset($settings['_after'], $settings['max']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (min 2010-06-01T00:00:00; step 15 minutes)');

		$settings = array_merge($settings, array
		(
			'_after' => '2011-01-01T00:00:00',
			'_label' => 'DateTime-Local 4',
			'id' => 'datetime-local4',
			'max' => '2011-01-01T00:00:00',
			'step' => Date::DAY * 1,
		));
		unset($settings['_before'], $settings['min']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (max 2011-01-01T00:00:00; step 1 day)');
	}
} // End Controller_MMI_Form_Test_Field_DateTimeLocal
