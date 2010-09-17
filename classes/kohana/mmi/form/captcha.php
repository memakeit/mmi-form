<?php defined('SYSPATH') or die('No direct script access.');
/**
 * CAPTCHA interface.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
interface Kohana_MMI_Form_CAPTCHA
{
	/**
	 * Generate the CAPTCHA HTML.
	 *
	 * @return	string
	 */
	public function html();

	/**
	 * Is the CAPTCHA response valid?
	 *
	 * @return	boolean
	 */
	public function valid();
} // End Kohana_MMI_Form_Captcha
