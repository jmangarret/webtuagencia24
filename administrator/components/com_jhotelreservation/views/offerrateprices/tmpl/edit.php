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
jimport('joomla.html.pane');
$appSetings = JHotelUtil::getApplicationSettings();

JHTML::_("behavior.calendar");
?>

<form action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&view=offerrateprices&layout=edit&rate_id='.$this->state->get("filter.rate_id")); ?>" method="post" name="quickFilterFrm" id="quickFilterFrm">
	<table class="rate-quick-filter">
		<thead>
			<tr>
				<th colspan="4"><?php echo JText::_("LNG_QUICK_SETUP")?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="4"> 
					<?php echo JText::_("LNG_FROM")?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JHTML::_('calendar', $this->state->get("filter.start_date")==$appSetings->defaultDateValue?'': $this->state->get("filter.start_date"), 'start_date', 'start_date', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("LNG_TO")?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JHTML::_('calendar', $this->state->get("filter.end_date")==$appSetings->defaultDateValue?'': $this->state->get("filter.end_date"), 'end_date', 'end_date', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
				</td>
			</tr>
				<tr>
				<td colspan="4"> 
					<TABLE>
						<TR>
							<?php
							for($day=1;$day<=7;$day++)
							{
								?>
								<TD>
								<?php
								switch( $day )
								{
									case 1:
										echo JText::_('LNG_MON',true);
										break;
									case 2:
										echo JText::_('LNG_TUE',true);
										break;
									case 3:
										echo JText::_('LNG_WED',true);
										break;
									case 4:
										echo JText::_('LNG_THU',true);
										break;
									case 5:
										echo JText::_('LNG_FRI',true);
										break;
									case 6:
										echo JText::_('LNG_SAT',true);
										break;
									case 7:
										echo JText::_('LNG_SUN',true);
										break;
								}
								?>
								</TD>
								<?php
							}
							?>
							
						</TR>
						<TR>
							<?php
							for($day=1;$day<=7;$day++)
							{
								?>
								<TD>
									<input 
									type	= 'checkbox' 
									name	= 'week_day[]'
									id		= 'week_day_<?php echo $day?>'
									value	= "<?php echo $day?>"
									class="week-day"
									<?php echo  0 == 1 ? " checked " : " "?>
								>
								</TD>
							<?php
							}
							?>
						</TR>
					</TABLE>
				</td>
			</tr>
			<tr>
				<td colspan="4">
					<hr/>
				</td>
			</tr>
			<tr>
				<td wdith="200">
					<?php echo JText::_('LNG_PRICE',true)?>
				</td>
				<td>
					<input 
						type		= "text"
						name		= "price"
						id			= "price"
						value		= ""
						size		= 10
						maxlength	= 10
					/>
				</td>
				<td width="50%">
				</td>
				<td>
				</td>
			</tr>
			<?php if($this->appSettings->show_children!=0){ ?>
			<tr>
				<td >
					<?php echo JText::_('LNG_CHILD_PRICE',true)?>
				</td>
				<td>
					<input 
						type		= "text"
						name		= "child_price"
						id			= "child_price"
						value		= ""
						size		= 10
						maxlength	= 10
					/>
				</td>
				<td width="50%">
				</td>
				<td>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td >
					<?php echo JText::_('LNG_SINGLE_USE_PRICE',true)?>
				</td>
				<td>
					<input 
						type		= "text"
						name		= "single_use_price"
						id			= "single_use_price"
						value		= ""
						size		= 10
						maxlength	= 10
					/>
				</td>
				<td>
				</td>
			</tr>
			<tr>
				<td >
					<?php echo JText::_('LNG_EXTRA_PERS_PRICE',true)?>
				</td>
				<td>
					<input 
						type		= "text"
						name		= "extra_pers_price"
						id			= "extra_pers_price"
						value		= ""
						size		= 10
						maxlength	= 10
					/>
				</td>
				<td>
				</td>
			</tr>
	
			<tr>
				<td colspan="4">
					<input type="submit" class="right" value="<?php echo JText::_("LNG_SAVE");?>" />
				</td>
			</tr>
		</tbody>
	</table>
	
		<input type="hidden" name="option"	value="<?php echo getBookingExtName()?>" /> 
		<input type="hidden" name="task" value="offerrateprices.quickSetup" /> 
		<input type="hidden" name="rate_id" id="rate_id" value="<?php echo $this->state->get("filter.rate_id") ?>" /> 
		<?php echo JHTML::_( 'form.token' ); ?> 
</form>

<form action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&view=offerrateprices&layout=edit&rate_id='.(int) $this->rate->id); ?>" method="post" name="adminForm" id="room-form">

		<div id="rate-wrapper" class="rate-offers">
			<div class="rates-header">
				<div id="month-chooser"> 
					<?php echo JText::_('LNG_CHOOSE_MONTH',true) ?>
					<select name="filter_month" onchange="this.form.submit()">
						<?php 
							
							for($i=-1;$i<11;$i++){
							?>
							<option value="<?php echo (date("n")+$i)%12+1 ?>" <?php echo $this->state->get("filter.month")==((date("n")+$i)%12+1)?"selected":"" ?> ><?php echo date("F Y",mktime(0, 0, 0, (date("n")+$i)+1, 1, date("Y"))); ?> </option>
							
						<?php }?>
					</select>
				</div>
				<?php 
					$year = $this->state->get("filter.month")<date("n")? date("Y")+1 : date("Y");
				?>
				<h3><?php echo JText::_('LNG_RATES_AND_AVAILABILITY',true) ." ".date('F Y',mktime(0, 0, 0, $this->state->get("filter.month"), 1, $year)); ?></h3>
				<span> <?php echo JText::_('LNG_PRICE_TYPE',true).": ".($this->rate->price_type==1?JText::_('LNG_PER_PERSON',true):JText::_('LNG_PER_ROOM',true)) ?></span><br/>
				<span> <?php $newRates = $this->state->get("filter.newRates"); echo isset($this->items) && isset($this->items[0]->isNew) & $this->items[0]->isNew == true?JText::_("LNG_RATES_LOADED_DEFAULT"):JText::_("LNG_RATES_LOADED_DATABASE") ?></span>
			</div>
		
		
			<br/>
			
			<div id="rate-container">
				<div class="rate-row">
				<?php 
					$weekDay = date("N", strtotime($this->items[0]->date));
					if($weekDay!=1){
					
				?>
					<div class="rate-header">
						<ul>
							<li>
								<?php echo JText::_('LNG_DATE',true)?>
							</li>
							<li>
								<?php echo JText::_('LNG_PRICE',true)?>
							</li>
							<?php if($this->appSettings->show_children!=0){ ?>
							<li>
								<?php echo JText::_('LNG_CHILD_PRICE',true)?>
							</li>
							<?php } ?>
							<li>
								<?php echo JText::_('LNG_SINGLE_USE_PRICE',true)?>
							</li>
							<li>
								<?php echo JText::_('LNG_EXTRA_PERS_PRICE',true)?>
							</li>
						</ul>
					</div>
			<?php 
					
					for($j=1; $j<$weekDay; $j++){
						echo '<div class="rate-cell"></div>';	
					}
				}
			?>
			<?php foreach($this->items as $i => $item){ 
				$monthDay = date("j", strtotime($item->date));
			?>
				
			 <?php $weekDay = date("N", strtotime($item->date));
			 	   if($weekDay==1){
			  ?>
				</div>
				<div class="rate-row">
					<div class="rate-header">
						<ul>
							<li>
								<?php echo JText::_('LNG_DATE',true)?>
							</li>
							<li>
								<?php echo JText::_('LNG_PRICE',true)?>
							</li>
							<?php if($this->appSettings->show_children!=0){ ?>
							<li>
								<?php echo JText::_('LNG_CHILD_PRICE',true)?>
							</li>
							<?php } ?>
							<li>
								<?php echo JText::_('LNG_SINGLE_USE_PRICE',true)?>
							</li>
							<li>
								<?php echo JText::_('LNG_EXTRA_PERS_PRICE',true)?>
							</li>
							
						</ul>
					</div>
				<?php }?>
				<div class="rate-cell">
					<ul>
						<li>
							<span class="date"><?php echo strftime("%a, %d-%m-%Y", strtotime($item->date)); ?></span>
						</li>
						<li>
							<input type="text" name="price[<?php echo $monthDay?>]" id="price[<?php echo $monthDay?>]" value="<?php echo $item->price ?>"   />
						</li>
						<?php if($this->appSettings->show_children!=0){ ?>
						<li>
							<input type="text" name="child_price[<?php echo $monthDay?>]" id="child_price[<?php echo $monthDay?>]" value="<?php echo $item->child_price ?>" />
						</li> 
						<?php } ?>
						<li>
							<input type="text" name="single_use_price[<?php echo $monthDay?>]" id="single_use_price[<?php echo $monthDay?>]" value="<?php echo $item->single_use_price ?>" />
						</li>
						<li>
							<input type="text" name="extra_pers_price[<?php echo $monthDay?>]" id="extra_pers_price[<?php echo $monthDay?>]" value="<?php echo $item->extra_pers_price ?>" />
						</li>
						
					</ul>
				</div>
			<?php }?>
			</div>
			</div>
			<div class="clr"></div>
		</div>
		<input type="hidden" name="option"	value="<?php echo getBookingExtName()?>" /> 
		<input type="hidden" name="task" value="" /> 
		<input type="hidden" name="rate_id" id="rate_id" value="<?php echo $this->state->get("filter.rate_id") ?>" /> 
		
		<?php echo JHTML::_( 'form.token' ); ?> 
	</form>


	<script language="javascript" type="text/javascript">

		Joomla.submitbutton = function(task, type)
		{
			//console.debug(task);
			if (task == 'item.cancel' || validateForm() ) {
				Joomla.submitform(task, document.id('room-form'));
			}
		}	
		
		function validateForm(){
			return true;
			//console.debug("validate");
			if( !validateField( form.elements['room_name'], 'string', false, "<?php echo JText::_('LNG_PLEASE_INSERT_ROOM_NAME',true); ?>" ) ){
				varTabPane.showTab(1);
				return false;
			}
			/*
			if( !validateField( form.elements['number_of_rooms'], 'numeric', false, "<?php echo JText::_('LNG_PLEASE_INSERT_NUMBER_OF_ROOMS',true); ?>',true)){
				varTabPane.showTab(1);
				return false;
			}*/
	
			return true;
		}
	
	</script>


