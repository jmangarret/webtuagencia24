<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: daily_rate_job.php 2012-11-15 11:11:50 svn $
 * @author Yusely Palacios
 * @package Joomla
 * @subpackage catalogo_planes
 * @license Copyright (c) 2012 Amadeus - All Rights Reserved
 *
 *
 *
 */

// Inicializar las constantes necesarias para Joomla
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);
$dir = dirname(__FILE__);
define('JPATH_BASE', substr($dir, 0, stripos($dir, DS . 'administrator')));

// Archivos requeridos para usar el manejo de base de datos de Joomla
require_once (JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require_once (JPATH_BASE . DS . 'includes' . DS . 'framework.php');
require_once (JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'factory.php');
$mainframe =& JFactory::getApplication('administrator');
$db =& JFactory::getDBO();
// Se obtiene el conector a base de datos
$config = JFactory::getConfig();
$dbprefix = $config->getValue('config.dbprefix');

// Se usa el precio básico de los tarifarios.
// Colocar valor en 1 para calcularlo automáticamente basado en los precios de las vigencias
$calculatePrices = 0;

// Obtiene los tipos de productos activos que tienen tabla de precálculo creada
$query = 'SELECT p.product_type_code, r.last_date FROM #__cp_prm_product_type p LEFT JOIN';
$query .= ' #__cp_rate_resume_log r ON p.product_type_code = r.product_type_code WHERE p.`published` = 1';
$db->setQuery($query);
$results = $db->loadObjectList();
$productTypes = array();
// Se obtiene la lista de tablas creadas
$tablesAllowed = $db->getTableList();
foreach ($results as $row) {
	$tableName = $dbprefix . 'cp_' . $row->product_type_code . '_resume';
	if (in_array($tableName, $tablesAllowed)) {
		$productTypes[] = $row;
	}
}

if (!empty($productTypes)) {
	// Obtiene la fecha de un año más un día para el precálculo.
	$date = date('Y-m-d', strtotime('+366 days'));
	foreach ($productTypes as $row) {
        // Eliminar los precálculos de fechas pasadas
        $query = 'DELETE FROM #__cp_' . $row->product_type_code . '_resume WHERE `date` < CURDATE()';
		$db->setQuery($query);
		$db->query();

		// Ejecutar proceso para precálculo de los productos de este tipo
		if ($row->last_date && ($row->last_date < $date)) {
            $query = 'CALL Generate' . $row->product_type_code . 'RateResume(\'' . $row->last_date . '\', \'' . $date . '\', ' . $calculatePrices . ')';
		} else {
            $query = 'CALL Generate' . $row->product_type_code . 'RateResume(CURDATE(), \'' . $date . '\', ' . $calculatePrices . ')';
		}
		$db->setQuery($query);
		//$db->query();
		//*
		// Si se ejecutó el proceso correctamente.
		if (!$db->query()) {
			echo $db->getErrorMsg();
			echo '<br />';
		}
		//*/
	}
}


