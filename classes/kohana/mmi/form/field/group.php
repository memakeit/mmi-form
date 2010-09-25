<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Grouped form field (checkboxes or radio buttons).
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
	abstract protected function _get_name();

	/**
	 * @var array the default item order
	 */
	protected $_default_item_order = array
	(
		MMI_Form::ORDER_FIELD,
		MMI_Form::ORDER_LABEL
	);

	/**
	 * Generate the HTML.
	 *
	 * @return	string
	 */
	public function render()
	{
		// Get group options
		$meta = $this->_meta;
		$choices = Arr::get($meta, 'choices', array());
		$group_options = Arr::get($meta, 'group', array());
		$group_order = Arr::get($meta, '_order', $this->_default_item_order);

		// Cast the form value(s) to string(s)
		$form_value = Arr::get($this->_attributes, 'value', '');
		if (is_array($form_value))
		{
			foreach ($form_value as $idx => $value)
			{
				$form_value[$idx] = strval($value);
			}
		}
		elseif (is_scalar($form_value))
		{
			$form_value = strval($form_value);
		}

		// Generate the item HTML
		$items = array();
		foreach ($choices as $name => $value)
		{
			$html = '';
			$id = $this->_get_item_id();
			foreach ($group_order as $order)
			{
				switch ($order)
				{
					case MMI_Form::ORDER_ERROR:
						$html .= $this->_item_error($id);
						break;

					case MMI_Form::ORDER_FIELD:
						$html .= $this->_item_field($id, strval($value), $form_value);
						break;

					case MMI_Form::ORDER_LABEL:
						$html .= $this->_item_label($id, $name);
						break;
				}
			}
			$items[] = $html;
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
	 * Generate the item's error HTML.
	 *
	 * @param	string	the item id
	 * @return	string
	 */
	protected function _item_error($id)
	{
		$options = Arr::path($this->_meta, 'group._error', array());
		$options['_html'] = Arr::get($this->_errors, $id, '');
		$options['for'] = $id;
		return MMI_Form_Label::factory($options)->render();
	}

	/**
	 * Generate the item's input field HTML.
	 *
	 * @param	string	the item id
	 * @param	string	the item value
	 * @param	mixed	the checked form value(s)
	 * @return	string
	 */
	protected function _item_field($id, $value, $form_value)
	{
		$meta = $this->_meta;
		$options = Arr::path($meta, 'group._field', array());
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
		$name = $this->_get_name();
		if ( ! empty($name))
		{
			$options['name'] = $name;
		}
		$type = Arr::get($this->_attributes, 'type', 'checkbox');
		return MMI_Form_Field::factory($type, $options)->render();
	}

	/**
	 * Generate the item's label HTML.
	 *
	 * @param	string	the item id
	 * @param	string	the item value
	 * @return	string
	 */
	protected function _item_label($id, $name)
	{
		$options = Arr::path($this->_meta, 'group._label', array());
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
} // End Kohana_MMI_Form_Field_Group
