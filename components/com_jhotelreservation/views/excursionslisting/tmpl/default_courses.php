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
// dmp($this->_models);
$need_all_fields = true;
if( isset($this->_models['search'] ) && isset($this->_models['search']->tmp ) )
{
	if( strlen( $this->_models['search']->tmp ) > 0 )
		$need_all_fields = $this->_models['search']->tmp > strtotime( " - 10 min ")? false : true; //allow only 10 min for admin
}
if( isset($this->userData->searchFilter) )
	$searchFilter= $this->userData->searchFilter;
if( isset($this->userData->orderBy) )
	$orderBy = $this->userData->orderBy;
else	
	$orderBy = '';

?>

<script>

jQuery(document).ready(function(){
	if(jQuery('.trigger').length > 0) 
	{
		jQuery('.trigger').click(function() 
		{
			if (jQuery(this).hasClass('open')) 
			{
				jQuery(this).removeClass('open');
				jQuery(this).addClass('close');
				jQuery(this).parent().parent().parent().children('.hotel-details').children('.cnt').slideDown(100);
				jQuery(this).children('.room_expand').addClass('expanded');
				jQuery(this).children('.link_more').html('&nbsp;<?php echo JText::_('LNG_LESS',true)?> »');
				return false;
			} else {
				jQuery(this).removeClass('close');
				jQuery(this).addClass('open');
				jQuery(this).parent().parent().parent().children('.hotel-details').children('.cnt').slideUp(100);
				jQuery(this).children('.room_expand').removeClass('expanded');
				jQuery(this).children('.link_more').html('&nbsp;<?php echo JText::_('LNG_MORE',true)?> »');
				return false;
			}			
		});
		

	jQuery('.show-availability').click(function() 
				{
					if (jQuery(this).hasClass('open')) 
					{
						jQuery(this).removeClass('open');
						jQuery(this).addClass('close');
						jQuery('.room-availabity').slideDown(100);
						jQuery(this).children('.show-text').html('&nbsp;<?php echo JText::_('LNG_HIDE',true)?>');
						return false;
					} else {
						jQuery(this).removeClass('close');
						jQuery(this).addClass('open');
						jQuery('.room-availabity').slideUp(100);
						jQuery(this).children('.show-text').html('&nbsp;<?php echo JText::_('LNG_SHOW',true)?>');
						return false;
					}			
				});	

	}
	showExcursionsCalendars();
});	
	
</script>
<?php  if (is_array($this->userData->excursions) && count($this->userData->excursions)==0) {?>
	<div class="clear"> </div>
	<div class="reservation-details-holder">
		<h3> <?php echo JText::_('LNG_SEARCH')?>:</h3>
		<form action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&view=excursionslisting') ?>" method="post" name="searchForm" id="searchForm">
			<input type='hidden' name='resetSearch' value='true'>
			<input type='hidden' name='task' value='excursionslisting.searchExcursions'>
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
			<input type='hidden' name='jhotelreservation_rooms' value=''>
			
			
			<?php 
				if(isset($this->userData->roomGuests )){
					foreach($this->userData->roomGuests as $guestPerRoom){?>
						<input class="room-search" type="hidden" name='room-guests[]' value='<?php echo $guestPerRoom?>'/>
					<?php }
				}
				/*if(isset($this->userData->roomGuestsChildren )){
					foreach($this->userData->roomGuestsChildren as $guestPerRoomC){?>
							<input class="room-search" type="hidden" name='room-guests-children[]' value='<?php echo $guestPerRoomC?>'/>
						<?php }
					}*/
			?>
			<div class="reservation-details">
				<div class="reservation-detail">
					<label for=""><?php echo JText::_('LNG_ARIVAL')?></label>
					<?php
						
											echo JHTML::calendar(
																	$this->userData->start_date,'jhotelreservation_datas','jhotelreservation_datas2',$this->appSettings->calendarFormat, 
																	array(
																			'class'		=>'date_hotelreservation', 
																			'minDate'		=>'0',
																			'onchange'	=>
																						"
																							if(!checkStartDate(this.value, '',''))
																								return false;
																							setDepartureDate('jhotelreservation_datae2',this.value);
																						"
																		)
																);
			
										?>
					
				</div>
				<div class="reservation-detail">
					<label for=""><?php echo JText::_('LNG_DEPARTURE')?></label>
					<?php
						
						echo JHTML::calendar($this->userData->end_date,'jhotelreservation_datae','jhotelreservation_datae2', $this->appSettings->calendarFormat, array('class'=>'date_hotelreservation','onchange'	=>'checkEndDate(this.value,defaultStartDate,defaultEndDate);'));
					?>
				</div>
				
			
				<div class="reservation-detail">
					<label for=""><?php echo JText::_('LNG_ADULTS_19')?></label>
					<select name='jhotelreservation_guest_adult' id='jhotelreservation_guest_adult'
						class		= 'select_hotelreservation'
					>
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
				<div class="reservation-detail" style="<?php echo $this->appSettings->show_children==110 ? "":"display:none" ?>">
					<label for=""><?php //echo JText::_('LNG_CHILDREN_0_18')?> Niños(2-11)</label>
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
				<!-- <div class="reservation-detail voucher">
					<label for=""><?php echo JText::_('LNG_VOUCHER')?></label>
					<input type="text" value="<?php echo $this->userData->voucher ?>" name="voucher" id="voucher" size="15"/>
				</div> -->
				<div class="reservation-detail">
					<span class="button button-green">
						<button	onClick		=	"checkRoomRates('searchForm');"
							type="button" name="checkRates" value="checkRates"><?php echo JText::_('LNG_CHECK')?></button>
					</span>
				</div>
				<div class="clear"></div>
				
			</div>
		</form>
	</div>
<?php }
 else {
?>
			<font size="4px">
				<strong><?php  echo JText::_('LNG_ADD_COURSES')?>:</strong>
			</font>
			<span class=""><?php echo strtolower(JHotelUtil::getDateGeneralFormat($this->userData->start_date)).' '.JText::_('LNG_TO',true).' '.strtolower(JHotelUtil::getDateGeneralFormat($this->userData->end_date)).', '.JText::_('LNG_NUMBER_OF',true).' '.strtolower(JText::_('LNG_ADULTS',true)).': '. $this->userData->adults; ?>  </span>
<?php }?>

	<form action="<?php echo JRoute::_('index.php') ?>" method="post" name="userForm" id="userForm">
		<div class="hotel-search-list">
			<?php
			if(count($this->excursions)>0){
			foreach( $this->excursions as $excursion )
			{
				//var_dump($excursion);
			?>
					<div class="hotel-info row-fluid ">
						<div class="hotel-image-holder span3">
								<img class="hotel-image" 
									src='<?php echo isset($excursion->pictures[0]->picture_path)?JURI::root().PATH_PICTURES.$excursion->pictures[0]->picture_path:"";?>'
									alt="<?php echo isset($excursion->pictures[0]->picture_info)?$excursion->pictures[0]->picture_info:''; ?>" 
								/>
						</div>
						
						<div class="hotel-content span5">								
							<div class="hotel-title">
								<h2>
									<?php echo stripslashes($excursion->excursion_name) ?>
								</h2>
							</div>
							
							<div class="hotel-address">
								<?php echo $excursion->hotel_name?> <br>
								<?php echo $excursion->hotel_address?>, <?php echo $excursion->hotel_city?>, <?php echo $excursion->hotel_county?>, <?php echo $excursion->country_name?>
							</div>
							
							<div class="clear"></div>
							<div class="hotel-description ">
								<div id="excursionDescription<?php echo $excursion->excursion_id?>">
								<?php 
									$excursionDescription = $excursion->excursion_main_description;
									echo $excursionDescription;
								?>	
								
									<div class='picture-container'>
										<?php 
										if( isset($excursion->pictures) && count($excursion->pictures) >0 )
										{
											foreach( $excursion->pictures as $picture )
											{
										?>
											<a class="preview" onclick="return false;" title="<?php echo isset($picture->picture_info)?$picture->picture_info:'' ?>" alt="<?php echo isset($picture->picture_info)?$picture->picture_info:'' ?>" href="<?php echo JURI::base() .PATH_PICTURES.$picture->picture_path?>">
												<img 
														class="img_picture"
														style="height: 50px"
														src='<?php echo JURI::base() .PATH_PICTURES.$picture->picture_path?>' 
														alt="<?php echo isset($picture->picture_info)?$picture->picture_info:'' ?>"
														title="<?php echo isset($picture->picture_info)?$picture->picture_info:'' ?>"
												/>
											</a>
										<?php
										}
										}
										?>
								</div> 
								</div>
							</div>

						</div>
						
						<div class="hotel-details span4">
							
							<div class="hotel-price">
								<span class="details"><?php  echo JText::_('LNG_PRICE').": ".$excursion->currency_symbol.$excursion->pers_total_price;?> </span>
								<br/>
								<span class="details"><?php  echo JText::_('LNG_AVAILABLE').": ".$excursion->capacity;?> </span>
								<span><br> </span>
								
								<div class="view-hotel">
									<a href="javascript:void(0);" onclick="bookItems()"><?php  echo JText::_('LNG_BOOK_GENERAL',true);?></a>
								</div>
								
							</div>
							<?php  if (is_array($this->userData->excursions) && count($this->userData->excursions)==0) {?>
								<div class="right" style="padding: 0 10px 15px;clear:both;">
										<span class="button button-white trigger open" >
											<button
												class="reservation "
												name="check-button"
												value		= "<?php echo JText::_('LNG_CHECK_DATES',true);?>"
												type		= 'button'
													
											><?php echo JText::_('LNG_CHECK_DATES',true); ?>
											</button>
										</span>
								</div>
							<?php }?>
							
							<div class="right" style="padding: 0 10px 15px;clear:both;">
								<?php 
								$crt_excursion_sel  = 1;
									
								$datas = ( $this->userData->year_start.'-'.$this->userData->month_start.'-'.$this->userData->day_start );
								$datae = ( $this->userData->year_end.'-'.$this->userData->month_end.'-'.$this->userData->day_end );
									
									
								$diff = abs(strtotime($datae) - strtotime($datas));
								$years = floor($diff / (365*60*60*24));
								$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
									
								$nrDays = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
								
								$is_checked = false;
								if ($nrDays < $excursion->min_days) {
									//dmp($nrDays);
									$text = JText::_('LNG_MINIMUM_DAYS',true);
									$text = str_replace("<<min_days>>",	$excursion->min_days, $text);
									echo $text;
								} else if ($nrDays > $excursion->max_days && $excursion->max_days!=0) {
									//dmp($nrDays);
									$text = JText::_('LNG_MAXIMUM_DAYS',true);
									$text = str_replace("<<max_days>>",	$excursion->max_days, $text);
									echo $text;
								}else if(!$excursion->is_disabled){
								?>
								<select id	= 'excursionId<?php echo $excursion->excursion_id;?>' name= 'excursions[]' 
										<?php echo ($excursion->is_disabled)? " disabled " : ""; ?> 					
										<?php //echo count($this->userData->reservedItems)  == $this->userData->rooms ? 'disabled' : ''?>
										style="width: auto;"
								>
									<option value="0"><?php echo JText::_('LNG_SELECT_TO_BOOK'); ?> </option>
									<?php for($i=1;$i<=$excursion->capacity;$i++){
										echo "<option value='".$excursion->hotel_id."_".$excursion->excursion_id."_"."$i'>".$i."</option>";
									}
									?>
								</select>
								<?php } ?>
							</div>
						
														<!--  extra details excursion -->
							<div class="cnt" style=" background: none;padding-top:15px;">
								<div class="excursion-description">
									<div class="tabs-container" style="display:none">
											<ul style="display:block">
												<li><a href="#tabs-1_<?php echo $excursion->excursion_id; ?>"><?php echo JText::_('LNG_ROOM_DETAILS',true)?></a></li>
												<li><a href="#tabs-2_<?php echo $excursion->excursion_id; ?>"><?php echo JText::_('LNG_RATE',true)?></a></li>
												<li><a href="#tabs-3_<?php echo $excursion->excursion_id; ?>"><?php echo JText::_('LNG_RATE_RULES',true)?></a></li>
											</ul>
											<div id="tabs-1_<?php echo $excursion->excursion_id; ?>">
												<?php echo $excursion->excursion_details?>
											</div>
											<div id="tabs-2_<?php echo $excursion->excursion_id; ?>">
												<?php echo JText::_('LNG_PRICE_BREAKDOWN_BY_NIGHT',true)?>
												<div class="price_breakdown">
													<table >
													<?php
													$grand_total = 0;
													foreach( $excursion->daily as $daily )
													{
														$p 		= $daily['display_price_final'];
														$day	= $daily['date'];
														echo '<tr><td>'.date('D d M', strtotime($day)).'</td><td>'.JHotelUtil::fmt($p,2).' '.$this->userData->currency->symbol.'</td></tr>';
														$grand_total += JHotelUtil::fmt($p,2);	
													}
													?>
														<tr class="price_breakdown_grad_total">
															<td>
																<strong> = <?php echo JText::_('LNG_GRAND_TOTAL',true) ?></strong>
															</td>
															<td>
																<?php echo JHotelUtil::fmt($grand_total,2); ?> <?php //echo $this->_models['variables']->currency_selector?>
															</td>
														</tr>
													</table>
												</div>
											</div>
											<div id="tabs-3_<?php echo $excursion->excursion_id; ?>">
												<?php echo JText::_('LNG_RATE_RULES_DESCRIPTION',true)?>
											</div>
										</div>
							
									<div id="calendar-holder-<?php echo $excursion->excursion_id+1000?>" class="excursion-calendar">	
										<div class="excursion-loader right"></div>
										<?php  
											if(isset($this->_models['variables']->availabilityCalendar)){
												$calendar =  $this->_models['variables']->availabilityCalendar;
												$id= $excursion->excursion_id;
												echo $calendar[$id];
											}
											
											if(isset($this->_models['variables']->defaultAvailabilityCalendar)){
												echo $this->_models['variables']->defaultAvailabilityCalendar;
											}
										?>
									</div>
									
								</div>
								
								
							</div>
						
							
						</div>

			</div> 				
			<?php
				}
			}
			$labelBook = JText::_('LNG_CONTINUE');
			if (is_array($this->userData->excursions) && count($this->userData->excursions)==0)
				$labelBook = JText::_('LNG_BOOK_GENERAL');
			?>
			
		
			<div class="right" style="padding-top:20px;">
					<span class="button button-green">
						<button 
							class="reservation"
							id			= 'submitButton' 
							name		= 'submitButton' 
							type		= 'button'
							onclick 	= 'return bookItems();'
						><?php echo $labelBook;?></button>
					</span>
			</div>
			<div class="pagination">
				<?php echo $this->pagination->getListFooter(); ?>
				<div class="clear"></div>
			</div>		
	<?php 
		$session = JFactory::getSession();
		$userData =  $_SESSION['userData'];
	?>
			
	
	<input type="hidden" name="option" 			id="option" 				value="<?php echo getBookingExtName();?>" />
	<input type="hidden" name="task" 			id="task" 				value="excursionslisting.reserveCourses" />
	<input type="hidden" name="tip_oper" 			id="tip_oper" 				value="-2" />
	<input type="hidden" name="tmp" 				id="tmp" 					value="<?php echo JRequest::getVar('tmp') ?>" />
	<input type="hidden" name="orderBy" 			id="orderBy" 				value="" />
	
	<input type='hidden'	name='jhotelreservation_datas' value='<?php echo $this->userData->start_date?>'>
	<input type='hidden'	name='jhotelreservation_datae' value='<?php echo $this->userData->end_date?>'>
	<input type='hidden'	name='guest_adult' 		value='<?php echo $userData->adults?>'>
	<input type='hidden'	name='guest_child' 		value='<?php echo $userData->children?>'>
	<input type='hidden'	name='year_start' 		value='<?php echo $userData->year_start?>'>
	<input type='hidden'	name='month_start' 		value='<?php echo $userData->month_start ?>'>
	<input type='hidden'	name='day_start'		value='<?php echo $userData->day_start ?>'>
	<input type='hidden'	name='year_end' 		value='<?php echo $userData->year_end?>'>
	<input type='hidden'	name='month_end' 		value='<?php echo $userData->month_end?>'>
	<input type='hidden'	name='day_end' 			value='<?php echo $userData->day_end?>'>
	<input type='hidden'	name='filterParams'		id="filterParams" value='<?php  echo $this->searchFilter ?>'>
	<input type='hidden'	name='excursionRedirect' value='<?php echo JRequest::getVar("excursionRedirect")?>'>
	
</form>

<script>
	var dateFormat = "<?php echo  $this->appSettings->dateFormat; ?>";
	jQuery(document).ready(function(){
	<?php foreach($this->excursions as $excursion){?>	
		jQuery('#excursionDescription<?php echo $excursion->excursion_id?>').readmore({
			  afterToggle: function(trigger, element, expanded) {
			  }
			});
		<?php }?>
	});


	
		function bookItems(){
			
			<?php  if (is_array($this->userData->excursions) && count($this->userData->excursions)==0) {?>
		
			var selected =false; 
			jQuery("select[name='excursions[]']").each(function(){
				if(this.value!=0)
					selected = true; 
			});
			if(!selected){
				alert('<?php echo JText::_('LNG_SELECT_ITEM_BOOK');?>');
				return false;
			}
			<?php if(!$this->appSettings->enable_excursions){?>
				alert('<?php echo JText::_('LNG_EXCURSIONS_COURSES_DISABLED',true);?>');
				return false;
			<?php } ?>
		
			<?php }?>

			jQuery("#userForm").submit();
		}
	
		//not used anymore
		function setCheckedValue(radioObj, newValue) {
			if(!radioObj)
				return;
			var radioLength = radioObj.length;
			if(radioLength == undefined) {
				radioObj.checked = (radioObj.value == newValue.toString());
				return;
			}
			for(var i = 0; i < radioLength; i++) {
				radioObj[i].checked = false;
				if(radioObj[i].value == newValue.toString()) {
					radioObj[i].checked = true;
				}
			}
		}
		
		function showHotel(hotelId, selectedTab){
			jQuery("#tabId").val(selectedTab);
			jQuery("#tip_oper").val('-1');
			jQuery("#controller").val('');
			jQuery("#task").val('checkAvalability');
			jQuery("#hotel_id").val(hotelId);
			jQuery("#searchForm").submit();
		}
		
		function changeOrder(orderField){
			jQuery("#orderBy").val(orderField);
			jQuery("#searchForm").submit();	
		}


		function showExcursionsCalendars(){
			var postParameters='';
			postParameters +="&tip_oper=-1";
			<?php 
					foreach($this->userData->reservedItems as $itemReserved){
						echo 'postParameters +="&items_reserved[]='.$itemReserved.'";';
					}
				?>

			var postData='&task=excursionslisting.getExcursionsCalendars'+postParameters;

			jQuery.post(baseUrl, postData, processShowExcursionsCalendarResults);
		}

		function processShowExcursionsCalendarResults(responce){
			var xml = responce;
			//alert(xml);
			//xml = parseXml(xml);
			//alert(xml);
			//console.log(xml);
			jQuery("<div>" + xml + "</div>").find('answer').each(function()
			{
				var identifier = jQuery(this).attr('identifier');
				//console.debug(identifier);
				//alert(jQuery("#calendar-holder-"+identifier));
				jQuery("#calendar-holder-"+identifier).html(jQuery(this).attr('calendar'));
			});
		}

		function showRoomCalendar(hotelId,year,month, identifier){
			//alert("show");
			var postParameters='';
			postParameters +="&month="+month;
			postParameters +="&year="+year;
			postParameters +="&identifier="+identifier;
			postParameters +="&hotel_id="+hotelId;
			postParameters +="&tip_oper=-1";
			postParameters +="&current_room=1";

			//alert(postParameters);
			
			jQuery("#loader-"+identifier).show();
			jQuery("#room-calendar-"+identifier).hide();
			
			var postData='&task=excursionslisting.getRoomCalendar'+postParameters;
			//alert(baseUrl + postData);
			jQuery.post(baseUrl, postData, processShowRoomCalendarResult);
		}

		function processShowRoomCalendarResult(responce){
			var xml = responce;
			//alert(xml);
			//xml = parseXml(xml);
			//alert(xml);
			jQuery("<div>" + xml + "</div>").find('answer').each(function()
			{
				//alert("here");
				var identifier = jQuery(this).attr('identifier');
				//console.debug(identifier);
				//alert(jQuery("#calendar-holder-"+identifier));
				jQuery("#calendar-holder-"+identifier).html(jQuery(this).attr('calendar'));
			});
		}

		function selectCalendarDate(hoteId,startDate, endDate){
			jQuery('#jhotelreservation_datas2').val(startDate);
			jQuery('#jhotelreservation_datae2').val(endDate);
			if(typeof checkRoomRates === 'function')
				checkRoomRates('searchForm');
		}
		
		
	</script>
	