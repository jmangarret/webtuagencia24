<?php
/**
 * @file com_rotator/admin/tables/am_rotadores.php
 * @ingroup _comp_adm
 * Archivo que contiene la descripcion de la tabla de rotadores.
 */
defined('_JEXEC') or die("Invalid access");

/**
 * @brief Clase que mapea la tabla rotadores de la base de datos
 */
class JTableAm_rotadores extends JTable
{

    /// Identificador único
    var $id         = null;

    /// Nombre del Rotador, para identificarlo
    var $nombre     = null;

    /// Ancho que va a tener las imágenes de ese carrusel
    var $width      = null;

    /// Alto que va a tener las imágenes de ese carrusel
    var $height     = null;

    /// Clase sobre la cual se pueden aplicar los estilos
    var $class      = null;

    /// Indica si usa una imagen de muestra
    var $thumb      = null;

    /// Ancho del Thumbnail
    var $twidth     = null;

    /// Alto del Thumbnail
    var $theight    = null;

    /// Indica el estado de publicado del rotador
    var $published  = null;

    /// CSS definido para ese carrusel
    var $css        = null;


    /**
     * @brief Constructor de la clase
     * @param resource $db Conexion activa de la base de datos.
     */
    function __construct(&$db)
    {
        parent::__construct( '#__am_rotadores', 'id', $db );
    }

}
