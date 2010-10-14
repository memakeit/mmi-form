<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Image button.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Image extends MMI_Form_Field implements MMI_Form_Field_NonValidating, MMI_Form_Field_NonPosting
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
		$options['_type'] = 'input';
		$options['type'] = 'image';
		parent::__construct($options);
	}

	/**
	 * Get the view parameters.
	 *
	 * @return	array
	 */
	protected function _get_view_parms()
	{
		$parms = parent::_get_view_parms();
		$src = trim(strval(Arr::get($parms['attributes'], 'src', '')));
		if ($src !== '')
		{
			if (strpos($src, '://') === FALSE)
			{
				// Prepend the base URL
				$parms['attributes']['src'] = URL::site($src, TRUE);
			}
		}
		return $parms;
	}
} // End Kohana_MMI_Form_Field_Image
