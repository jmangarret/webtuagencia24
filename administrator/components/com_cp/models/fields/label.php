<?php
// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

/**
 * Shows a label
 *
 */

class JFormFieldLabel extends JFormField {
	/**
	 * Element name
	 *
	 * @access       protected
	 * @var          string
	 */
	protected $type = 'Label';

	public function getInput() {
        $value = $this->value;
		$attribs = '';
		if ($v = $this->element['class']) {
			$attribs .= ' class="' . $v.'"';
		}
		return '<div' . $attribs . '>' . $value . '</div>';
	}
}
