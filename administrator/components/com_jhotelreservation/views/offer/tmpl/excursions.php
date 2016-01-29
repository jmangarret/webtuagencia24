<fieldset class="adminform">
	<legend><?php echo JText::_( 'LNG_WIZARD_OFFER_PACKAGES' ,true); ?></legend>
	<TABLE class="admintable">
		<TR>
			<TD width=10% nowrap class="key"><?php echo JText::_('LNG_EXTRA_OPTIONS',true);?> :</TD>
			<TD nowrap align=left id="offers-holder">
				<div>
					<select id='excursion_ids' name='excursion_ids[]' multiple="multiple" >
						<option value=""><?php echo JText::_('LNG_SELECT_EXCURSIONS'); ?></option>
						<?php
							$excursionArray = explode(",", $this->item->itemExcursions);	
							if(isset($this->excursions) && count($this->excursions)>0){
								foreach( $this->excursions as $excursion )
								{
								?>
								<option value='<?php echo $excursion->id?>' <?php echo in_array($excursion->id, $excursionArray)? " selected" : ""?>>
									<?php echo $excursion->excursion_name?>
								</option>
								<?php
								}
							}
						?>
					</select>
					<a href="javascript:checkAllExtraOptions()" class="select-all"><?php echo JText::_('LNG_CHECK_ALL',true)?></a>
					<a href="javascript:uncheckAllExtraOptions()" class="deselect-all"><?php echo JText::_('LNG_UNCHECK_ALL',true)?></a>
				</div>
			</TD>
		</TR>
	</TABLE>
</fieldset>


<script>
var offerSelectList = null;
jQuery(document).ready(function(){
	offerSelectList = jQuery("select#excursion_ids").selectList({ 
		sort: true,
		classPrefix: 'offer_ids',
		instance: true
	});
});

function checkAllExtraOptions(){
	//uncheckAllExtraOptions();
	jQuery(".excursion_ids-select option:not(:disabled)").each(function(){ 
		jQuery(this).attr("selected","selected"); 
		if(jQuery(this).val()!=""){
			offerSelectList.add(jQuery(this));
		}
		
	});
}  

function uncheckAllExtraOptions(){
	jQuery("#excursion_ids option").each(function(){ 
		jQuery(this).removeAttr("selected"); 
	});
	
	offerSelectList.remove();
}  



</script>