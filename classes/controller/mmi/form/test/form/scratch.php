<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for form generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Form_Scratch extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test form generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$form = MMI_Form::factory(array
		(
			'_auto_validate' => TRUE,
			'_namespace' => 'mmi',
			'id' => 'form1',
		));
		$form->add_field('text', array
		(
			'_label' => '<b>First Name</b>',
			'_namespace' => 'mmi',
			'id' => 'text1',
		));
		$form->add_submit('Testing Text Field');

		$html = trim($form->render());
		echo $html;
		if ($this->debug)
		{
			MMI_Debug::dump($html, 'form');
		}
	}
} // End Controller_MMI_Form_Test_Form_Scratch
