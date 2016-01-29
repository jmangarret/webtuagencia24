<?php
/**
 *
 */

defined('_JEXEC') or die;

JLoader::register('AawsController', JPATH_COMPONENT.'/controller.php');

if(JRequest::getCmd('task') == '')
    JRequest::setVar('task', 'common.error');

$controller = JControllerLegacy::getInstance('Aaws');

$controller->execute(JRequest::getCmd('task'));
$controller->redirect();

