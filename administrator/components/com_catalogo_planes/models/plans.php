<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: plans.php 2012-09-10 18:29:50 svn $
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

class CatalogoPlanesModelPlans extends CatalogoPlanesModelBasic {

    function __construct() {
        $option = JRequest::getCmd('option');

        $this->_filter_prefix = $option . '_plans';

        parent::__construct();
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
				if ($this->_data->region_id) {
					// Cargar el nombre del país
					$regionTable =& $this->getTable('region');
					$regionTable->load($this->_data->region_id);
					$this->_data->region_name = $regionTable->region_name;
				} else {
					$this->_data->region_name = '';
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
		$orderby	= ($use_filter && (count($conditions) < 1))? $this->_buildQueryOrderBy(): ' ORDER BY a.product_name ASC';
		$orderby	.= ', b.country_name ASC, c.region_name ASC';

		$query = ' SELECT a.*, m.name AS editor, b.country_name, c.region_name, e.category_name,';
		$query .= ' d.city_name, g.title AS groupname FROM ' . $this->getTable()->getTableName();
                $query .= ' a JOIN #__cp_prm_plans_category e ON a.category_id = e.category_id ';
                $query .= ' JOIN #__cp_prm_country b ON a.country_id = b.country_id ';
                $query .= ' JOIN #__cp_prm_city d ON a.city_id = d.city_id ';
                $query .= ' LEFT JOIN #__cp_prm_region c ON a.region_id = c.region_id ';
                $query .= ' LEFT JOIN #__cp_plans_tourismtype f ON a.product_id = f.product_id ';
                $query .= ' LEFT JOIN #__users AS m ON m.id = a.modified_by ';
                $query .= ' LEFT JOIN #__viewlevels AS g ON g.id = a.access ';
                $query .= $where . ' GROUP BY a.product_id ' . $orderby;

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
	public function getOrderByField($query = true) {
		$mainframe =& JFactory::getApplication(); 
		// Obtiene el campo por el que se debe ordenar
		$filter_order = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_order', 'filter_order', 'ordering', 'cmd');

		// se asegura que el orden esté dando por los campos aprobados
		$allowed_fields = array('product_id', 'published', 'product_name', 'ordering', 'category_name', 'featured', 'access', 'modified', 'city_name', 'editor');
		if (in_array($filter_order, $allowed_fields)) {
			if (!$query) {
				switch ($filter_order) {
	                case 'city_name':
	                    $filter_order = 'd.city_name';
	                break;
	                case 'category_name':
	                    $filter_order = 'e.category_name';
	                break;
	                case 'editor':
	                    $filter_order = 'm.name';
	                break;
					default:
						$filter_order = 'a.' . $filter_order;
					break;
				}
			}
		} else {
			$filter_order = ($query)? 'a.product_name': 'product_name';
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
			$allowed_filters['region_id'] = 'a.`region_id`';
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
			$country_id = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'country_id', 'country_id', '', 'int');
			$region_id = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'region_id', 'region_id', '', 'int');
			$city_id = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'city_id', 'city_id', '', 'int');
			$category_id = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'category_id', 'category_id', '', 'int');
			$tourismtype_id = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'tourismtype_id', 'tourismtype_id', '', 'int');
			$search = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'search', 'search', '', 'string');
			// Limpia el valor de búsqueda
			if (strpos($search, '"') !== false) {
				$search = str_replace(array('=', '<'), '', $search);
			}
			$search = JString::strtolower($search);

			// Guarda en un arreglo las condiciones de búsqueda por cada filtro.
			if ($search) {
				$value = $this->_db->Quote('%' . $this->_db->getEscaped($search, true).'%', false);
                                $where[] = ' (LOWER(a.`product_name`) LIKE ' . $value . ' OR LOWER(a.`product_code`) LIKE ' . $value . ') ';
			}
			if ($country_id) {
				$where[] = ' a.country_id = ' . $country_id;
			}
                        if ($region_id) {
                            $where[] = ' a.region_id = ' . $region_id;
                        }
                        if ($city_id) {
                            $where[] = ' a.city_id = ' . $city_id;
                        }
                        if ($category_id) {
                            $where[] = ' a.category_id = ' . $category_id;
                        }
                        if ($tourismtype_id) {
                            $where[] = ' f.tourismtype_id = ' . $tourismtype_id;
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
     * Almacena un registro
     *
     * @return  boolean true cuando se almacena correctamente
     */
    public function store() {
        $row =& $this->getTable();
        $this->_data =& $row;

        $data = JRequest::get('post');
        // HTML content must be required!
        $data['tag_content1'] = JRequest::getVar('tag_content1', '', 'post', 'string', JREQUEST_ALLOWHTML);
        $data['tag_content2'] = JRequest::getVar('tag_content2', '', 'post', 'string', JREQUEST_ALLOWHTML);
        $data['tag_content3'] = JRequest::getVar('tag_content3', '', 'post', 'string', JREQUEST_ALLOWHTML);
        $data['tag_content4'] = JRequest::getVar('tag_content4', '', 'post', 'string', JREQUEST_ALLOWHTML);
        $data['tag_content5'] = JRequest::getVar('tag_content5', '', 'post', 'string', JREQUEST_ALLOWHTML);
        $data['tag_content6'] = JRequest::getVar('tag_content6', '', 'post', 'string', JREQUEST_ALLOWHTML);
        
        $user = & JFactory::getUser();
        $details = JRequest::getVar('details', '', 'post');
        $data['featured'] = isset($details['featured'])? $details['featured']: 0;
        $data['publish_down'] = $details['publish_down'];
        $data['country_id'] = $data['detailscountry_id'];
        $data['region_id'] = $data['detailsregion_id'];
        $data['city_id'] = $data['detailscity_id'];
        $data['publish_up'] = $details['publish_up'];
        $data['access'] = $details['access'];
        $data['published'] = $details['published'];
        $data['product_desc'] = $details['product_desc'];
        $data['product_code'] = $details['product_code'];
        $data['disclaimer'] = $details['disclaimer'];
        $data['category_id'] = $details['category_id'];
        $data['latitude'] = $details['latitude'];
        $data['longitude'] = $details['longitude'];
        $data['product_url'] = $details['product_url'];
        $data['duration'] = $details['duration'];
        $data['days_total'] = $details['days_total'];
        $data['supplier_id'] = $details['supplier_id'];
        $data['tourismtypes'] = $details['tourismtypes'];

        $createdate =& JFactory::getDate();
        if (!$data['product_id']) {
            $data['created_by'] = $user->get('id');
            $data['created'] = $createdate->toFormat();
        } else {
            $data['modified_by'] = $user->get('id');
            $data['modified'] = $createdate->toFormat();
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
        } else {
            $product_id = $row->product_id;
	        // Manejo de las imágenes del producto
	        if ($product_id && isset($data['mediafiles']) && is_array($data['mediafiles'])) {
	            $mediafiles = $data['mediafiles'];
	            $files = "'" . implode("','", $mediafiles) . "'";

	            // Delete the files not included
	            $query = 'DELETE FROM #__cp_plans_files WHERE `file_url` NOT IN (' . $files . ') AND `product_id` = ' . $product_id;
	            $this->_db->setQuery($query);
	            if (!$this->_db->query()) {
	                $this->setError($this->_db->getErrorMsg());
	                return false;
	            }

	            $i = 1;
	            foreach ($mediafiles as $file) {
	                // Check if the file is already related to the product, if it's not, insert.
	                $query = 'SELECT `file_url` FROM #__cp_plans_files WHERE `file_url` = \'' . $file . '\' AND `product_id` = ' . $product_id;
	                $this->_db->setQuery($query);
	                $this->_db->query();
	                if (!$this->_db->getNumRows()) {
	                    $query = 'INSERT INTO #__cp_plans_files (`product_id`, `file_url`, `ordering`) VALUES (' . $product_id . ', \'' . $file . '\', ' . $i . ')';
	                } else {
	                    $query = 'UPDATE #__cp_plans_files SET `ordering` = ' . $i . ' WHERE `file_url` = \'' . $file . '\' AND `product_id` = ' . $product_id;
	                }
	                $this->_db->setQuery($query);
	                if (!$this->_db->query()) {
	                    $this->setError($this->_db->getErrorMsg());
	                    return false;
	                }

	                $i++;
	            }
	        } else {
	            $query = 'DELETE FROM #__cp_plans_files WHERE product_id = ' . $product_id;
	            $this->_db->setQuery($query);
	            if (!$this->_db->query()) {
	                $this->setError($this->_db->getErrorMsg());
	                return false;
	            }
	        }
        }

        return true;
    }


    /**
     * Obtiene el listado de suplementos asociados al producto
     * @param int $product_id ID del producto al que se le desea obtener los suplementos
     * @return array
     */
    public function getRelatedSupplements($product_id, $getTaxes = true) {
    	if ($getTaxes) {
            $query = 'SELECT a.*, d.tax_id, d.tax_name, d.tax_value, d.published as tax_published 
                FROM #__cp_prm_supplement a INNER JOIN #__cp_plans_supplement b ON a.supplement_id 
                = b.supplement_id LEFT JOIN #__cp_plans_supplement_tax c ON a.supplement_id = c.supplement_id AND 
                b.product_id = c.product_id LEFT JOIN #__cp_prm_tax d ON c.tax_id = d.tax_id WHERE b.product_id = ' . $product_id;
            $query .= ' ORDER BY a.supplement_name ASC, d.tax_name ASC';
    	} else {
            $query = 'SELECT a.* FROM #__cp_prm_supplement a INNER JOIN #__cp_plans_supplement b 
                ON a.supplement_id = b.supplement_id WHERE b.product_id = ' . $product_id;
            $query .= ' ORDER BY a.supplement_name ASC';
    	}

        $this->_db->setQuery($query);

        if ($getTaxes) {
            $result = $this->_db->loadObjectList();

	        // Obtener los impuestos aplicados a cada suplemento
	        $list = array();
	        $lastSupplement = 0;
	        $i = -1;
	        foreach ($result as $key => $row) {
	        	$id = $row->supplement_id;
	        	$tax_id = $row->tax_id;
	        	if ($id != $lastSupplement) {
	        		$i++;
	                $list[$i] = $row;
	                $list[$i]->taxes = array();
	                $lastSupplement = $id;
	        	}
	        	if ($row->tax_id > 0) {
		        	$list[$i]->taxes[$tax_id] = new stdClass();
		        	$list[$i]->taxes[$tax_id]->tax_id = $row->tax_id;
		        	$list[$i]->taxes[$tax_id]->tax_name = $row->tax_name. (($row->tax_published)? '&nbsp;': '*&nbsp;') . ($row->tax_value * 100) . '%';
		        	$list[$i]->taxes[$tax_id]->tax_value = $row->tax_value;
		        	unset($list[$i]->tax_id);
		        	unset($list[$i]->tax_name);
		        	unset($list[$i]->tax_value);
		        	unset($list[$i]->tax_published);
	        	}
            }
        } else {
            $list = $this->_db->loadObjectList();
        }

        return $list;
    }


    /**
     * Obtiene el listado de parámetros de tarificación que aplican al producto.
     * @param int $product_id ID del producto
     * @return array
     */
    public function getRateParams($product_id) {
    	$list = array();

    	// Parámetro 1
        $query = 'SELECT a.* FROM #__cp_prm_plans_param1 a INNER JOIN #__cp_plans_param1 b 
	        ON a.param1_id = b.param1_id WHERE b.product_id = ' . $product_id;
        $query .= ' ORDER BY a.param1_name ASC';
        $this->_db->setQuery($query);
        $list['param1'] = $this->_db->loadAssocList();

    	// Parámetro 2
        $query = 'SELECT a.* FROM #__cp_prm_plans_param2 a INNER JOIN #__cp_plans_param2 b 
	        ON a.param2_id = b.param2_id WHERE b.product_id = ' . $product_id;
        $query .= ' ORDER BY a.capacity ASC, a.param2_name ASC';
        $this->_db->setQuery($query);
        $list['param2'] = $this->_db->loadAssocList();

    	// Parámetro 3
        $query = 'SELECT a.* FROM #__cp_prm_plans_param3 a INNER JOIN #__cp_plans_param3 b 
	        ON a.param3_id = b.param3_id WHERE b.product_id = ' . $product_id;
        $query .= ' ORDER BY a.param3_name ASC';
        $this->_db->setQuery($query);
        $list['param3'] = $this->_db->loadAssocList();

        return $list;
    }


    /**
     * Obtiene el listado de parámetros de tarificación que aplican al producto.
     * @param int $product_id ID del producto
     * @return array
     */
    public function getStockParams($product_id) {
    	// Parámetro 1
        $query = 'SELECT a.param1_id AS param_id, a.param1_name AS param_name FROM #__cp_prm_plans_param1 a INNER JOIN 
	        #__cp_plans_param1 b ON a.param1_id = b.param1_id WHERE b.product_id = ' . $product_id . ' ORDER BY a.param1_name ASC';
        $this->_db->setQuery($query);
        $list = $this->_db->loadAssocList();

        return $list;
    }


    /**
     * Obtiene el inventario de un producto en un rango de fechas dado
     * @param int $product_id ID del producto
     * @param date $start_date Fecha inicial para la consulta
     * @param date $end_date Fecha final para la consulta
     * @return array
     */
    public function getStock($product_id, $start_date, $end_date) {
    	$list = array();
        $query = 'SELECT param_id, `day`, quantity FROM #__cp_plans_stock WHERE product_id = ' . $product_id .
            ' AND `day` BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'';
        $this->_db->setQuery($query);
        $result = $this->_db->loadAssocList();

        // Se organiza en un arreglo cuyo índice es el id del parámetro
        // Los valores son arreglos donde el índice es la fecha, y el valor es el cupo
        // Ejemplo: Array ([1] => Array ([2012-11-08] => 50 [2012-11-09] => 50 [2012-11-10] => 50));
        if (!empty($result)) {
        	foreach ($result as $key => $row) {
        		$param_id = $row['param_id'];
        		if (!isset($list[$param_id])) {
        			$list[$param_id] = array();
        		}
        		$list[$param_id][$row['day']] = $row['quantity'];
        	}
        }
        return $list;
    }


    /**
     * Guardar el inventario de un producto en un intervalo de fechas dado.
     * @param int $product_id
     * @param array $params
     * @param date $start_date
     * @param date $end_date
     * @param int $quantity
     */
    function saveAutomaticStock($product_id, $params, $start_date, $end_date, $quantity) {
        // Verifica que ninguna de las fechas esté vencidas
        $today = date('Y-m-d');
        if ($start_date < $today || $end_date < $today) {
            $this->setError(JText::_('CP.PRODUCT_STOCK_ERROR_INVALID_DATES'));
            return false;
        }

        // Se inicia la transacción.
    	$query = "START TRANSACTION;\n";
    	// Por cada fila se almacena el inventario
    	foreach ($params as $param_id) {
            // Se limpia el inventario del producto por cada parámetro en el rango de fechas dado.
            $query .= "CALL CleanPlanStock($product_id, $param_id, '$start_date', '$end_date');\n";
    		// Sólo se almacenan valores mayor o igual a 1 y fechas no vencidas
    		if ($quantity < 1) continue;
    		$query .= "CALL LoadPlanStock($product_id, $param_id, '$start_date', '$end_date', $quantity);\n";
    	}
        // Se finaliza la transacción
    	$query .= "COMMIT;";
        $this->_db->setQuery($query);
    	$result = $this->_db->queryBatch();
    	// Si hubo error, regrese al estado original
    	if (!$result) {
    		$this->setError($this->_db->getErrorMsg());
            $this->_db->setQuery('ROLLBACK');
            $this->_db->query();
            
    	}

    	return $result;
    }


    /**
     * Guardar el inventario de un producto en un intervalo de fechas dado.
     * @param int $product_id
     * @param array $stock
     * @param date $start_date
     * @param date $end_date
     */
    function saveManualStock($product_id, $stock, $start_date, $end_date) {
        // Verifica que ninguna de las fechas esté vencidas
        $today = date('Y-m-d');
        if ($start_date < $today || $end_date < $today) {
            $this->setError(JText::_('CP.PRODUCT_STOCK_ERROR_INVALID_DATES'));
            return false;
        }

        // Se inicia la transacción.
    	$query = "START TRANSACTION;\n";
        // Se limpia el inventario del producto en el rango de fechas dado.
        $query .= "CALL CleanPlanStock($product_id, 0, '$start_date', '$end_date');\n";

        // Por cada fila se almacena el inventario
    	foreach ($stock as $param_id => $values) {
    		foreach ($values as $date => $quantity) {
    			// Sólo se almacenan valores mayor o igual a 1 y fechas no vencidas
    			if ($quantity < 1 || $date < $today) continue;
                $query .= "CALL LoadPlanStock($product_id, $param_id, '$date', '$date', $quantity);\n";
    		}
    	}
    	// Se finaliza la transacción
    	$query .= "COMMIT;";
        $this->_db->setQuery($query);
    	$result = $this->_db->queryBatch();
        // Si hubo error, regrese al estado original
    	if (!$result) {
    		$this->setError($this->_db->getErrorMsg());
            $this->_db->setQuery('ROLLBACK');
            $this->_db->query();
            
    	}

    	return $result;
    }
}
