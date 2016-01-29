<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: region.php 2012-09-10 18:29:50 svn $
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

class CatalogoPlanesModelRegion extends CatalogoPlanesModelBasic {

	function __construct() {
		$option = JRequest::getCmd('option');

		parent::__construct();

		$this->_filter_prefix = $option . '_region';
    }


    /**
	 * Carga la información de un registro.
	 * @param int $id
	 * @return JTable
	 */
	public function &getRow($id = null, $loadRelationships = true) {
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
			} else {
				$this->_data->country_id = JRequest::getInt('country_id', null);
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
		$orderby	= ($use_filter && (count($conditions) < 1))? $this->_buildQueryOrderBy(): ' ORDER BY a.region_name ASC, b.country_name ASC';

		$query = ' SELECT a.*, b.country_name FROM ' . $this->getTable()->getTableName();
		$query .= ' a JOIN #__cp_prm_country b ON a.country_id = b.country_id ' . $where . $orderby;

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
                $filter_order = "b.country_name $filter_order_Dir, a.region_name ASC";
                break;
            default:
                $filter_order = "a.$filter_order $filter_order_Dir, b.country_name ASC";
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
		$filter_order = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_order', 'filter_order', 'region_name', 'cmd');

		// se asegura que el orden esté dando por los campos aprobados
		if (!in_array($filter_order, array('region_id', 'region_name', 'region_code', 'published', 'country_name'))) {
			$filter_order = 'region_name';
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
			$allowed_filters['country_id'] = 'a.`country_id`';
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
			$search = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'search', 'search', '', 'string');
			// Limpia el valor de búsqueda
			if (strpos($search, '"') !== false) {
				$search = str_replace(array('=', '<'), '', $search);
			}
			$search = JString::strtolower($search);

			// Guarda en un arreglo las condiciones de búsqueda por cada filtro.
			if ($search) {
				$where[] = ' LOWER(a.`region_name`) LIKE ' . $this->_db->Quote('%' . $this->_db->getEscaped($search, true).'%', false);
			}
			if ($country_id) {
				$where[] = ' a.country_id = ' . $country_id;
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
}
