<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: supplement.php 2012-09-11 8:29:50 svn $
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
 * supplement Controller
 *
 * @package Joomla
 * @subpackage catalogo_planes
 */
class CatalogoPlanesControllerSupplement extends JController {

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
		$model =& $this->getModel('supplement');
		$view = & $this->getView('supplement', 'html');
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
		$search = $mainframe->getUserStateFromRequest($filter_prefix . 'search', 'search', '', 'string');
		// Limpia el valor de búsqueda
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		// filtro de estado
		$lists['state']	= JHTML::_('grid.state', $filter_state);

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
		$model =& $this->getModel('supplement');
		$view = & $this->getView('supplement', 'html');
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
		if ($id && empty($data->supplement_name)) {
			$this->setRedirect('index.php?option=' . $option . '&view=supplement', JText::_('CP.ROW_UNKNOWN'), 'error');
		}

		// Obtener productos relacionados
		if ($id) {
			$productList = $model->getRelatedProducts($id);
			$view->assignRef('productList', $productList);
		}

		// Obtiene los tipos de productos activos
		$modelProductTypes =& $this->getModel('producttype');
		$productTypes = $modelProductTypes->getActiveProductTypesInfo();
		$view->assignRef('productTypes', $productTypes);

		//Obtiene los tipos de turismo registrados
		$modelTourism =& $this->getModel('tourismtype');
		$tourismTypes = $modelTourism->getList(false);
		$view->assignRef('tourismTypes', $tourismTypes);

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

		$model =& $this->getModel('supplement');

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

		if (JRequest::getVar('format') == 'raw') {
			$row =& $model->getRow();
			$document =& JFactory::getDocument();
			$document->setMimeEncoding('application/json');
			$data = new stdClass();
			$data->result = $type;
			$data->supplement_id = $row->supplement_id;
			$data->message = $msg;
			die(Zend_Json_Encoder::encode($data));
		} else {
			// Redireccionar
			$link = 'index.php?option=' . $option . '&view=supplement';
			if ($this->task == 'apply') {
				$supplement_id = JRequest::getInt('supplement_id');
				if ($supplement_id < 1) {
					$data =& $model->getRow();
					$supplement_id = $data->supplement_id;
				}
				$link .= '&task=edit&cid[]=' . $supplement_id;
			} elseif ($this->task != 'save') {
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
		$model = $this->getModel('supplement');
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

		$this->setRedirect('index.php?option=' . $option . '&view=supplement', $msg, $type);
	}


	/**
	 * Publica registro(s) y redirecciona a la lista
	 * @return void
	 */
	function publish() {
		$option = JRequest::getCmd('option');

		// Se obtiene el modelo
		$model = $this->getModel('supplement');
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
		$this->setRedirect('index.php?option=' . $option . '&view=supplement', $msg, $type);
	}


	/**
	 * Despublica registro(s) y redirecciona a la lista
	 * @return void
	 */
	function unpublish() {
		$option = JRequest::getCmd('option');

		// Se obtiene el modelo
		$model = $this->getModel('supplement');
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
		$this->setRedirect('index.php?option=' . $option . '&view=supplement', $msg, $type);
	}


	/**
	 * Despliega la interfaz con la lista de registros
	 */
	function getRawList() {
		$mainframe =& JFactory::getApplication(); 
		$conditions = array();

		// obtiene el modelo
		$model =& $this->getModel('supplement');

		// Recupera los valores de los filtros
		$product_type_id = JRequest::getInt('product_type_id');
		if ($product_type_id > 0) {
			$conditions['product_type_id'] = $product_type_id;
		}
		$tourismtype_id = JRequest::getInt('tourismtype_id');
		if ($tourismtype_id > 0) {
			$conditions['tourismtype_id'] = $tourismtype_id;
		}
		$supplement_id = JRequest::getInt('supplement_id');
		if ($supplement_id > 0) {
			$conditions['supplement_id'] = $supplement_id;
		}
		$published = JRequest::getInt('published', null);
		if ($published != null) {
			$conditions['published'] = $published;
		}

		// Recupera la información
		$data = $model->getList(true, $conditions);
		$document =& JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		echo (Zend_Json_Encoder::encode($data));
		die();
	}
}
