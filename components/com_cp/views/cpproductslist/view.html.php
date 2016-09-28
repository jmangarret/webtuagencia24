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
 * @package		Cp
 * @subpackage	Components
 */
class CPViewCPProductsList extends JViewLegacy {

	function display($tpl = null) {
		$app = JFactory::getApplication();

		$data = $this->get('Items');
		if (empty($data)) {
			$app->redirect('index.php', JText::_('COM_CP_NO_RESULTS'), 'error');
		}
		$this->assignRef('data', $data);

		$isnewsearch = JRequest::getInt('isnewsearch', 0);
		$this->assignRef('isnewsearch', $isnewsearch);
		$pathway   = $app->getPathway();
		if ($isnewsearch) {
			$pathway->addItem(JText::_('COM_CP_SEARCH_TITLE'), '');
		}

		$params = JComponentHelper::getParams('com_cp');
		$this->assignRef('params', $params);

		$order_field = JRequest::getVar('order_field', 'ordering');
		$this->assignRef('order_field', $order_field);

		$pagination = $this->get('Pagination');
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}
}