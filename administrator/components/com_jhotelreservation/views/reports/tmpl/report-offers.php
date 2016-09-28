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

if (!checkUserAccess(JFactory::getUser()->id,"special_offers_report")){
	$msg = "You are not authorized to access this resource";
	$this->setRedirect( 'index.php?option='.getBookingExtName(), $msg );
}

?>
	<div id="editcell">
		<fieldset class="adminform">
			<legend><?php echo JText::_('LNG_RESERVATIONS_OFFERS_ACCESS_DETAILS',true); ?></legend>
			<div style='text-align:left'>
				<?php 
					foreach($this->offerReport as $k=>$details){ 
						
						if(strcmp($k,'media_refers')==0)
							continue;
						?>
						<table class="offers-report">
							<tr class="title_reports"> 
								<td colspan="100">
									<?php echo JText::_('LNG_VOUCHER',true).' - '.$k?>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<?php foreach($this->offerReport["media_refers"] as $mediaReferer ) {?>
								<td colspan="2">
									<?php echo $mediaReferer?>
								</td>
								<?php } ?>
							</tr>
							<tr>
							<td>&nbsp;</td>
							<?php foreach($this->offerReport["media_refers"] as $mediaReferer ) {?>
								<td>
									<?php echo JText::_('LNG_BOOKINGS',true)?>
								</td>
								<td>
								<?php echo JText::_('LNG_VIEWS',true)?>
								</td>
							<?php } ?>
							</tr>
							<?php foreach($details as $detail){?>
								<tr>
									<td class="offer-info">
										<?php echo $detail["info"] ?>
									</td>
									<?php foreach($this->offerReport["media_refers"] as $mediaReferer ) {?>
										<td>
											<?php
											     if($mediaReferer==''){
											     	echo isset($detail['bookings'][""])?$detail['bookings'][""]:'0';
											     }
											     else{
											     	echo isset($detail['bookings'][$mediaReferer])?$detail['bookings'][$mediaReferer]:'0';
											     }	
											?>
										</td>
										<td>
											<?php 
												if($mediaReferer==''){
											     	echo isset($detail['views'][''])?$detail['views']['']:'0';
											     }
											     else{
											     	echo isset($detail['views'][$mediaReferer])?$detail['views'][$mediaReferer]:'0';
											     }
											 ?>
										</td>
									<?php } ?>
								</tr>
							<?php } ?>
						</table>
						<br/><br/>
				<?php }
				?>
			</div>
		</fieldset>
		
	</div>
	<input type="hidden" name="option" value="<?php echo getBookingExtName()?>" />


	<?php echo JHTML::_( 'form.token' ); ?> 
	<script language="javascript" type="text/javascript">

		Joomla.submitbutton = function(pressbutton) 
		{
			var form = document.adminForm;
			form.task.value='';
			submitform( pressbutton );
			
		}
		jQuery(document).ready(function()
				{
					var hotelId=jQuery('#hotel_id').val();
					var refreshScreen=jQuery('#refreshScreen').val();
					if(hotelId>0 && refreshScreen==""){
						jQuery('#refreshScreen').val("true");
						jQuery("#hotel_id").trigger('change');	
					}
				});	
	</script>
<?php


