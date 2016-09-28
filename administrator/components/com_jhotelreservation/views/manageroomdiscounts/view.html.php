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

if (!checkUserAccess(JFactory::getUser()->id,"manage_room_discounts")){
	$msg = "You are not authorized to access this resource";
	$this->setRedirect( 'index.php?option='.getBookingExtName(), $msg );
}


JHTML::_('script', 						'administrator/components/'.getBookingExtName().'/assets/js/jquery.selectlist.js');
JHTML::_('script', 							'administrator/components/'.getBookingExtName().'/assets/js/manageDiscounts.js');

class JHotelReservationViewManageRoomDiscounts extends JViewLegacy
{
	function display($tpl = null)
	{
		if( 
			JRequest::getString( 'task') !='edit' 
			&& 
			JRequest::getString( 'task') !='add' 
		) 
		{
			JToolBarHelper::title(   'J-HotelReservation :'.JText::_('LNG_MANAGE_ROOM_DISCOUNTS',true), 'generic.png' );
			// JRequest::setVar( 'hidemainmenu', 1 );  
			
			JHotelReservationHelper::addSubmenu('roomdiscounts');
			
			$hotel_id =  $this->get('HotelId'); 
			
			if( $hotel_id > 0 )
			{
				JToolBarHelper::addNew('manageroomdiscounts.edit'); 
				JToolBarHelper::editList('manageroomdiscounts.edit');
				
				JToolBarHelper::deleteList( '', 'manageroomdiscounts.delete', JText::_('LNG_DELETE',true));
			}
			JToolBarHelper::custom( 'hotels.back', JHotelUtil::getDashBoardIcon(), 'home', JText::_('LNG_HOTEL_DASHBOARD',true),false, false );
				
			// dmp($hotel_id);
			
			$this->hotel_id =  $hotel_id; 
			
			$items		= $this->get('Datas'); 
			$this->items =  $items; 
			
			$hotels		= $this->get('Hotels'); 
			$hotels = checkHotels(JFactory::getUser()->id,$hotels);
			$this->hotels =  $hotels; 
			
		}
		else
		{
			$item				= $this->get('Data'); 
			$this->item =  $item; 
			
			
			$hotel_id =  $this->get('HotelId'); 
			$this->hotel_id =  $hotel_id; 
			
			$hotel		= $this->get('Hotel'); 
			$this->hotel =  $hotel; 
		
			JToolBarHelper::title(    'J-Hotel Reservation : '.( $item->discount_id > 0? JText::_('LNG_EDIT',true): JText::_('LNG_ADD_NEW',true) ).' '.JText::_('LNG_DISCOUNT',true), 'generic.png' );
			JRequest::setVar( 'hidemainmenu', 1 );  
			JToolBarHelper::cancel('manageroomdiscounts.cancel');
			JToolBarHelper::save('manageroomdiscounts.save'); 
			
			$tpl = "edit";
		}
		parent::display($tpl);
	}
}