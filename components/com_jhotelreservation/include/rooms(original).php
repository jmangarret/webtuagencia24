<?php 
$index =0;
foreach( $this->rooms as $room ){
	if($this->userData->voucher!=''){
		continue;
	}
	
	if(!$room->front_display){
		continue;
	}
	
	$index++;
}
?>

<?php if($index>0){ ?>
<br/>
<TABLE width="100%" valign="top" border="0" class="table_info rooms" cellspacing="0" >
<tr class="tr_header">
		<TH width=2%>&nbsp;</TH>
		<TH align="left"><?php //echo JText::_('LNG_ROOMS',true)?> Habitaciones </TH><!--micod-->
		<TH width=10% class="hidden-phone"><?php echo JText::_('LNG_CAPACITY_PERS',true)?></TH>
		<?php if($this->appSettings->show_children!=0){ ?>
			<TH width=10% class="hidden-phone"><?php echo JText::_('LNG_CHILDREN',true)?></TH>
		<?php } ?>
		<TH width=10% align=right>
		<?php 
		if(JRequest::getVar( 'show_price_per_person')==1){ 
			echo JText::_('LNG_PRICE_PER_PERSON',true);
			$solo_precio = false;
		}else{ 
			echo JText::_('LNG_PRICE',true);
			$solo_precio = true;
		}
		?>
	    </TH>
	    <?php
	    if ($solo_precio == false){
	    ?>
		<TH align=right width="15%" class="hidden-phone"> Precio por Niño</TH> 
		<?php
	    }
		?>
		<TH align=right width="15%" >
		</TH> 
		
	</tr>
<?php
$index = 0;
$showRoomHeader = true;
$noOfferFound = true;
foreach( $this->rooms as $room )
{
	//dmp($room);
	$daily = $room->daily;
	$price_per_person = 0;

	if($price_per_person == 0)
		$price_per_person = $room->price_type;

	//dmp($room->public);
	//when searching with voucher only offer with searched voucher should be displayed
	if($this->userData->voucher!=''){
		continue;
	}


	if(!$room->front_display){
		continue;
	}

	$capacityExceeded = false;
	if((isset($userData->roomGuests) && $userData->roomGuests[count($this->userData->reservedItems)] > $room->max_adults)){
		$capacityExceeded = true;	
	}
	else if(!isset($userData->roomGuests) && ($room->max_adults < $this->userData->adults)){
		$capacityExceeded = true;
	}else if(($this->appSettings->show_children) && (!empty($userData->roomGuestsChildren) && isset($userData->roomGuestsChildren[count($this->userData->reservedItems)]) && $userData->roomGuestsChildren[count($this->userData->reservedItems)] > $room->base_children)){
		$capacityExceeded = true;
	}
	else if(($this->appSettings->show_children) && !isset($userData->roomGuestsChildren) && ($room->base_children < $this->userData->children)){
		$capacityExceeded = true;
		} 
	$grand_total = 0;


	foreach( $room->daily as $daily )
	{
		$p = $daily['display_price_final'];
		$grand_total += $p;
	}


		
	?>
	
	
	<tr>
		<td align=center>
			<input 
				type	= 'checkbox' 
				name	= 'room_ids[]'
				id		= 'room_ids[]'
				value	= '<?php echo '0|'.$room->room_id?>'
				onmouseover			=	"this.style.cursor='hand';this.style.cursor='pointer'"
				onmouseout			=	"this.style.cursor='default'"
				style				= 	"display:none;"
			>
		</td>
		<td id="<?php echo "room_".$room->room_id;?>" align=left nowrap >
			<div class="trigger open">
				<div class="room_expand"></div>
				<?php 
					$itemName =$room->room_name;
				?>
				<a href="#">
				<?php 
				if( strlen($itemName) > MAX_LENGTH_ROOM_NAME )	
				{
					
					?>
					<span 
						title			=	"<?php echo $itemName?>" 
						style			=	'cursor:hand;cursor:pointer'
						onmouseover		= 	"
												var text = jQuery(this).attr('title');
												var posi = jQuery(this).position();
												var top  = posi.top;
												var left = posi.left+5;
												var wid	 = jQuery(this).width();
												
												jQuery(this).attr('title','');
												jQuery(this).parent().append('<div class=\'poptext\'>'+text.replace('|','<br />')+'</div>');
												jQuery('.poptext').attr('css','TOOLTIP_ROOM_NAME');
												jQuery('.poptext').css(
																	{
																		'left':(left+wid)+'px',
																		'top'			:(top-jQuery('.poptext').height())+'px',
																		'display'		:'none',
																		'position'		:'absolute',
																		'z-index'		:'1000',
																		'padding'		:'5px',
																		'background-color': '#fff'
																	});
												jQuery('.poptext').fadeIn('slow');"
						onmouseout		= 	"
												var title = jQuery(this).parent().find('.poptext').html();
												jQuery(this).attr('title',title.replace('<br />','|'));
												jQuery(this).parent().find('.poptext').fadeOut('slow');
												jQuery(this).parent().find('.poptext').remove();
												
											"
					><?php echo substr($itemName, 0,MAX_LENGTH_ROOM_NAME); ?>...</span>
					<?php
				}
				else
					echo $itemName;
				?>
				</a>
				<?php 
					if($room->has_breakfast){
						echo "(".JText::_('LNG_BREAKFAST_INCLUDED',true).")";
					}
				?>
				&nbsp;|&nbsp;
				<a class="link_more" href="#">&nbsp;<?php echo "Otras Disponibilidades"?> »</a>
			</div>
		</td>
		
		<td align=center class="hidden-phone">
			<?php echo $room->max_adults;?>
		</td>
		<?php if($this->appSettings->show_children!=0){ ?>
			<td align=center class="hidden-phone">
				<?php echo $room->max_children; ?>
			</td>
		<?php } ?>
		<td align=right class="">
			<?php 
				if(!$room->is_disabled && !$capacityExceeded){
					if(JRequest::getVar( 'show_price_per_person')==1){
						echo $room->pers_total_price." Bsf.";
					}else{ 
						echo $room->room_average_display_price." Bsf.";
					}
				}
			?>
		</td>
	    
		<td align="center" class="hidden-phone">
		<?php
		if(!$room->is_disabled && !$capacityExceeded){
		if ($solo_precio == false){
					$comision = $room->commission;
					$total_comision_child_view = $room->child_price * $comision / 100;
					$room->child_price = $room->child_price + $total_comision_child_view;
					$room->child_price = round($room->child_price, 2);
				echo $room->child_price." Bsf.";
							/*echo "<pre>";
							var_dump($room->commission);
							echo "</pre>";*/
			}
		}
		?>
			<ul style='margin-top:0px'>
			<?php
			$crt_room_sel  = 1;
			
			$datas = ( $this->userData->year_start.'-'.$this->userData->month_start.'-'.$this->userData->day_start );
			$datae = ( $this->userData->year_end.'-'.$this->userData->month_end.'-'.$this->userData->day_end );
			
			$interval = date_diff(new DateTime($datas), new DateTime($datae));
			$nrDays = $interval->format('%a');
			
			foreach( $this->userData->reservedItems as $i=>$v )
			{
				$room_ex = explode('|' , $v );
				if( $room_ex[0] == 0 && $room_ex[1] == $room->room_id)
				{
				?>
				<li>
					<div style='text-align:center'>
						<?php echo $room_ex[2]?>
						<!-- 
						<a href='#dialog_room_selected_<?php echo $room_ex[0]?>_<?php echo $room_ex[1]?>_<?php echo $room_ex[2]?>' name='modal'>
							<img width='12px' height='12px' src ="<?php echo JURI::base() ."components/".getBookingExtName()."/assests/img/icon_info.png"?>" >
						</a>
						<a onclick='javascript:deleteReserveRoom(<?php echo $room_ex[0]?>,<?php echo $room_ex[1]?>,<?php echo $room_ex[2]?>)'>
							<img style='cursor:hand;cursor:pointer' width='12px' height='12px' src ="<?php echo JURI::base() ."components/".getBookingExtName()."/assests/img/icon_delete.png"?>" >
						</a>
						
						<a onclick='javascript:editReserveRoom(<?php echo $room_ex[0]?>,<?php echo $room_ex[1]?>,<?php echo $room_ex[2]?>)'>
							<img style='cursor:hand;cursor:pointer' width='12px' height='12px' src ="<?php echo JURI::base() ."components/".getBookingExtName()."/assests/img/icon_edit.png"?>" >
						</a> -->
						
					</div>
					<div 
						id		='dialog_room_selected_<?php echo $room_ex[0]?>_<?php echo $room_ex[1]?>_<?php echo $room_ex[2]?>'
						class	='window'
					>
						<div class='info'>
							<SPAN class='title_ID'>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type='button' value='<?php echo JText::_('LNG_CLOSE_IT',true)?>' class='close'/>
								<!--explore information about # room reservation-->
							</span>
							
						</div>
					</div>
				</li>
				<?php
				}
				
			}
			?>
			</ul>
		</td>
		<td colspan=1 align="right" style="padding-left:15px;" >
			<?php
			$is_checked = false;
			
			if ($nrDays < $room->min_days) {
				//dmp($nrDays);
				$text = JText::_('LNG_MINIMUM_DAYS',true);
				$text = str_replace("<<min_days>>",	$room->min_days, $text);
				echo $text;
			} else if ($nrDays > $room->max_days && $room->max_days!=0) {
				//dmp($nrDays);
				$text = JText::_('LNG_MAXIMUM_DAYS',true);
				$text = str_replace("<<max_days>>",	$room->max_days, $text);
				echo $text;
			}else if($capacityExceeded){
				//echo JText::_('LNG_CAPACITY_EXCEDEED',true);
				echo "Sobrepasa la capacidad máxima";
	
			}else if(!$room->is_disabled){
			?>
				  
					<button class="ui-hotel-button <?php echo count($this->userData->reservedItems)  == $this->userData->rooms ? 'not-bookable' : ''?>"
						id			= 'itemRoomsCapacity_RADIO' 
						name		= 'itemRoomsCapacity_RADIO[]' 
						type		= 'button'
						<?php echo $room->is_disabled? " disabled " : "" ?>
						<?php echo count($this->userData->reservedItems)  == $this->userData->rooms ? 'disabled' : ''?>
						onclick 	= 'return bookItem(0, 
											<?php echo $room->room_id?>);'
						><span class="ui-button-text"><?php //echo JText::_('LNG_BOOK')?>Cotizar</span></button><!--micod Cambiar valor del botón-->
		
			<?php }else{ 
				$buttonLabel = JText::_('LNG_CHECK_DATES',true);
				$class="";
			
				if( $room->lock_for_departure){
					$buttonLabel = JText::_('LNG_NO_DEPARTURE',true);
					$class = "red";
				}

				?>			
					<button class=" ui-hotel-button ui-hotel-button-grey reservation trigger open <?php echo $class ?> <?php echo count($this->userData->reservedItems)  == $this->userData->rooms ? 'not-bookable' : ''?>"
						name="check-button"
						value		= "<?php echo $buttonLabel?>"
						type		= 'button'
						<?php echo count($this->userData->reservedItems)  == $this->userData->rooms ? 'disabled' : ''?>
					>
						<span class="ui-button-text"><?php echo $buttonLabel?></span>
					</button>
					
				
			<?php } ?>
			
		</td>
	</tr>
	<tr class="tr_cnt">
		<td class="td_cnt" colspan="9" >
			<div class="cnt">
				<div class="room-description">
					<div class="tabs-container" style="display:none">
							<ul style="display:block">
								<li><a href="#tabs-1_<?php echo $room->room_id; ?>"><?php echo JText::_('LNG_ROOM_DETAILS',true)?></a></li>
								<li><a href="#tabs-2_<?php echo $room->room_id; ?>"><?php echo JText::_('LNG_RATE',true)?></a></li>
								<li><a href="#tabs-3_<?php echo $room->room_id; ?>"><?php echo JText::_('LNG_RATE_RULES',true)?></a></li>
							</ul>
							<div id="tabs-1_<?php echo $room->room_id; ?>">
								<?php echo $room->room_details?>
							</div>
							<div id="tabs-2_<?php echo $room->room_id; ?>">
								<?php echo JText::_('LNG_PRICE_BREAKDOWN_BY_NIGHT',true)?>
								<div class="price_breakdown">
									<table >
									<?php
									$grand_total = 0;
									foreach( $room->daily as $daily )
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
							<div id="tabs-3_<?php echo $room->room_id; ?>">
								<?php echo JText::_('LNG_RATE_RULES_DESCRIPTION',true)?>
							</div>
						</div>
			
				
					<div id="calendar-holder-<?php echo $room->room_id?>" class="room-calendar">	
						<div class="room-loader right"></div>
						<?php  
							if(isset($this->_models['variables']->availabilityCalendar)){
								$calendar =  $this->_models['variables']->availabilityCalendar;
								$id= $room->room_id;
								echo $calendar[$id];
							}
							
							if(isset($this->_models['variables']->defaultAvailabilityCalendar)){
								echo $this->_models['variables']->defaultAvailabilityCalendar;
							}
						?>
					</div>
					<div class="room_main_description">
					
						<?php
						$description = $room->room_main_description; 
						echo $description;
						?>
					</div>
				</div>
				
				<div class='picture-container'>
					<?php 
					if( isset($room->pictures) && count($room->pictures) >0 )
					{
						foreach( $room->pictures as $picture )
						{
					?>
						<a class="preview" onclick="return false;" title="<?php echo isset($picture->room_picture_info)?$picture->room_picture_info:'' ?>" alt="<?php echo isset($picture->room_picture_info)?$picture->room_picture_info:'' ?>" href="<?php echo JURI::base() .PATH_PICTURES.$picture->room_picture_path?>">
							<img 
									class="img_picture"
									style="height: 50px"
									src='<?php echo JURI::base() .PATH_PICTURES.$picture->room_picture_path?>' 
									alt="<?php echo isset($picture->room_picture_info)?$picture->room_picture_info:'' ?>"
									title="<?php echo isset($picture->room_picture_info)?$picture->room_picture_info:'' ?>"
							/>
						</a>
					<?php
					}
					}
					?>
				</div>
			</div>
		</td>
	</tr>
	<?php
	}
	?>
		
		
</TABLE>
<?php } ?>