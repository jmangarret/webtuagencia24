<?php
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

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

	public function getInput()
	{
		$size = ($node->attributes('size') ? 'size="' . $node->attributes('size').'"' : '');
		$class = ($node->attributes('class') ? 'class="' . $node->attributes('class').'"' : 'class="text_area"');
        /*
         * Required to avoid a cycle of encoding &
         * html_entity_decode was used in place of htmlspecialchars_decode because
         * htmlspecialchars_decode is not compatible with PHP 4
         */
        $values = explode('|', $value);
        $value = htmlspecialchars(html_entity_decode($values[0], ENT_QUOTES), ENT_QUOTES);
        $additional = $values[1];

		if ($node->attributes('side') == 'right') {
			return '<input type="text" name="' . $control_name.'[' . $name.']" id="' . $control_name.$name.'" value="' . $value.'" ' . $class.' ' . $size.' /> ' . $additional;
		} else {
			return $additional . ' <input type="text" name="' . $control_name.'[' . $name.']" id="' . $control_name.$name.'" value="' . $value.'" ' . $class.' ' . $size.' />';
		}
	}
}