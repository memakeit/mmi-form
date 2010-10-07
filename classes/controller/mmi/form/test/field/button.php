<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for button generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Button extends Controller_MMI_Form_Test_Field
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * @var boolean automatically add a submit button?
	 **/
	protected $_auto_add_submit = FALSE;

	/**
	 * Test button generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'button';

		$settings = array
		(
			'_namespace' => 'mmi',
			'class' => 'button',
			'id' => 'button1',
			'value' => 'button',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (button)');
		}

		$settings = array_merge($settings, array
		(
			'_html' => '<div style="height: 25px;"><b>reset</b></div>',
			'id' => 'reset1',
			'type' => 'reset',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (reset)');
		}

		$settings = array_merge($settings, array
		(
			'_html' => '<i>submit</i>',
			'id' => 'submit1',
			'type' => 'submit',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (submit)');
		}
	}
} // End Controller_MMI_Form_Test_Field_Button
