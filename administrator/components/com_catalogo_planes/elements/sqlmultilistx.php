<?php
/**
* @copyright    Copyright (C) 2009 Open Source Matters. All rights reserved.
* @license      GNU/GPL
*/
 
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
 
/**
 * Renders a multiple item select element 
 * using SQL result and explicitly specified params
 *
 */
 
class JFormFieldSQLMultiListX extends JFormField
{
        /**
        * Element name
        *
        * @access       protected
        * @var          string
        */
        protected $type = 'SQLMultiListX';
 
        public function getInput()
        {
            // Base name of the HTML control.
            $ctrl = $this->options['control'].$this->name;

            // Construct the various argument calls that are supported.
            $attribs = ' ';
            if ($v = $this->element->getAttribute('size')) {
            	$attribs .= 'size="' . $v.'"';
            }
            if ($v = $this->element->getAttribute('class')) {
            	$attribs .= 'class="' . $v.'"';
            } else {
            	$attribs .= 'class="inputbox"';
            }
            if ($m = $this->element->getAttribute('multiple')) {
            	$attribs .= ' multiple="multiple"';
            	//$ctrl .= '[]';
            }

            // Query items for list.
            $db = & JFactory::getDBO();
            $db->setQuery($this->element->getAttribute('sql'));
            $key = ($this->element->getAttribute('key_field') ? $this->element->getAttribute('key_field') : 'value');
            $val = ($this->element->getAttribute('value_field') ? $this->element->getAttribute('value_field') : $name);

            $options = array ();
            if ($this->element->getAttribute('default_label')) {
                    $options[] = array($key => $this->element->getAttribute('default'), $val => JText::_($this->element->getAttribute('default_label')));
            }
            foreach ($this->element->children() as $option) { 
                $options[] = array($key => $option->attributes('value'), $val => JText::_($option->data()));
            }
 
            $rows = $db->loadAssocList();
            foreach ($rows as $row) {
                    $options[] = array($key=>$row[$key], $val=>$row[$val]);
            }
            $nombre=  str_replace('[', '', $this->name);
            $nombre1=  str_replace(']', '', $nombre);

            if ($options) {
                    $value = explode(',', $this->value);
                    return JHTML::_('select.genericlist', $options, $ctrl, $attribs, $key, $val, $value, $this->options['control'].$nombre1);
            }
    }
}
