<?php
 
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

// import Joomla formfile library
jimport('joomla.form.formfield');
/**
 * Shows a label
 *
 */
 
class JFormFieldLabel extends JFormField
{
    /**
    * Element name
    *
    * @access       protected
    * @var          string
    */
    protected $type = 'Label';

    public function getInput()
    {   //obtenemos a clase 
        $attribs = '';
        if ($v = $this->element->getAttribute('class')) {
            $attribs .= 'class="' . $v.'"';
        }
        return '<div' . $attribs . '>' . $this->value . '</div>';
    }
}
