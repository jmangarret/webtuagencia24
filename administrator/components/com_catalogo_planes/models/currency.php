<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: currency.php 2012-09-10 18:29:50 svn $
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

class CatalogoPlanesModelCurrency extends CatalogoPlanesModelBasic {

	function __construct() {
		$option = JRequest::getCmd('option');

		parent::__construct();

		$this->_filter_prefix = $option . '_currency';
    }


	/**
	 * Genera consulta usada en el listado de registros.
	 * @return string
	 */
	protected function _buildQuery($use_filter = true, $conditions = array()) {
                // Obtiene el WHERE y ORDER BY para la consulta
                $where      = $use_filter? $this->_buildQueryWhere($conditions): '';
                $orderby    = ($use_filter && (count($conditions) < 1))? $this->_buildQueryOrderBy(): ' ORDER BY currency_name ASC';

		$query = ' SELECT * FROM ' . $this->getTable()->getTableName() . $where . $orderby;

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

		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}


	/**
	 * Devuelve el campo usado para ordenar
	 * @return string
	 */
	public function getOrderByField() {
		$mainframe =& JFactory::getApplication(); 

		// Obtiene el campo por el que se debe ordenar
		$filter_order = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_order', 'filter_order', 'currency_name', 'cmd');

		// se asegura que el orden esté dando por los campos aprobados
		if (!in_array($filter_order, array('currency_id', 'currency_name', 'currency_code', 'default_currency', 'approx', 'trm', 'published'))) {
			$filter_order = 'currency_name';
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
                    $allowed_filters['currency_name'] = '`currency_name`';
                    $allowed_filters['currency_code'] = '`currency_code`';
                    $allowed_filters['default_currency'] = '`default_currency`';
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
				$condition = ' (LOWER(`currency_name`) LIKE ' . $this->_db->Quote('%' . $this->_db->getEscaped($search, true).'%', false);
				$condition .= ' OR LOWER(`currency_code`) LIKE ' . $this->_db->Quote('%' . $this->_db->getEscaped($search, true).'%', false) . ')';
				$where[] = $condition;
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
	 * Devuelve el listado de posibles criterios de aproximación para los registros
	 * @return array
	 */
	function getApproxCriteriaOptions() {
		// Coloca los valores y etiquetas en un arreglo
		$list = array();
		// Generar la lista de opciones
		$list[] = array(6, JText::_('CP.CURRENCY_APPROXIMATION_6'));
		$list[] = array(7, JText::_('CP.CURRENCY_APPROXIMATION_7'));
		for ($i = 1; $i < 6; $i++) {
			$list[] = array($i, JText::_('CP.CURRENCY_APPROXIMATION_' . $i));
		}

		return $list;
	}


	/**
	 * Almacena un registro
	 *
	 * @return	boolean	true cuando se almacena correctamente
	 */
	public function store() {
		$row =& $this->getTable();

		$data = JRequest::get('post');
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
		} elseif ($row->default_currency == 1) {
			// establece la moneda como principal
			return $this->setDefault($row->currency_id);
		}

		return true;
	}


    /**
    * Cambia la moneda por defecto
    */
    function setDefault ($id) {
        // Verificar que no haya tarifarios ya creados
        $modelProducts =& JModel::getInstance('producttype', 'CatalogoPlanesModel');
        $productTypes = $modelProducts->getActiveProductTypesInfo();
        if (is_array($productTypes)) {
            $queries = array();
            foreach ($productTypes as $type) {
                $queries[] = '(SELECT product_id FROM #__cp_' . $type->product_type_code . '_rate LIMIT 1)';
            }
            $query = implode(' UNION ALL ', $queries);
            $this->_db->setQuery($query);
            $obj = $this->_db->loadObject();
            if ($obj) {
                $this->setError(JText::sprintf('CP.CURRENCY_ERROR_CHANGING_DEFAULT_CURRENCY_WITH_RATES'));
                return false;
            }
        }

        // Obtiene la tabla
    	$row =& $this->getTable();
    	$tableName = $row->getTableName();

    	// Quita la moneda por defecto
        $query = 'UPDATE ' . $tableName . ' SET `default_currency` = 0 WHERE 1';
        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // coloca la nueva moneda por defecto
        $query = 'UPDATE ' . $tableName . ' SET `default_currency` = 1 WHERE `currency_id` = ' . (int) $id;
        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        
        return true;
    }
}
