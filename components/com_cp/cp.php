<?php
/**
 * Cp entry point file for cp Component
 * 
 * @package    Cp
 * @subpackage com_cp
 *
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// require helper file
JLoader::register('CPHelper', JPATH_COMPONENT . DS . 'helper.php');

$controller = JControllerLegacy::getInstance('CP');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
