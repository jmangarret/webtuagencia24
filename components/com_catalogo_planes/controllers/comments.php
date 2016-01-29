<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: comments.php 2013-01-31 8:29:50 svn $
 * @author Yusely Palacios
 * @package Joomla
 * @subpackage catalogo_planes
 * @license Copyright (c) 2013 Amadeus - All Rights Reserved
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
$option = JRequest::getCmd('option');
require_once 'administrator/components/' . $option . '/models/producttype.php';

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
    $comment_id = JRequest::getInt('id');
    $key = JRequest::getVar('key');
    if (empty($comment_id) || empty($key)) {
      $mainframe->redirect('index.php', JText::_('CP.COMMENTS.RECORD.NOT.FOUND'), 'error');
    }

    $data = $model->getRow($comment_id, $key);
    if (empty($data)) {
      $mainframe->redirect('index.php', JText::_('CP.COMMENTS.RECORD.NOT.FOUND'), 'error');
    }

    $view = & $this->getView('comments', 'html');
    $view->setModel($model, true);

    // Asignar la información a la vista
    $view->assignRef('data', $data);
    $view->assignRef('key', $key);

    $view->display();
  }


  /**
   * Guardar el registro
   * @return void
   */
  function save() {
    $mainframe =& JFactory::getApplication();

    $model =& $this->getModel('comments');
    $comment_id = JRequest::getInt('id');
    $key = JRequest::getVar('key');
    if (empty($comment_id) || empty($key)) {
      $this->setRedirect('index.php', JText::_('CP.COMMENTS.RECORD.NOT.FOUND'), 'error');
    }

    $row = $model->getRow($comment_id, $key);
    if (empty($row)) {
      $this->setRedirect('index.php', JText::_('CP.COMMENTS.RECORD.NOT.FOUND'), 'error');
    }

    $data = JRequest::get('post');
    $modelProducts =& $this->getModel('producttype');
    $productType = $modelProducts->getProductTypeById($row->product_type_id);

    $row->product_type_code = $productType->product_type_code;
    $row->comment_text = $data['comment_text'];
    $row->comment_rate = $data['comment_rate'];

    if ($model->store($row)) {
      $model->delete($comment_id);
      $msg = JText::_('CP.COMMENTS.THANK.YOU');
      $type = 'message';
    } else {
      $msg = JText::_('CP.COMMENTS.ERROR.SAVING');
      $type = 'error';
    }

    // Redireccionar
    $mainframe->redirect('index.php', $msg, $type);
  }
}
