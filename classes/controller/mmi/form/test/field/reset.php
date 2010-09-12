<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for reset field generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Reset extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test reset field generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_namespace' => 'mmi',

			'checked' => TRUE,
			'class' => 'reset',
			'id' => 'reset1',
			'maxlength' => 22,
			'readonly' => 'readonly',
			'value' => 'test',
		);

		$type = 'reset';
		$field = MMI_Form_Field::factory($type, $settings);
		MMI_Debug::dump($field->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_Reset
