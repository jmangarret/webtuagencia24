<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: comments.php 2012-09-11 8:29:50 svn $
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
 * comments Controller
 *
 * @package Joomla
 * @subpackage catalogo_planes
 */
class CatalogoPlanesControllerComments extends JController {

	/**
	 * Constructor
	 * @access private
	 * @subpackage catalogo_planes
	 */
	function __construct($config = array()) {
		parent::__construct();
	}


	/**
	 * Despliega la interfaz con la lista de registros
	 */
	function display() {
		$mainframe =& JFactory::getApplication(); 

		// obtiene el modelo
		$model =& $this->getModel('comments');
		$view = & $this->getView('comments', 'html');
		$view->setModel($model, true);
                $modelProducts =& $this->getModel('producttype');
                $productTypes = $modelProducts->getActiveProductTypesInfo();

                // Recupera los valores de los filtros
                $filter_state = $mainframe->getUserStateFromRequest($filter_prefix . 'filter_state', 'filter_state', 'A', 'word');
                $product_type_code = $mainframe->getUserStateFromRequest($filter_prefix . 'product_type_code', 'product_type_code', $productTypes[0]->product_type_code, 'word');

                // Recupera la información
		$lists = array();
		$data = $model->getList();
		$pagination = & $model->getPagination();
		$filter_prefix = $model->getFilterPrefix();
		$filter_order = $model->getOrderByField();
		$filter_order_Dir = $model->getOrderByDirection();

                // Generar lista de tipos producto
                $lists['productTypes'] = JHTML::_('select.genericlist', $productTypes, 'product_type_code', 'class="inputbox" onchange="submitform();"', 'product_type_code', 'product_type_name', $product_type_code);

		// filtro de estado
		$lists['state']	= JHTML::_('grid.state', $filter_state, 'PUBLISHED', 'UNPUBLISHED', 'CP.COMMENTS_NEW_STATE');

		// ordenamiento de la tabla
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// filtro de búsqueda
		$lists['product_type_code'] = $product_type_code;

		// Asignar la información a la vista
		$view->assignRef('data', $data);
		$view->assignRef('lists', $lists);
		$view->assignRef('items', $items);
		$view->assignRef('pagination', $pagination);

		$view->display();
	}


	/**
	 * Borra registro(s) y redirecciona a la lista
	 * @return void
	 */
	function remove() {
		$option = JRequest::getCmd('option');
		// Se obtienen los ids de los registros a borrar
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
                $product_type_code = JRequest::getVar('product_type_code');

		// Lanzar error si no se ha seleccionado al menos un registro a borrar
                if (count($cids) < 1 || !$product_type_code) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_DELETE'));
		}

		// Se obtiene el modelo
		$model = $this->getModel('comments');
		// Se intenta el borrado
		if ($model->delete($cids, $product_type_code)) {
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

		$this->setRedirect('index.php?option=' . $option . '&view=comments', $msg, $type);
	}


	/**
	 * Publica registro(s) y redirecciona a la lista
	 * @return void
	 */
	function publish() {
		$option = JRequest::getCmd('option');

		// Se obtiene el modelo
		$model = $this->getModel('comments');
		// Se obtienen los ids de los registros a publicar
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		JArrayHelper::toInteger($cids);
                $product_type_code = JRequest::getVar('product_type_code');

		// Lanzar error si no se ha seleccionado al menos un registro a publicar
                if (count($cids) < 1 || !$product_type_code) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_PUBLISH'));
		}

		if ($model->publish($cids, true, $product_type_code)) {
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
		$this->setRedirect('index.php?option=' . $option . '&view=comments', $msg, $type);
	}


	/**
	 * Despublica registro(s) y redirecciona a la lista
	 * @return void
	 */
	function unpublish() {
		$option = JRequest::getCmd('option');

		// Se obtiene el modelo
		$model = $this->getModel('comments');
		// Se obtienen los ids de los registros a publicar
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		JArrayHelper::toInteger($cids);
                $product_type_code = JRequest::getVar('product_type_code');

		// Lanzar error si no se ha seleccionado al menos un registro a despublicar
		if (count($cids) < 1 || !$product_type_code) {
			JError::raiseError(500, JText::_('CP.SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		if ($model->publish($cids, false, $product_type_code)) {
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
		$this->setRedirect('index.php?option=' . $option . '&view=comments', $msg, $type);
	}


	/**
	 * Envía la invitación a calificar a los clientes
	 * @return void
	 */
    function sendInvitations () {
    	$option = JRequest::getCmd('option');

    	$params =& JComponentHelper::getParams('com_catalogo_planes');
        $max_attempts = ((int) $params->get('cfg_comments_invitation'));
        $db =& JFactory::getDBO();
        $query = 'SELECT * FROM #__cp_pending_comments WHERE end_date < CURDATE()';
        // Si se ha definido un máximo de envío de correos, agregar a consulta.
        if ($max_attempts) {
        	$max_attempts++;
        	$query .= ' AND total_attempts < ' . $max_attempts;
        }
        $db->setQuery($query);
        $result = $db->loadObjectList();

        if (count($result)) {
        	$msg = JText::_('CP.COMMENTS_EMAIL_SENT');
        	$type = 'message';
        	$link = JURI::root() . 'index.php?option=' . $option . '&view=comments&layout=display&id=';
        	$lang =& JFactory::getLanguage();
        	$languages = array();
        	$langparams = JComponentHelper::getParams('com_languages');
        	$langTag = $defaultLang = $langparams->get("site");

        	// Si el idioma actual no es el idioma por defecto, cargarlo
        	if ($langTag != $lang->getTag()) {
        		$lang =& JLanguage::getInstance($langTag);
        		$lang->load($option);
        	}
        	$languages[$langTag] = $lang;

        	// Preparar envío de correo
        	$mailer =& JFactory::getMailer();
        	$config =& JFactory::getConfig();
        	$sender = array($config->getValue('config.mailfrom'), $config->getValue('config.fromname'));
        	$mailer->setSender($sender);
        	$mailer->isHTML(true);

        	// Enviar correo por cada reserva pendiente
        	foreach ($result as $row) {
        		$langTag = $row->language;
        		// Carga el idioma de la reserva para enviar el correo
        		if (isset($languages[$langTag])) {
        			$lang =& $languages[$langTag];
        		} else {
                        if (JLanguage::exists($langTag)) {
                            $lang =& JLanguage::getInstance($langTag);
                            $lang->load($option);
                        } else {
                            $lang =& $languages[$defaultLang];
                        }
                        $languages[$langTag] =& $lang;
        		}
                        //print_r($languages[$langTag]->getTag() . ' ' . $languages[$langTag]->_strings['CP.CLONE']);
                        //echo '<br />';
                        //print_r(nl2br(sprintf($languages[$langTag]->_strings['CP.COMMENTS_EMAIL_BODY'], $row->contact_name, $row->product_name)));
                        $email_link = $link . $row->comment_id . '&lang=' . substr($langTag, 0, 2) . '&key=' . base64_encode($row->product_name);
        		$body = nl2br(sprintf($lang->_('CP.COMMENTS_EMAIL_BODY'), $row->contact_name, $row->product_name, JRoute::_($email_link)));
        		$subject = sprintf($lang->_('CP.COMMENTS_EMAIL_SUBJECT'), $row->product_name);
        		$mailer->setSubject($subject);
        		$mailer->ClearAllRecipients();
        		$mailer->addRecipient($row->contact_email);
        		$mailer->setBody($body);
        		$send =& $mailer->Send();
                        //echo 'aqui='.$send;die();
        		if ($send !== true) {
        			$type = 'error';
        			$msg = JText::_('CP.COMMENTS_EMAIL_ERROR') . '<div class="hiddenerrormessage">' . $send . '</div>';
        			break;
        		} else {
        			$query = 'UPDATE #__cp_pending_comments SET total_attempts = total_attempts + 1 WHERE comment_id = ' . $row->comment_id;
        			$db->setQuery($query);
        			$db->query();
        		}
        	}
        } else {
        	$type = 'notice';
        	$msg = JText::_('CP.COMMENTS_NO_PENDING_INVITATIONS');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=comments', $msg, $type);
    }
}
