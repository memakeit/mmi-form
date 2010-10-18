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
			'_auto_validate' => FALSE,
			'_namespace' => 'mmi',
			'id' => 'form1',
		));
		$form->add_field('text', array
		(
			'_label' => 'First Name',
			'_namespace' => 'mmi',
			'id' => 'text1',
			'required' => 'required',
		));
		$form->add_submit('Testing ...');

		if ($_POST)
		{
			if ($form->valid())
			{
				$form->reset();
			}
			else
			{
				// Invalid logic here
			}
		}
		echo $form->render();
	}
} // End Controller_MMI_Form_Test_Form_Scratch
