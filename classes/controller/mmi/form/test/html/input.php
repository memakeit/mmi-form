<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for HTML input attributes.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_HTML_Input extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test the HTML input attributes.
	 *
	 * @return	void
	 */
	public function action_index()
	{
//		$data = MMI_HTML4_Attributes_Input::get();
//		MMI_Debug::dump($data, 'html4 form attr');

		$data = MMI_HTML5_Attributes_Input::get();
		MMI_Debug::dump($data, 'html5 text attr');
		$data = MMI_HTML5_Attributes_Input::get('range');
		MMI_Debug::dump($data, 'html5 range attr');
	}
} // End Controller_MMI_Form_Test_HTML_Input
