<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: basicmodel.php 2012-09-10 18:29:50 svn $
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

// Import Joomla! libraries
jimport('joomla.application.component.model');

abstract class CatalogoPlanesModelBasic extends JModel {

	/**
	 * Prefijo de los filtros
	 *
	 * @var string
	 */
	var $_filter_prefix = null;

	/**
	 * Rows array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Rows total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;


	function __construct() {
		$option = JRequest::getCmd('option');

		parent::__construct();

		$app =& JFactory::getApplication();

		// Obtiene las variables para la paginación
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

		// En caso que el límite se haya cambiado, ajústelo
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}


	/**
	 * Carga la información de un registro.
	 * @param int $id
	 * @return JTable
	 */
	public function &getRow($id = null, $loadRelationships = true) {
		if (empty($this->_data)) {
			// Obtiene la tabla
			$row =& $this->getTable();

			// Carga la información
			$row->load($id, $loadRelationships);
			// Para registros nuevos, por defecto usar estado activo.
			if ($id < 1 && property_exists($row, 'published')) {
				$row->published = 1;
			}
			$this->_data =& $row;
		}

		return $this->_data;
	}


	/**
	 * Carga la información de varios registros.
	 * @return array
	 */
	public function getList($use_filter = true, $conditions = array()) {
		//die(print_R($conditions));
		// Cargar la información
		if (empty($this->_data)) {
			$query = $this->_buildQuery($use_filter, $conditions);
			if ($query) {
				if ($use_filter && count($conditions) < 1) {
					$limit = $this->getState('limit');
					$limitstart = $this->getState('limitstart');
				} else {
					$limit = 0;
					$limitstart = 0;
				}
				$this->_data = $this->_getList($query, $limitstart, $limit);
			}
		}

		return $this->_data;
	}


	/**
	 * Genera consulta usada en el listado de registros.
	 * @return string
	 */
	abstract protected function _buildQuery($use_filter = true, $conditions = array());


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
	abstract public function getOrderByField();


	/**
	 * Devuelve la dirección en la que se debe ordenar
	 * @return string
	 */
	public function getOrderByDirection() {
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
	abstract protected function _buildQueryWhere($conditions = array());


	/**
	 * Obtiene el total de registros
	 *
	 * @return integer
	 */
	protected function getTotal() {
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}


	/**
	 * Obtiene el objecto de paginación
	 *
	 * @return integer
	 */
	public function getPagination() {
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}


	/**
	 * Devuelve el prefijo a usar en los filtros de listado de registros.
	 * @return string
	 */
	public function getFilterPrefix() {
		return $this->_filter_prefix;
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
		}

		return true;
	}


	/**
	 * Borrar registro(s)
	 *
	 * @return boolean
	 */
	public function delete($cids) {
		if (count($cids)) {
			// Se obtiene la tabla
			$row =& $this->getTable();

			// Ciclo para borrar cada registro
			foreach($cids as $cid) {
				// Si no se pudo borrar, guarde el error
				if (!$row->delete($cid)) {
					$this->setError($row->getError());
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
	public function publish($cids, $publish = true) {
		$status = ($publish)? 1: 0;

		if (count($cids)) {
			// Se obtiene la tabla
			$row =& $this->getTable();
			// se intenta la publicación
			if (!$row->publish($cids, $status)) {
				// si no funciona, se obtiene el error
				$this->setError($row->getError());
				return false;
			}
		}
		return true;
	}


	/**
	 * Cambia el orden del registro hacia arriba o hacia abajo una posición
	 *
	 * @param $direction Dirección en la que desea cambiar el orden
	 * @access  public
	 * @return  boolean True on success
	 */
	public function move($id, $direction) {
		$row =& $this->getTable();

		if (!$row->load($id)) {
			$this->setError($row->getError());
			return false;
		}

		if (!$row->move($direction)) {
			$this->setError($row->getError());
			return false;
		}

		return true;
	}


	/**
	 * Cambia el orden de uno o más registros
	 *
	 * @param $cid Arreglo con los ids de los registros a cambiar de posición
	 * @param $order Arreglo con el orden que tendrá cada registro
	 * @access  public
	 * @return  boolean True on success
	 */
	public function saveorder($cid = array(), $order) {
		$row =& $this->getTable();

		// update ordering values
		$n = count($cid);
		for ($i=0; $i < $n; $i++) {
			$row->load((int) $cid[$i]);

			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				// No actualice las relaciones del objeto, si existen
				if (!$row->store(false, false)) {
					$this->setError($row->getError());
					return false;
				}
			}
		}

		return true;
	}


	/**
	 * Duplicar registro
	 *
	 * @return int Id del nuevo producto ó cero en caso de error
	 */
	public function duplicate($cid) {
		if ($cid > 0) {
			$row =& $this->getTable();
			$product_id = $row->duplicate($cid);

			// Si no hay un id de producto nuevo, recuperar el error
			if ($product_id < 1) {
				$this->setError($row->getError());
			}
		} else {
			$product_id = 0;
		}

		return $product_id;
	}


	/**
	 * Obtiene el listado de registros resultado de aplicar la relación dada
	 * @param string $relationName Nombre de la relación como aparece en la tabla correspondiente
	 * @return array
	 */
	public function getRelationShipRows($relationName, $id = false, $listObjects = true) {
		$result = array();
		$row =& $this->getTable();

		// Obtener el query para esa relación
		$query = $row->getRelationshipQuery($relationName, $id);
		if ($query) {
			$this->_db->setQuery($query);
			if ($listObjects) {
				$result = $this->_db->loadObjectList();
			} else {
				$result = $this->_db->loadResultArray();
			}
		}

		return $result;
	}
}
