<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test controller for the jQuery validation plugin.
 *
 * @package		MMI Form
 * @category	plugin
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 * @link		http://docs.jquery.com/Plugins/Validation
 * @link		http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 */
class Controller_MMI_Form_Test_Plugin_JQuery extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = FALSE;

	/**
	 * @var boolean allow Unicode characters?
	 **/
	protected $_unicode = TRUE;

	/**
	 * Test the jQuery validation plugin.
	 *
	 * @return	void
	 */
	public function action_index()
	{
		$form = MMI_Form::factory(array
		(
			'id' => 'mmi_form',
			'_auto_validate' => TRUE,
		));

		// Alpha
		$settings = array
		(
			'_error' => array('_after' => '<br/><i>use unicode: '.($this->_unicode ? 'yes' : 'no').'</i><br/>&nbsp;'),
			'_label' => 'Alpha',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'alpha' => array($this->_unicode),
			),
			'id' => 'text1',
		);
		$txt = $form->add_field('text', $settings);

		// Alpha-dash
		$settings = array
		(
			'_error' => array('_after' => '<br/><i>use unicode: '.($this->_unicode ? 'yes' : 'no').'</i><br/>&nbsp;'),
			'_label' => 'Alpha Dash',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'alpha_dash' => array($this->_unicode),
			),
			'id' => 'text2',
		);
		$txt = $form->add_field('text', $settings);

		// Alpha-numeric
		$settings = array
		(
			'_error' => array('_after' => '<br/><i>use unicode: '.($this->_unicode ? 'yes' : 'no').'</i><br/>&nbsp;'),
			'_label' => 'Alpha Numeric',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'alpha_numeric' => array($this->_unicode),
			),
			'id' => 'text3',
		);
		$txt = $form->add_field('text', $settings);

		// Color
		$settings = array
		(
			'_label' => 'Color',
			'_namespace' => 'mmi',
			'id' => 'color1',
		);
		$txt = $form->add_field('color', $settings);

		// Credit card
		$parm = 'american express';
		$settings = array
		(
			'_error' => array('_after' => '<br/><i>enter a valid '.$parm.' number</i><br/>&nbsp;'),
			'_label' => 'Credit Card',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'credit_card' => array($parm),
			),
			'id' => 'text4',
		);
		$txt = $form->add_field('text', $settings);

		// Decimal
		$parm = array(2, 4);
		$settings = array
		(
			'_error' => array('_after' => '<br/><i>enter '.$parm[1].' digits and '.$parm[0].' decimal places</i><br/>&nbsp;'),
			'_label' => 'Decimal',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'decimal' => $parm,
			),
			'id' => 'text5',
		);
		$txt = $form->add_field('text', $settings);

		// Digit
		$settings = array
		(
			'_error' => array('_after' => '<br/><i>use unicode: '.($this->_unicode ? 'yes' : 'no').'</i><br/>&nbsp;'),
			'_label' => 'Digit',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'digit' => array($this->_unicode),
			),
			'id' => 'text6',
		);
		$txt = $form->add_field('text', $settings);

		// Exact length
		$parm = 4;
		$settings = array
		(
			'_label' => 'Exact Length',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'exact_length' => array($parm),
			),
			'id' => 'text7',
		);
		$txt = $form->add_field('text', $settings);

		// IP
		$settings = array
		(
			'_label' => 'IP Address',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'ip' => NULL,
			),
			'id' => 'text8',
		);
		$txt = $form->add_field('text', $settings);

		// Numeric
		$settings = array
		(
			'_label' => 'Numeric',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'numeric' => NULL,
			),
			'id' => 'text9',
		);
		$txt = $form->add_field('text', $settings);

		// Phone
		$parm = array(3, 5);
		$settings = array
		(
			'_label' => 'Phone',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'phone' => $parm,
			),
			'id' => 'tel1',
			'title' => 'Please enter a phone number with '.implode('or ', $parm).'digits.',
		);
		$txt = $form->add_field('tel', $settings);

		// Regex
		$parm = '/^\d{5}([\-]\d{4})?$/';
		$settings = array
		(
			'_label' => 'Regex',
			'_namespace' => 'mmi',
			'_rules' => array
			(
				'regex' => array($parm),
			),
			'id' => 'text10',
			'title' => 'Please enter a valid US zip code.',
		);
		$txt = $form->add_field('text', $settings);

		$form
			->add_plugin('jquery_validation', 'jqv')
			->add_submit()
		;
		$js = $form->jqv_get_validation_js();
		if ($this->debug)
		{
			MMI_Debug::dump($js, 'validation javascript');
		}

		echo HTML::script('media/_src/js/jquery/jquery-1.4.2.min.js').PHP_EOL;
		echo HTML::script('media/_src/js/jquery/jquery.validate.min.js').PHP_EOL;
		echo '<script type="text/javascript">'.PHP_EOL.$js.PHP_EOL.'</script>';

		$html = trim($form->render());
		echo $html;
		if ($this->debug)
		{
			MMI_Debug::dump($html, 'form');
		}
	}
} // End Controller_MMI_Form_Test_Plugin_JQuery
