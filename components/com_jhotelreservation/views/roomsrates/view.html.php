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

jimport( 'joomla.application.component.view');

class JHotelReservationViewRoomsRates extends JViewLegacy
{
	function display($tpl = null)
	{
		
		// $itemCurrency		=& $this->get('Currency'); 
		// $this->itemCurrency =  $itemCurrency;
		
		// $itemRooms		=& $this->get('Rooms'); 
		// $this->itemRooms =  $itemRooms;
		$appSettings = JUtil::getApplicationSettings();
		parent::display($tpl);
	}
}
?>
