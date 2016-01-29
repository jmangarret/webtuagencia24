<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  User.profile
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

//JFormHelper::loadFieldClass('input');

/**
 * Provides input for TOS
 *
 * @package     Joomla.Plugin
 * @subpackage  User.profile
 * @since       2.5.5
 */
class JFormFieldPhone extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  2.5.5
	 */
	protected $type = 'Phone';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
        // To identify each phone field
        $rand = mt_rand(0, 100);

		// Initialize some field attributes.
		$class    = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $class2   = ' class="numeric-' . $rand . ($this->element['class'] ? ' ' . (string) $this->element['class'] : '') .'"';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

        // Initialize javascript
        $script  = 'jQuery(document).ready(function(){';
        $script .=   'var $ = jQuery, forceNumeric = function(){';
        $script .=     'var key, keychar;';
        $script .=     'if(window.event) key = window.event.keyCode;';
        $script .=     'else if (e) key = e.which;';
        $script .=     'else return true;';
        $script .=     'keychar = String.fromCharCode(key);';
        $script .=     'if((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) ) return true;';
        $script .=     'else if ((("0123456789").indexOf(keychar) > -1)) return true;';
        $script .=     'else return false;';
        $script .=   '};';
        $script .=   'var passNumber = function(){';
        $script .=     '$("#' . $this->id . '").val(';
        $script .=       '$("#' . $this->id . '-country").val()+"~"+';
        $script .=       '$("#' . $this->id . '-code").val()+"~"+';
        $script .=       '$("#' . $this->id . '-number").val()';
        $script .=     ');';
        $script .=   '};';
        $script .=   '$(".numeric-' . $rand . '").on("keypress", forceNumeric).on("blur", passNumber);';
        $script .= '});';

        JFactory::getDocument()->addScriptDeclaration($script);

        $value = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');
        $parts = split('~', $value);
        $parts = count($parts) == 3 ? $parts : array('', '', '');

        $html  = '<input type="text" id="' . $this->id . '-country" ' . $class2 . ' value="' . $parts[0] . '" maxlength="3" placeholder="' . JText::_('JCOUNTRY') . '"/>';
        $html .= '<input type="text" id="' . $this->id . '-code" ' . $class2 . ' value="' . $parts[1] . '" maxlength="3" placeholder="' . JText::_('JCODE') . '"/>';
        $html .= '<input type="text" id="' . $this->id . '-number" ' . $class2 . ' value="' . $parts[2] . '" maxlength="10" placeholder="' . JText::_('JNUMBER') . '"/>';
        $html .= '<input type="hidden" id="' . $this->id . '" name="' . $this->name . '"';
        $html .= 'value="' . $value . $class . '"/>';
            
        return $html;
	}

}

