<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for HTML4 input attributes.
 *
 * @package		MMI Form
 * @category	HTML4
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_HTML4_Input extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test the HTML4 input attributes.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$types = array
		(
			'button',
			'checkbox',
			'file',
			'hidden',
			'image',
			'password',
			'radio',
			'reset',
			'submit',
			'text',

			'range',
		);
		foreach ($types as $type)
		{
			$data = MMI_HTML4_Attributes_Input::get($type);
			MMI_Debug::dump($data, 'html4 '.$type.' attr');
		}
	}
} // End Controller_MMI_Form_Test_HTML4_Input
