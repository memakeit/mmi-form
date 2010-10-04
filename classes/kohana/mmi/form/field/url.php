<?php defined('SYSPATH') or die('No direct script access.');
/**
 * URL field.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_URL extends MMI_Form_Field
{
	/**
	 * Set default options.
	 *
	 * @param	array	an associative array of field options
	 * @return	void
	 */
	public function __construct($options = array())
	{
		if ( ! is_array($options))
		{
			$options = array();
		}

		// Add URL validation rule
		$rules = Arr::get($options, '_rules', array());
		if ( ! is_array($rules))
		{
			$rules = array();
		}
		if ( ! array_key_exists('url', $rules))
		{
			$rules['url'] = NULL;
		}
		$options['_rules'] = $rules;

		$options['_type'] = 'input';
		$options['type'] = 'url';
		parent::__construct($options);
	}
} // End Kohana_MMI_Form_Field_URL
