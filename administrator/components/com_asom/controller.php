<?php
/**
 * @file com_asom/admin/controller.php
 * @defgroup _comp_adm Componente (Administración)
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class AsomController extends JController
{

    public function __construct()
    {
        parent::__construct();

        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root(true).'/administrator/components/com_asom/css/style.css');
    }

    public function display()
    {/*
        $menus = array(
            'AOM_ORDERS'   => 'index.php?option=com_asom&task=orders.display',
            'AOM_STATUSES' => 'index.php?option=com_asom&task=statuses.display'
        );

        $uri        = JFactory::getURI();
        $currentURL = 'index.php'.$uri->toString(array('query'));

        foreach($menus as $menu => $link)
            JSubMenuHelper::addEntry(JText::_($menu), $link, strncmp($currentURL, $link, strlen($link) - 8) == 0);
        */
        parent::display();
    }

}
