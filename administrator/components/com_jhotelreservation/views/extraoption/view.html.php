<?php
/**
 * @package    JHotelReservation
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * The HTML  View.
 */
if (!checkUserAccess(JFactory::getUser()->id,"manage_extra_options")){
	$msg = "You are not authorized to access this resource";
	$this->setRedirect( 'index.php?option='.getBookingExtName(), $msg );
}

JHTML::_('script','administrator/components/'.getBookingExtName().'/assets/js/jquery.selectlist.js');
JHTML::_('script','administrator/components/'.getBookingExtName().'/assets/js/manageextraoptions.js');
JHTML::_('script','administrator/components/'.getBookingExtName().'/assets/js/jquery.upload.js');

class JHotelReservationViewExtraOption extends JViewLegacy
{
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null){
	
		$this->item	 = $this->get('Item');
		$this->state = $this->get('State');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$hoteltranslationsModel = new JHotelReservationModelhoteltranslations();
		$this->translations = $hoteltranslationsModel->getAllTranslations(EXTRA_OPTIONS_TRANSLATION, $this->item->id);
		
		parent::display($tpl);
		$this->addToolbar();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		JToolbarHelper::title(JText::_($isNew ? 'LNG_NEW_EXTRA_OPTION' : 'LNG_EDIT_EXTRA_OPTION',true), 'menu.png');
		
		JToolbarHelper::apply('extraoption.apply');
			
		JToolbarHelper::save('extraoption.save');
		
		JToolbarHelper::cancel('extraoption.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_JHotelReservation_COMPANY_TYPE_EDIT');
	}
}
