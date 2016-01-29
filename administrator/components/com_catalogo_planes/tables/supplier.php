<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: supplier.php 2012-09-11 8:29:50 svn $
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
class TableSupplier extends BasicTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $supplier_id = null;
	/**
	 *
	 * @var string
	 */
	var $supplier_name = null;
	/**
	 *
	 * @var string
	 */
	var $supplier_code = null;
    /**
     *
     * @var string
     */
    var $phone = null;
	/**
	 *
	 * @var string
	 */
	var $fax = null;
    /**
     *
     * @var string
     */
    var $url = null;
    /**
     *
     * @var string
     */
    var $email = null;
	/**
	 *
	 * @var int
	 */
	var $country_id = null;
    /**
     *
     * @var int
     */
    var $city_id = null;
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
        $this->relations['producttypes']['referencedtable'] = '#__cp_prm_supplier_product_type';
        $this->relations['producttypes']['referencedfield'] = 'supplier_id';
        $this->relations['producttypes']['referencedtableid'] = 'product_type_id';
        $this->relations['producttypes']['extrainfotable'] = '#__cp_prm_product_type';
        $this->relations['producttypes']['extrainfoid'] = 'product_type_id';

        parent::__construct('#__cp_prm_supplier', 'supplier_id', $db);
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
        $query = 'SELECT supplier_id FROM ' . $tableName . ' WHERE';
        $query .= ' LOWER(supplier_code) = ' . $this->_db->Quote(strtolower($this->supplier_code));
        $query .= ' AND supplier_id != ' . (int) $this->$key;
        $this->_db->setQuery($query);
        $id = $this->_db->loadResult();
        if ($id) {
            $this->setError(JText::_('CP.SUPPLIER_ERROR_DUPLICATE_CODE'));
            return false;
        }

        return true;
	}


    /**
     * Inserts a new row if id is zero or updates an existing row in the database table
     *
     *
     * @access public
     * @param boolean If false, null object variables are not updated
     * @param boolean If false, relationships aren't updated
     * @return null|string null if successful otherwise returns and error message
     */
    function store($updateNulls = false, $updateRelationships = true) {
        $key = $this->getKeyName();
        $id = $this->$key;

        // Si el proveedor no es nuevo y se está guardando la información completa con estado inactivo, inactivar los productos asociados 
        if ($updateRelationships && $id && $this->published < 1) {
	        $modelProducts =& JModel::getInstance('producttype', 'CatalogoPlanesModel');
	        $productTypes = $modelProducts->getActiveProductTypesInfo();
	        if (is_array($productTypes)) {
	            foreach ($productTypes as $type) {
	                $query = 'UPDATE #__cp_' . $type->product_type_code . '_info SET `published` = 0 WHERE `' . $key . '` = ' . $id;
                    $this->_db->setQuery($query);
	                if (!$this->_db->query()) {
	                    $this->setError(JText::sprintf('CP.SUPPLIER_ERROR_UNPUBLISH_RELATED_PRODUCTS', $this->supplier_name));
	                    return false;
	                }
	            }
	        }
        }

        $result = parent::store($updateNulls, $updateRelationships);

        return $result;
    }


    /**
     * Publica/despublica registros que no estén bloqueados (published = 2)
     *
     * @access public
     * @param array An array of id numbers
     * @param integer 0 if unpublishing, 1 if publishing
     * @param integer The id of the user performnig the operation
     */
    public function publish($cid = null, $publish = 1, $user_id = 0) {
    	$k = $this->_tbl_key;
        if (count($cid) < 1) {
            if ($this->$k) {
                $cid = array($this->$k);
            } else {
                $this->setError("No items selected.");
                return false;
            }
        }

        $publish = (int) $publish;

        // Despublicar el proveedor
        $result = parent::publish($cid, $publish, $user_id);

        // Si el nuevo estado es inactivo, inactivar los productos asociados 
        if ($result && $publish < 1) {
            $modelProducts =& JModel::getInstance('producttype', 'CatalogoPlanesModel');
            $productTypes = $modelProducts->getActiveProductTypesInfo();
            if (is_array($productTypes)) {
            	$cids = $k . '=' . implode(' OR ' . $k . '=', $cid);
                foreach ($productTypes as $type) {
                    $query = 'UPDATE #__cp_' . $type->product_type_code . '_info SET `published` = 0 WHERE (' . $cids . ')';
                    $this->_db->setQuery($query);
                    if (!$this->_db->query()) {
                        $this->setError(JText::sprintf('CP.SUPPLIER_ERROR_UNPUBLISH_RELATED_PRODUCTS', ''));
                        return false;
                    }
                }
            }
        }
        return $result;
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

        // Verificar que no haya productos con ese id de registro
        if ($id) {
        	$modelProducts =& JModel::getInstance('producttype', 'CatalogoPlanesModel');
        	$productTypes = $modelProducts->getActiveProductTypesInfo();
        	if (is_array($productTypes)) {
        		$queries = array();
        		foreach ($productTypes as $type) {
        			$queries[] = '(SELECT product_id FROM #__cp_' . $type->product_type_code . '_info WHERE supplier_id = ' . $id . ' LIMIT 1)';
        		}
        		$query = implode(' UNION ALL ', $queries);
        		$this->_db->setQuery($query);
        		$obj = $this->_db->loadObject();
        		if ($obj) {
        			$this->setError(JText::sprintf('CP.SUPPLIER_ERROR_DELETE_EXISTS_RELATED_PRODUCTS', $id));
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
        return parent::bind($from, $ignore, array('supplier_name', 'supplier_code', 'phone', 'fax', 'url', 'email'));
    }
}
