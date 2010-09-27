<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for reset button generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Reset extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test reset button generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'reset';

		$settings = array
		(
			'_namespace' => 'mmi',
			'class' => 'reset',
			'id' => 'reset1',
			'value' => 'reset 1',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);

		$settings = array_merge($settings, array
		(
			'id' => 'reset2',
			'value' => 'reset 2',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_Reset
