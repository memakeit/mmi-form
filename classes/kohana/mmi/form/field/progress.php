<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Progress element.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_Progress extends MMI_Form_Field
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
		$options['_type'] = 'progress';
		parent::__construct($options);
	}

	/**
	 * Load the post data.
	 *
	 * @return	void
	 */
	protected function _load_post_data()
	{
		if ( ! $this->_posted)
		{
			return;
		}
		$this->_post_data_loaded = TRUE;
		$this->_state |= MMI_Form::STATE_POSTED;
	}

	/**
	 * Get the view parameters
	 *
	 * @return	array
	 */
	protected function _get_view_parms()
	{
		$meta = $this->_meta;
		return array
		(
			'after'			=> Arr::get($meta, 'after', ''),
			'attributes'	=> $this->_get_view_attributes(),
			'before'		=> Arr::get($meta, 'before', ''),
			'html'			=> Arr::get($meta, 'html', ''),
		);
	}

	/**
	 * Get the HTML attributes allowed.
	 *
	 * @return	array
	 */
	protected function _get_allowed_attributes()
	{
		if ($this->_html5)
		{
			return MMI_HTML5_Attributes_Progress::get();
		}
		return MMI_HTML4_Attributes::get();
	}
} // End Kohana_MMI_Form_Field_Progress
