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
JHTML::_('script', 						'administrator/components/'.getBookingExtName().'/assets/js/jquery.selectlist.js');
JHTML::_('script', 							'administrator/components/'.getBookingExtName().'/assets/js/jquery.blockUI.js');
JHTML::_('script', 							'administrator/components/'.getBookingExtName().'/assets/js/offers.js');

JHTML::_('script', 						'administrator/components/'.getBookingExtName().'/assets/datepicker/js/datepicker.js');
JHTML::_('script', 								'administrator/components/'.getBookingExtName().'/assets/datepicker/js/eye.js');
JHTML::_('script', 							'administrator/components/'.getBookingExtName().'/assets/datepicker/js/utils.js');
JHTML::_('script', 							'administrator/components/'.getBookingExtName().'/assets/datepicker/js/layout.js');

class JHotelReservationControllerManageOffers extends JControllerLegacy
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	 
	function __construct()
	{
		parent::__construct();
		$this->registerTask( 'state', 'state');  
		$this->registerTask( 'add', 'edit');
		if( JRequest::getVar('is_error')=="1" && JRequest::getVar('task')=="save")
		{
			JRequest::setVar( 'view', 'manageoffers' ); 
			//$this->display();
		}
		if(JRequest::getVar('task')!="back")
			JRequest::setVar( 'view', 'manageoffers' );
		
		$this->registerTask( 'apply', 'save');
		$this->registerTask( 'saveAsNew', 'save');
	}


	
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('manageoffers');

		$post = JRequest::get( 'post' );
		
		if(JRequest::getVar('task')=='saveAsNew'){
			$post["offer_id"]=0;
			$post["offer_name"] = $post["offer_name"].'-Copy';
		}

		$post['offer_description'] 	= JRequest::getVar('offer_description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['offer_content'] 		= JRequest::getVar('offer_content', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['offer_other_info'] 	= JRequest::getVar('offer_other_info', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		$pictures					= array();
		$rooms						= array();
		$rooms_discounts			= array();
		$rooms_packages				= array();
		$rooms_arrival_options		= array();
		
		foreach( $post as $key => $value )
		{
			if( 
				strpos( $key, 'offer_picture_info' ) !== false 
				||
				strpos( $key, 'offer_picture_path' ) !== false
				||
				strpos( $key, 'offer_picture_enable' ) !== false				
			)
			{
				foreach( $value as $k => $v )
				{
					if( !isset($pictures[$k]) )
						$pictures[$k] = array('offer_picture_info'=>'', 'offer_picture_path'=>'','offer_picture_enable'=>1);
						
						
					$pictures[$k][$key] = $v;
				}
			}
			else if( strpos($key, "room_ids") !== false )
			{
				foreach( $post['room_ids'] as $valueRoomID )
				{
					$rooms[] = array(
									'room_id'						=> $valueRoomID,
									'offer_id'						=> 0,
									'datas'							=> $post['offer_datas'],
									'datae'							=> $post['offer_datae'],
									'offer_price'						=> array()
								);
					
					$r_d['price_type'] 						= $post["price_type_".$valueRoomID];
					$r_d['price_type_day'] 					= $post["price_type_day_".$valueRoomID];
					$r_d['child_price']		 				= $post["child_price_".$valueRoomID];
					$r_d['single_balancing']		 		= $post["single_balancing_".$valueRoomID];
					$r_d['extra_night_price']		 		= $post["extra_night_price_".$valueRoomID];
					$r_d['extra_pers_price']				= $post["extra_pers_price_".$valueRoomID];
					$r_d['base_adults']						= $post["base_adults_".$valueRoomID];
					$r_d['base_children']					= $post["base_children_".$valueRoomID];
					
					if(JRequest::getVar('task')=='saveAsNew')
						$r_d['offer_room_rate_id']				= 0;
					else 	
						$r_d['offer_room_rate_id']				= $post['offer_room_rate_id_'.$valueRoomID];
					
					$week_days 		= $post["week_day_".$valueRoomID];
					//dump($week_days);
					if(isset($post["week_types_".$valueRoomID]))
						$r_d['week_type'] 		= $post["week_type_".$valueRoomID];
					
					for($i=1; $i<=7; $i++){
						$r_d["price_".$i]= $week_days[$i-1];
					}
						
						
					//dbg($r_d);
					//exit;
					$rooms_discounts[] = $r_d;
					$rooms[ count($rooms) -1 ]["offer_price"] =  $r_d;
						
					 //exit;
					if( isset( $post['offer_room_package_id_'.$valueRoomID] ) )
					{	
						foreach( $post['offer_room_package_id_'.$valueRoomID] as $packageID )
						{
							$r_p = array( 
											'offer_id'						=>	0,
											'room_id'						=> 	$valueRoomID,
											'package_id'					=>	$packageID
											
							);
							$rooms_packages[] = $r_p;
						}
					}
					
					if( isset( $post['offer_room_arrival_option_id_'.$valueRoomID] ) )
					{	
						foreach( $post['offer_room_arrival_option_id_'.$valueRoomID] as $arrivalOptionID )
						{
							$r_p = array( 
											'offer_id'						=>	0,
											'room_id'						=> 	$valueRoomID,
											'arrival_option_id'				=>	$arrivalOptionID
											
							);
							$rooms_arrival_options[] = $r_p;
						}
					}
				}
				// dbg($rooms);
				// exit;
			}
		}
		
		$post['pictures'] 				= $pictures;
		$post['rooms'] 					= $rooms;
		$post['rooms_packages']			= $rooms_packages;
		$post['rooms_arrival_options']	= $rooms_arrival_options;
		$post['offer_order']			= $model->getLastOrder($post["offer_id"]);
		$offer_reservation_cost_val		= $post['offer_reservation_cost_val'];
		$offer_reservation_cost_proc	= $post['offer_reservation_cost_proc'];
		
		// week days when offer is available
		$nr_days		 = 0;
		for( $day=1;$day<=7;$day ++ )
		{
			if( !isset($post["offer_day_$day"]) )
				$post["offer_day_$day"] = 0;
				
			$nr_days += $post["offer_day_$day"];
		}
		// dbg($post['offer_order']);
		// exit;
		//dbg($rooms_discounts);
		//exit;
		
		if( !is_numeric($offer_reservation_cost_val) && $offer_reservation_cost_val !='' )
		{
			$msg = JText::_('LNG_ERROR_OFFER_COST_VALUE',true);
			JError::raiseWarning( 500, $msg );
		}
		else if( $offer_reservation_cost_proc !='' &&  !is_numeric($offer_reservation_cost_proc) )
		{
			$msg = JText::_('LNG_ERROR_OFFER_COST_PERCENT',true);
			JError::raiseWarning( 500, $msg );
		}
		else if( $offer_reservation_cost_proc !='' && ($offer_reservation_cost_proc < 0 || $offer_reservation_cost_proc > 100 ) )
		{
			$msg = JText::_('LNG_ERROR_OFFER_COST_PERCENT',true);
			JError::raiseWarning( 500, $msg );
		}
		else if( $nr_days == 0 )
		{
			$msg = JText::_('LNG_SELECTED_OFFER_DAYS_ERROR',true);
			JError::raiseWarning( 500, $msg );
		}
		else if( $this->checkNumberRooms($post) == false )
		{
			$msg = JText::_('LNG_SELECTED_ROOMS_ERROR',true);
			echo "<script> 
					if(document.getElementById('selected_TAB')) 
						document.getElementById('selected_TAB').value = '1';
				</script>";
			JError::raiseWarning( 500, $msg );
		}
		
		else if ( $model->store($post)) 
		{
			$msg = JText::_( 'LNG_OFFER_SAVED' ,true);
			if(JRequest::getVar('task')=='apply' || JRequest::getVar('task')=='saveAsNew')
				$this->setRedirect( 'index.php?option='.getBookingExtName().'&controller=manageoffers&view=manageoffers&task=edit&hotel_id='.$post['hotel_id'].'&offer_id='.$model->_offer_id, $msg );
			else	
				$this->setRedirect( 'index.php?option='.getBookingExtName().'&controller=manageoffers&view=manageoffers&hotel_id='.$post['hotel_id'], $msg );
		} 
		else 
		{
			$msg = "";
			JError::raiseWarning( 500, JText::_('LNG_ERROR_SAVING_OFFER',true));
			//$this->setRedirect( 'index.php?option='.getBookingExtName().'&controller=manageoffers&view=manageoffers', $msg );	
		}

		return false;
	}
	
	function checkNumberRooms($post)
	{
		if( !isset($post['room_ids']) )
			$post['room_ids'] = array();
		return count($post['room_ids']) > 0 ?  true : false;
	}
	
	
	
	
	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		
		$msg = JText::_( 'LNG_OPERATION_CANCELLED' ,true);
		$post 		= JRequest::get( 'post' );
		if( !isset($post['hotel_id']) )
			$post['hotel_id'] = 0; 
		$this->setRedirect( 'index.php?option='.getBookingExtName().'&controller=manageoffers&view=manageoffers&hotel_id='.$post['hotel_id'], $msg );
	}
	
	function delete()
	{
		$model = $this->getModel('manageoffers');
		$post = JRequest::get( 'post' );
		if( !isset($post['hotel_id']) )
			$post['hotel_id'] = 0; 
			
		if ($model->remove()) {
			$msg = JText::_( 'LNG_OFFER_HAS_BEEN_DELETED' ,true);
		} else {
			
		}

		// Check the table in so it can be edited.... we are done with it anyway
		
		$this->setRedirect( 'index.php?option='.getBookingExtName().'&controller=manageoffers&view=manageoffers&hotel_id='.$post['hotel_id'], $msg );
	}
	
	function edit()
	{
		JRequest::setVar( 'view', 'manageoffers' );
		parent::display(); 
	}
	
	function state()
	{
		$model = $this->getModel('manageoffers');
		$get = JRequest::get( 'get' );
		if( !isset($get['hotel_id']) )
			$get['hotel_id'] = 0; 

		if ($model->state()) {
			$msg = JText::_( '' ,true);
		} else {
			$msg = JText::_( 'LNG_ERROR_CHANGE_OFFER_STATE' ,true);
		}

	
		$this->setRedirect( 'index.php?option='.getBookingExtName().'&controller=manageoffers&view=manageoffers&hotel_id='.$get['hotel_id'], $msg );
	}
	
	
	function changeFeaturedState(){
		$model = $this->getModel('manageoffers');
	
		if ($model->changeFeaturedState()) {
			$msg = JText::_( 'LNG_STATE_CHANGED_SUCCESSFULLY' ,true);
		} else {
			$msg = JText::_('LNG_ERROR_CHANGE_OFFER_STATE',true);
		}
		
		$this->setMessage($msg);
		
		//JRequest::setVar( 'view', 'manageoffers' );
		$this->display();
	}
	
	function offer_order()
	{
		$tip_order 	= '';
		$offer_id 	= 0;
		if( isset( $_GET['tip_order'] ) )
			$tip_order = $_GET['tip_order'];
		if( isset( $_GET['offer_id'] ) )
			$offer_id = $_GET['offer_id'];

		$ret	= true;
		$up	 	= false;
		$down	= false;
		$e		= '';
		$p		= -1;
		$id_alter = 0;
		if( $tip_order == '' || $offer_id == 0  || $offer_id =='' )
		{
			$ret = false;
			$e = 'Invalid params';
		}
		if( $ret == true )
		{
			$db = & JFactory::getDBO();

			$db->setQuery( " 
							(
								SELECT 
									*,
									'up'	AS type_order
								FROM #__hotelreservation_offers 
								WHERE 
									offer_order <= ( SELECT offer_order FROM #__hotelreservation_offers WHERE offer_id = $offer_id ) 
									AND
									offer_id <> $offer_id
								LIMIT 1
							)
							UNION ALL
							(
								SELECT 
									*,
									'crt'	AS type_order
								FROM #__hotelreservation_offers 
								WHERE offer_order = ( SELECT offer_order FROM #__hotelreservation_offers WHERE offer_id = $offer_id ) 
								LIMIT 1
							)
							UNION ALL
							(
								SELECT 
									*,
									'down'	AS type_order
								FROM #__hotelreservation_offers 
								WHERE 
									offer_order >= ( SELECT offer_order FROM #__hotelreservation_offers WHERE offer_id = $offer_id ) 
									AND
									offer_id <> $offer_id
								LIMIT 1
							)
							
							" );
			//dbg($db);
			$rows 			= &$db->loadObjectList();
			$row_up			= null;
			$row_crt		= null;
			$row_down		= null;
			foreach( $rows as $value )
			{
				switch( $value->type_order )
				{
					case 'up':
						$row_up = $value;
						break;
					case 'crt':
						$row_crt = $value;
						break;
					case 'down':
						$row_down = $value;
						break;	
				}
			}
			$db->setQuery( " START TRANSACTION ");
			if (!$db->query() ) 
			{
				$ret = false;
				$e = ' sql STATEMENT error !';
			} 
			switch( $tip_order )
			{
				case 'up':
				{
					if( $row_up != null && $row_crt != null )
					{
						$id_alter = $row_up->offer_id;
						$db->setQuery( " UPDATE  #__hotelreservation_offers  SET offer_order = ".$row_up->offer_order." WHERE offer_id=".$row_crt->offer_id );
						if (!$db->query() ) 
						{
							$ret = false;
							$e = 'UPDATE sql STATEMENT error !';
						} 
						$db->setQuery( " UPDATE  #__hotelreservation_offers  SET offer_order = ".$row_crt->offer_order." WHERE offer_id=".$row_up->offer_id );
						if (!$db->query() ) 
						{
							$ret = false;
							$e = 'UPDATE sql STATEMENT error !';
						} 
					}
					else
						$ret = false;
					
					break;
				}
				case 'down':
				{
					if( $row_down != null && $row_crt != null )
					{
						$id_alter = $row_down->offer_id;
						$db->setQuery( " UPDATE  #__hotelreservation_offers  SET offer_order = ".$row_down->offer_order." WHERE offer_id=".$row_crt->offer_id );
						if (!$db->query() ) 
						{
							$ret = false;
							$e = 'UPDATE sql STATEMENT error !';
						} 
						
						$db->setQuery( " UPDATE  #__hotelreservation_offers  SET offer_order = ".$row_crt->offer_order." WHERE offer_id=".$row_down->offer_id );
						if (!$db->query() ) 
						{
							$ret = false;
							$e = 'UPDATE sql STATEMENT error !';
						} 
						
					}
					else
						$ret = false;
					break;
				}
			}
			if( $ret == true )
			{
				$db->setQuery( " COMMIT ");
				if (!$db->query() ) 
				{
					$ret = false;
					$e = ' sql STATEMENT error !';
				} 
				//check results
				$db->setQuery( " 
								(
									SELECT 
										*,
										'up'	AS type_order
									FROM #__hotelreservation_offers 
									WHERE 
										offer_order <= ( SELECT offer_order FROM #__hotelreservation_offers WHERE offer_id = $offer_id ) 
										AND
										offer_id <> $offer_id
									ORDER BY offer_order DESC
									LIMIT 1
								)
								UNION ALL
								(
									SELECT 
										*,
										'crt'	AS type_order
									FROM #__hotelreservation_offers 
									WHERE offer_order = ( SELECT offer_order FROM #__hotelreservation_offers WHERE offer_id = $offer_id ) 
									ORDER BY offer_order
									LIMIT 1
								)
								UNION ALL
								(
									SELECT 
										*,
										'down'	AS type_order
									FROM #__hotelreservation_offers 
									WHERE 
										offer_order >= ( SELECT offer_order FROM #__hotelreservation_offers WHERE offer_id = $offer_id ) 
										AND
										offer_id <> $offer_id
									ORDER BY offer_order
									LIMIT 1
								)
								
								" );
				//dbg($db);
				$rows 			= &$db->loadObjectList();
				$row_up			= null;
				$row_crt		= null;
				$row_down		= null;
				foreach( $rows as $value )
				{
					switch( $value->type_order )
					{
						case 'up':
							$up = true;
							break;
						case 'crt':
							break;
						case 'down':
							$down = true;
							break;	
					}
				}
				//check results
			}
			else
			{
				$db->setQuery( " ROLLBACK ");
				if (!$db->query() ) 
				{
					$ret = false;
					$e = ' sql STATEMENT error !';
				} 
			}
		
		}

		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<offer_order>';
		echo '<answer up="'.($up? "1" : "0").'" down="'.($down? "1" : "0").'" error="'.($ret ? "0" : "1").'" info="'.$e.'" "p="'.$id_alter.'" />';
		echo '</offer_order>';
		echo '</xml>';
		exit;
	}
	
	function updateThemes()
	{
		$ret = true;
		$offerId = JRequest::getVar('offerId');
		$e="";
		if( $ret == true )
		{
			$db = & JFactory::getDBO();
				
			$query = "START TRANSACTION";
			$db->setQuery($query);
			$db->queryBatch();
			if( $ret == true )
			{
				$opt_ids = $_POST['themeIds'];
				$db->setQuery (	" DELETE FROM #__hotelreservation_offers_themes
									WHERE id NOT IN (".implode(',', $opt_ids).")");
	
				if (!$db->query() )
				{
					// dbg($db);
					$ret = false;
					$e = 'INSERT / UPDATE sql STATEMENT error !';
				}
	
				foreach($_POST['themeNames'] as $key => $value )
				{
						
	
					//dbg($value);
					$recordId 			= isset($_POST['themeIds'][$key]) ?trim($_POST['themeIds'][$key]) : 0;
					$recordName			= trim($_POST['themeNames'][ $key ]);
						
						
					$db->setQuery( "
											INSERT INTO #__hotelreservation_offers_themes
											(
												id,
												name
											)
											VALUES
											(
												'$recordId',
												'$recordName'
												
											)
											ON DUPLICATE KEY UPDATE
												id 				= '$recordId',
												name			= '$recordName'
											" );
					//dbg($db);
					if (!$db->query() )
					{
						// dbg($db);
						$ret = false;
						$e = 'INSERT / UPDATE sql STATEMENT error !';
					}
						
				}
			}
	
			if( $ret == true )
			{
				$query = "COMMIT";
				$db->setQuery($query);
				$db->queryBatch();
				$m="Theme Saved Successfully!";
			}
			else
			{
				$query = "ROLLBACK";
				$db->setQuery($query);
				$db->queryBatch();
			}
	
	
		}
	
		$buff 		= $ret ? $this->getHTMLContentOffersThemes($offerId) : '';
			
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<room_statement>';
		echo '<answer error="'.($ret ? "0" : "1").'" errorMessage="'.$e.'" mesage="'.$m.'" content_records="'.$buff.'" />';
		echo '</room_statement>';
		echo '</xml>';
		exit;
	}
	
	function getHTMLContentOffersThemes($offerlId)
	{
		$path = JPATH_ADMINISTRATOR . '/components/com_jhotelreservation/views/manageoffers/view.html.php';
		include_once( $path);
	
		$view = $this->getView('manageoffers');
		$db = & JFactory::getDBO();
		$db->setQuery( "
								SELECT 
									*
								FROM #__hotelreservation_offers_themes
								ORDER BY name
								" );
		$themes 	= &$db->loadObjectList();
		$db->setQuery( "
										SELECT 
											*
										FROM #__hotelreservation_offers_themes_relation where offerId=".$offerlId );
		$selectedThemes 	= &$db->loadObjectList();
		// dbg($facilities);
		$buff = $view->displayThemes($themes, $selectedThemes);
		//var_dump($buff);
		return htmlspecialchars($buff);
	}
}