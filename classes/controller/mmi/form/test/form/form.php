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
		$settings = array
		(
			'_label' => 'Label 1',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'min_length' => array(6),
				'alpha' => array(FALSE),
			),

			'class' => 'text',
			'name' => 'text1',
			'title' => 'enter a first name at least 6 characters long',
			'value' => 'memakeit',
		);
		$type = 'text';
		$txt = MMI_Form_Field::factory($type, $settings);

		$form = MMI_Form::factory(array('_open' => array('_before' => 'Test Form')))
			->add_field($type, $settings)
			->fieldset_open(array('_legend' => 'Submit ...', 'id' => 'fs1', '_namespace' => 'mmi'))
			->add_submit()
			->fieldset_close()
		;

//		$form->add_plugin('jquery_validation', 'jval');
//		$jquery = $form->jval_get_validation_js();
//		MMI_Debug::dead($jquery, 'jquery_get_validation_js');
//MMI_Debug::dead($form, 'form');
		$html = $form->render();
		echo $html;
		MMI_Debug::mdump($html, 'form', $form);
//		MMI_Debug::dump($form->valid(), 'valid');
//		MMI_Debug::dump($form->error(), 'errors');
//		MMI_Debug::dump($form->updated(), 'updated');
//		MMI_Debug::dump($form->diff(), 'diff');
//		MMI_Debug::dump(MMI_Form::instance(), 'form');
	}
} // End Controller_MMI_Form_Test_Form_Form
