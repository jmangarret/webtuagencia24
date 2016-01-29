<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: view.html.php 2012-09-10 18:29:50 svn $
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

// Import Joomla! libraries
jimport('joomla.application.component.view');

class CatalogoPlanesViewPanel extends JView {

    function display ($tpl = null) {
    $option = JRequest::getCmd('option');;
    //print_r($option);die();
    JHTML::_('behavior.tooltip');

            // Agrega título de la plantilla
            JToolBarHelper::title(JText::_('CP.CATALOGOPLANES'), 'generic.png');

            // Agrega link a las preferencias del componente
            JToolBarHelper::preferences($option, '600', '700');

            // Se obtiene el modelo
            $model =& $this->getModel('panel');

            // Se obtiene los tipos de productos instalados y se asignan a la plantilla
            $this->assignRef('productTypes', $model->getProductTypes());
            parent::display($tpl);
    }
    /**
    * render Information module
    */
    protected function renderInformation () {
          $output = '';
          //$panelStates = $this->get('panelStates');
          $this->assignRef('sysInfo', $this->panelStates['system']);
          return $this->loadTemplate('information');
    }
    /**
    * render Information module
    */
    protected function renderversion () {
          $output = '';
          //$panelStates = $this->get('panelStates');
          $this->assignRef('sysInfo', $this->panelStates['system']);
          return $this->loadTemplate('version');
    }
    /**
    * render Information module
    */
    protected function rendercopyright () {
          $output = '';
          //$panelStates = $this->get('panelStates');
          $this->assignRef('sysInfo', $this->panelStates['system']);
          return $this->loadTemplate('copyright');
    }
}
