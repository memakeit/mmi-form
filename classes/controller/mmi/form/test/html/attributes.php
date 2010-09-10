<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for HTML attributes.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_HTML_Attributes extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test the HTML attributes.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$data = MMI_HTML4_Attributes::get();
		MMI_Debug::dump($data, 'html4 attributes');

		$data = MMI_HTML5_Attributes::get();
		MMI_Debug::dump($data, 'html5 attributes');	}
} // End Controller_MMI_Form_Test_HTML_Attributes
