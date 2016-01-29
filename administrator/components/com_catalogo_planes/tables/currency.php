<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: currency.php 2012-09-11 8:29:50 svn $
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
class TableCurrency extends BasicTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $currency_id = null;
	/**
	 *
	 * @var string
	 */
	var $currency_name = null;
	/**
	 *
	 * @var string
	 */
	var $currency_code = null;
	/**
	 *
	 * @var int
	 */
	var $approx = null;
	/**
	 *
	 * @var double
	 */
	var $trm = null;
	/**
	 *
	 * @var int
	 */
	var $published = null;
    /**
     *
     * @var int
     */
    var $default_currency = null;


    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__cp_prm_currency', 'currency_id', $db);
	}


	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
	function check() {
		return true;
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
        return parent::bind($from, $ignore, array('currency_name', 'currency_code'));
    }
}
