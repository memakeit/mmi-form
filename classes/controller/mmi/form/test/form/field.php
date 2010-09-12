<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for form field generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Form_Field extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test form field generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_html' => '<b>sprehe</b>',
			'_namespace' => 'mmi',
			'_text' => 'sprehe',

			'checked' => TRUE,
			'class' => 'mmi',
			'id' => 'test',
			'maxlength' => 22,
			'readonly' => 'readonly',
			'value' => 'shawn',
		);

		$type = 'button';
		$field = MMI_Form_Field::factory($type, $settings);
		$field->html('<i>buTTon !!!</i>');
		$data = $field->render();
		MMI_Debug::mdump($data, $type);

		$type = 'checkbox';
		$field = MMI_Form_Field::factory($type, $settings);
		$data = $field->render();
		MMI_Debug::mdump($data, $type);

		$type = 'file';
		$field = MMI_Form_Field::factory($type, $settings);
		$data = $field->render();
		MMI_Debug::mdump($data, $type);

		$type = 'hidden';
		$field = MMI_Form_Field::factory($type, $settings);
		$data = $field->render();
		MMI_Debug::mdump($data, $type);

		$type = 'password';
		$field = MMI_Form_Field::factory($type, $settings);
		$data = $field->render();
		MMI_Debug::mdump($data, $type);

		$type = 'radio';
		$field = MMI_Form_Field::factory($type, $settings);
		$data = $field->render();
		MMI_Debug::mdump($data, $type);

		$type = 'reset';
		$field = MMI_Form_Field::factory($type, $settings);
		$data = $field->render();
		MMI_Debug::mdump($data, $type);

		$type = 'submit';
		$field = MMI_Form_Field::factory($type, $settings);
		$data = $field->render();
		MMI_Debug::mdump($data, $type);

		$type = 'text';
		$field = MMI_Form_Field::factory($type, $settings);
		$data = $field->render();
		MMI_Debug::mdump($data, $type);

		$type = 'textarea';
		$field = MMI_Form_Field::factory($type, $settings);
		$field->text('TeXTArea ???');
		$data = $field->render();
		MMI_Debug::mdump($data, $type);

//		$field->attribute('placeholder', 'Enter something!');
//		MMI_Debug::dump($field->attribute(), 'attr');
//		MMI_Debug::dump($field->meta(), 'meta');
	}
} // End Controller_MMI_Form_Test_Form_Form
