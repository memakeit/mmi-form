<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML4 events.
 *
 * @package		MMI Form
 * @category	HTML4
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_HTML4_Events
{
	/**
	 * @var the HTML4 events
	 **/
	protected static $_events;

	/**
	 * @var array HTML4 window events
	 */
	protected static $_events_window = array
	(
		'onblur',
		'onfocus',
		'onload',
	);

	/**
	 * @var array HTML4 form events
	 */
	protected static $_events_form = array
	(
		'onblur',
		'onchange',
		'onfocus',
		'onreset',
		'onselect',
		'onsubmit',
	);

	/**
	 * @var array HTML4 keyboard events
	 */
	protected static $_events_keyboard = array
	(
		'onkeydown',
		'onkeypress',
		'onkeyup',
	);

	/**
	 * @var array HTML4 mouse events
	 */
	protected static $_events_mouse = array
	(
		'onclick',
		'ondblclick',
		'onmousedown',
		'onmousemove',
		'onmouseout',
		'onmouseover',
		'onmouseup',
	);

	/**
	 * Get the valid HTML4 events.
	 *
	 * @return	array
	 */
	public static function get()
	{
		(self::$_events === NULL) AND self::$_events = array_values(array_unique(array_merge
		(
			self::$_events_window,
			self::$_events_form,
			self::$_events_keyboard,
			self::$_events_mouse
		)));
		return self::$_events;
	}
} // End Kohana_MMI_HTML4_Events
