<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: catalogo_planes.php 2012-09-10 18:29:50 svn $
 * @author Yusely Palacios
 * @package Joomla
 * @subpackage catalogo_planes
 * @license Copyright (c) 2012 Amadeus - All Rights Reserved
 *
 * 
 *
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
/*
 * Define constants for all pages
 */
define('COM_CATALOGO_PLANES_DIR', $option . DS);
define('COM_CATALOGO_PLANES_BASE', JPATH_ADMINISTRATOR . DS . 'components' . DS . COM_CATALOGO_PLANES_DIR);
define('COM_CATALOGO_PLANES_BASEURL', JURI::base() . 'components/' . str_replace(DS, '/', COM_CATALOGO_PLANES_DIR));
define('COM_CATALOGO_PLANES_IMAGESDIR', '../administrator/components/' . $option . '/assets/images');
define('COM_CATALOGO_PLANES_IMAGESURL', COM_CATALOGO_PLANES_BASEURL . 'assets/images');

// Incluir archivo para codificar en formato JSON.
require_once(COM_CATALOGO_PLANES_BASE . 'lib' . DS . 'json' . DS . 'Encoder.php');
include_once(COM_CATALOGO_PLANES_BASE . 'lib' . DS . 'json' . DS . 'Decoder.php');
JTable::addIncludePath(COM_CATALOGO_PLANES_BASE . 'tables');

// Incluye hoja de estilo y scripts
JHTML::stylesheet('jquery-ui.custom.css', COM_CATALOGO_PLANES_BASEURL . 'assets/css/');
JHTML::stylesheet('general.css', COM_CATALOGO_PLANES_BASEURL . 'assets/css/');
JHTML::script('jquery.min.js', COM_CATALOGO_PLANES_BASEURL . 'assets/js/');
JHTML::script('jquery-ui.custom.min.js', COM_CATALOGO_PLANES_BASEURL . 'assets/js/');
JHTML::script('jquery.validate.min.js', COM_CATALOGO_PLANES_BASEURL . 'assets/js/');
JHTML::script('additional-methods.min.js', COM_CATALOGO_PLANES_BASEURL . 'assets/js/');
JHTML::script('general.js', COM_CATALOGO_PLANES_BASEURL . 'assets/js/');
$lang =& JFactory::getLanguage();
$langTag = substr($lang->getTag(), 0, 2);
if ($langTag != 'en') {
    JHTML::script('messages_' . $langTag . '.js', COM_CATALOGO_PLANES_BASEURL . 'assets/js/');
}

// Obtener el nombre de la vista a acceder. Si no hay, use la vista por defecto
if (JRequest::getCmd('view') == '') {
	JRequest::setVar('view', 'panel');
}

$view = JRequest::getCmd('view');
$controllerFile = JPATH_COMPONENT . DS . 'controllers' . DS . $view . '.php';

// Lance error si no existe el controlador para la vista accedida.
if (!file_exists($controllerFile)) {
	JError::raiseError (500, JText::_("File not found") . ': ' . $controllerFile);
}

// Require the base controller
require_once $controllerFile;

// Initialize the controller
$controllerName = 'CatalogoPlanesController' . ucfirst($view);
$controller = new $controllerName();

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
