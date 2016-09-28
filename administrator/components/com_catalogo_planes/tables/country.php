<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: country.php 2012-09-11 8:29:50 svn $
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
class TableCountry extends BasicTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $country_id = null;
	/**
	 *
	 * @var string
	 */
	var $country_name = null;
	/**
	 *
	 * @var string
	 */
	var $country_code = null;
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
		parent::__construct('#__cp_prm_country', 'country_id', $db);
	}


	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
	function check() {
        // Verificar que se de un nombre
        if (!$this->country_name) {
            $this->setError(JText::_('CP.COUNTRY_ERROR_EMPTY_NAME'));
            return false;
        }

        // Verificar que se de un codigo
        if (!$this->country_code) {
            $this->setError(JText::_('CP.COUNTRY_ERROR_EMPTY_CODE'));
            return false;
        }

		$key = $this->getKeyName();
		$tableName = $this->getTableName();
		// Verificar que no haya registros con el mismo nombre
		$query = 'SELECT country_id FROM ' . $tableName . ' WHERE';
		$query .= ' LOWER(country_name) = ' . $this->_db->Quote(strtolower($this->country_name));
		$query .= ' AND country_id != ' . (int) $this->$key;
		$this->_db->setQuery($query);
		$id = $this->_db->loadResult();
		if ($id) {
			$this->setError(JText::_('CP.COUNTRY_ERROR_DUPLICATE_NAME'));
			return false;
		}

		// Verificar que no haya registros con el mismo código
		$query = 'SELECT country_id FROM ' . $tableName . ' WHERE';
		$query .= ' LOWER(country_code) = ' . $this->_db->Quote(strtolower($this->country_code));
		$query .= ' AND country_id != ' . (int) $this->$key;
		$this->_db->setQuery($query);
		$id = $this->_db->loadResult();
		if ($id) {
			$this->setError(JText::_('CP.COUNTRY_ERROR_DUPLICATE_CODE'));
			return false;
		}

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

        	// Verificar que no haya regiones con ese id de país
        	$table =& JTable::getInstance('region', 'Table');
        	$foreignKeyTable = $table->getTableName();
        	$query = 'SELECT a.country_id FROM ' . $tableName . ' a ';
        	$query .= 'JOIN ' . $foreignKeyTable . ' b ON a.country_id = b.country_id';
        	$query .= ' WHERE a.country_id = ' . $id . ' GROUP BY a.country_id';
        	$this->_db->setQuery($query);
        	$obj = $this->_db->loadObject();
        	if ($obj) {
        		$this->setError(JText::sprintf('CP.COUNTRY_ERROR_DELETE_EXISTS_RELATED_REGION', $id));
        		return false;
        	}

        	// Verificar que no haya ciudades con ese id de país
        	$table =& JTable::getInstance('city', 'Table');
        	$foreignKeyTable = $table->getTableName();
        	$query = 'SELECT a.country_id FROM ' . $tableName . ' a ';
        	$query .= 'JOIN ' . $foreignKeyTable . ' b ON a.country_id = b.country_id';
        	$query .= ' WHERE a.country_id = ' . $id . ' GROUP BY a.country_id';
        	$this->_db->setQuery($query);
        	$obj = $this->_db->loadObject();
        	if ($obj) {
        		$this->setError(JText::sprintf('CP.COUNTRY_ERROR_DELETE_EXISTS_RELATED_CITY', $id));
        		return false;
        	}

        	// Verificar que no haya productos con ese id de país
        	$modelProducts =& JModel::getInstance('producttype', 'CatalogoPlanesModel');
        	$productTypes = $modelProducts->getActiveProductTypesInfo();
        	if (is_array($productTypes)) {
        		$queries = array();
        		foreach ($productTypes as $type) {
        			$queries[] = '(SELECT product_id FROM #__cp_' . $type->product_type_code . '_info WHERE country_id = ' . $id . ' LIMIT 1)';
        		}
        		$query = implode(' UNION ALL ', $queries);
        		$this->_db->setQuery($query);
        		$obj = $this->_db->loadObject();
        		if ($obj) {
        			$this->setError(JText::sprintf('CP.COUNTRY_ERROR_DELETE_EXISTS_RELATED_PRODUCTS', $id));
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
        return parent::bind($from, $ignore, array('country_name', 'country_code'));
    }
}
