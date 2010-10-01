<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for month input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Month extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test month input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'month';

		$settings = array
		(
			'_after' => '2010-10',
			'_before' => '2010-01',
			'_label' => 'Month 1',
			'_namespace' => 'mmi',
			'class' => 'month',
			'id' => 'month1',
			'max' => '2010-10',
			'min' => '2010-01',
			'required' => 'required',
			'step' => 1,
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (min 2010-01; max 2010-10; step 1)');

		$settings = array_merge($settings, array
		(
			'_before' => '2010-10',
			'_label' => 'Month 2',
			'_namespace' => 'mmi',
			'class' => 'month',
			'id' => 'month2',
			'min' => '2010-10',
			'required' => FALSE,
			'step' => 2,
			'value' => '2010-01',
		));
		unset($settings['_after'], $settings['max']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (min 2010-10; step 2)');

		$settings = array_merge($settings, array
		(
			'_after' => '2010-03',
			'_label' => 'Month 3',
			'_namespace' => 'mmi',
			'class' => 'month',
			'id' => 'month3',
			'max' => '2010-03',
			'required' => FALSE,
			'step' => 3,
			'value' => '2010-06',
		));
		unset($settings['_before'], $settings['min']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (max 2010-03; step 3)');
	}
} // End Controller_MMI_Form_Test_Field_Month
