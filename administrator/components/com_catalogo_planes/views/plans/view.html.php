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
jimport('joomla.html.form');

class CatalogoPlanesViewPlans extends JView {

function display ($tpl = null) {
        $option = JRequest::getCmd('option');
        JHTML::_('behavior.tooltip');

        $layout = $this->getLayout();
        switch ($layout) {
            case 'form':
                $data = $this->get('data');
                $isNew = ($data->product_id == null);

                // Obtiene título de la interfaz
                if (JRequest::getCmd('task') != 'add') {
                        $title = JText::_('EDIT');
                } else {
                        $title = JText::_('NEW');
                }
                $title .= ' ' . JText::_('CP.PLAN');
                // Se colocan los botones en la barra de herramientas
                if (!$isNew) {
                    $bar = & JToolBar::getInstance('toolbar');
                    $bar->appendButton('Link', 'stock', JText::_('CP.STOCK'), 'index.php?option=' . $option . '&view=plans&task=showstock&cid[]=' . $data->product_id);
                    $bar->appendButton('Link', 'rates', JText::_('CP.RATES'), 'index.php?option=' . $option . '&view=planrate&task=listrates&product_id=' . $data->product_id);
                    JToolBarHelper::divider();
                }
                JToolBarHelper::customX('save2new', 'save-new', '', JText::_('CP.SAVE_AND_NEW'), false);
                JToolBarHelper::divider();
                JToolBarHelper::save('save');
                JToolBarHelper::apply('apply');
                JToolBarHelper::cancel('cancel');

                JRequest::setVar('hidemainmenu', 1);

                // Cargue de parámetros básicos
                
                // Cargue de parámetros básicos
                $form = &JForm::getInstance('',JPATH_COMPONENT.DS.'models'.DS.'plans.xml');
                //$form = new JParameter('', JPATH_COMPONENT.DS.'models'.DS.'plans.xml');

                $document =& JFactory::getDocument();
                if ($isNew) {
                    $countFiles = 0;
                    $data->published = 1;
                    $createdate =& JFactory::getDate();
                    $data->tag_name1 = JText::_('CP.PRODUCT_TAG1_DESC');
                    $data->tag_name2 = JText::_('CP.PRODUCT_TAG2_DESC');
                    $data->tag_name3 = JText::_('CP.PRODUCT_TAG3_DESC');
                    $data->tag_name4 = JText::_('CP.PRODUCT_TAG4_DESC');
                    $data->tag_name5 = JText::_('CP.PRODUCT_TAG5_DESC');
                    $data->tag_name6 = JText::_('CP.PRODUCT_TAG6_DESC');
                    $data->disclaimer = JText::_('CP.PRODUCT_DISCLAIMER_DEFAULT_VALUE');
                    $form->setValue('publish_up','details',$createdate->toFormat('%Y-%m-%d'));
                } else {
                    $countFiles = count($data->mediafiles);
                    $form->setValue('publish_up','details', substr($data->publish_up, 0, 10));
                    $form->setValue('publish_down','details', substr($data->publish_down, 0, 10));
                }

                // Initialize media files script
                $document->addScriptDeclaration('var mediaCount = ' . $countFiles . '; var delText = "' . JText::_('CP.DELETE') . '"; 
                var siteURL = "' . JURI::root() . '"; var catalogueImagesURL = "' . COM_CATALOGO_PLANES_IMAGESDIR . '"; 
                var textareaLengthExceeded = "' . JText::_('CP.PRODUCT_ERROR_TEXTAREA_LENGTH_EXCEEDED') . '";');

                $data->latitude = ($data->latitude) ? $data->latitude: '';
                $data->longitude = ($data->longitude) ? $data->longitude: '';

                $component = JComponentHelper::getComponent($option)->params;
                $params=$component->get('params');
                $this->assignRef('params', $params);

                // Parámetros Básicos
                $tourismtypes = $data->tourismtypes;
                $form->setValue('productid','details', $data->product_id);
                $form->setValue('tourismtypes','details', implode(',', $tourismtypes));
                $form->setValue('featured','details', $data->featured);
                $form->setValue('product_desc','details', $data->product_desc);
                $form->setValue('additional_description','details', $data->additional_description);
                $form->setValue('product_code','details', $data->product_code);
                $form->setValue('disclaimer','details', $data->disclaimer);
                $form->setValue('access','details', $data->access);
                $form->setValue('category_id','details', $data->category_id);
                $form->setValue('supplier_id','details', $data->supplier_id);
                $form->setValue('published','details', $data->published);
                $form->setValue('duration','details', $data->duration);
                $form->setValue('days_total','details', $data->days_total);
                $form->setValue('latitude','details', $data->latitude);
                $form->setValue('longitude','details', $data->longitude);
                $form->setValue('product_url','details', $data->product_url);

                $this->assignRef('form', $form);
                $icon = 'plan.png';
                break;

            case 'default_stock':
                $data = $this->get('data');
                // Obtiene título de la interfaz
                $title = JText::_('CP.STOCK') . ' :: <a href="index.php?option=' . $option . '&view=plans&task=edit&cid[]=' . $data->product_id . '" class="product_link">' . $this->product_name . '</a>';
                $icon = 'stock.png';

                $component = JComponentHelper::getComponent($option)->params;
                $params=$component->get('params');
                $this->assignRef('params', $params);

                JToolBarHelper::save('save');
                JToolBarHelper::apply('apply');
                JToolBarHelper::cancel('cancel');

                JRequest::setVar('hidemainmenu', 1);
                break;

            default:
                // Se colocan los botones en la barra de herramientas
                $bar = & JToolBar::getInstance('toolbar');
                $bar->appendButton('Link', 'cpanel', JText::_('CP.PRINCIPAL_VIEW'), 'index.php?option=' . $option);
                JToolBarHelper::divider();
                // Agrega link a las preferencias del componente
                JToolBarHelper::preferences($option, '600', '600');
                JToolBarHelper::divider();
                JToolBarHelper::customX('showstock', 'stock', '', JText::_('CP.STOCK'), true);
                JToolBarHelper::customX('listrates', 'rates', '', JText::_('CP.RATES'), true);
                JToolBarHelper::divider();
                JToolBarHelper::publishList();
                JToolBarHelper::unpublishList();
                JToolBarHelper::divider();
                JToolBarHelper::customX('duplicate', 'copy', '', JText::_('CP.CLONE'), true);
                JToolBarHelper::deleteList(JText::_('CP.CONFIRM_DELETE'));
                JToolBarHelper::editListX();
                JToolBarHelper::addNewX();

                JSubMenuHelper::addEntry(JText::_('CP.PLANS'), 'index.php?option=' . $option . '&view=plans');
                JSubMenuHelper::addEntry(JText::_('CP.PLANPARAM1'), 'index.php?option=' . $option . '&view=planparam1');
                JSubMenuHelper::addEntry(JText::_('CP.PLANPARAM2'), 'index.php?option=' . $option . '&view=planparam2');
                JSubMenuHelper::addEntry(JText::_('CP.PLANPARAM3'), 'index.php?option=' . $option . '&view=planparam3');
                JSubMenuHelper::addEntry(JText::_('CP.PLANCATEGORY'), 'index.php?option=' . $option . '&view=plancategory');

                // Obtiene título de la interfaz
                $title = JText::_('CP.CATALOGOPLANES') . ' :: ' . JText::_('CP.PLANS');
                $icon = 'plan.png';
                break;
        }
    // Agrega título de la plantilla
    JToolBarHelper::title($title, $icon);

    $this->assignRef('viewName', $this->getName());

    parent::display($tpl);
    }
}
