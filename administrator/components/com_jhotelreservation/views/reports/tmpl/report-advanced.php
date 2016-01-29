<div id="boxes"> 
<table class="report report-advanced">
	<tr class='title_reports'>
		<td nowrap="nowrap" class='title_room_type' align="center"><?php echo JText::_('LNG_ROOM_TYPE',true)?></td>
		<td class='title_room_number' align="center">#</td>
		<?php
		
		$startDate = $this->state->get('filter.start_date');
		$endDate = $this->state->get('filter.end_date');
		for( $d = strtotime($startDate);$d <= strtotime($endDate); ){
			$dayString = date('d-m-Y',$d);
		?>
		<td align=center colspan='2' 
			class='<?php echo date('d-m-Y')==$dayString? "td_crt_data" : "td_data"?>'
		>
			&nbsp;<?php echo JHotelUtil::convertToFormat($dayString)?>&nbsp;
		</td>
		<?php
			$d = strtotime( date('d-m-Y', $d).' + 1 day ' );
		}
		?>
		
	
	</tr>
	<tr>
		<?php
		for( $d = strtotime($startDate);$d <= strtotime($endDate); ){
			$dayString = date('d-m-Y',$d);
		?>
		<td></td><td></td>
			<?php
			$d = strtotime( date('d-m-Y', $d).' + 1 day ' );
		}
		?>	
	</tr>
	<?php
		foreach($this->rooms as $room )	{
			$rowCount = isset($this->availabilityReport[$room->room_id])? count($this->availabilityReport[$room->room_id]) : "1";
	?>
			<tr>
				<td align=center class='td_data td_room' rowspan="<?php echo $rowCount ?>">
					&nbsp;<?php echo $room->room_name ?>&nbsp;
				</td>
				<td class="td_number"> 1 </td>
					<?php
						 $this->displayTableRow($room,0);	
					?>
				
			</tr>
			<?php for($i=1;$i<$rowCount; $i++){?>
			<tr>
				<td class="td_number"><?php echo $i+1?></td>
					<?php 	
						 $this->displayTableRow($room,$i);	
					?>
				
			</tr>
			<?php }?>
	<?php
		}
	?>
</table>							

</div>
