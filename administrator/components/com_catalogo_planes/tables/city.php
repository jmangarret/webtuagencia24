<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: city.php 2012-09-11 8:29:50 svn $
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
class TableCity extends BasicTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $city_id = null;
	/**
	 *
	 * @var string
	 */
	var $city_name = null;
	/**
	 *
	 * @var string
	 */
	var $city_code = null;
	/**
	 *
	 * @var int
	 */
	var $country_id = null;
	/**
	 *
	 * @var int
	 */
	var $region_id = null;
	/**
	 *
	 * @var int
	 */
	var $ordering = null;
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
		parent::__construct('#__cp_prm_city', 'city_id', $db);
	}


	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
	function check() {
        // Verificar que se de un nombre
        if (!$this->city_name) {
            $this->setError(JText::_('CP.CITY_ERROR_EMPTY_NAME'));
            return false;
        }

        // Verificar que no haya registros con el mismo nombre
		$query = 'SELECT ' . $this->_tbl_key . ' FROM ' . $this->_tbl;
		$query .= ' WHERE LOWER(city_name) = ' . $this->_db->Quote(strtolower($this->city_name)) . ' AND country_id = ' . (int) $this->country_id;
		$query .= ' AND region_id = ' . (int) $this->region_id . ' AND city_id != ' . (int) $this->city_id;
		$this->_db->setQuery($query);
		$id = $this->_db->loadResult();
		if ($id) {
			$this->setError(JText::_('CP.CITY_ERROR_DUPLICATE_NAME'));
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

		// Verificar que no haya productos con ese id de ciudad
		if ($id) {
			$modelProducts =& JModel::getInstance('producttype', 'CatalogoPlanesModel');
			$productTypes = $modelProducts->getActiveProductTypesInfo();
			if (is_array($productTypes)) {
				$queries = array();
				foreach ($productTypes as $type) {
					$queries[] = '(SELECT product_id FROM #__cp_' . $type->product_type_code . '_info WHERE city_id = ' . $id . ' LIMIT 1)';
				}
				$query = implode(' UNION ALL ', $queries);
				$this->_db->setQuery($query);
				$obj = $this->_db->loadObject();
				if ($obj) {
					$this->setError(JText::sprintf('CP.CITY_ERROR_DELETE_EXISTS_RELATED_PRODUCTS', $id));
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
        return parent::bind($from, $ignore, array('city_name', 'city_code'));
    }
}
