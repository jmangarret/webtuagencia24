<?php
// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
/**
 * Renders a text element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JFormFieldAdditionalText extends JFormField {
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $type = 'AdditionalText';

	public function getInput() {
		$name = $this->element['name'];
		$control_name = $this->name;
		$value = $this->value;
		$size = ($this->element['size']? ' size="' . $this->element['size'] . '"' : '');
		$class = ($this->element['class']? ' class="' . $this->element['class'] . '"' : 'class="text_area"');
		/*
		 * Required to avoid a cycle of encoding &
		 * html_entity_decode was used in place of htmlspecialchars_decode because
		 * htmlspecialchars_decode is not compatible with PHP 4
		 */
		$values = explode('|',  $value);
		$value = htmlspecialchars(html_entity_decode($values[0], ENT_QUOTES), ENT_QUOTES);
		$additional = $values[1];

		if ($this->element['side'] == 'right') {
			return '<input type="text" name="' . $control_name . '[' . $name . ']" id="' . $control_name.$name . '" value="' . $value . '" ' . $class.' ' . $size . ' /> ' . $additional;
		} else {
			return $additional . ' <input type="text" name="' . $control_name . '[' . $name . ']" id="' . $control_name.$name . '" value="' . $value . '"' . $class . $size . ' />';
		}
	}
}