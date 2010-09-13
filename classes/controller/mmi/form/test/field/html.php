<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for HTML field generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_HTML extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test HTML field generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_html' => '<b>mmi!!!</b>',
		);

		$type = 'html';
		$field = MMI_Form_Field::factory($type, $settings);
		$field->html('<i>HTML string !!!</i>');
		MMI_Debug::dump($field->render(), 'html text');

		$field->html_callback('Controller_MMI_Form_Test_Field_HTML::test_static');
		MMI_Debug::mdump($field->render(), 'html static callback');

		$field->html_callback(array('Controller_MMI_Form_Test_Field_HTML', 'test_static', array('!me', 'make it?')));
		MMI_Debug::dump($field->render(), 'html static callback');

		$field->html_callback(array($this, 'test', array('me', 'mmi')));
		MMI_Debug::mdump($field->render(), 'html callback', $field);
	}

	/**
	 * Test callback.
	 *
	 * @param	string	the first name
 	 * @param	string	the last name
	 * @return	void
	 */
	public function test($first_name, $last_name)
	{
		return $first_name.' '.$last_name;
	}

	/**
	 * Test static callback.
	 *
	 * @param	string	the first name
 	 * @param	string	the last name
	 * @return	void
	 */
	public static function test_static($first_name ='me', $last_name = 'make it')
	{
		return $first_name.' :: '.$last_name;
	}
} // End Controller_MMI_Form_Test_Field_HTML
