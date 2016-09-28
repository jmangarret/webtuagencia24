<?php
/**
 * @package    JHotelReservation
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
if (!checkUserAccess(JFactory::getUser()->id,"manage_extra_options")){
	$msg = "You are not authorized to access this resource";
	$this->setRedirect( 'index.php?option='.getBookingExtName(), $msg );
}
/**
 * The HTML Menus Menu Menus View.
 *
 * @package    JHotelReservation
 * @subpackage  com_jbusinessdirectory

 */

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JHotelReservationViewExtraOptions extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$layout = JRequest::getVar("layout", null);
		if(isset($layout)){
			$tpl = $layout;
		}
		
		JHotelReservationHelper::addSubmenu('extraoptions');

		$hotels		= $this->get('Hotels');
		$this->hotels = checkHotels(JFactory::getUser()->id,$hotels);
		
		$this->statuses = JHotelReservationHelper::getStatuses();
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->hoteltranslationsModel = new JHotelReservationModelhoteltranslations();

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JToolBarHelper::title('J-HotelReservation : '.JText::_('LNG_EXTRA_OPTION',true), 'generic.png' );
		
		JToolbarHelper::addNew('extraoption.add');
		JToolBarHelper::custom( 'extraoptions.addDefault', 'new.png', 'new.png', JText::_('LNG_ADD_FROM_DEFAULT',true), false, false );
		JToolbarHelper::editList('extraoption.edit');
		
		JToolbarHelper::divider();
		JToolbarHelper::deleteList('','extraoptions.delete');
				
		JToolbarHelper::divider();
		JToolBarHelper::custom( 'extraoptions.back', JHotelUtil::getDashBoardIcon(), 'preview.png', JText::_('LNG_HOTEL_DASHBOARD',true), false, false );
		JToolbarHelper::help('JHELP_COMPANIES');
	}
}
