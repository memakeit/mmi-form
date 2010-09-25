<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for meter element generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Meter extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test meter element generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_html' => 'meter!!',
			'_namespace' => 'mmi',

			'class' => 'meter',
			'id' => 'meter1',
			'high' => 75,
			'low' => 25,
			'min' => 0,
			'max' => 100,
			'optimum' => 50,
			'value' => 76,
			'title' => '76%',
		);

		$type = 'meter';
		$field = MMI_Form_Field::factory($type, $settings);
		echo $field->render();
		MMI_Debug::mdump($field->render(), $type, $field);
	}
} // End Controller_MMI_Form_Test_Field_Meter
