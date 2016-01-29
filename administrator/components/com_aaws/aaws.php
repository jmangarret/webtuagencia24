<?php
/**
 *
 */
defined('_JEXEC') or die;

if (!JFactory::getUser()->authorise('core.manage', 'com_aaws')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('AawsHelper', JPATH_COMPONENT.DS.'helpers'.DS.'aaws.php');

$input = JFactory::getApplication()->input;

if($input->getCmd('task') == '')
{
    JFactory::getApplication()->redirect('index.php?option=com_aaws&task=general.display');
}

$controller = JControllerLegacy::getInstance('Aaws');
$controller->execute($input->getCmd('task'));
$controller->redirect();

