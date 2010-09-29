<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for search input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_Search extends Controller_MMI_Form_Test_Field
{
	/**
	 * Test search input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'search';

		$settings = array
		(
			'_label' => 'Search 1',
			'_namespace' => 'mmi',
			'class' => 'search',
			'id' => 'search1',
			'required' => 'required',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);

		$settings = array_merge($settings, array
		(
			'_label' => 'Search 2',
			'id' => 'search2',
			'required' => FALSE,
			'value' => 'search 2',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		MMI_Debug::dump($field->render(), $type);
	}
} // End Controller_MMI_Form_Test_Field_Search
