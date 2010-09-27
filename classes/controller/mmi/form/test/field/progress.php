<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for progress bar generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Progress extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test progress bar generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'progress';

		$settings = array
		(
			'_html' => 'progress 1!',
			'_label' => 'Progress 1',
			'_namespace' => 'mmi',
			'class' => 'progress',
			'id' => 'progress1',
			'max' => 100,
			'value' => 76,
			'title' => '76%',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);

		$settings = array_merge($settings, array
		(
			'_html' => 'progress 2!',
			'_label' => 'Progress 2',
			'id' => 'progress2',
			'max' => 1000,
			'value' => 76,
			'title' => '7.6%',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_Progress
