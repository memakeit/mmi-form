<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for week input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Week extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test week input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'week';

		$settings = array
		(
			'_label' => 'Week 1',
			'_namespace' => 'mmi',
			'class' => 'week',
			'id' => 'week1',
			'required' => 'required',
			'step' => 3,
			'value' => '1970-W01'
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (step 3)');

		$settings = array_merge($settings, array
		(
			'_after' => '2010-W10',
			'_before' => '2010-W01',
			'_label' => 'Week 2',
			'id' => 'week2',
			'max' => '2010-W10',
			'min' => '2010-W01',
			'required' => FALSE,
			'step' => 1,
			'value' => '',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (min 2010-W01; max 2010-W10; step 1)');

		$settings = array_merge($settings, array
		(
			'_before' => '2010-W10',
			'_label' => 'Week 3',
			'id' => 'week3',
			'min' => '2010-W10',
			'step' => 2,
		));
		unset($settings['_after'], $settings['max']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (min 2010-W10; step 2)');

		$settings = array_merge($settings, array
		(
			'_after' => '2010-W30',
			'_label' => 'Week 4',
			'id' => 'week4',
			'max' => '2010-W30',
			'step' => 4,
		));
		unset($settings['_before'], $settings['min']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (max 2010-W30; step 4)');
	}
} // End Controller_MMI_Form_Test_Field_Week
