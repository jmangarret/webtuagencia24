<?php
/**
 * Cp Controller for Cp Component
 * 
 * @package    Cp
 * @subpackage com_cp
 * @license  GNU/GPL v2
 *
 *
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controllerform');

/**
 * Cp Model
 *
 * @package    Joomla.Components
 * @subpackage 	Cp
 */
class CPControllerCPProducts extends JControllerForm {
	protected $view_item = 'cpproducts';
	protected $view_list = 'cpproductslist';
}
