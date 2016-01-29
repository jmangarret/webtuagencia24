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

class CatalogoPlanesViewHotelParam1 extends JView {

function display ($tpl = null) {
        $option = JRequest::getCmd('option');
        JHTML::_('behavior.tooltip');

        $layout = $this->getLayout();
        if ($layout == 'form') {
                // Obtiene título de la interfaz
                if (JRequest::getCmd('task') != 'add') {
                        $title = JText::_('EDIT');
                } else {
                        $title = JText::_('NEW');
                }
                $title .= ' ' . JText::_('CP.HOTELPARAM1');
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
                // Agrega link a las preferencias del componente
                JToolBarHelper::preferences($option, '600', '600');
                JToolBarHelper::divider();
                JToolBarHelper::publishList();
                JToolBarHelper::unpublishList();
                JToolBarHelper::divider();
                JToolBarHelper::deleteList(JText::_('CP.CONFIRM_DELETE'));
                JToolBarHelper::editListX();
                JToolBarHelper::addNewX();

                JSubMenuHelper::addEntry(JText::_('CP.HOTELS'), 'index.php?option=' . $option . '&view=hotels');
                JSubMenuHelper::addEntry(JText::_('CP.HOTELPARAM1'), 'index.php?option=' . $option . '&view=hotelparam1');
                JSubMenuHelper::addEntry(JText::_('CP.HOTELPARAM2'), 'index.php?option=' . $option . '&view=hotelparam2');
                JSubMenuHelper::addEntry(JText::_('CP.HOTELPARAM3'), 'index.php?option=' . $option . '&view=hotelparam3');
                JSubMenuHelper::addEntry(JText::_('CP.HOTELCATEGORY'), 'index.php?option=' . $option . '&view=hotelcategory');
                JSubMenuHelper::addEntry(JText::_('CP.HOTELAMENITY'), 'index.php?option=' . $option . '&view=hotelamenity');

                // Obtiene título de la interfaz
                $title = JText::_('CP.CATALOGOPLANES') . ' :: ' . JText::_('CP.HOTELPARAM1');
        }
        // Agrega título de la plantilla
        JToolBarHelper::title($title, 'hotel.png');

        $this->assignRef('viewName', $this->getName());

        parent::display($tpl);
    }
}
