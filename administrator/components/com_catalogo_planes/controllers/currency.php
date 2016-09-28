<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: currency.php 2012-09-11 8:29:50 svn $
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
 * currency Controller
 *
 * @package Joomla
 * @subpackage catalogo_planes
 */
class CatalogoPlanesControllerCurrency extends JController {

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
		$model =& $this->getModel('currency');
		$view = & $this->getView('currency', 'html');
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
		$model =& $this->getModel('currency');
		$view = & $this->getView('currency', 'html');
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
		if ($cid[0] && empty($data->currency_name)) {
			$this->setRedirect('index.php?option=' . $option . '&view=currency', JText::_('CP.ROW_UNKNOWN'), 'error');
		}

		// Asignar la información a la vista
		$view->assignRef('data', $data);
		$view->assignRef('approxOptions', $model->getApproxCriteriaOptions());

		$view->display();
	}


	/**
	 * Guardar el registro
	 * @return void
	 */
	function save() {
		$option = JRequest::getCmd('option');

		$published = JRequest::getInt('published');
		$currency_id = JRequest::getInt('currency_id');
		$default_currency = JRequest::getInt('default_currency', null);

		// se verifica que no se esté intentando despublicar la moneda por defecto
		if ($published == 0 && $default_currency == 1) {
			$msg = JText::_('CP.CURRENCY_ERROR_UNPUBLISHING_DEFAULT_CURRENCY');
			$type = 'error';
		} else {
			// Se obtiene el modelo
			$model =& $this->getModel('currency');

			// se verifica que no se esté intentando dejar el componente sin moneda por defecto
			$approved = true;
			$data = $model->getList(true, array('default_currency' => 1));
			if (is_array($data)) {
				if ($currency_id == $data[0]->currency_id && $default_currency === 0) {
					$msg = JText::_('CP.CURRENCY_ERROR_CHANGING_DEFAULT_CURRENCY');
					$type = 'error';
					$approved = false;
				}
				// Si no se pudo borrar, guarde el error
				if ($currency_id == $data[0]->currency_id && $published == 0) {
					$msg = JText::_('CP.CURRENCY_ERROR_UNPUBLISHING_DEFAULT_CURRENCY');
					$type = 'error';
					$approved = false;
				}
			}

			if ($approved) {
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
		}

		// Redireccionar
		$link = 'index.php?option=' . $option . '&view=currency';
		if ($this->task == 'apply') {
			$currency_id = JRequest::getInt('currency_id');
			if ($currency_id < 1) {
				$data =& $model->getRow();
				$currency_id = $data->currency_id;
			}
			$link .= '&task=edit&cid[]=' . $currency_id;
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
		$approved = true;

		// Se obtiene el modelo
		$model = $this->getModel('currency');

		// se verifica que no se esté intentando borrar la moneda por defecto
		$data = $model->getList(true, array('default_currency' => 1));
		if (is_array($data)) {
			foreach($cids as $cid) {
				if ($cid == $data[0]->currency_id) {
					$msg = JText::_('CP.CURRENCY_ERROR_DELETING_DEFAULT_CURRENCY');
					$type = 'error';
					$approved = false;
					break;
				}
			}
		}

		// se verifica que no se esté intentando borrar la moneda por defecto
		if ($approved) {
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

		$this->setRedirect('index.php?option=' . $option . '&view=currency', $msg, $type);
	}


	/**
	 * Publica registro(s) y redirecciona a la lista
	 * @return void
	 */
	function publish() {
		$option = JRequest::getCmd('option');

		// Se obtienen los ids de los registros a publicar
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		JArrayHelper::toInteger($cids);

		// Lanzar error si no se ha seleccionado al menos un registro a publicar
		if (count($cids) < 1) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_PUBLISH'));
		}

		// Se obtiene el modelo
		$model = $this->getModel('currency');
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
		$this->setRedirect('index.php?option=' . $option . '&view=currency', $msg, $type);
	}


	/**
	 * Despublica registro(s) y redirecciona a la lista
	 * @return void
	 */
	function unpublish() {
		$option = JRequest::getCmd('option');

		// Se obtienen los ids de los registros a publicar
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		JArrayHelper::toInteger($cids);

		// Lanzar error si no se ha seleccionado al menos un registro a despublicar
		if (count($cids) < 1) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_UNPUBLISH'));
		}
		$approved = true;

		// Se obtiene el modelo
		$model = $this->getModel('currency');

		// se verifica que no se esté intentando despublicar la moneda por defecto
		$data = $model->getList(true, array('default_currency' => 1));
		if (is_array($data)) {
			foreach($cids as $cid) {
				// Si no se pudo borrar, guarde el error
				if ($cid == $data[0]->currency_id) {
					$msg = JText::_('CP.CURRENCY_ERROR_UNPUBLISHING_DEFAULT_CURRENCY');
					$type = 'error';
					$approved = false;
					break;
				}
			}
		}

		// se verifica que no se esté intentando despublicar la moneda por defecto
		if ($approved) {
			// Se obtiene el modelo
			$model = $this->getModel('currency');
			if ($model->publish($cids, false)) {
				$msg = JText::_('CP.ROWS_UNPUBLISHED');
			} else {
				// Si hay algún error se ajusta el mensaje
				$msg = $model->getError();
				if (!$msg) {
					$msg = JText::_('CP.ERROR_ONE_OR_MORE_DATA_COULD_NOT_BE_UNPUBLISHED');
				}
				$type = 'error';
			}
		}
		$this->setRedirect('index.php?option=' . $option . '&view=currency', $msg, $type);
	}


	/**
	 * sets the default currency
	 * @return void
	 */
	public function setDefault() {
		$option = JRequest::getCmd('option');

		$cids = JRequest::getVar('cid', array(0), 'post', 'array');

		// Lanzar error si no se ha seleccionado al menos un registro a borrar
		if (count($cids) < 1) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_EDIT'));
		}

		$model =& $this->getModel('currency');
		$data =& $model->getRow($cids[0]);

		// se verifica que no se esté intentando despublicar la moneda por defecto
		if ((int) $data->published == 0) {
			$msg = JText::_('CP.CURRENCY_ERROR_UNPUBLISHING_DEFAULT_CURRENCY');
			$type = 'error';
		} else {
			if ($model->setDefault($cids[0])) {
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

		$link = 'index.php?option=' . $option . '&view=currency';
		$this->setRedirect($link, $msg, $type);
	}
}
