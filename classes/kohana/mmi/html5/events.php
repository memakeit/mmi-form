<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML5 events.
 *
 * @package		MMI Form
 * @category	HTML5
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_HTML5_Events
{
	/**
	 * @var the HTML5 events
	 **/
	protected static $_events;

	/**
	 * @var array HTML5 window events
	 */
	protected static $_events_window = array
	(
		'onafterprint',
		'onbeforeprint',
		'onbeforeonload',
		'onblur',
		'onerror',
		'onfocus',
		'onhaschange',
		'onload',
		'onmessage',
		'onoffline',
		'ononline',
		'onpagehide',
		'onpageshow',
		'onpopstate',
		'onredo',
		'onresize',
		'onstorage',
		'onundo',
		'onunload',
	);

	/**
	 * @var array HTML5 form events
	 */
	protected static $_events_form = array
	(
		'onblur',
		'onchange',
		'oncontextmenu',
		'onfocus',
		'onformchange',
		'onforminput',
		'oninput',
		'oninvalid',
		'onselect',
		'onsubmit',
	);

	/**
	 * @var array HTML5 keyboard events
	 */
	protected static $_events_keyboard = array
	(
		'onkeydown',
		'onkeypress',
		'onkeyup',
	);

	/**
	 * @var array HTML5 mouse events
	 */
	protected static $_events_mouse = array
	(
		'onclick',
		'ondblclick',
		'ondrag',
		'ondragend',
		'ondragenter',
		'ondragleave',
		'ondragover',
		'ondragstart',
		'ondrop',
		'onmousedown',
		'onmousemove',
		'onmouseout',
		'onmouseover',
		'onmouseup',
		'onmousewheel',
		'onscroll',
	);

	/**
	 * Get the valid HTML5 events.
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
} // End Kohana_MMI_HTML5_Events
