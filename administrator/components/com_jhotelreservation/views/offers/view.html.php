<?php
/**
 * @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
if (!checkUserAccess(JFactory::getUser()->id,"manage_offers")){
	$msg = "You are not authorized to access this resource";
	$this->setRedirect( 'index.php?option='.getBookingExtName(), $msg );
}

class JHotelReservationViewOffers extends JViewLegacy
{
	function display($tpl = null){

		$lang 		= JFactory::getLanguage();
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		$this->appSettings = JHotelUtil::getInstance()->getApplicationSettings();
		
		$hotel_id =  $this->get('HotelId');
		$this->hotel_id =  $hotel_id;
		
		$hotels		= $this->get('Hotels');
		$hotels = checkHotels(JFactory::getUser()->id,$hotels);
		$this->hotels =  $hotels;
		
		parent::display($tpl);
		$this->addToolbar();
		
	}
	
	protected function addToolbar()
	{
		$canDo = JHotelReservationHelper::getActions();
		JToolBarHelper::title(JText::_( 'LNG_MANAGE_OFFERS' ,true), 'generic.png' );
			
		if ($canDo->get('core.create')){
			JToolBarHelper::addNew('offer.edit');
			JToolBarHelper::editList('offer.edit');
		}
		JToolBarHelper::divider();
		JToolBarHelper::publish('offers.state', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('offers.state', 'JTOOLBAR_UNPUBLISH', true);
	
		if ($canDo->get('core.delete')){
			JToolBarHelper::deleteList( '', 'offers.delete', JText::_('LNG_DELETE',true));
		}
		JToolBarHelper::custom( 'hotels.back', JHotelUtil::getDashBoardIcon(), 'home', JText::_('LNG_HOTEL_DASHBOARD',true), false, false );
		JToolBarHelper::divider();
		JHotelReservationHelper::addSubmenu('offers');
			
	}
	
}