<?php
/**
 * @file com_rotator/admin/toolbar.php
 * @ingroup _comp_adm
 * Archivo con la definicion de las diferentes barras de herramientas.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * @brief Clase que contiene cada una de las barras de Herramientas a usar en el componente.
 */
class Toolbar {

    /**
     * @brief Barra de Herramientas para el listado de Rotadores.
     */
    function listRotator(){
        JToolBarHelper::title( JText::_( 'ADMINISTRATOR_ROTATOR_LIST' ), 'module.png' );
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::divider();
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
        JToolBarHelper::preferences('com_rotator');
    }

    /**
     * @brief Barra de herraminetas para la edicion de Rotadores.
     */
    function editRotator()
    {
        JToolBarHelper::title( JText::_( 'ADMINISTRATOR_ROTATOR_EDIT' ), 'module.png' );
        JToolBarHelper::save();
        JToolBarHelper::apply();
        JToolBarHelper::cancel();
    }

    /**
     * @brief Barra de Herramientas para el listado de Banners.
     */
    function listBanner(){
        JToolBarHelper::title( JText::_( 'ADMINISTRATOR_BANNER_LIST' ), 'module.png' );
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::divider();
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
        JToolBarHelper::preferences('com_rotator');
    }

    /**
     * @brief Barra de herraminetas para la seleccion de Rotadores.
     */
    function selectRotator()
    {
        JToolBarHelper::title( JText::_( 'ADMINISTRATOR_SELECT_ROTATOR' ), 'module.png' );
        JToolBarHelper::cancel();
    }

    /**
     * @brief Barra de herraminetas para la edicion de Banners.
     */
    function editBanner()
    {
        JToolBarHelper::title( JText::_( 'ADMINISTRATOR_BANNER_EDIT' ), 'module.png' );
        JToolBarHelper::save();
        JToolBarHelper::apply();
        JToolBarHelper::cancel();
    }
}
