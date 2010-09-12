<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for select field generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Select extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test select field generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_namespace' => 'mmi',

			'checked' => TRUE,
			'class' => 'mmi select',
			'id' => 'select1',
			'readonly' => 'readonly',
			'value' => 'test',
		);

		$type = 'select';
		$field = MMI_Form_Field::factory($type, $settings);
		$field
			->add_option('value1', 'name1')
			->clear_options()
			->add_option('value2', 'name2')
			->add_option('value3', array('value4' => 'name4', 'value5' => 'name5'))
			->add_option('value100', array('value101' => 'name101', 'value102' => 'name102'))
			->remove_option('value3')

			->attribute('multiple', TRUE)
			->attribute('size', 10)
			->blank_option('blank!')
			->selected('value2');
		;
		MMI_Debug::dump($field->render(), $type.' multiple');

		$field->attribute('multiple', FALSE);
		MMI_Debug::dump($field->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_Select
