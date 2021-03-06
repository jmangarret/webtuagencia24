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
 * Cp view
 *
 * @package    Joomla.Components
 * @subpackage 	Cp
 */
class CPViewCPCategory extends JViewLegacy {
	protected $form;
	protected $item;
	protected $state;

	/**
	 * display method of Cp view
	 * @return void
	 **/
	function display($tpl = null) {
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		JRequest::setVar('hidemainmenu', true);
		$user = JFactory::getUser();

		$isNew = ($this->item->category_id < 1);

		// add toolbar
		$text = $isNew ? JText::_('COM_CP_NEW') : JText::_('COM_CP_EDIT');
		JToolBarHelper::title($text . ' ' . JText::_('COM_CP_CATEGORY_PAGE_TITLE'));
		if ($isNew && $user->authorise('core.create', 'com_cp')) {
			JToolBarHelper::apply('cpcategory.apply');
			JToolBarHelper::save('cpcategory.save');
			JToolBarHelper::save2new('cpcategory.save2new');
			JToolBarHelper::cancel('cpcategory.cancel');
		} else {
			if ($user->authorise('core.edit', 'com_cp')) {
				JToolBarHelper::apply('cpcategory.apply');
				JToolBarHelper::save('cpcategory.save');
				if ($user->authorise('core.edit', 'com_cp')) {
					JToolBarHelper::save2new('cpcategory.save2new');
				}
				JToolBarHelper::cancel('cpcategory.cancel', 'COM_CP_CLOSE');
			}
		}

		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::base(true) . '/components/com_cp/assets/css/style.css');

		parent::display($tpl);
	}
}