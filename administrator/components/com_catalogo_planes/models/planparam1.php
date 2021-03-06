<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: planparam1.php 2012-09-10 18:29:50 svn $
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

// Incluir la clase básica para tablas
require_once(JPATH_COMPONENT.DS.'models'.DS.'basicmodel.php');

class CatalogoPlanesModelPlanParam1 extends CatalogoPlanesModelBasic {

	function __construct() {
		$option = JRequest::getCmd('option');

		parent::__construct();

		$this->_filter_prefix = $option . '_planparam1';
    }


	/**
	 * Genera consulta usada en el listado de registros.
	 * @return string
	 */
	protected function _buildQuery($use_filter = true, $conditions = array()) {
        // Obtiene el WHERE y ORDER BY para la consulta
        $where      = $use_filter? $this->_buildQueryWhere($conditions): '';
        $orderby    = ($use_filter && (count($conditions) < 1))? $this->_buildQueryOrderBy(): ' ORDER BY param1_name ASC';

		$query = ' SELECT * FROM ' . $this->getTable()->getTableName() . $where . $orderby;

		return $query;
	}


    /**
     * Almacena un registro
     *
     * @return  boolean true cuando se almacena correctamente
     */
    public function store() {
        $row =& $this->getTable();

        $data = JRequest::get('post');
        $data['value'] = (isset($data['value']))? $data['value']: 0;
        $this->_data =& $row;

        if (count($this->getListByName($data['param1_name']))>0 && $data['param1_id']==0) {
            $this->setError(JText::_('CP.ERROR_PLAN_HOTEL_DUPLICATE'));
            return false;
        }
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


	/**
	 * Devuelve el campo usado para ordenar
	 * @return string
	 */
	public function getOrderByField() {
		$mainframe =& JFactory::getApplication(); 

		// Obtiene el campo por el que se debe ordenar
		$filter_order = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_order', 'filter_order', 'param1_name', 'cmd');

		// se asegura que el orden esté dando por los campos aprobados
		if (!in_array($filter_order, array('param1_id', 'param1_name', 'description', 'value', 'published'))) {
			$filter_order = 'param1_name';
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
            $allowed_filters['created_by'] = '`created_by`';
            $allowed_filters['param1_id'] = '`param1_id`';
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
				$where[] = ' LOWER(`param1_name`) LIKE ' . $this->_db->Quote('%' . $this->_db->getEscaped($search, true).'%', false);
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
		$query = 'SELECT a.product_id, a.product_name FROM #__cp_plans_param1 b JOIN #__cp_plans_info a ' .
            'ON b.product_id = a.product_id WHERE b.param1_id = ' . $id . ' ORDER BY 2 ASC';
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();

        return $data;
    }


    /**
     * Obtiene el listado de registros por nombre
     * @param string $param1_name Parte del nombre a buscar
     * @return array
     */
    public function getListByName($param1_name) {
        $city_name = strtolower($city_name);
        $query = 'SELECT * FROM #__cp_prm_plans_param1 WHERE LOWER(param1_name) LIKE \'%' . $param1_name . '%\' ORDER BY param1_name ASC';

        $this->_db->setQuery($query);

        $list = $this->_db->loadObjectList();

        return $list;
    }
}
