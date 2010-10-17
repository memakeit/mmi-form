<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for HTML generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_HTML extends Controller_MMI_Form_Test_Field
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * Test HTML generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'html';

		$settings = array
		(
			'_after' => '<br />',
			'_html' => '<b>mmi!!!</b>',
		);
		$this->_form->add_html('<div>');

		$field = MMI_Form_Field::factory($type, $settings);
		$field->html('<i>HTML string !!!</i>');
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (text)');
		}

		$field = MMI_Form_Field::factory($type, $settings);
		$field->html_callback('Controller_MMI_Form_Test_Field_HTML::test_static');
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (static callback)');
		}

		$field = MMI_Form_Field::factory($type, $settings);
		$field->html_callback(array('Controller_MMI_Form_Test_Field_HTML', 'test_static', array('!me', 'make it?')));
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (static callback)');
		}

		$field = MMI_Form_Field::factory($type, $settings);
		$field->html_callback(array($this, 'test', array('me', 'mmi')));
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (callback)');
		}

		$this->_form->add_html('</div>');
	}

	/**
	 * Test callback.
	 *
	 * @param	string	the first name
 	 * @param	string	the last name
	 * @return	string
	 */
	public function test($first_name, $last_name)
	{
		return $first_name.' -- '.$last_name;
	}

	/**
	 * Test static callback.
	 *
	 * @param	string	the first name
 	 * @param	string	the last name
	 * @return	string
	 */
	public static function test_static($first_name ='me', $last_name = 'make it')
	{
		return $first_name.' :: '.$last_name;
	}
} // End Controller_MMI_Form_Test_Field_HTML
