<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: comments.php 2012-09-10 18:29:50 svn $
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

class CatalogoPlanesModelComments extends JModel {

  function __construct() {
    global $option;

    parent::__construct();
  }


  /**
   * Obtiene el registro
   * @return object
   */
  public function getRow($comment_id, $key) {
    $query = 'SELECT * FROM #__cp_pending_comments WHERE comment_id = ' . $comment_id . ' AND product_name = \'' . base64_decode($key) . '\'';

    $this->_db->setQuery($query);
    return $this->_db->loadObject();
  }


  /**
   * Guarda el registro
   * @return object
   */
  public function store($row) {
    $createdate =& JFactory::getDate();
    $obj = new stdClass();
    $obj->order_id = $row->order_id;
    $obj->product_id = $row->product_id;
    $obj->product_name = $row->product_name;
    $obj->comment_rate = $row->comment_rate;
    $obj->comment_text = $row->comment_text;
    $obj->created = $createdate->toFormat();
    $obj->created_by = $row->created_by;
    $obj->contact_name = $row->contact_name;
    $obj->contact_email = $row->contact_email;
    $obj->lang = $row->language;
    $obj->modified = $createdate->toFormat();
    $obj->modified_by = $row->modified_by;
    $obj->end_date = $row->end_date;
    $obj->published = 2;
    $result = $this->_db->insertObject('#__cp_' . $row->product_type_code . '_comments', $obj);
     
    if ($result) {

      $query = 'UPDATE #__cp_' . $row->product_type_code . '_info SET average_rating = (SELECT AVG(comment_rate) FROM #__cp_' . $row->product_type_code . '_comments WHERE product_id = ' . $row->product_id . ' AND published = 1) WHERE product_id = ' . $row->product_id;

      $this->_db->setQuery($query);
       
      return $this->_db->query();
    }
    return $result;
  }


  /**
   * Borra el registro
   * @return object
   */
  public function delete($id) {
    $query = 'DELETE FROM #__cp_pending_comments WHERE comment_id = ' . $id;

    $this->_db->setQuery($query);
    return $this->_db->query();
  }
}
