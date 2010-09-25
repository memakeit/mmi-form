<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for grouped checkbox and radio button generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Group extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test grouped checkbox and radio button generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		// Checkboxes
		$settings = array
		(
			'_choices' => array
			(
				'cb1' => 'value 1',
				'cb2' => 'value 2',
				'cb3' => 'value 3',
				'cb4' => 'value 4',
				'cb5' => 'value 5',
			),
			'_namespace' => 'mmi',

			'class' => 'checkbox',
			'id' => 'checkbox1',
			'name' => 'cbgroup1',
			'value' => 'value 1',
		);

		$type = 'checkbox';
		$field = MMI_Form_Field::factory($type, $settings);
		MMI_Debug::dump($field->render(), $type.' one value');

		$field
			->attribute('name', 'cbgroup2')
			->attribute('value', array('value 2', 'value 4'))
		;
		MMI_Debug::mdump($field->render(), $type.' multiple values', $field);

		// Radio buttons
		$settings = array
		(
			'_choices' => array
			(
				'rb1' => 'value 1',
				'rb2' => 'value 2',
				'rb3' => 'value 3',
				'rb4' => 'value 4',
				'rb5' => 'value 5',
			),

			'class' => 'radio',
			'id' => 'radio1',
			'name' => 'rbgroup1',
			'value' => 'value 1',
		);
		$type = 'radio';
		$field = MMI_Form_Field::factory($type, $settings);
		MMI_Debug::dump($field->render(), $type.' one value');

		$field
			->attribute('name', 'rbgroup2')
			->attribute('value', array('value 2', 'value 4'))
		;
		MMI_Debug::mdump($field->render(), $type.' multiple values', $field);
	}
} // End Controller_MMI_Form_Test_Field_Group
