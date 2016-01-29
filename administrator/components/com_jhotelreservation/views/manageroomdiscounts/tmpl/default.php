<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
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

?>
<form action="index.php" method="post" name="adminForm">
	<div id="editcell">
		<fieldset class="adminform">
			<legend><?php echo JText::_('LNG_MANAGE_DISCOUNTS',true); ?></legend>
			<center>
				<div style='text-align:left'>
					<strong><?php echo JText::_('LNG_PLEASE_SELECT_THE_HOTEL_IN_ORDER_TO_VIEW_THE_EXISTING_SETTINGS',true)?> :</strong>
					<select name='sel_hotel_id' id='sel_hotel_id' style='width:300px'
						onchange ='
									var form 	= document.adminForm; 
									form.hotel_id.value  = this.value;
									var obView	= document.createElement("input");
									obView.type = "hidden";
									obView.name	= "view";
									obView.value= "manageroomdiscounts";
									form.appendChild(obView);
									// form.view.value="manageroomdiscounts";
									form.submit();
									'
					>
						<option value=0 <?php echo $this->hotel_id ==0? 'selected' : ''?>><?php echo JText::_('LNG_SELECT_DEFAULT',true)?></option>
						<?php
						foreach($this->hotels as $hotel )
						{
						?>
						<option value='<?php echo $hotel->hotel_id?>' 
							<?php echo $this->hotel_id ==$hotel->hotel_id||(count($this->hotels)==1)? 'selected' : ''?>
						>
							<?php 
								echo stripslashes($hotel->hotel_name);
								echo (strlen($hotel->country_name)>0? ", ".$hotel->country_name : "");
								echo stripslashes(strlen($hotel->hotel_city)>0? ", ".$hotel->hotel_city : "");
							?>
						</option>
						<?php
						}
						?>
					</select>
					<hr>
				</div>
				<?php
				if( $this->hotel_id > 0  )
				{
				?>
				<table class="table table-striped adminlist"  id="itemList">
					<thead>
						<th width='1%'>#</th>
						<th width='1%'  align=center>&nbsp;</th>
						<th width='25%' align=center ><B><?php echo JText::_('LNG_NAME',true); ?></B></th>
						<th width='15%' align=center><B><?php echo JText::_('LNG_PERIOD',true); ?></B></th>
						<th width='30%' align=center><B><?php echo JText::_('LNG_ROOMS',true); ?></B></th>
						<th width='7%' align=center><B><?php echo JText::_('LNG_VALUE',true); ?></B></th>
						<th width='10%' align=center><B><?php echo JText::_('LNG_CONDITIONS',true); ?></B></th>
						<th width='10%' align=center><B><?php echo JText::_('LNG_CODE',true); ?></B></th>
						<th width='1%' align=center><B><?php echo JText::_('LNG_AVAILABLE',true); ?></B></th>
					</thead>
					<tbody>

					<?php
					$nrcrt = 1;
					//if(0)
					for($i = 0; $i <  count( $this->items ); $i++)
					{
						$room_discount = $this->items[$i]; 

					?>
					<TR class="row<?php echo $i%2 ?>"
						onmouseover	=	"this.style.cursor='hand';this.style.cursor='pointer'"
						onmouseout	=	"this.style.cursor='default'"
					>
						<TD align=center><?php echo $nrcrt++?></TD>
						<TD align=center>
							 <input type="radio" name="boxchecked"  id="boxchecked" value="<?php echo $room_discount->discount_id?>" 
								onmouseover	=	"this.style.cursor='hand';this.style.cursor='pointer'"
								onmouseout	=	"this.style.cursor='default'"
								onclick="
											adminForm.discount_id.value = '<?php echo $room_discount->discount_id?>'
										" 
							/>
							
						</TD>
						<TD align=left>
							
							<a href='<?php echo JRoute::_( 'index.php?option='.getBookingExtName().'&controller=manageroomdiscounts&view=manageroomdiscounts&task=edit&hotel_id='.$room_discount->hotel_id.'&discount_id[]='. $room_discount->discount_id )?>'
								title		= 	"<?php echo JText::_('LNG_CLICK_TO_EDIT',true); ?>"
							>
								<B><?php echo $room_discount->discount_name?></B>
							</a>	
							
						</TD>
						<TD align=center><?php echo JText::_('LNG_FROM',true).'&nbsp;&nbsp;'.JHotelUtil::getDateGeneralFormat($room_discount->discount_datas).'&nbsp;&nbsp;'. JText::_('LNG_TO',true).'&nbsp;&nbsp;'.JHotelUtil::getDateGeneralFormat($room_discount->discount_datae) ?></TD>
						<TD align=center><?php echo $room_discount->discount_rooms?></TD>
						<TD align=center><?php echo $room_discount->discount_value.' %'?></TD>
						<TD align=center>
						<?php 
							$str_conditions = '';
							if( $room_discount->minimum_number_days > 0 )
								$str_conditions .=' >= '.$room_discount->minimum_number_days.'&nbsp;'. JText::_('LNG_DAYS',true);
							
							if(strlen($str_conditions) > 0 )
								$str_conditions .='<BR>';
							
							if( $room_discount->minimum_number_persons > 0 )
								$str_conditions .=' >= '.$room_discount->minimum_number_persons.'&nbsp;'. JText::_('LNG_PERS',true);

							if( $room_discount->minimum_amount> 0 )
								$str_conditions .=' >= '.$room_discount->minimum_amount.'&nbsp;';
								
							echo $str_conditions;
						?>
						</TD>
						<TD align=left><?php echo $room_discount->code?></TD>
						<TD align=center>
							<img border= 1 
								src ="<?php echo JURI::base() ."components/".getBookingExtName()."/assets/img/".($room_discount->is_available==false? "unchecked.gif" : "checked.gif")?>" 
								onclick	=	"	
												document.location.href = '<?php echo JRoute::_( 'index.php?option='.getBookingExtName().'&task=manageroomdiscounts.state&hotel_id='.$room_discount->hotel_id.'&discount_id[]='. $room_discount->discount_id )?> '
											"
							/>
							
						</TD>
						
					</TR>
					<?php
					}
					?>
					<tbody>
				</TABLE>
				<?php
				}
				?>
			</center>
		</fieldset>
	</div>
	<input type="hidden" name="option" value="<?php echo getBookingExtName()?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="discount_id" value="" />
	<input type="hidden" name="refreshScreen" id="refreshScreen" value="<?php echo JRequest::getVar('refreshScreen',null)?>" />
	<input type="hidden" name="hotel_id" value="<?php echo $this->hotel_id?>" />
	<input type="hidden" name="controller" value="<?php echo JRequest::getCmd('controller', 'J-HotelReservation')?>" />
	<?php echo JHTML::_( 'form.token' ); ?> 
	<script language="javascript" type="text/javascript">
		Joomla.submitbutton = function(pressbutton) 
		{
			var form = document.adminForm;
			if (pressbutton == 'edit' || pressbutton == 'Delete') 
			{
				var isSel = false;
				if( form.elements['boxchecked'].length == null )
				{
					if(form.elements['boxchecked'].checked)
					{
						isSel = true;
					}
				}
				else
				{
					for( i = 0; i < form.boxchecked.length; i ++ )
					{
						if(form.elements['boxchecked'][i].checked)
						{
							isSel = true;
							break;
						}
					}
				}
				
				if( isSel == false )
				{
					alert('<?php echo JText::_('LNG_YOU_MUST_SELECT_ONE_RECORD',true)?>');
					return false;
				}
				submitform( pressbutton );
				return;
			} else {
				submitform( pressbutton );
			}
		}
		jQuery(document).ready(function()
				{
					var hotelId=jQuery('#sel_hotel_id').val();
					var refreshScreen=jQuery('#refreshScreen').val();
					if(hotelId>0 && refreshScreen==""){
						jQuery('#refreshScreen').val("true");
						jQuery("#sel_hotel_id").trigger('change');	
					}
				});	
	</script>
</form>


