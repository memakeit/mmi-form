<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for URL input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_URL extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test URL input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'url';

		$settings = array
		(
			'_label' => 'URL 1',
			'_namespace' => 'mmi',
			'class' => 'url',
			'id' => 'url1',
			'required' => 'required',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);

		$settings = array_merge($settings, array
		(
			'_label' => 'URL 2',
			'id' => 'url2',
			'required' => FALSE,
			'value' => 'http://www.yahoo.com',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_URL
