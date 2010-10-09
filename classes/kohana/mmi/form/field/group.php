<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Grouped form fields (checkboxes or radio buttons).
 *
 * @package		MMI Form
 * @author		Me Make It
 * @copyright	(c) 2010 Me Make It
 * @license		http://www.memakeit.com/license
 */
abstract class Kohana_MMI_Form_Field_Group extends MMI_Form_Field
{
	// Abstract methods
	abstract protected function _get_item_id();

	/**
	 * @var array the default item order
	 */
	protected $_default_item_order = array
	(
		MMI_Form::ORDER_FIELD,
		MMI_Form::ORDER_LABEL
	);

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

		$value = Arr::get($options, 'value', '');
		if ( ! array_key_exists('_default', $options))
		{
			$options['_default'] = $value;
		}
		if ( ! array_key_exists('_original', $options))
		{
			$options['_original'] = $value;
		}

		$order = Arr::path($options, '_group._order');
		if (is_array($order))
		{
			$options['_order'] = $order;
		}
		parent::__construct($options);
	}

	/**
	 * Generate the HTML.
	 *
	 * @return	string
	 */
	public function render()
	{
		$this->_pre_render();

		// Get group options
		$meta = $this->_meta;
		$choices = Arr::get($meta, 'choices', array());
		$group_options = Arr::get($meta, 'group', array());
		$item_options = Arr::get($group_options, '_item', array());
		$item_order = Arr::get($item_options, '_order', $this->_default_item_order);

		// Get the form value(s)
		if ($this->_state & MMI_Form::STATE_RESET)
		{
			$form_value = Arr::get($this->_meta, 'default', '');
		}
		elseif ($this->_posted)
		{
			$form_value = Arr::get($this->_meta, 'posted', '');
		}
		else
		{
			$form_value = Arr::get($this->_attributes, 'value', '');
		}

		// Generate the item HTML
		$items = array();
		foreach ($choices as $name => $value)
		{
			$html = array();
			$id = $this->_get_item_id();
			foreach ($item_order as $order)
			{
				switch ($order)
				{
					case MMI_Form::ORDER_ERROR:
						$html[] = $this->_item_error($id);
						break;

					case MMI_Form::ORDER_FIELD:
						$html[] = $this->_item_field($id, strval($value), $form_value);
						break;

					case MMI_Form::ORDER_LABEL:
						$html[] = $this->_item_label($id, $name);
						break;
				}
			}
			$items[] = implode(PHP_EOL, $html);
		}

		// Generate the group HTML
		$path = $this->_get_view_path();
		$cache = MMI_Form::view_cache($path);
		if (isset($cache))
		{
			$view = clone $cache;
		}
		if ( ! isset($view))
		{
			$view = View::factory($path);
			MMI_Form::view_cache($path, $view);
		}

		return $view->set(array
		(
			'after'		=> Arr::get($group_options, '_after', ''),
			'before'	=> Arr::get($group_options, '_before', ''),
			'items'		=> $items,
		))->render();
	}

	/**
	 * Ensure group sub-arrays are properly processed.
	 *
	 * @param	array	an associative array of field options
	 * @return	void
	 */
	protected function _init_options($options)
	{
		$group_options = Arr::get($options, '_group', array());
		if ( ! is_array($group_options))
		{
			$group_options = array();
		}

		// Process group label when a string is specified instead of an array
		$label = Arr::get($group_options, '_label', array());
		if ( ! is_array($label))
		{
			$group_options['_label'] = array('_html' => $label);
		}

		// Ensure group sub-arrays are properly merged
		$group_config = self::get_config()->get($options['type'], array());
		$group_config = Arr::get($group_config, '_group', array());
		foreach (array('_error', '_item', '_label') as $name)
		{
			$default = Arr::get($group_config, $name, array());
			$value = Arr::get($group_options, $name, array());
			$group_options[$name] = array_merge($default, $value);
		}
		$options['_group'] = array_merge($group_config, $group_options);
		$options['_is_group'] = TRUE;

		parent::_init_options($options);

		// Set the order meta value to the group order value
		$group_options = Arr::get($this->_meta, 'group', array());
		$order = Arr::get($group_options, '_order');
		if ( ! empty($order))
		{
			$this->_meta['order'] = $order;
		}
	}

	/**
	 * Generate the error HTML.
	 *
	 * @param	string	the item id
	 * @return	string
	 */
	protected function _item_error($id)
	{
		$options = Arr::path($this->_meta, 'group._item._error', array());
		$options['_html'] = Arr::get($this->_errors, $id, '');
		$options['for'] = $id;
		return MMI_Form_Label::factory($options)->render();
	}

	/**
	 * Generate the input field HTML.
	 *
	 * @param	string	the item id
	 * @param	string	the item value
	 * @param	mixed	the form value(s)
	 * @return	string
	 */
	protected function _item_field($id, $value, $form_value)
	{
		$meta = $this->_meta;
		$options = Arr::path($meta, 'group._item', array());
		$options['_is_group'] = TRUE;
		$options['id'] = $id;
		$options['value'] = $value;
		if ((is_array($form_value) AND in_array($value, $form_value)) OR (is_scalar($form_value) AND $value === $form_value))
		{
			$options['checked'] = 'checked';
		}
		elseif (isset($options['checked']))
		{
			unset($options['checked']);
		}
		$name = $this->name();
		if ($name !== '')
		{
			$options['name'] = $name;
		}
		$type = Arr::get($this->_attributes, 'type', 'checkbox');
		return MMI_Form_Field::factory($type, $options)->render();
	}

	/**
	 * Generate the label HTML.
	 *
	 * @param	string	the item id
	 * @param	string	the item value
	 * @return	string
	 */
	protected function _item_label($id, $name)
	{
		$options = Arr::path($this->_meta, 'group._item._label', array());
		$options['_html'] = $name;
		$options['for'] = $id;
		return MMI_Form_Label::factory($options)->render();
	}

	/**
	 * Get the view path.
	 *
	 * @return	string
	 */
	protected function _get_view_path()
	{
		$meta = $this->_meta;
		$dir = Arr::get($meta, 'view_path', 'mmi/form/field/group');
		$file = Arr::get($meta, 'view', 'default');
		if ( ! Kohana::find_file('views/'.$dir, $file))
		{
			// Use the default view
			$file = 'default';
		}
		return $dir.'/'.$file;
	}

	/**
	 * Get the error label settings.
	 *
	 * @return	array
	 */
	protected function _error_meta()
	{
		$error = Arr::path($this->_meta, 'group._error', array());
		$error['for'] = $this->name();
		$error['_html'] = implode('<br />', $this->_errors);
		return $error;
	}

	/**
	 * Get the label settings.
	 *
	 * @return	array
	 */
	protected function _label_meta()
	{
		$label = Arr::path($this->_meta, 'group._label', array());
		if ( ! is_array($label))
		{
			$label = array('_html' => $label);
		}
		$label['for'] = $this->name();
		$html = trim(strval(Arr::get($label, '_html', '')));
		if ($html !== '' AND substr($html, -1) !== ':')
		{
			$html .= ':';
		}
		$label['_html'] = $html;
		return $label;
	}
} // End Kohana_MMI_Form_Field_Group
