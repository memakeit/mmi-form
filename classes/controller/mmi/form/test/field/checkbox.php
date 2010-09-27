<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for checkbox generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Checkbox extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test checkbox generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'checkbox';

		$settings = array
		(
			'_before' => '<div>'.PHP_EOL,
			'_label' => array('_before' => '', '_html' => 'Checkbox 1'),
			'_namespace' => 'mmi',
			'checked' => TRUE,
			'class' => 'checkbox',
			'id' => 'checkbox1',
			'required' => 'required',
			'value' => 'test',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (checked)');

		$settings = array_merge($settings, array
		(
			'_before' => '',
			'_label' => array('_before' => '<div>'.PHP_EOL, '_html' => 'Checkbox 2'),
			'_order' => array(MMI_Form::ORDER_LABEL, MMI_Form::ORDER_FIELD, MMI_Form::ORDER_ERROR),
			'checked' => FALSE,
			'id' => 'checkbox2',
			'required' => FALSE,
			'value' => '',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (unchecked w/out value)');
	}
} // End Controller_MMI_Form_Test_Field_Checkbox
