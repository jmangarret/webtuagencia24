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
 $intinerary= $xml->AirReservation->OriginDestinationOptions->OriginDestinationOption;
?>
<script type="text/javascript">
function ventanaNueva(documento){	
	window.open(documento,'nuevaVentana','width=800, height=400');
}
</script>
<div id="resume-order">
	<div class="resume-payment">
		 
		<?php   echo $this->Articulo[introtext];	?>
	 
		<br></br>
		
		<p align="left">
			<a rel="{handler: 'iframe', size: {x: 570, y: 450}}"
				href="<?php echo JRoute::_("index.php?option=com_asom&task=orders.detail&order=".$this->Order->id) ?>"
				class="modal"> <?php echo JText::_("ORDERS.ORDER.INFORMACION.RESERVA.LABEL") ?>
			</a>
		</p>

		<br></br> <input type="button"
			value="<?php echo JText::_('ORDERS.ORDER.END.BUTTON.LABEL'); ?>"
			id="button" class="button_next" name="button"
			onclick="location.href='../../index.php'" /> <input
			type="button" value="<?php echo JText::_("PRINT"); ?>" id="button"
			class="button_next" name="button" 
			onclick="ventanaNueva('<?php echo JRoute::_("index.php?option=com_asom&view=products&task=orders.displayPdfProduct&idprod=".$this->Order->product_type."&idorder=".base64_encode($this->Order->id."OM")); ?> ')" />
	</div>

	<div class="resume-flight new-resume">
	<div class="resume-detail">
		<h3 class="title">
		<?php echo JText::_("ORDERS.ORDER.TITLE"); ?>
		</h3>
		<div class="fare">
			<span class="label"><?php echo JText::_("ORDERS.ORDER.RECORD"); ?>:</span>
			<span class="value"><?php echo $this->Order->recloc;?> </span>
		</div>
		<div class="fare">
			<span class="label"><?php echo JText::_("ORDERS.ORDER.NUMBER"); ?>:</span>
			<span class="value"><?php echo $this->Order->id;?> </span>
		</div>
		<div class="fare">
			<span class="label"><?php echo JText::_("ORDERS.ORDER.STATE"); ?>:</span>
			<span class="value"><?php echo $states[$this->Order->status];?> </span>
		</div>
	</div>
	 
		<h2><?php echo JText::_("ORDERS.ORDER.TITLE.PRICE"); ?></h2>
		
		<div class="fare_taxes">
			<div class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.PRICE.NETO"); ?>:</span>
				<span class="value"><?php echo JText::_("ORDERS.ORDER.COIN");?><?php echo number_format($this->Order->fare,2, ',', '.');?></span>
				
			</div><div class="clear"></div>
			<div class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.PRICE.IMP"); ?> :</span>
				<span class="value"><?php echo JText::_("ORDERS.ORDER.COIN");?><?php echo number_format($this->Order->taxes,2, ',', '.');?></span>
			
			</div><div class="clear"></div>
			<div class="fee">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.PRICE.CHR"); ?> :</span>
				<span class="value"><?php echo JText::_("ORDERS.ORDER.COIN");?><?php echo number_format($this->Order->fare_ta+$this->Order->taxes_ta ,2, ',', '.');?></span>
			</div>
		</div>
		<div class="total">
			<span class="value"><?php echo JText::_("ORDERS.ORDER.COIN");?><?php echo number_format($this->Order->total,2, ',', '.');?></span>
		</div>
		 <div class="clear"></div>
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
			 $infoDep= $airportDep[1]." - ".$cityDep[1];
			 $infoArr= $airportArr[1]." - ".$cityArr[1];
		?>
		<div>
			<div class="resume-detail">
		 	<div class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.DATE.DEPARTURE"); ?>:</span>
				<span class="value"><?php echo  date("d/m/Y h:i A", strtotime($departure));?></span>
			</div>	
			<div class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.DATE.ARRIVAL"); ?>:</span>
				<span class="value"><?php echo  date("d/m/Y h:i A", strtotime($arrival));?></span>
			</div>	
			<div class="fare">	 
				<span class="label"><?php echo JText::_("ORDERS.ORDER.AIRPORT.DEPARTURE"); ?>:</span>
				<span class="value"><?php echo $infoDep?></span>
			</div>	
			<div class="fare">		 
				<span class="label"><?php echo JText::_("ORDERS.ORDER.AIRPORT.ARRIVAL"); ?>:</span>
				<span class="value"><?php echo $infoArr?></span>
			</div>
			</div>
		</div>
		<br>
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
		 $infoDep= $airportDep[1]." - ".$cityDep[1];
		 $infoArr= $airportArr[1]." - ".$cityArr[1];
		 ?>
		<div>
			<div class="resume-detail">
		 	<div class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.DATE.DEPARTURE"); ?>:</span>
				<span class="value"><?php echo  date("d/m/Y h:i A", strtotime($departure));?></span>
			</div>	
			<div class="fare">
				<span class="label"><?php echo JText::_("ORDERS.ORDER.DATE.ARRIVAL"); ?>:</span>
				<span class="value"><?php echo  date("d/m/Y h:i A", strtotime($arrival));?></span>
			</div>	
			<div class="fare">	 
				<span class="label"><?php echo JText::_("ORDERS.ORDER.AIRPORT.DEPARTURE"); ?>:</span>
				<span class="value"><?php echo $infoDep?></span>
			</div>	
			<div class="fare">		 
				<span class="label"><?php echo JText::_("ORDERS.ORDER.AIRPORT.ARRIVAL"); ?>:</span>
				<span class="value"><?php echo $infoArr?></span>
			</div>
			</div>
		</div>
		 <br>
		<?php 
		}
		endforeach;
		?>
<br>
<div class="clear"></div>
 <hr></hr>
	<h2><?php echo JText::_("ORDERS.ORDER.PAX.DETAIL");?></h2>
		<?php $i=1;foreach ($passenger->TravelerInfo as $pax): ?>
		<div>
		<h2><?php echo JText::_("ORDERS.ORDER.PAX.NUM"); echo " ".$i." ";?></h2>
		<div class="resume-detail">
		<div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.NAME");?>:</span>
				<span class="value"><?php echo $pax->GivenName?></span></div>
				<div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.LASTNAME");?>:</span>
				<span class="value"><?php echo $pax->Surname?></span></div>
				<div class="fare"><span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.TYPE");?>:</span>
				<span class="value"><?php echo JText::_("ORDERS.ORDER.PAX.".$pax->PassengerType); ?></span>
		 		</div>
			<?php if($pax->PassengerType=='ADT' || $pax->PassengerType=='YCD' ): ?>
		 <div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.DOCUMENT.TYPE");?>:</span>
				<span class="value"><?php  echo JText::_("ORDERS.ORDER.PAX.DOC.TYPE.".$pax->DocumentTypeId);?></span>
		 </div>
			<?php endif;?>
			<?php if($pax->PassengerType=='CHD' || $pax->PassengerType=='INF'): ?>
	 <div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.DOCUMENT.TYPE");?>:</span>
				<span class="value"><?php  echo JText::_("ORDERS.ORDER.PAX.DOC.TYPEDIF.".$pax->DocumentTypeId);?></span>
		 </div>
			<?php endif;?>
			 <div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.DOCUMENT.NUMBER");?>:</span>
				<span class="value"><?php echo $pax->DocumentNumber?></span>
			 </div><div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.BORN");?>:</span>
				<span class="value"><?php echo $pax->BithDate?></span>
			 </div><div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.GENDER");?>:</span>
				<span class="value"><?php echo JText::_("ORDERS.ORDER.PAX.GENDER.".$pax->Gender);?></span>
		 </div>
			<?php if($pax->PassengerType=='ADT'): ?>
		 <div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.EMAIL");?>:</span>
				<span class="value"><?php echo $pax->Email?></span>
		 </div>
			 <?php endif;?>
			 <div class="clear"></div>
	 		<h4><?php  echo JText::_("ORDERS.ORDER.PAX.VFREQ");?></h4>
			 <div class="fare">
			 
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.VFREQ.NUMBER");?>:</span>
				<span class="value"><?php echo $pax->FrequentFlyerNumber?></span>
		 </div><div class="fare">
				<span class="label"><?php  echo JText::_("ORDERS.ORDER.PAX.VFREQ.AIRLINE");?>:</span>
				<span class="value"><?php echo $pax->FrequentFlyerAirline?></span></div>
		 </div>
		</div>
		 <div class="clear"></div>
	<?php $i++; endforeach;?>
	 
	</div>

	<div class="clear"></div>

</div>

 

<br class="salto">
 








