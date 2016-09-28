<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: supplier.php 2012-09-11 8:29:50 svn $
 * @author Yusely Palacios
 * @package Joomla
 * @subpackage catalogo_planes
 * @license Copyright (c) 2012 Amadeus - All Rights Reserved
 *
 *
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * supplier Controller
 *
 * @package Joomla
 * @subpackage catalogo_planes
 */
class CatalogoPlanesControllerSupplier extends JController {

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
		$mainframe =& JFactory::getApplication(); 

		// obtiene el modelo
		$model =& $this->getModel('supplier');
		$view = & $this->getView('supplier', 'html');
		$view->setModel($model, true);

		// Recupera la información
		$lists = array();
		$data = $model->getList();
		$pagination = & $model->getPagination();
		$filter_prefix = $model->getFilterPrefix();
		$filter_order = $model->getOrderByField();
		$filter_order_Dir = $model->getOrderByDirection();

		// Recupera los valores de los filtros
		$filter_state = $mainframe->getUserStateFromRequest($filter_prefix . 'filter_state', 'filter_state', '', 'word');
		$country_id = $mainframe->getUserStateFromRequest($filter_prefix . 'country_id', 'filter_country_id', '', 'int');
		$city_id = $mainframe->getUserStateFromRequest($filter_prefix . 'city_id', 'filter_city_id', '', 'int');
		$search = $mainframe->getUserStateFromRequest($filter_prefix . 'search', 'search', '', 'string');
		// Limpia el valor de búsqueda
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		// Generar lista de países
		$countryModel =& $this->getModel('country');
		// Traer todos los países
		$countries = $countryModel->getList(true, array('published' => 1));
		$opt = JHTML::_('select.option', '', ' - ' . JText::_('CP.SELECT_A_COUNTRY') . ' - ', 'country_id', 'country_name');
		array_unshift($countries, $opt);
		$lists['countries'] = JHTML::_('select.genericlist', $countries, 'filter_country_id', 'class="inputbox" onchange="document.getElementById(\'filter_city_id\').selectedIndex=0;submitform();" size="1"', 'country_id', 'country_name', $country_id);

		// Generar lista de ciudades
		$opt = JHTML::_('select.option', '', ' - ' . JText::_('CP.SELECT_A_CITY') . ' - ', 'city_id', 'city_name');
		if ($country_id > 0) {
			// Se obtiene el modelo de la región
			$cityModel =& $this->getModel('city');
			// Traer todas las ciudades de ese país
			$cityConditions = array('country_id' => $country_id);
			$cities = $cityModel->getList(true, $cityConditions);
			array_unshift($cities, $opt);
		} else {
			$cities = array();
			$cities[] = $opt;
		}
		$lists['cities'] = JHTML::_('select.genericlist', $cities, 'filter_city_id', 'class="inputbox" onchange="submitform();" size="1"', 'city_id', 'city_name', $city_id);

		// filtro de estado
		$lists['state'] = JHTML::_('grid.state', $filter_state);

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
		$model =& $this->getModel('supplier');
		$view = & $this->getView('supplier', 'html');
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
		$id = $cid[0];

		$data = $model->getRow($id);
		// Redireccionar cuando el registro no es encontrado.
		if ($id && empty($data->supplier_name)) {
			$this->setRedirect('index.php?option=' . $option . '&view=supplier', JText::_('CP.ROW_UNKNOWN'), 'error');
		}

		// Obtener productos relacionados
		if ($id) {
			$productList = $model->getRelatedProducts($id);
			$view->assignRef('productList', $productList);
		}

		// Generar lista de países
		$countryModel =& $this->getModel('country');
		// Traer todos los países
		$countries = $countryModel->getList(true, array('published' => 1));
		$opt = JHTML::_('select.option', '', ' - ' . JText::_('CP.SELECT_A_COUNTRY') . ' - ', 'country_id', 'country_name');
		array_unshift($countries, $opt);
		$data->countries = JHTML::_('select.genericlist', $countries, 'country_id', 'class="inputbox" size="1"', 'country_id', 'country_name', $data->country_id);

		// Generar lista de ciudades
		$opt = JHTML::_('select.option', '', ' - ' . JText::_('NONE') . ' - ', 'city_id', 'city_name');
		if ($data->country_id > 0) {
			// Se obtiene el modelo de la región
			$cityModel =& $this->getModel('city');
			// Traer todas las ciudades de ese país
			$cityConditions = array('country_id' => $data->country_id, 'published' => 1);
			$cities = $cityModel->getList(true, $cityConditions);
			array_unshift($cities, $opt);
		} else {
			$cities = array();
			$cities[] = $opt;
		}
		$data->cities = JHTML::_('select.genericlist', $cities, 'city_id', 'class="inputbox" size="1"', 'city_id', 'city_name', $data->city_id);

		// Obtiene los tipos de productos activos
		$modelProductTypes =& $this->getModel('producttype');
		$productTypes = $modelProductTypes->getActiveProductTypesInfo();
		$view->assignRef('productTypes', $productTypes);

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

		$model =& $this->getModel('supplier');

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
		$link = 'index.php?option=' . $option . '&view=supplier';
		if ($this->task == 'apply') {
			$supplier_id = JRequest::getInt('supplier_id');
			if ($supplier_id < 1) {
				$data =& $model->getRow();
				$supplier_id = $data->supplier_id;
			}
			$link .= '&task=edit&cid[]=' . $supplier_id;
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
		$model = $this->getModel('supplier');
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

		$this->setRedirect('index.php?option=' . $option . '&view=supplier', $msg, $type);
	}


	/**
	 * Publica registro(s) y redirecciona a la lista
	 * @return void
	 */
	function publish() {
		$option = JRequest::getCmd('option');

		// Se obtiene el modelo
		$model = $this->getModel('supplier');
		// Se obtienen los ids de los registros a publicar
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		JArrayHelper::toInteger($cids);

		// Lanzar error si no se ha seleccionado al menos un registro a publicar
		if (count($cids) < 1) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_PUBLISH'));
		}

		if ($model->publish($cids, true)) {
			$msg = JText::_('CP.ROWS_PUBLISHED');
			$type = 'message';
		} else {
			// Si hay algún error se ajusta el mensaje
			$msg = $model->getError();
			if (!$msg) {
				$msg = JText::_('CP.ERROR_ONE_OR_MORE_DATA_COULD_NOT_BE_PUBLISHED');
			}
			$type = 'error';
		}
		$this->setRedirect('index.php?option=' . $option . '&view=supplier', $msg, $type);
	}


	/**
	 * Despublica registro(s) y redirecciona a la lista
	 * @return void
	 */
	function unpublish() {
		$option = JRequest::getCmd('option');

		// Se obtiene el modelo
		$model = $this->getModel('supplier');
		// Se obtienen los ids de los registros a publicar
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		JArrayHelper::toInteger($cids);

		// Lanzar error si no se ha seleccionado al menos un registro a despublicar
		if (count($cids) < 1) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		if ($model->publish($cids, false)) {
			$msg = JText::_('CP.ROWS_UNPUBLISHED');
			$type = 'message';
		} else {
			// Si hay algún error se ajusta el mensaje
			$msg = $model->getError();
			if (!$msg) {
				$msg = JText::_('CP.ERROR_ONE_OR_MORE_DATA_COULD_NOT_BE_UNPUBLISHED');
			}
			$type = 'error';
		}
		$this->setRedirect('index.php?option=' . $option . '&view=supplier', $msg, $type);
	}
}
