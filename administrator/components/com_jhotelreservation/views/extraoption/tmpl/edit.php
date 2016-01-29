<?php
/**
 * @package    JHotelReservation
 * @subpackage  com_jhotelreservation
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
$appSetings = JHotelUtil::getApplicationSettings();
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		Joomla.submitform(task, document.getElementById('item-form'));
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&view=extraoption');?>" method="post" name="adminForm" id="item-form">

	<fieldset class="adminform">
		<legend><?php echo JText::_('LNG_EXTRA_OPTION',true); ?></legend>
		
		<table class="admintable"  border=0>
			<tr>
				<td width=10% nowrap class="key"><?php echo JText::_('LNG_NAME',true); ?> </td>
				<td nowrap width=1% align=left>
					<input 
						type		= "text"
						name		= "name"
						id			= "name"
						value		= '<?php echo $this->item->name?>'
						size		= 50
						maxlength	= 128
						AUTOCOMPLETE=OFF
					/>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td width=10% nowrap class="key"><?php echo JText::_('LNG_STATUS',true); ?> </td>
				<td>
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "status"
						id			= "status"
						value		= '1'
						<?php echo $this->item->status==1? " checked " :""?>
					/>
					<?php echo JText::_('LNG_ENABLED',true); ?>
					&nbsp;
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "status"
						id			= "status"
						value		= '0'
						<?php echo $this->item->status==0? " checked " :""?>
					/>
					<?php echo JText::_('LNG_DISABLED',true); ?>
				</td>
			</tr>
			<tr>
				<td width=10% nowrap class="key"><?php echo JText::_('LNG_PERIOD',true); ?> </td>
				<td>
					<div style="float:left;padding-top:10px;"><?php echo JText::_('LNG_FROM',true); ?>:&nbsp;</div><div style="float:left;"><?php echo JHTML::_('calendar', $this->item->start_date==$appSetings->defaultDateValue?'': $this->item->start_date, 'start_date', 'start_date', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?></div>
					<div style="float:left;padding-top:10px;"><?php echo JText::_('LNG_TO',true); ?>:&nbsp;</div><div style="float:left;"><?php echo JHTML::_('calendar', $this->item->end_date==$appSetings->defaultDateValue?'': $this->item->end_date, 'end_date', 'end_date', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?></div>
					
				</td>
			</tr>
			<?php if($this->state->get('extraoption.hotel_id')>0){?>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_ROOMS',true); ?> :</TD>
				<TD nowrap align=left>
					<div>
						<select id='room_ids' name='room_ids[]' multiple="multiple" >
							<option value=""><?php echo JText::_('LNG_SELECT_ROOMS',true); ?></option>
							<?php
								if(isset($this->item->rooms) && count($this->item->rooms)>0){
									foreach( $this->item->rooms as $item )
									{
									?>
									<option 
										value='<?php echo $item->room_id?>'
										<?php echo isset($item->is_sel) && $item->is_sel? " selected" : ""?>
									>
										<?php echo $item->room_name?>
									</option>
									<?php
									}
								}
							?>
						</select>
					</div>
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_OFFERS',true); ?> :</TD>
				<TD nowrap align=left id="offers-holder">
					<div>
						<select id='offer_ids' name='offer_ids[]' multiple="multiple" >
							<option value=""><?php echo JText::_('LNG_SELECT_OFFERS',true); ?></option>
							<?php
								if(isset($this->item->offers) && count($this->item->offers)>0){
									foreach( $this->item->offers as $item )
									{
									?>
									<option value='<?php echo $item->offer_id?>' <?php echo isset($item->is_sel) && $item->is_sel? " selected" : ""?>>
										<?php echo $item->offer_name?>
									</option>
									<?php
									}
								}
							?>
						</select>
						<a href="javascript:checkAllOffers()" class="select-all"><?php echo JText::_('LNG_CHECK_ALL',true)?></a>
						<a href="javascript:uncheckAllOffers()" class="deselect-all"><?php echo JText::_('LNG_UNCHECK_ALL',true)?></a>
					</div>
				</TD>
				
			</TR>
			<?php } ?>
			<tr>
				<td width=10% nowrap class="key"><?php echo JText::_('LNG_DESCRIPTION',true); ?> </td>
				<td colspan="2">
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
							echo $editor->display('description_'.$_lng, $langContent, '800', '400', '70', '15', false);
						
						}
						echo JHtml::_('tabs.end');
					  ?>
				</td>
			</tr>
			<tr>
				<td width=10% nowrap class="key"><?php echo JText::_('LNG_PRICE_TYPE',true); ?> </td>
				<td>
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
				</td>
			</tr>
			<tr>
				<td width=10% nowrap class="key"><?php echo JText::_('LNG_PRICE',true); ?> </td>
				<td>
					<input 
						type		= "text"
						name		= "price"
						id			= "price"
						value		= '<?php echo $this->item->price?>'
						size		= 10
						maxlength	= 128
						AUTOCOMPLETE=OFF
					/>
				</td>
			</tr>
			<tr>
				<td width=10% nowrap class="key"><?php echo JText::_('LNG_EXTRA_TYPE',true); ?> </td>
				<td>
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "is_per_day"
						id			= "is_per_day"
						value		= '1'
						<?php echo $this->item->is_per_day==1? " checked " :""?>
					/>
					<?php echo JText::_('LNG_PER_DAY',true); ?>
					&nbsp;&nbsp;&nbsp;
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "is_per_day"
						id			= "is_per_day"
						value		= '2'
						<?php echo $this->item->is_per_day==2? " checked " :""?>
					/>
					<?php echo JText::_('LNG_PER_NIGHT',true); ?>
					&nbsp;&nbsp;&nbsp;
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "is_per_day"
						id			= "is_per_day"
						value		= '0'
						<?php echo $this->item->is_per_day==0? " checked " :""?>
					/>
					<?php echo JText::_('LNG_PER_STAY',true); ?>
				</td>
			</tr>
			<tr>
				<td width=10% nowrap class="key"><?php echo JText::_('LNG_MANDATORY',true); ?> </td>
				<td>
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "mandatory"
						id			= "mandatory"
						value		= '1'
						<?php echo $this->item->mandatory==1? " checked " :""?>
					/>
					<?php echo JText::_('LNG_YES',true); ?>
					&nbsp;
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "mandatory"
						id			= "mandatory"
						value		= '0'
						<?php echo $this->item->mandatory==0? " checked " :""?>
					/>
					<?php echo JText::_('LNG_NO',true); ?>
				</td>
			</tr>	
			<tr>
				<td width=10% nowrap class="key"><?php echo JText::_('LNG_MAP_PER_LENGHT_OF_STAY',true); ?> </td>
				<td>
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "map_per_length_of_stay"
						id			= "map_per_length_of_stay"
						value		= '1'
						<?php echo $this->item->map_per_length_of_stay==1? " checked " :""?>
					/>
					<?php echo JText::_('LNG_YES',true); ?>
					&nbsp;
					<input 
						style		= 'float:none'
						type		= "radio"
						name		= "map_per_length_of_stay"
						id			= "map_per_length_of_stay"
						value		= '0'
						<?php echo $this->item->map_per_length_of_stay==0? " checked " :""?>
					/>
					<?php echo JText::_('LNG_NO',true); ?>
				</td>
			</tr>	
			
			<tr>
				<td class="key"><?php echo JText::_('LNG_ID',true); ?></td>
				<td><?php echo $this->item->id ?></td>
				<td>&nbsp;</td>
			</tr>	
		</table>
	</fieldset>

	<fieldset class="adminform">
		<div class="form-box">
			<h2>
				<?php echo JText::_('LNG_EXTRA_OPTION_IMAGE',true);?>
			</h2>
			<div>
				<?php echo JText::_('LNG_EXTRA_OPTION_IMAGE_TEXT',true);?>
			</div>
			<div class="form-upload-elem">
				<div class="form-upload">
					<label class="optional" for="logo"><?php echo JText::_('LNG_SELECT_IMAGE_TYPE',true) ?>.</label>
					
					<input type="hidden" name="image_path" id="imageLocation"
						value="<?php echo $this->item->image_path?>"> 
					<input
						type="hidden" id="MAX_FILE_SIZE" value="2097152"
						name="MAX_FILE_SIZE"> <input type="file" id="uploadedfile"
						name="uploadedfile" size="50">
					<div class="clear"></div>
				</div>
			</div>
			<div class="picture-preview" id="picture-preview">

				<?php
				if(isset($this->item->image_path)){
					echo "<img src='".JURI::root().PATH_PICTURES.EXTRA_OPTISON_PICTURE_PATH.$this->item->image_path."'/>";
				}
				?>
			</div>
		</div>

	</fieldset>

	<input type="hidden" name="hotel_id" value="<?php echo $this->state->get('extraoption.hotel_id') ?>" />
	<input type="hidden" name="option" value="<?php echo getBookingExtName()?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>


<script>
	var offerSelectList = null;
	jQuery(document).ready(function(){

		<?php if($this->state->get('extraoption.hotel_id')>0){?>
		jQuery("select#room_ids").selectList({ 
			 sort: true,
			 classPrefix: 'room_ids',
			 onAdd: function (select, value, text) {
				addSelection(value);
			 },
			 onRemove: function (select, value, text) {
				 removeSelection(value);
				 jQuery('select#room_ids option[value='+value+']').removeAttr('selected');	
			 }
		});

		
		
		offerSelectList = jQuery("select#offer_ids").selectList({ 
			sort: true,
			classPrefix: 'offer_ids',
			instance: true,
			 onAdd: function (select, value, text) {
					
			},
			 onRemove: function (select, value, text) {
				 jQuery('select#offer_ids option[value='+value+']').removeAttr('selected');
			 }
		});
		
		<?php } ?>

		
		jQuery('#uploadedfile').change(function() {
				var fisRe 	= /^.+\.(jpg|bmp|gif|png)$/i;
				var path = jQuery('#uploadedfile').val();
				if (path.search(fisRe) == -1)
				{   
					alert(' JPG, BMP, GIF, PNG only!');
					return false;
				}  
				<?php 
					$baseUrl = JURI::base();
					if(strpos ($baseUrl,'administrator') ===  false)
						$baseUrl = $baseUrl.'administrator/';
				?>
				
				jQuery(this).upload('<?php echo $baseUrl?>components/<?php echo getBookingExtName()?>/helpers/upload.php?t=<?php echo strtotime('now')?>&_root_app=<?php echo urlencode(JPATH_ROOT)?>&_target=<?php echo urlencode(PATH_PICTURES.EXTRA_OPTISON_PICTURE_PATH.($this->item->id+0).'/')?>', function(responce) 
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
																											// alert(responce);
																											jQuery(xml).find("picture").each(function()
																											{
																												if(jQuery(this).attr("error") == 0 )
																												{
																													setUpLogo(
																																"<?php echo ($this->item->id+0).'/'?>" + jQuery(this).attr("path"),
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
        
	function setUpLogo(path, name){
	<?php 
			$baseUrl = JURI::root();
		?>
	jQuery("#imageLocation").val(path);
	var img_new = document.createElement('img');
	img_new.setAttribute('src', "<?php echo $baseUrl.PATH_PICTURES.EXTRA_OPTISON_PICTURE_PATH?>" + path );
	img_new.setAttribute('class', 'company-logo');
	jQuery("#picture-preview").empty();
	jQuery("#picture-preview").append(img_new);
	}

    function checkAllOffers(){
        //console.debug("check");
        uncheckAllOffers();
    	jQuery(".offer_ids-select option").each(function(){ 
			jQuery(this).attr("selected","selected"); 
			if(jQuery(this).val()!=""){
				offerSelectList.add(jQuery(this));
			}
		});

    }  

    function uncheckAllOffers(){
        console.debug("uncheck");
    	jQuery("#offer_ids option").each(function(){ 
			jQuery(this).removeAttr("selected"); 
		});
    	
    	offerSelectList.remove();
    }  
</script>