<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: zone.php 2012-09-10 18:29:50 svn $
 * @author Yusely Palacios
 * @package Joomla
 * @subpackage catalogo_planes
 * @license Copyright (c) 2012 Amadeus - All Rights Reserved
 *
 * 
 *
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Incluir la clase básica para modelos
require_once(JPATH_COMPONENT.DS.'models'.DS.'basicmodel.php');

class CatalogoPlanesModelZone extends CatalogoPlanesModelBasic {

	function __construct() {
		$option = JRequest::getCmd('option');

		parent::__construct();

		$this->_filter_prefix = $option . '_zone';
    }


	/**
	 * Genera consulta usada en el listado de registros.
	 * @return string
	 */
	protected function _buildQuery($use_filter = true, $conditions = array()) {
        // Obtiene el WHERE y ORDER BY para la consulta
        $where      = $use_filter? $this->_buildQueryWhere($conditions): '';
        $orderby    = ($use_filter && (count($conditions) < 1))? $this->_buildQueryOrderBy(): ' ORDER BY zone_name ASC';

		$query = ' SELECT * FROM ' . $this->getTable()->getTableName() . $where . $orderby;

		return $query;
	}


	/**
	 * Devuelve el campo usado para ordenar
	 * @return string
	 */
	public function getOrderByField() {
		$mainframe =& JFactory::getApplication(); 

		// Obtiene el campo por el que se debe ordenar
		$filter_order = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_order', 'filter_order', 'zone_name', 'cmd');

		// se asegura que el orden esté dando por los campos aprobados
		if (!in_array($filter_order, array('zone_id', 'zone_name', 'published'))) {
			$filter_order = 'zone_name';
		}

		return $filter_order;
	}


	/**
	 * Genera las condiciones para la consulta usada en el listado de registros.
	 * @return string
	 */
	protected function _buildQueryWhere($conditions = array()) {
        $mainframe =& JFactory::getApplication(); 
        $where = array();

        // Si recibe un arreglo con condiciones, las usa para traer los registros
        if (count($conditions) > 0) {
            // Filtros permitidos
            $allowed_filters = array();
            $allowed_filters['tax_id'] = '`tax_id`';
            $allowed_filters['published'] = '`published`';
            // Recorre el arreglo de condiciones, y si están entre los permitidos, las usa
            foreach ($conditions as $key => $value) {
                if (array_key_exists($key, $allowed_filters)) {
                    $where[] = $allowed_filters[$key] . ' = ' . $this->_db->Quote($this->_db->getEscaped($value));
                }
            }
        } else {
			// Recupera los valores de los filtros
			$filter_state = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_state', 'filter_state', '', 'word');
			$search = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'search', 'search', '', 'string');
			// Limpia el valor de búsqueda
			if (strpos($search, '"') !== false) {
				$search = str_replace(array('=', '<'), '', $search);
			}
			$search = JString::strtolower($search);

			// Guarda en un arreglo las condiciones de búsqueda por cada filtro.
			if ($search) {
				$where[] = ' LOWER(`zone_name`) LIKE ' . $this->_db->Quote('%' . $this->_db->getEscaped($search, true).'%', false);
			}
			if ($filter_state) {
				if ($filter_state == 'P') {
					$where[] = '`published` = 1';
				} else if ($filter_state == 'U') {
					$where[] = '`published` = 0';
				}
			}
		}

		// Organiza las condiciones de la consulta
		$where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');

		return $where;
	}


	/**
	 * Devuelve listado de productos relacionados al registro dado.
	 * @param int $id
	 * @return array
	 */
	public function getRelatedProducts ($id) {
        $data = null;
        $modelProducts =& JModel::getInstance('producttype', 'CatalogoPlanesModel');
        $productTypes = $modelProducts->getActiveProductTypesInfo();
        if (is_array($productTypes)) {
        	$queries = array();
        	foreach ($productTypes as $type) {
        		$code = $type->product_type_code;
        		if($code=='hotels'){
        			$queries[] = '(SELECT \'' . $code . '\' AS product_type_code, \'' . $type->product_type_name . '\' AS product_type_name, ' . 
                    ' product_id, product_name FROM #__cp_' . $code . '_info WHERE zone_id = ' . $id . ' ORDER BY product_name ASC)';
        		}
        	}
        	$query = implode(' UNION ALL ', $queries);
        	
        	$this->_db->setQuery($query);
        	$data = $this->_db->loadObjectList();
        }

        return $data;
	}
}
