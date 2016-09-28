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

class CatalogoPlanesViewHotelRate extends JView {

    function display ($tpl = null) {
        $option = JRequest::getCmd('option');
        JHTML::_('behavior.tooltip');

        $layout = $this->getLayout();
        $product_link = '<a href="index.php?option=' . $option . '&view=hotels&task=edit&cid[]=' . $this->product_id . '" class="product_link">' . $this->product_name . '</a>';
        if ($layout == 'form') {
            // Obtiene título de la interfaz
            if (JRequest::getCmd('task') != 'add') {
                $title = JText::_('EDIT');
            } else {
                $title = JText::_('NEW');
            }
            // Se colocan los botones en la barra de herramientas
            JToolBarHelper::customX('save2new', 'save-new', '', JText::_('CP.SAVE_AND_NEW'), false);
            JToolBarHelper::divider();
            JToolBarHelper::save('save');
            JToolBarHelper::apply('apply');
            JToolBarHelper::cancel('cancel');

            JRequest::setVar('hidemainmenu', 1);

        } else {

            // Se colocan los botones en la barra de herramientas
            $bar = & JToolBar::getInstance('toolbar');
            $bar->appendButton('Link', 'cpanel', JText::_('CP.PRINCIPAL_VIEW'), 'index.php?option=' . $option);
            JToolBarHelper::divider();
            $bar->appendButton('Link', 'back', JText::_('CP.BACK'), 'index.php?option=' . $option . '&view=hotels');
            JToolBarHelper::divider();
            JToolBarHelper::deleteList(JText::_('CP.CONFIRM_DELETE'));
            JToolBarHelper::editListX();
            JToolBarHelper::addNewX();

            $title = '';

            JSubMenuHelper::addEntry(JText::_('CP.PRINCIPAL_VIEW'), 'index.php?option=' . $option);
        }
        // Obtiene título de la interfaz
        $title .= ' ' . JText::_('CP.RATES') . ' :: ' . $product_link;

        // Agrega título de la plantilla
        JToolBarHelper::title($title, 'rates.png');

        $this->assignRef('viewName', $this->getName());

        parent::display($tpl);
    }
}
