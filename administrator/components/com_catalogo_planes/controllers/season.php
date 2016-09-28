<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: season.php 2012-09-11 8:29:50 svn $
 * @author Yusely Palacios
 * @package Joomla
 * @subpackage catalogo_planes
 * @license Copyright (c) 2012 Amadeus - All Rights Reserved
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * season Controller
 *
 * @package Joomla
 * @subpackage catalogo_planes
 */
class CatalogoPlanesControllerSeason extends JController {

	/**
	 * Constructor
	 * @access private
	 * @subpackage catalogo_planes
	 */
	function __construct($config = array()) {
		parent::__construct();

		// Register Extra tasks
		$this->registerTask('add', 'edit');
		$this->registerTask('apply', 'save');
		$this->registerTask('save2new', 'save');
		$this->registerTask('duplicate', 'edit');
		$this->registerTask('newSpecialSeason', 'edit');
	}


	/**
	 * Despliega la interfaz con la lista de registros
	 */
	function display() {
		$mainframe =& JFactory::getApplication(); 

		// obtiene el modelo
		$model =& $this->getModel('season');
		$view = & $this->getView('season', 'html');
		$view->setModel($model, true);

		// Recupera la información
		$lists = array();
		$data = $model->getList();
		$pagination = & $model->getPagination();
		$filter_prefix = $model->getFilterPrefix();
		$filter_order = $model->getOrderByField();
		$filter_order_Dir = $model->getOrderByDirection();

		// Obtener los rangos de fechas de cada vigencia
		if (is_array($data)) {
			foreach ($data as $index => $row) {
				if (!$row->is_special) {
					$row->dates = $model->getSeasonDates($row->season_id);
				}
			}
		}

		// Recupera los valores de los filtros
		$is_special = $mainframe->getUserStateFromRequest($filter_prefix . 'is_special', 'filter_is_special', -1, 'int');
		$search = $mainframe->getUserStateFromRequest($filter_prefix . 'search', 'search', '', 'string');
		// Limpia el valor de búsqueda
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		// Filtro de tipo de vigencia
		$season_types = array();
		array_push($season_types, array('value' => '-1', 'text' => JText::_('CP.SELECT_A_SEASON_TYPE')));
		array_push($season_types, array('value' => '0', 'text' => JText::_('CP.SEASON_TYPE_STANDARD')));
		array_push($season_types, array('value' => '1', 'text' => JText::_('CP.SEASON_TYPE_SPECIAL')));
		$lists['season_types'] = JHTML::_('select.genericlist', $season_types, 'filter_is_special', 'class="inputbox" onchange="submitform();"', 'value', 'text', $is_special);

		// ordenamiento de la tabla
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// filtro de búsqueda
		$lists['search']= $search;

		// Asignar la información a la vista
		$view->assignRef('data', $data);
		$view->assignRef('lists', $lists);
		$view->assignRef('items', $items);
		$view->assignRef('pagination', $pagination);

		$view->display();
	}


	/**
	 * Despliega formulario para crear/editar un registro
	 */
	function edit() {
		$option = JRequest::getCmd('option');

		// obtiene el modelo
		$model =& $this->getModel('season');
		$view =& $this->getView('season', 'html');
		$view->setModel($model, true);
		$productList = null;

		// obtiene el id del registro a editar
		// si se trata de un arreglo de registros, se edita el primero
		if ($this->task == 'edit') {
			$cid = JRequest::getVar('cid', array(0), 'method', 'array');
			$id = (int) $cid[0];

			// Verificar que no haya tarifarios con ese id de vigencia
			$result = $model->hasRelatedRates($id);
			if ($result) {
				$editable = false;
			} else {
				$editable = true;
			}
			$productList = $model->getRelatedProducts($id);
		} else {
			$id = 0;
			$editable = true;
		}

		$data = $model->getRow($id);
		// Redireccionar cuando el registro no es encontrado.
		if ($id && empty($data->season_name)) {
			$this->setRedirect('index.php?option=' . $option . '&view=season', JText::_('CP.ROW_UNKNOWN'), 'error');
		}
		$view->assignRef('productList', $productList);

		// Verificar si se está duplicando los registros
		if ($this->task == 'duplicate') {
			$cid = JRequest::getVar('cid', array(0), 'method', 'array');
			$idSeason = (int) $cid[0];
			$result = $model->getRawRow($idSeason);
			foreach (get_object_vars($result) as $property => $value) {
				$data->$property = $result->$property;
			}
			$data->season_id = 0;
			$data->season_name = '';
		} else {
			// Ajustar el layout de acuerdo al tipo de vigencia
			if ($id || $data->is_special) {
				$is_special = $data->is_special;
			} else {
				$is_special = JRequest::getInt('is_special');
			}
		}
		if ($is_special || $this->task == 'newSpecialSeason') {
			$view->setLayout('special');
		} else {
			$view->setLayout('standard');
		}

		// Obtiene los tipos de productos activos
		$modelProductTypes =& $this->getModel('producttype');
		$productTypes = $modelProductTypes->getActiveProductTypesInfo();
		$view->assignRef('productTypes', $productTypes);

		// Asignar la información a la vista
		$view->assignRef('data', $data);
		$view->assignRef('editable', $editable);

		$view->display();
	}


	/**
	 * Guardar el registro
	 * @return void
	 */
	function save() {
		$option = JRequest::getCmd('option');

		$model =& $this->getModel('season');
		$id = JRequest::getInt('season_id');
		$format = strtolower(JRequest::getVar('format'));
		$editable = true;

		if ($id > 0 && $format != 'raw') {
			// Verificar que no haya tarifarios con ese id de vigencia
			$result = $model->hasRelatedRates($id);
			if ($result) {
				$editable = false;
				$model->setError(JText::_('CP.SEASON_ERROR_EDIT_EXISTS_RELATED_RATES'));
			}
		}

		if ($editable && $model->store()) {
			$msg = JText::_('CP.DATA_SAVED');
			$type = 'message';
		} else {
			$msg = $model->getError();
			if (!$msg) {
				$msg = JText::_('CP.ERROR_SAVING_DATA');
			}
			$type = 'error';
		}

		if ($format == 'raw') {
			$document =& JFactory::getDocument();
			$document->setMimeEncoding('application/json');
			$row =& $model->getRow();
			$data = new stdClass();
			$data->result = $type;
			$data->season_id = $row->season_id;
			$data->message = $msg;
			die(Zend_Json_Encoder::encode($data));
		} else {
			// Redireccionar
			$link = 'index.php?option=' . $option . '&view=season';
			if ($this->task == 'apply') {
				if ($id < 1) {
					$data =& $model->getRow();
					$id = $data->season_id;
				}
				$link .= '&task=edit&cid[]=' . $id;
			} elseif ($this->task != 'save') {
				//Valido que sea una temporada especial
				if(JRequest::getVar('is_special','')=="1"){
					$link .= '&is_special=1';
				}
				$link .= '&task=add';
			}
			$this->setRedirect($link, $msg, $type);
		}
	}


	/**
	 * Borra registro(s) y redirecciona a la lista
	 * @return void
	 */
	function remove() {
		$option = JRequest::getCmd('option');
		// Se obtienen los ids de los registros a borrar
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');

		// Lanzar error si no se ha seleccionado al menos un registro a borrar
		if (count($cids) < 1) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_DELETE'));
		}

		// Se obtiene el modelo
		$model = $this->getModel('season');
		// Se intenta el borrado
		if ($model->delete($cids)) {
			$msg = JText::_('CP.DATA_DELETED');
			$type = 'message';
		} else {
			// Si hay algún error se ajusta el mensaje
			$msg = $model->getError();
			if (!$msg) {
				$msg = JText::_('CP.ERROR_ONE_OR_MORE_DATA_COULD_NOT_BE_DELETED');
			}
			$type = 'error';
		}

		$this->setRedirect('index.php?option=' . $option . '&view=season', $msg, $type);
	}


	/**
	 * Devuelve las propiedades del registro dado
	 */
	function getRawRow() {
		$mainframe =& JFactory::getApplication(); 

		// Recupera los valores de los filtros
		$season_id = JRequest::getInt('season_id');
		if ($season_id > 0) {
			// obtiene el modelo
			$model =& $this->getModel('season');
			$data = $model->getRawRow($season_id);
			$document =& JFactory::getDocument();
			$document->setMimeEncoding('application/json');
			die(Zend_Json_Encoder::encode($data));
		}
	}


	/**
	 * Despliega la interfaz con la lista de registros
	 */
	function getRawListByProductType() {
		// Recupera los valores de los filtros
		$product_type_code = JRequest::getVar('product_type_code');
		$season_id = JRequest::getInt('season_id');
		$product_id = JRequest::getInt('product_id');
		$rate_id = JRequest::getInt('rate_id');

		// obtiene el modelo
		$model =& $this->getModel('season');
		$data = $model->getSeasonsByProductType($product_type_code, $season_id, $product_id, $rate_id);
		$supplement_id = JRequest::getInt('supplement_id');
		$document =& JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		die(Zend_Json_Encoder::encode($data));
	}
}
