<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for output element generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Output extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test output element generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_html' => 'output!!',
			'_namespace' => 'mmi',

			'class' => 'output',
			'id' => 'output1',
		);

		$type = 'output';
		$field = MMI_Form_Field::factory($type, $settings);
		echo $field->render();
		MMI_Debug::mdump($field->render(), $type, $field);
	}
} // End Controller_MMI_Form_Test_Field_Output
