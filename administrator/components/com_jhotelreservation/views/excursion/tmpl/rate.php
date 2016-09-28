<fieldset class='adminform'>
		<legend><?php echo JText::_('LNG_EXCURSION_RATE',true); ?></legend>
		<input type="hidden" id="rate_id" name="rate_id" value = "<?php echo $this->item->rate->id ?>">
		 <table class="admintable rommprice">
			 <tr>
				<td class="key">
					<?php echo JText::_('LNG_RATE_NAME',true)?>
				</td>
				<td>
					<input 
						type		= "text"
						name		= "name"
						id			= "name"
						value		= '<?php echo $this->item->rate->name ?>'
						size		= 30
						maxlength	= 70
					/>
				</td>
			</tr>
			
		    <tr>
				<td class="key">
					<?php echo JText::_('LNG_RATE_DESCRIPTION',true)?>
				</td>
				<td>
					<textarea id='rate_description' name='rate_description' rows='5' cols="50" style='width: 460px'><?php echo $this->item->rate->rate_description ?></textarea>
				</td>
			</tr>
			
			
		</table>
	</fieldset>
	<fieldset class='adminform'>
		<legend><?php echo JText::_('LNG_EXCURSION_RATE_DEFAULT_SETTINGS',true); ?></legend>
		 <table class="admintable rommprice">	
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_PRICE_TYPE',true); ?></TD>
				<TD nowrap align=left>
					<?php echo JText::_('LNG_PER_PERSON',true); ?>
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_PRICE',true); ?></TD>
				<td colspan=2 valign=top align=left>
					
					<div id='div_price_day_by_day' name='div_price_day_by_day'>
						<TABLE class='admintable' align=left border=0 width=100%>
							<TR>
								<td colspan=2 valign=top align=left>
									<TABLE cellpadding=0 cellspacing=0 align=left class="admintable" align=center border=0
										id='table_excursion_price_days' name='table_excursion_price_days' 
									>
										<TR>
											<?php
											for($day=1;$day<=7;$day++)
											{
											?>
											<TD nowrap="nowrap" align=center>
												<i>
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
												</i>
											</TD>
												<?php
											}
											?>
											<TD rowspan=2% width=40%>
												&nbsp;
											</TD>
										</TR>
										<TR>
											<?php
											for($day=1;$day<=7;$day++)
											{
												switch( $day )
												{
													case 1:
														$p = $this->item->rate->price_1;
														break;
													case 2:
														$p = $this->item->rate->price_2;
														break;
													case 3:
														$p = $this->item->rate->price_3;
														break;
													case 4:
														$p = $this->item->rate->price_4;
														break;
													case 5:
														$p = $this->item->rate->price_5;
														break;
													case 6:
														$p = $this->item->rate->price_6;
														break;
													case 7:
														$p = $this->item->rate->price_7;
														break;
												}
											?>
											<TD nowrap nowrap align=left width=1% align=left valign=center nowrap>
												<input 
													type		= "text"
													name		= "price_<?php echo $day?>"
													id			= "price_<?php echo $day?>"
													value		= '<?php echo $p?>'
													size		= 10
													maxlength	= 10
													
													style		= 'text-align:right'
												/>
											</td>
											<?php
											}
											?>
										</TR>
									</TABLE>
								</td>
							</tr>
						</table>
					</div>
					
				
				</td>
			</tr>
			
			
			<?php if($this->appSettings->show_children!=0 && 1==0){ ?>				
			<tr>
				<td class="key">
					<?php echo JText::_('LNG_CHILD_PRICE',true)?>
				</td>
				<td>
					<input 
						type		= "text"
						name		= "child_price"
						id			= "child_price"
						value		= '<?php echo $this->item->rate->child_price ?>'
						size		= 10
						maxlength	= 10
					/>
				</td>
			</tr>	 
			<?php }?>		
			<TR>
				<TD nowrap='nowrap' class="key"><?php echo JText::_('LNG_AVAILABILITY',true); ?> :</TD>
				<TD nowrap='nowrap' align=left>
					<input 
						type		= "text"
						name		= "availability"
						id			= "availability"
						value		= '<?php echo $this->item->rate->availability?>'
						size		= 10
						maxlength	= 10
						
						style		= 'text-align:center'
					/>
				</TD>
				<TD align=left colspan="4">&nbsp;</TD>
			</TR>
			
			<tr>
				<td class="key">
					<?php echo JText::_('LNG_MIN_DAYS',true)?>
				</td>
				<td>
					<input 
						type		= "text"
						name		= "min_days"
						id			= "min_days"
						value		= '<?php echo $this->item->rate->min_days?>'
						size		= 10
						maxlength	= 10
					/>
				</td>
				<td rowspan="3">
					 <div style="border:1px solid #ccc;width:300px;padding:10px;"><?php echo JText::_('LNG_CUSTOM_RATES_NOTICE',true)?></div>
				</td> 

				
			</tr>
			<tr>
				<td class="key">
					<?php echo JText::_('LNG_MAX_DAYS',true)?>
				</td>
				<td>
					<input 
						type		= "text"
						name		= "max_days"
						id			= "max_days"
						value		= '<?php echo $this->item->rate->max_days?>'
						size		= 10
						maxlength	= 10
					/>
				</td>
			</tr>
		 </table>

	</fieldset>