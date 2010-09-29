<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for datalist generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_DataList extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test datalist generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		// Create the data list
		$type = 'datalist';
		$settings = array
		(
			'_choices' => array
			(
				'value1' => 'name1',
				'value2' => 'name2',
				'value3' => 'name3',
				'value4' => 'name4',
				'value5' => 'name5',
			),
			'_namespace' => 'mmi',
			'class' => 'datalist',
			'id' => 'datalist1',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);

		// Create the text inputs
		$type = 'text';
		$settings = array
		(
			'_label' => 'Text 1',
			'_namespace' => 'mmi',
			'class' => 'text',
			'id' => 'text1',
			'list' => $field->id(),
			'required' => 'required',
		);
		$text = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($text);
		MMI_Debug::dump($text->render(), $type);

		$settings = array_merge($settings, array
		(
			'_label' => 'Text 2',
			'id' => 'text2',
			'list' => $field->id(),
			'required' => FALSE,
			'value' => 'value5',
		));
		$text = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($text);
		MMI_Debug::dump($text->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_DataList
