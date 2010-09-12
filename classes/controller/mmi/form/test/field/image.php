<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for image field generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Image extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test image field generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_namespace' => 'mmi',

			'checked' => TRUE,
			'class' => 'image',
			'id' => 'image1',
			'maxlength' => 22,
			'readonly' => 'readonly',
			'src' => 'media/img/favicon.ico',
			'value' => 'test',
		);

		$type = 'image';
		$field = MMI_Form_Field::factory($type, $settings);
		MMI_Debug::dump($field->render(), $type, '(relative src specified)');

		$field->attribute('src', 'http:://www.yahoo.com/favicon.ico');
		MMI_Debug::dump($field->render(), $type, '(absolute src specified)');
	}
} // End Controller_MMI_Form_Test_Field_Image
