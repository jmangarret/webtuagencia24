<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: catalogo_planes.php 2012-09-10 18:29:50 svn $
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

class CatalogoPlanesModelPanel extends JModel {

	function __construct() {
		parent::__construct();
    }

	/**
	 * Devuelve listado de tipos de productos instalados en el componente.
	 */
	function getProductTypes() {
		$model =& JModel::getInstance('producttype', 'CatalogoPlanesModel');
		return $model->getActiveProductTypesInfo();
	}
}
