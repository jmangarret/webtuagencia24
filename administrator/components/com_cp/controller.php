<?php
/**
 * Cp Controller for Cp Component
 * 
 * @package    Cp
 * @subpackage com_cp
 *
 *
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Cp Model
 *
 * @package    Joomla.Components
 * @subpackage 	Cp
 */
class CPController extends JControllerLegacy {

	protected $default_view = 'cpproductslist';

	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	public function display($cachable = false, $urlparams = false) {
		if (stripos(JRequest::getCmd('view', 'cpproductslist'), 'list') !== false) {
			// Add submenu
			JSubMenuHelper::addEntry(JText::_('COM_CP_MENUCPPRODUCTSLIST'), 'index.php?option=com_cp&view=cpproductslist');
			JSubMenuHelper::addEntry(JText::_('COM_CP_MENUCPCATEGORYLIST'), 'index.php?option=com_cp&view=cpcategorylist');
			JSubMenuHelper::addEntry(JText::_('COM_CP_MENUCPPRODUCTTOURISMTYPELIST'), 'index.php?option=com_cp&view=cptourismtypelist');
		}

        parent::display($cachable, $urlparams);

        return $this;
	}
}