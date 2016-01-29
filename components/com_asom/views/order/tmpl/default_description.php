 <?php defined('_JEXEC') or die('Restricted access');
 
$states = 	  Array(1 => JText::_('ORDER.STATUS.1'),
					2=>  JText::_('ORDER.STATUS.2'),
					3 => JText::_('ORDER.STATUS.3'),
					4 => JText::_('ORDER.STATUS.4'),
					5 => JText::_('ORDER.STATUS.5'),
					6 => JText::_('ORDER.STATUS.6')

);
 
$typeProduct = Array(1 => JText::_('ORDER.FILTER.AIR'),
					3=> JText::_('ORDER.FILTER.PLAN'),
					7 => JText::_('ORDER.FILTER.CRUISE'),
					2 => JText::_('ORDER.FILTER.HOTEL')

);
$invoType= Array(1 => JText::_('ORDER.COMPANY'),
				0=> JText::_('ORDER.PERSON')

);

$typeS= Array(1 => JText::_('ORDERS.ORDER.INVOICE.TYPE.E'),
				0=> JText::_('ORDERS.ORDER.INVOICE.TYPE.C')
);
//Informacion Adicional 
$xmlstr=$this->XMLAdd;
$data = new SimpleXMLElement($xmlstr);
$passenger=$data->AdditionalInfo->Travelers;
 
//Informacion del Booking
 $xml = new SimpleXMLElement($this->XMLData);
 $intinerary= $xml->AirReservation->OriginDestinationOptions->OriginDestinationOption->FlightSegment;
 
$proveedor = $xml->AirReservation->Ticketing->TicketingVendor;
 
 $impuestos=$xml->AirReservation->PriceInfo->PTC_FareBreakdowns;
 
 ?>
 <script language="JavaScript">
function muestra_oculta(id){
if (document.getElementById){ //se obtiene el id
var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
}
}
window.onload = function(){/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
muestra_oculta('resume-flights');/* "contenido_a_mostrar" es el nombre que le dimos al DIV */
}
</script>
<div >
	<div >
	<div class="resume-detail">
		<h3 class="title">
		<?php echo JText::_("ORDERS.ORDER.DETAIL"); ?>
		</h3>
		<h2>
		<?php echo JText::_("ORDERS.ORDER.GENERAL.INFORMATION"); ?>
		</h2>
		<div class="generalInformation">
		<div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.NUMBER"); ?>:</span>
		<span class="value"><?php echo $this->Order->id;?> </span>
		</div><div class="clear"></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.NAME.LAST"); ?>:</span>
		<span class="value"><?php echo $this->Order->firstname;?> </span>
		</div><div class="clear"></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.PAX.EMAIL"); ?>:</span>
		<span class="value"><?php echo $this->Order->email;?> </span>
		</div><div class="clear"></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.STATE.ACTUAL"); ?>:</span>
		<span class="value"><?php echo $states[$this->Order->status];?> </span>
		</div>
		<div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.TOTAL.VALUE"); ?>:</span>
		<span class="value"><?php echo JText::_("ORDERS.ORDER.COIN");?><?php echo number_format($this->Order->total,'2',',','.');?> </span>
		</div><div class="clear"></div>
		</div>
	</div>
	 
	 <div class="resume-detail">
	 	<h2>
		<?php echo JText::_("ORDERS.ORDER.TITLE"); ?>
		</h2>
		<div class="bookInformation">
		<div class="taxes">
		<span class="label"><?php echo JText::_("AOM_PRODUCT.TYPE"); ?>:</span>
		<span class="value"><?php echo $typeProduct[$this->Order->product_type]?> </span></div>
		<div class="taxes">
		<span class="label"><?php echo JText::_("AOM_PRODUCT_NAME"); ?>:</span>
		<span class="value"><?php echo $this->Order->product_name?> </span>
		</div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.RECORD"); ?>:</span>
		<span class="value"><?php echo $this->Order->recloc;?> </span></div>
		<div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.SUPPLIER.CODE"); ?>:</span>
		<span class="value"><?php echo $proveedor['Code']?> </span></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.SUPPLIER.NAME"); ?>:</span>
		<span class="value"><?php $proc= $this->getAirlines($proveedor['Code']); print_r($proc[0]->nombre); ?> </span></div>
		<span class="label"> <a   onclick="muestra_oculta('resume-flights')"> <?php echo JText::_("ORDERS.ORDER.TITLE.RESUM") ?>
			</a></span>
		</div>
		
	 </div>
	  
	 
	  <div class="resume-detail">
	 	<h2>
		<?php echo JText::_("ORDERS.ORDER.PRICE"); ?>
		</h2>
		<div class="rateDetail">
		<?php 
		 $values = $this->transformArrayValues($this->getValues());
		?>
	 <?php
      $k = 0;
      $sum = array();
      foreach($values->values as $name => $value)
      {
    ?>
   <div class="taxes">
      <span class="label"> <?php echo JText::_($name); ?>:</span>
      <?php
      $total = array();
      foreach($values->passengers as $type => $number)
      {
        $total[$type] += $number * (float)$value[$type];
        $sum[$type] += $number * (float)$value[$type];
      ?>
      <?php
      }
      ?>
    
     <span class="value"><?php echo JText::_("ORDERS.ORDER.COIN");?><?php $sum['total'] += array_sum($total); 
     echo number_format(array_sum($total), 2, ',', '.'); ?></span> 
   </div>
    <?php
        $k = 1 - $k;
      }
    ?>
	</div>
	</div>
	 
	<?php  if((int)$data->AdditionalInfo->TypeRequest==0):?>
	<div class="resume-detail">
	<h2>
		<?php echo JText::_("ORDERS.ORDER.PAYMENT.INFO"); ?>
		</h2>  
	<div class="generalInformation">
	 	
		<?php  if((int)$data->AdditionalInfo->PaymentTypeId==0 && (int)$data->AdditionalInfo->TypeRequest==0):?>
		<div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.TYPE.CARD"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->CreditCardType ; ?> </span>
		</div><div class="clear"></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.CARD.NUMBER"); ?>:</span>
		<span class="value"><?php  $cta=base64_decode($data->AdditionalInfo->CreditCardNumber);  echo substr($cta, -3);?> </span>
		</div><div class="clear"></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.DATE.CARD"); ?>:</span>
		<span class="value"><?php echo base64_decode($data->AdditionalInfo->CreditCardExpiration);   ?> </span>
		</div><div class="clear"></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.SECURY.CARD"); ?>:</span>
		<span class="value"><?php echo base64_decode($data->AdditionalInfo->CreditCardSecurityCode);   ?> </span>
		</div><div class="clear"></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.NAME.CARD"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->CreditCardOwner   ?> </span>
		</div><div class="clear"></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDER.ORDER.NAME.ID"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->CreditCardDocumentNumber   ?> </span> 
		</div><div class="clear"></div>
		<?php endif;?><div class="taxes">
		<?php  if((int)$data->AdditionalInfo->PaymentTypeId==1 && (int)$data->AdditionalInfo->TypeRequest==0):?>
		<div><span class="label"><?php echo JText::_("ORDER.ORDER.BANK"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->Bank ?> </span> 
		</div><div>
		<span class="label"><?php echo JText::_("ORDER.ORDER.ACOUNT"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->Acount  ?> </span> 
		</div><div>
		<span class="label"><?php echo JText::_("ORDER.ORDER.CODE.TYPEACOUNT"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->TypeAcount  ?> </span> 
		</div>
		<div>
		<span class="label"><?php echo JText::_("ORDER.ORDER.CODE.TRANSFER"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->Tranference  ?> </span> 
		</div>
		<?php endif;?>
</div>
	</div>
		 
		<div class="clear"></div>
	<div class="resume-detail">
		<h2>
		<?php echo JText::_("ORDERS.ORDER.INVOICE.DATA"); ?></h2>
			<div class="generalInformation">
		<div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.INVOICE.DATA.NAME"); ?>:</span>
		<span class="value"><?php echo $invoType[trim($data->AdditionalInfo->InvoiceTo)]   ?> </span> 
		</div><div class="clear"></div>
		<?php if($data->AdditionalInfo->InvoiceTo!=0):?>
		<div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.INVOICE.DATA.RIF"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->InvoiceDocumentNumber   ?> </span> 
		</div><div class="clear"></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.INVOICE.DATA.COMPANY"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->InvoiceCustomerName   ?> </span> 
		</div><div class="clear"></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.INVOICE.DATA.LOCATION"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->InvoiceLocation   ?> </span> 
	  </div>
	  <?php endif;?>
	  </div>
	</div>
			 
			<div class="clear"></div>
	<div class="resume-detail">
		<h2>
		<?php echo JText::_("ORDERS.ORDER.INVOICE.SEND"); ?></h2>
			<div class="generalInformation">
		<div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.INVOICE.SEND.CITY"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->InvoiceCity    ?> </span> 
		</div><div class="clear"></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.INVOICE.SEND.LOCATION"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->InvoiceUrbanization   ?> </span> 
		</div><div class="clear"></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.INVOICE.STREET"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->InvoiceAvenue   ?> </span> 
		</div><div class="clear"></div><div class="taxes">
		<span class="label"><?php echo JText::_("ORDERS.ORDER.INVOICE.TYPE"); ?>:</span>
		<span class="value"><?php echo $typeS[trim($data->AdditionalInfo->InvoiceBuilding)]    ?> </span>
		</div><div class="clear"></div><div class="taxes"> 
	  	<span class="label"><?php echo JText::_("ORDERS.ORDER.INVOICE.ZIP"); ?>:</span>
		<span class="value"><?php echo $data->AdditionalInfo->InovicePostalCode ?> </span> 
		</div>
		 </div>	
	</div><?php endif;?>
<div class="clear"></div>	
	<div class="resume-detail">
		<h2>
		<?php echo JText::_("ORDERS.ORDER.HISTORY"); ?></h2>
		<table width="95%" border="1" cellpadding="4"  cellspacing="0" bordercolor="#004488">
		<tr>
		<th style="background-color:#004488"><?php echo JText::_("ORDERS.ORDER.HISTORY.USER"); ?></th>
		<th style="background-color:#004488"><?php echo JText::_("ORDERS.ORDER.HISTORY.OBSER"); ?></th>
		<th style="background-color:#004488"><?php echo JText::_("ORDERS.ORDER.HISTORY.STATE"); ?></th>
		<th style="background-color:#004488"><?php echo JText::_("ORDERS.ORDER.HISTORY.DATE"); ?></th>
		</tr> 
		<?php
		foreach($this->History as $historia):?>
		<tr >
		<td><?php echo $historia->name ?></td>
		<td><?php echo JText::_("$historia->note");?></td>
		<td><?php echo $states[$historia->status]?></td>
		<td><?php echo date("d/m/Y h:i:s A", strtotime($historia->fecsis)); ?></td>
		</tr>
		<?php   endforeach;?>
		</table>	
	</div>
	</div><br class="salto">
	
	<input type="button"
			value="<?php echo JText::_('ORDERS.ORDER.BUTTON.CLOSE'); ?>"
			id="button" class="button_next" name="button"
			onclick="location.href='<?php echo JURI::root() . "index.php"; ?>'" />
			
	<!-- <input type="button"
			value="<?php //echo JText::_('ORDERS.ORDER.BUTTON.CLOSE'); ?>"
			id="button" class="button_next" name="button"
			onclick="location.href='index.php?option=com_asom&task=orders.display'" /> -->
			
	<div class="clear"></div>

</div>

 

<br class="salto">

 