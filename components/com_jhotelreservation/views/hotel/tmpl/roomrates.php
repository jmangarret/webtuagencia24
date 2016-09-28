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

defined('_JEXEC') or die('Restricted access');
JHTML::_('script', 							'components/com_jhotelreservation/assets/js/search.js');
$userData =  $_SESSION['userData'];
//$max_package_number = 0;

/*------------------------------------------------------------------------------------------------------*/
/*MICOD
Consulta desde la base de datos Joomla*/

//Clases, archivos y definiciones necesarias para conectar a la base de datos de Joomla
          define( '_JEXEC', 1 );
          define('JPATH_BASE', dirname(__FILE__) );
          define( 'DS', DIRECTORY_SEPARATOR );
          require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
          require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
          $mainframe =& JFactory::getApplication('site');

//Código para consultar cualquier dato de la base de datos de Joomla
		  	$db	=& JFactory::getDBO();//Obtener base de datos
		  	$hotel=$this->hotel->hotel_id;//Obtener el ID del Hotel
			$query = "SELECT * FROM #__hotelreservation_hotel_informations 
			WHERE hotel_id =".$hotel." ORDER BY hotel_id"; //Generar el Query del tipo SELECT
			$db->setQuery($query);//Ejecutamos el Query
			$result = $db->loadObjectList();//Obentemos los resultados en un arreglo
		    $datos  = $result[0];//Asignamos el arreglo a una variable más cómoda
		    
/*------------------------------------------------------------------------------------------------------*/

			$niños_libre_tarifas = explode("|", $datos->niños_libre_tarifas);
			//dmp($niños_libre_tarifas);
			$niños_tarifa_ajustada = explode("|", $datos->niños_tarifa_ajustada);
			//dmp($niños_tarifa_ajustada);
			$edad_tarifa_adult = explode("|", $datos->edad_tarifa_adult);
			//dmp($edad_tarifa_adult);   
/*------------------------------------------------------------------------------------------------------*/
?>
<div class="reservation-info-container">
	<div class="reservation-info-container-outer">
		<div class="reservation-info-container-inner">
			<div class="choose-room" style="font-size: 20px;" id="elegir_room">
				<?php
				//$this->_models['variables']->getReservedItems() < $this->_models['variables']->rooms
						if(  $this->userData->rooms > 1)
						{
							echo (isset($this->hotel->types) & $this->hotel->types[0]->id == PARK_TYPE_ID ?JText::_('LNG_CHOOSE_YOUR_PARK',true) : JText::_('LNG_CHOOSE_YOUR_ROOM',true)) ."&nbsp;(";
							echo count($this->userData->reservedItems) +1 ;
							echo "&nbsp;".JText::_('LNG_OF',true) ." ";
							echo $this->userData->rooms.") ";
							echo JText::_('LNG_ADULTS').":".$this->userData->roomGuests[count($this->userData->reservedItems)];
							if($this->appSettings->show_children){
								echo " ".JText::_('LNG_CHILDREN').":".$this->userData->roomGuestsChildren[count($this->userData->reservedItems)];
							}
						}else{
						    //echo JText::_('LNG_AVAILABLE_ROOMS',true);
						}
				?>
			</div>
			<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
			<!--MICOD
			Lugar donde muestro en pantalla las diferentes condiciones de tarifas de los niños según su edad-->
			<?php
			if (($niños_libre_tarifas[2] == 1) || ($niños_tarifa_ajustada[4] == 1) || ($edad_tarifa_adult[1] == 1)){ //Si alguna de las configuraciones está activa
			?>
			<div style="font-size: 14px; margin-bottom: 5px; margin-top: -5px; padding-top: 5px;"  id="info-tarifa-ninos">
			<div> <!--MICOD agregado nueva ID-->

				<?php
				if ($niños_libre_tarifas[2] == 1){ //Libre de Tarifas
				?>
				<div>
					<span style="text-transform: uppercase; font-weight: bold;">Niños de <?= $niños_libre_tarifas[0] ?> a <?= $niños_libre_tarifas[1] ?> años.</span><span> Libres de tarifas.</span>
				</div>
				<?php
				}

				if ($niños_tarifa_ajustada[4] == 1){ //Tarifas Ajustadas
					$niño_tarifa = number_format($niños_tarifa_ajustada[2], 2);
				?>
				<div>
					<span style="text-transform: uppercase; font-weight: bold;">Niños de <?= $niños_tarifa_ajustada[0] ?> a <?= $niños_tarifa_ajustada[1] ?> años.</span><span> 
					<?php if (($niños_tarifa_ajustada[2] == "") || ($niños_tarifa_ajustada[2] == "0")){ echo "Se cobrará la tarifa establecida por cada habitación.";}else{
						if ($niños_tarifa_ajustada[3] == 1){ echo "Se cobrará el ".$niños_tarifa_ajustada[2]."% de la tarifa del adulto por habitación."; }else{
						echo "Se cobrará una tarifa fija de ".$niño_tarifa."Bs.F";
						}
					}
					  ?></span>
				</div>
				<?php
				}
				if ($edad_tarifa_adult[1] == 1){ //Tarifas de Adulto
				?>
				<div>
					<span style="text-transform: uppercase; font-weight: bold;">Niños a partir de <?= $edad_tarifa_adult[0] ?> años.</span><span> Se le cobrará la tarifa de adulto según la habitación.</span>
				</div>
				<?php
				}
				?>

			</div>
			<?php
			}
			?>
			<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
			<div> <!--MICOD agregado nueva ID-->
			
				<?php echo ucfirst(isset($this->hotel->types) & $this->hotel->types[0]->id == PARK_TYPE_ID ?JText::_('LNG_AVAILABLE_PARKS',true) : JText::_('LNG_AVAILABLE_ROOMS',true)." ".JText::_('LNG_FROM',true)) ?>
					
				<?php
					$data_1 = $this->userData->year_start.'-'.$this->userData->month_start.'-'.$this->userData->day_start;
				?>
				<strong>
				<?php 	
					echo JHotelUtil::getDateGeneralFormat($data_1);
				?>
				</strong>
				<?php 
					//echo date( 'l, F d, Y', strtotime( $this->userData->year_start.'-'.$this->userData->month_start.'-'.$this->userData->day_start ) )
					echo JText::_('LNG_TO',true);
				?>
				<strong>
				<?php 
					$data_2 = $this->userData->year_end.'-'.$this->userData->month_end.'-'.$this->userData->day_end;
					echo JHotelUtil::getDateGeneralFormat($data_2);
				?>
				</strong>
				<?php  //echo ", ".JText::_('LNG_FOR',true)." ".(isset($this->userData->roomGuests)?$this->userData->roomGuests[$this->_models['variables']->getReservedItems()]:$this->userData->guest_adult).' '.strtolower(JText::_('LNG_ADULT_S',true)) ?>
						
			</div>
			</div>
		</div>
	</div>
</div>

		<?php
			if( JRequest::getVar( 'infoCheckAvalability') != '' )	
			{
		?>
			<div class="alert_message" style="color: #FF0000; margin-top: 15px;">
					<?php echo JRequest::getVar('infoCheckAvalability') ?>
			</div>	
			<?php
			}else{
				require_once JPATH_COMPONENT_SITE.DS.'include'.DS.'roomratesf.php'; 
			}			
			?>
