<?php
$hotel =  $this->hotel;

//create dates & default values
$startDate = $this->userData->start_date;
$endDate = $this->userData->end_date;
$startDate = JHotelUtil::convertToFormat($startDate);
$endDate = JHotelUtil::convertToFormat($endDate);
$log = Logger::getInstance(JPATH_COMPONENT."/logs/site-log-".date("d-m-Y").'.log',1);
		$log->LogDebug("Archivo \components\com_jhotelreservation\views\hotel\tmpl\hoteloverview.php");
 		$log->LogDebug("Accedió");

$habit = $this->userData->rooms;
?>
<!--////////////////////////////////////////////////////////////////-->
<!--MICOD
Aqui importo el archivo JS donde se encuentra el código AJAX para enviar datos por POST-->
<!--<script type="text/javascript" src="ajax_filtro.js"></script>-->
<!--////////////////////////////////////////////////////////////////-->
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
					src='<?php echo JURI::root() .PATH_PICTURES.$picture->hotel_picture_path?>' />
			</div>	
			
		<?php } ?>
		</div>
		
		<div class="clear"> </div>
		<div class="right">
			<a href="<?php echo JHotelUtil::getHotelLink($this->hotel).'?'.strtolower(JText::_("LNG_PHOTO_GALLERY")) ?>" ><?php echo JText::_('LNG_VIEW_ALL_PHOTOS')?></a>
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
		<!--micod-->
		<input type='hidden' name='child_age' value=''><!--Nuevo campo hidden para los valores de las edaddes-->
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
						$i_max = 4;
						
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
			<div class="reservation-detail" style="Display: inline;" id="huespedes" name="huespedes">
				<label for="" style="margin-left: 16px;"><?php //echo JText::_('LNG_ADULTS_19')?>Adultos</label>
				<div class="styled-select" style="margin-left: 15px;">
					<select name='jhotelreservation_guest_adult' id='jhotelreservation_guest_adult'	class		= 'select_hotelreservation'	>
						<?php
						$i_min = 1;
						$i_max = 7; //micod original en 12
						
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
			
			<div class="reservation-detail " style="<?php echo $this->appSettings->show_children!=0 ? "":"display:none" ?>" style="Display: inline;" id="huespedes2" name="huespedes2">
				<label for="" style="margin-left: 13px;"><?php //echo JText::_('LNG_CHILDREN_0_18')?>Niños</label>
				<div class="styled-select" style="margin-left: 12px;">
					<select name='jhotelreservation_guest_child' id='jhotelreservation_guest_child'
						class		= 'select_hotelreservation'
					>
						<?php
						$i_min = 0;
						$i_max = 6; //micod original en 10
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

			<?php
			//MICOD
			$edades = explode("|", $this->userData->child_age);
			$niños=0;
			foreach ($edades as $key => $value) {
				if ($value != "no") {
					$niños++;
				}
			}

			?>

						<!--////////////////////////////////////////////////////////////////////////-->
						<!--micod
						Script Jquery donde aplico capas para las habitaciones y edades de los niños-->
						<script type="text/javascript">
								$(document).ready(function(){
/*--------------------------------------------------------------------------------------------------------------------------------------------*/
//PRIMERA SESSIÓN: Aqui se ejecutarán los evento JQuery cuando el documento esté cargado (de forma automática)
    								//Remover los name de las habitaciones al cargar la página para que no envíen datos si no son seleccionados
    										$( "#roomgues1" ).removeAttr("name");
											$( "#roomgues2" ).removeAttr("name");
											$( "#roomgues3" ).removeAttr("name");
											$( "#roomgues4" ).removeAttr("name");
											$( "#room-guests-children1" ).removeAttr("name");
											$( "#room-guests-children2" ).removeAttr("name");
											$( "#room-guests-children3" ).removeAttr("name");
											$( "#room-guests-children4" ).removeAttr("name");

											if($('select[name=jhotelreservation_rooms]').val() == 1){
											//Ocultar/Mostrar las habitaciones adicionales
											$("#rooms-container").css("display","none");
											$("#huespedes").css("display","inline");
											$("#huespedes2").css("display","inline");
											$("#edades").css("display","inline");
											//Ocultar/Mostrar los campos de las habitaciones
											$("#titulo-room-guests-1").css("display","none");
											$("#titulo-room-guests-2").css("display","none");
											$("#titulo-room-guests-3").css("display","none");
											$("#titulo-room-guests-4").css("display","none");
											$("#room-guests-1").css("display","none");
											$("#room-guests-2").css("display","none");
											$("#room-guests-3").css("display","none");
											$("#room-guests-4").css("display","none");
											//Eliminar atributo name a las habitaciones para NO enviar sus datos al controlador
											$( "#roomgues1" ).removeAttr("name");
											$( "#roomgues2" ).removeAttr("name");
											$( "#roomgues3" ).removeAttr("name");
											$( "#roomgues4" ).removeAttr("name");
											$( "#room-guests-children1" ).removeAttr("name");
											$( "#room-guests-children2" ).removeAttr("name");
											$( "#room-guests-children3" ).removeAttr("name");
											$( "#room-guests-children4" ).removeAttr("name");
											//Habilitar/Deshabilitar <tr> para las edades de los niños por habitación
											$("#habilitar-edades-1").css("display","none");
											$("#habilitar-edades-2").css("display","none");
											$("#habilitar-edades-3").css("display","none");
											$("#habilitar-edades-4").css("display","none");
									//Condición para el número de habitaciones seleccionado
										}else if($('select[name=jhotelreservation_rooms]').val() == 2){
											//Ocultar/Mostrar las habitaciones adicionales
											$("#huespedes").css("display","none");
											$("#huespedes2").css("display","none");
											$("#edades").css("display","none");
											$("#rooms-container").css("display","block");
											//Colocar atributo name a las habitaciones para enviar sus datos al controlador
											$( "#roomgues1" ).attr("name","room-guests[]");
											$( "#roomgues2" ).attr("name","room-guests[]");
											$( "#room-guests-children1" ).attr("name","room-guests-children[]");
											$( "#room-guests-children2" ).attr("name","room-guests-children[]");
											//Ocultar/Mostrar los campos de las habitaciones
											$("#titulo-room-guests-1").css("display","block");
											$("#titulo-room-guests-2").css("display","block");
											$("#titulo-room-guests-3").css("display","none");
											$("#titulo-room-guests-4").css("display","none");
											$("#room-guests-1").css("display","block");
											$("#room-guests-2").css("display","block");
											$("#room-guests-3").css("display","none");
											$("#room-guests-4").css("display","none");
											//Eliminar atributo name a las habitaciones para NO enviar sus datos al controlador
											$( "#roomgues3" ).removeAttr("name");
											$( "#roomgues4" ).removeAttr("name");
											$( "#room-guests-children3" ).removeAttr("name");
											$( "#room-guests-children4" ).removeAttr("name");
											//Habilitar/Deshabilitar <tr> para las edades de los niños por habitación
											$("#habilitar-edades-1").css("display","block");
											$("#habilitar-edades-2").css("display","block");
											$("#habilitar-edades-3").css("display","none");
											$("#habilitar-edades-4").css("display","none");
									//Condición para el número de habitaciones seleccionado
										}else if ($('select[name=jhotelreservation_rooms]').val() == 3) {
											//Ocultar/Mostrar las habitaciones adicionales
											$("#huespedes").css("display","none");
											$("#huespedes2").css("display","none");
											$("#edades").css("display","none");
											$("#rooms-container").css("display","block");
											//Colocar atributo name a las habitaciones para enviar sus datos al controlador
											$( "#roomgues1" ).attr("name","room-guests[]");
											$( "#roomgues2" ).attr("name","room-guests[]");
											$( "#roomgues3" ).attr("name","room-guests[]");
											$( "#room-guests-children1" ).attr("name","room-guests-children[]");
											$( "#room-guests-children2" ).attr("name","room-guests-children[]");
											$( "#room-guests-children3" ).attr("name","room-guests-children[]");
											//Ocultar/Mostrar los campos de las habitaciones
											$("#titulo-room-guests-1").css("display","block");
											$("#titulo-room-guests-2").css("display","block");
											$("#titulo-room-guests-3").css("display","block");
											$("#titulo-room-guests-4").css("display","none");
											$("#room-guests-1").css("display","block");
											$("#room-guests-2").css("display","block");
											$("#room-guests-3").css("display","block");
											$("#room-guests-4").css("display","none");
											//Habilitar/Deshabilitar <tr> para las edades de los niños por habitación
											$("#habilitar-edades-1").css("display","block");
											$("#habilitar-edades-2").css("display","block");
											$("#habilitar-edades-3").css("display","block");
											$("#habilitar-edades-4").css("display","none");
											//Eliminar atributo name a las habitaciones para NO enviar sus datos al controlador
											$( "#roomgues4" ).removeAttr("name");
											$( "#room-guests-children4" ).removeAttr("name");
									//Condición para el número de habitaciones seleccionado
										}else if ($('select[name=jhotelreservation_rooms]').val() == 4) {
											//Ocultar/Mostrar las habitaciones adicionales
											$("#huespedes").css("display","none");
											$("#huespedes2").css("display","none");
											$("#edades").css("display","none");
											$("#rooms-container").css("display","block");
											//Colocar atributo name a las habitaciones para enviar sus datos al controlador
											$( "#roomgues1" ).attr("name","room-guests[]");
											$( "#roomgues2" ).attr("name","room-guests[]");
											$( "#roomgues3" ).attr("name","room-guests[]");
											$( "#roomgues4" ).attr("name","room-guests[]");
											$( "#room-guests-children1" ).attr("name","room-guests-children[]");
											$( "#room-guests-children2" ).attr("name","room-guests-children[]");
											$( "#room-guests-children3" ).attr("name","room-guests-children[]");
											$( "#room-guests-children4" ).attr("name","room-guests-children[]");
											//Ocultar/Mostrar los campos de las habitaciones
											$("#titulo-room-guests-1").css("display","block");
											$("#titulo-room-guests-2").css("display","block");
											$("#titulo-room-guests-3").css("display","block");
											$("#titulo-room-guests-4").css("display","block");
											$("#room-guests-1").css("display","block");
											$("#room-guests-2").css("display","block");
											$("#room-guests-3").css("display","block");
											$("#room-guests-4").css("display","block");
											//Habilitar/Deshabilitar <tr> para las edades de los niños por habitación
											$("#habilitar-edades-1").css("display","block");
											$("#habilitar-edades-2").css("display","block");
											$("#habilitar-edades-3").css("display","block");
											$("#habilitar-edades-4").css("display","block");
										}

										
										/*Lugar donde cargo los combos de las edades si el combo de niños por habitación
										está seleccionado y tiene un valor*/
											if ($('select[id=room-guests-children1').val()) {

												generateAgeRoomContent($('select[id=room-guests-children1').val(),1);

											} if ($('select[id=room-guests-children2').val()){

												generateAgeRoomContent($('select[id=room-guests-children2').val(),2);

											} if ($('select[id=room-guests-children3').val()){

												generateAgeRoomContent($('select[id=room-guests-children3').val(),3);

											} if ($('select[id=room-guests-children4').val()){

												generateAgeRoomContent($('select[id=room-guests-children4').val(),4);
												
											}
										
										




									//Inicio: Sección de las edades de los niños
							            if($('select[name=jhotelreservation_guest_child]').val() == 0){
											$("#edades").css("display","none");
										}else if($('select[name=jhotelreservation_guest_child]').val() == 1){
											for (var i = 2; i <= 4; i++) {
												if ($('select[name=jhotelreservation_rooms]').val() == i) {
													$("#edades").css("display","none");
													break;
												}else{
													$("#edades").css("display","inline");
												}
											}
											$("#edad-1").css("display","inline");
											$("#edad-2").css("display","none");
											$("#edad-3").css("display","none");
											$("#edad-4").css("display","none");
											$("#edad-5").css("display","none");
											$("#edad-6").css("display","none");
										}else if ($('select[name=jhotelreservation_guest_child]').val() == 2) {
											for (var i = 2; i <= 4; i++) {
												if ($('select[name=jhotelreservation_rooms]').val() == i) {
													$("#edades").css("display","none");
													break;
												}else{
													$("#edades").css("display","inline");
												}
											}
											$("#edad-1").css("display","inline");
											$("#edad-2").css("display","inline");
											$("#edad-3").css("display","none");
											$("#edad-4").css("display","none");
											$("#edad-5").css("display","none");
											$("#edad-6").css("display","none");
										}else if ($('select[name=jhotelreservation_guest_child]').val() == 3) {
											for (var i = 2; i <= 4; i++) {
												if ($('select[name=jhotelreservation_rooms]').val() == i) {
													$("#edades").css("display","none");
													break;
												}else{
													$("#edades").css("display","inline");
												}
											}
											$("#edad-1").css("display","inline");
											$("#edad-2").css("display","inline");
											$("#edad-3").css("display","inline");
											$("#edad-4").css("display","none");
											$("#edad-5").css("display","none");
											$("#edad-6").css("display","none");
										}else if ($('select[name=jhotelreservation_guest_child]').val() == 4) {
											for (var i = 2; i <= 4; i++) {
												if ($('select[name=jhotelreservation_rooms]').val() == i) {
													$("#edades").css("display","none");
													break;
												}else{
													$("#edades").css("display","inline");
												}
											}
											$("#edad-1").css("display","inline");
											$("#edad-2").css("display","inline");
											$("#edad-3").css("display","inline");
											$("#edad-4").css("display","inline");
											$("#edad-5").css("display","none");
											$("#edad-6").css("display","none");
										}else if ($('select[name=jhotelreservation_guest_child]').val() == 5) {
											for (var i = 2; i <= 4; i++) {
												if ($('select[name=jhotelreservation_rooms]').val() == i) {
													$("#edades").css("display","none");
													break;
												}else{
													$("#edades").css("display","inline");
												}
											}
											$("#edad-1").css("display","inline");
											$("#edad-2").css("display","inline");
											$("#edad-3").css("display","inline");
											$("#edad-4").css("display","inline");
											$("#edad-5").css("display","inline");
											$("#edad-6").css("display","none");
										}else if ($('select[name=jhotelreservation_guest_child]').val() == 6) {
											for (var i = 2; i <= 4; i++) {
												if ($('select[name=jhotelreservation_rooms]').val() == i) {
													$("#edades").css("display","none");
													break;
												}else{
													$("#edades").css("display","inline");
												}
											}
											$("#edad-1").css("display","inline");
											$("#edad-2").css("display","inline");
											$("#edad-3").css("display","inline");
											$("#edad-4").css("display","inline");
											$("#edad-5").css("display","inline");
											$("#edad-6").css("display","inline");
										}
//FIN DE LA PRIMERA SECCIÓN.
/*--------------------------------------------------------------------------------------------------------------------------------------------*/
//SEGUNDA SESSIÓN: Aqui se ejecutarán los evento JQuery cuando algún campo cambie su valor (de forma manual)

								$("select[name=jhotelreservation_guest_child]").change(function(){
										if($('select[name=jhotelreservation_guest_child]').val() == 0){
											$("#edades").css("display","none");
										}else if($('select[name=jhotelreservation_guest_child]').val() == 1){
											$("#edades").css("display","inline");
											$("#edad-1").css("display","inline");
											$("#edad-2").css("display","none");
											$("#edad-3").css("display","none");
											$("#edad-4").css("display","none");
											$("#edad-5").css("display","none");
											$("#edad-6").css("display","none");
										}else if ($('select[name=jhotelreservation_guest_child]').val() == 2) {
											$("#edades").css("display","inline");
											$("#edad-1").css("display","inline");
											$("#edad-2").css("display","inline");
											$("#edad-3").css("display","none");
											$("#edad-4").css("display","none");
											$("#edad-5").css("display","none");
											$("#edad-6").css("display","none");
										}else if ($('select[name=jhotelreservation_guest_child]').val() == 3) {
											$("#edades").css("display","inline");
											$("#edad-1").css("display","inline");
											$("#edad-2").css("display","inline");
											$("#edad-3").css("display","inline");
											$("#edad-4").css("display","none");
											$("#edad-5").css("display","none");
											$("#edad-6").css("display","none");
										}else if ($('select[name=jhotelreservation_guest_child]').val() == 4) {
											$("#edades").css("display","inline");
											$("#edad-1").css("display","inline");
											$("#edad-2").css("display","inline");
											$("#edad-3").css("display","inline");
											$("#edad-4").css("display","inline");
											$("#edad-5").css("display","none");
											$("#edad-6").css("display","none");
										}else if ($('select[name=jhotelreservation_guest_child]').val() == 5) {
											$("#edades").css("display","inline");
											$("#edad-1").css("display","inline");
											$("#edad-2").css("display","inline");
											$("#edad-3").css("display","inline");
											$("#edad-4").css("display","inline");
											$("#edad-5").css("display","inline");
											$("#edad-6").css("display","none");
										}else if ($('select[name=jhotelreservation_guest_child]').val() == 6) {
											$("#edades").css("display","inline");
											$("#edad-1").css("display","inline");
											$("#edad-2").css("display","inline");
											$("#edad-3").css("display","inline");
											$("#edad-4").css("display","inline");
											$("#edad-5").css("display","inline");
											$("#edad-6").css("display","inline");
										}
							            
							        });
									//Fin: Sección de las edades de los niños

									var before_change1;
									var before_change2;

									//$("#jhotelreservation_rooms").change( function(e){
									$("select[name=jhotelreservation_rooms]").change(function(e){
									//showExpandDialog(this.value,1);
									//Condición para el número de habitaciones seleccionado
									if($('select[name=jhotelreservation_rooms]').val() == 1){
											//Ocultar/Mostrar las habitaciones adicionales
											$("#rooms-container").css("display","none");
											$("#huespedes").css("display","inline");
											$("#huespedes2").css("display","inline");
											$("#edades").css("display","inline");
											//Ocultar/Mostrar los campos de las habitaciones
											$("#titulo-room-guests-1").css("display","none");
											$("#titulo-room-guests-2").css("display","none");
											$("#titulo-room-guests-3").css("display","none");
											$("#titulo-room-guests-4").css("display","none");
											$("#room-guests-1").css("display","none");
											$("#room-guests-2").css("display","none");
											$("#room-guests-3").css("display","none");
											$("#room-guests-4").css("display","none");
											//Eliminar atributo name a las habitaciones para NO enviar sus datos al controlador
											$( "#roomgues1" ).removeAttr("name");
											$( "#roomgues2" ).removeAttr("name");
											$( "#roomgues3" ).removeAttr("name");
											$( "#roomgues4" ).removeAttr("name");
											$( "#room-guests-children1" ).removeAttr("name");
											$( "#room-guests-children2" ).removeAttr("name");
											$( "#room-guests-children3" ).removeAttr("name");
											$( "#room-guests-children4" ).removeAttr("name");
											//Habilitar/Deshabilitar <tr> para las edades de los niños por habitación
											$("#habilitar-edades-1").css("display","none");
											$("#habilitar-edades-2").css("display","none");
											$("#habilitar-edades-3").css("display","none");
											$("#habilitar-edades-4").css("display","none");
									//Condición para el número de habitaciones seleccionado
										}else if($('select[name=jhotelreservation_rooms]').val() == 2){
											//Ocultar/Mostrar las habitaciones adicionales
											$("#huespedes").css("display","none");
											$("#huespedes2").css("display","none");
											$("#edades").css("display","none");
											$("#rooms-container").css("display","block");
											//Colocar atributo name a las habitaciones para enviar sus datos al controlador
											$( "#roomgues1" ).attr("name","room-guests[]");
											$( "#roomgues2" ).attr("name","room-guests[]");
											$( "#room-guests-children1" ).attr("name","room-guests-children[]");
											$( "#room-guests-children2" ).attr("name","room-guests-children[]");
											//Ocultar/Mostrar los campos de las habitaciones
											$("#titulo-room-guests-1").css("display","block");
											$("#titulo-room-guests-2").css("display","block");
											$("#titulo-room-guests-3").css("display","none");
											$("#titulo-room-guests-4").css("display","none");
											$("#room-guests-1").css("display","block");
											$("#room-guests-2").css("display","block");
											$("#room-guests-3").css("display","none");
											$("#room-guests-4").css("display","none");
											//Eliminar atributo name a las habitaciones para NO enviar sus datos al controlador
											$( "#roomgues3" ).removeAttr("name");
											$( "#roomgues4" ).removeAttr("name");
											$( "#room-guests-children3" ).removeAttr("name");
											$( "#room-guests-children4" ).removeAttr("name");
											//Habilitar/Deshabilitar <tr> para las edades de los niños por habitación
											$("#habilitar-edades-1").css("display","block");
											$("#habilitar-edades-2").css("display","block");
											$("#habilitar-edades-3").css("display","none");
											$("#habilitar-edades-4").css("display","none");
									//Condición para el número de habitaciones seleccionado
										}else if ($('select[name=jhotelreservation_rooms]').val() == 3) {
											//Ocultar/Mostrar las habitaciones adicionales
											$("#huespedes").css("display","none");
											$("#huespedes2").css("display","none");
											$("#edades").css("display","none");
											$("#rooms-container").css("display","block");
											//Colocar atributo name a las habitaciones para enviar sus datos al controlador
											$( "#roomgues1" ).attr("name","room-guests[]");
											$( "#roomgues2" ).attr("name","room-guests[]");
											$( "#roomgues3" ).attr("name","room-guests[]");
											$( "#room-guests-children1" ).attr("name","room-guests-children[]");
											$( "#room-guests-children2" ).attr("name","room-guests-children[]");
											$( "#room-guests-children3" ).attr("name","room-guests-children[]");
											//Ocultar/Mostrar los campos de las habitaciones
											$("#titulo-room-guests-1").css("display","block");
											$("#titulo-room-guests-2").css("display","block");
											$("#titulo-room-guests-3").css("display","block");
											$("#titulo-room-guests-4").css("display","none");
											$("#room-guests-1").css("display","block");
											$("#room-guests-2").css("display","block");
											$("#room-guests-3").css("display","block");
											$("#room-guests-4").css("display","none");
											//Habilitar/Deshabilitar <tr> para las edades de los niños por habitación
											$("#habilitar-edades-1").css("display","block");
											$("#habilitar-edades-2").css("display","block");
											$("#habilitar-edades-3").css("display","block");
											$("#habilitar-edades-4").css("display","none");
											//Eliminar atributo name a las habitaciones para NO enviar sus datos al controlador
											$( "#roomgues4" ).removeAttr("name");
											$( "#room-guests-children4" ).removeAttr("name");
									//Condición para el número de habitaciones seleccionado
										}else if ($('select[name=jhotelreservation_rooms]').val() == 4) {
											//Ocultar/Mostrar las habitaciones adicionales
											$("#huespedes").css("display","none");
											$("#huespedes2").css("display","none");
											$("#edades").css("display","none");
											$("#rooms-container").css("display","block");
											//Colocar atributo name a las habitaciones para enviar sus datos al controlador
											$( "#roomgues1" ).attr("name","room-guests[]");
											$( "#roomgues2" ).attr("name","room-guests[]");
											$( "#roomgues3" ).attr("name","room-guests[]");
											$( "#roomgues4" ).attr("name","room-guests[]");
											$( "#room-guests-children1" ).attr("name","room-guests-children[]");
											$( "#room-guests-children2" ).attr("name","room-guests-children[]");
											$( "#room-guests-children3" ).attr("name","room-guests-children[]");
											$( "#room-guests-children4" ).attr("name","room-guests-children[]");
											//Ocultar/Mostrar los campos de las habitaciones
											$("#titulo-room-guests-1").css("display","block");
											$("#titulo-room-guests-2").css("display","block");
											$("#titulo-room-guests-3").css("display","block");
											$("#titulo-room-guests-4").css("display","block");
											$("#room-guests-1").css("display","block");
											$("#room-guests-2").css("display","block");
											$("#room-guests-3").css("display","block");
											$("#room-guests-4").css("display","block");
											//Habilitar/Deshabilitar <tr> para las edades de los niños por habitación
											$("#habilitar-edades-1").css("display","block");
											$("#habilitar-edades-2").css("display","block");
											$("#habilitar-edades-3").css("display","block");
											$("#habilitar-edades-4").css("display","block");
										}

								});
//FIN DE LA SEGUNDA SECCIÓN.
/*--------------------------------------------------------------------------------------------------------------------------------------------*/
//FUNCIÓN DE LA VISUALIZACION DE LAS EDADES POR HABITACIÓN

								$("#room-guests-children1").change(function(e){
									generateAgeRoomContent(this.value,1);
								});
								$("#room-guests-children2").change(function(e){
									generateAgeRoomContent(this.value,2);
								});
								$("#room-guests-children3").change(function(e){
									generateAgeRoomContent(this.value,3);
								});
								$("#room-guests-children4").change(function(e){
									generateAgeRoomContent(this.value,4);
								});

							function generateAgeRoomContent(child, num){
							$('#pegar-'+num).children().remove(); //Importante. Remueve los combos de las edades a medida que se eligen, evita una sumatoria acumnulada de <select>

							var selectage=""; //Vaciar <option> antes de cargarlas
							<?php
							$i_min = 0;
							$i_max = 17;

							//Lugar donde Tomo las edades convertidos en String y lo convierto en una Matriz con su habitación
							$buffer = $this->userData->child_ages;
							foreach ($buffer as $key => $value) {
								$edades_habit[$key] = explode("|", $value);
							}

							for($niño=$i_min; $niño<=$i_max; $niño++) //Generar los options del Select de las edades
							{


							?>
							
								selectage+="<option <?php $habitacion==$niño?'selected':''?> value='<?php echo $niño?>'><?php echo $niño?></option>";

							<?php
							}

							?>
							if (child>=1){ //Generar el Select según el número de habitaciones
								$("#edades-room-"+num).css("display","block");
							for(i=1;i<=child;i++){



								var age = "<li valign='middle' style='margin: 5px; display: inline;'><select id='edad-"+num+"-"+i+"' name='edad-"+num+"-"+i+"' style='width: 50px;'>"+selectage+"</select></li>";
								if($("#room-guests-children"+num).val()>=1){ 
								$("#pegar-"+num).append(age); //Pegar los select generados en una etiqueta HTML Predeterminada

								}
								
							}


							}else if(child==0){ //Si no existe habitaciones, eliminamos los Select
									$(".room-search").remove();
								$("#edades-room-"+num).css("display","none");
								age = "";
							}

							$('#edades-room-'+num+' ul').each(function(index) { //Detectamos la {ultima habitación creada con una clase
							   $(this).removeClass("last");
							
							});

							<?php
							/*-------------------------------------------------------------------*/
							/* MICOD IMPORTANTE:
							Éste ciclo es el responsable de señalar las edades por habitaciones en los select,
							sin él todas las edades aparecerían en 0 al momento de llegar al filtro en la
							pantalla de selección de habitaciones*/
							for ($habitacion=0; $habitacion <= 3; $habitacion++) { //Ciclo Habitaciones
								for ($niño=0; $niño <= 5; $niño++) { //Ciclo Niños
									for($edad=$i_min; $edad<=$i_max; $edad++){ //Ciclo Edades
										if ($edades_habit[$habitacion][$niño]==$edad) { //Condición si encuentra coincidencias...
											?>
											var niño = "<?php echo $niño ?>";
											var habit = "<?php echo $habitacion ?>";
											var edad = "<?php echo $edad ?>";
											/*Importante, las posiciones de los arreglos comienzan en 0 y las etiquetas ID
											de los HTML comienzan en 1, así que corremos una posición antes de pegar
											el valor en su respectivo select*/
											niño++;
											habit++;

											//alert("Habitación: "+habit+" Niño: "+niño+" Edad: <?= $edades[$habitacion][$niño] ?>");
											$("#edad-"+habit+"-"+niño).val(edad); //Donde pegamos el valor con JQuery
											
											<?php
										}
									}
								}
							}
							/*-------------------------------------------------------------------*/
							?>
							
							$("#edades-room-"+num+" ul:last").addClass("last");
							$("#close-1").hide();
						}
						

							});
/*--------------------------------------------------------------------------------------------------------------------------------------------*/
						</script>
						<!--////////////////////////////////////////////////////////////////////////-->
						

						<!--////////////////////////////////////////////////////////////////////////-->
						<!--MICOD
						Contenedor de Habitaciones-->
						<div id="contenedor_habitaciones" style="position: relative; margin-top: 70px; left: 16px; width: 100%;">
							<div id="rooms-container" cellpadding="4" style="display: block; width: 97%; border-collapse: separate; border-spacing: 5px; border-radius: 5px 5px;">
							<div style="width: 450px; float: left; height: 110px;">
							<ul id="titulo-room-guests-1" style="display: none; margin-left: 0px; padding-top:150px;"><li><span><b>Habitación 1</b></span></li><li></li></ul>
							<ul id='room-guests-1' style="display: none;">
								<li valign='middle' style="float:left; padding-right: 50px;"><span>Adultos</span>
									<select name='room-guests[]'  id="roomgues1" style="width: 150px;">
										<?php
									$i_min = 1;
									$i_max = 8;
									if(isset($this->userData->roomGuests)){
										$jhotelreservation_room_adult = $this->userData->roomGuests;
									}
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option <?php echo $jhotelreservation_room_adult[0]==$i ? " selected " : ""?> value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								

								<span><?php echo JText::_('LNG_CHILDREN',true)?></span>
									<select name='room-guests-children[]' id='room-guests-children1' style="width: 150px;">
										<?php
									$i_min = 0;
									$i_max = 6;
									if(isset($this->userData->roomGuestsChildren)){
										$jhotelreservation_room_child = $this->userData->roomGuestsChildren;	
									}
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option <?php echo $jhotelreservation_room_child[0]==$i ? " selected " : ""?> value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								
								</li>
							<li>
								<div id='close-1' class='close' onclick='deleteRoom(1)'></div>
							</li>
							</ul>

								<div id="habilitar-edades-1" style="display: none; float: left;">
									<div id="edades-room-1" style="display: none; margin: -3px; padding: 8px;">
								<ul>
									<li>Edades</li>
								</ul>
								<ul id="pegar-1">
								</ul>		
								</div>
								</div>
							</div>

							<div style="position: relative; top: 0px; width: 450px; float: right; height: 110px;">
								<ul id="titulo-room-guests-2" style="display: none;"><span><b>Habitación 2</b></span></ul>
								<ul id='room-guests-2' style="display: none;">
								<li valign='middle'  style="float:left; padding-right: 50px;"><span>Adultos</span>
									<select name='room-guests[]' id="roomgues2" style="width: 150px;">
										<?php
									$i_min = 1;
									$i_max = 8;
									if(isset($this->userData->roomGuests)){
										$jhotelreservation_room_adult = $this->userData->roomGuests;
									}
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option <?php echo $jhotelreservation_room_adult[1]==$i ? " selected " : ""?> value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								

								<span><?php echo JText::_('LNG_CHILDREN',true)?></span>
									<select name='room-guests-children[]' id='room-guests-children2' style="width: 150px;">
										<?php
									$i_min = 0;
									$i_max = 6;
									if(isset($this->userData->roomGuestsChildren)){
										$jhotelreservation_room_child = $this->userData->roomGuestsChildren;	
									}
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option <?php echo $jhotelreservation_room_child[1]==$i ? " selected " : ""?> value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								</li>
							<li>
								<div id='close-2' class='close' onclick='deleteRoom(2)'></div>
							</li>
							</ul>

								<div id="habilitar-edades-2" style="display: none;">
								<div id="edades-room-2" style="display: none; margin: -3px; padding: 8px;">
								<ul>
									<li>Edades</li>
								</ul>
								<ul id="pegar-2">
								</ul>		
								</div>
								</div>

							</div>

							<div id='room-guests-3' style="display: none; height: 110px; position: relative; top: 0px;  width: 450px; float: left;">
							<ul id="titulo-room-guests-3" style="display: none;"><li><span><b>Habitación 3</b></span></li><li></li></ul>
							<ul  style="">
								<li valign='middle' style="float:left; padding-right: 50px;"><span>Adultos</span>
									<select name='room-guests[]' id="roomgues3" style="width: 150px;">
										<?php
									$i_min = 1;
									$i_max = 8;
									if(isset($this->userData->roomGuests)){
										$jhotelreservation_room_adult = $this->userData->roomGuests;
									}
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option <?php echo $jhotelreservation_room_adult[2]==$i ? " selected " : ""?> value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
										<span><?php echo JText::_('LNG_CHILDREN',true)?></span>
									<select name='room-guests-children[]' id='room-guests-children3' style="width: 150px;">
										<?php
									$i_min = 0;
									$i_max = 6;
									if(isset($this->userData->roomGuestsChildren)){
										$jhotelreservation_room_child = $this->userData->roomGuestsChildren;	
									}
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option <?php echo $jhotelreservation_room_child[2]==$i ? " selected " : ""?> value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								</li>
							<li>
								<div id='close-3' class='close' onclick='deleteRoom(3)'></div>
							</li>
							</ul>

								<div id="habilitar-edades-3" style="display: none; margin-top: 0px;">
									<div id="edades-room-3" style="display: none; margin: -3px; padding: 8px;">
								<ul>
									<li>Edades</li>
								</ul>
								<ul id="pegar-3">
								</ul>		
								</div>
								</div>

							</div>

							<div style="position: relative; top: 0px;  width: 450px; float: right;">
							<ul id="titulo-room-guests-4" style="display: none;"><li><span><b>Habitación 4</b></span></li><li></li></ul>
							<ul id='room-guests-4' style="display: none;">
								<li valign='middle' style="float:left; padding-right: 50px;"><span>Adultos</span>
									<select name='room-guests[]' id="roomgues4" style="width: 150px;">
										<?php
									$i_min = 1;
									$i_max = 8;
									if(isset($this->userData->roomGuests)){
										$jhotelreservation_room_adult = $this->userData->roomGuests;
									}
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option <?php echo $jhotelreservation_room_adult[3]==$i ? " selected " : ""?> value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
										<span><?php echo JText::_('LNG_CHILDREN',true)?></span>
									<select name='room-guests-children[]' id='room-guests-children4' style="width: 150px;">
										<?php
									$i_min = 0;
									$i_max = 6;
									if(isset($this->userData->roomGuestsChildren)){
										$jhotelreservation_room_child = $this->userData->roomGuestsChildren;	
									}
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option <?php echo $jhotelreservation_room_child[3]==$i ? " selected " : ""?> value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								</li>
							<li>
								<div id='close-4' class='close' onclick='deleteRoom(4)'></div>
							</li>
							</ul>

								<div id="habilitar-edades-4" style="display: none;">
									<div id="edades-room-4" style="display: none; margin: -3px; padding: 8px;">
								<ul>
									<li>Edades</li>
								</ul>
								<ul id="pegar-4">
								</ul>		
								</div>
								</div>

							</div>

							</div>
						</div>
						<!--////////////////////////////////////////////////////////////////////////-->
						<!--micod
						DIV en donde mostraremos todos los combos de las edades-->
						<div class="reservation-detail" id="edades" style="display: none; margin-top: -70px; margin-left: 500px;">
						<div id="label">Edad de los niños</div>
								<div id="edad-1" style="display: inline;">
								<select style="width: 50px;" id="edad-1" name="edad-1">
									<option value="<?php echo $edades[0]; ?>"> <?php echo $edades[0]; ?> </option>
									<option value="no"></option>
								<?php
									$i_min = 0;
									$i_max = 17;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									
								</select>
								</div>
								<div id="edad-2" style="display: none;">
								<select style="width: 50px;" id="edad-2" name="edad-2">
								<option value="<?php echo $edades[1]; ?>"> <?php echo $edades[1]; ?> </option>
									<option value="no"></option>
								<?php
									$i_min = 0;
									$i_max = 17;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									
								</select>
								</div>
								<div id="edad-3" style="display: none;">
								<select style="width: 50px;" id="edad-3" name="edad-3">
								<option value="<?php echo $edades[2]; ?>"> <?php echo $edades[2]; ?> </option>
									<option value="no"></option>
								<?php
									$i_min = 0;
									$i_max = 17;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									
								</select>
								</div>
								<div id="edad-4" style="display: none;">
								<select style="width: 50px;" id="edad-4" name="edad-4">
								<option value="<?php echo $edades[3]; ?>"> <?php echo $edades[3]; ?> </option>
									<option value="no"></option>
								<?php
									$i_min = 0;
									$i_max = 17;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									
								</select>
								</div>
								<div id="edad-5" style="display: none;">
								<select style="width: 50px;" id="edad-5" name="edad-5">
								<option value="<?php echo $edades[4]; ?>"> <?php echo $edades[4]; ?> </option>
									<option value="no"></option>
								<?php
									$i_min = 0;
									$i_max = 17;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									
								</select>
								</div>
								<div id="edad-6" style="display: none;">
								<select style="width: 50px;" id="edad-6" name="edad-6">
								<option value="<?php echo $edades[5]; ?>"> <?php echo $edades[5]; ?> </option>
									<option value="no"></option>
								<?php
									$i_min = 0;
									$i_max = 17;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									
								</select>
								</div>
						</div>
						<!--////////////////////////////////////////////////////////////////////////-->




			<div class="reservation-detail voucher">
				<!--<label for=""><?php echo JText::_('LNG_VOUCHER')?></label>
				<input type="text" value="<?php echo $this->userData->voucher ?>" name="voucher" id="voucher"/>-->
			</div>
			<div class="reservation-detail" style="margin-left: 87%; margin-top: -30px;">
				<label for="">&nbsp;</label>
				<!--<button onclick="enviar();" name="prueba" value="prueba">Enviar</button>-->
				<button class="ui-hotel-button ui-hotel-button-grey"	onClick	="checkRoomRates('searchForm');"
					type="button" name="checkRates" value="checkRates">Buscar<?php //echo JText::_('LNG_CHECK_AVAIL')?></button>
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
	//require_once JPATH_SITE.'/components/com_jhotelreservation/include/multipleroomselection.php';
?> 
