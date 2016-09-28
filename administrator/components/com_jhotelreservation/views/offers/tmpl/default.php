<?php defined('_JEXEC') or die('Restricted access'); 

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
<form action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&view=offers');?>" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<fieldset class="adminform">
			<div style='text-align:left'>
				<strong><?php echo JText::_('LNG_PLEASE_SELECT_THE_HOTEL_IN_ORDER_TO_VIEW_THE_EXISTING_SETTINGS',true)?> :</strong>
				
				 <select name="hotel_id" id="hotel_id" class="inputbox" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('LNG_SELECT_DEFAULT',true)?></option>
						<?php echo JHtml::_('select.options', $this->hotels, 'hotel_id', 'hotel_name', $this->state->get('filter.hotel_id'));?>
				</select>
				
				<hr>
			</div>
			<?php
			if( $this->state->get('filter.hotel_id') > 0  )
			{
			?>
	
				<div id="boxes">
					<TABLE class="table table-striped adminlist"  name='table_offers' id='table_offers'>
						<thead>
					
							<th width='1%'>&nbsp;</th>
							<th width='2%'>&nbsp;</th>
							
							<th width='15%' align=center><B><?php echo JText::_( 'LNG_NAME' ,true); ?></B></th>
							<?php 
								if (checkUserAccess(JFactory::getUser()->id,"hotel_extra_info")){
							?>
							<th width='20%' align=center><B><?php echo JText::_( 'LNG_VOUCHER_CODE' ,true); ?></B></th>
							<?php 
								}
							?>
							<th width='2%' align=center><B><?php echo JText::_( 'LNG_MIN_NIGHTS' ,true); ?></B></th>
							<th width='2%' align=center><B><?php echo JText::_( 'LNG_MAX_NIGHTS' ,true); ?></B></th>
							<th width='10%' align=center><B><?php echo JText::_( 'LNG_DATA_FRONT_DISPLAYED' ,true); ?></B></th>
							<th width='10%' align=center><B><?php echo JText::_( 'LNG_PERIOD' ,true); ?></B></th>
							<?php 
							if (checkUserAccess(JFactory::getUser()->id,"manage_featured_hotels")){
							?>
								<th width='1%' align=center>
									<?php echo JText::_( 'LNG_TOP'); ?>
								</th>
								<th width='1%' align=center>
									<?php echo JText::_( 'LNG_FEATURED'); ?>
								</th>
							<?php 
								}
							?>
							<th width='1%' align=center><B><?php echo JText::_( 'LNG_ENABLED' ,true); ?></B></th>
							<th width='1%' align=center><B>&nbsp;</B></th>
							<th width='1%' align=center><B>&nbsp;</B></th>
							<th width='1%' align=center><B>&nbsp;</B></th>
							<Th width='1%' align=center><B><?php echo JText::_('LNG_ORDER',true)?></B></Th>
						</thead>
						<tbody>
						<?php
						$nrcrt = 1;
						//if(0)
						for($i = 0; $i <  count( $this->items ); $i++)
						{
							$offer = $this->items[$i]; 

						?>
						<TR
							onmouseover	=	"this.style.cursor='hand';this.style.cursor='pointer'"
							onmouseout	=	"this.style.cursor='default'"
						>
							<TD align=center><?php echo $nrcrt++?></TD>
							<td class="nowrap">
							<?php echo JHtml::_('grid.id', $i,$offer->offer_id); ?>
							</td>
							</TD>
							
							<TD align=left>
								<a href='<?php echo JRoute::_( 'index.php?option='.getBookingExtName().'&task=offer.edit&offer_id='. $offer->offer_id.'&hotel_id='. $offer->hotel_id )?>'
									title		= 	"<?php echo JText::_( 'LNG_CLICK_TO_EDIT' ,true); ?>"
								>
									<B><?php echo $offer->offer_name?></B>
								</a>
							</TD>
							<?php 
								if (checkUserAccess(JFactory::getUser()->id,"hotel_extra_info")){
							?>
							<TD align=center>
								<a href='<?php echo JRoute::_( 'index.php?option='.getBookingExtName().'&task=offer.edit&offer_id='. $offer->offer_id.'&hotel_id='. $offer->hotel_id )?>'
									title		= 	"<?php echo JText::_( 'LNG_CLICK_TO_EDIT' ,true); ?>"
								>
									<B><?php echo $offer->vouchers?></B>
								</a>
							</TD>
							<?php } ?>
							<TD align=center>
								<B><?php echo $offer->offer_min_nights !=0 ? $offer->offer_min_nights : "&nbsp;"?></B>
							</TD>
							<TD align=center>
								<B><?php echo $offer->offer_max_nights !=0 ? $offer->offer_max_nights : "&nbsp;"?></B>
							</TD>
							<TD align=center>
								<?php echo $offer->offer_datasf=='0000-00-00' ? "&nbsp;" : JHotelUtil::getDateGeneralFormat($offer->offer_datasf)?>
								<?php echo JText::_( 'LNG_TO' ,true); ?>		
								<?php echo $offer->offer_dataef=='0000-00-00' ? "&nbsp;" : JHotelUtil::getDateGeneralFormat($offer->offer_dataef)?>
							</TD>
							<TD align=center>
								<?php echo $offer->offer_datas=='0000-00-00' ? "&nbsp;" : JHotelUtil::getDateGeneralFormat($offer->offer_datas)?>
								<?php echo JText::_( 'LNG_TO' ,true); ?>	
								<?php echo $offer->offer_datae=='0000-00-00' ? "&nbsp;" : JHotelUtil::getDateGeneralFormat($offer->offer_datae)?>
							</TD>
								<?php 
									if (checkUserAccess(JFactory::getUser()->id,"manage_featured_hotels")){
								?>
									<td align=center><img border=1
										src="<?php echo JURI::base() ."components/".getBookingExtName()."/assets/img/".($offer->top==false? "unchecked.gif" : "checked.gif")?>"
										onclick="document.location.href = '<?php echo JRoute::_( 'index.php?option='.getBookingExtName().'&task=offers.changeTopState&offer_id='.$offer->offer_id.'&hotel_id='. $offer->hotel_id  )?> '" />
									</td>
									<td align=center><img border=1
										src="<?php echo JURI::base() ."components/".getBookingExtName()."/assets/img/".($offer->featured==false? "unchecked.gif" : "checked.gif")?>"
										onclick="document.location.href = '<?php echo JRoute::_( 'index.php?option='.getBookingExtName().'&task=offers.changeFeaturedState&offer_id='.$offer->offer_id.'&hotel_id='. $offer->hotel_id  )?> '" />
									</td>
								<?php 
									}
								?>
							<TD align=center>
								<img border= 1 
									src ="<?php echo JURI::base() ."components/".getBookingExtName()."/assets/img/".($offer->is_available==false? "unchecked.gif" : "checked.gif")?>" 
									onclick	=	"	
													document.location.href = '<?php echo JRoute::_( 'index.php?option='.getBookingExtName().'&task=offers.state&cid[]='. $offer->offer_id.'&hotel_id='. $offer->hotel_id )?> '
												"
								/>
							</TD>
							<TD>
								<div>
									<a href='#dialog_<?php echo $offer->offer_id?>' onclick="getOfferContent('<?php echo $offer->offer_id?>')" name='modal'>
										<img style="width:25px;height:25px;max-width: none;" src ="<?php echo JURI::base() ."components/".getBookingExtName()."/assets/img/info_icon.png"?>" >
									</a>
								</div>
								<div 
									id		='dialog_<?php echo $offer->offer_id?>'
									class	='window'
								>
									<div class='info'>
										<SPAN class='title_ID'>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='Close it' class='close'/>
										</span>
										<div id="offerContentDiv<?php echo $offer->offer_id?>"></div>
									</div>
								</div>
							</TD>
							<TD>
								<div>
									<a href='#warning_<?php echo $offer->offer_id?>' onclick="getWarningContent('<?php echo $offer->offer_id?>')" name='modal'>
										<img style="width:25px;height:25px;max-width: none;" src ="<?php echo JURI::base() ."components/".getBookingExtName()."/assets/img/alert_icon.png"?>" >
									</a>
								</div>
								<div 
									id		='warning_<?php echo $offer->offer_id?>'
									class	='window'
								>
									<div class='info'>
										<SPAN class='title_ID'>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='Close it' class='close'/>
										</span>
										<div id="warningContentDiv<?php echo $offer->offer_id?>"></div>
										
									</div>
								</div>
							</TD>
							<TD width='1%' align=center nowrap>
								<B>
									<span 
										name="span_up_<?php echo $offer->offer_id?>"
										id	="span_up_<?php echo $offer->offer_id?>"
										class= "span_up"
										onclick='
														jQuery.ajax({
															url		: "<?php echo JURI::base()?>/index.php/?option=<?php echo getBookingExtName();?>&task=offers.offer_order&tip_order=up&hotel_id=<?php echo $offer->hotel_id?>&offer_id=<?php echo $offer->offer_id?>",
															context	: document.body,
															success	: function( responce ){
																					var xml = responce;
																					jQuery(xml).find("answer").each(function()
																					{
																						if( jQuery(this).attr("error") == "0" )
																						{
																							window.location="<?php echo JURI::base()?>?index.php&option=<?php echo getBookingExtName()?>&view=offers&hotel_id=<?php echo $offer->hotel_id?>"
																							var row = jQuery("#span_up_<?php echo $offer->offer_id?>").parents("tr:first"); 
																							row.insertBefore(row.prev());
																						}
																					});
																			}
														});
												'
									>
										<?php echo JText::_('LNG_STR_UP',true)?>
									</span>
									&nbsp;
									<span 
										name="span_down_<?php echo $offer->offer_id?>"
										id	="span_down_<?php echo $offer->offer_id?>"
										class="span_down"
										onclick='
													// var row = jQuery(this).parents("tr:first"); 
																					// row.insertAfter(row.next());
																	
														jQuery.ajax({
															url		: "<?php echo JURI::base()?>/index.php?option=<?php echo getBookingExtName()?>&task=offers.offer_order&tip_order=down&hotel_id=<?php echo $offer->hotel_id?>&offer_id=<?php echo $offer->offer_id?>",
															context	: document.body,
															success	: function(responce){
																					var xml = responce;
																					jQuery(xml).find("answer").each(function()
																					{
																						if( jQuery(this).attr("error") == "0" )
																						{
																							window.location="<?php echo JURI::base()?>?index.php&option=<?php echo getBookingExtName()?>&view=offers&hotel_id=<?php echo $offer->hotel_id?>"
																							var row = jQuery("#span_down_<?php echo $offer->offer_id?>").parents("tr:first"); 
																							row.insertAfter(row.next());
																						}
																					});
																					
																				}
														});
												'
									>
										<?php echo JText::_('LNG_STR_DOWN',true)?>
									</span>
								</B>
							</TD>
							<TD align=center>
								<B><?php echo $offer->offer_order?></B>
							</TD>
						</TR>
						<?php
						}
						?>
						</tbody>
						<tfoot>
						<tr>
							<td colspan="15">
								<?php echo $this->pagination->getListFooter(); ?>
							</td>
						</tr>
					</tfoot>
					</TABLE>
				</div>
				<?php
				}
				?>
				<div id="mask"></div>
		</fieldset>
	</div>
	<input type="hidden" name="option" value="<?php echo getBookingExtName()?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="offer_id" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="refreshScreen" id="refreshScreen" value="<?php echo JRequest::getVar('refreshScreen',null)?>" />
	<input type="hidden" name="controller" value="<?php echo JRequest::getCmd('controller', 'J-HotelReservation')?>" />
	<?php echo JHTML::_( 'form.token' ); ?> 
	<script language="javascript" type="text/javascript">
		Joomla.submitbutton = function(task) {
			if (task != 'items.delete' || confirm('<?php echo JText::_('LNG_ARE_YOU_SURE_YOU_WANT_TO_DELETE', true,true);?>')) {
				Joomla.submitform(task);
			}
		}
		jQuery(document).ready(function()
			{
				var hotelId=jQuery('#hotel_id').val();
				var refreshScreen=jQuery('#refreshScreen').val();
				var nrHotels = jQuery('#hotel_id option').length;
				if(refreshScreen=="" && parseInt(nrHotels)==2){
					jQuery('#hotel_id :nth-child(2)').prop('selected', true); 
					jQuery('#refreshScreen').val("true");
					jQuery("#hotel_id").trigger('change');	
				}
			});	
	</script>
</form>
<script>
	jQuery(document).ready(function()
	{
		jQuery('a[name=modal]').click(function(e) {
			//Cancel the link behavior
			e.preventDefault();
			//Get the A tag
			var id = jQuery(this).attr('href');
		
			//Get the screen height and width
			var maskHeight = jQuery(document).height();
			var maskWidth = jQuery(window).width();
				
			//Set heigth and width to mask to fill up the whole screen
			jQuery('#mask').css({'width':maskWidth,'height':maskHeight});
			
			//transition effect		
			jQuery('#mask').fadeIn(1000);	
			jQuery('#mask').fadeTo("slow",0.8);	
		
			//Get the window height and width
			var winH = jQuery(window).height();
			var winW = jQuery(window).width();
			//Set the popup window to center
			// jQuery(id).css('top',  winH/2-jQuery(id).height()/2);
			// jQuery(id).css('left', winW/2-jQuery(id).width()/2);
			jQuery(id).css('top',  f_scrollTop() + 20);
			jQuery(id).css('left', winW/2-jQuery(id).width()/2);
				
			//transition effect
			jQuery(id).fadeIn(2000); 
		
		});
		
		//if close button is clicked
		jQuery('.window .close').click(function (e) {
			//Cancel the link behavior
			e.preventDefault();
			
			jQuery('#mask').hide();
			jQuery('.window').hide();
		});		
		
		//if mask is clicked
		jQuery('#mask').click(function () {
			jQuery(this).hide();
			jQuery('.window').hide();
		});	
		
		function f_clientWidth() {
			return f_filterResults (
				window.innerWidth ? window.innerWidth : 0,
				document.documentElement ? document.documentElement.clientWidth : 0,
				document.body ? document.body.clientWidth : 0
			);
		}
		function f_clientHeight() {
			return f_filterResults (
				window.innerHeight ? window.innerHeight : 0,
				document.documentElement ? document.documentElement.clientHeight : 0,
				document.body ? document.body.clientHeight : 0
			);
		}
		function f_scrollLeft() {
			return f_filterResults (
				window.pageXOffset ? window.pageXOffset : 0,
				document.documentElement ? document.documentElement.scrollLeft : 0,
				document.body ? document.body.scrollLeft : 0
			);
		}


		function f_scrollTop() {
			return f_filterResults (
				window.pageYOffset ? window.pageYOffset : 0,
				document.documentElement ? document.documentElement.scrollTop : 0,
				document.body ? document.body.scrollTop : 0
			);
		}	
		function f_filterResults(n_win, n_docel, n_body) {
			var n_result = n_win ? n_win : 0;
			if (n_docel && (!n_result || (n_result > n_docel)))
				n_result = n_docel;
			return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
		}

		
	});

	function getOfferContent(offerId){
		var siteRoot = '<?php echo JURI::root();?>';
		var siteRootAdmin = '<?php echo JURI::root();?>administrator/';
		var compName = '<?php echo getBookingExtName();?>';
		
		var fieldName = 'offerContentDiv'+offerId;
		var showField = 'dialog_';
		var url = siteRootAdmin+'index.php?option='+compName+'&task=offers.getOfferContent&offer_id='+offerId;
		
		getAjaxData(fieldName,showField,url,null);
	}
	function getWarningContent(offerId){
		var siteRoot = '<?php echo JURI::root();?>';
		var siteRootAdmin = '<?php echo JURI::root();?>administrator/';
		var compName = '<?php echo getBookingExtName();?>';
		
		var fieldName = 'warningContentDiv'+offerId;
		var showField = 'dialog_';
		var url = siteRootAdmin+'index.php?option='+compName+'&task=offers.getWarningContent&offer_id='+offerId;
		
		getAjaxData(fieldName,showField,url,null);
	}
	

	getAjaxData = function(fieldName,showField,url,inputParams) {
		jQuery.ajax({
          type: 'POST',
          url: url,
          data: inputParams,
          dataType: 'html',
          success: function(data){
              jQuery("#"+fieldName).html(data);
             // jQuery("#"+showField).show();
          },
          error: function(xhr, ajaxOptions, thrownError) {
                               alert("ERROR: " + xhr.statusText +" " + ajaxOptions+" " +thrownError);
             
          }
      });/*ajax post function*/
	} 
</script>

    