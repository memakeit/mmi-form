<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for radio field generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Radio extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test radio field generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_namespace' => 'mmi',

			'checked' => TRUE,
			'class' => 'radio',
			'id' => 'radio',
			'maxlength' => 22,
			'readonly' => 'readonly',
			'value' => 'test',
		);

		$type = 'radio';
		$field = MMI_Form_Field::factory($type, $settings);
		MMI_Debug::dump($field->render(), $type.' checked');

		$field->attribute('checked', FALSE);
		MMI_Debug::dump($field->render(), $type. 'unchecked');
	}
} // End Controller_MMI_Form_Test_Field_Radio
