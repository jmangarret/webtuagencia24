<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: catalogo_planes.php 2012-09-10 18:29:50 svn $
 * @author Yusely Palacios
 * @package Joomla
 * @subpackage catalogo_planes
 * @license Copyright (c) 2012 Amadeus - All Rights Reserved
 *
 * 
 *
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport('joomla.application.component.model');

class CatalogoPlanesModelProductType extends JModel {

	function __construct() {
		parent::__construct();
    }

	/**
	 * Devuelve listado de tipos de productos instalados en el componente.
	 */
	function getActiveProductTypesInfo() {
		// Se obtiene el conector a base de datos
		$config	= JFactory::getConfig();
        $this->_dbprefix = $config->getValue('config.dbprefix');

        $query = 'SELECT * FROM #__cp_prm_product_type WHERE `published` = 1 ORDER BY product_type_name ASC';
        $this->_db->setQuery($query);
        $productTypes = $this->_db->loadObjectList();
        $res = array();
        $tablesAllowed = $this->_db->getTableList();
        // Ver qué productos tienen creadas sus tablas de información
        foreach ($productTypes as $key => $productType) {
        	$tableName = $this->_dbprefix . 'cp_' . $productType->product_type_code . '_info';
	        if (in_array($tableName, $tablesAllowed)) {
	            $res[] = $productType;
	        }
        }

        return $res;
	}

	/**
	 * Devuelve información de tipo de producto por id
	 */
	function getProductTypeById($id) {
		// Se obtiene el conector a base de datos
		$config	= JFactory::getConfig();
        $query = 'SELECT * FROM #__cp_prm_product_type WHERE `product_type_id` = ' . $id;
        $this->_db->setQuery($query);

        return $this->_db->loadObject();
	}
}
