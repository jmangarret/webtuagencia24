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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$appSetings = JHotelUtil::getApplicationSettings();

?>

<script language="javascript" type="text/javascript">

	jQuery(document).ready(function() {	
		var hotelId=jQuery('#hotel_id').val();
		var refreshScreen=jQuery('#refreshScreen').val();
		var nrHotels = jQuery('#hotel_id option').length;
		if(hotelId>0 && refreshScreen=="" && parseInt(nrHotels)==2){
			jQuery('#refreshScreen').val("true");
			jQuery("#hotel_id").trigger('change');	
		}
		
		jQuery('#div_results').css({'width':f_clientWidth()-60,'height':f_clientHeight()/2});//40 padding
		//select all the a tag with name equal to modal
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
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&view=reports');?>" method="post" name="adminForm" id="adminForm">

	<div id="editcell">
		<fieldset class="adminform">
				<div style='text-align:left'>
					<strong><?php echo JText::_('LNG_PLEASE_SELECT_THE_HOTEL_IN_ORDER_TO_VIEW_THE_EXISTING_SETTINGS',true)?> :</strong>
					
					 <select name="hotel_id" class="inputbox" onchange="this.form.submit()">
							<option value=""><?php echo JText::_('LNG_SELECT_DEFAULT',true)?></option>
							<?php echo JHtml::_('select.options', $this->hotels, 'hotel_id', 'hotel_name', $this->state->get('filter.hotel_id'));?>
					</select>
					
					<hr>
				</div>
				
		
		<?php
				if( $this->state->get('filter.hotel_id') > 0  )
				{
				?>
	
			<div class="filter-search fltlft">
				<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL',true); ?></label>
				<?php echo JHTML::_('calendar',JHtml::_('date', $this->state->get('filter.start_date'), $appSetings->dateFormat), 'filter_start_date', 'filter_start_date', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
				&nbsp;
				<?php echo JHTML::_('calendar',JHtml::_('date', $this->state->get('filter.end_date'), $appSetings->dateFormat), 'filter_end_date', 'filter_end_date', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
		
				<button type="submit" class="btn"><?php echo JText::_('LNG_GENERATE_REPORT',true); ?></button>
			</div>
			<div class="filter-select fltrt">
				<label class="filter-search-lbl" for=filter_type"><?php echo JText::_('JSEARCH_FILTER_REPORT_TYPE',true); ?></label>
				<select id="filter_type" name="filter_type" class="inputbox" onchange="this.form.submit()">
					<?php echo JHtml::_('select.options', $this->types, 'value', 'text', $this->state->get('filter.type'), true);?>
				</select>
			
				<select id="filter_room_type" name="filter_room_type" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('JOPTION_SELECT_ROOM_TYPES',true);?></option>
					<?php echo JHtml::_('select.options', $this->roomTypes, 'value', 'text', $this->state->get('filter.room_type'));?>
				</select>
			</div>
		</fieldset>
		<div class="clr"> </div>
				
		<?php 
			switch($this->state->get('filter.type')){
				case "simple":
					require_once("report-simple.php");
					break;
				case "advanced":
					require_once("report-advanced.php");
					break;
				case "offers":
					require_once("report-offers.php");
					break;
				default:
					break;
			}
		}			
		?>
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="refreshScreen" id="refreshScreen" value="<?php echo JRequest::getVar('refreshScreen',null)?>" >
	<?php echo JHTML::_( 'form.token' ); ?> 
	
</form>


