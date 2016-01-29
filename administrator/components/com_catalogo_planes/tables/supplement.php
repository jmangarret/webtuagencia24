<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: supplement.php 2012-09-11 8:29:50 svn $
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
class TableSupplement extends BasicTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $supplement_id = null;
    /**
     *
     * @var string
     */
    var $supplement_name = null;
    /**
     *
     * @var string
     */
    var $supplement_code = null;
    /**
     *
     * @var string
     */
    var $imageurl = null;
    /**
     *
     * @var string
     */
    var $description = null;
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
        $this->relations['producttypes']['referencedtable'] = '#__cp_prm_supplement_product_type';
        $this->relations['producttypes']['referencedfield'] = 'supplement_id';
        $this->relations['producttypes']['referencedtableid'] = 'product_type_id';
        $this->relations['producttypes']['extrainfotable'] = '#__cp_prm_product_type';
        $this->relations['producttypes']['extrainfoid'] = 'product_type_id';

        $this->relations['tourismtypes'] = array();
        $this->relations['tourismtypes']['referencedtable'] = '#__cp_prm_supplement_tourismtype';
        $this->relations['tourismtypes']['referencedfield'] = 'supplement_id';
        $this->relations['tourismtypes']['referencedtableid'] = 'tourismtype_id';
        $this->relations['tourismtypes']['extrainfotable'] = '#__cp_prm_tourismtype';
        $this->relations['tourismtypes']['extrainfoid'] = 'tourismtype_id';

        parent::__construct('#__cp_prm_supplement', 'supplement_id', $db);
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

        // Verificar que se de un nombre
		if (!$this->supplement_name) {
			$this->setError(JText::_('CP.SUPPLEMENT_ERROR_EMPTY_NAME'));
			return false;
		}

        // Verificar que se de un codigo
		if (!$this->supplement_code) {
			$this->setError(JText::_('CP.SUPPLEMENT_ERROR_EMPTY_CODE'));
			return false;
		}

		// Verificar que se seleccione al menos un tipo de producto
		if (empty($this->producttypes)) {
			$this->setError(JText::_('CP.SUPPLEMENT_ERROR_EMPTY_PRODUCT_TYPE'));
			return false;
		}

		// Verificar que se seleccione al menos un tipo de turismo
		if (empty($this->tourismtypes)) {
			$this->setError(JText::_('CP.SUPPLEMENT_ERROR_EMPTY_TOURISM_TYPE'));
			return false;
		}

        // Verificar que no haya registros con el mismo código
        $query = 'SELECT supplement_id FROM ' . $tableName . ' WHERE';
        $query .= ' LOWER(supplement_code) = ' . $this->_db->Quote(strtolower($this->supplement_code));
        $query .= ' AND supplement_id != ' . (int) $this->$key;
        $this->_db->setQuery($query);
        $id = $this->_db->loadResult();
        if ($id) {
            $this->setError(JText::_('CP.SUPPLEMENT_ERROR_DUPLICATE_CODE'));
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

		// Verificar que no haya productos con ese id de registro
        if ($id) {
        	$modelProducts =& JModel::getInstance('producttype', 'CatalogoPlanesModel');
        	$productTypes = $modelProducts->getActiveProductTypesInfo();
        	if (is_array($productTypes)) {
        		$queries = array();
        		foreach ($productTypes as $type) {
        			$queries[] = '(SELECT product_id FROM #__cp_' . $type->product_type_code . '_supplement WHERE supplement_id = ' . $id . ' LIMIT 1)';
        		}
        		$query = implode(' UNION ALL ', $queries);
        		$this->_db->setQuery($query);
        		$obj = $this->_db->loadObject();
        		if ($obj) {
        			$this->setError(JText::sprintf('CP.SUPPLEMENT_ERROR_DELETE_EXISTS_RELATED_PRODUCTS', $id));
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
        return parent::bind($from, $ignore, array('supplement_name', 'supplement_code', 'description'));
    }
}
