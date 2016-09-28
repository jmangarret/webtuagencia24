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

JHTML::_('stylesheet', 'administrator/components/'.getBookingExtName().'/assets/tabs.css');
JHTML::_('stylesheet', 'components/'.getBookingExtName().'/assets/css/hotel_gallery.css');
JHTML::_('script', 'components/'.getBookingExtName().'/assets/js/jquery.opacityrollover.js');
JHTML::_('script', 'components/'.getBookingExtName().'/assets/js/readmore.js');
JHTML::_('script', 'components/'.getBookingExtName().'/assets/js/jquery.galleriffic.js');
JHTML::_('script', 'components/com_jhotelreservation/assets/js/search.js');

class JHotelReservationViewExcursionsListing extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->appSettings = JHotelUtil::getInstance()->getApplicationSettings();
		$this->userData =  $_SESSION['userData'];
		//dmp($this->userData);
		$this->searchFilter = $this->get('SearchFilter');
		
		if(!$this->appSettings->enable_excursions){
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_("LNG_EXCURSIONS_COURSES_DISABLED"), 'warning');
		}
		
		// get the menu parameters for use
		$type= JRequest::getVar("excursion_type");

		if(method_exists($this,$type))
			$tpl = $this->$type();
		$this->setLayout('default');
		parent::display($tpl);
	}
		
	function excursions()
	{
		$this->excursions= $this->get("AllExcursions");
		
		//pagination
		$pagination =$this->get('Pagination');
		$this->assignRef('pagination', $pagination);
		$tpl = 'excursions';
		return $tpl;

	}
	
	function courses()
	{
		$this->excursions= $this->get("AllCourses");
	
		//pagination
		$pagination =$this->get('Pagination');
		$this->assignRef('pagination', $pagination);
		$tpl = 'courses';
		return $tpl;
	
	}
	
}
?>
