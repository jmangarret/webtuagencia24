<?php
/**
 * @copyright	Copyright (C) 2009-2011 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

?>
<input type="hidden" name="informationId" value="<?php echo $this->item->informations->id ?>" />
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'LNG_IMPORTANT_INFORMATION' ,true); ?></legend>
		<table class="admintable">
			<TR>
				<TD nowrap class="key"><?php echo JText::_('LNG_CHECK_IN',true); ?>:</TD>
				<TD nowrap align=left>
					<select name="check_in">
						<?php for($i=0;$i<24;$i++) {
							$j= $i.":00";	
							?>
							<option value="<?php echo $j?>" <?php echo strcmp($j, $this->item->informations->check_in)==0?'selected="selected"':''?>><?php echo $j?></option>
							<?php $j= $i.":30";	?>
							<option value="<?php echo $j?>" <?php echo strcmp($j, $this->item->informations->check_in)==0?'selected="selected"':''?>><?php echo $j?></option>
						<?php } ?>
					</select>
				</TD>
			</TR>
			<TR>
				<TD nowrap class="key"><?php echo JText::_('LNG_CHECK_OUT',true); ?>:</TD>
				<TD nowrap align=left>
					<select name="check_out">
						<?php for($i=0;$i<24;$i++) {
							$j= $i.":00";	
							?>
							<option value="<?php echo $j?>" <?php echo strcmp($j, $this->item->informations->check_out)==0?'selected="selected"':''?>><?php echo $j?></option>
							<?php $j= $i.":30";	?>
							<option value="<?php echo $j?>" <?php echo strcmp($j, $this->item->informations->check_out)==0?'selected="selected"':''?>><?php echo $j?></option>
						<?php } ?>
					</select>
				</TD>
			</TR>
			<TR>
				<TD nowrap class="key"><?php echo JText::_('LNG_PARKING',true); ?>:</TD>
				<TD nowrap="nowrap" align=left>
					<?php echo $this->elements->parking; ?>
					<div style="display:inline; padding-left:20px">
						<?php echo JText::_('LNG_PRICE',true); ?> <input type="input" value="<?php echo $this->item->informations->price_parking?>"  name="price_parking" size="7"/>
						 &nbsp;
						 <?php echo JText::_('LNG_PERIOD',true); ?> &nbsp;
						<input type="input" value="<?php echo $this->item->informations->parking_period?>"  name=parking_period />
					</div>
				</TD>
				<td>


				</td>
			</TR>
			<TR>
				<TD nowrap class="key"><?php echo JText::_('LNG_PETS',true); ?>:</TD>
				<TD nowrap align=left>
					<?php echo $this->elements->allowPets; ?>
					<div style="display:inline;padding-left:20px">
						<?php echo JText::_('LNG_PRICE',true); ?> <input type="input" value="<?php echo $this->item->informations->price_pets?>" name="price_pets" size="7"/> <input type="input" value="<?php echo $this->item->informations->pet_info?>" name="pet_info" size="35"/>
					</div>
							 
				</TD>
			</TR>
			
			<TR>
				<TD nowrap class="key"><?php echo JText::_('LNG_CITY_TAX',true); ?>:</TD>
				<TD nowrap align=left>
					<input type="text" class="validate[required,custom[number]] text-input" id="city_tax" name="city_tax" size="10" value="<?php echo $this->item->informations->city_tax ?>">
						<input  type="checkbox" name="city_tax_percent" value="1" <?php echo $this->item->informations->city_tax_percent == 1?'checked':'' ?>>(%)
				</TD>
				
				
			</TR>
			<TR>
				<TD nowrap class="key"><?php echo JText::_('LNG_NUMBER_OF_ROOMS',true); ?>:</TD>
				<TD nowrap align=left>
					<input type="text" name="number_of_rooms" id="number_of_rooms" class="validate[required,custom[integer],min[1]] input-text" size="10" value="<?php echo $this->item->informations->number_of_rooms ?>">
				</TD>
			</TR>
			<TR>
				<TD nowrap class="key"><?php echo JText::_('LNG_CANCELATION_DAYS',true); ?>:</TD>
				<td>
					<select id="cancellation_days" name="cancellation_days">
						<?php for($i=1;$i<100;$i++) {
								?>
							<option value="<?php echo $i?>" <?php echo $i==$this->item->informations->cancellation_days ?'selected="selected"':''?>><?php echo $i?></option>
						<?php } ?>
					</select>
					<input type="checkbox" value="1" name="uvh_agree" id="uvh_agree" <?php echo $this->item->informations->uvh_agree == 1?'checked':''?>> <label for="uvh_agree"><?php echo JText::_('LNG_AGREE_WITH_UVH'); ?></label>
					<br/>
					<textarea class="inputbox" name="cancellation_conditions" rows=6 cols=150><?php echo $this->item->informations->cancellation_conditions ?></textarea>
				</td>
			</TR>
			<TR>
				<TD nowrap class="key"><?php echo JText::_('LNG_INTERNET_WIFI',true); ?>:</TD>
				<TD nowrap align=left>
					<?php echo $this->elements->wifi; ?>
					<div style="display:inline;padding-left:20px">
						<?php echo JText::_('LNG_PRICE',true); ?> &nbsp;&nbsp;&nbsp;&nbsp;
						<input type="input" value="<?php echo $this->item->informations->price_wifi?>" name="price_wifi" size="5" />
						 &nbsp;
						<?php echo JText::_('LNG_PERIOD',true); ?> 
						 &nbsp;
						<input type="input" value="<?php echo $this->item->informations->wifi_period?>" name="wifi_period" size="25" />
					</div>
				</TD>
			</TR>
			<TR>
				<TD nowrap class="key"><?php echo JText::_('LNG_SUITABLE_FOR_DISABLED',true); ?>:</TD>
				<TD nowrap align=left>
					<?php echo $this->elements->suitableDisabled; ?>
				</TD>
			</TR>
			<TR>
				<TD nowrap class="key"><?php echo JText::_('LNG_PUBLIC_TRANSPORTATION',true); ?>:</TD>
				<TD nowrap align=left>
					<?php echo $this->elements->publicTransport; ?>
				</TD>
			</TR>
			<TR>
				<TD nowrap class="key"><?php echo JText::_('LNG_HOTEL_PAYMENT_OPTIONS',true); ?>:</TD>
				<TD nowrap align=left>
					<div id="paymentOption-holder" class="option-holder">
						<?php
							echo $this->paymentoptions->displayPaymentOptions( $this->item->paymentOptions, $this->item->selectedPaymentOptions );
						?>
					</div>
				<?php 
					if (checkUserAccess(JFactory::getUser()->id,"manage_options")){
				?>			
					<div class="manage-option-holder">
						<a href="javascript:" onclick="showManagePaymentOptions()"><?php  echo isset($this->item->hotel_id) ? JText::_('LNG_MANAGE_PAYMENT_OPTIONS',true):"" ?></a>
					</div>		
				<?php 
					}
				?>			
				</TD>
			</TR>
			<TR>
				<TD nowrap class="key"><?php echo JText::_('LNG_CHILDREN_AGE_CATEGORY',true); ?>:</TD>
				<TD nowrap align=left>
					<textarea name="children_category" id="children_category"  class="validate[required]" rows=6 cols=50><?php echo $this->item->informations->children_category ?></textarea>
				</TD>
			</TR>
			<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
			<!--MICOD
			En éste bloque se está mostrando los nuevos campos, checked y combos para las edades de los niños; útil para aquellos hoteles con tarifas propias según la edad del niño-->
			<TR>
				<TD nowrap class="key">Niños libre de tarifas</TD>
				<TD nowrap align=left>
				 	De
					<select name="edad_libre_min" id="edad_libre_min" style="">
						<?php 
						$edad_libre = explode("|", $this->item->informations->niños_libre_tarifas);
						for($i=0;$i<=17;$i++) {
							?>
							<option value="<?php echo $i?>" <?php echo strcmp($i, $edad_libre[0])==0?'selected="selected"':''?>><?php echo $i?></option>
						<?php } ?>
					</select> a 
					<select name="edad_libre_max" id="edad_libre_max" style="">
						<?php for($i=0;$i<=17;$i++) {
							?>
							<option value="<?php echo $i?>" <?php echo strcmp($i, $edad_libre[1])==0?'selected="selected"':''?>><?php echo $i?></option>
						<?php } ?>
					</select> años
					<input type="checkbox" name="activ_desactiv_libre" id="activ_desactiv_libre" value="1" <?php echo $edad_libre[2] == 1?'checked':'' ?>><span id="check_libre_tarifa" >(Activar/Desactivar)</span>
				</TD>
			</TR>
			<TR>
				<TD nowrap class="key">Niños con tarifa ajustada</TD>
				<TD nowrap align=left>
					De
					<select name="edad_tarifa_min" id="edad_tarifa_min">
						<?php 
						$edad_tarifa = explode("|", $this->item->informations->niños_tarifa_ajustada);
						for($i=0;$i<=17;$i++) {
							?>
							<option value="<?php echo $i?>" <?php echo strcmp($i, $edad_tarifa[0])==0?'selected="selected"':''?>><?php echo $i?></option>
						<?php } ?>
					</select> a 
					<select name="edad_tarifa_max" id="edad_tarifa_max">
						<?php for($i=0;$i<=17;$i++) {
							?>
							<option value="<?php echo $i?>" <?php echo strcmp($i, $edad_tarifa[1])==0?'selected="selected"':''?>><?php echo $i?></option>
						<?php } ?>
					</select> años
					<input type="input" value="<?php echo $edad_tarifa[2]?>" name="valor_tarifa_ajustada" id="valor_tarifa_ajustada" size="15" />
					<input type="checkbox" name="porcent_tarifa_ajustada" id="porcent_tarifa_ajustada" value="1" <?php echo $edad_tarifa[3] == 1?'checked':'' ?>>(%)
					<input type="checkbox" name="activ_desactiv_ajust" id="activ_desactiv_ajust" value="1" <?php echo $edad_tarifa[4] == 1?'checked':'' ?>><span id="check_tarifa_ajustada" >(Activar/Desactivar)</span>
				</TD>
				</TD>
			</TR>
			<TR>
				<TD nowrap class="key">Niños con tarifa de adulto:</TD>
				<TD nowrap align=left>
					A partir de: 
					<select name="edad_tarifa_adult" id="edad_tarifa_adult">
						<?php 
						$edad_adulta = explode("|", $this->item->informations->edad_tarifa_adult);
						for($i=0;$i<=17;$i++) {
							?>
							<option value="<?php echo $i?>" <?php echo strcmp($i, $edad_adulta[0])==0?'selected="selected"':''?>><?php echo $i?></option>
						<?php } ?>
					</select> años
					<input type="checkbox" name="activ_desactiv_adult" id="activ_desactiv_adult" value="1" <?php echo $edad_adulta[1] == 1?'checked':'' ?>><span id="check_tarifa_adulto" >(Activar/Desactivar)</span>
				</TD>
				</TD>
			</TR>
			<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
		</table>
	</fieldset>

<div id="showPaymentOptionsNewFrm" style="display:none;">
  		<div id="popup_container">
    <!--Content area starts-->

    		<div class="head">
      		    <div class="head_inner">
               <h2> <?php echo JText::_('LNG_MANAGE_PAYMENT_OPTIONS',true); ?></h2>
               <a href="#" class="cancel_btn" onclick="closePopup();"><span class="cancel_icon">&nbsp;</span><?php echo JText::_('LNG_CANCEL',true); ?></a></div>
            </div>
            <div class="content">
                    <div class="descriptions" >

                       <div id="content_section_tab_data1">
                       	<span id="frm_error_msg_paymentOption" class="text_error" style="display: none;"></span> 
						<div class="row" id="paymentOption-container">
						</div>
						 
					 	<div class="option-row">
							<a href="javascript:" onclick="addNewPaymentOption(0,'')"><?php echo JText::_('LNG_ADD_NEW_PAYMENT_OPTION',true); ?></a>
						</div>
						<div class="proceed_row">
                           <!--button sec starts-->
                              <button name="btnSave" id="btnSave" onclick="savePaymentOptions(this.form);" type="submit" class="submit">    
                                     <span><span>Save</span></span>
                              </button>
                              <input value="Cancel" class="cancel" name="btnCancel" id="btnCancel" onclick="closePopup();" type="button">
                          </div>
                          <!--button sec ends-->
                        </div>
                        <div class="buttom_sec" id="frmPaymentOptionsFormSubmitWait" style="display: none;"> <span class="error_msg" style="background-image: none; color: rgb(0, 0, 0) ! important;">Please wait...</span> </div>
            </div>
          </div>
          </div>
     </div>        
     
     <script>
	     jQuery("select#paymentOptions").selectList({ 
			 sort: true,
			 classPrefix: 'paymentOptions',
			 onAdd: function (select, value, text) {
				    if(value=='new'){
					    return true;
				    }
			 }
	
		});

/*-----------------------------------------------------------------------------*/
/*MICOD
En éste bloque estoy aplicando la animasión Disabled para los combos de los niños libre de tarifas, tarifas ajustadas
y tarifas de adulto. Además de eliminar el "name" de sus etiquetas html para que no guarde en la base de datos*/
	jQuery(document).ready(function(){  
  
  		if(jQuery("#activ_desactiv_libre").is(':checked')) {  
            jQuery( "#edad_libre_min" ).attr("name","edad_libre_min");
            jQuery( "#edad_libre_min" ).css("background-color","#FFFFFF");
            jQuery( "#edad_libre_min" ).prop( "disabled", false );
            jQuery( "#edad_libre_max" ).attr("name","edad_libre_max");
            jQuery( "#edad_libre_max" ).css("background-color","#FFFFFF");
            jQuery( "#edad_libre_max" ).prop( "disabled", false );
            jQuery('#check_libre_tarifa').text( "Activado" );
        } else {  
            jQuery( "#edad_libre_min" ).removeAttr("name");
            jQuery( "#edad_libre_min" ).css("background-color","#B8B8B8");
            jQuery( "#edad_libre_min" ).prop( "disabled", true);
            jQuery( "#edad_libre_max" ).removeAttr("name");
            jQuery( "#edad_libre_max" ).css("background-color","#B8B8B8");
            jQuery( "#edad_libre_max" ).prop( "disabled", true);
            jQuery('#check_libre_tarifa').text( "Desactivado" );
        }



        if(jQuery("#activ_desactiv_ajust").is(':checked')) {  
            jQuery( "#edad_tarifa_min" ).attr("name","edad_tarifa_min");
            jQuery( "#edad_tarifa_min" ).css("background-color","#FFFFFF");
            jQuery( "#edad_tarifa_min" ).prop( "disabled", false );
            jQuery( "#edad_tarifa_max" ).attr("name","edad_tarifa_max");
            jQuery( "#edad_tarifa_max" ).css("background-color","#FFFFFF");
            jQuery( "#edad_tarifa_max" ).prop( "disabled", false );
            jQuery( "#valor_tarifa_ajustada" ).attr("name","valor_tarifa_ajustada");
            jQuery( "#valor_tarifa_ajustada" ).css("background-color","#FFFFFF");
            jQuery( "#valor_tarifa_ajustada" ).prop( "disabled", false );
            jQuery( "#porcent_tarifa_ajustada" ).attr("name","porcent_tarifa_ajustada");
            jQuery( "#porcent_tarifa_ajustada" ).css("background-color","#FFFFFF");
            jQuery( "#porcent_tarifa_ajustada" ).prop( "disabled", false );
            jQuery('#check_tarifa_ajustada').text( "Activado" );
        } else {  
            jQuery( "#edad_tarifa_min" ).removeAttr("name");
            jQuery( "#edad_tarifa_min" ).css("background-color","#B8B8B8");
            jQuery( "#edad_tarifa_min" ).prop( "disabled", true);
            jQuery( "#edad_tarifa_max" ).removeAttr("name");
            jQuery( "#edad_tarifa_max" ).css("background-color","#B8B8B8");
            jQuery( "#edad_tarifa_max" ).prop( "disabled", true);
            jQuery( "#valor_tarifa_ajustada" ).removeAttr("name");
            jQuery( "#valor_tarifa_ajustada" ).css("background-color","#B8B8B8");
            jQuery( "#valor_tarifa_ajustada" ).prop( "disabled", true);
            jQuery( "#porcent_tarifa_ajustada" ).removeAttr("name");
            jQuery( "#porcent_tarifa_ajustada" ).css("background-color","#B8B8B8");
            jQuery( "#porcent_tarifa_ajustada" ).prop( "disabled", true);
            jQuery('#check_tarifa_ajustada').text( "Desactivado" );
        }


        if(jQuery("#activ_desactiv_adult").is(':checked')){
        	jQuery( "#edad_tarifa_adult" ).attr("name","edad_tarifa_adult");
            jQuery( "#edad_tarifa_adult" ).css("background-color","#FFFFFF");
            jQuery( "#edad_tarifa_adult" ).prop( "disabled", false );
            jQuery('#check_tarifa_adulto').text( "Activado" );
        } else {  
            jQuery( "#edad_tarifa_adult" ).removeAttr("name");
            jQuery( "#edad_tarifa_adult" ).css("background-color","#B8B8B8");
            jQuery( "#edad_tarifa_adult" ).prop( "disabled", true);
            jQuery('#check_tarifa_adulto').text( "Desactivado" );
        }

/*------------------------------------------------------------------------------------------*/


    jQuery("#activ_desactiv_libre").click(function() {  
        if(jQuery("#activ_desactiv_libre").is(':checked')) {  
            jQuery( "#edad_libre_min" ).attr("name","edad_libre_min");
            jQuery( "#edad_libre_min" ).css("background-color","#FFFFFF");
            jQuery( "#edad_libre_min" ).prop( "disabled", false );
            jQuery( "#edad_libre_max" ).attr("name","edad_libre_max");
            jQuery( "#edad_libre_max" ).css("background-color","#FFFFFF");
            jQuery( "#edad_libre_max" ).prop( "disabled", false );
            jQuery('#check_libre_tarifa').text( "Activado" );
        } else {  
            jQuery( "#edad_libre_min" ).removeAttr("name");
            jQuery( "#edad_libre_min" ).css("background-color","#B8B8B8");
            jQuery( "#edad_libre_min" ).prop( "disabled", true);
            jQuery( "#edad_libre_max" ).removeAttr("name");
            jQuery( "#edad_libre_max" ).css("background-color","#B8B8B8");
            jQuery( "#edad_libre_max" ).prop( "disabled", true);
            jQuery('#check_libre_tarifa').text( "Desactivado" );
        }
    });  
	
	jQuery("#activ_desactiv_ajust").click(function(){
		if(jQuery("#activ_desactiv_ajust").is(':checked')) {  
            jQuery( "#edad_tarifa_min" ).attr("name","edad_tarifa_min");
            jQuery( "#edad_tarifa_min" ).css("background-color","#FFFFFF");
            jQuery( "#edad_tarifa_min" ).prop( "disabled", false );
            jQuery( "#edad_tarifa_max" ).attr("name","edad_tarifa_max");
            jQuery( "#edad_tarifa_max" ).css("background-color","#FFFFFF");
            jQuery( "#edad_tarifa_max" ).prop( "disabled", false );
            jQuery( "#valor_tarifa_ajustada" ).attr("name","valor_tarifa_ajustada");
            jQuery( "#valor_tarifa_ajustada" ).css("background-color","#FFFFFF");
            jQuery( "#valor_tarifa_ajustada" ).prop( "disabled", false );
            jQuery( "#porcent_tarifa_ajustada" ).attr("name","porcent_tarifa_ajustada");
            jQuery( "#porcent_tarifa_ajustada" ).css("background-color","#FFFFFF");
            jQuery( "#porcent_tarifa_ajustada" ).prop( "disabled", false );
            jQuery('#check_tarifa_ajustada').text( "Activado" );
        } else {  
            jQuery( "#edad_tarifa_min" ).removeAttr("name");
            jQuery( "#edad_tarifa_min" ).css("background-color","#B8B8B8");
            jQuery( "#edad_tarifa_min" ).prop( "disabled", true);
            jQuery( "#edad_tarifa_max" ).removeAttr("name");
            jQuery( "#edad_tarifa_max" ).css("background-color","#B8B8B8");
            jQuery( "#edad_tarifa_max" ).prop( "disabled", true);
            jQuery( "#valor_tarifa_ajustada" ).removeAttr("name");
            jQuery( "#valor_tarifa_ajustada" ).css("background-color","#B8B8B8");
            jQuery( "#valor_tarifa_ajustada" ).prop( "disabled", true);
            jQuery( "#porcent_tarifa_ajustada" ).removeAttr("name");
            jQuery( "#porcent_tarifa_ajustada" ).css("background-color","#B8B8B8");
            jQuery( "#porcent_tarifa_ajustada" ).prop( "disabled", true);
            jQuery('#check_tarifa_ajustada').text( "Desactivado" );
        }
	});

	jQuery("#activ_desactiv_adult").click(function(){
		if(jQuery("#activ_desactiv_adult").is(':checked')){
        	jQuery( "#edad_tarifa_adult" ).attr("name","edad_tarifa_adult");
            jQuery( "#edad_tarifa_adult" ).css("background-color","#FFFFFF");
            jQuery( "#edad_tarifa_adult" ).prop( "disabled", false );
            jQuery('#check_tarifa_adulto').text( "Activado" );
        } else {  
            jQuery( "#edad_tarifa_adult" ).removeAttr("name");
            jQuery( "#edad_tarifa_adult" ).css("background-color","#B8B8B8");
            jQuery( "#edad_tarifa_adult" ).prop( "disabled", true);
            jQuery('#check_tarifa_adulto').text( "Desactivado" );
        }
	});

});  
/*-----------------------------------------------------------------------------*/
     </script>