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
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

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
			'_label' => 'Time 1',
			'_namespace' => 'mmi',
			'class' => 'time',
			'id' => 'time1',
			'max' => '12:00',
			'min' => '00:00',
			'required' => 'required',
			'step' => 10.5,
			'value' => '00:00',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (step 10.5 seconds)');
		}

		$settings = array_merge($settings, array
		(
			'_after' => '12:00',
			'_before' => '00:00',
			'_label' => 'Time 2',
			'id' => 'time2',
			'max' => '12:00',
			'min' => '00:00',
			'required' => FALSE,
			'step' => Date::MINUTE * 15,
			'value' => '',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (min 00:00; max 12:00; step 15 minutes)');
		}

		$settings = array_merge($settings, array
		(
			'_before' => '13:00',
			'_label' => 'Time 3',
			'id' => 'time3',
			'min' => '13:00',
			'step' => Date::MINUTE * 30,
		));
		unset($settings['_after'], $settings['max']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (min 13:00; step 30 minutes)');
		}

		$settings = array_merge($settings, array
		(
			'_after' => '08:00',
			'_label' => 'Time 4',
			'id' => 'time4',
			'max' => '08:00',
			'step' => Date::HOUR * 1.5,
		));
		unset($settings['_before'], $settings['min']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (max 08:00; step 1.5 hours)');
		}
	}
} // End Controller_MMI_Form_Test_Field_Time
