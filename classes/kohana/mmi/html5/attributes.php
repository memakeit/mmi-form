<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML5 attributes.
 *
 * @package		MMI Form
 * @category	HTML5
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_HTML5_Attributes
{
	/**
	 * @var array HTML5 attributes (including events)
	 */
	protected static $_attributes;

	/**
	 * @var array HTML5 core attributes
	 */
	protected static $_attr_core = array
	(
		'accesskey',
		'class',
		'contenteditable',
		'contextmenu',
		'data-yourvalue',
		'dir',
		'draggable',
		'hidden',
		'id',
		'item',
		'itemprop',
		'lang',
		'spellcheck',
		'style',
		'subject',
		'tabindex',
		'title',
	);

	/**
	 * Get the valid HTML5 attributes.
	 *
	 * @return	array
	 */
	public static function get()
	{
		(self::$_attributes === NULL) AND self::$_attributes = array_values(array_unique(array_merge
		(
			MMI_HTML5_Events::get(),
			self::$_attr_core
		)));
		return self::$_attributes;
	}
} // End Kohana_MMI_HTML5_Attributes
