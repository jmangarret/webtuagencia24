<?php 
$appSetings = JHotelUtil::getApplicationSettings();
?>

<form action="index.php" method="post" name="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_('LNG_DISCOUNT_DETAILS',true); ?></legend>
		<center>
		<div style='text-align:left'>
			<strong>
				<?php echo JText::_('LNG_HOTEL',true)?> : 
				<?php 
					echo $this->hotel->hotel_name;
					echo (strlen($this->hotel->country_name)>0? ", ".$this->hotel->country_name : "");
					echo (strlen($this->hotel->hotel_city)>0? ", ".$this->hotel->hotel_city : "");
				?>
			</strong>
			<hr>
		</div>
		<TABLE class="admintable" align=center border=0>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_NAME',true); ?> :</TD>
				<TD nowrap width=1% align=left>
					<input 
						type		= "text"
						name		= "discount_name"
						id			= "discount_name"
						value		= '<?php echo $this->item->discount_name?>'
						size		= 70
						maxlength	= 128
					/>
				</TD>
				<TD>&nbsp;</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_PERIOD',true); ?> :</TD>
				<TD nowrap width=1% align=left>
					<?php echo JHTML::_('calendar', $this->item->discount_datas, 'discount_datas', 'discount_datas',  $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php echo JHTML::_('calendar', $this->item->discount_datae, 'discount_datae', 'discount_datae',  $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
					
				</TD>
				<TD>&nbsp;</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_ROOMS',true); ?> :</TD>
				<TD nowrap align=left>
					<select id='discount_room_ids' name='discount_room_ids[]' multiple="multiple" >
					<option value=""><?php echo JText::_('LNG_SELECT_ROOMS',true); ?></option>
					<?php
					foreach( $this->item->itemRooms as $value )
					{
					?>
					<option 
						value='<?php echo $value->room_id?>'
						<?php echo $value->is_sel? " selected" : ""?>
					>
						<?php echo $value->room_name?>
					</option>
					<?php
					}
					?>
					</select>
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_OFFERS',true); ?> :</TD>
				<TD nowrap align=left id="offers-holder">
					<select id='offer_ids' name='offer_ids[]' multiple="multiple" >
					<option value=""><?php echo JText::_('LNG_SELECT_OFFERS',true); ?></option>
					<?php
					foreach( $this->item->offers as $value )
					{
					?>
					<option 
						value='<?php echo $value->offer_id?>'
						<?php echo $value->is_sel? " selected" : ""?>
					>
						<?php echo $value->offer_name?>
					</option>
					<?php
					}
					?>
					</select>
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_EXCURSIONS',true); ?> :</TD>
				<TD nowrap align=left id="offers-holder">
					<select id='excursion_ids' name='excursion_ids[]' multiple="multiple" >
					<option value=""><?php echo JText::_('LNG_SELECT_EXCURSIONS',true); ?></option>
					<?php
					foreach( $this->item->excursions as $value )
					{
					?>
					<option 
						value='<?php echo $value->id?>'
						<?php echo $value->is_sel? " selected" : ""?>
					>
						<?php echo $value->excursion_name?>
					</option>
					<?php
					}
					?>
					</select>
				</TD>
			</TR>
			<tr>
				<td width="10%" class="key" nowrap >
					<?php echo JText::_('LNG_CONNECT_ONLY_OFFERS',true); ?>:			
				</td>
				<td align="left" nowrap>
					<input 
						type		= "radio"
						name		= "only_on_offers"
						id			= "only_on_offers"
						value		= '1'
						<?php echo $this->item->only_on_offers==true? " checked " :""?>
						accesskey	= "Y"
						onmouseover	="this.style.cursor='hand';this.style.cursor='pointer'"
						onmouseout	="this.style.cursor='default'"
					/>
					<?php echo JText::_('LNG_YES',true); ?>
					&nbsp;
					<input 
						type		= "radio"
						name		= "only_on_offers"
						id			= "only_on_offers"
						value		= '0'
						<?php echo $this->item->only_on_offers==false? " checked " :""?>
						accesskey	= "N"
						onmouseover	="this.style.cursor='hand';this.style.cursor='pointer'"
						onmouseout	="this.style.cursor='default'"
					/>
					<?php echo JText::_('LNG_NO',true); ?>
				</td>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_PRICE_TYPE',true); ?></TD>
				<TD nowrap align=left>
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "price_type"
						id			= "price_type"
						value		= '1'
						<?php echo $this->item->price_type==1? " checked " :""?>
					/>
					<?php echo JText::_('LNG_PER_PERSON',true); ?>
					&nbsp;
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "price_type"
						id			= "price_type"
						value		= '0'
												<?php echo $this->item->price_type==0? " checked " :""?>
					/>
					<?php echo JText::_('LNG_PER_ROOM',true); ?>
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_MINIM_NUMBER_DAYS',true); ?> :</TD>
				<TD nowrap align=left>
					<input 
						type		= "text"
						name		= "minimum_number_days"
						id			= "minimum_number_days"
						value		= '<?php echo $this->item->minimum_number_days > 0 ? $this->item->minimum_number_days : ""?>'
						size		= 5
						maxlength	= 10
						style		= 'text-align:right'
					/>
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_MAXIMUM_NUMBER_DAYS',true); ?> :</TD>
				<TD nowrap align=left>
					<input 
						type		= "text"
						name		= "maximum_number_days"
						id			= "maximum_number_days"
						value		= '<?php echo $this->item->maximum_number_days > 0 ? $this->item->maximum_number_days : ""?>'
						size		= 5
						maxlength	= 10
						style		= 'text-align:right'
					/>
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_MINIM_NUMBER_PERSONS',true); ?> :</TD>
				<TD nowrap align=left>
					<input 
						type		= "text"
						name		= "minimum_number_persons"
						id			= "minimum_number_persons"
						value		= '<?php echo $this->item->minimum_number_persons > 0 ? $this->item->minimum_number_persons : ""?>'
						size		= 5
						maxlength	= 10
						style		= 'text-align:right'
					/>
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_MINIM_AMOUNT',true); ?> :</TD>
				<TD nowrap align=left>
					<input 
						type		= "text"
						name		= "minimum_amount"
						id			= "minimum_amount"
						value		= '<?php echo $this->item->minimum_amount > 0 ? $this->item->minimum_amount : ""?>'
						size		= 5
						maxlength	= 10
						style		= 'text-align:right'
					/>
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_VALUE',true); ?> :</TD>
				<TD nowrap align=left>
					<input 
						type		= "text"
						name		= "discount_value"
						id			= "discount_value"
						value		= '<?php echo $this->item->discount_value?>'
						size		= 10
						maxlength	= 10
						style		= 'text-align:right'
					/>
					
					<input 
						type		= "checkbox"
						name		= "percent"
						id			= "percent"
						value		= '1'
						<?php echo $this->item->percent == 1 ? 'checked="checked"' :''?>
					/>
					%
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_DISCOUNT_CODE',true); ?> :</TD>
				<TD nowrap align=left>
					<input 
						type		= "text"
						name		= "code"
						id			= "code"
						value		= '<?php echo $this->item->code ?>'
						size		= 30
						maxlength	= 50
					/>
				</TD>
			</TR>
			<tr>
				<td  class="key"  >
					<?php echo JText::_('LNG_CHECK_FULL_CODE',true); ?>:
				</td>
				<td align="left" nowrap>
					<input 
						type		= "radio"
						name		= "check_full_code"
						id			= "check_full_code"
						value		= '1'
						<?php echo $this->item->check_full_code==true? " checked " :""?>
						accesskey	= "Y"
						onmouseover	="this.style.cursor='hand';this.style.cursor='pointer'"
						onmouseout	="this.style.cursor='default'"
					/>
					<?php echo JText::_('LNG_YES',true); ?>
					&nbsp;
					<input 
						type		= "radio"
						name		= "check_full_code"
						id			= "check_full_code"
						value		= '0'
						<?php echo $this->item->check_full_code==false? " checked " :""?>
						accesskey	= "N"
						onmouseover	="this.style.cursor='hand';this.style.cursor='pointer'"
						onmouseout	="this.style.cursor='default'"
					/>
					<?php echo JText::_('LNG_NO',true); ?>
				</td>
			</TR>
		</TABLE>
	</fieldset>
	<script language="javascript" type="text/javascript">
		<?php
		if( JHotelUtil::getCurrentJoomlaVersion() < 1.6 )
		{
		?>
		function submitbutton(pressbutton) 
		<?php
		}
		else
		{
		?>
		Joomla.submitbutton = function(pressbutton) 
		<?php
		}
		?>
		{
			var form = document.adminForm;
			if (pressbutton == 'save') 
			{
				if( !validateField( form.discount_name, 'string', false, "<?php echo JText::_('LNG_PLEASE_INSERT_DISCOUNT_NAME',true); ?>" ) )
					return false;
				if( !validateField( form.discount_datas, 'date', false, "<?php echo JText::_('LNG_PLEASE_INSERT_DISCOUNT_DATA_START',true); ?>" ) )
					return false;
				if( !validateField( form.discount_datae, 'date', false, "<?php echo JText::_('LNG_PLEASE_INSERT_DISCOUNT_DATA_STOP',true); ?>" ) )
					return false;
				if( !compareDate( form.discount_datas, form.discount_datae, "<?php echo JText::_('LNG_DATA_START_DATA_STOP',true); ?>" ) )
					return false;
				
				if( form.elements['discount_room_ids[]'] && form.elements['discount_room_ids[]'].selectedIndex <= -1 )
				{
					alert( "<?php echo JText::_('LNG_PLEASE_SELECT_AT_LEAST_ONE_ROOM',true); ?>" ) ;
					return false;
				}
				if( !validateField( form.minimum_number_days, 'numeric', true, "<?php echo JText::_('LNG_PLEASE_INSERT_NUMBER',true); ?>" ) )
					return false;
				if( !validateField( form.minimum_number_persons, 'numeric', true, "<?php echo JText::_('LNG_PLEASE_INSERT_NUMBER',true); ?>" ) )
					return false;
				if( !validateField( form.discount_value, 'numeric', false, "<?php echo JText::_('LNG_PLEASE_INSERT_DISCOUNT_PERCENT',true); ?>" ) )
					return false;
					
				
				submitform( pressbutton );
				return;
			} else {
				submitform( pressbutton );
			}
		}
	</script>
	<input type="hidden" name="option" value="<?php echo getBookingExtName()?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hotel_id" value="<?php echo $this->hotel_id ?>" />
	<input type="hidden" name="discount_id" value="<?php echo $this->item->discount_id ?>" />
	<input type="hidden" name="controller" value="manageroomdiscounts>" />
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>

<script>
jQuery(document).ready(function(){
	
	jQuery("select#discount_room_ids").selectList({ 
		 sort: true,
		 classPrefix: 'discount_room_ids',
		 onAdd: function (select, value, text) {
			addSelection(value);
		 },
		 onRemove: function (select, value, text) {
			 removeSelection(value);
		 }
	});
	
	jQuery("select#offer_ids").selectList({ 
		sort: true,
		classPrefix: 'offer_ids'
	});

	jQuery("select#excursion_ids").selectList({ 
		sort: true,
		classPrefix: 'offer_ids'
	});

});

</script>