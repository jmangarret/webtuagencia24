<?php
$hotel =  $this->hotel;

//create dates & default values
$startDate = $this->userData->start_date;
$endDate = $this->userData->end_date;
$startDate = JHotelUtil::convertToFormat($startDate);
$endDate = JHotelUtil::convertToFormat($endDate);
?>
							
<?php if ($this->appSettings->enable_hotel_tabs==1) {?>	
	<div class="hotel-image-gallery">
		<div class="image-preview-cnt">
			<img id="image-preview" alt="<?php if (isset($hotel->pictures[0])) echo isset($hotel->pictures[0]->hotel_picture_info)?$hotel->pictures[0]->hotel_picture_info:'' ?>" src='<?php if(isset($hotel->pictures[0])) echo JURI::root().PATH_PICTURES.$hotel->pictures[0]->hotel_picture_path?>' 
			width="1100px" height="400px"/>
		</div>
		<div class="small-images">
		<?php
			foreach( $this->hotel->pictures as $index=>$picture ){
				if($index>=32) break;
		?>
			<div class="image-prv-cnt">
				<img class="image-prv" alt="<?php echo isset($picture->hotel_picture_info)?$picture->hotel_picture_info:'' ?>"
					src='<?php echo JURI::root() .PATH_PICTURES.$picture->hotel_picture_path?>'/>
			</div>	
			
		<?php } ?>
		</div>
		
		<div class="clear"> </div>
		<div class="right">
		<!--micod
		El enlace tiene un mal direccionamiento, enlace original-->
			<!--<a href="<?php echo JHotelUtil::getHotelLink($this->hotel).'?'.strtolower(JText::_("LNG_PHOTO_GALLERY")) ?>" ><?php echo JText::_('LNG_VIEW_ALL_PHOTOS')?></a>-->

			<a href="<?php echo JHotelUtil::getHotelLink($this->hotel).'?'.'foto' ?>" ><?php echo JText::_('LNG_VIEW_ALL_PHOTOS')?></a>
		</div>
	</div>
<?php }?>
<div class="clear"> </div>
<div class="reservation-details-holder row-fluid">
	<h3><?php echo isset($this->hotel->types) & $this->hotel->types[0]->id == PARK_TYPE_ID ?JText::_('LNG_SEARCH_PARKS_SPECIALS') : JText::_('LNG_SEARCH_ROOMS_SPECIALS')?>:</h3>
	<form action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&view=hotel') ?>" method="post" name="searchForm" id="searchForm">
		<input type='hidden' name='resetSearch' value='true'>
		<input type='hidden' name='task' value='hotel.changeSearch'>
		<input type="hidden" name="hotel_id" id="hotel_id" value="<?php echo $this->hotel->hotel_id ?>" />
		<input type='hidden' name='year_start' value=''>
		<input type='hidden' name='month_start' value=''>
		<input type='hidden' name='day_start' value=''>
		<input type='hidden' name='year_end' value=''>
		<input type='hidden' name='month_end' value=''>
		<input type='hidden' name='day_end' value=''>
		<input type='hidden' name='rooms' value=''>
		<input type='hidden' name='guest_adult' value=''>
		<input type='hidden' name='guest_child' value=''>
		<input type='hidden' name='user_currency' value=''>
		
		<?php 
			if(isset($this->userData->roomGuests )){
				foreach($this->userData->roomGuests as $guestPerRoom){?>
					<input class="room-search" type="hidden" name='room-guests[]' value='<?php echo $guestPerRoom?>'/>
				<?php }
			}
			if(isset($this->userData->roomGuestsChildren )){
				foreach($this->userData->roomGuestsChildren as $guestPerRoomC){?>
						<input class="room-search" type="hidden" name='room-guests-children[]' value='<?php echo $guestPerRoomC?>'/>
					<?php }
				}
			if(isset($this->userData->excursions ) && is_array($this->userData->excursions) && count($this->userData->excursions)>0){
				foreach($this->userData->excursions as $excursion){?>
					<input class="excursions" type="hidden" name='excursions[]' value='<?php echo $excursion;?>' />
				<?php }
				}
				

		?>
		<div class="reservation-details span12" >
			<div class="reservation-detail" >
				<label for=""><?php echo JText::_('LNG_ARIVAL')?></label>
					<div class="calendarHolder">
						<?php
						echo JHTML::calendar(
												$startDate,'jhotelreservation_datas','jhotelreservation_datas2',$this->appSettings->calendarFormat, 
												array(
														'class'		=>'date_hotelreservation', 
														'minDate'		=>'0',
														'onchange'	=>
																	"
																		if(!checkStartDate(this.value, defaultStartDate,defaultEndDate))
																			return false;
																		setDepartureDate('jhotelreservation_datae2',this.value);
																	",
													)
											);
						?>
				</div>
			</div>
			
			<div class="reservation-detail">
				<label for=""><?php echo JText::_('LNG_DEPARTURE')?></label>
				<div class="calendarHolder">
					<?php
						echo JHTML::calendar($endDate,'jhotelreservation_datae','jhotelreservation_datae2', $this->appSettings->calendarFormat, array('class'=>'date_hotelreservation','onchange'	=>'checkEndDate(this.value,defaultStartDate,defaultEndDate);'));
					?>
				</div>
				
			</div>
			
			
			<div class="reservation-detail">
					<!--DESACTIVADO EL LINK DE DISTRIBUCIÓN DE HABITACIONES DEL HOTEL-->
				<!--<label for=""><a id="" href="javascript:void(0);" onclick="showExpandedSearch()"><?php //echo JText::_('LNG_ROOMS')?></a></label>-->
				<label for=""><?php echo JText::_('LNG_ROOMS')?></label>
				<div class="styled-select">
					<select id='jhotelreservation_rooms2' name='jhotelreservation_rooms' class = 'select_hotelreservation'>
						<?php
						$jhotelreservation_rooms = $this->userData->rooms;
						
						$i_min = 1;
						$i_max = 5;
						
						for($i=$i_min; $i<=$i_max; $i++)
						{
						?>
						<option 
							value='<?php echo $i?>'
							<?php echo $jhotelreservation_rooms==$i ? " selected " : ""?>
						>
							<?php echo $i?>
						</option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
			<div class="reservation-detail">
				<label for=""><?php echo JText::_('LNG_ADULTS_19')?></label>
				<div class="styled-select" style="margin-left: 15px;">
					<select name='jhotelreservation_guest_adult' id='jhotelreservation_guest_adult'	class		= 'select_hotelreservation'	>
						<?php
						$i_min = 1;
						$i_max = 12;
						
						$jhotelreservation_adults = $this->userData->total_adults;
						
						for($i=$i_min; $i<=$i_max; $i++)
						{
						?>
						<option value='<?php echo $i?>'  <?php echo $jhotelreservation_adults==$i ? " selected " : ""?>><?php echo $i?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
			
			<div class="reservation-detail " style="<?php echo $this->appSettings->show_children!=0 ? "":"display:none" ?>">
				<label for=""><?php //echo JText::_('LNG_CHILDREN_0_18')?>Niños(2-11)</label>
				<div class="styled-select" style="margin-left: 12px;">
					<select name='jhotelreservation_guest_child' id='jhotelreservation_guest_child'
						class		= 'select_hotelreservation'
					>
						<?php
						$i_min = 0;
						$i_max = 10;
						$jhotelreservation_children = $this->userData->total_children;
							
						for($i=$i_min; $i<=$i_max; $i++)
						{
						?>
						<option <?php echo $jhotelreservation_children==$i ? " selected " : ""?> value='<?php echo $i?>'  ><?php echo $i?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
			<div class="reservation-detail voucher">
				<!--<label for=""><?php echo JText::_('LNG_VOUCHER')?></label>
				<input type="text" value="<?php echo $this->userData->voucher ?>" name="voucher" id="voucher"/>-->
			</div>
			<div class="reservation-detail">
				<label for="">&nbsp;</label>
				<!--micod
				Botón para filtrar búsqueda de habitaciones-->
				<button class="ui-hotel-button ui-hotel-button-grey"	onClick	="checkRoomRates('searchForm');"
					type="button" name="checkRates" value="checkRates"><?php //echo JText::_('LNG_CHECK_AVAIL')?> Filtrar </button>
			</div>
			<div class="clear"></div>
		</div>
	</form>
</div>
<?php require_once 'roomrates.php'; ?>

<?php if($this->appSettings->enable_hotel_description==1){?>
<div class="hotel-description hotel-item">
	<h2><?php echo isset($this->hotel->types) & $this->hotel->types[0]->id == PARK_TYPE_ID ? JText::_('LNG_PARK_DESCRIPTION'): JText::_('LNG_HOTEL_DESCRIPTION')?>  <?php echo $this->hotel->hotel_name; ?></h2>
	<?php  
		$hotelDescription = $this->hotel->hotelDescription;
		echo $hotelDescription;
	?>
</div>
<?php }?>
<?php if($this->appSettings->enable_hotel_facilities==1){?>
<div class="hotel-facilities hotel-item">
	
	<h2><?php echo isset($this->hotel->types) & $this->hotel->types[0]->id == PARK_TYPE_ID ?JText::_('LNG_PARK_FACILITIES') : JText::_('LNG_HOTEL_FACILITIES')?> <?php echo $this->hotel->hotel_name; ?></h2>
	<ul class="blue">
		<?php 
		foreach($this->hotel->facilities as $facility)	{
		?>
			<li><?php echo $facility->name?></li>			
		<?php } ?>
	</ul>
</div>
<?php }?>

<?php
 if(count($hotel->reviews) >= MINIMUM_HOTEL_REVIEWS & $this->appSettings->enable_hotel_rating==1){ 
	 require_once 'hotelreviews.php'; 
 }
 ?>
 
 
<?php if($this->appSettings->enable_hotel_information==1) require_once 'informations.php'; ?>

<script>

	var dateFormat = "<?php echo  $this->appSettings->dateFormat; ?>";
	var message = "<?php echo JText::_('LNG_ERROR_PERIOD',true)?>";
	var defaultEndDate = "<?php echo isset($module)?$module->params["start-date"]: ''?>";
	var defaultStartDate = "<?php echo isset($module)?$module->params["end-date"]: ''?>";
	
	// starting the script on page load
	jQuery(document).ready(function(){

		jQuery("img.image-prv").hover(function(e){
			jQuery("#image-preview").attr('src', this.src);	
		});

		jQuery("#jhotelreservation_datas2").click(function(){
			 jQuery("#jhotelreservation_datas2_img").click();
		});

		jQuery("#jhotelreservation_datae2").click(function(){
			 jQuery("#jhotelreservation_datae2_img").click();
		});
	
	});		
</script>
	
<?php 
	require_once JPATH_SITE.'/components/com_jhotelreservation/include/multipleroomselection.php';
?> 
