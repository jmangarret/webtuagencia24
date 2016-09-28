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

/**
 * Cp View
 *
 * @package    Joomla.Components
 * @subpackage 	Cp
 */
class CPViewCPProductsList extends JViewLegacy {
    protected $items;
    protected $pagination;
    protected $state;
	protected $posibleStates;

    /**
	 * Cpproductslist view display method
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

		// draw menu
		JToolBarHelper::title(JText::_('COM_CP_CP_MANAGER') . ' - ' . JText::_('COM_CP_MENUCPPRODUCTSLIST'), 'generic.png');

		if ($user->authorise('core.create', 'com_cp')) {
			JToolBarHelper::addNew('cpproducts.add');
		}
        if ($user->authorise('core.edit', 'com_cp') || $user->authorise('core.edit.own', 'com_cp')) {
            JToolBarHelper::editList('cpproducts.edit');
        }
		if ($user->authorise('core.edit.state', 'com_cp') && count($this->items)) {
			JToolBarHelper::divider();
            JToolBarHelper::publishList('cpproductslist.publish');
            JToolBarHelper::unpublishList('cpproductslist.unpublish');
            JToolBarHelper::custom('cpproductslist.featured', 'featured.png', 'featured_f2.png', 'JFEATURED', true);
		}
		if ($user->authorise('core.delete', 'com_cp')) {
			JToolBarHelper::divider();
			JToolBarHelper::deleteList('COM_CP_CONFIRM_DELETE_PRODUCT', 'cpproductslist.delete');
		}

		// configuration editor for config.xml
        if ($user->authorise('core.admin', 'com_cp')) {
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_cp');
        }

		// SORTING get the user state of order and direction
		$helper = new CPHelper();
		$lists = array();
		$lists['category_id'] = $helper->filterCategory($this->state->get('filter.category_id'));
		$lists['tourismtype_id'] = $helper->filterTourismType($this->state->get('filter.tourismtype_id'));
		$this->assignRef('helper', $helper);
		$this->assignRef('lists', $lists);

		parent::display($tpl);
	}
}