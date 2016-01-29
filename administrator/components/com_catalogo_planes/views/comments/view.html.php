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

class CatalogoPlanesViewComments extends JView {

	function display ($tpl = null) {
		$option = JRequest::getCmd('option');
                JHTML::_('behavior.tooltip');

		$layout = $this->getLayout();
                $document =& JFactory::getDocument();
                // Se colocan los botones en la barra de herramientas
                $bar = & JToolBar::getInstance('toolbar');
                $bar->appendButton('Link', 'cpanel', JText::_('CP.PRINCIPAL_VIEW'), 'index.php?option=' . $option);
                JToolBarHelper::divider();
                // Agrega link a las preferencias del componente
                JToolBarHelper::preferences($option, '600', '600');
                JToolBarHelper::divider();
                $bar->appendButton('Link', 'send', JText::_('CP.COMMENTS_SEND_INVITATIONS_LINK'), 'index.php?option=' . $option . '&view=comments&task=sendinvitations');
                JToolBarHelper::divider();
                JToolBarHelper::publishList();
                JToolBarHelper::unpublishList();
                JToolBarHelper::divider();
                JToolBarHelper::deleteList(JText::_('CP.CONFIRM_DELETE'));

                JSubMenuHelper::addEntry(JText::_('CP.PRINCIPAL_VIEW'), 'index.php?option=' . $option);

                // Obtiene título de la interfaz
                $title = JText::_('CP.CATALOGOPLANES') . ' :: ' . JText::_('CP.COMMENTS');
                // Agrega título de la plantilla
		JToolBarHelper::title($title, 'comment.png');

		$this->assignRef('viewName', $this->getName());

		parent::display($tpl);
    }
}
