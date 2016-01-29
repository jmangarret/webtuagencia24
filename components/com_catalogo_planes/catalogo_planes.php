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

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// Initialize the controller
$controller = new Catalogo_planesController();
$task = JRequest::getCmd('task')?JRequest::getCmd('task'):null;
$controller->execute($task);
// Redirect if set by the controller
$controller->redirect();
?>