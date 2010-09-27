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
			'value' => '2010-11-11',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);

		$settings = array_merge($settings, array
		(
			'_label' => 'Date 2',
			'_namespace' => 'mmi',
			'class' => 'date',
			'id' => 'date2',
			'required' => FALSE,
			'value' => '',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_Date
