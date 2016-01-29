<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: hotelrate.php 2012-09-10 18:29:50 svn $
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

class CatalogoPlanesModelHotelRate extends CatalogoPlanesModelBasic {

	function __construct() {
		$option = JRequest::getCmd('option');

		parent::__construct();

		$this->_filter_prefix = $option . '_hotelrate';
    }


    /**
	 * Carga la información de un registro.
	 * @param int $id
	 * @return JTable
	 */
	function &getRow($id = null, $loadRelationships = true) {
		if (empty($this->_data)) {
			// Obtiene la tabla
			$row =& $this->getTable();

			// Si se pasó un id válido, carga la información
			if ($id) {
				$row->load($id, $loadRelationships);

                // Cargar el nombre del usuario que creó la vigencia
                if ($row->created_by) {
                    $query = 'SELECT name FROM #__users WHERE id = '. (int) $row->created_by;
                    $this->_db->setQuery($query);
                    $row->creator = $this->_db->loadResult();
                } else {
                    $row->creator = '';
                }

                // Cargar el nombre del usuario que creó la vigencia
                if ($row->modified_by) {
                    $query = 'SELECT name FROM #__users WHERE id = '. (int) $row->modified_by;
                    $this->_db->setQuery($query);
                    $row->editor = $this->_db->loadResult();
                } else {
                	$row->editor = '';
                }
			} else {
				$row->creator = '';
				$row->editor = '';
			}
			$this->_data =& $row;
		}

		return $this->_data;
	}


	/**
	 * Genera consulta usada en el listado de registros.
	 * @return string
	 */
	protected function _buildQuery($use_filter = true, $conditions = array()) {
        // Obtiene el WHERE y ORDER BY para la consulta
        $where      = $use_filter? $this->_buildQueryWhere($conditions): '';
        $orderby    = ($use_filter && (count($conditions) < 1))? $this->_buildQueryOrderBy(): ' ORDER BY s.season_name ASC';

		$query = ' SELECT a.*, s.season_name, s.is_special, v.name AS creator, m.name AS editor, s.day1, s.day2';
		$query .= ', s.day3, s.day4, s.day5, s.day6, s.day7 FROM ' . $this->getTable()->getTableName();
		$query .= ' a INNER JOIN #__cp_prm_season s ON a.season_id = s.season_id INNER JOIN #__cp_prm_season_date d ON d.season_id';
		$query .= ' = s.season_id LEFT JOIN #__users AS v ON v.id = a.created_by LEFT JOIN #__users AS m ON m.id = a.modified_by ';
		$query .= $where . ' GROUP BY a.rate_id ' . $orderby;

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
		$filter_order = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_order', 'filter_order', null, 'cmd');

		// se asegura que el orden esté dando por los campos aprobados
		switch ($filter_order) {
			case 'season_name':
			case 'is_special':
                $filter_order = 's.' . $filter_order;
                break;
			case 'created':
			case 'created_by':
			case 'modified':
			case 'modified_by':
                $filter_order = 'a.' . $filter_order;
                break;
			case 'name':
                $filter_order = 'm.name';
                break;
			default:
                $filter_order = 's.season_name';
                break;
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
            $allowed_filters['created_by'] = 'a.`created_by`';
            $allowed_filters['rate_id'] = 'a.`rate_id`';
            $allowed_filters['is_special'] = 's.`is_special`';
            // Recorre el arreglo de condiciones, y si están entre los permitidos, las usa
            foreach ($conditions as $key => $value) {
                if (array_key_exists($key, $allowed_filters)) {
                    $where[] = $allowed_filters[$key] . ' = ' . $this->_db->Quote($this->_db->getEscaped($value));
                }
            }
        } else {
			// Recupera los valores de los filtros
            $product_id = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'product_id', 'product_id', 0, 'int');
            $season_year = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'season_year', 'season_year', date('Y'), 'int');

			// Guarda en un arreglo las condiciones de búsqueda por cada filtro.
            $where[] = $season_year . ' BETWEEN YEAR(d.`start_date`) AND YEAR(d.`end_date`)';
            if ($product_id >= 0) {
            	$where[] = 'a.`product_id` = ' . $product_id;
            }
		}

		// Organiza las condiciones de la consulta
		$where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');

		return $where;
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

		// Limpiar las tarifas que vinieron en ceros.
		if (isset($data['prices'])) {
			foreach ($data['prices'] as $key => $price) {
				if (doubleval($price['price']) <= 0) {
					unset($data['prices'][$key]);
				}
			}
		}
		if (isset($data['supplements'])) {
			foreach ($data['supplements'] as $key => $supplement) {
				if (doubleval($supplement['amount']) <= 0) {
					unset($data['supplements'][$key]);
				}
			}
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
	 * Verifica si al agregar una vigencia se solaparía en un producto
	 *
	 * @return	array 
	 */
    function checkSeasonOverlapping($product_id, $season_id, $rate_id) {
    	// Obtener la informac ión d
        $query = 'SELECT s1.season_name, s1.is_special, s1.day1, s1.day2, s1.day3, s1.day4, 
            s1.day5, s1.day6, s1.day7, d1.start_date, d1.end_date FROM #__cp_prm_season s1 
            INNER JOIN #__cp_prm_season_date d1 ON s1.season_id = d1.season_id 
            INNER JOIN #__cp_hotels_rate r ON s1.season_id = r.season_id 
            INNER JOIN #__cp_prm_season s ON s1.is_special = s.is_special AND s1.season_id <> s.season_id
            INNER JOIN #__cp_prm_season_date d ON s.season_id = d.season_id
            WHERE 
            r.product_id = ' . $product_id . 
            (($rate_id)? ' AND r.rate_id <> ' . $rate_id: '') . ' AND 
            s.season_id = ' . $season_id . ' AND 
            (d1.start_date BETWEEN d.start_date AND d.end_date OR d1.end_date BETWEEN d.start_date AND d.end_date)
            ORDER BY s1.season_name, d1.start_date, d1.end_date';
        $this->_db->setQuery($query);
        $list = $this->_db->loadObjectList();

        // Devolver falso si no hay solapamiento
        if (empty($list)) {
        	return false;
        } else {
            // Para vigencias estándar, verificar si hay coincidencia en los días
            if ($list[0]->is_special == 0) {
	            $query = 'SELECT day1, day2, day3, day4, day5, day6, day7 FROM #__cp_prm_season WHERE season_id = ' . $season_id;
	            $this->_db->setQuery($query);
	            $selectedSeason = $this->_db->loadObject();
	            $result = array();

	            foreach ($list as $season) {
	            	if (($season->day1 == 1 && $selectedSeason->day1 == 1) || 
	            	   ($season->day2 == 1 && $selectedSeason->day2 == 1) || 
	            	   ($season->day3 == 1 && $selectedSeason->day3 == 1) || 
	            	   ($season->day4 == 1 && $selectedSeason->day4 == 1) || 
	            	   ($season->day5 == 1 && $selectedSeason->day5 == 1) || 
	            	   ($season->day6 == 1 && $selectedSeason->day6 == 1) || 
	            	   ($season->day7 == 1 && $selectedSeason->day7 == 1)) {
	            	   	$result[] = $season;
	                }
	            }
            } else {
            	// Para vigencias especiales no es necesario verificar días
            	$result = $list;
            }

	        // devuelve el listado
	        return $result;
        }
    }
}
