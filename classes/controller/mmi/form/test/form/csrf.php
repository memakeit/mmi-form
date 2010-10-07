<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for form generation with the CSRF plugin.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Form_CSRF extends Controller
{
	/**
	 * @var string the cache type
	 **/
	public $cache_type = MMI_Cache::CACHE_TYPE_NONE;

	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * Test form generation.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_label' => 'First Name',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'min_length' => array(6),
				'alpha' => array(FALSE),
			),

			'class' => 'text',
			'id' => 'text1',
			'title' => 'enter a first name at least 6 characters long',
			'value' => 'memakeit',
		);
		$type = 'text';
		$txt = MMI_Form_Field::factory($type, $settings);

		$form = MMI_Form::factory(array
		(
			'_auto_validate' => TRUE,
			'_open' => array('_before' => 'CSRF Test Form'),
		));
		$form
			->add_plugin('csrf', 'csrf', array('id' => 'csrf'))
			->add_field($txt)
			->add_submit()
		;

		$html = trim($form->render());
		echo $html;
		MMI_Debug::mdump($html, 'form', $form);
	}
} // End Controller_MMI_Form_Test_Form_CSRF
