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
 * Cp view
 *
 * @package    Joomla.Components
 * @subpackage 	Cp
 */
class CPViewCPProducts extends JViewLegacy {
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

		$isNew = (empty($this->item->product_id));
		$document = JFactory::getDocument();

		if (JRequest::getVar('tmpl', '') != 'component') {
			$document->addScript(JURI::base(true) . '/components/com_cp/assets/js/jquery-1.10.1.min.js');
			$document->addScriptDeclaration('jQuery.noConflict();');
			$document->addScript(JURI::base(true) . '/components/com_cp/assets/js/jquery-ui-1.10.3.custom.min.js');
			$document->addScript(JURI::base(true) . '/components/com_cp/assets/js/scripts.js');
			$document->addStyleSheet(JURI::base(true) . '/components/com_cp/assets/css/style.css');
			$document->addStyleSheet(JURI::base(true) . '/components/com_cp/assets/css/smoothness/jquery-ui-1.8.21.custom.css');
			$document->addStyleDeclaration('body { min-width: 1170px; }');
		}

        // Create the form
        JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		$text = $isNew ? JText::_('COM_CP_NEW') : JText::_('COM_CP_EDIT');
		JToolBarHelper::title($text . ' ' . JText::_('COM_CP_PRODUCT_PAGE_TITLE'));
		if ($isNew && $user->authorise('core.create', 'com_cp')) {
			JToolBarHelper::apply('cpproducts.apply');
			JToolBarHelper::save('cpproducts.save');
			JToolBarHelper::save2new('cpproducts.save2new');
			JToolBarHelper::cancel('cpproducts.cancel');
		} else {
			if ($user->authorise('core.edit', 'com_cp')) {
				JToolBarHelper::apply('cpproducts.apply');
				JToolBarHelper::save('cpproducts.save');
				if ($user->authorise('core.edit', 'com_cp')) {
					JToolBarHelper::save2new('cpproducts.save2new');
				}
				JToolBarHelper::cancel('cpproducts.cancel', 'COM_CP_CLOSE');
			}
		}

		$helper = new CPHelper();
		if ($isNew) {
			$countFiles = 0;
		} else {
			$countFiles = count($this->item->media);
		}

		// Initialize media files script
		$document->addScriptDeclaration('var mediaCount = ' . $countFiles . '; var delText = "' . JText::_('COM_CP_DELETE') . '"; var siteURL = "' . JURI::root() . '";');

		$params = JComponentHelper::getParams('com_cp');
		$this->assignRef('params', $params);

   		$this->item->cities = $helper->listCities($this->item->country_code, $this->item->city, 'jform[city]', 'jform_city');

		parent::display($tpl);
	}
}