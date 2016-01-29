<?php
/**
 * @file com_rotator/admin/controller.php
 * @ingroup _comp_adm
 * Contiene la clase del controlador base, de esta heredan
 * los demas controladores
 */
defined( '_JEXEC' ) or die( 'Restricted access' ); 

jimport( 'Amadeus.Controller.Basic' );
require_once( JPATH_COMPONENT.DS.'toolbar.php' );

/**
 * @brief Clase base de la cual heredan los demas controladores, implementa
 * las funciones comunes a todos los controladores.
 */
class RotatorController extends AmadeusController
{

    public function __construct()
    {
        parent::__construct();

        $controller = JRequest::getCmd('controller');
        $this->addSubmenu($controller);
    }

    public static function addSubmenu($vName)
    {
        JSubMenuHelper::addEntry(
            JText::_('COM_ROTATOR_ROTATOR'),
            'index.php?option=com_rotator&controller=rotator',
            $vName == 'rotator' || $vName == ''
        );

        JSubMenuHelper::addEntry(
            JText::_('COM_ROTATOR_BANNER'),
            'index.php?option=com_rotator&controller=banner',
            $vName == 'banner'
        );
    }

}
