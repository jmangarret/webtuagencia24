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
?>

<div class='reservationinfo<?php echo $moduleclass_sfx ?>' >
	<h3><?php echo JText::_("LNG_RESERVATION_INFO") ?></h3>
	<div>
		<a href="<?php echo JHotelUtil::getHotelLink($hotel) ?>">
			<img class="hotelimage" 
				src='<?php if(isset($hotel->pictures[0])) echo JURI::root() .PATH_PICTURES.$hotel->pictures[0]->hotel_picture_path?>' 
			/>
		</a>
	</div>
	<div class="hotelinfo">
		<div class="hotelcontent">								
			<div class="hoteltitle">
				<h2 >
					<a href="<?php echo JHotelUtil::getHotelLink($hotel) ?>">
						<?php echo stripslashes($hotel->hotel_name) ?>
					</a> 
				</h2>
				<span class="hotelstars">
					<?php
					for ($i=1;$i<=$hotel->hotel_stars;$i++){ ?>
						<img  src='<?php echo JURI::root() ."administrator/components/".getBookingExtName()."/assets/img/star.png" ?>' />
					<?php } ?>
				</span>
			</div>
			
			<div class="hoteladdress">
				<?php echo $hotel->hotel_address?>, <?php echo $hotel->hotel_city?>, <?php echo $hotel->hotel_county?>, <?php echo $hotel->country_name?>
			</div>
			
			<div class="clear"></div>
			
			<div class="reservation-description">
				<table>
					<tr>
						<td width="85">
							<strong><?php echo JText::_('LNG_ARIVAL',true) ?></strong>
						</td>
						<td>
						<?php
							$data_1 = strtotime( $userData->year_start.'-'.$userData->month_start.'-'.$userData->day_start );
							
							echo JText::_( substr(strtoupper(date( 'l', $data_1)),0,3)). ' ';
							echo date( ' d', $data_1 ) .' ';
							echo JText::_( strtoupper(date( 'F', $data_1))) . ', ';
							echo date( 'Y', $data_1 );
							//echo date( 'l, F d, Y', strtotime( $this->_models['variables']->year_start.'-'.$this->_models['variables']->month_start.'-'.$this->_models['variables']->day_start ) )
						?> 
						</td>
					</tr>
					<tr>
						<td>
							<strong><?php echo JText::_('LNG_DEPARTURE',true) ?></strong>
						</td>
						<td>
						<?php
							$data_2 = strtotime( $userData->year_end.'-'.$userData->month_end.'-'.$userData->day_end );
							echo JText::_( substr(strtoupper(date( 'l', $data_2)),0,3)). ' ';
							echo date( ' d', $data_2 ) .' ';
							echo JText::_( strtoupper(date( 'F', $data_2))) . ', ';
							echo date( 'Y', $data_2 );
							//echo date( 'l, F d, Y', strtotime( $this->_models['variables']->year_start.'-'.$this->_models['variables']->month_start.'-'.$this->_models['variables']->day_start ) )
						?> 
						</td>
					</tr>
					<tr>
						<td>
							<strong><?php echo JText::_('LNG_ADULT_S',true) ?></strong>
						</td>
						<td>
							<?php echo $userData->total_adults > 0? $userData->total_adults.' '.JText::_('LNG_ADULT_S',true) : ""?>
							<?php echo $userData->children > 0?  $userData->children.' '.JText::_('LNG_CHILD_S',true) : ""?>
						</td>
					</tr>
					<tr>
						<td>
							<strong><?php echo JText::_('LNG_ROOMS',true) ?></strong>
						</td>
						<td>
							<?php echo $userData->rooms ?> 
						</td>
					</tr>
				</table>
			</div>
			<br/>
			<div class="reservationinfo-details">
				<table >
					<?php if (!empty($reservationDetails->roomsInfo)){?>
						<?php foreach($reservationDetails->roomsInfo as $roomInfo){?>
							<tr>
								<td>
									<?php echo $roomInfo->name ?>
								</td>
								<td class="price">
									<?php echo $hotel->currency_symbol.' '.JHotelUtil::fmt($roomInfo->roomPrice,2) ?>
								</td>
							</tr>
						<?php }?>
					<?php }?>
					<?php if (!empty($reservationDetails->extraOptions)){?> 
						<?php foreach($reservationDetails->extraOptionsInfo as $extraOptionDetails){?>
							<?php foreach($extraOptionDetails->details as $extraOption){?>
							<tr>
								<td>
									<?php echo $extraOption->name ?>
								</td>
								<td class="price">
									<?php echo $hotel->currency_symbol.' '.JHotelUtil::fmt($extraOption->amount,2) ?>
								</td>
							</tr>
							<?php }?>
						<?php }?>
					<?php }?>
				</table>
				<table id="rooms-info">
				</table>
			</div>
			
			<div class="cost-info">
				<table>
					<tr>
						<td>
							<?php echo JText::_('LNG_ESTIMATED_SUBTOTAL')?>
						</td>
						<td class="price">
							<?php echo $hotel->currency_symbol ?> <span id="info-subtotal"><?php echo JHotelUtil::fmt(($reservationDetails->total - $reservationDetails->costData->costV),2) ?></span>
						</td>
					</tr>
					<tr>
						<td>
							
							<?php echo JText::_('LNG_COST_VALUE')?>
						</td>
						<td class="price">
							<?php echo $hotel->currency_symbol ?> <span id="info-cost"><?php echo JHotelUtil::fmt($reservationDetails->costData->costV,2) ?></span>
						</td>
					</tr>
					<tr>
						<td>
							<strong><?php echo JText::_('LNG_ESTIMATED_TOTAL')?></strong>
						</td>
						<td class="price">
							<?php echo $hotel->currency_symbol ?> <span id="info-total"><?php echo JHotelUtil::fmt($reservationDetails->total,2) ?></span>
						</td>
					</tr>
				</table>
			</div>
			
		</div>
		<div class="clear"></div>
	</div>
</div>

<script>
var reservationSubtotalI=<?php echo  ($reservationDetails->total - $reservationDetails->costData->costV) ?>;
var reservationCostI=<?php echo $reservationDetails->costData->costV ?>;
var reservationTotalI=<?php echo $reservationDetails->total?>;
</script>