<?php
/**
 * @file com_rotator/admin/admin.rotator.php
 * @defgroup _comp_adm Componente (Administración)
 * Archivo de entrada del componente en su parte administrativa.
 *
 * @mainpage Componente Rotador Plus
 * @section Introducción
 *
 * Componente que permite la administración de diferentes rotadores en el sitio.
 * Permite crear el rotador, y configurar cada una de los banners a mostrar, dando
 * la posibilidad de editar la imagen, el título, el link, el detalle y demas valores
 * necesario para crear un banner.
 *
 * Ademas también ofrece la posibilidad de editar las imágenes directamente desde
 * la administración, para hacer el proceso de creación de banner mas fácil y
 * productivo.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JPATH_COMPONENT.DS.'controller.php' );

if ($controller = JRequest::getWord('controller'))
{
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path))
    {
        require_once $path;
    }
    else
    {
        $controller = '';
    }
}
else
{
    $controller = 'rotator';
    require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php' );
}

$classname  = 'RotatorController'.ucfirst($controller);
$controller = new $classname();
$controller->execute( JRequest::getVar( 'task' ) );
$controller->redirect();
