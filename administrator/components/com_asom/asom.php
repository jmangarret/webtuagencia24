<?php
/**
 * @file com_asom/admin/admin.asom.php
 * @defgroup _comp_adm Componente (Administración)
 * Archivo de entrada del componente en su parte administrativa.
 *
 * @mainpage Componente de Administración de Pedidos - <b>A</b>madeu<b>S</b> <b>O</b>rder <b>M</b>anager
 * @section Introducción
 *
 * Componente diseñado para guardar y administrar pedidos de fuentes externas, como el motor E-Retail de
 * Amadeus u otros.
 *
 * El componente puede interactuar con diferentes crecursos o componentes mediante plugins y clases que se
 * especializan en cada recurso, para traducirlos y manejarlos en una nomenclatura estandar y clara dentro del
 * mismo.
 */
defined('_JEXEC') or die('Restricted access');

if (!JFactory::getUser()->authorise('core.manage', 'com_asom')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once(JPATH_COMPONENT.DS.'controller.php');
require_once(JPATH_COMPONENT.DS.'models'.DS.'model.php');

$input = JFactory::getApplication()->input;

if($input->getCmd('task') == '')
{
    JFactory::getApplication()->redirect('index.php?option=com_asom&task=orders.display');
}

$controller = JControllerLegacy::getInstance('Asom');
$controller->execute($input->getCmd('task'));
$controller->redirect();
