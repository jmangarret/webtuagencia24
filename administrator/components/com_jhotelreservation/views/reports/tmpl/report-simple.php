<div id="boxes">

	<table class="report report-simple">
		<tr class='title_reports'>
			<td colspan="2" nowrap="nowrap" class='title_room_type' align="center"><?php echo JText::_('LNG_DATE',true)?>
			</td>
			
			<?php
				foreach($this->rooms as $room )	{
			?>
				<td align=center
					class='td_data'>
					&nbsp;<?php echo $room->room_name ?>&nbsp;
				</td>
			<?php
			}
			?>
		</tr>
		<?php foreach($this->availabilityReport as $date=>$dailyReport){ ?>
			<tr>
				<td class="date" rowspan="2" > <?php echo JHtml::_('date',$date, $appSetings->dateFormat); ?></td>
				<td class="info-type"><?php echo JText::_('LNG_AVAILABILITY',true)?></td>
				<?php
				foreach($this->rooms as $room )	{
				?>
					<td align="center" class="room-info <?php echo $dailyReport[$room->room_id][2] == 1? "available":"not-available" ?>"><?php echo $dailyReport[$room->room_id][0] ?> </td>
				<?php }?>
			</tr>
			<tr class="info-row">
				
				<td class="info-type"><?php echo JText::_('LNG_BOOKED',true)?></td>
				<?php
				foreach($this->rooms as $room )	{
				?>
					<td align="center" class="room-info <?php echo $dailyReport[$room->room_id][2] == 1? "available":"not-available" ?>"><?php echo $dailyReport[$room->room_id][1]?> </td>
				<?php }?>
			</tr>
		<?php } ?>
	</table>
			
</div>