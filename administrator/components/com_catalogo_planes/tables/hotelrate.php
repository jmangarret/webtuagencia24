<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: hotelrate.php 2012-09-11 8:29:50 svn $
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
class TableHotelRate extends BasicTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $rate_id = null;
    /**
     *
     * @var int
     */
    var $season_id = null;
    /**
     *
     * @var int
     */
    var $product_id = null;
    /**
     *
     * @var double
     */
    var $basic_price = null;
    /**
     *
     * @var double
     */
    var $previous_value = null;
    /**
     *
     * @var int
     */
    var $currency_id = null;
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
        $this->relations['prices'] = array();
        $this->relations['prices']['referencedtable'] = '#__cp_hotels_rate_price';
        $this->relations['prices']['referencedfield'] = 'rate_id';
        $this->relations['prices']['extrainfofields'] = array('param1', 'param2', 'param3', 'is_child', 'price');

        $this->relations['supplements'] = array();
        $this->relations['supplements']['referencedtable'] = '#__cp_hotels_rate_supplement';
        $this->relations['supplements']['referencedfield'] = 'rate_id';
        $this->relations['supplements']['extrainfofields'] = array('amount');

        parent::__construct('#__cp_hotels_rate', 'rate_id', $db);
	}


	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
	function check() {
        // Verificar que se de una vigencia
        if (!$this->season_id) {
            $this->setError(JText::_('CP.PRODUCT_RATE_ERROR_EMPTY_SEASON'));
            return false;
        }

        // Verificar que se de el precio desde
        if (!$this->basic_price) {
            $this->setError(JText::_('CP.PRODUCT_RATE_ERROR_EMPTY_BASIC_PRICE'));
            return false;
        }

        // Verificar que se de al menos una tarifa
        /*
        if (count($this->prices) < 1) {
            $this->setError(JText::_('CP.PRODUCT_RATE_ERROR_EMPTY_PRICES'));
            return false;
        }
        */

        return true;
	}


    public function load($id = null, $loadRelationships = true) {
        // Cargar la información de la tarifa junto con la temporada
        $query = 'SELECT a.*, p.product_name, s.season_name, s.is_special, s.day1, s.day2';
        $query .= ', s.day3, s.day4, s.day5, s.day6, s.day7 FROM ' . $this->getTableName();
        $query .= ' a JOIN #__cp_hotels_info p ON a.product_id = p.product_id JOIN';
        $query .= ' #__cp_prm_season s ON a.season_id = s.season_id WHERE a.rate_id = ' . $id;
        $this->_db->setQuery($query);

        // Se obtiene el resultado y se convierte en propiedades del objeto
        if ($result = $this->_db->loadAssoc()) {
            foreach ($result as $key => $value) {
            	$this->$key = $value;
            }

	        // Si se cargó correctamente y hay un id y relaciones definidas, cargue los datos de las relaciones.
	        if ($loadRelationships && $result) {
	            $this->loadRelationships($id);
	        }
	        return true;
        } else {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
    }


    function store($updateNulls = false, $updateRelationships = true) {
        $result = parent::store($updateNulls, $updateRelationships);

        $key = $this->getKeyName();
        $id = $this->$key;

        // Precalcular precios y disponibilidad
        if ($result && $this->product_id) {
	        // Determina si el precio desde de la disponibilidad se calcula por las vigencias o 
	        // se toma de la configuración del tarifario. Colocar en 1 para usar las vigencias.
	        $calculatePrices = 0;
            $query = 'CALL GenerateHotelsRateResumeById(' . $this->product_id . ', CURDATE(), ADDDATE(CURDATE(), 365), ' . $calculatePrices . ')';
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
            	$this->setError($this->_db->getErrorMsg());
            	$result = false;
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
        $id = $this->$key;
        if ($oid) {
            $this->$key = $oid;
        }
        $id = $this->$key;
        $this->load($id, false);
        $result = parent::delete($oid);

        // Precalcular precios y disponibilidad
        if ($result && $this->product_id) {
            // Determina si el precio desde de la disponibilidad se calcula por las vigencias o 
            // se toma de la configuración del tarifario. Colocar en 1 para usar las vigencias.
            $calculatePrices = 0;
            $query = 'CALL GenerateHotelsRateResumeById(' . $this->product_id . ', CURDATE(), ADDDATE(CURDATE(), 365), ' . $calculatePrices . ')';
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                $result = false;
            }
        }

        return $result;
    }
}
