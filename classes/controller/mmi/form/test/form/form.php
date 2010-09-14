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
			'id' => 'text1',
			'title' => 'first name',
			'value' => 'shawn',

			'_description' => 'yr 1st name',
			'_namespace' => 'mmi',

			'_label' => array
			(
				'class' => 'lbl',
			),
			'_error' => array
			(
				'class' => 'err',
			),
			'_rules' => array
			(
				'min_length' => array(20),
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

//			->remove_field($btn)
//			->remove_field(array('button1', 'text1'), 'mmi')
		;
//		MMI_Debug::dead($form->field('button1', NULL), 'fields');

		echo $form->render();
		MMI_Debug::dump($form->render(), 'form');
		$form->valid();
	}
} // End Controller_MMI_Form_Test_Form_Form
