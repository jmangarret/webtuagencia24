<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: region.php 2012-09-11 8:29:50 svn $
 * @author Yusely Palacios
 * @package Joomla
 * @subpackage catalogo_planes
 * @license Copyright (c) 2012 Amadeus - All Rights Reserved
 *
 * 
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Incluir la clase básica para tablas
require_once(JPATH_COMPONENT.DS.'tables'.DS.'basictable.php');

/**
* Table class
*
* @package          Joomla
* @subpackage		catalogo_planes
*/
class TableRegion extends BasicTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $region_id = null;
	/**
	 *
	 * @var string
	 */
	var $region_name = null;
	/**
	 *
	 * @var string
	 */
	var $region_code = null;
	/**
	 *
	 * @var int
	 */
	var $country_id = null;
	/**
	 *
	 * @var int
	 */
	var $published = null;


    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__cp_prm_region', 'region_id', $db);
	}


	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
	function check() {
		// Verificar que se de un nombre
		if (!$this->region_name) {
			$this->setError(JText::_('CP.REGION_ERROR_EMPTY_NAME'));
			return false;
		}

		// Verificar que no haya países con el mismo nombre
		$query = 'SELECT ' . $this->_tbl_key . ' FROM ' . $this->_tbl;
		$query .= ' WHERE LOWER(region_name) = ' . $this->_db->Quote(strtolower($this->region_name));
		$query .= ' AND country_id = ' . (int) $this->country_id . ' AND region_id != ' . (int) $this->region_id;
		$this->_db->setQuery($query);
		$id = $this->_db->loadResult();
		if ($id) {
			$this->setError(JText::_('CP.REGION_ERROR_DUPLICATE_NAME'));
			return false;
		}

		/*
		// Verificar que no haya países con el mismo código
		$query = 'SELECT ' . $this->_tbl_key . ' FROM ' . $this->_tbl;
		$query .= ' WHERE LOWER(region_code) = ' . $this->_db->Quote(strtolower($this->region_code)) . ' AND region_id != ' . (int) $this->region_id;
		$this->_db->setQuery($query);
		$id = $this->_db->loadResult();
		if ($id) {
			$this->setError(JText::_('CP.REGION_ERROR_DUPLICATE_CODE'));
			return false;
		}*/

		return true;
	}


	/**
	 * Borra registro, verificando que no haya problemas de integridad referencial
	 *
	 * @return true if successful otherwise returns false
	 */
	function delete($oid = null) {
        $key = $this->getKeyName();
        if ($oid) {
            $this->$key = $oid;
        }
        $id = $this->$key;

        if ($id) {
        	$tableName = $this->getTableName();

        	// Verificar que no haya ciudades con ese id de región
        	$table =& JTable::getInstance('city', 'Table');
        	$foreignKeyTable = $table->getTableName();
        	$query = 'SELECT a.region_id FROM ' . $tableName . ' a ';
        	$query .= 'JOIN ' . $foreignKeyTable . ' b ON a.region_id = b.region_id';
        	$query .= ' WHERE a.region_id = ' . $id . ' GROUP BY a.region_id';
        	$this->_db->setQuery($query);
        	$obj = $this->_db->loadObject();
        	if ($obj) {
        		$this->setError(JText::sprintf('CP.REGION_ERROR_DELETE_EXISTS_RELATED_CITY', $id));
        		return false;
        	}

        	// Verificar que no haya productos con ese id de región
        	$modelProducts =& JModel::getInstance('producttype', 'CatalogoPlanesModel');
        	$productTypes = $modelProducts->getActiveProductTypesInfo();
        	if (is_array($productTypes)) {
        		$queries = array();
        		foreach ($productTypes as $type) {
        			$queries[] = '(SELECT product_id FROM #__cp_' . $type->product_type_code . '_info WHERE region_id = ' . $id . ' LIMIT 1)';
        		}
        		$query = implode(' UNION ALL ', $queries);
        		$this->_db->setQuery($query);
        		$obj = $this->_db->loadObject();
        		if ($obj) {
        			$this->setError(JText::sprintf('CP.REGION_ERROR_DELETE_EXISTS_RELATED_PRODUCTS', $id));
        			return false;
        		}
        	}
		}

		return parent::delete($oid);
	}


    /**
     * Binds a named array/hash to this object
     *
     * Can be overloaded/supplemented by the child class
     *
     * @access  public
     * @param   $from   mixed   An associative array or object
     * @param   $ignore mixed   An array or space separated list of fields not to bind
     * @return  boolean
     */
    public function bind($from, $ignore = array()) {
        return parent::bind($from, $ignore, array('region_name', 'region_code'));
    }
}
