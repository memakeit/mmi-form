<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for form generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Form_Form extends Controller
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
			'_open' => array('_before' => 'Test Form')
		));

		$settings = array
		(
			'_label' => 'Label 1',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'alpha' => array(FALSE),
				'min_length' => array(6),
			),

			'class' => 'text',
			'name' => 'text1',
			'title' => 'enter a first name at least 6 characters long',
			'value' => 'memakeit',
		);
		$type = 'text';
		$field = MMI_Form_Field::factory($type, $settings);

		$form
			->add_field($field)
			->fieldset_open(array('_legend' => 'Submit ...', 'id' => 'fs1', '_namespace' => 'mmi'))
			->add_submit()
			->fieldset_close()
		;

		$html = trim($form->render());
		echo $html;
		if ($this->debug)
		{
			MMI_Debug::dump($html, 'form');
		}
	}
} // End Controller_MMI_Form_Test_Form_Form
