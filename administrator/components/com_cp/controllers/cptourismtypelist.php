<?php
/**
 * Cp Model for Cp Component
 * 
 * @package    Cp
 * @subpackage com_cp
 * @license  GNU/GPL v2
 *
 *
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

/**
 * Cp Model
 *
 * @package    Joomla.Components
 * @subpackage 	Cp
 */
class CPControllerCPTourismTypeList extends JControllerAdmin {

	public function getModel($name = 'CPTourismType', $prefix = 'CPModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
}
