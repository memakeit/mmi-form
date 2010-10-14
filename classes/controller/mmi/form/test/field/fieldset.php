<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for fieldset generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Fieldset extends Controller_MMI_Form_Test_Field
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * Test fieldset generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'fieldset';

		$settings = array
		(
			'_legend' => 'Debug Fieldset',
			'_namespace' => 'mmi',
			'class' => 'fieldset',
			'id' => 'fs1',
			'value' => 'test',
		);
		$fieldset = MMI_Form_FieldSet::factory($settings);
		$this->_form
			->add_html($fieldset->open())
			->add_html(MMI_Debug::get($fieldset->open(), $type.' (open)'))
			->add_html(MMI_Debug::get($fieldset->close(), $type.' (close)'))
			->add_html($fieldset->close())
		;
	}
} // End Controller_MMI_Form_Test_Field_Fieldset
