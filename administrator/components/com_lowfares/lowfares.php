<?php
/**
 *
 */
defined('_JEXEC') or die;

if (!JFactory::getUser()->authorise('core.manage', 'com_lowfares')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

jimport('joomla.application.component.model');

JLoader::register('LowFaresHelper', JPATH_COMPONENT.DS.'helpers'.DS.'lowfares.php');
JLoader::register('BaseModel', JPATH_COMPONENT.DS.'models'.DS.'model.php');

$input = JFactory::getApplication()->input;

if($input->getCmd('task') == '')
{
    JFactory::getApplication()->redirect('index.php?option=com_lowfares&task=flights.display');
}

$controller = JControllerLegacy::getInstance('LowFares');
$controller->execute($input->getCmd('task'));
$controller->redirect();

