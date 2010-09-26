<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML content.
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
class Kohana_MMI_Form_Field_HTML extends MMI_Form_Field
{
	// Class constants
	const SRC_CALLBACK = 'callback';
	const SRC_STRING = 'string';

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

		// Process HTML when a string is initially specified
		$scalar = Arr::get($options, '_scalar');
		if ( ! empty($scalar))
		{
			$options['id'] = str_replace('.', '', microtime(TRUE));
			$options['_html'] = $scalar;
			$options['_id_generated']= TRUE;
		}

		$options['type'] = 'html';
		if (empty($options['_order'] ))
		{
			$options['_order'] = array(MMI_Form::ORDER_FIELD);
		}
		if (empty($options['_source']))
		{
			$options['_source'] = self::SRC_STRING;
		}
		parent::__construct($options);
	}

	/**
	 * Set the HTML string.
	 *
	 * @param	string	the HTML string
	 * @return	MMI_Form_Field_HTML
	 */
	public function html($value = NULL)
	{
		$this->meta('source', self::SRC_STRING);
		return $this->meta('html', $value);
	}

	/**
	 * Set the HTML callback function.
	 *
	 * @param	mixed	the string or array representing the callback
	 * @return	MMI_Form_Field_HTML
	 */
	public function html_callback($value = NULL)
	{
		$this->meta('source', self::SRC_CALLBACK);
		return $this->meta('html', $value);
	}

	/**
	 * Get the view parameters.
	 *
	 * @return	array
	 */
	protected function _get_view_parms()
	{
		$meta = $this->_meta;
		if (Arr::get($meta, 'source', self::SRC_STRING) === self::SRC_STRING)
		{
			$html = Arr::get($meta, 'html', '');
		}
		else
		{
			$html = $this->_get_callback_html();
		}
		return array
		(
			'after'		=> Arr::get($meta, 'after', ''),
			'before'	=> Arr::get($meta, 'before', ''),
			'html'		=> $html,
		);
	}

	/**
	 * Generate the HTML using a callback.
	 *
	 * @return	string
	 */
	protected function _get_callback_html()
	{
		$callback = Arr::get($this->_meta, 'html');
		if (empty($callback))
		{
			return '';
		}

		$html = '';
		if (is_string($callback) AND strpos($callback, '::') !== FALSE)
		{
			// Convert the static callback into an array
			$callback = explode('::', $callback, 2);
		}

		if (is_array($callback))
		{
			// Separate the object and method
			list ($object, $method) = $callback;

			// Get the arguments
			$args = array();
			if (isset($callback[2]))
			{
				$args = $callback[2];
			}
			if ( ! is_array($args))
			{
				$args = array();
			}

			// Invoke an object method
			$method = new ReflectionMethod($object, $method);
			if ( ! is_object($object))
			{
				// Object must be null for static calls
				$object = NULL;
			}
			$html = $method->invokeArgs($object, $args);
		}
		else
		{
			// Invoke a function
			$function = new ReflectionFunction($callback);
			$html = $function->invoke();
		}
		return $html;
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
			return MMI_HTML5_Attributes::get();
		}
		return MMI_HTML4_Attributes::get();
	}
} // End Kohana_MMI_Form_Field_HTML
