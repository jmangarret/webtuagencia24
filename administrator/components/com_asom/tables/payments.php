<?php
/**
 * @file com_sales/admin/tables/sl_gds.php
 * @ingroup _comp_adm
 * Archivo que contiene la descripcion de la tabla de reservas.
 */
defined('_JEXEC') or die("Invalid access");

/**
 * @brief Clase que mapea la tabla reservas de la base de datos
 */
class JTablePayments extends JTable
{

    /**
     * @brief Constructor de la clase
     * @param resource $db Conexion activa de la base de datos.
     */
    function __construct(&$db)
    {
        parent::__construct('#__aom_payments', 'id', $db);
    }

}
