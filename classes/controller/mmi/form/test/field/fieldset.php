<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for fieldset generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Fieldset extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test fieldset generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_legend' => 'Address',
			'_namespace' => 'mmi',

			'class' => 'file',
			'id' => 'fs1',
			'value' => 'test',
		);

		$type = 'file';
		$fieldset = MMI_Form_FieldSet::factory($settings);
		MMI_Debug::mdump($fieldset->open(), 'fieldset open', $fieldset);
		MMI_Debug::mdump($fieldset->close(), 'fieldset close', $fieldset);
	}
} // End Controller_MMI_Form_Test_Field_Fieldset
