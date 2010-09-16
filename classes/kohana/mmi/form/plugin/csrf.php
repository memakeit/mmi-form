<?php defined('SYSPATH') or die('No direct script access.');
/**
 * CSRF (cross-site request forgery) plugin.
 *
 * @package		MMI Form
 * @category	plugin
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Plugin_CSRF extends MMI_Form_Plugin
{
	/**
	 * @var string the CSRF token
	 */
	protected $_token;

	/**
	 * Initialize the options.
	 *
	 * @param	array	an associative array of plugin options
	 * @return	void
	 */
	public function __construct($options = array())
	{
		if (Request::$is_ajax)
		{
			return;
		}
		parent::__construct($options);

		if (empty($this->_options['id']))
		{
			$this->_options['id'] = 'mmi_token';
		}
		$this->_token = Security::token();
		$this->_set_token();
	}

	/**
	 * Is the CSRF token valid?  If not, set an error in the form.
	 *
	 * @return	boolean
	 */
	public function valid()
	{
		$valid = ($this->_token === $_POST[$this->_id()]);
		if ( ! $valid)
		{
			$this->form()->error('invalid csrf');
		}
		return $valid;
	}

	/**
	 * Set the CSRF token (in the session and in a hidden form field).
	 *
	 * @return	void
	 */
	protected function _set_token()
	{
		// Generate a new token and save it in the session
		$token = Security::token(TRUE);

		// Add a hidden form field
		$this->form()->add_field('hidden', array
		(
			'id'			=> $this->_id(),
			'value'			=> $token,
			'_callbacks'	=> array
			(
				array($this, 'valid', array($token)),
			),
		));
	}

	/**
	 * Get the hidden field id.
	 *
	 * @return	string
	 */
	protected function _id()
	{
		$options = $this->_options;
		$id = Arr::get($options, 'id');
		$namespace = Arr::get($options, '_namespace');
		return MMI_Form_Field::field_id($id, $namespace);
	}
} // End Kohana_MMI_Form_Plugin_CSRF
