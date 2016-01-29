<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: season.php 2012-09-10 18:29:50 svn $
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

class CatalogoPlanesModelSeason extends CatalogoPlanesModelBasic {

	function __construct() {
		$option = JRequest::getCmd('option');

		parent::__construct();

		$this->_filter_prefix = $option . '_season';
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
        $orderby    = ($use_filter && (count($conditions) < 1))? $this->_buildQueryOrderBy(): ' ORDER BY a.season_name ASC';

		$query = ' SELECT a.*, v.name AS creator, m.name AS editor FROM ' . $this->getTable()->getTableName();
		$query .= ' a LEFT JOIN #__users AS v ON v.id = a.created_by LEFT JOIN #__users AS m ON m.id = a.modified_by ';
		$query .= $where . $orderby;

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
            case 'editor':
                $filter_order = "m.name $filter_order_Dir, a.season_name ASC";
                break;
            case 'is_special':
                $filter_order = 'a.is_special ' . ((strtoupper($filter_order_Dir) != 'ASC')? 'ASC': 'DESC');
                break;
            default:
                $filter_order = "a.$filter_order $filter_order_Dir";
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
		$filter_order = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_order', 'filter_order', 'season_name', 'cmd');

		// se asegura que el orden esté dando por los campos aprobados
		if (!in_array($filter_order, array('season_id', 'season_name', 'is_special', 'editor', 'modified'))) {
			$filter_order = 'season_name';
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
            $allowed_filters['season_id'] = 'a.`season_id`';
            $allowed_filters['is_special'] = 'a.`is_special`';
            $allowed_filters['product_type_code'] = 'p.`product_type_code`';
            // Recorre el arreglo de condiciones, y si están entre los permitidos, las usa
            foreach ($conditions as $key => $value) {
                if (array_key_exists($key, $allowed_filters)) {
                    $where[] = $allowed_filters[$key] . ' = ' . $this->_db->Quote($this->_db->getEscaped($value));
                }
            }
        } else {
			// Recupera los valores de los filtros
            $is_special = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'is_special', 'filter_is_special', -1, 'int');
			$search = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'search', 'search', '', 'string');
			// Limpia el valor de búsqueda
			if (strpos($search, '"') !== false) {
				$search = str_replace(array('=', '<'), '', $search);
			}
			$search = JString::strtolower($search);

			// Guarda en un arreglo las condiciones de búsqueda por cada filtro.
			if ($search) {
				$where[] = ' LOWER(a.`season_name`) LIKE ' . $this->_db->Quote('%' . $this->_db->getEscaped($search, true).'%', false);
			}
            if ($is_special >= 0) {
            	$where[] = 'a.`is_special` = ' . $is_special;
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

		// Asignar los valores de los campos del formulario a la tabla
		if (!$row->bind($data)) {
			$this->setError($row->getError());
			return false;
		}

		// Asegurarse que se guarden los días de la semana para vigencias estándar.
		if ($data['is_special'] == 0) {
			for ($cont = 1; $cont < 8; $cont++) {
				$day = 'day' . $cont;
				if (!isset($data[$day])) {
				    $row->$day = 0;
				}
			}
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
     * Obtiene las fechas de cada vigencia
     *
     * @param $id Id de la vigencia de la que se obtendrá las fechas
     * @return array|false
     */
    public function getSeasonDates ($id) {
    	// Obtiene la tabla
        $row =& $this->getTable();
        // Obtiene el query de la relación de vigencias y sus fechas
        $query = $row->getRelationshipQuery('dates', $id);
        $dates = array();

        if ($query) {
        	$this->_db->setQuery($query);
        	$dates = $this->_db->loadObjectList();
        }
        return $dates;
    }


    /**
     * Genera consulta usada en el listado de registros.
     * @return array
     */
    public function getSeasonsByProductType($product_type_code, $season_id = 0, $product_id = 0, $rate_id = 0) {
        $query = ' SELECT DISTINCT a.season_id, a.season_name, a.is_special FROM ' . $this->getTable()->getTableName();
        $query .= ' a JOIN #__cp_prm_season_date dat ON a.season_id = dat.season_id JOIN';
        $query .= ' #__cp_prm_season_product_type rel ON a.season_id = rel.season_id JOIN';
        $query .= ' #__cp_prm_product_type p ON rel.product_type_id = p.product_type_id WHERE';
        $query .= ' ((dat.start_date >= CURDATE() AND p.product_type_code = "' . $product_type_code . '")';
        if ($season_id > 0) {
            $query .= ' OR a.season_id = ' . $season_id;
        }
        if ($product_id > 0) {
	        $query .= ') AND NOT EXISTS (SELECT r.rate_id FROM #__cp_' . $product_type_code . '_rate r WHERE ';
            $query .= ' r.season_id = a.season_id AND r.product_id = ' . $product_id;
	        if ($rate_id > 0) {
	            $query .= ' AND r.rate_id <> ' . $rate_id;
	        }
        }
        $query .= ') ORDER BY a.season_name ASC';
        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }


    /**
     * Devuelve las propiedades del registro dado
     */
    function getRawRow($season_id) {
    	$data = null;
        if ($season_id > 0) {
            // Cargar la información de la tarifa junto con la temporada
            $query = 'SELECT season_id, season_name, is_special, day1, day2, day3, day4, day5, day6, day7';
            $query .= ' FROM #__cp_prm_season WHERE season_id = ' . $season_id;
            $this->_db->setQuery($query);

            // Se obtiene el resultado y se convierte en propiedades del objeto
            $data = $this->_db->loadObject();
            $data->dates = $this->getRelationShipRows('dates', $season_id);
        }
        return $data;
    }


    /**
     * Devuelve 1 si la temporada es especial, 0 si no lo es.
     */
    function isSpecialSeason($season_id) {
    	$query = 'SELECT is_special FROM #__cp_prm_season WHERE season_id = ' . $season_id;
    	$this->_db->setQuery($query);

    	return $this->_db->loadResult();
    }


    /**
     * Devuelve las propiedades del registro dado
     */
    function hasRelatedRates($season_id) {
    	$result = false;

    	// Verificar que no haya tarifarios con ese id de registro
    	$modelProducts =& JModel::getInstance('producttype', 'CatalogoPlanesModel');
    	$productTypes = $modelProducts->getActiveProductTypesInfo();
    	if (is_array($productTypes)) {
    		$queries = array();
    		foreach ($productTypes as $type) {
    			$queries[] = '(SELECT product_id FROM #__cp_' . $type->product_type_code . '_rate WHERE season_id = ' . $season_id . ' LIMIT 1)';
    		}
    		$query = implode(' UNION ALL ', $queries);
    		$this->_db->setQuery($query);
    		$obj = $this->_db->loadObject();

    		// Si tiene tarifarios relacionados, no permitir el editar la vigencia
    		if ($obj) {
    			$result = true;
    		}
    	}
        return $result;
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
                $queries[] = '(SELECT \'' . substr($code, 0, -1) . '\' AS product_type_code, \'' . $type->product_type_name . '\' AS product_type_name, a.product_id, ' . 
                    ' a.product_name FROM #__cp_' . $code . '_rate b JOIN #__cp_' . $code . '_info a ON b.product_id = a.product_id WHERE b.season_id = ' . $id . ')';
            }
            $query = implode(' UNION ALL ', $queries) . ' ORDER BY 2 ASC, 4 ASC';
            $this->_db->setQuery($query);
            $data = $this->_db->loadObjectList();
        }

        return $data;
    }
}
