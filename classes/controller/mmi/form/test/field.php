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
	public $debug = FALSE;

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

	 * @param	Request	the request that created the controller
	 * @return	void
	 */
	public function __construct($request)
	{
		$this->_form = MMI_Form::factory(array
		(
			'id' => 'mmi_form',
			'_auto_validate' => FALSE,
		));
		parent::__construct($request);
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

		if ($_POST)
		{
			if ($form->valid())
			{
				$form->reset();
				echo 'form valid<br/>';
			}
			else
			{
				echo 'form invalid<br/>';
			}
		}

		$html = trim($form->render());
		if ($this->debug)
		{
			$html .= MMI_Debug::get($html, 'form', $form);
		}
		echo $html;
	}
} // End Controller_MMI_Form_Test_Field
