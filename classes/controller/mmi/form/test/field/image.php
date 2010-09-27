<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for image input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Image extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test image input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'image';

		$settings = array
		(
			'_label' => 'Image 1',
			'_namespace' => 'mmi',
			'class' => 'image',
			'id' => 'image1',
			'src' => 'media/img/favicon.ico',
			'value' => 'img 1',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (relative src)');

		$settings = array_merge($settings, array
		(
			'_label' => 'Image 2',
			'id' => 'image2',
			'src' => 'http:://www.yahoo.com/favicon.ico',
			'value' => 'img 2',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type.' (absolute src)');
	}
} // End Controller_MMI_Form_Test_Field_Image
