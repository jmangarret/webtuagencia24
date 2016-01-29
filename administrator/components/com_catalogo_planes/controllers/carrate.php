<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: carrate.php 2012-09-11 8:29:50 svn $
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
 * carrate Controller
 *
 * @package Joomla
 * @subpackage catalogo_planes
 */
class CatalogoPlanesControllerCarRate extends JController {

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
	}


	/**
	 * Despliega la interfaz con la lista de registros
	 */
	function display() {
                $option = JRequest::getCmd('option');
                $mainframe =& JFactory::getApplication(); 

		// obtiene el modelo
		$model =& $this->getModel('carrate');
		$modelSeason =& $this->getModel('season');
		$view = & $this->getView('carrate', 'html');
		$view->setModel($model, true);

		// Recupera la información
		$lists = array();
		$data = $model->getList();
		$pagination = & $model->getPagination();
		$filter_prefix = $model->getFilterPrefix();
		$filter_order = substr($model->getOrderByField(), 2);
		$filter_order_Dir = $model->getOrderByDirection();
		$currentYear = date('Y');

		// Recupera los valores de los filtros
		$product_id = $mainframe->getUserStateFromRequest($filter_prefix . 'product_id', 'product_id', 0, 'int');
		// Redireccionar cuando no se da ID del producto.
		if (!$product_id) {
			$this->setRedirect('index.php?option=' . $option . '&view=cars', JText::_('CP.ROW_UNKNOWN'), 'error');
		}
		$modelProduct =& $this->getModel('cars');
		$productInfo = $modelProduct->getRow($product_id, false);
		// Redireccionar cuando el registro no es encontrado.
		if (empty($productInfo->product_name)) {
			$this->setRedirect('index.php?option=' . $option . '&view=cars', JText::_('CP.ROW_UNKNOWN'), 'error');
		}

		$season_year = $mainframe->getUserStateFromRequest($filter_prefix . 'season_year', 'season_year', $currentYear, 'int');
		$lists['product_info'] = $filter_prefix . 'product_id';
		$lists[$filter_prefix . 'product_id'] = $product_id;
		$lists['season_year'] = $season_year;

		// ordenamiento de la tabla
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// Recuperar las fechas de cada vigencia.
		foreach ($data as $key => $row) {
			if (!$row->is_special) {
				$data[$key]->dates = $modelSeason->getSeasonDates($row->season_id);
			}
		}

		// Obtener el nombre del producto.
		$view->assignRef('product_id', $product_id);
		$view->assignRef('product_name', $productInfo->product_name);

		// Filtro de años
		$season_years = array();
		for ($i = ($currentYear - 2); $i < ($currentYear + 2); $i++) {
			array_push($season_years, array('value' => $i, 'text' => $i));
		}
		$lists['season_years'] = JHTML::_('select.genericlist', $season_years, 'season_year', 'class="inputbox" onchange="submitform();"', 'value', 'text', $season_year);

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
                $mainframe =& JFactory::getApplication(); 

		// obtiene el modelo
		$model =& $this->getModel('carrate');
		$modelProduct =& $this->getModel('cars');
		$view = & $this->getView('carrate', 'html');
		$view->setModel($model, true);
		$view->setLayout('form');

		// obtiene el id del registro a editar
		// si se trata de un arreglo de registros, se edita el primero
		if ($this->task == 'edit') {
			$cid = JRequest::getVar('cid', array(0), 'method', 'array');
			$cid = array((int) $cid[0]);
		} else {
			$cid = array(0);
		}

		// Guardar el id del producto en el registro por si se quiere regresar al listado de tarifarios
		$product_id = JRequest::getInt('product_id');
		$filter_prefix = $model->getFilterPrefix();
		$mainframe->setUserState($filter_prefix . 'product_id', $product_id);
		// Redireccionar cuando no se da ID del producto.
		if (!$product_id) {
			$this->setRedirect('index.php?option=' . $option . '&view=cars', JText::_('CP.ROW_UNKNOWN'), 'error');
		}
		$productInfo = $modelProduct->getRow($product_id, false);
		// Redireccionar cuando el registro no es encontrado.
		if (empty($productInfo->product_name)) {
			$this->setRedirect('index.php?option=' . $option . '&view=cars', JText::_('CP.ROW_UNKNOWN'), 'error');
		}

		$rate_id = $cid[0];
		$data = $model->getRow($rate_id);
		// Redireccionar cuando el registro no es encontrado.
		if ($rate_id && !$data->season_id) {
			$this->setRedirect('index.php?option=' . $option . '&view=carrate&product_id=' . $product_id, JText::_('CP.ROW_UNKNOWN'), 'error');
		}

		// Obtener el listado de las vigencias para el combo
		$modelSeason =& $this->getModel('season');

		// Obtener las fechas de la vigencia del tarifario
		if ($rate_id > 0) {
			$seasons = $modelSeason->getSeasonsByProductType('cars', $data->season_id, $product_id, $rate_id);
			$data->dates = $modelSeason->getSeasonDates($data->season_id);
		} else {
			$seasons = $modelSeason->getSeasonsByProductType('cars', 0, $product_id);
			$data->dates = array();
			$data->product_id = $product_id;
			// Por defecto se toma como una vigencia estándar
			$data->is_special = 0;
		}
		$view->assignRef('seasons', $seasons);

		// Obtener el nombre del producto.
		$view->assignRef('product_id', $product_id);
		$view->assignRef('product_name', $productInfo->product_name);

		// Obtener los parámetros de tarificación que aplican al producto
		$rateParams = $modelProduct->getRateParams($product_id);
		$view->assignRef('rateParams', $rateParams);

		// Traer los suplementos relacionados al producto, sin sus impuestos
		$supplements = $modelProduct->getRelatedSupplements($product_id, false);
		$view->assignRef('supplements', $supplements);

		// Asignar la información a la vista
		$view->assignRef('data', $data);

		$view->display();
	}


	/**
	 * Guardar el registro
	 * @return void
	 */
	function save() {
		$option = JRequest::getCmd('option');

		$model =& $this->getModel('carrate');
		$product_id = JRequest::getInt('product_id');

		if ($model->store()) {
			$msg = JText::_('CP.DATA_SAVED');
			$type = 'message';
		} else {
			$msg = $model->getError();
			if (!$msg) {
				$msg = JText::_('CP.ERROR_SAVING_DATA');
			}
			$type = 'error';
		}

		// Redireccionar
		$link = 'index.php?option=' . $option . '&view=carrate&product_id=' . $product_id;
		if ($this->task == 'apply') {
			$rate_id = JRequest::getInt('rate_id');
			if ($rate_id < 1) {
				$data =& $model->getRow();
				$rate_id = $data->rate_id;
			}
			$link .= '&task=edit&cid[]=' . $rate_id;
		} elseif ($this->task != 'save') {
			$link .= '&task=add';
		}
		$this->setRedirect($link, $msg, $type);
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
		$model = $this->getModel('carrate');
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

		$product_id = JRequest::getInt('product_id');
		$this->setRedirect('index.php?option=' . $option . '&view=carrate&product_id=' . $product_id, $msg, $type);
	}


	/**
	 * Verifica si al agregar una vigencia se solaparía en un producto
	 *
	 * @return  array
	 */
	function checkSeasonOverlapping() {
		// obtiene el modelo
		$model =& $this->getModel('carrate');

		// Recupera los valores de los filtros
		$rate_id = JRequest::getInt('rate_id');
		$season_id = JRequest::getInt('season_id');
		$product_id = JRequest::getInt('product_id');

		// Recupera la información
		$list = $model->checkSeasonOverlapping($product_id, $season_id, $rate_id);
		// Devolver el resultado
		if (empty($list)) {
			$data = array('approved' => true);
		} else {
			$data = array('approved' => false, 'list' => $list);
		}
		$document =& JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		die(Zend_Json_Encoder::encode($data));
	}
}