<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for hidden input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Hidden extends Controller_MMI_Form_Test_Field
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * Test hidden input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'hidden';

		$settings = array
		(
			'_namespace' => 'mmi',
			'class' => 'hidden',
			'id' => 'hidden1',
			'value' => 'test1',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}

		$settings = array_merge($settings, array
		(
			'id' => 'hidden2',
			'value' => 'test2',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}
	}
} // End Controller_MMI_Form_Test_Field_Hidden
