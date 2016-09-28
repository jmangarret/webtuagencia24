<?php
/**
 * @file com_rotator/admin/tables/am_banners.php
 * @ingroup _comp_adm
 * Archivo que contiene la descripcion de la tabla de banners.
 */
defined('_JEXEC') or die("Invalid access");

/**
 * @brief Clase que mapea la tabla rotadores de la base de datos
 */
class JTableAm_banners extends JTable
{

    /// Identificador único
    var $id          = null;

    /// ID del rotador al cual pertenece el banner
    var $rotator     = null;

    /// Titulo del banner
    var $title       = null;

    /// Descripcion del banner
    var $description = null;

    /// Texto que aparece en el boton del rotador
    var $button      = null;

    /// Link a donde debe redireccionar el banner
    var $link        = null;

    /// Clase sobre la cual se pueden aplicar los estilos
    var $class      = null;

    /// Imagen del banner
    var $image      = null;

    /// Nombre del archivo de imagen del thumbnail
    var $thumb      = null;

    /// Posición que ocupa el banner con respecto a los banners del mismo rotador
    var $orden      = null;

    /// Indica el estado de publicado del rotador
    var $published  = null;

    /**
     * @brief Constructor de la clase
     * @param resource $db Conexion activa de la base de datos.
     */
    function __construct(&$db)
    {
        parent::__construct( '#__am_banners', 'id', $db );
    }

}
