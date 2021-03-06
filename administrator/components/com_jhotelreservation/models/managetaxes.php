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

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model'); 

class JHotelReservationModelManageTaxes extends JModelLegacy
{ 
	function __construct()
	{
		parent::__construct();
		$array 	= JRequest::getVar('tax_id',  0, '', 'array');
		$h		= JRequest::getVar('hotel_id',  0, '');
		//var_dump($array);
		if(isset($array[0])) $this->setId((int)$array[0]);
		$this->setHotelId((int)$h);
	}
	function setId($tax_id)
	{
		// Set id and wipe data
		$this->_tax_id		= $tax_id;
		$this->_data		= null;
		$this->_hotels		= null;
	}

	function setHotelId($hotel_id)
	{
		// Set id and wipe data
		$this->_hotel_id	= $hotel_id;
		$this->_data		= null;
		$this->_hotels		= null;
	}

	/**
	 * Method to get applicationsettings
	 * @return object with data
	 */
	 
	function &getHotelId()
	{
		return $this->_hotel_id;
	}
	
	/**
	 * Method to get applicationsettings
	 * @return object with data
	 */
	function &getDatas()
	{
		// Load the data
		if (empty( $this->_data )) 
		{
			$query = ' SELECT * FROM #__hotelreservation_taxes WHERE hotel_id='.$this->_hotel_id." ORDER BY tax_name ";
			//$this->_db->setQuery( $query );
			$this->_data = $this->_getList( $query );
		}
		
		return $this->_data;
	}
	
	function &getHotels()
	{
		// Load the data
		if (empty( $this->_hotels )) 
		{
			$query = ' SELECT 
							h.*,
							c.country_name
						FROM #__hotelreservation_hotels 			h
						LEFT JOIN #__hotelreservation_countries		c USING ( country_id)
						ORDER BY hotel_name, country_name ';
			//$this->_db->setQuery( $query );
			$this->_hotels = $this->_getList( $query );
		}
		return $this->_hotels;
	}
	
	function &getHotel()
	{
		$query = 	' SELECT 	
							h.*,
							c.country_name
						FROM #__hotelreservation_hotels				h
						LEFT JOIN #__hotelreservation_countries		c USING ( country_id)
						'.
					' WHERE 
						hotel_id = '.$this->_hotel_id;
		
		$this->_db->setQuery( $query );
		$h = $this->_db->loadObject();
		return  $h;
	}
	
	
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) 
		{
			$query = 	' SELECT * FROM #__hotelreservation_taxes'.
						' WHERE tax_id = '.$this->_tax_id;
			
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
					
					
			
		}
		if (!$this->_data) 
		{
			$this->_data = new stdClass();
			$this->_data->tax_id 			= null;
			$this->_data->hotel_id 			= null;
			$this->_data->tax_name			= null;			
			$this->_data->tax_type			= null;			
			$this->_data->tax_value			= null;			
			$this->_data->tax_description	= null;			
			$this->_data->is_available		= null;
			
		}

		return $this->_data;
	}

	function store($data)
	{	
		$row = $this->getTable();

		// Bind the form fields to the table
		if (!$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		return true;
	}
	
	function remove()
	{
		$cids = JRequest::getVar( 'tax_id', array(0), 'post', 'array' );
		
		$query = " 	SELECT  
						*  
					FROM #__hotelreservation_confirmations					c
					INNER JOIN #__hotelreservation_confirmations_taxes		ht USING( confirmation_id )
					WHERE 
						ht.tax_id IN (".implode(',', $cids).") AND c.reservation_status NOT IN (".CANCELED_ID.", ".CHECKEDOUT_ID." )
					";
						
		$checked_records = $this->_getList( $query );
		if ( count($checked_records) > 0 ) 
		{
			JError::raiseWarning( 500, JText::_('LNG_SKIP_TAX_REMOVE',true) );
			return false;
		}

		$row = $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					$msg = JText::_('LNG_ERROR_DELETE_TAX',true);
					return false;
				}
			}
		}
		return true;

	}
	
	function state()
	{
		$query = 	" UPDATE #__hotelreservation_taxes SET is_available = IF(is_available, 0, 1) WHERE tax_id = ".$this->_tax_id;
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) 
		{
			//JError::raiseWarning( 500, "Error change Room state !" );
			return false;
			
		}
		return true;
	}
}
?>