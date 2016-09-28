
<fieldset class="adminform">
		<legend><?php echo JText::_('LNG_EXCURSION_DETAILS',true); ?></legend>

		<TABLE class="admintable" align=center border=0 cellpadding="5">
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_NAME',true); ?></TD>
				<TD nowrap width=1% align=left>
					<input 
						type		= "text"
						name		= "excursion_name"
						id			= "excursion_name"
						value		= '<?php echo $this->item->name?>'
						size		= 64
						maxlength	= 128
					/>
				</TD>
				<TD>&nbsp;</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_TYPE',true); ?></TD>
				<TD nowrap align=left>
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "type"
						id			= "type"
						value		= '0'
						<?php echo $this->item->type==0? " checked " :""?>
						accesskey	= "Y"
						
					/>
					<?php echo JText::_('LNG_COURSE',true); ?>
					&nbsp;
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "type"
						id			= "type"
						value		= '1'
						<?php echo $this->item->type==1? " checked " :""?>
						accesskey	= "N"
					/>
					<?php echo JText::_('LNG_EXCURSION_TYPE',true); ?>
				</TD>
				<TD nowrap>
					&nbsp;
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_AVAILABLE',true); ?></TD>
				<TD nowrap align=left>
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "is_available"
						id			= "is_available"
						value		= '1'
						<?php echo $this->item->is_available==true? " checked " :""?>
						accesskey	= "Y"
						
					/>
					<?php echo JText::_('LNG_STR_YES',true); ?>
					&nbsp;
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "is_available"
						id			= "is_available"
						value		= '0'
						<?php echo $this->item->is_available==false? " checked " :""?>
						accesskey	= "N"
					/>
					<?php echo JText::_('LNG_STR_NO',true); ?>
				</TD>
				<TD nowrap>
					&nbsp;
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_DISPLAY_ON_FRONT',true); ?></TD>
				<TD nowrap align=left>
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "front_display"
						id			= "front_display"
						value		= '1'
						<?php echo $this->item->front_display==true? " checked " :""?>
						accesskey	= "Y"
						
					/>
					<?php echo JText::_('LNG_STR_YES',true); ?>
					&nbsp;
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "front_display"
						id			= "front_display"
						value		= '0'
						<?php echo $this->item->front_display==false? " checked " :""?>
						accesskey	= "N"
					/>
					<?php echo JText::_('LNG_STR_NO',true); ?>
				</TD>
				<TD nowrap>
					&nbsp;
				</TD>
			</TR>
			
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_( 'LNG_DAYS' ,true); ?> :</TD>
				<TD nowrap colspan=2 ALIGN=LEFT>
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
								$tag_name = "excursion_day_$day";?>
								<TD <?php echo $day<7 ? "style='border-right:solid 2px black'" :""?> align=center >
									<input 
									type	= 'checkbox' 
									name	= 'excursion_day_<?php echo $day?>'
									id		= 'excursion_day_<?php echo $day?>'
									value	= "1"
									class="offer-day"
									<?php echo $this->item->{$tag_name} == 1 ? " checked " : " "?>
								>
								</TD>
							<?php
							}
							?>
						</TR>
					</TABLE>
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_( 'LNG_PERIOD' ,true); ?>:</TD>
				<TD nowrap width=1% align=left>
					<div class="period_offer_calendar" id="period_offer_calendar"></div>
					
					<?php echo JHTML::_('calendar', $this->item->data_start==$appSetings->defaultDateValue ? '' : $this->item->data_start, 'data_start', 'data_start', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
					<?php echo JHTML::_('calendar', $this->item->data_end==$appSetings->defaultDateValue ? '' : $this->item->data_end, 'data_end', 'data_end', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
				</TD>
				<TD>&nbsp;</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_UNAVAILABILITY',true); ?>:</TD>
				<td>
					<span> <?php echo JText::_('LNG_UNAVAILABILITY_INFO',true);?> </span>
					<input 
							type='hidden' 
							name='ignored_dates' 
							id='ignored_dates'
							value='<?php echo $this->item->ignored_dates;?>'
						>
					<div class="dates_hotel_calendar" id="dates_hotel_calendar"></div>
					
				</td>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_MAIN_DESCRIPTION',true); ?>:</TD>
				<TD nowrap colspan=2 ALIGN=LEFT>
				<?php 
						$appSettings = JHotelUtil::getApplicationSettings();
						$options = array(
												    'onActive' => 'function(title, description){
												        description.setStyle("display", "block");
												        title.addClass("open").removeClass("closed");
												    }',
												    'onBackground' => 'function(title, description){
												        description.setStyle("display", "none");
												        title.addClass("closed").removeClass("open");
												    }',
												    'startOffset' => 0,  // 0 starts on the first tab, 1 starts the second, etc...
												    'useCookie' => true, // this must not be a string. Don't use quotes.
						);
						
						echo JHtml::_('tabs.start', 'tab_group_id', $options);
						
						$path = JLanguage::getLanguagePath(JPATH_COMPONENT_ADMINISTRATOR);
						$dirs = JFolder::folders( $path );
						sort($dirs);
						//dmp($dirs);
						$j=0;
						foreach( $dirs  as $_lng ){
							
							echo JHtml::_('tabs.panel', $_lng, 'tab'.$j );						
							$langContent = isset($this->translations[$_lng])?$this->translations[$_lng]:"";
							$editor =JFactory::getEditor();
							echo $editor->display('excursion_main_description_'.$_lng, $langContent, '400', '200', '30', '30', false);
							
						}
						echo JHtml::_('tabs.end');
					?>
				</TD>
			</TR>
			
			
			
		</TABLE>
	</fieldset>
	
	