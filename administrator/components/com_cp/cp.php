<?php
/**
 * @package    Cp
 * @subpackage com_cp
 *
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

if (!JFactory::getUser()->authorise('core.manage', 'com_cp')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Require the base controller
JLoader::register('CPController', JPATH_COMPONENT . DS . 'controller.php');
// require helper file
JLoader::register('CPHelper', JPATH_COMPONENT . DS . 'helper.php');
JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cp' . DS . 'tables');

$controller = JControllerLegacy::getInstance('CP');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
