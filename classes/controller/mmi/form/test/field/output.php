<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for output element generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Output extends Controller_MMI_Form_Test_Field
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * Test output element generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'output';

		$settings = array
		(
			'_html' => 'output 1!',
			'_label' => 'Output 1',
			'_namespace' => 'mmi',
			'class' => 'output',
			'id' => 'output1',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}

		$settings = array_merge($settings, array
		(
			'_html' => 'output 2!',
			'_label' => 'Output 2',
			'id' => 'output2',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}
	}
} // End Controller_MMI_Form_Test_Field_Output
