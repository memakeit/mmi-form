<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for label generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Label extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test label generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'label';

		$settings = array
		(
			'_before' => '',
			'_html' => 'Label 1:',
			'_namespace' => 'mmi',
			'id' => 'lbl1',
		);
		$label = MMI_Form_Label::factory($settings);
		$this->_form->add_html('<p>'.$label->render().'</p>');
		MMI_Debug::dump($label->render(), $type);

		$settings = array_merge($settings, array
		(
			'_html' => 'Label 2',
			'_namespace' => 'mmi',
			'id' => 'lbl2',
		));
		$label = MMI_Form_Label::factory($settings);
		$this->_form->add_html('<p>'.$label->render().'</p>');
		MMI_Debug::dump($label->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_Label
