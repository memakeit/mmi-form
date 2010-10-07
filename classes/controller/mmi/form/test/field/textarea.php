<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for textarea generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Textarea extends Controller_MMI_Form_Test_Field
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * Test textarea generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'textarea';

		$settings = array
		(
			'_label' => 'Textarea 1 (double encode TRUE)',
			'_namespace' => 'mmi',
			'class' => 'textarea',
			'id' => 'textarea1',
			'required' => 'required',
			'value' => 'TeXTArea1 \' " &amp; ???',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}

		$settings = array_merge($settings, array
		(
			'_double_encode' => FALSE,
			'_label' => 'Textarea 2 (double encode FALSE)',
			'id' => 'textarea2',
			'required' => FALSE,
			'value' => 'TeXTArea2 \' " &amp; ???',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type);
		}
	}
} // End Controller_MMI_Form_Test_Field_Textarea
