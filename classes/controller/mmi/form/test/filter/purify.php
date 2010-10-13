<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for the HTML Purifier filter.
 *
 * @package		MMI Form
 * @category	filter
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 * @link		http://htmlpurifier.org/
 */
class Controller_MMI_Form_Test_Filter_Purify extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * Test the HTML Purify filter.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$settings = array
		(
			'_filters'	=> array
			(
				'MMI_Form_Filter_HTML::purify' => array('b,i'),
			),
			'_label' => 'Notes',
			'_namespace' => 'mmi',

			'_after' => '<br/><i>only &lt;b&gt; and &lt;i&gt; tags are allowed</i>',
			'class' => 'textarea',
			'id' => 'textarea1',
			'value' => '<span><b>memakeit</b></span>!',
		);
		$type = 'textarea';
		$txt = MMI_Form_Field::factory($type, $settings);

		$form = MMI_Form::factory(array
		(
			'_open' => array('_before' => 'Purify Filter Test'),
		));
		$form
			->add_field($txt)
			->add_submit()
		;

		$html = trim($form->render());
		echo $html;
		if ($this->debug)
		{
			MMI_Debug::mdump($html, 'form', $form);
		}
	}
} // End Controller_MMI_Form_Test_Filter_Purify
