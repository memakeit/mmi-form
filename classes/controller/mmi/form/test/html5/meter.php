<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for HTML5 meter attributes.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_HTML5_Meter extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test the HTML5 meter attributes.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$data = MMI_HTML5_Attributes_Meter::get();
		MMI_Debug::dump($data, 'html5 meter attr');
	}
} // End Controller_MMI_Form_Test_HTML5_Meter
