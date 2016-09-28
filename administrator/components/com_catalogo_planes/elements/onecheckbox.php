<?php
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Renders a checkbox element with only one option
 *
 */

class JFormFieldOneCheckbox extends JFormField {
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	protected $type = 'OneCheckbox';

	public function getInput()
	{
                //print_r($this);die();
		$class = ($this->element->getAttribute('class') ? ' class="' . $this->element->getAttribute('class') . '"' : ' class="text_area"');
		$checked = ($this->value == $this->element->getAttribute('default')) ? ' checked="checked"' : '';

		return '<input type="checkbox" name="' . $this->options['control'].$this->name .'" id="' . $this->options['control'].$this->name . '" value="' .  $this->element->getAttribute('default') . '"' . $class . $checked . ' />';
	}
}