<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: category.php 2012-09-11 8:29:50 svn $
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
class TableTourCategory extends BasicTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $category_id = null;
	/**
	 *
	 * @var string
	 */
	var $category_name = null;
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
		parent::__construct('#__cp_prm_tours_category', 'category_id', $db);
	}


	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
	function check() {
		// Verifitour que se de un nombre
		if (!$this->category_name) {
			$this->setError(JText::_('CP.TOURCATEGORY_ERROR_EMPTY_NAME'));
			return false;
		}

		$key = $this->getKeyName();
		$tableName = $this->getTableName();
		// Verifitour que no haya registros con el mismo nombre
		$query = 'SELECT ' . $key . ' FROM ' . $tableName . ' WHERE';
		$query .= ' LOWER(category_name) = ' . $this->_db->Quote(strtolower($this->category_name));
		$query .= ' AND category_id != ' . (int) $this->$key;
		$this->_db->setQuery($query);
		$id = $this->_db->loadResult();
		if ($id) {
			$this->setError(JText::_('CP.TOURCATEGORY_ERROR_DUPLICATE_NAME'));
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

        // Verifitour que no haya productos con ese id de registro
        if ($id) {
        	// Verifitour que no haya productos con ese id de categoría
        	$query = 'SELECT product_id FROM #__cp_tours_info WHERE category_id = ' . $id . ' LIMIT 1';
        	$this->_db->setQuery($query);
        	$obj = $this->_db->loadObject();
        	if ($obj) {
        		$this->setError(JText::sprintf('CP.TOURCATEGORY_ERROR_DELETE_EXISTS_RELATED_PRODUCTS', $id));
        		return false;
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
        return parent::bind($from, $ignore, array('category_name'));
    }
}
