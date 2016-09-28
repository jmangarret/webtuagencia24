<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: tourismtype.php 2012-09-11 8:29:50 svn $
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
class TableTourismType extends BasicTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $tourismtype_id = null;
	/**
	 *
	 * @var string
	 */
	var $tourismtype_name = null;
	/**
	 *
	 * @var int
	 */
	var $published = null;

    /**
     * @var string
     */
    var $image;
	/**
	 *
	 * @var int
	 */
	var $publishedqs=0;

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

        // Agregar relación con cada tipo de producto
        $modelProducts =& JModel::getInstance('producttype', 'CatalogoPlanesModel');
        $productTypes = $modelProducts->getActiveProductTypesInfo();
        $productTypeWithoutTourismTypes = array('transfers', 'cars');
        if (is_array($productTypes)) {
            foreach ($productTypes as $type) {
                $code = $type->product_type_code;
                if (in_array($code, $productTypeWithoutTourismTypes)) continue;
		        $this->relations[$code] = array();
		        $this->relations[$code]['referencedtable'] = '#__cp_' . $type->product_type_code . '_tourismtype';
		        $this->relations[$code]['referencedfield'] = 'tourismtype_id';
		        $this->relations[$code]['referencedtableid'] = 'product_id';
		        $this->relations[$code]['selectable'] = false;
		        $this->relations[$code]['manualinsert'] = true;
            }
        }

        $this->relations['supplements'] = array();
        $this->relations['supplements']['referencedtable'] = '#__cp_prm_supplement_tourismtype';
        $this->relations['supplements']['referencedfield'] = 'tourismtype_id';
        $this->relations['supplements']['referencedtableid'] = 'supplement_id';
        $this->relations['supplements']['selectable'] = false;
        $this->relations['supplements']['manualinsert'] = true;

        parent::__construct('#__cp_prm_tourismtype', 'tourismtype_id', $db);
	}


	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
	function check() {
		// Verificar que se de un nombre
		if (!$this->tourismtype_name) {
			$this->setError(JText::_('CP.TOURISMTYPE_ERROR_EMPTY_NAME'));
			return false;
		}

		$key = $this->getKeyName();
		$tableName = $this->getTableName();
		// Verificar que no haya registros con el mismo nombre
		$query = 'SELECT ' . $key . ' FROM ' . $tableName . ' WHERE';
		$query .= ' LOWER(tourismtype_name) = ' . $this->_db->Quote(strtolower($this->tourismtype_name));
		$query .= ' AND tourismtype_id != ' . (int) $this->$key;
		$this->_db->setQuery($query);
		$id = $this->_db->loadResult();
		if ($id) {
			$this->setError(JText::_('CP.TOURISMTYPE_ERROR_DUPLICATE_NAME'));
			return false;
		}
		return true;
	}
}
