<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for keygen element generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Keygen extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test keygen element generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'keygen';

		$settings = array
		(
			'_label' => 'Keygen 1',
			'_namespace' => 'mmi',
			'class' => 'keygen',
			'id' => 'keygen1',
			'title' => '(keygen 1)',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);

		$settings = array_merge($settings, array
		(
			'_label' => 'Keygen 2',
			'challenge' => 'challenge',
			'id' => 'keygen2',
			'keytype' => 'rsa',
			'title' => '(keygen 2)',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_Keygen
