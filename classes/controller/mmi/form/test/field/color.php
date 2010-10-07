<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for color input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Color extends Controller_MMI_Form_Test_Field
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * Test color input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'color';

		$settings = array
		(
			'_label' => 'Color 1',
			'_namespace' => 'mmi',
			'class' => 'color',
			'id' => 'color1',
			'required' => 'required',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}

		$settings = array_merge($settings, array
		(
			'_label' => 'Color 2',
			'id' => 'color2',
			'required' => FALSE,
			'value' => 'FFFFFF',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}
	}
} // End Controller_MMI_Form_Test_Field_Color
