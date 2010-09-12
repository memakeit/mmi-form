<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for HTML5 input attributes.
 *
 * @package		MMI Form
 * @category	HTML5
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_HTML5_Input extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test the HTML5 input attributes.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$types = array
		(
			'button',
			'checkbox',
			'color',
			'date',
			'datetime',
			'datetime-local',
			'email',
			'file',
			'hidden',
			'image',
			'month',
			'number',
			'password',
			'radio',
			'range',
			'reset',
			'search',
			'submit',
			'tel',
			'text',
			'time',
			'url',
			'week',
		);
		foreach ($types as $type)
		{
			$data = MMI_HTML5_Attributes_Input::get($type);
			MMI_Debug::dump($data, 'html5 '.$type.' attr');
		}
	}
} // End Controller_MMI_Form_Test_HTML5_Input
