<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Core test controller for field generation.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Controller_MMI_Form_Test_Field extends Controller
{
	/**
	 * @var boolean turn debugging on?
	 **/
	public $debug = TRUE;

	/**
	 * @var boolean automatically add a submit button?
	 **/
	protected $_auto_add_submit = TRUE;

	/**
	 * @var MMI_Form the form object
	 **/
	protected $_form;

	/**
	 * Create the form object.
	 *
	 * @return	void
	 */
	public function __construct()
	{
		$this->_form = MMI_Form::factory();
	}

	/**
	 * Display the form HTML.
	 *
	 * @return	void
	 */
	public function after()
	{
		$form = $this->_form;
		if ($this->_auto_add_submit)
		{
			$form->add_submit();
		}
		$html = trim($form->render());
		echo $html;
		MMI_Debug::mdump($html, 'form', $form);
	}
} // End Controller_MMI_Form_Test_Field
