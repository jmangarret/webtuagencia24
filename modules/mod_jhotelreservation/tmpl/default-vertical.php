<?php // no direct access
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


defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.session.session' );

$appSettings = JHotelUtil::getInstance()->getApplicationSettings();
/*----------------------------------------------------------------*/
//MICOD
/*Aqui es donde reinicio el número de invitados por habitación
para que no se acumule junto a la siguiente búsqueda, con ésto 
le estoy asignando éste valor cada vez que se ingresa a la página*/
$userData->roomGuests = 0;
$userData->roomGuestsChildren = 0;
/*----------------------------------------------------------------*/
//dmp($userData->roomGuests);


//MICOD
//-------------------------------------------------------------
//Hago una instanciación del archivo que tiene el query de los datos de hoteles
//Luego creo un objeto de ésa clase y llamo a la función que ya está retornando los datos del query
include '..\helper.php';
$obj = new modJHotelReservationHelper;
$datos_hoteles = $obj->getHotelItems();
//-------------------------------------------------------------


$userData =  UserDataService::getUserData();
$app = JFactory::getApplication();
$menu = $app->getMenu();
$voucher=$userData->voucher;
if ($menu->getActive() == $menu->getDefault()) {
	$voucher='';
}


?>
<script>
	var dateFormat = "<?php echo $appSettings->dateFormat; ?>";
	var message = "<?php echo JText::_('LNG_ERROR_PERIOD',true)?>";
	var defaultEndDate = "<?php echo $params->get('end-date'); ?>";
	var defaultStartDate = "<?php echo $params->get('start-date'); ?>";

	
</script>

		<form action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&view=hotels') ?>" method="post" name="userModuleForm" id="userModuleForm" >
			<input type='hidden' name='task' value='hotels.searchHotels'/>
			<input type='hidden' name='year_start' value=''/>
			<input type='hidden' name='month_start' value=''/>
			<input type='hidden' name='day_start' value=''/>
			<input type='hidden' name='year_end' value=''/>
			<input type='hidden' name='month_end' value=''/>
			<input type='hidden' name='hotel_id' value=''/>
			<input type='hidden' name='day_end' value=''/>
			<input type='hidden' name='rooms' value='' />
			<input type='hidden' name='guest_adult' value=''/>
			<input type='hidden' name='guest_child' value=''/>
			<input type='hidden' name='filterParams' id="filterParams" value='<?php echo isset($userData->filterParams) ? $userData->filterParams :''?>' />
			<input type="hidden" name="resetSearch" id="resetSearch" value="true"/>
			<input type="hidden" name="searchType" id="searchType" value=""/>
			<input type="hidden" name="searchId" id="searchId" value=""/>
			<?php 
				if(isset($userData->roomGuests)){
					foreach($userData->roomGuests as $guestPerRoom){?>
					<input class="room-search" type="hidden" name='room-guests[]' value='<?php echo $guestPerRoom?>'/>
					<?php }
				}
			?>
			<?php 
				if(isset($userData->roomGuestsChildren)){
					foreach($userData->roomGuestsChildren as $guestPerRoom){?>
					<input class="room-search" type="hidden" name='room-guests-children[]' value='<?php echo $guestPerRoom?>'/>
					<?php }
				}
			?>
			<div class="mod_hotel_reservation<?php echo $moduleclass_sfx;?>" id="mod_hotel_reservation">
				<div class="reservation-container ">
				<!--micod
				Titulo original del buscador de hoteles-->
					<!--<h3><?php echo JText::_('LNG_FIND_BEST_HOTEL_DEAL',true)?></h3>-->
					<!--<ul id="ul-titulo-buscador">
					<li id="li-titulo-buscador">
					<h3 id="h3-titulo-buscador">Busca las mejores ofertas de hoteles</h3>
					</li>
					</ul>-->
					<?php 
					//micod
					//La variable $params no tiene datos cargados, eliminé la condición para mostrar el combo con loshoteles cargados
						/*if ($params->get('show-search')==1) {*/?>
						<div class="destination divider">
							<div class="search-nav">
							<div id="div_mapa" style="margin-left: 170px;"><a href="javascript:void(0)" id="show_hotels_map"><?php //echo JText::_('LNG_SHOW_HOTELS_MAP',true)?> Mostrar el Mapa de los Hoteles</a></div>
							<hr><br><br>
								<label>
								<!--micod-->
									<?php //echo JText::_('LNG_FIND_HOTEL',true);?>
									Elija el hotel en donde desea hospedarse.
								</label>
								
								<!--<input autocomplete="off" class="keyObserver inner-shadow" type="text" value="<?php echo $userData->keyword ?>" name="keyword" id="keyword" placeholder="<?php echo JText::_("LNG_TYPE_INSTRUCTIONS")?>"/>-->
									
								<!--micod-->
								<!--///////////////////////////////////////////////////////////////////////////////////////-->
								<center><select class="keyObserver inner-shadow" name="keyword" id="keyword" style="width:100%; margin-left: -3px;">
								<?php foreach ($datos_hoteles as $key => $value) {
									?>
										<option value="<?php echo $value->hotel_name; ?>"><?php echo $value->hotel_city."   -   ".$value->hotel_name."   -   ".$value->hotel_address; ?></option>
								<?php	
								}
								?>
								<option value="">Todos</option>
								<!--///////////////////////////////////////////////////////////////////////////////////////-->
								</select></center>
								
							</div>
						</div>
					<?php 
					//micod
					//cierre de la condición IF
					//}?>
					<div class="dates divider">
					<!--micod
					comentar éste div, obstruye en el estilo-->
						<!--<div class="row-fluid">-->
						<!--micod
						 estilo en general.css-->
							<div class="date span6" id="date_span6_llegada">
								<label><?php echo JText::_('LNG_ARIVAL',true)?></label>
								<?php
									echo JHTML::calendar(
															$jhotelreservation_datas,'jhotelreservation_datas','jhotelreservation_datas',$appSettings->calendarFormat, 
															array(
																	'class'		=>'date_hotelreservation keyObserver inner-shadow', 
																	'onchange'	=>' checkStartDate(this.value,defaultStartDate,defaultEndDate);
																				setDepartureDate(\'jhotelreservation_datae\',this.value); ',
																	'disableFunc' =>'disabledate',
																	'weekNumbers' =>false
																)
														);
	
								?>
							</div>
							<!--micod
						 estilo en general.css-->
							<div class="date span6" id="date_span6_partida">
								<label><?php echo JText::_('LNG_DEPARTURE',true)?></label>
								<?php
									echo JHTML::calendar($jhotelreservation_datae,'jhotelreservation_datae','jhotelreservation_datae',$appSettings->calendarFormat, array('class'=>'date_hotelreservation keyObserver inner-shadow','onchange'=>'checkEndDate(this.value,defaultStartDate,defaultEndDate);'));
	
								?>
							</div>
							<!--micod
							cierre del div que obstruye con el estilo-->
						<!--</div>-->
						<!--<div class="no-dates">
							<input type="checkbox" name="no-dates" id="no-dates" value="1" <?php echo isset($userData->noDates) && $userData->noDates!=0?"checked='checked'":"" ?>/>
							 <label for="no-dates"><?php echo JText::_('LNG_NO_DATES')?></label>
						</div>-->
						<div class="clear"></div>
					</div><br><br>

					<div class="rooms divider row-fluid" id="div_combos">
						<div class="span4">
							<!--micod-->
								<!--Eliminamos el enlace que distribuye las habitaciones por personas-->
								<!--<label for=""><a id="" href="javascript:void(0);" onclick="showExpandedSearch()"><?php echo JText::_('LNG_ROOMS')?></a></label>-->
								<label for="" id="label">Habitaciones</label>
								<div class="styled-select_search">
								<select id='jhotelreservation_rooms' name='jhotelreservation_rooms'
										class		= 'select_hotelreservation keyObserver inner-shadow'
									>
										<?php
									$i_min = 1;
									$i_max = $params->get("max-rooms");
									if(!isset($i_max))
										$i_max= 4;//micod máximo 4 habitaciones
									
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

						<div class="span4" style="Display: inline;" id="huespedes" name="huespedes">
						<!--micod
						Eliminar "Invitado" y cambiarlo a "Adultos"-->
							<!--<label><?php echo JText::_('LNG_GUEST',true)?></label>-->
							<label id="label">Adultos</label>
							<div class="styled-select_search">
								<select name='jhotelreservation_guest_adult' id='jhotelreservation_guest_adult'
									class		= 'select_hotelreservation keyObserver inner-shadow' 
								>
									<?php
									$i_min = 1;
									$i_max = 8;
									//micod
									//éste código no funciona en éste archivo, pero en el "default-horizontal" si funciona, averiguar por qué...
									//existe un problema con éste combo, al seleccionar un valor, se eliminan los valores que son más altos en la próxima búsqueda.
									//es decir, si seleccionamos 1 adulto en el campo, en la próxima búsqueda sólo estará disponible ése valor, los demas desaparecen...
									//$i_max = $params->get("max-room-guests");
									//dmp($jhotelreservation_guest_adult);

									if($jhotelreservation_guest_adult>$i_max)
										$i_max = $jhotelreservation_guest_adult;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
										//dmp($i);
									?>
									<option value='<?php echo $i?>'  <?php echo $jhotelreservation_guest_adult==$i ? " selected " : ""?>><?php echo $i?></option>
									<?php
									}
									?>
								</select>
							</div>
						</div>
				
						
						<div class="span4" style="<?php echo $appSettings->show_children!=0 ? "":"display:none" ?>" style="Display: inline;" id="huespedes2" name="huespedes2">
							<label id="label"><?php echo JText::_('LNG_CHILDREN',true)?></label>
							<div class="styled-select_search">
								<select name='jhotelreservation_guest_child' id='jhotelreservation_guest_child'
								class		= 'select_hotelreservation'  
								>
									<?php
									$i_min = 0;
									$i_max = 6;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option <?php echo $jhotelreservation_guest_child==$i ? " selected " : ""?> value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
							</div>
						</div>


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






									//Inicio: Sección de las edades de los niños
							            if($('select[name=jhotelreservation_guest_child]').val() == 0){
											$("#edades").css("display","none");
										}else if($('select[name=jhotelreservation_guest_child]').val() == 1){
											for (var i = 2	; i <= 4; i++) {
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
							$('#pegar-'+num).children().remove(); //Importante. Remueve los combos de las edades a medida que se eligen, evita una sumatoria constante de <select>

							var selectage=""; //Vaciar <option> antes de cargarlas
							<?php
							$i_min = 0;
							$i_max = 17;
							
							for($i=$i_min; $i<=$i_max; $i++)
							{
							?>
								selectage+="<option <?php echo $i==0?'selected':''?> value='<?php echo $i?>'><?php echo $i?></option>";
							<?php
							}
							?>
							if (child>=1){
								$("#edades-room-"+num).css("display","block");
							for(i=1;i<=child;i++){

								var age = "<li valign='middle' style='margin: 5px;'><select name='edad-"+num+"-"+i+"' style='width: 50px;'>"+selectage+"</select></li>";
								if($("#room-guests-children"+num).val()>=1){ 
								$("#pegar-"+num).append(age);

								}
								
							}
							}else if(child==0){
									$(".room-search").remove();
								$("#edades-room-"+num).css("display","none");
								age = "";
							}

							$('#edades-room-'+num+' ul').each(function(index) {
							   $(this).removeClass("last");
							});
							
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
							<table id="rooms-container" cellpadding="4" style="display: block; width: 104%; border-collapse: separate; border-spacing: 5px; background-color: #FFFAA5; border-radius: 5px 5px;">
							<tr id="titulo-room-guests-1" style="display: none;"><td><span><b>Habitación 1</b></span></td><td></td></tr>
							<tr id='room-guests-1' style="display: none;">
								<td><span>Adultos</span></td><td valign='middle'>
									<select name='room-guests[]'  id="roomgues1" style="width: 150px;">
										<?php
									$i_min = 1;
									$i_max = 8;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								</td>

								<td><span><?php echo JText::_('LNG_CHILDREN',true)?></span></td><td valign='middle'>
									<select name='room-guests-children[]' id='room-guests-children1' style="width: 150px;">
										<?php
									$i_min = 0;
									$i_max = 6;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								</td>
							<td>
								<div id='close-1' class='close' onclick='deleteRoom(1)'></div>
							</td>
							</tr>
							<tr id="habilitar-edades-1" style="display: none;">
							<td>
								<div id="edades-room-1" style="display: none; margin: -3px; background-color: #FFFAA5; border-radius: 5px 5px; padding: 8px; border: solid; border-color: #fff110">
							<ul>
								<li>Edades</li>
							</ul>
							<ul id="pegar-1">
							</ul>		
						</div>
						</td>
							</tr>
								<tr id="titulo-room-guests-2" style="display: none;"><td><span><b>Habitación 2</b></span></td><td></td></tr>
								<tr id='room-guests-2' style="display: none;">
								<td><span>Adultos</span></td><td valign='middle'>
									<select name='room-guests[]' id="roomgues2" style="width: 150px;">
										<?php
									$i_min = 1;
									$i_max = 8;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								</td>

								<td><span><?php echo JText::_('LNG_CHILDREN',true)?></span></td><td valign='middle'>
									<select name='room-guests-children[]' id='room-guests-children2' style="width: 150px;">
										<?php
									$i_min = 0;
									$i_max = 6;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								</td>
							<td>
								<div id='close-2' class='close' onclick='deleteRoom(2)'></div>
							</td>
							</tr>
							<tr id="habilitar-edades-2" style="display: none;">
							<td>
								<div id="edades-room-2" style="display: none; margin: -3px; background-color: #FFFAA5; border-radius: 5px 5px; padding: 8px; border: solid; border-color: #fff110">
							<ul>
								<li>Edades</li>
							</ul>
							<ul id="pegar-2">
							</ul>		
						</div>
						</td>
							</tr>
							<tr id="titulo-room-guests-3" style="display: none;"><td><span><b>Habitación 3</b></span></td><td></td></tr>
							<tr id='room-guests-3' style="display: none;">
								<td><span>Adultos</span></td><td valign='middle'>
									<select name='room-guests[]' id="roomgues3" style="width: 150px;">
										<?php
									$i_min = 1;
									$i_max = 8;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								</td>

								<td><span><?php echo JText::_('LNG_CHILDREN',true)?></span></td><td valign='middle'>
									<select name='room-guests-children[]' id='room-guests-children3' style="width: 150px;">
										<?php
									$i_min = 0;
									$i_max = 6;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								</td>
							<td>
								<div id='close-3' class='close' onclick='deleteRoom(3)'></div>
							</td>
							</tr>
							<tr id="habilitar-edades-3" style="display: none;">
							<td>
								<div id="edades-room-3" style="display: none; margin: -3px; background-color: #FFFAA5; border-radius: 5px 5px; padding: 8px; border: solid; border-color: #fff110">
							<ul>
								<li>Edades</li>
							</ul>
							<ul id="pegar-3">
							</ul>		
						</div>
						</td>
							</tr>
							<tr id="titulo-room-guests-4" style="display: none;"><td><span><b>Habitación 4</b></span></td><td></td></tr>
							<tr id='room-guests-4' style="display: none;">
								<td><span>Adultos</span></td><td valign='middle'>
									<select name='room-guests[]' id="roomgues4" style="width: 150px;">
										<?php
									$i_min = 1;
									$i_max = 8;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								</td>

								<td><span><?php echo JText::_('LNG_CHILDREN',true)?></span></td><td valign='middle'>
									<select name='room-guests-children[]' id='room-guests-children4' style="width: 150px;">
										<?php
									$i_min = 0;
									$i_max = 6;
									
									for($i=$i_min; $i<=$i_max; $i++)
									{
									?>
										<option value='<?php echo $i?>'  ><?php echo $i?></option>
										<?php
										}
										?>
									</select>
								</td>
							<td>
								<div id='close-4' class='close' onclick='deleteRoom(4)'></div>
							</td>
							</tr>
							<tr id="habilitar-edades-4" style="display: none;">
							<td>
								<div id="edades-room-4" style="display: none; margin: -3px; background-color: #FFFAA5; border-radius: 5px 5px; padding: 8px; border: solid; border-color: #fff110">
							<ul>
								<li>Edades</li>
							</ul>
							<ul id="pegar-4">
							</ul>		
						</div>
						</td>
							</tr>
							</table>
						</div>

						<!--////////////////////////////////////////////////////////////////////////-->
						<!--MICOD
						DIV en donde mostraremos todos los combos de las edades-->
						<div id="edades" style="position: relative; left: 40px; padding: 10px; display: none;">
						    <div id="label" style="padding: 20px;"><lablel style="position: relative; bottom: -10px; left: -40px;">Edad de los niños</lablel></div>
								<div id="contener">
								<div id="edad-1" style="display: inline;">
								<select  style="width: 120px;" id="edad-1" name="edad-1">
									<option value="Edad"></option>
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
								<select  style="width: 120px;" id="edad-2" name="edad-2">
									<option value="Edad"></option>
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
								<select  style="width: 120px;" id="edad-3" name="edad-3">
									<option value="Edad"></option>
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
								<select  style="width: 120px;" id="edad-4" name="edad-4">
									<option value="Edad"></option>
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
								<select  style="width: 120px;" id="edad-5" name="edad-5">
									<option value="Edad"></option>
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
								<select  style="width: 120px;" id="edad-6" name="edad-6">
									<option value="Edad"></option>
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
						</div>
						<!--////////////////////////////////////////////////////////////////////////-->



					</div>

					<hr>
					<?php if ($params->get('show-voucher')==1){?>
						<div class="voucher divider">
							<label><?php echo JText::_('LNG_VOUCHER',true)?></label>
							<input type="text" class="keyObserver inner-shadow" value="<?php echo $voucher ?>" name="voucher" id="voucher" />
						</div>
					<?php } ?>
					<!--micod
					cambiar el nombre de ID para no confundirlo con el otro buscador de hoteles. Nombre original (div_del_boton_verificar)-->
					<div id="div_del_boton_verificar_mini">
						<button	class="ui-hotel-button"  id="boton_buscar_mini" onClick	= "jQuery('#resetSearch').val(1); checkRoomRates('userModuleForm'); showLoadingAnimation()"
							type="button" name="checkRates" value="checkRates"><?php //echo JText::_('LNG_SEARCH',true)?> Buscar
						</button>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			
			<?php 
			if($params->get("show-filter")){
				$filter = $userData->searchFilter;
				$filterCategories= $filter["filterCategories"];
				
				$showFilter = JRequest::getVar('showFilter');
				if(count($filterCategories)>0 && isset($showFilter)){?>
					<div id="search-filter" class="seach-filter moduletable module-menu" >
					<div>
						<div>
						<h3><?php echo JText::_('LNG_SEARCH_FILTER',true)?></h3>
						<?php 
							foreach ($filterCategories as $filterCategory){
								echo '<div class="search-category-box">';
								echo '<h4>'.$filterCategory['name'].'</h4>';
								echo '<ul>';
								foreach ($filterCategory['items'] as $filterCategoryItem){
									if(isset($filterCategoryItem->count)){
								?>	
									<li <?php if(isset($filterCategoryItem->selected)) echo 'class="selectedlink"';  ?> >  	 										
										<a href="javascript:void(0)" onclick="<?php if(isset($filterCategoryItem->selected)) echo "removeFilterRule('$filterCategoryItem->identifier=$filterCategoryItem->id')"; else echo "addFilterRule('$filterCategoryItem->identifier=$filterCategoryItem->id')";?>"><?php echo $filterCategoryItem->name ?> <?php echo '('.$filterCategoryItem->count.')' ?> <?php if(isset($filterCategoryItem->selected)) echo '<span class="cross">(remove)</span>';  ?></a>
									</li>
								<?php
									} 
								}
								echo '</ul>';
								echo '</div>';
								
							}
						?>
						</div>
						</div>
					</div>
			<?php
				 }
			}
			 ?>
		</form>
		<script>
			jQuery(document).ready(function(){
				jQuery(".keyObserver").keypress( function(e){
					if(e.which == 13) {
						checkRoomRates('userModuleForm');
						showLoadingAnimation();
					}
				});

				jQuery("#jhotelreservation_datas").click(function(){
					 jQuery("#jhotelreservation_datas_img").click();
				});

				jQuery("#jhotelreservation_datae").click(function(){
					 jQuery("#jhotelreservation_datae_img").click();
				});

				jQuery("#show_hotels_map").click(function(){
					jQuery.blockUI({ message: jQuery('#hotel-map-container'), css: {
						top:  100 + 'px', 
			            left: (jQuery(window).width() - 850) /2 + 'px',
						width: '850px', 
						backgroundColor: '#fff' }});
					
						jQuery('.blockUI').click(function(){
							//jQuery.unblockUI();
						});
				});
			});			
		</script>
		<?php //require_once JPATH_SITE.'/components/com_jhotelreservation/include/multipleroomselection.php'; ?> 
		<?php require_once 'hotel-map.php'; ?> 
		<?php require_once 'autocomplete.php'; ?>
		<?php require_once 'loading-info.php'; ?>  
		