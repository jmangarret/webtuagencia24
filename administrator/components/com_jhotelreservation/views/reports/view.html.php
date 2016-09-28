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

if (!checkUserAccess(JFactory::getUser()->id,"reservations_reports")){
	$msg = "You are not authorized to access this resource";
	$this->setRedirect( 'index.php?option='.getBookingExtName(), $msg );
}

class JHotelReservationViewReports extends JViewLegacy
{
	
	protected $rooms;
	protected $state;
	protected $hotels;
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$lang 		= JFactory::getLanguage();
		$this->rooms		= $this->get('Items');
		$this->state		= $this->get('State');
		$this->roomTypes 	= $this->get('RoomTypesOptions');
		//set hotels
		$hotels				= $this->get('Hotels');
		$this->hotels = checkHotels(JFactory::getUser()->id,$hotels);
		
		JHotelReservationHelper::addSubmenu('reports');

		if(PROFESSIONAL_VERSION==1)
			$this->types=array("simple"=>"Simple Report", "advanced"=>"Advanced Report");
		else 
			$this->types=array("simple"=>"Simple Report");
		
		if (checkUserAccess(JFactory::getUser()->id,"special_offers_report") && PROFESSIONAL_VERSION==1){
			$this->types["offers"] = "Offers Report";
		}
		
		switch($this->state->get('filter.type')){
			case "simple":
				$this->availabilityReport= $this->get('AvailabilityReport');
				break;
			case "advanced":
				$this->availabilityReport= $this->get('DetailedAvailabilityReport');
				break;
			case "offers":
				$this->offerReport= $this->get('OfferReport');
				break;
			default:
				$this->availabilityReport= $this->get('AvailabilityReport');
				break;
		}
	
		
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
	
		parent::display($tpl);
		$this->addToolbar();
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('LNG_REPORTS',true), 'menumgr.png');
		JToolBarHelper::custom( 'reports.back', 'home', 'home', JText::_('LNG_HOTEL_DASHBOARD',true), false, false );
		JToolBarHelper::custom( 'reservationsreports.back', 'home', 'home', JText::_('LNG_RESERVATIONS_DASHBOARD',true), false, false );
		//JToolBarHelper::help('JHELP_REPORTS');
	}
	
	function displayTableRow($room,$row){
		//dmp("R: ". $row);
		$startDate = $this->state->get('filter.start_date');
		$endDate = $this->state->get('filter.end_date');
		
		echo "<td class='td_color_fundal_1'></td>";
		for( $d = strtotime($startDate);$d <= strtotime($endDate); ){
			$dayString = date('d-m-Y',$d);

			$class="";
			if( $d==strtotime(date('d-m-Y').' -1 day') ){
				$class	= 'td_color_fundal_crt_day_1';
			}
			else{
				$class	= 'td_color_fundal_1';
			}

			$class2="";
			if( $d==strtotime(date('d-m-Y')) ){
				$class2	= 'td_color_fundal_crt_day_2';
			}
			else{
				$class2	= 'td_color_fundal_2';
			}

			$showBooking = false;
			if(isset($this->availabilityReport[$room->room_id])){
					
				foreach($this->availabilityReport[$room->room_id][$row] as $booking){
					if(strtotime($booking->start_date)<strtotime($startDate)){
						$booking->start_date=$startDate;
					}
					if(strtotime($booking->start_date) == $d){
						$stayPeriod = JHotelUtil::getNumberOfDays($booking->start_date, $booking->end_date);
						$colSpan = $stayPeriod*2;

						echo "<td class='td_color_fundal_1' colspan='$colSpan'>";
						echo "	<div class='reseravation_box reserved_details_".strtolower(str_replace(' ', '_', $booking->status_reservation_name ))."'>".
								"<a class='client' href='#dialog_".$booking->confirmation_id."' name='modal'>".$booking->last_name.' '.$booking->first_name."</a>".
								"</div>".
								"<div id='dialog_".$booking->confirmation_id."' class='window'>".
								"<div class='info'>".
								"<SPAN class='title_ID'>".JText::_('LNG_RESERVATION',true).' : '.JHotelUtil::getStringIDConfirmation($booking->confirmation_id)."</SPAN>".
								"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='Close it' class='close'/>".
								$booking->confirmation_details.
								"</div>".
								"</div>";
						echo "</td>";
							
						$showBooking = true;
						$d = strtotime( date('d-m-Y', $d).' + '.$stayPeriod.' day ');
						break;
					}
				}
				//echo "<td  class='td_color_fundal_1'></td>";
			}
			if(!$showBooking ){
				echo "<td  class='$class2'></td>";
				if($d != strtotime($endDate))
					echo "<td class='$class'></td>";
			}else{
				continue;
			}
			$d = strtotime( date('d-m-Y', $d).' + 1 day ');
		}
	}
	
}