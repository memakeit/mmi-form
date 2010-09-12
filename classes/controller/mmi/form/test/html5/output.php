<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for HTML5 output attributes.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_HTML5_Output extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test the HTML5 output attributes.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$data = MMI_HTML5_Attributes_Output::get();
		MMI_Debug::dump($data, 'html5 output attr');
	}
} // End Controller_MMI_Form_Test_HTML5_Output
