<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for radio button generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Radio extends Controller_MMI_Form_Test_Field
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * Test radio button generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'radio';

		$settings = array
		(
			'_before' => '<div>'.PHP_EOL,
			'_label' => array('_before' => '', '_html' => 'Radio Button 1'),
			'_namespace' => 'mmi',
			'checked' => FALSE,
			'class' => 'radio',
			'id' => 'radio1',
			'required' => 'required',
			'value' => 'rb-value-1',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (unchecked)');
		}

		$settings = array_merge($settings, array
		(
			'_before' => '',
			'_label' => array('_before' => '<div>'.PHP_EOL, '_html' => 'Radio Button 2'),
			'_order' => array(MMI_Form::ORDER_LABEL, MMI_Form::ORDER_FIELD, MMI_Form::ORDER_ERROR),
			'checked' => 'checked',
			'id' => 'radio2',
			'required' => FALSE,
			'value' => '',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (checked w/out value)');
		}
	}
} // End Controller_MMI_Form_Test_Field_Radio
