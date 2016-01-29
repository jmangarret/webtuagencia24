<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: hotelparam2.php 2012-09-11 8:29:50 svn $
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
* @subpackage       catalogo_planes
*/
class TableHotelParam2 extends BasicTable {

    /**
     * Primary Key
     *
     * @var int
     */
    var $param2_id = null;
    /**
     *
     * @var string
     */
    var $param2_name = null;
    /**
     *
     * @var string
     */
    var $description = null;
    /**
     *
     * @var int
     */
    var $value = null;
    /**
     *
     * @var int
     */
    var $capacity = null;
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
        $this->relations['hotels'] = array();
        $this->relations['hotels']['referencedtable'] = '#__cp_hotels_param2';
        $this->relations['hotels']['referencedfield'] = 'param2_id';
        $this->relations['hotels']['referencedtableid'] = 'product_id';
        $this->relations['hotels']['cascadedelete'] = false;
        $this->relations['hotels']['selectable'] = false;

        parent::__construct('#__cp_prm_hotels_param2', 'param2_id', $db);
    }


    /**
     * Overloaded check method to ensure data integrity
     *
     * @access public
     * @return boolean True on success
     */
    function check() {

        // Verificar que se de un nombre
        if (!$this->param2_name) {
            $this->setError(JText::_('CP.HOTELPARAM2_ERROR_EMPTY_NAME'));
            return false;
        }

        $key = $this->getKeyName();
        $tableName = $this->getTableName();
        // Verificar que no haya registros con el mismo nombre
        $query = 'SELECT ' . $key . ' FROM ' . $tableName . ' WHERE';
        $query .= ' LOWER(param2_name) = ' . $this->_db->Quote(strtolower($this->param2_name));
        $query .= ' AND param2_id != ' . (int) $this->$key;
        $this->_db->setQuery($query);
        $id = $this->_db->loadResult();
        if ($id) {
            $this->setError(JText::_('CP.HOTELPARAM2_ERROR_DUPLICATE_NAME'));
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
        	$query = 'SELECT product_id FROM #__cp_hotels_param2 WHERE param2_id = ' . $id;
        	$this->_db->setQuery($query);
        	$obj = $this->_db->loadObject();
        	if ($obj) {
        		$this->setError(JText::sprintf('CP.HOTELPARAM2_ERROR_DELETE_EXISTS_RELATED_PRODUCTS', $id));
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
        return parent::bind($from, $ignore, array('param2_name', 'description'));
    }
}
