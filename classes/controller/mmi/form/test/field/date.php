<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for date field generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Date extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test date field generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_namespace' => 'mmi',

			'class' => 'date',
			'id' => 'date1',
			'maxlength' => 22,
			'readonly' => 'readonly',
			'value' => 'test',
		);

		$type = 'date';
		$field = MMI_Form_Field::factory($type, $settings);
		MMI_Debug::mdump($field->render(), $type, $field);
	}
} // End Controller_MMI_Form_Test_Field_Date
