<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: transfers.php 2012-09-11 8:29:50 svn $
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
 * transfers Controller
 *
 * @package Joomla
 * @subpackage catalogo_planes
 */
class CatalogoPlanesControllerTransfers extends JController {

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
		$this->registerTask('accesspublic', 'access');
		$this->registerTask('accessregistered', 'access');
		$this->registerTask('accessspecial', 'access');
	}


	/**
	 * Despliega la interfaz con la lista de registros
	 */
	function display() {
		$mainframe =& JFactory::getApplication(); 

		// obtiene el modelo
		$model =& $this->getModel('transfers');
		$view = & $this->getView('transfers', 'html');
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
		$country_id = $mainframe->getUserStateFromRequest($filter_prefix . 'country_id', 'country_id', '', 'int');
		$region_id = $mainframe->getUserStateFromRequest($filter_prefix . 'region_id', 'region_id', '', 'int');
		$city_id = $mainframe->getUserStateFromRequest($filter_prefix . 'city_id', 'city_id', '', 'int');
		$category_id = $mainframe->getUserStateFromRequest($filter_prefix . 'category_id', 'category_id', '', 'int');
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
		$opt = JHTML::_('select.option', '', ' - ' . JText::_('CP.COUNTRY') . ' - ', 'country_id', 'country_name');
		array_unshift($countries, $opt);
		$lists['countries'] = JHTML::_('select.genericlist', $countries, 'country_id', 'class="inputbox" onchange="document.getElementById(\'region_id\').selectedIndex=0;document.getElementById(\'city_id\').selectedIndex=0;submitform();" size="1"', 'country_id', 'country_name', $country_id);

		// Generar lista de regiones
		$opt = JHTML::_('select.option', '', ' - ' . JText::_('CP.REGION') . ' - ', 'region_id', 'region_name');
		if ($country_id > 0) {
			// Se obtiene el modelo de la región
			$regionModel =& $this->getModel('region');
			// Traer todas las regiones de ese país
			$regionConditions = array('country_id' => $country_id);
			$regions = $regionModel->getList(true, $regionConditions);
			array_unshift($regions, $opt);
		} else {
			$regions = array();
			$regions[] = $opt;
		}
		$lists['regions'] = JHTML::_('select.genericlist', $regions, 'region_id', 'class="inputbox" onchange="document.getElementById(\'city_id\').selectedIndex=0;submitform();" size="1"', 'region_id', 'region_name', $region_id);

		// Generar lista de ciudades
		$opt = JHTML::_('select.option', '', ' - ' . JText::_('CP.CITY') . ' - ', 'city_id', 'city_name');
		if ($country_id > 0) {
			// Se obtiene el modelo de la región
			$cityModel =& $this->getModel('city');
			// Traer todas las ciudades de ese país
			$cityConditions = array('country_id' => $country_id);
			if ($region_id > 0) {
				$cityConditions['region_id'] = $region_id;
			}
			$cities = $cityModel->getList(true, $cityConditions);
			array_unshift($cities, $opt);
		} else {
			$cities = array();
			$cities[] = $opt;
		}
		$lists['cities'] = JHTML::_('select.genericlist', $cities, 'city_id', 'class="inputbox" onchange="submitform();" size="1"', 'city_id', 'city_name', $city_id);

		// Generar lista de tipos de alojamientos
		$opt = JHTML::_('select.option', '', ' - ' . JText::_('CP.TRANSFERCATEGORY') . ' - ', 'category_id', 'category_name');
		// Se obtiene el modelo de la región
		$categoryModel =& $this->getModel('transfercategory');
		$categories = $categoryModel->getList(false);
		array_unshift($categories, $opt);
		$lists['categories'] = JHTML::_('select.genericlist', $categories, 'category_id', 'class="inputbox" onchange="submitform();" size="1"', 'category_id', 'category_name', $category_id);
                
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
		$model =& $this->getModel('transfers');
		$view = & $this->getView('transfers', 'html');
		$view->setModel($model, true);
		$view->setLayout('form');

		$component = JComponentHelper::getComponent($option)->params;
                $params =   $component->get('params');
		$allLabel = ' - ' . JText::_('CP.ALL_M') . ' - ';
		$emptySelectOption = ' - ' . JText::_('CP.SELECT') . ' - ';
		$view->assignRef('emptySelectOption', $emptySelectOption);

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
		if ($id && empty($data->product_name)) {
			$this->setRedirect('index.php?option=' . $option . '&view=transfers', JText::_('CP.ROW_UNKNOWN'), 'error');
		}

		// Generar lista de países
		$countryModel =& $this->getModel('country');
		// Traer todos los países
		$countries = $countryModel->getList(false);
		// Se le coloca el asterico a los países que están inactivos
		foreach ($countries as $key => $country) {
			if ($country->published != 1) {
				$countries[$key]->country_name = $country->country_name . '*';
			}
		}
		// Se agrega opción vacía al inicio
		$opt = JHTML::_('select.option', '', $emptySelectOption, 'country_id', 'country_name');
		array_unshift($countries, $opt);
		$data->countries = JHTML::_('select.genericlist', $countries, 'detailscountry_id', 'class="inputbox" size="1"', 'country_id', 'country_name', $data->country_id);

		// Generar lista de regiones si está habilitado el uso de este combo
		if ($params->cfg_use_regions == '1') {
			$opt = JHTML::_('select.option', '', $emptySelectOption, 'region_id', 'region_name');
			if ($data->country_id > 0) {
				// Se obtiene el modelo de la región
				$regionModel =& $this->getModel('region');
				// Traer todas las regiones de ese país
				$regionConditions = array('country_id' => $data->country_id);
				$regions = $regionModel->getList(true, $regionConditions);

				// Se le coloca el asterico a las regiones que están inactivas
				foreach ($regions as $key => $region) {
					if ($region->published != 1) {
						$regions[$key]->region_name = $region->region_name . '*';
					}
				}
				// Se agrega opción vacía al inicio
				array_unshift($regions, $opt);
			} else {
				$regions = array();
				$regions[] = $opt;
			}
			$data->regions = JHTML::_('select.genericlist', $regions, 'detailsregion_id', 'class="inputbox" size="1"', 'region_id', 'region_name', $data->region_id);
		}

		// Generar lista de ciudades
		$opt = JHTML::_('select.option', '', $emptySelectOption, 'city_id', 'city_name');
		if ($data->country_id > 0) {
			// Se obtiene el modelo de la región
			$cityModel =& $this->getModel('city');
			// Traer todas las ciudades de ese país
			$cityConditions = array('country_id' => $data->country_id);
			if ($data->region_id > 0) {
				$cityConditions['region_id'] = $data->region_id;
			}
			$cities = $cityModel->getList(true, $cityConditions);

			// Se le coloca el asterico a las ciudades que están inactivas
			foreach ($cities as $key => $city) {
				if ($city->published != 1) {
					$cities[$key]->city_name = $city->city_name . '*';
				}
			}
			// Se agrega opción vacía al inicio
			array_unshift($cities, $opt);
		} else {
			$cities = array();
			$cities[] = $opt;
		}
		$data->cities = JHTML::_('select.genericlist', $cities, 'detailscity_id', 'class="inputbox" size="1"', 'city_id', 'city_name', $data->city_id);

		// Generar lista de parámetros de tarificación
		$param1Model =& $this->getModel('transferparam1');
		$rateparams1 = $param1Model->getList(false);
		$view->assignRef('rateparams1', $rateparams1);

		$param2Model =& $this->getModel('transferparam2');
		$rateparams2 = $param2Model->getList(false);
		$view->assignRef('rateparams2', $rateparams2);

		$param3Model =& $this->getModel('transferparam3');
		$rateparams3 = $param3Model->getList(false);
		$view->assignRef('rateparams3', $rateparams3);

		// Generar lista de suplementos del producto
		if ($id > 0) {
			$data->supplements = $model->getRelatedSupplements($id);
		}
//echo "task=";print_r($data->categories);die();
		// Generar lista de tipos producto
		$modelProducts =& $this->getModel('producttype');
		$productTypes = $modelProducts->getActiveProductTypesInfo();
		$view->assignRef('productTypes', $productTypes);

		//Obtiene los tipos de turismo registrados
		$modelTourism =& $this->getModel('tourismtype');
		$tourismTypes = $modelTourism->getList(true, array('published' => 1));
		array_unshift($tourismTypes, JHTML::_('select.option', '0', $allLabel, 'tourismtype_id', 'tourismtype_name'));
		$view->assignRef('tourismTypes', $tourismTypes);

		// Generar lista de impuestos
		$taxesModel =& $this->getModel('tax');
		$taxes = $taxesModel->getList(true, array('published' => 1, 'product_type_code' => 'transfers'));
		$view->assignRef('taxes', $taxes);

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

		$model =& $this->getModel('transfers');

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
		$link = 'index.php?option=' . $option . '&view=transfers';
		if ($this->task == 'apply') {
			$product_id = JRequest::getInt('product_id');
			if ($product_id < 1) {
				$data =& $model->getRow();
				$product_id = $data->product_id;
			}
			$link .= '&task=edit&cid[]=' . $product_id;
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
		if (empty($cids)) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_DELETE'));
		}

		// Se obtiene el modelo
		$model = $this->getModel('transfers');
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

		$this->setRedirect('index.php?option=' . $option . '&view=transfers', $msg, $type);
	}


	/**
	 * Publica registro(s) y redirecciona a la lista
	 * @return void
	 */
	function publish() {
		$option = JRequest::getCmd('option');

		// Se obtiene el modelo
		$model = $this->getModel('transfers');
		// Se obtienen los ids de los registros a publicar
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		JArrayHelper::toInteger($cids);

		// Lanzar error si no se ha seleccionado al menos un registro a publicar
		if (empty($cids)) {
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
		$this->setRedirect('index.php?option=' . $option . '&view=transfers', $msg, $type);
	}


	/**
	 * Despublica registro(s) y redirecciona a la lista
	 * @return void
	 */
	function unpublish() {
		$option = JRequest::getCmd('option');

		// Se obtiene el modelo
		$model = $this->getModel('transfers');
		// Se obtienen los ids de los registros a publicar
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		JArrayHelper::toInteger($cids);

		// Lanzar error si no se ha seleccionado al menos un registro a despublicar
		if (empty($cids)) {
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
		$this->setRedirect('index.php?option=' . $option . '&view=transfers', $msg, $type);
	}


	/**
	 * Sube un nivel el orden del registro
	 */
	function orderup() {
		$option = JRequest::getCmd('option');
		// Se obtienen los ids de los registros a ordenar
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');

		// Lanzar error si no se ha seleccionado al menos un registro
		if (empty($cids)) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_EDIT'));
		}
		$cid = (int) $cids[0];

		$model =& $this->getModel('transfers');
		$model->move($cid, -1);
		$this->setRedirect('index.php?option=' . $option . '&view=transfers', $msg, $type);
	}


	/**
	 * Baja un nivel el orden del registro
	 */
	function orderdown() {
		$option = JRequest::getCmd('option');
		// Se obtienen los ids de los registros a ordenar
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');

		// Lanzar error si no se ha seleccionado al menos un registro
		if (empty($cids)) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_EDIT'));
		}
		$cid = (int) $cids[0];

		$model =& $this->getModel('transfers');
		$model->move($cid, 1);
		$this->setRedirect('index.php?option=' . $option . '&view=transfers');
	}


	/**
	 * Guarda el orden de uno o más registros
	 */
	function saveorder() {
		$option = JRequest::getCmd('option');

		$cid    = JRequest::getVar('cid', array(), 'post', 'array');
		$order  = JRequest::getVar('order', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model =& $this->getModel('transfers');
		$model->saveorder($cid, $order);

		$msg = JText::_('CP.NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=transfers');
	}


	/**
	 * Despliega la interfaz con la lista de registros
	 */
	function getRawList() {
		$mainframe =& JFactory::getApplication(); 
		$conditions = array();

		// obtiene el modelo
		$model =& $this->getModel('transfers');

		// Recupera los valores de los filtros
		$country_id = JRequest::getInt('country_id');
		if ($country_id > 0) {
			$conditions['country_id'] = $country_id;
		}
		$region_id = JRequest::getInt('region_id');
		if ($region_id > 0) {
			$conditions['region_id'] = $region_id;
		}

		// Recupera la información
		$data = $model->getList(true, $conditions);
		$document =& JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		die(Zend_Json_Encoder::encode($data));
	}


	/**
	 * Changes the access level of a record
	 */
	function access() {
		$option = JRequest::getCmd('option');

		$cids  = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cids);

		// Lanzar error si no se ha seleccionado al menos un registro a modificar
		if (empty($cids)) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_EDIT'));
		}

		$task = JRequest::getCmd('task');
		switch ($task) {
			case 'accesspublic':
				$access = 0;
				break;

			case 'accessregistered':
				$access = 1;
				break;

			case 'accessspecial':
				$access = 2;
				break;
		}

		$row =& JTable::getInstance('transfers', 'Table');
		if ($row->changeAccess($cids, $access)) {
			$msg = JText::_('CP.DATA_SAVED');
			$type = 'message';
		} else {
			$msg = $row->getError();
			if (!$msg) {
				$msg = JText::_('CP.ERROR_SAVING_DATA');
			}
			$type = 'error';
		}
		$link = 'index.php?option=' . $option . '&view=transfers';
		$this->setRedirect($link, $msg, $type);
	}


	/**
	 * Cambia el estado de destacado del producto
	 */
	function toggleFeatured() {
		$option = JRequest::getCmd('option');

		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cids);

		// Lanzar error si no se ha seleccionado al menos un registro a modificar
		if (empty($cids)) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_EDIT'));
		}

		$row =& JTable::getInstance('transfers', 'Table');
		if ($row->toggleFeatured($cids)) {
			$msg = JText::_('CP.DATA_SAVED');
			$type = 'message';
		} else {
			$msg = $row->getError();
			if (!$msg) {
				$msg = JText::_('CP.ERROR_SAVING_DATA');
			}
			$type = 'error';
		}
		$link = 'index.php?option=' . $option . '&view=transfers';
		$this->setRedirect($link, $msg, $type);
	}


	/**
	 * Duplicar un producto
	 */
	function duplicate() {
		$option = JRequest::getCmd('option');
		// Obtener id del producto
		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$cid = (int) $cid[0];

		// Lanzar error si no se ha seleccionado al menos un registro a duplicar
		if ($cid < 1) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_DUPLICATE'));
		}

		$model =& $this->getModel('transfers');
		$product_id = $model->duplicate($cid);

		// Redireccionar
		$link = 'index.php?option=' . $option . '&view=transfers';

		// Si se logró duplicar el producto, redireccionar a la página de edición
		if ($product_id > 0) {
			$msg = JText::_('CP.DATA_SAVED');
			$type = 'message';
		} else {
			$msg = $model->getError();
			if (!$msg) {
				$msg = JText::_('CP.ERROR_SAVING_DATA');
			}
			$type = 'error';
		}
		$this->setRedirect($link, $msg, $type);
	}


	/**
	 * Redireccionar a listado de tarifas
	 */
	function listrates() {
		$option = JRequest::getCmd('option');

		// Obtener id del producto
		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$cid = (int) $cid[0];

		// Lanzar error si no se ha seleccionado un registro a ver los tarifarios
		if ($cid < 1) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_RATE'));
		}

		// Redireccionar
		$this->setRedirect('index.php?option=' . $option . '&view=transferrate&product_id=' . $cid . '&season_year=' . date('Y'));
	}


	/**
	 * Redireccionar a manejo de Stock
	 */
	function showstock() {
		$option = JRequest::getCmd('option');

		// Obtener id del producto
		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$cid = (int) $cid[0];

		// Lanzar error si no se ha seleccionado un registro a ver su stock
		if ($cid < 1) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_STOCK'));
		}

		$model =& $this->getModel('transfers');
		$view = & $this->getView('transfers', 'html');
		$view->setModel($model, true);
		$view->setLayout('default_stock');

		// Nombre del producto
		$productInfo = $model->getRow($cid, false);
		// Redireccionar cuando el registro no es encontrado.
		if (empty($productInfo->product_name)) {
			$this->setRedirect('index.php?option=' . $option . '&view=transfers', JText::_('CP.ROW_UNKNOWN'), 'error');
		}
		$view->assignRef('product_name', $productInfo->product_name);

		// Se toman los datos para la consulta
		$today = date('Y-m-d');
		$limit = date('Y-m-d', strtotime('+1 month'));
		$manual_start_date = JRequest::getVar('manual_start_date', $today);
		$manual_end_date = JRequest::getVar('manual_end_date', $limit);

		$limit = date('Y-m-d', strtotime('+1 year'));
		$auto_start_date = JRequest::getVar('auto_start_date', $today);
		$auto_end_date = JRequest::getVar('auto_end_date', $limit);

		$data = new stdClass();
		$data->product_id = $cid;
		$data->manual_start_date = $manual_start_date;
		$data->manual_end_date = $manual_end_date;
		$data->auto_start_date = $auto_start_date;
		$data->auto_end_date = $auto_end_date;
		$data->stockParams = $model->getStockParams($cid);
		$data->stockValues = $model->getStock($cid, $manual_start_date, $manual_end_date);

		// Asignar la información a la vista
		$view->assignRef('data', $data);

		$view->display();
	}


	/**
	 * Guardar el inventario de forma masiva
	 * @return void
	 */
	function autoloadstock() {
		$option = JRequest::getCmd('option');

		// Obtener id del producto
		$product_id = JRequest::getInt('product_id');

		// Lanzar error si no se ha seleccionado un registro a modificar su stock
		if ($product_id < 1) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_UPDATE_STOCK'));
		}

		// Verificar que se tengan los datos adecuados
		$params = JRequest::getVar('param_id', array(0), 'method', 'array');
		if (count($params) < 1) {
			JError::raiseError(500, JText::_('CP.TRANSFER_ERROR_EMPTY_PARAM1'));
		}
		$quantity = JRequest::getInt('quantity', null);
		if (is_null($quantity)) {
			JError::raiseError(500, JText::_('CP.PRODUCT_STOCK_ERROR_INVALID_QUANTITY'));
		}
		$start_date = JRequest::getVar('auto_start_date');
		if (!$start_date) {
			JError::raiseError(500, JText::_('CP.PRODUCT_STOCK_ERROR_EMPTY_START_DATE'));
		}
		$end_date = JRequest::getVar('auto_end_date');
		if (!$end_date) {
			JError::raiseError(500, JText::_('CP.PRODUCT_STOCK_ERROR_EMPTY_END_DATE'));
		}

		$model =& $this->getModel('transfers');

		//  Se almacena el stock
		if ($model->saveAutomaticStock($product_id, $params, $start_date, $end_date, $quantity)) {
			$msg = JText::_('CP.DATA_SAVED');
			$type = 'message';
		} else {
			$msg = $model->getError();
			if (!$msg) {
				$msg = JText::_('CP.ERROR_SAVING_DATA');
			}
			$type = 'error';
		}

		// Redireccionar de acuerdo al botón presionado
		$link = 'index.php?option=' . $option . '&view=transfers';
		$realtask = JRequest::getVar('realtask');
		if ($realtask == 'apply') {
			$link .= '&task=showstock&cid[]=' . $product_id;
		}
		$this->setRedirect($link, $msg, $type);
	}


	/**
	 * Guardar el inventario digitado
	 * @return void
	 */
	function manualloadstock() {
		$option = JRequest::getCmd('option');

		// Obtener id del producto
		$product_id = JRequest::getInt('product_id');

		// Lanzar error si no se ha seleccionado un registro a modificar su stock
		if ($product_id < 1) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_UPDATE_STOCK'));
		}

		// Verificar que se tengan los datos adecuados
		$stock = JRequest::getVar('stock', array(0), 'method', 'array');
		if (count($stock) < 1) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_UPDATE_STOCK'));
		}
		$start_date = JRequest::getVar('manual_start_date');
		if (!$start_date) {
			JError::raiseError(500, JText::_('CP.PRODUCT_STOCK_ERROR_EMPTY_START_DATE'));
		}
		$end_date = JRequest::getVar('manual_end_date');
		if (!$end_date) {
			JError::raiseError(500, JText::_('CP.PRODUCT_STOCK_ERROR_EMPTY_END_DATE'));
		}

		$model =& $this->getModel('transfers');

		if ($model->saveManualStock($product_id, $stock, $start_date, $end_date)) {
			$msg = JText::_('CP.DATA_SAVED');
			$type = 'message';
		} else {
			$msg = $model->getError();
			if (!$msg) {
				$msg = JText::_('CP.ERROR_SAVING_DATA');
			}
			$type = 'error';
		}

		// Redireccionar de acuerdo al botón presionado
		$link = 'index.php?option=' . $option . '&view=transfers';
		$realtask = JRequest::getVar('realtask');
		if ($realtask == 'apply') {
			$link .= '&task=showstock&cid[]=' . $product_id;
		}
		$this->setRedirect($link, $msg, $type);
	}
}
