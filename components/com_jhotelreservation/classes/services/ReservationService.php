<?php 
JTable::addIncludePath('administrator/components/com_jhotelreservation/tables');

class ReservationService{

	function getReservation($reservationId=null, $hotelId = null, $checkAvailability = true){
		if(!isset($reservationId))
			$reservationId = JRequest::getInt("reservationId");
	
		$confirmationTable = JTable::getInstance('Confirmations','Table', array());
		$reservation = $confirmationTable->getReservationData($reservationId);
		
		if(!$reservationId){
			$reservation = UserDataService::createUserData( array());
			$reservation->hotelId = $hotelId;
		}else{
			$reservation->reservedItems = explode(",", $reservation->items_reserved);
			$reservation->extraOptionIds = explode(",", $reservation->extraOptionIds);
			$reservation->hotelId = $reservation->hotel_id;
			$reservation->guestDetails = $this->prepareGuestDetails($reservation->guestDetails);
			$reservation->roomGuests= explode(",", $reservation->total_adults);
			$reservation->total_adults = 0;
			if(isset($reservation->roomGuests) && count($reservation->roomGuests)>=1){
				foreach($reservation->roomGuests as $guestPerRoom){
					$values = explode("|",$guestPerRoom);
					$reservation->total_adults+= $values[0];
				}
			}
			$reservation->roomGuestsChildren= explode(",", $reservation->children);
			$reservation->total_children = 0;
			if(isset($reservation->roomGuestsChildren) && count($reservation->roomGuestsChildren)>=1){
				foreach($reservation->roomGuestsChildren as $guestPerRoom){
					$values = explode("|",$guestPerRoom);
					$reservation->total_children+= $values[0];
				}
			}
		}
		//dmp($reservation->total_adults);
		
		//dmp($reservation->roomGuests);
		
		//dmp($reservation);
		if(!isset($reservation->totalPaid))
			$reservation->totalPaid = 0;
		
		$hotel = HotelService::getHotel($reservation->hotelId);
		$reservation->currency = HotelService::getHotelCurrency($hotel);
	
		$reservationData = new stdClass;
		$reservationData->userData = $reservation;
		$reservationData->appSettings = JHotelUtil::getInstance()->getApplicationSettings();
		$reservationData->hotel = $hotel;
		
		$extraOptionIds = isset($reservationData->userData->extraOptionIds)?$reservationData->userData->extraOptionIds:null;
		$extraOptions = array();
		if(is_array($extraOptionIds) && count($extraOptionIds)>0){
			foreach($extraOptionIds as $key=>$value){
				if(strlen($value)>1){
					$extraOption = explode("|",$value);
					$extraOptions[$key] = $extraOption;
				}
			}
		}
		//dmp($_SESSION["extras"]);
		$reservationData->extraOptions = ExtraOptionsService::getHotelExtraOptions($reservationData->userData->hotelId, $reservationData->userData->start_date, $reservationData->userData->end_date, $_SESSION["extras"] , 0, 0, false);
		
		
		$reservationDetails = new stdClass;
		if($reservationId){
			$reservationDetails = $this->generateReservationSummary($reservationData, $checkAvailability);
		}
		$reservationDetails->reservationData = $reservationData;

		$reservationDetails->billingInformation = $this->getBillingInformation($reservationData->userData, $reservationData->appSettings->hide_user_email);
		
		$reservationDetails->confirmation_id = $reservation->confirmation_id;
		
		$paymentDetails = PaymentService::getConfirmationPaymentDetails($reservation->confirmation_id);
		
		if(isset($paymentDetails) && $paymentDetails->confirmation_id!=0)
			$reservationDetails->paymentInformation = $this->getPaymentInformation($paymentDetails, $reservationDetails->total, $reservationDetails->cost);
		//dmp($reservationDetails);

		return $reservationDetails;
	}
	
	public function generateReservationSummary($reservationData, $checkAvailability = true){
		//generate data for rooms
		$startDate = $reservationData->userData->start_date;
		$endDate = $reservationData->userData->end_date;
		$hotelId = $reservationData->userData->hotelId;
		$currency = $reservationData->userData->currency;
		
		$discountCode = $reservationData->userData->discount_code;
		
		$reservedItems = $reservationData->userData->reservedItems;

		$roomsPrices = array();
		if(isset($reservationData->userData->room_prices))
			$roomsPrices = explode(",", $reservationData->userData->room_prices);
				
		if(isset($reservationData->userData->roomCustomPrices)){
			$roomsPrices = $reservationData->userData->roomCustomPrices;
		}
					//echo"<pre>";var_dump($reservationData->hotel->informations);echo"</pre>";	
		//dmp($reservationData->userData->roomGuests);
		
		$selectedRooms = $this->getSelectedRooms($reservationData, $reservedItems, $roomsPrices, $hotelId, $startDate, $endDate, $reservationData->userData->roomGuests,$reservationData->userData->roomGuestsChildren, $discountCode, $checkAvailability,$reservationData->userData->confirmation_id);

		//MICOD: Nuevo parámetro agregado para tomar las tarifas de los niños según su edad para la cotización final.
		$roomsInfo = $this->getReservationDetailsRooms($reservationData->userData, $selectedRooms, $currency, $reservationData->hotel->informations);
		BookingService::setRoomAvailability($selectedRooms, array(), $hotelId, $startDate, $endDate,$reservationData->userData->confirmation_id);
		
		$nrRooms = count($selectedRooms);
		$roomNotAvailable = array();
		$showDiscounts = false;
		foreach($selectedRooms as $room){
			//dmp($room);
			if($room->is_disabled){
				$roomNotAvailable[] = $room;
			}
			//dmp($room->hasDiscounts);
			if($room->hasDiscounts){
				$showDiscounts = true;
			}
		}
		//exit;
		
		//generate extra options
		$extraOptionsInfo = null;
		$extraOptionIds = isset($reservationData->userData->extraOptionIds)?$reservationData->userData->extraOptionIds:null;
		//dmp($extraOptionIds);

		$extraOptions = array();
		if(is_array($extraOptionIds) && count($extraOptionIds)>0){
			foreach($extraOptionIds as $key=>$value){
				if(strlen($value)>1){
					$extraOption = explode("|",$value);
					$extraOptions[$key] = $extraOption;
				}
			}
		}



//micod
//Mi código modificado para poder aceptar la cotización de niños en el pasaje aereo en Extras
		//-------------------------------------------------------------------------------------------


//unset($_SESSION["extras"]);
		if(count($extraOptions, COUNT_RECURSIVE) == 17){
			$_SESSION["extras"] = $extraOptions;
			//echo "<script> alert('TRUE'); </script>";
			$verificar = true;
		}else{
			//echo "<script> alert('FALSE'); </script>";
			$verificar = false;
		}
		//dmp($_SESSION["extras"]);
		//dmp($_SESSION["ver"]);
		$selectedExtraOptions = array();

		if($verificar == true){

				if(isset($extraOptions) && count($extraOptions)>0){
				$selectedExtraOptions = ExtraOptionsService::getHotelExtraOptions($hotelId, $startDate, $endDate, $extraOptions, 0, 0);			
				$extraOptionsInfo = $this->getReservationDetailsExtraOptions($selectedExtraOptions,$extraOptions, $nrRooms,$currency);
				/*if ($_SESSION["ver"] == false){
					$_SESSION["ver"] = false;
				}else{
					
				}*/
				$_SESSION["ver"] = true;
				//echo "<script> alert('Entro en TRUE'); </script>";
			
		}

		}
		if($verificar == false){

				if(isset($extraOptions) && count($extraOptions)>0){
				$selectedExtraOptions = ExtraOptionsService::getHotelExtraOptions($hotelId, $startDate, $endDate, $extraOptions, 0, 0);			
				$extraOptionsInfo = $this->getReservationDetailsExtraOptions($selectedExtraOptions,$extraOptions, $nrRooms,$currency);
				//echo "<script> alert('Entro en FALSE'); </script>";
				//$_SESSION["ver"] = false;

				if($_SESSION["ver"] == true){
				
				if(isset($extraOptions) && count($extraOptions)>0){
				$selectedExtraOptions = ExtraOptionsService::getHotelExtraOptions($hotelId, $startDate, $endDate, $extraOptions, 0, 0);			
				$extraOptionsInfo = $this->getReservationDetailsExtraOptions($selectedExtraOptions,$extraOptions, $nrRooms,$currency);
				//echo "<script> alert('Entro en el que queríamos'); </script>";
				//unset($_SESSION["ver"]);
			}

		}

				
			}
}


			//-------------------------------------------------------------------------------------------




		
		//dmp($selectedExtraOptions);
		//generate course/excursions
		$excursionsInfo = null;
		$selectedExcursions = null;
		if($reservationData->appSettings->enable_excursions && count($reservationData->userData->excursions)>0){
			$excursionData= $reservationData->userData->excursions;
			if(!is_array($reservationData->userData->excursions))
				$excursionData= explode(",",$reservationData->userData->excursions);

			$selectedExcursions =ExcursionsService::getSelectedExcursions($excursionData, $reservedItems, $hotelId, $startDate, $endDate, $reservationData->userData->roomGuests,$reservationData->userData->roomGuestsChildren, $discountCode, $checkAvailability,$reservationData->userData->confirmation_id);
			$excursionsInfo = $this->getReservationDetailsExcursions($reservationData->userData, $selectedExcursions, $currency);
		}
		
		$costData = $this->getReservationCostData($selectedRooms); //LA CUOTA A PAGAR!!!!!!!!
		
		$guestDetails = array();
		if(isset( $reservationData->userData->guestDetails)){
			$guestDetails = $reservationData->userData->guestDetails;
		}
		
		$taxes = TaxService::getTaxes($hotelId);
		$reservationDetails = $this->getReservationDetails($reservationData, $roomsInfo, $extraOptionsInfo,$excursionsInfo, $taxes, $guestDetails, $currency, $costData);
		//echo"<pre>";var_dump($reservationDetails);echo"</pre>";
		$reservationDetails->rooms = $selectedRooms;
		$reservationDetails->roomsInfo = $roomsInfo;
		$reservationDetails->extraOptions = $selectedExtraOptions;
		$reservationDetails->extraOptionsInfo = $extraOptionsInfo;
		
		//dmp($extraOptions);
		$reservationDetails->roomNotAvailable= $roomNotAvailable;
		$reservationDetails->showDiscounts = $showDiscounts;
		$reservationDetails->costData= $costData;
		$reservationDetails->excursions= $selectedExcursions;
		$reservationDetails->excursionsInfo = $excursionsInfo;
		
		return $reservationDetails;
	}

	function prepareGuestDetails($guestDetails){
		$result = array();
		$guestDetails = explode(",", $guestDetails);
		foreach($guestDetails as $guestDetail){
			$guest = new stdClass();
			$value = explode("|",$guestDetail);
			if(isset($value[0]) && isset($value[1]) && isset($value[2])){
				$guest->first_name = $value[0];
				$guest->last_name = $value[1];
				$guest->identification_number = $value[2];
				$result[] = $guest;
			}
			
		}
		return $result;
	}
	
	function getExtraOptionIds($extraOptionIds, $index){
		$result = array();
		foreach($extraOptionIds as $extraOptionId){
			if($index == $extraOptionId[2]){
				$result[]=$extraOptionId[3];
			}
		}
		return $result;
	}
	
	function getExtraOptionInfo($extraOptionIds, $index){
		$result = array();
		//dmp($index);
		foreach($extraOptionIds as $extraOptionId){
			if($index == $extraOptionId[2]){
				//dmp($extraOptionId);
				$extrInfo = new stdClass();
				$extrInfo->id = $extraOptionId[3];
				$extrInfo->persons = $extraOptionId[5];
				$extrInfo->days = $extraOptionId[6];
				$extrInfo->offerId = $extraOptionId[0];
				$extrInfo->roomId = $extraOptionId[1];
				$extrInfo->current = $extraOptionId[2];
				$extrInfo->child = $extraOptionId[7];
				$result[$extrInfo->id] = $extrInfo;
			}
		}
		return $result;
	}
	/*------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	/*MICOD.
	Nuevo parámetro anexado "$reservationData" para los datos de las edades de las avitaciones y las configuraciones de edades por cada hotel*/
	function getSelectedRooms($reservationData, $reservedItems, $customPrices, $hotelId, $startDate, $endDate, $roomGuests, $roomGuestsChildren, $discountCode, $checkAvailability = true,$confirmationId=null){
	/*------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
		$selectedRooms = array();

		foreach($reservedItems as $key => $reservedItem){ //MICOD. Clave "$key" agregado para contar las interacciones
			$values = explode("|",$reservedItem);
			if(count($values)<2) continue;
			$nr_guests= 0;
			$selectedRoom = null;
			if(isset($roomGuests[$values[2]-1]))
				$adults = $roomGuests[$values[2]-1];
			else 
				$adults = 2;
			if(isset($roomGuestsChildren[$values[2]-1]))
				$children = $roomGuestsChildren[$values[2]-1];
			else
				$children = 0;
			
			if($values[0]==0){

				/*------------------------------------------------------------------------------------------*/
				/*MICOD
				Nuevo pase de parámetros donde capturo el número de habitaciones por interacción del FOREACH. También anexado el "$reservationData"*/
				$selectedRoom = HotelService::getHotelRooms($hotelId, $startDate, $endDate,array($values[1]), $adults, $children, $discountCode, $checkAvailability,$confirmationId, $key, $reservationData);
				/*------------------------------------------------------------------------------------------*/
			}else{
				$selectedRoom = HotelService::getHotelOffers($hotelId, $startDate, $endDate,array($reservedItem), $adults, $children, $discountCode, $checkAvailability,$confirmationId);
			}
			if(count($selectedRoom)==0){
				$selectedRoom = new stdClass();
				$selectedRoom->current =$values[2];
				$selectedRoom->is_disabled=false;
				$selectedRoom->hasDiscounts=false;
				$selectedRoom->offer_id = 0;
				$selectedRoom->reservation_cost_val  = 0;
				$selectedRoom->reservation_cost_proc= 0;
				$selectedRoom->customPrices =array();
				$selectedRoom->daily =array();
				$selectedRooms[$values[2]-1] = $selectedRoom;
			}
			else{
				$selectedRoom = $selectedRoom[0];
				$selectedRoom->current = $values[2];
				$selectedRoom->customPrices =  $this->getCustomPrices($selectedRoom, $customPrices);
				$selectedRooms[$values[2]-1] = $selectedRoom;
			}
			
		}
		ksort($selectedRooms);
		
		return $selectedRooms;
	}

	
	function getCustomPrices(&$room, $customPrices){
		$result = array();
	
		foreach($customPrices as $customPrice){
			$values = explode("|",$customPrice);
			
			if($values[0] == $room->offer_id 
				&& $values[1] == $room->room_id
				&& $values[2] == $room->current){
				$result[$values[3]] = $values[4];
			}
		}
	
		return $result;
	}
	
  	function getReservationDetails($reservationData, $roomsInfo, $extraOptionsInfo,$excursionsInfo, $taxes, $guestDetails, $currency, $costData){

		ob_start();
		?>
			<table class="reservation_details" cellspacing="0" cellpadding="0" border="0" width="100%" style="border: 1px solid rgb(190, 188, 183); background: none repeat scroll 0% 0% rgb(248, 247, 245);border-collapse: separate;">
			  <thead>
				<tr bgcolor="#C7D9E7" class='rsv_dtls_main_header'>
					<th  colspan="7" align="left" style="padding: 3px 9px;">
						<!--micod-->
						<strong><?php //echo JText::_('LNG_RESERVATION_DETAILS',true); ?> Detalles de la Pre-reserva</strong>
					</th>
				</tr>
			 </thead>
				<tbody class='rsv_dtls_container'>
				<?php if( isset($reservationData->hotel->hotel_id) && $reservationData->hotel->hotel_id >0 ) { ?>
					<tr bgcolor="#F8F7F5" class='rsv_dtls_hotel_container'>
						<td style="padding: 3px 9px;" colspan="10">
							<table>
								<tr>
									<td>
										<div style=" -moz-box-shadow: 0 2px 5px #969696; 	-webkit-box-shadow: 0px 2px 5px #969696; box-shadow: 0px 2px 5px #969696; float: left; padding: 2px;background-color: #FFFFFF;">
											<img height="70" style="height: 70px;border: medium none; float: left;"
											src="<?php echo isset($reservationData->hotel->pictures[0])?JURI::root() .PATH_PICTURES.$reservationData->hotel->pictures[0]->hotel_picture_path:"" ?>" alt="Hotel Image" />
										</div>
									</td>
									<td style="padding-left: 10px;">
										<span style="float: left;font-size: 15px !important; font-weight: bold;  line-height: 24px; margin: 0;"><?php echo $reservationData->hotel->hotel_name?></span>
										<span style="  float: left;    margin-left: 10px;    margin-top: 3px;">
											<?php
											for ($i=1; $i<=$reservationData->hotel->hotel_stars; $i++){ ?>
												<img  src='<?php echo JURI::root() ."administrator/components/".getBookingExtName()."/assets/img/star.png" ?>' />
											<?php } ?>
										</span>
										<br>
										<div class="hotel-address"  style="display: inline-block; font-size: 11px; margin-bottom: 5px; width: 100%;">
											<?php echo $reservationData->hotel->hotel_address?>, <?php echo isset($reservationData->hotel->hotel_zipcode)?$reservationData->hotel->hotel_zipcode.", ":""?> <?php echo $reservationData->hotel->hotel_city?>, <?php echo $reservationData->hotel->hotel_county?>, <?php echo $reservationData->hotel->country_name?>
										</div>		
										
										<span class="hotel-address" style="display: inline-block; font-size: 11px;"><?php echo JText::_('LNG_TELEPHONE_NUMBER',true).' '.$reservationData->hotel->hotel_phone  ?> </span>

									</td>
								</tr>
							</table>
						</td>
					</tr>
					
					
					<tr bgcolor="#D9E5EE" class='rsv_dtls_header'>
						<td colspan="6" align="left" style="padding: 3px 9px;">&nbsp;</td>
						<td align="right" style="padding: 3px 9px;">&nbsp;</td>
					</tr><?php }?>
					<!--micod-->
					<!--Lugar de la vista de la cotización donde se muestra la hora de llegada, de salida, número de habitaciones y los huespedes-->
					<tr bgcolor="#F8F7F5" class='rsv_dtls_hotel_container'>
						<td style="padding: 3px 9px 3px 0;" colspan="10">
							<table>
								<?php
									if( isset($reservationData->userData->confirmation_id) && $reservationData->userData->confirmation_id >0 )
									{
									?>
									<tr>
										<td  align="left" valign="top" style="padding: 3px 9px;">
											<strong><?php echo JText::_('LNG_ID_RESERVATION',true); ?></strong>
										</td>
										<td style="padding: 3px 9px;" colspan="4" align="left">
											<span class='title_ID'><?php echo JHotelUtil::getStringIDConfirmation($reservationData->userData->confirmation_id)?></span>
										</td>
									</tr>
									<?php
									}
									?>
									<?php if($reservationData->userData->rooms && count($roomsInfo)>0){?>
									<tr>	
										<td align="left" valign="top" style="padding: 3px 9px;">
											<strong><?php echo JText::_('LNG_ARIVAL'); ?></strong>
										</td>
										<td  align="left" style="padding: 3px 9px;">
											<?php echo JHotelUtil::getDateGeneralFormat($reservationData->userData->start_date) ?> (<?php echo JText::_('LNG_CHECK_IN'); ?> <?php echo $reservationData->hotel->informations->check_in  ?>)
										</td>
									</tr>	
									<tr>	
										<td  align="left" valign="top" style="padding: 3px 9px;">
											<strong><?php echo JText::_('LNG_DEPARTURE'); ?></strong>
										</td>
										<td  align="left" style="padding: 3px 9px;">
											<?php echo JHotelUtil::getDateGeneralFormat($reservationData->userData->end_date) ?> (<?php echo JText::_('LNG_CHECK_OUT'); ?> <?php echo  $reservationData->hotel->informations->check_out ?>)
										</td>
									</tr>	

									<tr>
										<td  align="left" valign="top" style="padding: 3px 9px;">
											<strong><?php echo isset($reservationData->hotel->types) && $reservationData->hotel->types[0]->id == PARK_TYPE_ID ?JText::_('LNG_NUMBER_OF_PARKS',true) : JText::_('LNG_NUMBER_OF_ROOMS',true); ?></strong>
										</td>
										<td  align="left" style="padding: 3px 9px;">
											<?php 
											 
											echo $reservationData->userData->rooms > 0? $reservationData->userData->rooms.'&nbsp;'.($reservationData->userData->rooms >1? strtolower(isset($reservationData->hotel->types) && $reservationData->hotel->types[0]->id == PARK_TYPE_ID ?JText::_('LNG_PARKS',true): JText::_('LNG_ROOMS',true)) :  strtolower(isset($reservationData->hotel->types) && $reservationData->hotel->types[0]->id == PARK_TYPE_ID ?JText::_('LNG_PARK',true): JText::_('LNG_ROOM',true)) ) : ""?>
										</td>
									</tr>


									<?php
									/* Precio del adulot y niño en la cotización, hay que ponerlo en cada habitación
									Recordar también que éste dato lo recibe del día de partida que el cliente escogió.
									Hay hoteles que tiene costes distintos por día y por temporada...
									?>
									<!--micod-->
									<!--Precio total por adulto en la cotización-->
									<tr>	
										<td align="left" valign="top" style="padding: 3px 9px;">
											<strong><?php //echo JText::_('LNG_GUESTS'); ?> Precio por adulto</strong>
										</td>
										<td   align="left" valign="top" style="padding: 3px 9px;">
												<?php echo $_SESSION["adult_price"]." ".$currency->symbol; ?>
										</td>					
									</tr>
									<!--micod-->
									<!--Precio total por niño en la cotización-->
									<?php
									if ($reservationData->userData->total_children!=0) {
									?>
									<tr>	
										<td align="left" valign="top" style="padding: 3px 9px;">
											<strong><?php //echo JText::_('LNG_GUESTS'); ?> Precio por niño</strong>
										</td>
										<td   align="left" valign="top" style="padding: 3px 9px;">
												<?php echo $_SESSION["child_price"]." ".$currency->symbol; ?>
										</td>					
									</tr>
									<?php
									}
									*/
									?>

									
									<!--Total de Huespedes-->
									<tr>	
										<td align="left" valign="top" style="padding: 3px 9px;">
											<strong><?php echo JText::_('LNG_GUESTS'); ?></strong>
										</td>
										<td   align="left" valign="top" style="padding: 3px 9px;">
												<?php echo $reservationData->userData->total_adults > 0? $reservationData->userData->total_adults.'&nbsp;'.JText::_('LNG_ADULT_S',true) : ""?>
												&nbsp;&nbsp;&nbsp;<?php if(isset($reservationData->userData->total_children))  echo $reservationData->userData->total_children > 0? $reservationData->userData->total_children.'&nbsp;'.JText::_('LNG_CHILD_S',true) : ""?>
										</td>					
									</tr>


									<?php }?>
									<?php if(!empty($reservationData->userData->remarks) || !empty($reservationData->userData->discount_code)){?>
									<tr>	
										<td align="left" valign="top" style="padding: 3px 9px;">
											<strong><?php echo JText::_('LNG_REMARKS'); ?></strong>
										</td>
										<td  align="left" valign="top" style="padding: 3px 9px;">
												<?php echo $reservationData->userData->remarks ?>
												<br/>
												<?php if(!empty($reservationData->userData->discount_code)){
													echo JText::_('LNG_DISCOUNT_CODE')." ".$reservationData->userData->discount_code;
													}
												 ?>
										</td>					
									</tr>
									<?php } ?>
									<?php if(isset($guestDetails) && count($guestDetails)>0){?>
										<tr>
											<td align="left" valign="top" style="padding: 3px 9px;">
												<strong><?php echo JText::_('LNG_GUEST_DETAILS'); ?></strong>
											</td>
											<td>
												<table>
													<tr>
														<td style="padding: 3px 9px;">
															<?php echo JText::_('LNG_FIRST_NAME');?>
														</td>
														<td style="padding: 3px 9px;">
															<?php echo JText::_('LNG_LAST_NAME');?>
														</td>
														<td style="padding: 3px 9px;">
															<?php echo JText::_('LNG_PASSPORT_NATIONAL_ID',true);?>
														</td>
													</tr>
													<?php foreach($guestDetails as $guestDetail){?>	
														<tr>
															<td style="padding: 3px 9px;">
																<?php echo $guestDetail->first_name?>
															</td>
															<td style="padding: 3px 9px;">
																<?php echo $guestDetail->last_name?>
															</td>
															<td style="padding: 3px 9px;">
																<?php echo $guestDetail->identification_number?>
															</td>
														</tr>
													<?php } ?>
												</table>
											</td>
										</tr>
									<?php } ?>
							</table>
						</td>
					</tr>
				
					
				
					<tr bgcolor="#D9E5EE" class='rsv_dtls_header'>
					<td colspan="6" align="left" style="padding: 3px 9px;"><?php echo JText::_('LNG_ITEM',true)?></td>
					<td align="right" style="padding: 3px 9px;"><?php echo JText::_('LNG_SUBTOTAL',true)?></td>
					</tr>
					<!--room details  -->
					<?php
						//dmp($roomsInfo);
						//dmp($extraOptionsInfo);
						$reservationPrice = 0; 
						foreach($roomsInfo as $key=>$roomInfo){
							$subtotalRooms = 0;
							if($roomInfo->roomPrice!=0)
								echo $roomInfo->roomDescription;
							$subtotalRooms += $roomInfo->roomPrice;
							if(isset($extraOptionsInfo[$key])){
								echo $extraOptionsInfo[$key]->description;
								$subtotalRooms += $extraOptionsInfo[$key]->extraOptionsAmount;
							}
							
							$reservationPrice += $subtotalRooms;
							/*echo "<pre>";
							var_dump($reservationPrice);
							echo "</pre>";*/
						
						?>
						
						<?php if($subtotalRooms!=0){?>
							<!--MICOD: Comentado Subtotal por Habitación.
							<tr class='rsv_dtls_subtotal'  bgcolor="#EFEDE9" >
								<td colspan=6 align="right">
									<strong><?php echo JText::_('LNG_ESTIMATED_SUBTOTAL' )?> (<?php echo $currency->symbol?>)</strong>
								</td>
								<td align=right style="padding: 3px 9px;">
									<strong><?php echo JHotelUtil::fmt($subtotalRooms,2)?></strong>
								</td>
							</tr>-->
						<?php }?>
						<?php 
						}
						
						//display courses/excursions
						if(is_array(($excursionsInfo)))
  						foreach($excursionsInfo as $key=>$excursionInfo){
							$subtotalExcursions= 0;
							echo $excursionInfo->excursionDescription;
							$subtotalExcursions += $excursionInfo->excursionPrice;
							$reservationPrice += $subtotalExcursions;
						?>
							<?php if($subtotalExcursions>0){?>
							<tr class='rsv_dtls_subtotal'  bgcolor="#EFEDE9" >
								<td colspan=6 align="right">
									<strong><?php if($subtotalExcursions>0) echo JText::_('LNG_ESTIMATED_SUBTOTAL' )?> (<?php echo $currency->symbol?>)</strong>
								</td>
								<td align=right style="padding: 3px 9px;">
									<strong><?php echo JHotelUtil::fmt($subtotalExcursions,2)?></strong>
								</td>
							</tr>
						<?php 
							}
						}
						
					?>
					
					<!--MICOD
					Comentado el subtotal de la cotización, muestra el mismo resultado que la cotización total-->
					<!--<tr class='rsv_dtls_total_room_price' bgcolor="#EFEDE9">
						<td align="right" colspan="6" style="border-top:solid 2px gray;padding: 3px 9px;">
							<strong><?php echo JText::_('LNG_TOTAL_ROOMS_RATES')?> (<?php echo $currency->symbol?>)</strong>
						</td>
						<td align="right" style="border-top:solid 2px gray;padding: 3px 9px">
							<strong><?php echo JHotelUtil::fmt($reservationPrice)?></strong>
						</td>
					</tr>-->
				
					<?php
						$val_taxes = 0;
						foreach( $taxes as $tax)
						{
							if( $tax->tax_type =='Fixed'){
								$val_taxes = $tax->tax_value;
							}else{
								$val_taxes = ($tax->tax_value * $reservationPrice / 100);
							}
							
							if( $val_taxes == 0 )
								continue;
							?>
							<tr>
								<td colspan=6 align="right" style="padding: 3px 9px;">
									<?php echo $tax->tax_name?>
									(<?php echo (($tax->tax_value).' '.($tax->tax_type=='Fixed'? ($currency->symbol) : ' % ') )?>)
								</td>

								<td align="right" style="padding: 3px 9px;">
									<?php echo JHotelUtil::fmt($val_taxes)?>
								</td>

							</tr>
							<?php
							$reservationPrice += $val_taxes;
						}
					?>
					<?php

					if($reservationData->appSettings->charge_only_reservation_cost)
						$costData->bIsCostV = true;
					
										
					if( $costData->bIsCostV )
					{
						?>
						<!--MICOD
						Comentada el ajuste de la reserva, su resultado siempre es 0,00Bs.F-->
						<!--<tr class='rsv_dtls_subtotal' bgcolor="#EFEDE9">
							<td colspan=6 align="right"><strong><?php echo JText::_('LNG_COST_VALUE',true)?>
									(<?php echo $currency->symbol?>)</strong>
							</td>
							<td align=right style="padding: 3px 9px;"><strong><?php echo JHotelUtil::fmt( $costData->costV,2)?>
							</strong>
							</td>
						</tr>-->
						<?php
					 }
					 
					 if( $costData->bIsCostV )
					 {
						?>
						<tr class='rsv_dtls_subtotal' bgcolor="#EFEDE9">
						<!--MICOD Etiqueta TD editado, atributo de estilo agregado-->
							<td colspan=6 align="right" style="border-top:solid 2px gray;padding: 3px 9px;"><!--<strong><h3><?php echo JText::_('LNG_ESTIMATED_TOTAL')?>
									(<?php echo $currency->symbol?>)</h3></strong>-->
							</td>
							<!--MICOD Etiqueta TD editado, atributo de estilo agregado, originalmente solo tenía el "padding"-->
							<td align=right style="border-top:solid 2px gray;padding: 3px 9px;"><h3><?php //echo JText::_('LNG_ESTIMATED_TOTAL').": " ?> Cálculo Total: <strong><?php echo $currency->symbol." ".JHotelUtil::fmt( $reservationPrice + $costData->costV,2)?>
							</strong></h3>
							</td>
						</tr>
						<?php
					 }
					
				
					$total_cost	= 0;
					//dmp($this->tip_oper);
					// dmp($arr_val_amount_to_pay );
					










					//micod
					//EL IMPORTE A PAGAR!!!

					/*if( $reservationData->userData->totalPaid == 0 )
					{
					?>
					<tr class='rsv_dtls_subtotal'  bgcolor="#EFEDE9">
						<td colspan=6 align="right">
							<strong>
								
								<?php echo JText::_('LNG_AMOUNT_PAY',true)?> 
								
								<?php
								if($costData->costV > 0 )
									echo "(".JText::_('LNG_COST_VALUE',true);
								if($costData->costV  > 0 && $costData->percent  > 0  )
									echo ' + ';
								if($costData->percent  > 0 )
									echo $costData->percent.'% '.JText::_('LNG_ESTIMATED_SUBTOTAL',true);
								if($costData->costV > 0 )
									echo ")";
								?>
								
								(<?php echo $currency->symbol?>)
							</strong>
						</td>
						
						
						<td align=right style="padding: 3px 9px;">
							<?php if($costData->bIsCostV || $costData->bIsCostP){ ?>
								<strong><?php echo JHotelUtil::fmt(($costData->costV + $costData->percent * $reservationPrice /100),2)?> </strong>
							<?php }else{?>
								<strong><?php echo JHotelUtil::fmt($reservationPrice, 2)?></strong>
							<?php } ?>
						</td>
					</tr>
					<?php
					}*/














					$total_cost  += $costData->costV + $costData->percent * $reservationPrice /100;
					$reservationPrice = $reservationPrice + $costData->costV;
				
					/*
					if($costData->bIsCostV || $costData->bIsCostP){
					?>
						<tr class='rsv_dtls_total_price' bgcolor="#dee5e8" style='border-top:solid 3px black'>
		
							<td align="right" colspan="6" style="padding: 3px 9px;">
								<strong><?php echo JText::_('LNG_GRAND_TOTAL',true)?> (<?php echo$currency->name?>)</strong>
							</td>
		
							<td align="right" style="padding: 3px 9px;">
								<strong><?php echo JHotelUtil::fmt($reservationPrice)?></strong>
							</td>
						</tr>
						
						<?php
						?>
						<tr class='rsv_dtls_subtotal'  bgcolor="#EFEDE9">
							<td colspan=6 align="right">
								<strong><?php echo JText::_('LNG_COST_TOTAL',true)?> 
								(<?php echo $currency->name?>)
								</strong>
							</td>
							<td align=right style="padding: 3px 9px;">
								<strong><?php echo JHotelUtil::fmt( $total_cost,2)?></strong>
							</td>
						</tr>
					<?php
					}*/
					



















					//micod
					//PAGO RESTANTE DESPUÉS DEL IMPORTE A PAGAR!!!!!!!!!!!

					/*if( $reservationData->userData->totalPaid >0 )
					{
						?>
							<tr class='rsv_dtls_subtotal' bgcolor="#EFEDE9">
								<td colspan=6 align="right"><strong><?php echo JText::_('LNG_TOTAL_PAID',true)?>
										(<?php echo $currency->symbol?>) <?php echo isset($reservationData->userData->payment_method)?' - '.strtoupper($reservationData->userData->payment_method):'' ?></strong>
								</td>
								<td align=right style="padding: 3px 9px;"><strong><?php echo JHotelUtil::fmt( $reservationData->userData->totalPaid,2)?>
								</strong>
								</td>
							</tr>
							<?php
						 }
				
				if( $total_cost > 0 || $reservationData->userData->totalPaid > 0)
				{
					?>
					<tr class='rsv_dtls_subtotal'  bgcolor="#EFEDE9">
						<td colspan=6 align="right">
							<strong><?php echo isset($reservationData->hotel->types) && $reservationData->hotel->types[0]->id == PARK_TYPE_ID ?JText::_('LNG_REMAINING_PARK_PAY',true) : JText::_('LNG_REMAINING_PAY',true)?> 
							(<?php echo $currency->symbol?>)
							</strong>
						</td>
						<td align=right style="padding: 3px 9px;">
							<?php if ($reservationData->userData->totalPaid == 0 ){?>
								<strong><?php echo JHotelUtil::fmt($reservationPrice - $total_cost,2)?></strong>
							<?php }else{?>
								<strong><?php echo JHotelUtil::fmt($reservationPrice -$reservationData->userData->totalPaid,2)?></strong>
							<?php }?>
						</td>
					</tr>
					<?php
				} */?>
				



















			</table>
			<?php
		
			$reservationInfo = ob_get_contents();
			ob_end_clean(); 
		
			$reservationDetails = new stdClass();
			$reservationDetails->total = $reservationPrice;
			$reservationDetails->cost = $total_cost;
			$reservationDetails->reservationInfo = $reservationInfo;
//echo"<pre>";var_dump($reservationInfo);echo"</pre>";
			//MICOD
			//$reservationData es el que posee las edades de los niños cargados desde la base de datos.
			$reservationDetails->reservationData = $reservationData; //Agregamos a $reservationData dentro de $reservationDetails para cargar las edades en el editor de reservas.

			return $reservationDetails;
	}

	public function getReservationDetailsRooms($resevation, $rooms, $currency, $information){ //MICOD: Nuevo parámetro para las configuraciones de las edades de los niños.
		$result = array();
		$nr_days_except_offers	= 0;
		$index = 0;
		$pos = 0;
		$cont_precios_iguales = 0;
		$cont_precios_desiguales = 1;
		$cont_day = -1;
		//echo"<pre>";var_dump($rooms);echo"</pre>";
		//MICOD: Convertimos las configuariones de las edades en un arreglo.
		$niños_libre_tarifas = explode("|", $information->niños_libre_tarifas);
		//echo"<pre>";var_dump($niños_libre_tarifas);echo"</pre>";
		$niños_tarifa_ajustada = explode("|", $information->niños_tarifa_ajustada);
		//echo"<pre>";var_dump($niños_tarifa_ajustada);echo"</pre>";
		$edad_tarifa_adult = explode("|", $information->edad_tarifa_adult);
		//echo"<pre>";var_dump($edad_tarifa_adult);echo"</pre>";
		foreach( $rooms as $key=>$room )
		{
		//echo"<pre>";var_dump($rooms);echo"</pre>";
			if ($resevation->child_age != NULL) {
				$edades = explode("|", $resevation->child_age);
			}else{
				$edades = explode("|", $resevation->child_ages[$key]);
			}
			
			$index++;
			$showRoomDescription = true;
			$roomInfo = new stdClass();
			$totalRoomPrice 	= 0;
			$dayCounter = 0;
			$showPricePerDay = true;
			if(isset($room->price_type_day) && $room->price_type_day == 1) {
				$showPricePerDay = false;
			}
			ob_start();
			//echo"<pre>";var_dump($rooms[1]->roomRateDetails);echo"</pre>";
			/*foreach ($rooms[$key]->roomRateDetails as $key2 => $value) {
					if ($key2 == 1) {
					$precio_buff = $value->price;
					$dia_inicial = $value->date;
				}elseif ($key2 > 1) {
					if ($precio_buff == $value->price) {
						$cont_precios_iguales++;
						$desigual = false;
					}else{
						$cont_precios_desiguales++;
						$desigual = true;
					}
					if (($desigual == true) || ($cont_precios_iguales >= 7)){
						if ($cont_precios_iguales >= 7) {
							$seven_days = true;
						}else{
							echo" Posición: ".$key2." ";
							echo" Desde el: ".$dia_inicial." hasta el: ".$value->date." es un precio. <br>";
						}
						$cont_precios_iguales = 0;
						$dia_inicial = $value->date;
						$precio_buff = $value->price;
					}
				}
				if($seven_days == true){
					echo "Habitación: ".$key." Key: ".$key2." Fecha: ".$value->date." ";
					echo "Todos los días el mismo precio";
				}
			}*/
			foreach( $room->daily as $key_day=>$day)
			{
				$price_day = $day['price_final'];
				if(isset($room->customPrices) && isset($room->customPrices[$day["date"]])){
					$price_day = $room->customPrices[$day["date"]]; 
				}
				
				$nrDays = JHotelUtil::getNumberOfDays($resevation->start_date, $resevation->end_date);
				$info_discount	= '';
				$dayCounter ++;
				foreach( $day['discounts'] as $d )
				{
					if( strlen($info_discount)>0)
						$info_discount	.='<BR>';
					$info_discount	.= $d->discount_name.' '.JHotelUtil::fmt(-1 * $d->discount_value).''.($d->percent==1?"%":" ".$currency->symbol);
				}
	
				if( strlen($info_discount)>0){
					$info_discount = "<div class='discount_info'>".$info_discount.'</div>';
				}
			
				?>
				
				<tr class='rsv_dtls_room_info'>
					<?php
					if( $showRoomDescription)
					{
						?>
						<td colspan=5 align="left" valign="top" style="border-top:solid 3px grey;padding: 3px 9px;"	rowspan='<?php echo !$showPricePerDay ? 1:count($room->daily)?>'>
							
							<?php if(count($rooms)>1){ ?>
								<strong>#<?php echo $index?></strong>
							<?php }?>
							<?php
									if($room->offer_id  > 0){
										echo "<strong>".$room->offer_name."</strong> <br/>";
										echo $room->offer_content;
									} 
									else
									{ 
										$i = $index - 1;
										//echo $room->room_name .' (<i>'.JText::_('LNG_CAPACITY',true).' '.$room->room_capacity.' '.( $room->room_capacity > 1 ? JText::_('LNG_PERS',true): JText::_('LNG_PER',true) ).'</i>)';

										//Original:
										//echo '<strong>'.$room->room_name.'</strong>'.' (<i>'.JText::_('LNG_CAPACITY',true).' '.$room->max_adults.' '.strtolower(JText::_('LNG_ADULTS',true)).($room->max_children > 0 ?' | '.$room->max_children.' '.JText::_('LNG_CHILDREN',true):'').'</i>)';
										
										//MICOD
										echo '<strong>'.$room->room_name.'</strong>'.' (<i>Huespedes: '.$resevation->roomGuests[$i].' '.strtolower(JText::_('LNG_ADULTS',true)).($resevation->roomGuestsChildren[$i] > 0 ?' | '.$resevation->roomGuestsChildren[$i].' '.JText::_('LNG_CHILDREN',true):'').'</i>) <br>';
									}
									//echo"<pre>";var_dump($resevation->roomGuests);echo"</pre>";
								?>		
								<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
								<!--MICOD-->
								<span style="float: left; margin-left: 9px;">
								<!--Agregadas etiquetas HTML para mostrar el precio de los adultos por habitación-->
								<strong>Tarifa por adulto: </strong><span><? if ($rooms[$key]->roomRateDetails[0]->price == NULL) {
									echo $currency->symbol, " ", $rooms[$key]->price_1;
								}else{
									echo $currency->symbol, " ", $rooms[$key]->roomRateDetails[0]->price;
									} ?></span><br>
								<?php
									if ($rooms[$key]->children != 0) {//Condicional para ocultar la etiqueta "<strong>" cuando hay ningún niño.
								?>
								<hr></hr>
								<!--Agregadas etiquetas HTML para mostrar el precio de los niños por habitación según la configuración de edad-->
								<strong> Tarifa por niño: </strong><br>
								<?php
									}
								//Contadores para cada caso de niño
								$cont_libre = 0;
								$cont_ajustada = 0;
								$cont_adulta = 0;
								$cont_normal = 0;
								foreach ($edades as $key2 => $value) {//Iteramos cada niño con su edad para someterlo a los controles de tarifas
									//Checkeo de cada configuración para verificar si fueron activados o no
									$check_gratis = false;
									$check_ajustada = false;
									$check_adutlo = false;
									$check_normal = false;
									if ($niños_libre_tarifas[2] == 1) {//Verificamos si las tarifas libres están activadas
										if (($value != "") && ($value != "Edad")) {//Solo pasan los niños con edades
											if (($value >= $niños_libre_tarifas[0]) && ($value <= $niños_libre_tarifas[1])) {//Verificamos el rango de edad
												$check_gratis = true;//Checkeo activado
												$cont_libre++;//Contador
											}
										}
									}
									if ($niños_tarifa_ajustada[4] == 1) {//Verificamos si las tarifas ajustadas están activadas
										if (($value != "") && ($value != "Edad")) {//Solo pasan los niños con edades
											if (($value >= $niños_tarifa_ajustada[0]) && ($value <= $niños_tarifa_ajustada[1])){//Verificamos el rango de edad
												$check_ajustada = true;//Checkeo activado
												$cont_ajustada++;//Contador
											}
										}
									}
									if ($edad_tarifa_adult[1] == 1) {//Verificamos si las tarifas adultas están activadas
										if (($value != "") && ($value != "Edad")) {//Solo pasan los niños con edades
											if ($value >= $edad_tarifa_adult[0]){//Verificamos el rango de edad
												$check_adutlo = true;//Checkeo activado
												$cont_adulta++;//Contador
											}
										}
									}
									if (($check_gratis == false) && ($check_ajustada == false) && ($check_adutlo == false)) { //Si el niño no entró en ninguna condición entonces...
										if (($value != "") && ($value != "Edad")){//Solo pasan los niños con edades
											$check_normal = true;//Checkeo activado
											$cont_normal++;//Contador
										}
									}
								}
								if ($cont_libre != 0) {//Si existe algún niño entonces...
									?>
									<span><?= $cont_libre; ?> Niño(s) libre de tarifas</span><br> <!--Mostramos la tarifa en pantalla-->
									<?php
								}
								if ($cont_ajustada != 0) {//Si existe algún niño entonces...
									if ($niños_tarifa_ajustada[3] == 1){//Verificamos si tiene un precio fijo o en porcentaje.
										?>
										<span><?= $cont_ajustada; ?> Niño(s) con el <?= $niños_tarifa_ajustada[2]; ?>% de la tarifa adulta</span><br><!--Mostramos la tarifa en pantalla-->
										<?php
									}else{
										?>
										<span><?= $cont_ajustada; ?> Niño(s) con tarifa fija de: <? echo $currency->symbol, " ", $niños_tarifa_ajustada[2]; ?></span><br><!--Mostramos la tarifa en pantalla-->
										<?php
									}
								}
								if ($cont_adulta != 0) {//Si existe algún niño entonces...
									?>
									<span><?= $cont_adulta; ?> Niño(s) con tarifa adulta</span><!--Mostramos la tarifa en pantalla-->
									<?php
								}
								if ($cont_normal != 0) {//Si existe algún niño entonces...
									?>
									<span><?= $cont_normal; ?> Niño(s) con tarifa estandar: <? echo $currency->symbol, " ", $rooms[$key]->roomRateDetails[0]->child_price; ?></span><!--Mostramos la tarifa en pantalla-->
									<?php
								}
								?>
								</span>
								<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
								<?php

								//echo"<pre>";var_dump($currency->symbol);echo"</pre>";
									if($room->offer_id  > 0 && $room->offer_max_nights <count($room->daily)){
										echo "<br/>";
										echo JText::_('LNG_EXTRA_NIGHT_BREAKFAST_INCLUDED',true);
										
									}
								?>
						</td>
						<?php
						$showRoomDescription = false;
					}
					?>
					
				<?php
				$totalRoomPrice += $price_day;
				
				if( isset($room->offer_id) && $room->offer_id > 0)
				{
					$nr_days_except_offers++;
				}
			
				if(isset($room->price_type_day) && $room->price_type_day == 1) {
					foreach( $room->daily as $day){
						if($day["isExtraNight"]){
							$totalRoomPrice +=$day["price"];
						}
					}
				}
				?>
				<!--MICOD: Comentado el listado de días que el huesped se va a quedar
				<td align="left" valign="top" style="border-top:solid  1px grey;padding: 3px 9px;" nowrap="nowrap">
						
						<?php
							 if(isset($room->price_type_day) && $room->price_type_day == 1) {
								$nrDays = JHotelUtil::getNumberOfDays($resevation->start_date, $resevation->end_date); 
								//TODO - get nr Days
								echo $nrDays." ".strtolower(JText::_("LNG_NIGHTS"));
							 }else{
								echo JHotelUtil::getDateGeneralFormat($day['date']);
							 }
					
							 echo $info_discount;
						?>
					</td>
					<td align="right" valign="top" style="border-top:solid  1px grey;padding: 3px 9px;">
						&nbsp;
						<?php
						echo JHotelUtil::fmt($showPricePerDay?$price_day:$totalRoomPrice,2);
						?>
					</td>
					-->
				
				
				<?php 
				if(isset($room->price_type_day) && $room->price_type_day == 1) {
					break;
				}
			}
			?>
			<!--micod-->
			<!--Lugar en la cotización donde se muestra la cantidad total solo en cuanto a las habitaciones-->
			<td align="left" valign="top" style="border-top:solid  3px grey;padding: 3px 9px;" nowrap="nowrap">
					</td>
					<td align="right" valign="top" style="border-top:solid  3px grey;padding: 3px 9px;">
					<strong><h4><?php //echo JText::_('LNG_ROOM_SUBTOTAL',true)?>Costo de la Habitación</h4></strong>
						<strong><h4><?php echo $currency->symbol." ".JHotelUtil::fmt($totalRoomPrice,2)?></h4></strong>
					</td>
				</tr>	
				
				
				<tr class='rsv_dtls_subtotal'>
					
					<td colspan=6 align="right">
					<!--MICOD: Agregado precio por persona y por niño por cada habitación cotizado-->
					<!--<span style="float: left; margin-left: 9px;">
								<strong>Precio por persona: </strong><span><? echo $currency->symbol, " ", $rooms[$key]->roomRateDetails[$key]->price ?></span>
								<strong> Precio por niño: </strong><span><? echo $currency->symbol," ", $rooms[$key]->roomRateDetails[$key]->child_price ?></span>
					</span>-->
						<!--MICOD:Comentado.<strong><?php //echo JText::_('LNG_ROOM_SUBTOTAL',true)?> Costo de la Habitación (<?php echo $currency->symbol?>)</strong>-->
					</td>
					<td align=right style="padding: 3px 9px;">
						<!--MICOD:Comentado.<strong><?php echo JHotelUtil::fmt($totalRoomPrice,2)?></strong>-->
					</td>
				</tr>

				<?php
				//micod
				//Lugar donde almaceno los precios de los adultos y los niños para mostrarlos individualemte en la cotización
				$comision = $room->roomRateDetails[0]->price * $room->commission / 100; //calcular comisión
							$total_precio = $room->roomRateDetails[0]->price + $comision; //Sumar comisión

							$_SESSION["adult_price"] = number_format($total_precio,2);

							$comision = $room->roomRateDetails[0]->child_price * $room->commission / 100; //calcular comisión
							$total_precio_niño = $room->roomRateDetails[0]->child_price + $comision; //sumer comisión
							
							$_SESSION["child_price"] = number_format($total_precio_niño,2);

			$roomInfo->name = $room->offer_id >0? $room->offer_name:$room->room_name;
			$roomInfo->roomDescription = ob_get_contents();
			ob_end_clean();
			$roomInfo->roomPrice = $totalRoomPrice;
			$result[] = $roomInfo;
		}
		return $result;
	}
	
	public function getReservationDetailsExcursions($resevation, $excursions, $currency){
		$result = array();
		$nr_days_except_offers	= 0;
		$index = 0;
		
		foreach( $excursions as $excursion )
		{
			$index++;
			$showExcursionDescription = true;
			$excursionInfo = new stdClass();
			$totalExcursionPrice 	= 0;
			$dayCounter = 0;
			$showPricePerDay = true;
			if(isset($excursion->price_type_day) && $excursion->price_type_day == 1) {
				$showPricePerDay = false;
			}
			ob_start();
				
			foreach( $excursion->daily as $day)
			{
				$price_day = $day['price_final'];
				if(isset($excursion->customPrices) && isset($excursion->customPrices[$day["date"]])){
					$price_day = $excursion->customPrices[$day["date"]];
				}
				
				$price_day *= $excursion->nrItemsBooked;
	
				$info_discount	= '';
				$dayCounter ++;
				foreach( $day['discounts'] as $d )
				{
						if( strlen($info_discount)>0)
							$info_discount	.='<BR>';
						$info_discount	.= $d->discount_name.' '.JHotelUtil::fmt(-1 * $d->discount_value).''.($d->percent==1?"%":" ".$currency->symbol);
				}
	
				if( strlen($info_discount)>0){
					$info_discount = "<div class='discount_info'>".$info_discount.'</div>';
				}
					
				?>
					
					<tr class='rsv_dtls_excursion_info'>
						<?php
						if( $showExcursionDescription)
						{
							?>
							<td colspan=5 align="left" valign="top" style="border-top:solid 1px grey;padding: 3px 9px;"	rowspan='<?php echo !$showPricePerDay ? 1:count($excursion->daily)?>'>
								
								<?php if(count($excursions)>1){ ?>
									<strong>#<?php echo $index?></strong>
								<?php }?>
								<?php
											echo '<strong>'.$excursion->excursion_name.'</strong>'.' ('.JText::_('LNG_FOR',true).' '.$excursion->nrItemsBooked.')';
									?>
							</td>
							<?php
							$showExcursionDescription = false;
						}
						?>
						
					<?php
					$totalExcursionPrice += $price_day;
					?>
					
					<td align="left" valign="top" style="border-top:solid  1px grey;padding: 3px 9px;" nowrap="nowrap">
							
							<?php
								 if(isset($excursion->price_type_day) && $excursion->price_type_day == 1) {
									$nrDays = JHotelUtil::getNumberOfDays($resevation->start_date, $resevation->end_date); 
									//TODO - get nr Days
									echo $nrDays." ".strtolower(JText::_("LNG_NIGHTS"));
								 }else{
									echo JHotelUtil::getDateGeneralFormat($day['date']);
								 }
						
								 echo $info_discount;
							?>
						</td>
						<td align="right" valign="top" style="border-top:solid  1px grey;padding: 3px 9px;">
							&nbsp;
							<?php
							echo JHotelUtil::fmt($showPricePerDay?$price_day:$totalExcursionPrice,2);
							?>
						</td>
						
						
					</tr>
					
					<?php 
					if(isset($excursion->price_type_day) && $excursion->price_type_day == 1) {
						break;
					}
				}
				?>
				
					<?php
				
				$excursionInfo->name = $excursion->excursion_name;
				$excursionInfo->excursionDescription = ob_get_contents();
				ob_end_clean();
				$excursionInfo->excursionPrice = $totalExcursionPrice;
				$result[] = $excursionInfo;
			}
			return $result;
	}
	
	public function getReservationCostData($rooms){
		$costData = new stdClass();
		$bIsCostV 	= false;
		$costV		= 0;
		$bIsCostP 	= false;
		$costP		= 0;
		$percent 	= 0;
		foreach($rooms as $room){
			if(	(( $room->offer_id  > 0 && ( $room->offer_reservation_cost_val > 0 || $room->offer_reservation_cost_proc > 0 ) )
				||( $room->offer_id == 0 && ( $room->reservation_cost_val > 0 || $room->reservation_cost_proc > 0 ) )))	{
				
				$bIsCostVi 	= ($room->offer_id  > 0 && $room->offer_reservation_cost_val > 0 ) || ($room->offer_id  == 0 && $room->reservation_cost_val > 0 );
				$costVi		=  $room->offer_id  > 0 ? $room->offer_reservation_cost_val : $room->reservation_cost_val;
				$bIsCostPi 	= ($room->offer_id  > 0 && $room->offer_reservation_cost_proc > 0) || ($room->offer_id  == 0 && $room->reservation_cost_proc > 0 );
				$costPi		= ($room->offer_id  > 0 ? $room->offer_reservation_cost_proc : $room->reservation_cost_proc) ;
				$percent	= ($room->offer_id  > 0 ? $room->offer_reservation_cost_proc : $room->reservation_cost_proc);
					
				if($bIsCostVi && ($costV < $costVi)){
					$bIsCostV = $bIsCostVi;
					$costV = $costVi;
				}
					
				if($bIsCostPi && ($costP < $costPi)){
					$bIsCostP = $bIsCostPi;
					$costP = $costPi;
				}
			}
		}
		$costData->bIsCostV 	= $bIsCostV;
		$costData->costV		= $costV;
		$costData->bIsCostP 	= $bIsCostP;
		$costData->costP		= $costP;
		$costData->percent		= $costP;
		
		return $costData;
	}
	
	public function getReservationDetailsExtraOptions($extraOptions,$extraOptionIds, $nrRooms, $currency){ //Función para anexar en la cotización el contenido EXTRA
		$result = array();

		if( isset($extraOptions) && count($extraOptions) > 0 && count($extraOptionIds)>0 ){
			for($i=1;$i<=$nrRooms;$i++){
				
				$extraOptionsDetails = array();
				$extraOptionInfo = new stdClass();
				$extraOptionsIds = $this->getExtraOptionIds($extraOptionIds,$i);
				$extraOptionsInfos = $this->getExtraOptionInfo($extraOptionIds,$i);
				$extraOptionsAmount	= 0;
						
				if(is_array($extraOptionsIds) && count($extraOptionsIds)>0){
					ob_start();
					?>
					<tr class='rsv_dtls_arrival_options'>
						<td colspan=7 align=left style="padding: 3px 9px">
							<strong><?php echo JText::_('LNG_EXTRAS',true)?></strong>
						</td>
					</tr>
					<?php
					session_start();
					foreach( $extraOptions as $extraOption){
						$child_extra = NULL; //MICOD: Números de niños en los paquetes extras.
						if(!in_array($extraOption->id,$extraOptionsIds) || $extraOption->current!=$i){
							continue;
						}
						
						
						$extrOptionInfo = $extraOptionsInfos[$extraOption->id];
						
						//dmp($extrOptionInfo->child);
						$extraOption->nrPersons= $extrOptionInfo->persons;
						$extraOption->nrDays=$extrOptionInfo->days;
						$extraOption->offerId=$extrOptionInfo->offerId;
						$extraOption->roomId=$extrOptionInfo->roomId;
						$extraOption->current=$extrOptionInfo->current;
						$extraOption->child=$extrOptionInfo->child;
						if (!is_null($extraOption->child)){
							$child_extra = $extraOption->child;
							$check_child_extra = true;
						}else{
							//echo "<script> alert('ALERTA'); </script>";
							$check_child_extra = false;
						}
						
						$amount =$extraOption->price;
						
						if($extraOption->price_type == 1){
							//micod
							//MODIFICACIÓN PARA ANEXAR EN LA COTIZACIÓN EL PASAJE AEREO DE LOS NIÑOS VISTA
							
							$amount_child = $amount;
							$amount_child = $amount_child * 50 / 100;
							if (!is_null($child_extra)){
								$amount = $amount * $extraOption->nrPersons + $amount_child * $child_extra;
							}else{
								$amount = $amount * $extraOption->nrPersons;
							}
							
						}
						if($extraOption->is_per_day == 1 || $extraOption->is_per_day == 2){
							$amount = $amount * $extraOption->nrDays;
						}
						?>
						<tr>
							<td nowrap align=left colspan=6 style="padding: 3px 9px 3px 20px;">
								<?php
									echo $extraOption->name.", ".$currency->symbol." ". JHotelUtil::fmt($extraOption->price,2)." ". ($extraOption->price_type == 1?strtolower(JText::_('LNG_PER_PERSON',true))." ":"" )."".($extraOption->is_per_day == 1 ?strtolower(JText::_('LNG_PER_DAY',true)):"" )."".($extraOption->is_per_day == 2 ?strtolower(JText::_('LNG_PER_NIGHT',true)):"" );
									
									if($extraOption->nrPersons > 0 || $extraOption->nrDays > 0){
										echo "<br/><i>(";
										$showDelimiter = false;
										if($extraOption->nrPersons > 0){
											//echo strtolower(JText::_('LNG_NUMBER_OF_PERSONS',true))." ".$extraOption->nrPersons;
											echo "Número de Adultos ".$extraOption->nrPersons;
											$showDelimiter = true;
										}
										if($child_extra > 0 && $check_child_extra == true){
											echo " - Número de Niños ".$child_extra;
											//$showDelimiter = true;
										}
										
										if($extraOption->nrDays > 0){
											if($showDelimiter){
												echo ", ";
											}
											echo strtolower(($extraOption->is_per_day == 1 ?JText::_('LNG_NUMBER_OF_DAYS',true):JText::_('LNG_NUMBER_OF_NIGHTS',true)))." ".$extraOption->nrDays;
										}
										echo ")</i>";
									}
								?>
							
							</td>	
							<td align=right nowrap style="padding: 3px 9px">
								&nbsp;
								<strong><h4><?php
									//Originalmente:
									echo $currency->symbol." ".JHotelUtil::fmt($amount,2);
								?></h4></strong>
							</td>
		
						</tr>
						<?php
						$extraOptionsDetail = new stdClass();
						$extraOptionsDetail->name = $extraOption->name;
						$extraOptionsDetail->amount = $amount;
						$extraOptionsDetails[] = $extraOptionsDetail;
						$extraOptionsAmount += $amount;
					}
					?>

					<!--</td>	
							<td align=right nowrap style="padding: 3px 9px">
								&nbsp;
								<strong><h3><?php echo $currency->symbol." ".JHotelUtil::fmt($extraOptionsAmount,2)?></h3></strong>
								<?php
									//Originalmente:
									//echo JHotelUtil::fmt($amount,2);
								?>
							</td>
					</tr>-->
					<!--MICOD: Subtotal de las opciones extrar.
					<tr class='rsv_dtls_room_price' bgcolor="#EFEDE9">
						<td colspan="6" style="padding: 3px 0px;"  align="right">
							<strong><?php echo JText::_('LNG_EXTRA_OPTIONS_SUBTOTAL',true)?> (<?php echo $currency->symbol?>)</strong>
						</td>
						<td align="right" style="padding: 3px 9px" >
							<strong><?php echo JHotelUtil::fmt($extraOptionsAmount,2)?></strong>
						</td>
					</tr>-->
					<?php
				
					$extraOptionInfo->details  = $extraOptionsDetails;
					$extraOptionInfo->description = ob_get_contents();
					ob_end_clean();
					$extraOptionInfo->extraOptionsAmount = $extraOptionsAmount;
					$result[$i-1] = $extraOptionInfo;
				}
			}
		}
		
		return $result;
	}
	public function getReservationDetailsAirportTransfer(){
		$val_airport_transfer 	= 0;
		if( count( $modelData->airport_transfer_type_ids ) > 0 )
		{
			// dmp($modelData->itemAirportTransferTypes);
			foreach( $modelData->itemAirportTransferTypes as $keyAirportTransfer => $airport_transfer )
			{
				$eKeyAirportTransfer = explode( '|', $keyAirportTransfer );
				if(
						$exRoomReserved[0] != $eKeyAirportTransfer[0]
						||
						$exRoomReserved[1] != $eKeyAirportTransfer[1]
						||
						$exRoomReserved[2] != $eKeyAirportTransfer[2]
				)
					continue;
		
				$pr_info = ($showDisplayPrice == true?$airport_transfer->airport_transfer_type_display_price: $airport_transfer->airport_transfer_type_price).
				($airport_transfer->airport_transfer_type_vat !=0 ? (" + ".$airport_transfer->airport_transfer_type_vat." %".JText::_('LNG_VAT',true)) : "");
		
				$val_airport_transfer = ($showDisplayPrice == true?$airport_transfer->airport_transfer_type_display_price: $airport_transfer->airport_transfer_type_price);
				if( $airport_transfer->airport_transfer_type_vat > 0 )
					$val_airport_transfer += ($val_airport_transfer * $airport_transfer->airport_transfer_type_vat / 100);
				?>
				<tr class='tr_airport_transfer_title'>
					<td nowrap colspan=7 align=left style="padding: 3px 9px;">
						<strong><?php echo JText::_('LNG_AIRPORT_TRANSFER',true) ?></strong>
					</td>
				</tr>
				<tr >	
					<td colspan=5 align=left style="padding: 3px 9px 3px 20px;">
						<?php echo $airport_transfer->airport_transfer_type_name?>						
					</td>
					<td>
						<?php echo $pr_info?>
					</td>
					<td align=right style="padding: 3px 9px;" >
						<?php echo JHotelUtil::fmt($val_airport_transfer)?>
					</td>

				</tr>
				<tr>
					<td colspan=7 align=left style="padding: 3px 9px 3px 40px;">
						<table class='table_airport_transfer' cellpadding=0 cellspacing=0 width=100%>
							<tr>
								<td nowrap ><?php echo JText::_('LNG_AIRLINE',true)?> :&nbsp;</td>
								<td  colspan=3>
									<?php 
									foreach( $modelData->itemArrivalAirlines as  $keyAirline => $valueAirline )
									{
										$exKeyAirline = explode( '|', $keyAirline );
										if( 
											$exRoomReserved[0] != $exKeyAirline[0]
											||
											$exRoomReserved[1] != $exKeyAirline[1]
											||
											$exRoomReserved[2] != $exKeyAirline[2]
										)
											continue;
										echo $valueAirline->airline_name;
									}
									?>
								</td>
							</tr>
							<tr>
								<td width=15% nowrap><?php echo JText::_('LNG_FLIGHT_NR',true)?> :</td>
								<td width=35%>
									<?php 
									foreach( $modelData->airport_transfer_flight_nrs as  $keyTransferFlightNr => $valueTransferFlightNr )
									{
										if( 
											$exRoomReserved[0] != $valueTransferFlightNr[0]
											||
											$exRoomReserved[1] != $valueTransferFlightNr[1]
											||
											$exRoomReserved[2] != $valueTransferFlightNr[2]
										)
											continue;
										echo $valueTransferFlightNr[3];
									}
									?>
								</td>
								<td width=15% nowrap><?php echo JText::_('LNG_GUEST',true)?> :</td>
								<td width=35%>
									<?php 
									foreach( $modelData->airport_transfer_guests as  $keyTransferGuest => $valueTransferGuest )
									{
										if( 
											$exRoomReserved[0] != $valueTransferGuest[0]
											||
											$exRoomReserved[1] != $valueTransferGuest[1]
											||
											$exRoomReserved[2] != $valueTransferGuest[2]
										)
											continue;
										echo $valueTransferGuest[3];
									}
									?>
								</td>
							</tr>
							<tr>
								<td width=10% nowrap><?php echo JText::_('LNG_DATE',true)?> :</td>
								<td width=35%>
									<?php 
									foreach( $modelData->airport_transfer_dates as  $keyTransferDate => $valueTransferDate )
									{
										if( 
											$exRoomReserved[0] != $valueTransferDate[0]
											||
											$exRoomReserved[1] != $valueTransferDate[1]
											||
											$exRoomReserved[2] != $valueTransferDate[2]
										)
											continue;
										echo $valueTransferDate[3];
									}
									?>
								</td>
								<td width=15%><?php echo JText::_('LNG_TIME',true)?> :</td>
								<td width=35%>
									<?php 
									foreach( $modelData->airport_transfer_time_hours as  $keyTransferTimeHour => $valueTransferTimeHour )
									{
										if( 
											$exRoomReserved[0] != $valueTransferTimeHour[0]
											||
											$exRoomReserved[1] != $valueTransferTimeHour[1]
											||
											$exRoomReserved[2] != $valueTransferTimeHour[2]
										)
											continue;
										echo $valueTransferTimeHour[3];
									}
									echo ":";
									foreach( $modelData->airport_transfer_time_mins as  $keyTransferTimeMin => $valueTransferTimeMin )
									{
										if( 
											$exRoomReserved[0] != $valueTransferTimeMin[0]
											||
											$exRoomReserved[1] != $valueTransferTimeMin[1]
											||
											$exRoomReserved[2] != $valueTransferTimeMin[2]
										)
											continue;
										echo $valueTransferTimeMin[3];
									}
									?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<!--
				<tr class='rsv_dtls_room_price' bgcolor="#EFEDE9">
					<td colspan="6" style="padding: 3px 9px;"  align="right">
						<strong><?php echo JText::_('LNG_AIRPORT_TRANSFER_SUBTOTAL',true)?>(<?php echo $currency->name?>)</strong>
					</td>
	
					<td align="right" style="padding: 3px 9px" >
						<strong><?php echo JHotelUtil::fmt($val_airport_transfer,2)?></strong>
					</td>

				</tr>
				-->
				<?php
			}
		}						
	}
	
	function getBillingInformation($data, $hideEmail = false)
	{
		//micod
		//éste tag quedará inhabilitado, por el momento no se está usando (tag que imprime el tipo de persona 'Mr','Ms','Mrs', etc)
		//$gender = JText::_("LNG_ADDRESS_GUEST_TYPE_".$data->guest_type,true);
		ob_start();
		?>
			<?php echo !empty($data->company_name)? $data->company_name."<br/>":"" ?>
			<?php echo  $gender.' '.$data->first_name.' '.$data->last_name?> <br/>
			<?php echo $data->address?><br/>							
			<?php echo $data->postal_code ." " ?>	<?php echo $data->city?><br/>
			<?php echo $data->country?><br/>
			T: <?php echo $data->phone?><br/>
			<?php if(!$hideEmail){ ?><a href='mailto:<?php echo $data->email?>'><?php echo $data->email?></a><br/><br/>	<?php } ?>
			<?php
			$buff = ob_get_contents();
			ob_end_clean(); 
	
			return $buff;
	}
	
	function getPaymentInformation($paymentDetails, $amount, $cost){
		$processor = PaymentService::createPaymentProcessor($paymentDetails->processor_type);
		ob_start();
	
		echo "<ul style='margin:0px;padding-left: 0;list-style:none'>";
		echo "<li style='margin-left: 0px'>";
		echo $processor->getPaymentDetails($paymentDetails, $amount, $cost);
		echo "</li>";
		echo "</ul>";
		
		$buff = ob_get_contents();
		ob_end_clean(); 
		
		return $buff;
	}
	
	static function getClientReservations($userId = null){
		if(!isset($userId))
			return null;
	
		$confirmationTable = JTable::getInstance('Confirmations','Table', array());
		$reservations = $confirmationTable->getClientReservations($userId);
		
		return $reservations;
	}
	
}

?>