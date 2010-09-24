<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for label generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Label extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test label generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_html' => 'First Name:',
			'_namespace' => 'mmi',

			'checked' => TRUE,
			'id' => 'lbl1',
			'maxlength' => 22,
			'readonly' => 'readonly',
			'value' => 'test',
		);

		$type = 'file';
		$label = MMI_Form_Label::factory($settings);
		MMI_Debug::mdump($label->render(), 'label', $label);
	}
} // End Controller_MMI_Form_Test_Field_Label
