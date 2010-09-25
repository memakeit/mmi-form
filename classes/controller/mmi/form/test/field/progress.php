<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for progress element generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Progress extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test progress element generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_html' => 'progress!!',
			'_namespace' => 'mmi',

			'class' => 'progress',
			'id' => 'progress1',
			'max' => 100,
			'value' => 76,
			'title' => '76%',
		);

		$type = 'progress';
		$field = MMI_Form_Field::factory($type, $settings);
		echo $field->render();
		MMI_Debug::mdump($field->render(), $type, $field);
	}
} // End Controller_MMI_Form_Test_Field_Progress
