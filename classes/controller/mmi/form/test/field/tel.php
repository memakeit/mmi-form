<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for tel input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Tel extends Controller_MMI_Form_Test_Field
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * Test tel input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'tel';

		$settings = array
		(
			'_label' => 'Tel 1',
			'_namespace' => 'mmi',
			'class' => 'tel',
			'id' => 'tel1',
			'required' => 'required',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}

		$settings = array_merge($settings, array
		(
			'_label' => 'Tel 2',
			'id' => 'tel2',
			'required' => FALSE,
			'value' => '111-222-3333',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}
	}
} // End Controller_MMI_Form_Test_Field_Tel
