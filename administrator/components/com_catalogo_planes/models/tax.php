<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: tax.php 2012-09-10 18:29:50 svn $
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

class CatalogoPlanesModelTax extends CatalogoPlanesModelBasic {


	function __construct() {
		$option = JRequest::getCmd('option');

		parent::__construct();

		$this->_filter_prefix = $option . '_tax';
    }


	/**
	 * Genera consulta usada en el listado de registros.
	 * @return string
	 */
	protected function _buildQuery($use_filter = true, $conditions = array()) {
        // Obtiene el WHERE y ORDER BY para la consulta
        $where      = $use_filter? $this->_buildQueryWhere($conditions): '';
        $orderby    = ($use_filter && (count($conditions) < 1))? $this->_buildQueryOrderBy(): ' ORDER BY a.tax_name ASC';

        $query = 'SELECT a.* FROM ' . $this->getTable()->getTableName() . ' a LEFT JOIN #__cp_prm_tax_product_type b ';
        $query .= ' ON a.tax_id = b.tax_id LEFT JOIN #__cp_prm_product_type c ON b.product_type_id = c.product_type_id ';
        $query .= $where . ' GROUP BY a.tax_id ' . $orderby;

		return $query;
	}


    /**
     * Genera el ordenamiento para la consulta usada en el listado de registros.
     * @return string
     */
    protected function _buildQueryOrderBy() {

        // Obtiene el campo por el que se debe ordenar
        $filter_order       = $this->getOrderByField();
        // Obtiene el sentido en el que se debe ordenar
        $filter_order_Dir   = $this->getOrderByDirection();

        $orderby = ' ORDER BY a.' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }


	/**
	 * Devuelve el campo usado para ordenar
	 * @return string
	 */
	public function getOrderByField() {
		$mainframe =& JFactory::getApplication(); 

		// Obtiene el campo por el que se debe ordenar
		$filter_order = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_order', 'filter_order', 'tax_name', 'cmd');

		// se asegura que el orden esté dando por los campos aprobados
		if (!in_array($filter_order, array('tax_id', 'tax_name', 'tax_code', 'tax_value', 'iva', 'published'))) {
			$filter_order = 'tax_name';
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
            $allowed_filters['tax_id'] = 'a.`tax_id`';
            $allowed_filters['published'] = 'a.`published`';
            $allowed_filters['product_type_code'] = 'c.`product_type_code`';
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
				$where[] = ' LOWER(a.`tax_name`) LIKE ' . $this->_db->Quote('%' . $this->_db->getEscaped($search, true).'%', false);
			}
			if ($filter_state) {
				if ($filter_state == 'P') {
					$where[] = 'a.`published` = 1';
				} else if ($filter_state == 'U') {
					$where[] = 'a.`published` = 0';
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
                $queries[] = '(SELECT \'' . $code . '\' AS product_type_code, \'' . $type->product_type_name . '\' AS product_type_name, a.product_id, ' . 
                    ' a.product_name FROM #__cp_' . $code . '_taxes b JOIN #__cp_' . $code . '_info a ON b.product_id = a.product_id WHERE b.tax_id = ' . $id . ')';
                $queries[] = '(SELECT \'' . $code . '\' AS product_type_code, \'' . $type->product_type_name . '\' AS product_type_name, a.product_id, ' . 
                    ' a.product_name FROM #__cp_' . $code . '_supplement_tax b JOIN #__cp_' . $code . '_info a ON b.product_id = a.product_id WHERE b.tax_id = ' . $id . ')';
            }
            $query = implode(' UNION ', $queries) . ' ORDER BY 2 ASC, 4 ASC';
            $this->_db->setQuery($query);
            $data = $this->_db->loadObjectList();
        }

        return $data;
    }


    /**
     * Almacena un registro
     *
     * @return  boolean true cuando se almacena correctamente
     */
    public function store() {
        $row =& $this->getTable();

        $data = JRequest::get('post');
        $data['tax_value'] /= 100;
        $this->_data =& $row;

        // Asignar los valores de los campos del formulario a la tabla
        if (!$row->bind($data)) {
            $this->setError($row->getError());
            return false;
        }

        // Asegurarse que el registro sea válido 
        if (!$row->check()) {
            $this->setError($row->getError());
            return false;
        }

        // almacenar el registro
        if (!$row->store()) {
            $this->setError($row->getError());
            return false;
        }

        return true;
    }
}
