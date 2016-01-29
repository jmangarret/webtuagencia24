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

<form action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm">

<?php echo JHTML::_( 'form.token' );?>
<?php 
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
		
		echo JHtml::_('tabs.start', 'tab_excursion_edit', $options);
		echo JHtml::_('tabs.panel', JText::_('LNG_GENERAL_INFORMATION',true), 'tab1' );
			include(dirname(__FILE__).DS.'details.php');
		echo JHtml::_('tabs.panel', JText::_('LNG_RATE',true), 'tab2' );
			include(dirname(__FILE__).DS.'rate.php');					
		//echo JHtml::_('tabs.panel', JText::_('LNG_EXCURSION_FEATURES',true), 'tab3');
		//include(dirname(__FILE__).DS.'features.php');
		echo JHtml::_('tabs.panel', JText::_('LNG_PICTURES'), 'tab4');
			include(dirname(__FILE__).DS.'pictures.php');
		echo JHtml::_('tabs.end');
?>		
		<input type="hidden" name="option" value="<?php echo getBookingExtName()?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $this->state->get('excursion.excursion_id')?>" />
		<input type="hidden" name="hotel_id" value="<?php echo $this->state->get('excursion.hotel_id') ?>" />
		<?php echo JHTML::_( 'form.token' ); ?> 
	</form>

	
	
	

	<script language="javascript" type="text/javascript">


	Joomla.submitbutton = function(task, type)
	{
		if (task == 'item.cancel' || validateForm() ) {
			Joomla.submitform(task, document.id('adminForm'));
		}
	}	
	
	function validateForm(){
		return true;
		if( !validateField( form.elements['excursion_name'], 'string', false, "<?php echo JText::_('LNG_PLEASE_INSERT_EXCURSION_NAME',true); ?>" ) ){
			varTabPane.showTab(1);
			return false;
		}
		/*
		if( !validateField( form.elements['number_of_excursions'], 'numeric', false, "<?php echo JText::_('LNG_PLEASE_INSERT_NUMBER_OF_EXCURSIONS',true); ?>',true)){
			varTabPane.showTab(1);
			return false;
		}*/

		return true;
	}
	
	</script>
	
	<script language="javascript" type="text/javascript">
	
	jQuery(document).ready(function()
	{

		updateStatus();
		
		jQuery(".span_up,.span_down").click(function(){
			
			var row = jQuery(this).parents("tr:first");

			if (jQuery(this).is(".span_up")) {
			   row.insertBefore(row.prev());
			} else {
			   row.insertAfter(row.next());
			}

		});
	})
	
	jQuery('#dates_hotel_calendar').DatePicker(
				{
					flat: 		true,
					date: 			[  ],
					current: 		new Date(<?php echo date('Y')?>, <?php echo date('m')-1?>, 1, 0,0,0),
					format: 		'Y-m-d',
					calendars: 		2,
					mode: 			'multiple',
					position:		'right',
					className: 		'custom-picker',
					starts: 		0,
					onRender: function(date) {
												var d =  new Date(<?php echo date('Y')?>, <?php echo date('m')-1?>, <?php echo date('d')?>, 0,0,0);
												return {
													disabled: (date.valueOf() < d.valueOf()),
													className: date.valueOf() == d.valueOf() ? 'datepickerSpecial' : false
												}
											},
					onBeforeShow: function(){
												
												var crtVal = new Array();
												crtVal = (jQuery("#ignored_dates").val( )).split(',');
												jQuery('#dates_hotel_calendar').DatePickerClear();
												jQuery('#dates_hotel_calendar').DatePickerSetDate(crtVal);
											},
					onHide: function()
											{
												
												return true;
											},

					onChange: function(formated, dates){
														jQuery("#ignored_dates").val( formated.join(',') );
													}

				}
	);

	var crtVal = new Array();
	crtVal = (jQuery("#ignored_dates").val( )).split(',');
	jQuery('#dates_hotel_calendar').DatePickerClear();
	jQuery('#dates_hotel_calendar').DatePickerSetDate(crtVal);
	jQuery('#dates_hotel_calendar').DatePickerShow();
	
	jQuery(function()
	{
		jQuery('#uploadedfile').change(function() {
			var fisRe 	= /^.+\.(jpg|bmp|gif|png)$/i;
			var path = jQuery('#uploadedfile').val();
			if (path.search(fisRe) == -1)
			{   
				alert(' JPG, BMP, GIF, PNG only!');
				return false;
			}  
			jQuery(this).upload('<?php echo JURI::base()?>components/<?php echo getBookingExtName()?>/helpers/upload.php?t=<?php echo strtotime('now')?>&_root_app=<?php echo urlencode(JPATH_ROOT)?>&_target=<?php echo urlencode(PATH_PICTURES.PATH_EXCURSION_PICTURES.($this->state->get('excursion.excursion_id')+0).'/')?>', function(responce) 
																								{
																									//alert(responce);
																									if( responce =='' )
																									{
																										alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
																										jQuery(this).val('');
																									}
																									else
																									{
																										var xml = responce;
																										 //alert(responce);
																										jQuery(xml).find("picture").each(function()
																										{
																											if(jQuery(this).attr("error") == 0 )
																											{
																												addPicture(
																															"<?php echo PATH_EXCURSION_PICTURES.($this->state->get('excursion.excursion_id')+0).'/'?>" + jQuery(this).attr("path"),
																															jQuery(this).attr("name")
																												);
																											}
																											else if( jQuery(this).attr("error") == 1 )
																												alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED',true)?>");
																											else if( jQuery(this).attr("error") == 2 )
																												alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
																											else if( jQuery(this).attr("error") == 3 )
																												alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY',true)?>");
																											else if( jQuery(this).attr("error") == 4 )
																												alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE',true)?>");
																										});
																										
																										jQuery(this).val('');
																									}
																								}, 'html'
			);
        });
		
	});
	
	jQuery(function()
	{
		jQuery('#btn_removefile').click(function() {
		//function delPicture( obj, path, pos )
		//{
			pos 	= jQuery('#crt_pos').val();
			path 	= jQuery('#crt_path').val();
			jQuery( this ).upload('<?php echo JURI::base()?>components/<?php echo getBookingExtName()?>/helpers/remove.php?_root_app=<?php echo urlencode(JPATH_COMPONENT_ADMINISTRATOR)?>&_filename='+ path + '&_pos='+pos, function(responce) 
																								{
																									// alert(responce);
																									if( responce =='' )
																									{
																										alert("<?php echo JText::_('LNG_ERROR_REMOVING_FILE',true)?>");
																										jQuery(this).val('');
																									}
																									else
																									{
																										var xml = responce;
																										//alert(responce);
																										jQuery(xml).find("picture").each(function()
																										{
																											if(jQuery(this).attr("error") == 0 )
																											{
																												removePicture( jQuery(this).attr("pos") );
																											}
																											else if( jQuery(this).attr("error") == 2 )
																												alert("<?php echo JText::_('LNG_ERROR_REMOVING_FILE',true)?>");
																											else if( jQuery(this).attr("error") == 3 )
																												alert("<?php echo JText::_('LNG_FILE_DOESNT_EXIST',true)?>");
																										});
																										
																										jQuery('#crt_pos').val('');
																										jQuery('#crt_path').val('');
																									}
																								}, 'html'
			);
		
		});
		
		
	});
	

	function updateStatus(){
		if(jQuery("input[name='price_type']:checked").val()==1){
			jQuery("#single-supplement-container").show();
			jQuery("#single-discount-container").hide();
		}else{
			jQuery("#single-supplement-container").hide();
			jQuery("#single-discount-container").show();
		}
	}
	
	function clickBtnIgnoreDays(crtPos)
	{
		var pos = jQuery('#crt_interval_number').val();
		if( pos == crtPos)
		{
			jQuery('#dates_excursion_calendar').DatePickerHide();
			this.className = 'span_ignored_days';
			return false;
		}
		jQuery('#crt_interval_number').val(crtPos);
		jQuery('#div_interval_number_dates_'+crtPos).append( jQuery('#div_calendar') );
		jQuery('#dates_excursion_calendar').DatePickerShow();
		this.className = 'span_ignored_days_sel';
	}

	function clickBtnSeasonIgnoreDays(crtPos)
	{
		jQuery('#dates_excursion_season_calendar').DatePickerShow();
		jQuery('#div_interval_season_datai').append( jQuery('#div_season_calendar') );
		this.className = 'span_ignored_days_sel';
	}

	function addPicture(path, name)
	{
		var tb = document.getElementById('table_excursion_pictures');
		if( tb==null )
		{
			alert('Undefined table, contact administrator !');
		}
		
		var td1_new			= document.createElement('td');  
		td1_new.style.textAlign='left';
		var textarea_new 	= document.createElement('textarea');
		textarea_new.setAttribute("name","excursion_picture_info[]");
		textarea_new.setAttribute("id","excursion_picture_info");
		textarea_new.setAttribute("cols","50");
		textarea_new.setAttribute("rows","2");
		td1_new.appendChild(textarea_new);
		
		var td2_new			= document.createElement('td');  
		td2_new.style.textAlign='center';
		var img_new		 	= document.createElement('img');
		img_new.setAttribute('src', "<?php echo JURI::root().PATH_PICTURES?>" + path );
		img_new.setAttribute('class', 'img_picture_excursion');
		td2_new.appendChild(img_new);
		var span_new		= document.createElement('span');
		span_new.innerHTML 	= "<BR>"+name;
		td2_new.appendChild(span_new);
		
		var input_new_1 		= document.createElement('input');
		input_new_1.setAttribute('type',		'hidden');
		input_new_1.setAttribute('name',		'excursion_picture_enable[]');
		input_new_1.setAttribute('id',			'excursion_picture_enable[]');
		input_new_1.setAttribute('value',		'1');
		td2_new.appendChild(input_new_1);
		
		var input_new_2		= document.createElement('input');
		input_new_2.setAttribute('type',		'hidden');
		input_new_2.setAttribute('name',		'excursion_picture_path[]');
		input_new_2.setAttribute('id',			'excursion_picture_path[]');
		input_new_2.setAttribute('value',		path);
		td2_new.appendChild(input_new_2);
		
		var td3_new			= document.createElement('td');  
		td3_new.style.textAlign='center';
		
		var img_del		 	= document.createElement('img');
		img_del.setAttribute('src', "<?php echo JURI::base() ."components/".getBookingExtName()."/assets/img/del_icon.png"?>");
		img_del.setAttribute('class', 'btn_picture_delete');
		img_del.setAttribute('id', 	tb.rows.length);
		img_del.setAttribute('name', 'del_img_' + tb.rows.length);
		img_del.onmouseover  	= function(){ this.style.cursor='hand';this.style.cursor='pointer' };
		img_del.onmouseout 		= function(){ this.style.cursor='default' };
		img_del.onclick  		= function(){ 
											if( !confirm("<?php echo JText::_('LNG_CONFIRM_DELETE_PICTURE',true)?>" )) 
												return; 
											var row 		= jQuery(this).parents('tr:first');
											var row_idx 	= row.prevAll().length;
											
											jQuery('#crt_pos').val(row_idx);
											jQuery('#crt_path').val( path );
											jQuery('#btn_removefile').click();
									};

		td3_new.appendChild(img_del);
		
		var td4_new			= document.createElement('td');  
		td4_new.style.textAlign='center';
		var img_enable	 	= document.createElement('img');
		img_enable.setAttribute('src', "<?php echo JURI::base() ."components/".getBookingExtName()."/assets/img/checked.gif"?>");
		img_enable.setAttribute('class', 'btn_picture_status');
				img_enable.setAttribute('id', 	tb.rows.length);
		img_enable.setAttribute('name', 'enable_img_' + tb.rows.length);
		
		img_enable.onclick  		= function(){ 
													var form 		= document.adminForm;
													var v_status  	= null; 
													if( form.elements['excursion_picture_enable[]'].length == null )
													{
														v_status  = form.elements['excursion_picture_enable[]'];
													}
													else
													{
														pos = this.id;
														var tb = document.getElementById('table_excursion_pictures');
														if( pos >= tb.rows.length )
															pos = tb.rows.length-1;
														v_status  = form.elements['excursion_picture_enable[]'][ pos ];
													}
													
													if(v_status.value=='1')
													{
														jQuery(this).attr('src', '<?php echo JURI::base() ."components/".getBookingExtName()."/assets/img/unchecked.gif"?>');
														v_status.value ='0';
													}
													else
													{
														jQuery(this).attr('src', '<?php echo JURI::base() ."components/".getBookingExtName()."/assets/img/checked.gif"?>');
														v_status.value ='1';
													}
									};
		td4_new.appendChild(img_enable);
		
		
		var td5_new			= document.createElement('td');  
		td5_new.style.textAlign='center';
				
		td5_new.innerHTML	= 	"<span class=\'span_up\' onclick=\'var row = jQuery(this).parents(\"tr:first\");  row.insertBefore(row.prev());\'><?php echo JText::_('LNG_STR_UP',true)?></span>"+
								'&nbsp;' +
								"<span class=\'span_down\' onclick=\'var row = jQuery(this).parents(\"tr:first\"); row.insertAfter(row.next());\'><?php echo JText::_('LNG_STR_DOWN',true)?></span>";
		
		var tr_new = tb.insertRow(tb.rows.length);
		
		tr_new.appendChild(td1_new);
		tr_new.appendChild(td2_new);
		tr_new.appendChild(td3_new);
		tr_new.appendChild(td4_new);
		tr_new.appendChild(td5_new);
	}
	
	
	function removePicture(pos)
	{
		var tb = document.getElementById('table_excursion_pictures');
		//alert(tb);
		if( tb==null )
		{
			alert('Undefined table, contact administrator !');
		}
		
		if( pos >= tb.rows.length )
			pos = tb.rows.length-1;
		tb.deleteRow( pos );
	
	}
</script>
</form>


