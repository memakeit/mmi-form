<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for meter generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Meter extends Controller_MMI_Form_Test_Field
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * Test meter generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'meter';

		$settings = array
		(
			'_html' => 'meter 1!',
			'_label' => 'Meter 1',
			'_namespace' => 'mmi',
			'class' => 'meter',
			'id' => 'meter1',
			'high' => 75,
			'low' => 25,
			'min' => 0,
			'max' => 100,
			'optimum' => 50,
			'value' => 76,
			'title' => '76%',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}

		$settings = array_merge($settings, array
		(
			'_html' => 'meter 2!',
			'_label' => 'Meter 2',
			'id' => 'meter2',
			'high' => 8,
			'low' => 2,
			'min' => 0,
			'max' => 10,
			'optimum' => 5,
			'value' => 6,
			'title' => '6/10',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}
	}
} // End Controller_MMI_Form_Test_Field_Meter
