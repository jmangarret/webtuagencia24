<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: transfers.php 2012-09-11 8:29:50 svn $
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
class TableTransfers extends BasicTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $product_id = null;
	/**
	 *
	 * @var string
	 */
	var $product_name = null;
    /**
     *
     * @var string
     */
    var $product_code = null;
    /**
     *
     * @var string
     */
    var $product_desc = null;
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
    var $city_id = null;
    /**
     *
     * @var int
     */
    var $featured = 0;
    /**
     *
     * @var string
     */
    var $latitude = null;
    /**
     *
     * @var string
     */
    var $longitude = null;
    /**
     *
     * @var int
     */
    var $ordering = null;
    /**
     *
     * @var datetime
     */
    var $publish_up = "0000-00-00 00:00:00";
    /**
     *
     * @var datetime
     */
    var $publish_down = "0000-00-00 00:00:00";
    /**
     *
     * @var datetime
     */
    var $created = 0;
    /**
     *
     * @var int
     */
    var $created_by = "0000-00-00 00:00:00";
    /**
     *
     * @var datetime
     */
    var $modified = 0;
    /**
     *
     * @var int
     */
    var $modified_by = "0000-00-00 00:00:00";
    /**
     *
     * @var int
     */
    var $access = 0;
    /**
     *
     * @var int
     */
    var $published = null;
    /**
     *
     * @var string
     */
    var $tag_name1 = null;
    /**
     *
     * @var string
     */
    var $tag_content1 = null;
    /**
     *
     * @var string
     */
    var $tag_name2 = null;
    /**
     *
     * @var string
     */
    var $tag_content2 = null;
    /**
     *
     * @var string
     */
    var $tag_name3 = null;
    /**
     *
     * @var string
     */
    var $tag_content3 = null;
    /**
     *
     * @var string
     */
    var $tag_name4 = null;
    /**
     *
     * @var string
     */
    var $tag_content4 = null;
    /**
     *
     * @var string
     */
    var $tag_name5 = null;
    /**
     *
     * @var string
     */
    var $tag_content5 = null;
    /**
     *
     * @var string
     */
    var $tag_name6 = null;
    /**
     *
     * @var string
     */
    var $tag_content6 = null;
    /**
     *
     * @var string
     */
    var $product_url = null;
    /**
     *
     * @var int
     */
    var $supplier_id = null;
    /**
     *
     * @var int
     */
    var $average_rating = null;
    /**
     *
     * @var string
     */
    var $additional_description = null;
    /**
     *
     * @var string
     */
    var $disclaimer = null;


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
        $this->relations['categories'] = array();
        $this->relations['categories']['referencedtable'] = '#__cp_transfers_category';
        $this->relations['categories']['referencedfield'] = 'product_id';
        $this->relations['categories']['referencedtableid'] = 'category_id';

        $this->relations['mediafiles'] = array();
        $this->relations['mediafiles']['referencedtable'] = '#__cp_transfers_files';
        $this->relations['mediafiles']['referencedfield'] = 'product_id';
        $this->relations['mediafiles']['extrainfofields'] = array('file_url', 'ordering');
        $this->relations['mediafiles']['manualinsert'] = true;
        $this->relations['mediafiles']['orderby'] = array('ordering');

        $this->relations['rateparam1'] = array();
        $this->relations['rateparam1']['referencedtable'] = '#__cp_transfers_param1';
        $this->relations['rateparam1']['referencedfield'] = 'product_id';
        $this->relations['rateparam1']['referencedtableid'] = 'param1_id';

        $this->relations['rateparam2'] = array();
        $this->relations['rateparam2']['referencedtable'] = '#__cp_transfers_param2';
        $this->relations['rateparam2']['referencedfield'] = 'product_id';
        $this->relations['rateparam2']['referencedtableid'] = 'param2_id';

        $this->relations['rateparam3'] = array();
        $this->relations['rateparam3']['referencedtable'] = '#__cp_transfers_param3';
        $this->relations['rateparam3']['referencedfield'] = 'product_id';
        $this->relations['rateparam3']['referencedtableid'] = 'param3_id';

        $this->relations['taxes'] = array();
        $this->relations['taxes']['referencedtable'] = '#__cp_transfers_taxes';
        $this->relations['taxes']['referencedfield'] = 'product_id';
        $this->relations['taxes']['referencedtableid'] = 'tax_id';
        $this->relations['taxes']['extrainfotable'] = '#__cp_prm_tax';
        $this->relations['taxes']['extrainfoid'] = 'tax_id';
        $this->relations['taxes']['extrainfofields'] = array('tax_id');
        $this->relations['taxes']['orderby'] = array('tax_name');

        $this->relations['supplements'] = array();
        $this->relations['supplements']['referencedtable'] = '#__cp_transfers_supplement';
        $this->relations['supplements']['referencedfield'] = 'product_id';
        $this->relations['supplements']['referencedtableid'] = 'supplement_id';
        $this->relations['supplements']['selectable'] = false;

        // Nombre raro para que no la relacione al hacer parent::bind();
        // No se consulta ni inserta desde la aplicación pero se coloca para que 
        // borre los registros relacionados cuando se borra el producto
        $this->relations['supps_taxes123'] = array();
        $this->relations['supps_taxes123']['referencedtable'] = '#__cp_transfers_supplement_tax';
        $this->relations['supps_taxes123']['referencedfield'] = 'product_id';
        $this->relations['supps_taxes123']['selectable'] = false;
        $this->relations['supps_taxes123']['manualinsert'] = true;

        // No se consulta ni inserta desde la aplicación pero se coloca para que 
        // borre los registros relacionados cuando se borra el producto
        $this->relations['resume'] = array();
        $this->relations['resume']['referencedtable'] = '#__cp_transfers_resume';
        $this->relations['resume']['referencedfield'] = 'product_id';
        $this->relations['resume']['selectable'] = false;
        $this->relations['resume']['manualinsert'] = true;

        // No se consulta ni inserta desde la aplicación pero se coloca para que 
        // borre los registros relacionados cuando se borra el producto
        $this->relations['stock'] = array();
        $this->relations['stock']['referencedtable'] = '#__cp_transfers_stock';
        $this->relations['stock']['referencedfield'] = 'product_id';
        $this->relations['stock']['selectable'] = false;
        $this->relations['stock']['manualinsert'] = true;

        // No se consulta ni inserta desde la aplicación pero se coloca para que 
        // borre los registros relacionados cuando se borra el producto
        $this->relations['comments'] = array();
        $this->relations['comments']['referencedtable'] = '#__cp_transfers_comments';
        $this->relations['comments']['referencedfield'] = 'product_id';
        $this->relations['comments']['selectable'] = false;
        $this->relations['comments']['manualinsert'] = true;

        // Guarda relación de suplementos, impuestos y productos
        $this->supplement_taxes = array();

        parent::__construct('#__cp_transfers_info', 'product_id', $db);
	}


    /**
     * Overloaded check method to ensure data integrity
     *
     * @access public
     * @return boolean True on success
     */
    function check() {
        // Verificar que se de un nombre
        if (!$this->product_name) {
            $this->setError(JText::_('CP.PRODUCT_ERROR_EMPTY_NAME'));
            return false;
        }

        // Verificar que se de un codigo
        if (!$this->product_code) {
            $this->setError(JText::_('CP.PRODUCT_ERROR_EMPTY_CODE'));
            return false;
        }

        // Verificar que se de un país
        if (!$this->country_id) {
            $this->setError(JText::_('CP.PRODUCT_ERROR_EMPTY_COUNTRY'));
            return false;
        }

        // Verificar que se de una ciudad
        if (!$this->city_id) {
            $this->setError(JText::_('CP.PRODUCT_ERROR_EMPTY_CITY'));
            return false;
        }

        // Verificar que se de la descripción corta
        if (!$this->product_desc) {
            $this->setError(JText::_('CP.PRODUCT_ERROR_EMPTY_SHORT_DESCRIPTION'));
            return false;
        }

        // Verificar que no haya registros con el mismo nombre
        $query = 'SELECT ' . $this->_tbl_key . ' FROM ' . $this->_tbl;
        $query .= ' WHERE LOWER(product_name) = ' . $this->_db->Quote(strtolower($this->product_name)) . ' AND country_id = ' . (int) $this->country_id;
        $query .= ' AND city_id = ' . (int) $this->city_id . ' AND product_id != ' . (int) $this->product_id;
        $this->_db->setQuery($query);
        $id = $this->_db->loadResult();
        if ($id) {
                $this->setError(JText::_('CP.TRANSFER_ERROR_DUPLICATE_NAME'));
                return false;
        }

        // Verificar que no haya registros con el mismo código
        $query = 'SELECT ' . $this->_tbl_key . ' FROM ' . $this->_tbl;
        $query .= ' WHERE LOWER(product_code) = ' . $this->_db->Quote(strtolower($this->product_code)) . ' AND product_id != ' . (int) $this->product_id;
        $this->_db->setQuery($query);
        $id = $this->_db->loadResult();
        if ($id) {
                $this->setError(JText::_('CP.TRANSFER_ERROR_DUPLICATE_CODE'));
                return false;
        }

        return true;
    }


    /**
     * Duplicar registro
     *
     * @return int Id del nuevo producto ó cero en caso de error
     */
    function duplicate($cid) {
        if ($cid > 0) {
        	$user =& JFactory::getUser();
            $query = 'SELECT CLONETRANSFER(' . $cid . ', \'' . JText::_('CP.PRODUCT_DUPLICATE_NAME_PREFIX') . '\', ' . $user->get('id') . ', \''.date("mdHis").'\')';
            $this->_db->setQuery($query);
            $product_id = $this->_db->loadResult();

            // Si no hay un id de producto nuevo, recuperar el error
            if ($product_id < 1) {
                $this->setError($this->_db->getErrorMsg());
            }
        } else {
            $product_id = 0;
        }

        return $product_id;
    }


    function store($updateNulls = false, $updateRelationships = true) {
        $result = parent::store($updateNulls, $updateRelationships);

        $key = $this->getKeyName();
        $id = $this->$key;

        // Borrar los tarifarios que usan parámetros o suplementos que ya no están asignados al producto
        if ($result && $id) {
        	
        	// Asociar los impuestos a los suplementos del producto
        	$query = 'DELETE FROM #__cp_transfers_supplement_tax WHERE product_id = ' . $id;
        	$this->_db->setQuery($query);
        	if (!$this->_db->query()) {        		
        		$this->setError($this->_db->getErrorMsg());
        		$result = false;
        	}

        	if ($result && !empty($this->supplement_taxes)) {
	        	$insertSupplements = array();
	        	foreach ($this->supplement_taxes as $key => $row) {
	        		$insertSupplements[] = "($id, $key, " . implode("), ($id, $key, ", $row) . ')';
	        	}
                $query = 'INSERT INTO #__cp_transfers_supplement_tax (product_id, supplement_id, tax_id) VALUES ' . implode(', ', $insertSupplements);
                
                $this->_db->setQuery($query);

	            if (!$this->_db->query()) {
	                $this->setError($this->_db->getErrorMsg());
	                $result = false;
	            }
        	}

        	/*
        	 * Verificar el tarifario:
             * - Eliminar los precios de tarifarios que están con parámetros que se eliminaron del producto
             * - Eliminar los precios de tarifarios que están con suplementos que se eliminaron del producto
             * - Eliminar los impuestos de suplementos que están con suplementos que se eliminaron del producto
        	 * - Eliminar la precalculada con fechas que no están en las vigencias de los tarifarios del producto
        	 * 
        	 */
        	if ($result) {
	            $calculatePrices = 0;
	            $query = 'CALL ValidateTransferRates(' . $id . ', ' . $calculatePrices . ')';
	            $this->_db->setQuery($query);

	            if (!$this->_db->query()) {
	            	$this->setError($this->_db->getErrorMsg());
	            	$result = false;
		            $query = 'UPDATE #__cp_transfers_info SET published = 0 WHERE product_id = ' . $id;
		            $this->_db->setQuery($query);
		            $this->_db->query();
	            }
            }
        }
        if (!$result) {
            if ($id) {
                $mes = JText::_('CP.ERROR_SAVING_OLD_PRODUCT');
            } else {
                $mes = JText::_('CP.ERROR_SAVING_NEW_PRODUCT');
            }
            $this->setError($mes . '<div class="hiddenerrormessage">' . $this->getError() . '</div>');
        }

        return $result;
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
        return parent::bind($from, $ignore, array('product_name', 'product_code', 'product_desc'));
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
            // Verificar que no haya productos con ese id de categoría
            $query = 'CALL DeleteTransfer(' . $id . ')';
            $this->_db->setQuery($query);
            if ($this->_db->query()) {
                return true;
            }
        }

        $this->setError(JText::sprintf('CP.PRODUCT_ERROR_DELETING', $id));
        return false;
    }
}
