<?php

$cantAdults = 1;
$cantChilds = 1;
$urlConditions = JRoute::_("index.php?option=com_content&view=article&tmpl=component&id=".$this->data["article"]["conditions_id"]);
$urlCancelPolicy = JRoute::_("index.php?option=com_content&view=article&tmpl=component&id=".$this->data["article"]["cancel_id"]);
$minAgeAdults = $this->catalogoComponentConfig->cfg_adults_age;
$app =& JFactory::getApplication();
if(isset($_SESSION["guestprevorder"]))
$guest_before = $_SESSION["guestprevorder"];
?>
<div class="conte_plan">
	<div class="top_titulo_cp_detail">
	<?php echo $this->data["name"]?>
	<?php /*echo ($this->data['featured']==1)?"<img src='".JURI::base()."images/paquete_recomendado_star_red.png' title='".$this->data["name"]."'/>":"";*/?>
	</div>
	<?php if($this->data["stars"]>0):?>
	<div class="precio_tit_precio_plan_dispo">
		<div class="esquina_left_dias_plan"></div>
		<div class="center_dias_plan">
			<div class="derecha_desc_plan">
			<?php for($star=0; $star<$this->data["stars"]; $star++):?>
				<img
					src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/stars_1b.png" />
					<?php endfor;?>
			</div>

		</div>
		<div class="esquina_right_dias_plan"></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<div class="ciudad_pais_precio_plan">
		<div class="ciudad_pais_plan">
			<div>
				<strong><?php echo JText::_( 'CP.HOTEL.DETAIL.COUNTRY' ); ?>:</strong>&nbsp;
				<?php echo $this->data["country"]["name"]?>
			</div>
			<div>
				| <strong><?php echo JText::_( 'CP.HOTEL.DETAIL.CITY' ); ?>:</strong>&nbsp;
				<?php echo $this->data["city"]["name"]?>
			</div>
		</div>
	</div>
	<form
		action="<?php echo JRoute::_("index.php?option=com_catalogo_planes&view=hotel&layout=booking")?>"
		method="POST" id="frm_guest">
		<input id="user_type" type="hidden" name="user_type" val="" />
		<div class="conte_general">
			<div class="conte_guest">
			<?php echo JText::_('CP.MESSAGE.FIELDS.REQUIRED'); ?>
			</div>

			<?php //for($i=0; $i<$this->data["guest"]["adults"]+$this->data["guest"]["childs"]; $i++):
			for($i=0,$n=0; $n<count($this->data["rates"]); $i++,$n++):
			for($j=0; $j<$this->data["rates"][$n]["quantity"];$j++, $i++):
			$classRequired = "";
			if($i==0):
			$classRequired = "required";
			endif;

			?>
			<div class="conte_guest">
				<div class="conte_guest_number">
					<img
						src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_adulto.png" />
						<?php if($i<$this->data["guest"]["adults"]):
						echo JText::_("CP.ADULT")." ".$cantAdults;
						$paxType = "ADT";
						$cantAdults++;
						else:
						echo JText::_("CP.CHILD")." ".$cantChilds;
						$paxType = "CHD";
						$cantChilds++;
						endif;?>
				</div>
				<table>
					<tr>
					<?php if($i<$this->data["guest"]["adults"]):?>
						<td><?php echo JText::_("CP.GUEST.TREATMENT")?> <span
							class="field_required"><?php echo ($classRequired!="")?"*":"";?>
						</span>
						</td>
						<td><select name="guest[<?php echo $i?>][treatment]"
							id="treatment" class="<?php echo $classRequired?>">
								<option value="">
								<?php echo JText::_("CP.GUEST.SELECT")?>
								</option>
								<option value="1"
								<?php echo(isset($guest_before['treatment']) && $guest_before['treatment']==1 && $i==0)?'selected="selected"':''; ?>>
									<?php echo JText::_("CP.GUEST.TREATMENT.MR")?>
								</option>
								<option value="2"
								<?php echo(isset($guest_before['treatment']) && $guest_before['treatment']==2 && $i==0)?'selected="selected"':''; ?>>
									<?php echo JText::_("CP.GUEST.TREATMENT.MRS")?>
								</option>
								<option value="3"
								<?php echo(isset($guest_before['treatment']) && $guest_before['treatment']==3 && $i==0)?'selected="selected"':''; ?>>
									<?php echo JText::_("CP.GUEST.TREATMENT.MS")?>
								</option>
						</select>
						</td>
						<?php endif;?>
						<?php /*<td><?php echo JText::_("CP.GUEST.GENRE")?>
						<span class="field_required"><?php echo ($classRequired!="")?"*":"";?></span>
						</td>
						<td>
						<select name="guest[<?php echo $i?>][gender]" class="<?php echo $classRequired?>">
						<option value="M" <?php echo($guest_before['gender']=='M' && $i==0)?'selected="selected"':''; ?>><?php echo JText::_("CP.GUEST.GENRE.M")?></option>
						<option value="F" <?php echo($guest_before['gender']=='F' && $i==0)?'selected="selected"':''; ?>><?php echo JText::_("CP.GUEST.GENRE.F")?></option>
						</select>
						</td>*/?>
					</tr>
					<tr>
						<td><?php echo JText::_("CP.GUEST.NAME")?><span
							class="field_required">*</span>
						</td>
						<td><input type="text" name="guest[<?php echo $i?>][name]"
							value="<?php echo(isset($guest_before['name']) && $i==0)?$guest_before["name"]:''; ?>"
							class="required" />
						</td>
						<td><?php echo JText::_("CP.GUEST.LASTNAME")?><span
							class="field_required">*</span> <input type="hidden"
							name="guest[<?php echo $i?>][type]" value="<?php echo $paxType?>">
						</td>
						<td><input type="text" name="guest[<?php echo $i?>][lastname]"
							value="<?php echo(isset($guest_before['lastname']) && $i==0)?$guest_before["lastname"]:''; ?>"
							class="required" />
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_("CP.GUEST.DOCUMENTTYPE")?> <span
							class="field_required"><?php echo ($classRequired!="")?"*":"";?>
						</span>
						</td>
						<td><select name="guest[<?php echo $i?>][documenttype]"
							id="documenttype" class="<?php echo $classRequired?>">
								<option value="">
								<?php echo JText::_("CP.GUEST.SELECT")?>
								</option>
								<option value="1"
								<?php echo(isset($guest_before['documenttype']) && $guest_before['documenttype']==1 && $i==0)?'selected="selected"':''; ?>>
									<?php echo JText::_("CP.GUEST.DOCUMENTTYPE.ID")?>
								</option>
								<option value="2"
								<?php echo(isset($guest_before['documenttype']) && $guest_before['documenttype']==2 && $i==0)?'selected="selected"':''; ?>>
									<?php echo JText::_("CP.GUEST.DOCUMENTTYPE.EXT")?>
								</option>
								<option value="3"
								<?php echo(isset($guest_before['documenttype']) && $guest_before['documenttype']==3 && $i==0)?'selected="selected"':''; ?>>
									<?php echo JText::_("CP.GUEST.DOCUMENTTYPE.PASS")?>
								</option>
						</select>
						</td>
						<td><?php echo JText::_("CP.GUEST.DOCUMENT")?> <span
							class="field_required"><?php echo ($classRequired!="")?"*":"";?>
						</span>
						</td>
						<td><input type="text" name="guest[<?php echo $i?>][document]"
							value="<?php echo(isset($guest_before['document']) && $guest_before['document'] && $i==0)?$guest_before["document"]:''; ?>"
							class="<?php echo $classRequired?>" />
						</td>

					</tr>
					<tr>
						<td><?php echo JText::_("CP.GUEST.NACIONALITY")?> <span
							class="field_required"><?php echo ($classRequired!="")?"*":"";?>
						</span>
						</td>

						<td><input name="guest[<?php echo $i?>][nationality]" type="text"
							class="<?php echo $classRequired?> nationality_autocomplete"
							id="nationality<?php echo $i?>"
							value="<?php echo(isset($guest_before['nationality']) && $i==0)?$guest_before["nationality"]:''; ?>" />
						</td>
						<?php if($i<$this->data["guest"]["adults"]):?>
						<td><?php echo JText::_("CP.GUEST.BIRTHDAY")?><span
							class="field_required">*</span>
						</td>
						<?php endif;?>
						<?php if($i<$this->data["guest"]["adults"]):?>

						<td><?php /*<div class="dateBirthday">
						<input type="text" name="guest[<?php echo $i?>][birthdate]" class="required" size="10" readOnly/>
						</div> */?> <select name="guest[<?php echo $i?>][bday]" id="bDay"
							class="required">
							<?php for($day=1;$day<=31;$day++):?>
								<option value="<?php echo $day?>"
								<?php echo(isset($guest_before['bday']) && $guest_before['bday']==$day && $i==0)?'selected="selected"':'' ?>>
									<?php echo $day?>
								</option>
								<?php endfor;?>
						</select> <select name="guest[<?php echo $i?>][bmonth]"
							id="bMonth" class="required">
								<option value="1"
								<?php echo(isset($guest_before['bmonth']) && $guest_before['bmonth']==1 && $i==0)?'selected="selected"':'' ?>>
									<?php echo JText::_('CP.MONTHS.JANUARY.SHORT.LABEL'); ?>
								</option>
								<option value="2"
								<?php echo(isset($guest_before['bmonth']) && $guest_before['bmonth']==2 && $i==0)?'selected="selected"':'' ?>>
									<?php echo JText::_('CP.MONTHS.FEBRUARY.SHORT.LABEL'); ?>
								</option>
								<option value="3"
								<?php echo(isset($guest_before['bmonth']) && $guest_before['bmonth']==3 && $i==0)?'selected="selected"':'' ?>>
									<?php echo JText::_('CP.MONTHS.MARCH.SHORT.LABEL'); ?>
								</option>

								<option value="4"
								<?php echo(isset($guest_before['bmonth']) && $guest_before['bmonth']==4 && $i==0)?'selected="selected"':'' ?>>
									<?php echo JText::_('CP.MONTHS.APRIL.SHORT.LABEL'); ?>
								</option>
								<option value="5"
								<?php echo(isset($guest_before['bmonth']) && $guest_before['bmonth']==5 && $i==0)?'selected="selected"':'' ?>>
									<?php echo JText::_('CP.MONTHS.MAY.SHORT.LABEL'); ?>
								</option>
								<option value="6"
								<?php echo(isset($guest_before['bmonth']) && $guest_before['bmonth']==6 && $i==0)?'selected="selected"':'' ?>>
									<?php echo JText::_('CP.MONTHS.JUNE.SHORT.LABEL'); ?>
								</option>
								<option value="7"
								<?php echo(isset($guest_before['bmonth']) && $guest_before['bmonth']==7 && $i==0)?'selected="selected"':'' ?>>
									<?php echo JText::_('CP.MONTHS.JULY.SHORT.LABEL'); ?>
								</option>
								<option value="8"
								<?php echo(isset($guest_before['bmonth']) && $guest_before['bmonth']==8 && $i==0)?'selected="selected"':'' ?>>
									<?php echo JText::_('CP.MONTHS.AUGUST.SHORT.LABEL'); ?>
								</option>
								<option value="9"
								<?php echo(isset($guest_before['bmonth']) && $guest_before['bmonth']==9 && $i==0)?'selected="selected"':'' ?>>
									<?php echo JText::_('CP.MONTHS.SEPTEMBER.SHORT.LABEL'); ?>
								</option>
								<option value="10"
								<?php echo(isset($guest_before['bmonth']) && $guest_before['bmonth']==10 && $i==0)?'selected="selected"':'' ?>>
									<?php echo JText::_('CP.MONTHS.OCTOBER.SHORT.LABEL'); ?>
								</option>
								<option value="11"
								<?php echo(isset($guest_before['bmonth']) && $guest_before['bmonth']==11 && $i==0)?'selected="selected"':'' ?>>
									<?php echo JText::_('CP.MONTHS.NOVEMBER.SHORT.LABEL'); ?>
								</option>
								<option value="12"
								<?php echo(isset($guest_before['bmonth']) && $guest_before['bmonth']==12 && $i==0)?'selected="selected"':'' ?>>
									<?php echo JText::_('CP.MONTHS.DECEMBER.SHORT.LABEL'); ?>
								</option>
						</select> <?php $currentYear=date("Y");
						$currentYear=$currentYear-$minAgeAdults;
						$anos = 100;?> <select name="guest[<?php echo $i?>][byear]"
							id="bYear" class="required">
							<?php for($y=$currentYear;$y>=($currentYear-$anos);$y--):?>
								<option value="<?php echo $y?>"
								<?php echo(isset($guest_before['byear']) && $guest_before['byear']==$y && $i==0)?'selected="selected"':'' ?>>
									<?php echo $y?>
								</option>
								<?php endfor;?>
						</select>
						</td>
						<?php endif;?>
					</tr>

				</table>
			</div>
			<?php endfor;
			endfor;?>
			<div class="conte_guest">
				<div class="conte_guest_number">
					<img
						src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_info_contacto.png" />
						<?php echo JText::_("CP.GUEST.CONTACTINFORMATION")?>
				</div>
				<table width="100%" cellspacing="3" cellpadding="2" border="0">
					<tr>
						<td width="40%"><?php echo JText::_("CP.GUEST.EMAIL.LABEL"); ?><span
							class="field_required">*</span>&nbsp;</td>
						<td width="60%"><input id="mail_contact" name="contact[mail]"
							value="" type="text" class="campo_form_reserva required"
							title="<?php echo JText::_("CP.GUEST.EMAIL.LABEL"); ?>">
						</td>
					</tr>
					<?php  /*<tr>
					<td><?php echo JText::_("CP.GUEST.EMAIL.CONFIRMATION.LABEL"); ?><span class="field_required">*</span>&nbsp;</td>
					<td>
					<input id="repeat_mail" name="contact[repeat_mail]" value="" type="text" class="campo_form_reserva required">
					</td>
					</tr>*/ ?>
					<tr>
						<td><?php echo JText::_("CP.GUEST.PRINCIPAL.PHONE.LABEL"); ?><span
							class="field_required">*</span>&nbsp;</td>
						<td><input onkeypress="return numberValidate(event);"
							name="contact[cod_country]" type="text" class="campo_form_fecha "
							id="cod_country" maxlength="4" size="4"
							title="<?php echo JText::_("CP.GUEST.PRINCIPAL.PHONE.LABEL").' - '.JText::_("CP.GUEST.CODCOUNTRY.LABEL.FORM"); ?>" />
							<input onkeypress="return numberValidate(event);"
							name="contact[cod_city]" type="text" class="campo_form_fecha "
							id="cod_city" maxlength="4" size="4"
							title="<?php echo JText::_("CP.GUEST.PRINCIPAL.PHONE.LABEL").' - '.JText::_("CP.GUEST.CODZONE.LABEL.FORM"); ?>" />
							<input onkeypress="return numberValidate(event);"
							name="contact[phone]" type="text"
							class="campo_form_fecha required" id="phone" maxlength="10"
							size="10"
							title="<?php echo JText::_("CP.GUEST.PRINCIPAL.PHONE.LABEL").' - '.JText::_("CP.GUEST.CODEPHONE.LABEL.FORM"); ?>" />
							<?php /*<select name="contact[phone_type]" class="campo_lista_pasajeros" id="phone_type">
							<option value="M"><?php echo JText::_("CP.GUEST.MOBILE.LABEL"); ?></option>
							<option value="H"><?php echo JText::_("CP.GUEST.HOME.LABEL"); ?></option>
							</select>*/ ?> <br /> <span class="description"><?php echo JText::_("CP.GUEST.CODCOUNTRY.LABEL.FORM"); ?>
						</span> &nbsp;<span class="description"><?php echo JText::_("CP.GUEST.CODZONE.LABEL.FORM"); ?>
						</span> &nbsp;<span class="description"><?php echo JText::_("CP.GUEST.CODEPHONE.LABEL.FORM"); ?>
						</span>
						</td>
					</tr>
					<?php /*<tr>
					<td><?php echo JText::_("CP.GUEST.OBSERVATION.LABEL"); ?></td>
					<td>
					<textArea name="contact[observation]" maxlength="1000" class="campo_form_reserva" rows="4"></textArea>
					</td>
					</tr> */?>
				</table>
			</div>
			<div class="conte_payment">
				<div class="conte_guest_number">
					<img
						src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_metodo_pago.png" />
						<?php echo JText::_("CP.GUEST.PAYMENTMETOD")?>
				</div>
				<div class="contain_payment">
				<?php if($this->data["haveStock"]):?>
					<input type="radio" name="payment" id="credit" value="credit" /> <label
						for="credit"><?php echo JText::_("CP.GUEST.PAYMENT.CREDIT")?> </label>
						<?php endif;?>
					<input type="radio" name="payment" id="pending" value="agency" /> <label
						for="pending"><?php echo JText::_("CP.GUEST.PAYMENT.PENDING")?> </label>

				</div>
				<div>
					<div class="conte_condition">
						<input type="checkbox" name="conditions_check"
							id="conditions_check" /> <label for="conditions_check"> <?php $condintionsText = JText::_("CP.GUEST.CONDITIONS.TEXT");
							$conditionIndex = array(
								"%d",
								"%c"
								);
								$conditions = array(
								"<a class='modal' href='".$urlConditions."' id='conditions' rel=\"{handler: 'iframe', size: {x: 570, y: 550}}\">".JText::_("CP.GUEST.CONDITIONS")."</a>",
								"<a class='modal' href='".$urlCancelPolicy."' id='conditions' rel=\"{handler: 'iframe', size: {x: 570, y: 550}}\">".JText::_("CP.GUEST.CANCELATION.POLICY")."</a>"
								);
								echo str_replace($conditionIndex, $conditions, $condintionsText);?>
						</label> <a href="javascript: void(0)" class="button_next"
							id="button_next"> <?php echo JText::_("CP.HOTEL.BUTTON.DETAIL.SELECT")?>
						</a> <a onclick="return false;" class="button_next"
							id="button_wait" style="display: none;"> <?php echo JText::_("CP.GUEST.WAIT.BUTTON.SELECT")?>
						</a>
					</div>
				</div>
			</div>
			<div id="messageBox"></div>
		</div>

	</form>
</div>


<!-- ********************************Seccion de tarifas************************************* -->
								<?php $subtotal = 0;
								$taxes = 0;?>
<div class="conte_tarif">
	<div class="top_titulo_cp_detail">
	<?php echo JText::_("CP.BOOKING.INFO")?>
		<a target="_BLANK"
			href="<?php echo JRoute::_("index.php?option=com_catalogo_planes&view=hotel&layout=pdfdetail&format=pdf");?>">
			<img alt="PDF"
			src="<?php echo $this->getImage("global", "/M_images/pdf_button.png");?>"
			title="<?php echo JText::_("CP.GUEST.PDF.QUOTE")?>"> </a> <a
			href="javascript:void(0)"
			title="<?php echo JText::_("CP.GUEST.MAIL.QUOTE")?>" id="mail"> <img
			alt="mail"
			src="<?php echo $this->getImage("global", "/M_images/emailButton.png");?>"
			title="<?php echo JText::_("CP.GUEST.MAIL.QUOTE")?>"> </a>
	</div>
	<div class="detalles_precio_plan">

		<h3 class="seccion_title">
			<img
				src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_calendar_int.png" />
				<?php echo JText::_("CP.HOTEL.DATES.TITLE");?>
		</h3>
		<div class="conte_resume">
			<div>
				<strong><?php echo JText::_("CP.HOTEL.DATE.CHECKIN").": "?> </strong>
				<?php echo $this->data["date"]["checkin"]?>
			</div>
			<div>
				<strong><?php echo JText::_("CP.HOTEL.DATE.CHECKOUT").": "?> </strong>
				<?php echo $this->data["date"]["checkout"]?>
			</div>
			<div>
				<strong><?php echo JText::_("CP.HOTEL.NUMBER.NIGHTS").": "?> </strong>
				<?php echo $this->data["nights"]?>
			</div>
		</div>
		<h3 class="seccion_title">
			<img
				src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_viajeros.png" />
				<?php echo JText::_("CP.HOTEL.GUESTS.TITLE");?>
		</h3>
		<div class="conte_resume">
			<div>
				<strong><?php echo JText::_("CP.HOTEL.ADULTS").": "?> </strong>
				<?php echo $this->data["guest"]["adults"]?>
			</div>
			<?php if($this->data["guest"]["childs"]>0):?>
			<div>
				<strong><?php echo JText::_("CP.HOTEL.CHILDS").": "?> </strong>
				<?php echo $this->data["guest"]["childs"]?>
			</div>
			<?php endif;?>
		</div>

		<?php /*if(is_array($this->data["supplements"])): ?>
		<h3 class="seccion_title">
		<img src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_suplementos.png" />
		<?php echo JText::_("CP.HOTEL.SUPPLEMENTS.TITLE");?>
		</h3>
		<div class="conte_resume">
		<?php foreach($this->data["supplements"] as $supplement):?>
		<div class="lista_suplementos">
		<div class="cant_suplementos"><?php echo $supplement["quantity"]?></div>
		<div class="nombre_suplementos"><?php echo $supplement["name"]?></div>
		<div class="precio_suplementos"><?php echo $this->getFormatPrice($supplement["price"],$this->data["currency"]["symbol"])?></div>
		<div class="clear"></div>
		</div>
		<?php endforeach;?>
		<div class="clear"></div>
		</div>
		<?php endif;*/?>

		<h3 class="seccion_title">
			<img
				src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_tarifas.png" />
				<?php echo JText::_("CP.RATE.INFO")?>
		</h3>
		<div class="conte_resume">
			<table class="info_tarifas">
				<tr>
					<th><?php echo JText::_("CP.HOTEL.GUEST.ROOM.QUANTITY")?></th>
					<th><?php echo JText::_("CP.HOTEL.GUEST.ROOM.SERVICE")?></th>
					<th><?php echo JText::_("CP.HOTEL.GUEST.ROOM.RATE")?></th>
				</tr>
				<?php foreach($this->data["rates"] as $rate):?>
				<tr>
					<td style="text-align: center"><?php echo $rate["quantity"]?></td>
					<td><?php echo $rate["pamam1"]["name"]." - ".$rate["pamam2"]["name"]?>
					</td>
					<td align="left"><?php echo $this->getFormatPrice($rate["totalPrice"],$this->data["currency"]["symbol"])?>
					</td>
				</tr>
				<?php endforeach;?>
				<?php if($this->data["guest"]["childs"]>0):?>
				<tr>
					<td style="text-align: center"><?php echo $this->data["guest"]["childs"]?>
					</td>
					<td><?php echo JText::_("CP.HOTEL.CHILDS")." - ".$this->data["childs_feed"]["param3"]?>
					</td>
					<td align="left"><?php echo $this->getFormatPrice($this->data["childs_feed"]["totalPrice"],$this->data["currency"]["symbol"])?>
					</td>
				</tr>
				<?php endif;?>
				<?php if(is_array($this->data["supplements"])):
				foreach($this->data["supplements"] as $supplement):?>
				<tr>
					<td style="text-align: center"><?php echo $supplement["quantity"]?>
					</td>
					<td><?php echo $supplement["name"]?></td>
					<td align="left"><?php echo $this->getFormatPrice($supplement["price"],$this->data["currency"]["symbol"])?>
					</td>
				</tr>
				<?php endforeach;
				endif;?>
				<tr>
					<td></td>
					<td><div class="subtotal">
					<?php echo JText::_("CP.HOTEL.SUBTOTAL")?>
						</div></td>
					<td align="left"><nobr>
					<?php echo $this->getFormatPrice($this->data["subtotal"],$this->data["currency"]["symbol"])?>

					</td>
				</tr>
				<tr>
					<td></td>
					<td><div class="subtotal">
					<?php echo JText::_("CP.HOTEL.TAXES")?>
						</div></td>
					<td align="left"><?php echo $this->getFormatPrice($this->data["totalTax"],$this->data["currency"]["symbol"])?>
					</td>
				</tr>
			</table>
		</div>
		<div id="seccion_subtotal">
		<?php echo JText::_("CP.HOTEL.TOTAL")?>
			<span id="subtotal"><?php echo $this->getFormatPrice($this->data["total"],$this->data["currency"]["symbol"])?>
			</span>
		</div>
	</div>
</div>

<!---------------------------------------- seccion logueo ------------------------------------------>
<div id="dialog_registro" style="display: none"
	class="ventana_usuarios_med">
	<div class="zona_acceso_usuario">
		<div class="tit_user">
		<?php echo JText::_("CP.GUEST.LOGIN.TITLE")?>
		</div>
		<form id="form-login" name="login" method="post" action="">
			<table width="100%" cellspacing="3" cellpadding="3" border="0">
				<tbody>
					<tr>
						<td><label for="modlgnmod_username"><?php echo JText::_("CP.GUEST.LOGIN.MAIL")?>*</label>
						</td>

					</tr>
					<tr>
						<td><input type="text" class="campo_vent_usuario" name="username"
							id="modlgnmod_username"></td>
					</tr>
					<tr>
						<td><label for="modlgnmod_passwd"><?php echo JText::_("CP.GUEST.LOGIN.PASSWORD")?>*</label>
						</td>
					</tr>
					<tr>
						<td><input type="password" id="modlgnmod_passwd"
							class="campo_vent_usuario" name="passwd"></td>
					</tr>
					<tr>
						<td valign="middle" align="center"><input type="button"
							value="<?php echo JText::_("CP.GUEST.LOGIN.BUTTON")?>"
							class="boton_ventana_sesion" id="login" /> <input type="button"
							value="<?php echo JText::_("CP.GUEST.WAIT.BUTTON.SELECT")?>"
							class="boton_ventana_sesion" id="wait_login"
							style="display: none;" /> <input type="hidden" value="com_user"
							name="option"> <input type="hidden" value="login" name="task"> <?php echo JHTML::_( 'form.token' ); ?>
						</td>
					</tr>
					<tr>
						<td class="mensaje_error" colspan="2">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<div class="zona_acceso_usuario">
		<div class="tit_new_user">
			<?php echo JText::_("CP.GUEST.NEWUSER.TITLE")?>
		</div>
		<div class="zona_boton_user">
			<input type="button"
				value="<?php echo JText::_("CP.GUEST.NEWUSER.BUTTON")?>"
				class="boton_ventana_sesion" id="newUser" />
		</div>
	</div>
</div>

<!-- -----------------------  seccion mail --------------------------------------- -->
<div id="dialog_mail" style="display: none;">
	<label><?php echo JText::_("CP.GUEST.TO")?> </label> <input type="text"
		name="toMail" id="to_mail"
		value="<?php echo (!$this->user->guest)?$this->user->email:"";?>" />
	<div id="mensagge_mail"></div>
</div>
