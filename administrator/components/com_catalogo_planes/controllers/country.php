<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: country.php 2012-09-11 8:29:50 svn $
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
 * country Controller
 *
 * @package Joomla
 * @subpackage catalogo_planes
 */
class CatalogoPlanesControllerCountry extends JController {

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
		$model =& $this->getModel('country');
		$view = & $this->getView('country', 'html');
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
		$model =& $this->getModel('country');
		$view = & $this->getView('country', 'html');
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

		$data = $model->getRow($cid[0]);
		// Redireccionar cuando el registro no es encontrado.
		if ($cid[0] && empty($data->country_name)) {
			$this->setRedirect('index.php?option=' . $option . '&view=country', JText::_('CP.ROW_UNKNOWN'), 'error');
		}

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

		$model =& $this->getModel('country');
		$country_id = JRequest::getInt('country_id');
		$published = JRequest::getInt('published');

		// se verifica que no se esté intentando despublicar el país por defecto
		// Obtener preferencias del componente
		$params =& JComponentHelper::getParams($option);
		$defaultCountry = $params->get('cfg_country_id');
		if ($published == 0 && $defaultCountry == $country_id) {
			$msg = JText::_('CP.COUNTRY_ERROR_UNPUBLISHING_DEFAULT_COUNTRY');
			$type = 'error';
		} else {
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
		}

		// Redireccionar
		$link = 'index.php?option=' . $option . '&view=country';
		if ($this->task == 'apply') {
			if ($country_id < 1) {
				$data =& $model->getRow();
				$country_id = $data->country_id;
			}
			$link .= '&task=edit&cid[]=' . $country_id;
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

		// se verifica que no se esté intentando borrar el país por defecto
		// Obtener preferencias del componente
		$params =& JComponentHelper::getParams($option);
		$defaultCountry = $params->get('cfg_country_id');
		if (in_array($defaultCountry, $cids)) {
			$msg = JText::_('CP.COUNTRY_ERROR_DELETING_DEFAULT_COUNTRY');
			$type = 'error';
		} else {
			// Se obtiene el modelo
			$model = $this->getModel('country');
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
		}

		$this->setRedirect('index.php?option=' . $option . '&view=country', $msg, $type);
	}


	/**
	 * Publica registro(s) y redirecciona a la lista
	 * @return void
	 */
	function publish() {
		$option = JRequest::getCmd('option');

		// Se obtiene el modelo
		$model = $this->getModel('country');
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
		$this->setRedirect('index.php?option=' . $option . '&view=country', $msg, $type);
	}


	/**
	 * Despublica registro(s) y redirecciona a la lista
	 * @return void
	 */
	function unpublish() {
		$option = JRequest::getCmd('option');

		// Se obtiene el modelo
		$model = $this->getModel('country');
		// Se obtienen los ids de los registros a publicar
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		JArrayHelper::toInteger($cids);

		// Lanzar error si no se ha seleccionado al menos un registro a despublicar
		if (count($cids) < 1) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		// se verifica que no se esté intentando despublicar el país por defecto
		// Obtener preferencias del componente
		$params =& JComponentHelper::getParams($option);
		$defaultCountry = $params->get('cfg_country_id');
		if (in_array($defaultCountry, $cids)) {
			$msg = JText::_('CP.COUNTRY_ERROR_UNPUBLISHING_DEFAULT_COUNTRY');
			$type = 'error';
		} else {
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
		}
		$this->setRedirect('index.php?option=' . $option . '&view=country', $msg, $type);
	}
}
