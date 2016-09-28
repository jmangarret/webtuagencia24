 <?php defined('_JEXEC') or die('Restricted access');
$states = 	  Array(1 => JText::_('ORDER.STATUS.1'),
					2=>  JText::_('ORDER.STATUS.2'),
					3 => JText::_('ORDER.STATUS.3'),
					4 => JText::_('ORDER.STATUS.4'),
					5 => JText::_('ORDER.STATUS.5'),
					6 => JText::_('ORDER.STATUS.6')

);
//Informacion Adicional 
$xmlstr=$this->XMLAdd;
$data = new SimpleXMLElement($xmlstr);
$passenger=$data->AdditionalInfo->Travelers;


//Informacion del Booking
 $xml = new SimpleXMLElement($this->XMLData);
 //$intinerary= $xml->AirReservation->OriginDestinationOptions->OriginDestinationOption->FlightSegment;
 $intinerary= $xml->AirReservation->OriginDestinationOptions->OriginDestinationOption;
 
 //print_R($intinerary); 
 
 $impuestos=$xml->AirReservation->PriceInfo->PTC_FareBreakdowns;
 ?>
 
	<div class="resume-flight">
	
		<h3 class="title">
		<?php echo JText::_("ORDERS.ORDER.TITLE.RESUM"); ?>
		</h3><div class="resume-detail">
		<div class="fare">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.RECORD.NUM"); ?>:</span>
		<span class="value"><?php echo date('H:i',$this->Order->recloc);?></span>
		
			</div><div class="clear"></div><div class="fare">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.STATE.RECORD"); ?>:</span>
		<span class="value"><?php echo $states[$this->Order->status];?> </span>
		<div class="clear"></div>
			</div>
	</div> 
	<h2><?php echo JText::_("ORDERS.ORDER.PAX.DETAIL");?></h2>
		<?php $i=1; foreach ($passenger->TravelerInfo as $pax): ?>
		<div class="fare_taxes">
		<h2><?php echo JText::_("ORDERS.ORDER.PAX.NUM"); echo " ".$i." ";?></h2>
	
			<div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.NAME");?>:</span>
				<span class="value"><?php echo $pax->GivenName?></span>
				<div class="clear"></div>
			</div>
			<div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.LASTNAME");?>:</span>
				<span class="value"><?php echo $pax->Surname?></span>
				<div class="clear"></div>
			</div>
			<div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.TYPE");?>:</span>
				<span class="value"><?php echo JText::_("ORDERS.ORDER.PAX.".$pax->PassengerType); ?></span>
				<div class="clear"></div>
			</div>
			<?php if($pax->PassengerType=='ADT' || $pax->PassengerType=='YCD' ): ?>
			<div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.DOCUMENT.TYPE");?>:</span>
				<span class="value"><?php  echo JText::_("ORDERS.ORDER.PAX.DOC.TYPE.".$pax->DocumentTypeId);?></span>
			<div class="clear"></div>
			</div>
			<?php endif;?>
			<?php if($pax->PassengerType=='CHD' || $pax->PassengerType=='INF'): ?>
			<div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.DOCUMENT.TYPE");?>:</span>
				<span class="value"><?php  echo JText::_("ORDERS.ORDER.PAX.DOC.TYPEDIF.".$pax->DocumentTypeId);?></span>
			<div class="clear"></div>
			</div>
			<?php endif;?>
			<div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.DOCUMENT.NUMBER");?>:</span>
				<span class="value"><?php echo $pax->DocumentNumber?></span>
			<div class="clear"></div>
			</div>
			<div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.BORN");?>:</span>
				<span class="value"><?php echo $pax->BithDate?></span>
			<div class="clear"></div>
			</div>
			<div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.GENDER");?>:</span>
				<span class="value"><?php echo JText::_("ORDERS.ORDER.PAX.GENDER.".$pax->Gender);?></span>
			<div class="clear"></div>
			</div>
			<?php if($pax->PassengerType=='ADT'): ?>
			<div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.EMAIL");?>:</span>
				<span class="value"><?php echo $pax->Email?></span>
			<div class="clear"></div>
			</div>
			 <?php endif;?>
	 		<h4><?php  echo JText::_("ORDERS.ORDER.PAX.VFREQ");?></h4>
			 
			<div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.VFREQ.NUMBER");?>:</span>
				<span class="value"><?php echo $pax->FrequentFlyerNumber?></span>
			<div class="clear"></div>
			</div>
			<div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.VFREQ.AIRLINE");?>:</span>
				<span class="value"><?php $proc= $this->getAirlines($pax->FrequentFlyerAirline); print_r($proc[0]->nombre);  ?></span>
			<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		 
	<?php $i++; endforeach;?>
 
		
		<h2><?php echo JText::_("ORDERS.ORDER.AIR.DETAIL"); ?></h2>
		<?php 
		 foreach($intinerary as $vuelo):
		 
		 if(count($vuelo) > 1){
			
			foreach($vuelo as $vuelo1){
			
				 $departure=str_replace("T", " ",$vuelo1['DepartureDateTime']);
				 $arrival=str_replace("T", " ",$vuelo1['ArrivalDateTime']);
				 $airportDep=explode(':',$vuelo1->Comment[0]);
				 $cityDep=explode(':',$vuelo1->Comment[2]);
				 $airportArr=explode(':',$vuelo1->Comment[7]);
				 $cityArr=explode(':',$vuelo1->Comment[9]);
		?>
		
		<div>
			<div class="fare_taxes">
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.DATE.DEPARTURE"); ?>:</span>
				<span class="value"><?php echo date("d/m/Y h:i:s A", strtotime($departure));?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.DATE.ARRIVAL"); ?>:</span>
				<span class="value"><?php echo date("d/m/Y h:i:s A", strtotime($arrival));?></span>
				<div class="clear"></div>
				</span> 
				 <span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.PAX.VFREQ.AIRLINE"); ?>:</span>
				<span class="value"><?php echo $vuelo1->OperatingAirline['CompanyShortName'] ?></span>
				<div class="clear"></div>
				</span> 
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.NUMBER.FLY"); ?>:</span>
				<span class="value"><?php echo $vuelo1['FlightNumber'] ?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.DEPARTURE.CITY"); ?>:</span>
				<span class="value"><?php echo $cityDep[1] ?></span>
				<div class="clear"></div>
				</span>
			    <span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.DEPARTURE.AIRPORT"); ?>:</span>
				<span class="value"><?php echo $airportDep[1] ?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.ARRIVAL.CITY"); ?>:</span>
				<span class="value"><?php echo $cityArr[1]  ?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.ARRIVAL.AIRPORT"); ?>:</span>
				<span class="value"><?php echo $airportArr[1] ?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.STOPS"); ?>:</span>
				<span class="value"><?php echo $vuelo1['StopQuantity'] ?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.CABINE"); ?>:</span>
				<span class="value"><?php echo $vuelo1->BookingClassAvails['CabinType'] ?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.TYPE.AIRPLANE"); ?>:</span>
				<span class="value"><?php echo $vuelo1->Equipment ?></span>
				<div class="clear"></div>
				</span>
				<div class="clear"></div>
			</div>
		</div>
		
		<?php
			
			}
			
		 }
		 else{
		 
		 $departure=str_replace("T", " ",$vuelo->FlightSegment['DepartureDateTime']);
		 $arrival=str_replace("T", " ",$vuelo->FlightSegment['ArrivalDateTime']);
		 $airportDep=explode(':',$vuelo->FlightSegment->Comment[0]);
		 $cityDep=explode(':',$vuelo->FlightSegment->Comment[2]);
		 $airportArr=explode(':',$vuelo->FlightSegment->Comment[7]);
		 $cityArr=explode(':',$vuelo->FlightSegment->Comment[9]);
	 
		 ?>
		<div>
			<div class="fare_taxes">
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.DATE.DEPARTURE"); ?>:</span>
				<span class="value"><?php echo date("d/m/Y h:i:s A", strtotime($departure));?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.DATE.ARRIVAL"); ?>:</span>
				<span class="value"><?php echo date("d/m/Y h:i:s A", strtotime($arrival));?></span>
				<div class="clear"></div>
				</span> 
				 <span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.PAX.VFREQ.AIRLINE"); ?>:</span>
				<span class="value"><?php echo $vuelo->FlightSegment->OperatingAirline['CompanyShortName'] ?></span>
				<div class="clear"></div>
				</span> 
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.NUMBER.FLY"); ?>:</span>
				<span class="value"><?php echo $vuelo->FlightSegment['FlightNumber'] ?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.DEPARTURE.CITY"); ?>:</span>
				<span class="value"><?php echo $cityDep[1] ?></span>
				<div class="clear"></div>
				</span>
			    <span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.DEPARTURE.AIRPORT"); ?>:</span>
				<span class="value"><?php echo $airportDep[1] ?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.ARRIVAL.CITY"); ?>:</span>
				<span class="value"><?php echo $cityArr[1]  ?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.ARRIVAL.AIRPORT"); ?>:</span>
				<span class="value"><?php echo $airportArr[1] ?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.STOPS"); ?>:</span>
				<span class="value"><?php echo $vuelo->FlightSegment['StopQuantity'] ?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.CABINE"); ?>:</span>
				<span class="value"><?php echo $vuelo->FlightSegment->BookingClassAvails['CabinType'] ?></span>
				<div class="clear"></div>
				</span>
				<span class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.TYPE.AIRPLANE"); ?>:</span>
				<span class="value"><?php echo $vuelo->FlightSegment->Equipment ?></span>
				<div class="clear"></div>
				</span>
				<div class="clear"></div>
			</div>
		</div>
		 
		<?php 
		}
		endforeach;
		?>
		
 <h2><?php echo JText::_("ORDERS.ORDER.TITLE.PRICE.PAX"); ?></h2>
		
		<div class="fare_taxes">
		<?php foreach ($impuestos->PTC_FareBreakdown   as $impuesto) : 
		 ?>
			<div class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.PRICE.NETO.PAX")." ".JText::_("ORDERS.ORDER.PAX.".$impuesto->PassengerTypeQuantityType['Code'])." (x".(int)$impuesto->PassengerTypeQuantityType['Quantity'].")"; ?>:</span>
				
				<span class="value"><?php echo JText::_("ORDERS.ORDER.COIN");?><?php $cant=(double)$impuesto->PassengerFare->BaseFare['Amount']*(int)$impuesto->PassengerTypeQuantityType['Quantity']; echo number_format($cant,2, ',', '.');?></span>
			<div class="clear"></div>
			</div>
			
			<?php 
		 $i=0; 
		 foreach($impuesto->PassengerFare->Taxes->Tax as $taxes):
		?>
		<div class="fare">
		<span class="label"><?php echo  $taxes[$i][TaxCode]  ?>:</span>
		<span class="value"><?php echo JText::_("ORDERS.ORDER.COIN");?><?php $val=  (double)$taxes[$i][Amount]*(int)$impuesto->PassengerTypeQuantityType['Quantity'];  echo number_format($val ,2, ',', '.');?> </span>
		</div><div class="clear"></div>
		<?php $i++; endforeach;?>
		<?php  endforeach;?>
	 
			
		 <div class="clear"></div>
		</div>
		 
	
		<div>
	</div>
	</div>

	<div class="clear"></div>


 

<div class="clear"></div>
 








 