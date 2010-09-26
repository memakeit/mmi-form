<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for keygen element generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Keygen extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test keygen element generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_namespace' => 'mmi',

			'challenge' => 'challenge',
			'class' => 'keygen',
			'id' => 'keygen1',
			'keytype' => 'rsa',
			'value' => 76,
			'title' => '(keygen)',
		);

		$type = 'keygen';
		$field = MMI_Form_Field::factory($type, $settings);
		echo $field->render();
		MMI_Debug::mdump($field->render(), $type, $field);
	}
} // End Controller_MMI_Form_Test_Field_Keygen
