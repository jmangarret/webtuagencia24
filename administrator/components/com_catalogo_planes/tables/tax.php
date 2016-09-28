<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: tax.php 2012-09-11 8:29:50 svn $
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
class TableTax extends BasicTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $tax_id = null;
	/**
	 *
	 * @var string
	 */
	var $tax_name = null;
	/**
	 *
	 * @var string
	 */
	var $tax_code = null;
	/**
	 *
	 * @var double
	 */
	var $tax_value = null;
	/**
	 *
	 * @var int
	 */
	var $iva = null;
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
        // Las relaciones siempre se deben definir antes de llamar al contructor del padre
        // Para una guía en definición de relaciones, ir a basictable.php
        $this->relations = array();
        $this->relations['producttypes'] = array();
        $this->relations['producttypes']['referencedtable'] = '#__cp_prm_tax_product_type';
        $this->relations['producttypes']['referencedfield'] = 'tax_id';
        $this->relations['producttypes']['referencedtableid'] = 'product_type_id';
        $this->relations['producttypes']['extrainfotable'] = '#__cp_prm_product_type';
        $this->relations['producttypes']['extrainfoid'] = 'product_type_id';

        parent::__construct('#__cp_prm_tax', 'tax_id', $db);
	}


	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
	function check() {
        $key = $this->getKeyName();
        $tableName = $this->getTableName();

        // Verificar que no haya registros con el mismo código
        $query = 'SELECT tax_id FROM ' . $tableName . ' WHERE';
        $query .= ' LOWER(tax_code) = ' . $this->_db->Quote(strtolower($this->tax_code));
        $query .= ' AND tax_id != ' . (int) $this->$key;
        $this->_db->setQuery($query);
        $id = $this->_db->loadResult();
        if ($id) {
            $this->setError(JText::_('CP.TAX_ERROR_DUPLICATE_CODE'));
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

        // Verificar que no haya productos ni suplementos relacionados con ese id de registro
        if ($id) {
        	$modelProducts =& JModel::getInstance('producttype', 'CatalogoPlanesModel');
        	$productTypes = $modelProducts->getActiveProductTypesInfo();
        	if (is_array($productTypes)) {
        		$queries = array();
        		foreach ($productTypes as $type) {
        			$queries[] = '(SELECT product_id FROM #__cp_' . $type->product_type_code . '_taxes WHERE tax_id = ' . $id . ' LIMIT 1)';
        		}
        		$query = implode(' UNION ALL ', $queries);
        		$this->_db->setQuery($query);
        		$obj = $this->_db->loadObject();
        		if ($obj) {
        			$this->setError(JText::sprintf('CP.TAX_ERROR_DELETE_EXISTS_RELATED_PRODUCTS', $id));
        			return false;
        		}

        		$queries = array();
        		foreach ($productTypes as $type) {
        			$queries[] = '(SELECT product_id FROM #__cp_' . $type->product_type_code . '_supplement_tax WHERE tax_id = ' . $id . ' LIMIT 1)';
        		}
        		$query = implode(' UNION ALL ', $queries);
        		$this->_db->setQuery($query);
        		$obj = $this->_db->loadObject();
        		if ($obj) {
        			$this->setError(JText::sprintf('CP.TAX_ERROR_DELETE_EXISTS_RELATED_SUPPLEMENT', $id));
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
        return parent::bind($from, $ignore, array('tax_name', 'tax_code'));
    }
}
