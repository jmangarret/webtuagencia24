<?php
/**
 * @file Amadeus/Amadeus.php
 * @ingroup _library
 * Clase informativa, que muestra la version de la libreria.
 */
defined( '_JEXEC' ) or die( 'Restricted access' ); 

/**
 * @brief Clase encargada de mostrar informacion relevante a la
 * version de la libreria Amadeus.
 */
class Amadeus
{

    /// Numero de version de la libreria.
    private static $_version = '1.0.1';

    /**
     * @brief Version de la libreria Actual. Esta version engloba todos
     * los cambios de los archivos contenidos.
     * @return string
     */
    function getVersion()
    {
        return self::$_version;
    }

}
