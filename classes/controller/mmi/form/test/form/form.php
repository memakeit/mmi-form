<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for form generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Form_Form extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test form generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
//		$settings = array
//		(
//			'_html' => '<b>button</b>',
////			'_namespace' => 'mmi',
//
//			'checked' => TRUE,
//			'class' => 'button',
//			'id' => 'button1',
//			'maxlength' => 22,
//			'readonly' => 'readonly',
//			'value' => 'test',
//		);
//
//		$type = 'button';
//		$btn = MMI_Form_Field::factory($type, $settings);

		$settings = array
		(
			'class' => 'text',
			'name' => 'text1',
			'title' => 'first name',
			'value' => 'shawn',

			'_description' => 'yr 1st name',
			'_namespace' => 'mmi',

			'_label' => array
			(
				'class' => 'lbl',
				'_html' => 'lbl #1:'
			),
			'_error' => array
			(
				'class' => 'err',
			),
			'_rules' => array
			(
				'min_length' => array(6),
				'alpha' => array(FALSE),
			),
		);
		$type = 'text';
		$txt = MMI_Form_Field::factory($type, $settings);

		$form = MMI_Form::factory();
		$form
			->add_field($txt)
			->add_field('submit', array
			(
				'id' => 'submit',
				'value' => 'Submit!',
			));
		;

		$form->add_plugin('csrf', 'csrf', array('id' => 'mmit'));
		$form->add_captcha('recaptcha');
		$form->add_plugin('jquery_validation', 'jval');
		$jquery = $form->jval_get_validation_js();
		MMI_Debug::dead($jquery, 'jquery_get_validation_js');
//MMI_Debug::dead($form, 'form');
		echo $form->render();
		MMI_Debug::dump($form->render(), 'form');
//		MMI_Debug::dump($form->valid(), 'valid');
//		MMI_Debug::dump($form->error(), 'errors');
//		MMI_Debug::dump($form->updated(), 'updated');
//		MMI_Debug::dump($form->diff(), 'diff');
		MMI_Debug::dump(MMI_Form::instance(), 'form');
	}
} // End Controller_MMI_Form_Test_Form_Form
