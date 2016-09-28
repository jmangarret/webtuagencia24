<?php
/**
 * Cp View for Cp Component
 * 
 * @package    Cp
 * @subpackage com_cp
 * @license  GNU/GPL v2
 *
 *
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Cp View
 *
 * @package    Joomla.Components
 * @subpackage 	Cp
 */
class CPViewCPCategoryList extends JViewLegacy {
	protected $items;
	protected $pagination;
	protected $state;
	protected $posibleStates;

	/**
	 * Cpcategorylist view display method
	 * @return void
	 **/
	function display($tpl = null) {
		// Get data from the model
		$this->items = $this->get('Items');
		$this->state = $this->get('State');
		$this->pagination = $this->get('Pagination');

		$posibleStates = array();
		$posibleStates[] = JHtml::_('select.option', '1', 'COM_CP_ACTIVE');
		$posibleStates[] = JHtml::_('select.option', '0', 'COM_CP_INACTIVE');
		$this->posibleStates = $posibleStates;

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$app = JFactory::getApplication();
		$user = JFactory::getUser();

		// draw menu checking user permissions
		JToolBarHelper::title(JText::_('COM_CP_CP_MANAGER') . ' - ' . JText::_('COM_CP_MENUCPCATEGORYLIST'), 'generic.png');
		if ($user->authorise('core.create', 'com_cp')) {
			JToolBarHelper::addNewX('cpcategory.add');
		}
		if ($user->authorise('core.edit', 'com_cp') || $user->authorise('core.edit.own', 'com_cp')) {
			JToolBarHelper::editListX('cpcategory.edit');
		}
		if (count($this->items) && $user->authorise('core.edit.state', 'com_cp')) {
			JToolBarHelper::divider();
			JToolBarHelper::publishList('cpcategorylist.publish');
			JToolBarHelper::unpublishList('cpcategorylist.unpublish');
		}
		if ($user->authorise('core.delete', 'com_cp')) {
			JToolBarHelper::divider();
			JToolBarHelper::deleteList('COM_CP_CONFIRM_DELETE_CATEGORY', 'cpcategorylist.delete');
		}

		// configuration editor for config.xml
        if ($user->authorise('core.admin', 'com_cp')) {
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_cp');
        }

		parent::display($tpl);
	}
}