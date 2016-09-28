<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  User.profile
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('text');

/**
 * Provides input for TOS
 *
 * @package     Joomla.Plugin
 * @subpackage  User.profile
 * @since       2.5.5
 */
class JFormFieldJCalendar extends JFormFieldText
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  2.5.5
	 */
	protected $type = 'JCalendar';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
		$this->element['class'] = $this->element['class'] ? $this->element['class'] . ' jcalendar' : 'jcalendar';

        // Initialize javascript
        $script  = 'jQuery(document).ready(function(){';
        $script .=   'jQuery(".jcalendar").datepicker({';
        $script .=     'maxDate: "today",';
        $script .=     'dateFormat: "yy-mm-dd",';
        $script .=     'yearRange: "c-50:c+50",';
        $script .=     'changeMonth: true,';

        if($this->element['image'])
        {
            $script .=     'showOn: "both",';
            $script .=     'buttonImageOnly: true,';
            $script .=     'buttonImage: "' . JURI::root() . $this->element['image'] . '",';
            $script .=     'buttonText: "Calendar",';
        }

        $script .=     'changeYear: true';
        $script .=   '});';
        $script .= '});';

        JFactory::getDocument()->addScriptDeclaration($script);

        return parent::getInput();
	}

}

