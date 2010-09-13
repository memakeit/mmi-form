<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for number field generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Number extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test number field generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_namespace' => 'mmi',

			'class' => 'number',
			'id' => 'number1',
			'max' => 100,
			'maxlength' => 22,
			'min' => 0,
			'readonly' => 'readonly',
			'step' => 10,
			'value' => 'test',
		);

		$type = 'number';
		$field = MMI_Form_Field::factory($type, $settings);
		MMI_Debug::mdump($field->render(), $type, $field);
	}
} // End Controller_MMI_Form_Test_Field_Number
