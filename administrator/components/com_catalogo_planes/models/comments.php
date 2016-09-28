<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: comments.php 2012-09-10 18:29:50 svn $
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

class CatalogoPlanesModelComments extends CatalogoPlanesModelBasic {

	function __construct() {
		$option = JRequest::getCmd('option');

		parent::__construct();

        $this->_filter_prefix = $option . '_comments';
    }


	/**
	 * Genera consulta usada en el listado de registros.
	 * @return string
	 */
	protected function _buildQuery($use_filter = true, $conditions = array()) {
        $mainframe =& JFactory::getApplication(); 
		// Obtiene el WHERE y ORDER BY para la consulta
        $where      = $use_filter? $this->_buildQueryWhere($conditions): '';
        $orderby    = ($use_filter && (count($conditions) < 1))? $this->_buildQueryOrderBy(): ' ORDER BY a.created DESC';

        $product_type_code = $mainframe->getUserStateFromRequest($filter_prefix . 'product_type_code', 'product_type_code', 'hotels', 'word');

		$query = ($product_type_code)? 'SELECT a.*, b.product_name FROM #__cp_' . $product_type_code . '_comments 
		a INNER JOIN #__cp_' . $product_type_code . '_info b ON a.product_id = b.product_id ' . $where . $orderby: false;

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
        if ($filter_order != 'product_name') {
        	$filter_order = 'a.' . $filter_order;
        } else {
            $filter_order = 'b.' . $filter_order;
        }

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }

    /**
     * Devuelve la dirección en la que se debe ordenar
     * @return string
     */
    public function getOrderByDirection() {
        $mainframe =& JFactory::getApplication(); 

        // Obtiene el sentido en el que se debe ordenar
        $filter_order_Dir = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_order_Dir', 'filter_order_Dir', 'DESC', 'word');

        // Por defecto el orden es ascendente
        if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC'))) {
            $filter_order_Dir = 'DESC';
        }

        return $filter_order_Dir;
    }


	/**
	 * Devuelve el campo usado para ordenar
	 * @return string
	 */
	public function getOrderByField() {
		$mainframe =& JFactory::getApplication(); 

		// Obtiene el campo por el que se debe ordenar
		$filter_order = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_order', 'filter_order', 'created', 'cmd');

		// se asegura que el orden esté dando por los campos aprobados
		if (!in_array($filter_order, array('comment_id', 'created_by', 'published', 'created', 'comment_rate', 'product_name', 'contact_email', 'end_date'))) {
			$filter_order = 'created';
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
            $allowed_filters['product_id'] = '`product_id`';
            $allowed_filters['published'] = '`published`';
            // Recorre el arreglo de condiciones, y si están entre los permitidos, las usa
            foreach ($conditions as $key => $value) {
                if (array_key_exists($key, $allowed_filters)) {
                    $where[] = $allowed_filters[$key] . ' = ' . $this->_db->Quote($this->_db->getEscaped($value));
                }
            }
        } else {
			// Recupera los valores de los filtros
			$filter_state = $mainframe->getUserStateFromRequest($this->_filter_prefix . 'filter_state', 'filter_state', 'A', 'word');
			switch($filter_state) {
				case 'P':
					$where[] = 'a.`published` = 1';
					break;
				case 'U':
					$where[] = 'a.`published` = 0';
					break;
				case 'A':
					$where[] = 'a.`published` = 2';
					break;
			}
		}

		// Organiza las condiciones de la consulta
		$where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');

		return $where;
	}


    /**
     * Borrar registro(s)
     *
     * @return boolean
     */
    public function delete($cids, $product_type_code) {
        if (count($cids)) {
        	$query = 'DELETE FROM #__cp_' . $product_type_code . '_comments WHERE comment_id = ';
            // Ciclo para borrar cada registro
            foreach($cids as $cid) {
            	$this->_db->setQuery($query . $cid);
                // Si no se pudo borrar, guarde el error
            	if (!$this->_db->query()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
        }
        return true;
    }


    /**
     * Publicar/despublicar registro(s)
     *
     * @param $cids array Arreglo con los ids de los registros a publicar o despublicar
     * @param $publish boolean Si se va a publicar o despublicar
     * @return boolean
     */
    public function publish($cids, $publish = true, $product_type_code) {
    	if (count($cids)) {
    		$status = ($publish)? 1: 0;
    		$query = 'UPDATE #__cp_' . $product_type_code . '_comments SET published = ' . $status . ' WHERE comment_id IN (' . implode(',', $cids) . ')';
    		$this->_db->setQuery($query );
            // se intenta la publicación
    		if (!$this->_db->query()) {
    			$this->setError($this->_db->getErrorMsg());
    			return false;
    		}

    		foreach ($cids as $cid) {
    			$query = 'SELECT product_id FROM #__cp_' . $product_type_code . '_comments WHERE comment_id = ' . $cid;
    			$this->_db->setQuery($query);
    			$product_id = $this->_db->loadResult();
    			if ($product_id) {
    				$query = 'UPDATE #__cp_' . $product_type_code . '_info SET average_rating = (SELECT AVG(comment_rate) FROM #__cp_' .
    				$product_type_code . '_comments WHERE product_id = ' . $product_id . ' AND published = 1) WHERE product_id = ' . $product_id;

    				$this->_db->setQuery($query);
    				// se intenta la publicación
    				if (!$this->_db->query()) {
    					$this->setError($this->_db->getErrorMsg());
    					return false;
    				}
    			}
    		}
    	}
        return true;
    }
}
