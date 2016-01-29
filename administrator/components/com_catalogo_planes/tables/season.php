<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: season.php 2012-09-11 8:29:50 svn $
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
class TableSeason extends BasicTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $season_id = null;
    /**
     *
     * @var string
     */
    var $season_name = null;
    /**
     *
     * @var int
     */
    var $day1 = null;
    /**
     *
     * @var int
     */
    var $day2 = null;
    /**
     *
     * @var int
     */
    var $day3 = null;
    /**
     *
     * @var int
     */
    var $day4 = null;
    /**
     *
     * @var int
     */
    var $day5 = null;
    /**
     *
     * @var int
     */
    var $day6 = null;
    /**
     *
     * @var int
     */
    var $day7 = null;
    /**
     *
     * @var int
     */
    var $is_special = null;
    /**
     *
     * @var date
     */
    var $created = null;
    /**
     *
     * @var int
     */
    var $created_by = null;
    /**
     *
     * @var date
     */
    var $modified = null;
    /**
     *
     * @var int
     */
    var $modified_by = null;


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
        $this->relations['producttypes']['referencedtable'] = '#__cp_prm_season_product_type';
        $this->relations['producttypes']['referencedfield'] = 'season_id';
        $this->relations['producttypes']['referencedtableid'] = 'product_type_id';
        $this->relations['producttypes']['extrainfotable'] = '#__cp_prm_product_type';
        $this->relations['producttypes']['extrainfoid'] = 'product_type_id';
        $this->relations['producttypes']['orderby'] = array('product_type_name');

        $this->relations['dates'] = array();
        $this->relations['dates']['referencedtable'] = '#__cp_prm_season_date';
        $this->relations['dates']['referencedfield'] = 'season_id';
        $this->relations['dates']['extrainfofields'] = array('start_date', 'end_date');
        $this->relations['dates']['orderby'] = array('start_date', 'end_date');

        parent::__construct('#__cp_prm_season', 'season_id', $db);
	}


	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
	function check() {

        // Verificar que se de un nombre
		if (!$this->season_name) {
			$this->setError(JText::_('CP.SEASON_ERROR_EMPTY_NAME'));
			return false;
		}

        // Verificar que tenga fechas asignadas
		if (empty($this->dates)) {
			if ($this->is_special) {
				$this->setError(JText::_('CP.SEASON_SPECIAL_ERROR_EMPTY_DATES'));
			} else {
				$this->setError(JText::_('CP.SEASON_STANDARD_ERROR_EMPTY_DATES'));
			}
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
        			$queries[] = '(SELECT product_id FROM #__cp_' . $type->product_type_code . '_rate WHERE season_id = ' . $id . ' LIMIT 1)';
        		}
        		$query = implode(' UNION ALL ', $queries);
        		$this->_db->setQuery($query);
        		$obj = $this->_db->loadObject();
        		if ($obj) {
        			$this->setError(JText::sprintf('CP.SEASON_ERROR_DELETE_EXISTS_RELATED_RATES', $id));
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
        return parent::bind($from, $ignore, array('season_name'));
    }
}
