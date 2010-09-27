<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for file input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_File extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test file input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'file';

		$settings = array
		(
			'_label' => 'File 1',
			'_namespace' => 'mmi',
			'accept' => 'application/pdf',
			'class' => 'file',
			'id' => 'file1',
			'required' => 'required',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);

		$settings = array_merge($settings, array
		(
			'_label' => 'File 2',
			'accept' => '',
			'id' => 'file2',
			'required' => '',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_File
