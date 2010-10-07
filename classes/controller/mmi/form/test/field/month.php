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
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

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
			'_label' => 'Month 1',
			'_namespace' => 'mmi',
			'class' => 'month',
			'id' => 'month1',
			'required' => 'required',
			'step' => 3,
			'value' => '1970-01',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (step 3)');
		}

		$settings = array_merge($settings, array
		(
			'_after' => '2010-10',
			'_before' => '2010-01',
			'_label' => 'Month 2',
			'id' => 'month2',
			'max' => '2010-10',
			'min' => '2010-01',
			'required' => FALSE,
			'step' => 1,
			'value' => '',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (min 2010-01; max 2010-10; step 1)');
		}

		$settings = array_merge($settings, array
		(
			'_before' => '2010-10',
			'_label' => 'Month 3',
			'id' => 'month3',
			'min' => '2010-10',
			'step' => 2,
		));
		unset($settings['_after'], $settings['max']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (min 2010-10; step 2)');
		}

		$settings = array_merge($settings, array
		(
			'_after' => '2010-03',
			'_label' => 'Month 4',
			'id' => 'month4',
			'max' => '2010-03',
			'step' => 4,
		));
		unset($settings['_before'], $settings['min']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (max 2010-03; step 4)');
		}
	}
} // End Controller_MMI_Form_Test_Field_Month
