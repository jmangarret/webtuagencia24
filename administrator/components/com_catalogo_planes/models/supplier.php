<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: supplier.php 2012-09-10 18:29:50 svn $
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

class CatalogoPlanesModelSupplier extends CatalogoPlanesModelBasic {

	function __construct() {
		$option = JRequest::getCmd('option');

		parent::__construct();

		$this->_filter_prefix = $option . '_supplier';
    }


    /**
	 * Carga la información de un registro.
	 * @param int $id
	 * @return JTable
	 */
	function &getRow($id = null, $loadRelationships = true) {
		if (empty($this->_data)) {
			// Obtiene la tabla
			$this->_data =& $this->getTable();

			// Si se pasó un id válido, carga la información
			if ($id) {
				$this->_data->load($id, $loadRelationships);
				if ($this->_data->country_id) {
					// Cargar el nombre del país
					$countryTable =& $this->getTable('country');
					$countryTable->load($this->_data->country_id);
					$this->_data->country_name = $countryTable->country_name;
				} else {
					$this->_data->country_name = '';
				}
				if ($this->_data->city_id) {
					// Cargar el nombre del país
					$cityTable =& $this->getTable('city');
					$cityTable->load($this->_data->city_id);
					$this->_data->city_name = $cityTable->city_name;
				} else {
					$this->_data->city_name = '';
				}
			} else {
				$this->_data->published = 1;
			}
		}

		return $this->_data;
	}


	/**
	 * Genera consulta usada en el listado de registros.
	 * @return string
	 */
	protected function _buildQuery($use_filter = true, $conditions = array()) {
		// Obtiene el WHERE y ORDER BY para la consulta
		$where		= $use_filter? $this->_buildQueryWhere($conditions): '';
		$orderby	= ($use_filter && (count($conditions) < 1))? $this->_buildQueryOrderBy(): ' ORDER BY a.supplier_name ASC';
		$orderby	.= ', b.country_name ASC, c.city_name ASC';

		$query = ' SELECT a.*, b.country_name, c.city_name FROM ' . $this->getTable()->getTableName();
		$query .= ' a LEFT JOIN #__cp_prm_country b ON a.country_id = b.country_id ';
		$query .= ' LEFT JOIN #__cp_prm_city c ON a.city_id = c.city_id ' . $where . $orderby;

		return $query;
	}


	/**
	 * Genera el ordenamiento para la consulta usada en el listado de registros.
	 * @return string
	 */
	protected function _buildQueryOrderBy() {

		// Obtiene el campo por el que se debe ordenar
		$filter_order		= $this->getOrderByField();
		// Obtiene el sentido en el que se debe ordenar
		$filter_order_Dir	= $this->getOrderByDirection();
        switch ($filter_order) {
            case 'country_name':
                $filter_order = "b.country_name $filter_order_Dir, c.city_name ASC, a.supplier_name ASC";
                break;
            case 'city_name':
                $filter_order = "c.city_name $filter_order_Dir, b.country_name ASC, a.supplier_name ASC";
                break;
            default:
                $filter_order = "a.$filter_order $filter_order_Dir, b.country_name ASC, c.city_name ASC";
                break;
        }

        $orderby = ' ORDER BY ' . $filter_order;


		return $orderby;
	}


	/**
	 * Devuelve el campo usado para ordenar
	 * @return string
	 */
	public function getOrderByField() {
		$mainframe =& JFactory::getApplication(); 
		// Obtiene el campo por el que se debe ordenar
		$filter_order = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_order', 'filter_order', 'supplier_name', 'cmd');

		// se asegura que el orden esté dando por los campos aprobados
		if (!in_array($filter_order, array('supplier_id', 'supplier_name', 'supplier_code', 'url', 'email', 'published', 'country_name', 'city_name'))) {
			$filter_order = 'supplier_name';
		}

		return $filter_order;
	}


	/**
	 * Devuelve la dirección en la que se debe ordenar
	 * @return string
	 */
	function getOrderByDirection() {
		$mainframe =& JFactory::getApplication(); 

		// Obtiene el sentido en el que se debe ordenar
		$filter_order_Dir = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_order_Dir', 'filter_order_Dir', '', 'word');

		// Por defecto el orden es ascendente
		if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC'))) {
			$filter_order_Dir = '';
		}

		return $filter_order_Dir;
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
			$allowed_filters['country_id'] = 'a.`country_id`';
			$allowed_filters['city_id'] = 'a.`city_id`';
			$allowed_filters['published'] = 'a.`published`';
			// Recorre el arreglo de condiciones, y si están entre los permitidos, las usa
			foreach ($conditions as $key => $value) {
				if (array_key_exists($key, $allowed_filters)) {
					$where[] = $allowed_filters[$key] . ' = ' . $this->_db->Quote($this->_db->getEscaped($value));
				}
			}
		} else {
			// Recupera los valores de los filtros
			$filter_state = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_state', 'filter_state', '', 'word');
			$country_id = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'country_id', 'filter_country_id', '', 'int');
			$city_id = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'city_id', 'filter_city_id', '', 'int');
			$search = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'search', 'search', '', 'string');
			// Limpia el valor de búsqueda
			if (strpos($search, '"') !== false) {
				$search = str_replace(array('=', '<'), '', $search);
			}
			$search = JString::strtolower($search);

			// Guarda en un arreglo las condiciones de búsqueda por cada filtro.
			if ($search) {
				$where[] = ' LOWER(a.`supplier_name`) LIKE ' . $this->_db->Quote('%' . $this->_db->getEscaped($search, true).'%', false);
			}
			if ($country_id) {
				$where[] = ' a.country_id = ' . $country_id;
			}
			if ($city_id) {
				$where[] = ' a.city_id = ' . $city_id;
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
                $queries[] = '(SELECT \'' . $code . '\' AS product_type_code, \'' . $type->product_type_name . '\' AS product_type_name, ' . 
                    ' product_id, product_name FROM #__cp_' . $code . '_info WHERE supplier_id = ' . $id . ' ORDER BY product_name ASC)';
            }
            $query = implode(' UNION ALL ', $queries);
            $this->_db->setQuery($query);
            $data = $this->_db->loadObjectList();
        }

        return $data;
    }
}
