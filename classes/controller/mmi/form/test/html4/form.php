<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for HTML4 form attributes.
 *
 * @package		MMI Form
 * @category	HTML4
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_HTML4_Form extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test the HTML4 form attributes.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$data = MMI_HTML4_Attributes_Form::get();
		MMI_Debug::dump($data, 'html4 form attr');
	}
} // End Controller_MMI_Form_Test_HTML4_Form
