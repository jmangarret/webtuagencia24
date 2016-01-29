<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: country.php 2012-09-10 18:29:50 svn $
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

class CatalogoPlanesModelCountry extends CatalogoPlanesModelBasic {

	function __construct() {
		$option = JRequest::getCmd('option');

		parent::__construct();

		$this->_filter_prefix = $option . '_country';
    }


	/**
	 * Genera consulta usada en el listado de registros.
	 * @return string
	 */
	protected function _buildQuery($use_filter = true, $conditions = array()) {
		// Obtiene el WHERE y ORDER BY para la consulta
		$where		= $use_filter? $this->_buildQueryWhere($conditions): '';
		$orderby	= ($use_filter && (count($conditions) < 1))? $this->_buildQueryOrderBy(): ' ORDER BY country_name ASC';

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
		$filter_order = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_order', 'filter_order', 'country_name', 'cmd');

		// se asegura que el orden esté dando por los campos aprobados
		if (!in_array($filter_order, array('country_id', 'country_name', 'country_code', 'published'))) {
			$filter_order = 'country_name';
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
			$allowed_filters['country_id'] = '`country_id`';
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
				$where[] = ' LOWER(`country_name`) LIKE ' . $this->_db->Quote('%' . $this->_db->getEscaped($search, true).'%', false);
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
   function getCountriesList($productType) {
  	 $database = & JFactory::getDBO();

  	 $query = "SELECT
                c.country_id,
                c.country_code,
                c.country_id,
          	  	c.country_name
    			FROM  #__cp_prm_country c, #__cp_".$productType."_info p
   				WHERE p.country_id = c.country_id
     			GROUP BY c.country_id
    			order by c.country_name";
  	 $database->setQuery($query);
  	 $countries = $database->loadObjectList();
  	 return $countries;
   }
   function getRegionsList($regionConditions,$productType) {
  	 $database = & JFactory::getDBO();

  	 $query = "SELECT
                r.region_id,
                r.region_name,
                r.region_code,
                r.country_id,
                c.country_name
                FROM  #__cp_prm_region r
                JOIN #__cp_".$productType."_info p ON p.region_id = r.region_id
                JOIN #__cp_prm_country c ON r.country_id = c.country_id
                WHERE c.country_id=".$regionConditions['country_id']."
                GROUP BY r.region_id
                order by r.region_name";
  	 $database->setQuery($query);
  	 $regions = $database->loadObjectList();
  	 return $regions;
   }
   function getCitiesList($cityConditions,$productType) {
  	 $database = & JFactory::getDBO();
  	 $query .= "SELECT
              c.city_id,
              c.city_name,
              c.city_code,
              c.country_id,
              c.region_id
              FROM  #__cp_prm_city c
              JOIN #__cp_".$productType."_info p
              ON p.city_id = c.city_id
              WHERE c.country_id=".$cityConditions['country_id'];
  	 if($cityConditions['region_id'])
        $query .=" AND c.region_id=".$cityConditions['region_id'];
      $query .="
              GROUP BY c.city_id
              ORDER BY c.city_name";
  	 $database->setQuery($query);
  	 $cities = $database->loadObjectList();
  	 return $cities;
   }
}
