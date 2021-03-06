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
		$form = MMI_Form::factory(array
		(
			'_open' => array('_before' => 'Purify Filter Test'),
		));

		$settings = array
		(
			'_filters'	=> array
			(
				'MMI_Form_Filter_HTML::purify' => array
				(
					array
					(
						'AutoFormat.AutoParagraph' => TRUE,
						'HTML.Allowed' => 'a[href],b,i,p',
					)
				),
			),
			'_label' => 'Notes',
			'_namespace' => 'mmi',

			'_after' => '<br/><i>tags allowed: a[href], b, i, and p<br/>auto-formating of paragraphs is enabled</i>',
			'class' => 'textarea',
			'id' => 'textarea1',
			'value' => '<span><b>memakeit</b></span>!'.PHP_EOL.PHP_EOL.'hello'.PHP_EOL.PHP_EOL.'goodbye',
		);
		$type = 'textarea';
		$txt = MMI_Form_Field::factory($type, $settings);

		$form
			->add_field($txt)
			->add_submit()
		;

		$html = trim($form->render());
		echo $html;
		if ($this->debug)
		{
			MMI_Debug::dump($html, 'form');
		}
	}
} // End Controller_MMI_Form_Test_Filter_Purify
