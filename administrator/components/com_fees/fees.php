<?php
/**
 *
 */
defined('_JEXEC') or die;

if (!JFactory::getUser()->authorise('core.manage', 'com_fees')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

jimport('joomla.application.component.model');

JLoader::register('FeesHelper', JPATH_COMPONENT.DS.'helpers'.DS.'fees.php');
JLoader::register('BaseModel', JPATH_COMPONENT.DS.'models'.DS.'model.php');

$input = JFactory::getApplication()->input;

if($input->getCmd('task') == '')
{
    JFactory::getApplication()->redirect('index.php?option=com_fees&task=groups.display');
}

$controller = JControllerLegacy::getInstance('Fees');
$controller->execute($input->getCmd('task'));
$controller->redirect();

?>
 