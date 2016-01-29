<?php
/**
 * Cp View for com_cp Component
 * 
 * @package    Cp
 * @subpackage com_cp
 * @license  GNU/GPL v2
 *
 *
 */

defined('_JEXEC') or die;

/**
 * HTML View class for the Cp Component
 *
 * @package	Joomla.Components
 * @subpackage	Cp
 */
class CPViewCPProducts extends JViewLegacy {

	function display($tpl = null) {
		$app = JFactory::getApplication();

		$data = $this->get('Item');
		if (empty($data->product_id)) {
			$app->redirect('index.php', JText::_('COM_CP_PRODUCT_NOT_FOUND'), 'error');
		}
		$pathway = $app->getPathway();
		$pathway->addItem($data->product_name, '');
		$this->assignRef('data', $data);

		$params = JComponentHelper::getParams('com_cp');
		$this->assignRef('params', $params);

		parent::display($tpl);
	}
}
