<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for datetime input generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field_DateTime extends Controller_MMI_Form_Test_Field
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * Test datetime input generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$type = 'datetime';

		$settings = array
		(
			'_label' => 'DateTime 1',
			'_namespace' => 'mmi',
			'class' => 'datetime',
			'id' => 'datetime1',
			'required' => 'required',
			'step' => DATE::MINUTE * 30,
			'value' => '1970-01-01T00:00:00Z',
		);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (step 30 minutes)');
		}

		$settings = array_merge($settings, array
		(
			'_after' => '2011-01-01T00:00:00Z',
			'_before' => '2010-09-01T00:00:00Z',
			'_label' => 'DateTime 2',
			'id' => 'datetime2',
			'max' => '2011-01-01T00:00:00Z',
			'min' => '2010-09-01T00:00:00Z',
			'required' => FALSE,
			'step' => Date::HOUR * 1.5,
			'value' => '',
		));
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (min 2010-09-01T00:00:00Z; max 2011-01-01T00:00:00Z; step 1.5 hours)');
		}

		$settings = array_merge($settings, array
		(
			'_before' => '2010-06-01T00:00:00Z',
			'_label' => 'DateTime 3',
			'id' => 'datetime3',
			'min' => '2010-06-01T00:00:00Z',
			'step' => DATE::MINUTE * 15,
		));
		unset($settings['_after'], $settings['max']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (min 2010-06-01T00:00:00Z; step 15 minutes)');
		}

		$settings = array_merge($settings, array
		(
			'_after' => '2011-01-01T00:00:00Z',
			'_label' => 'DateTime 4',
			'id' => 'datetime4',
			'max' => '2011-01-01T00:00:00Z',
			'step' => Date::DAY * 1,
		));
		unset($settings['_before'], $settings['min']);
		$field = MMI_Form_Field::factory($type, $settings);
		$this->_form->add_field($field);
		if ($this->debug)
		{
			MMI_Debug::dump($field->render(), $type.' (max 2011-01-01T00:00:00Z; step 1 day)');
		}
	}
} // End Controller_MMI_Form_Test_Field_DateTime
