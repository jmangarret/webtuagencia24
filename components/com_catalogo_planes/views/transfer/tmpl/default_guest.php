<?php
JHTML::_('behavior.modal');
$cantAdults = 1;
$cantChilds = 1;
$urlConditions = JRoute::_("index.php?option=com_content&view=article&tmpl=component&id=".$this->data["article"]["conditions_id"]);
$urlCancelPolicy = JRoute::_("index.php?option=com_content&view=article&tmpl=component&id=".$this->data["article"]["cancel_id"]);
$minAgeAdults = $this->catalogoComponentConfig->get("cfg_adults_age");
$i=0;
$classRequired="required";

?>
<div class="conte_plan">
	<div class="top_titulo_cp_detail"></div>
	<form
		action="<?php echo JRoute::_("index.php?option=com_catalogo_planes&view=transfer&layout=booking")?>"
		method="POST" id="frm_guest">
		<input id="user_type" type="hidden" name="user_type" val="" />
		<div class="conte_general">
			<div class="conte_guest">
				<div class="conte_guest_number">
					<img src="<?php echo $this->getImage("icon", "guest.png")?>" />
				</div>
				<table>
					<tr>
					<?php if($i<$this->data["guest"]["adults"]):?>
						<td><?php echo JText::_("CP.GUEST.TREATMENT")?> <?php echo ($classRequired!="")?"*":"";?>
						</td>
						<?php endif;?>
						<td><?php echo JText::_("CP.GUEST.NAME")?>*</td>
						<td><?php echo JText::_("CP.GUEST.LASTNAME")?>* <input
							type="hidden" name="guest[<?php echo $i?>][type]" value="ADT">
						</td>
					</tr>
					<tr>
					<?php if($i<$this->data["guest"]["adults"]):?>
						<td><select name="guest[<?php echo $i?>][treatment]"
							id="treatment" class="<?php echo $classRequired?>">
								<option value="">
								<?php echo JText::_("CP.GUEST.SELECT")?>
								</option>
								<option value="1">
								<?php echo JText::_("CP.GUEST.TREATMENT.MR")?>
								</option>
								<option value="2">
								<?php echo JText::_("CP.GUEST.TREATMENT.MRS")?>
								</option>
								<option value="3">
								<?php echo JText::_("CP.GUEST.TREATMENT.MS")?>
								</option>
						</select>
						</td>
						<?php endif;?>
						<td><input type="text" name="guest[<?php echo $i?>][name]"
							value="" class="required" />
						</td>
						<td><input type="text" name="guest[<?php echo $i?>][lastname]"
							value="" class="<?php echo $classRequired?>" />
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_("CP.GUEST.DOCUMENTTYPE")?> <?php echo ($classRequired!="")?"*":"";?>
						</td>
						<td><?php echo JText::_("CP.GUEST.DOCUMENT")?> <?php echo ($classRequired!="")?"*":"";?>
						</td>
						<td><?php echo JText::_("CP.GUEST.NACIONALITY")?> <?php echo ($classRequired!="")?"*":"";?>
						</td>
					</tr>
					<tr>
						<td><select name="guest[<?php echo $i?>][documenttype]"
							id="documenttype" class="<?php echo $classRequired?>">
								<option value="">
								<?php echo JText::_("CP.GUEST.SELECT")?>
								</option>
								<option value="1">
								<?php echo JText::_("CP.GUEST.DOCUMENTTYPE.ID")?>
								</option>
								<option value="2">
								<?php echo JText::_("CP.GUEST.DOCUMENTTYPE.EXT")?>
								</option>
								<option value="3">
								<?php echo JText::_("CP.GUEST.DOCUMENTTYPE.PASS")?>
								</option>
						</select>
						</td>
						<td><input type="text" name="guest[<?php echo $i?>][document]"
							value="" class="<?php echo $classRequired?>" />
						</td>
						<td><input type="text" name="guest[<?php echo $i?>][nationality]"
							value="" class="<?php echo $classRequired?>" />
						</td>
					</tr>
					<tr>
					<?php if($i<$this->data["guest"]["adults"]):?>
						<td><?php echo JText::_("CP.GUEST.BIRTHDAY")?>*</td>
						<?php endif;?>
						<td><?php echo JText::_("CP.GUEST.GENRE")?> <?php echo ($classRequired!="")?"*":"";?>
						</td>
					</tr>
					<tr>
					<?php if($i<$this->data["guest"]["adults"]):?>
						<td><select name="guest[<?php echo $i?>][bday]" id="bDay"
							class="required">
							<?php for($day=1;$day<=31;$day++):?>
								<option value="<?php echo $day?>">
								<?php echo $day?>
								</option>';
								<?php endfor;?>
						</select> <select name="guest[<?php echo $i?>][bmonth]"
							id="bMonth" class="required">
								<option value="1">
								<?php echo JText::_('CP.MONTHS.JANUARY.SHORT.LABEL'); ?>
								</option>
								<option value="2">
								<?php echo JText::_('CP.MONTHS.FEBRUARY.SHORT.LABEL'); ?>
								</option>
								<option value="3">
								<?php echo JText::_('CP.MONTHS.MARCH.SHORT.LABEL'); ?>
								</option>
								<option value="4">
								<?php echo JText::_('CP.MONTHS.APRIL.SHORT.LABEL'); ?>
								</option>
								<option value="5">
								<?php echo JText::_('CP.MONTHS.MAY.SHORT.LABEL'); ?>
								</option>
								<option value="6">
								<?php echo JText::_('CP.MONTHS.JUNE.SHORT.LABEL'); ?>
								</option>
								<option value="7">
								<?php echo JText::_('CP.MONTHS.JULY.SHORT.LABEL'); ?>
								</option>
								<option value="8">
								<?php echo JText::_('CP.MONTHS.AUGUST.SHORT.LABEL'); ?>
								</option>
								<option value="9">
								<?php echo JText::_('CP.MONTHS.SEPTEMBER.SHORT.LABEL'); ?>
								</option>
								<option value="10">
								<?php echo JText::_('CP.MONTHS.OCTOBER.SHORT.LABEL'); ?>
								</option>
								<option value="11">
								<?php echo JText::_('CP.MONTHS.NOVEMBER.SHORT.LABEL'); ?>
								</option>
								<option value="12">
								<?php echo JText::_('CP.MONTHS.DECEMBER.SHORT.LABEL'); ?>
								</option>
						</select> <?php $currentYear=date("Y");
						$currentYear=$currentYear-$minAgeAdults;
						$anos = 100;?> <select name="guest[<?php echo $i?>][byear]"
							id="bYear" class="required">
							<?php for($y=$currentYear;$y>=($currentYear-$anos);$y--):?>
								<option value="<?php echo $y?>">
								<?php echo $y?>
								</option>
								<?php endfor;?>
						</select>
						</td>
						<?php endif;?>
						<td><select name="guest[<?php echo $i?>][gender]"
							class="<?php echo $classRequired?>">
								<option value="M">
								<?php echo JText::_("CP.GUEST.GENRE.M")?>
								</option>
								<option value="F">
								<?php echo JText::_("CP.GUEST.GENRE.F")?>
								</option>
						</select>
						</td>
					</tr>

				</table>
			</div>

			<div class="conte_guest">
				<div class="conte_guest_number">
				<?php echo JText::_("CP.GUEST.CONTACTINFORMATION")?>
				</div>
				<table width="100%" cellspacing="3" cellpadding="2" border="0">
					<tr>
						<td width="40%"><?php echo JText::_("CP.GUEST.EMAIL.LABEL"); ?>*&nbsp;</td>
						<td width="60%"><input id="mail_contact" name="contact[mail]"
							value="" type="text" class="campo_form_reserva required"
							title="<?php echo JText::_("CP.GUEST.EMAIL.LABEL"); ?>">
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_("CP.GUEST.EMAIL.CONFIRMATION.LABEL"); ?>*&nbsp;</td>
						<td><input id="repeat_mail" name="contact[repeat_mail]" value=""
							type="text" class="campo_form_reserva required">
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_("CP.GUEST.PRINCIPAL.PHONE.LABEL"); ?>*&nbsp;</td>
						<td><input onkeypress="return numberValidate(event);"
							name="contact[cod_country]" type="text" class="campo_form_fecha "
							id="cod_country" maxlength="4" size="4"
							title="<?php echo JText::_("CP.GUEST.PRINCIPAL.PHONE.LABEL").' - '.JText::_("CP.GUEST.CODCOUNTRY.LABEL.FORM"); ?>" />
							<input name="contact[cod_city]" type="text"
							class="campo_form_fecha " id="cod_city" maxlength="4" size="4"
							title="<?php echo JText::_("CP.GUEST.PRINCIPAL.PHONE.LABEL").' - '.JText::_("CP.GUEST.CODZONE.LABEL.FORM"); ?>" />
							<input name="contact[phone]" type="text"
							class="campo_form_fecha required" id="phone" maxlength="10"
							size="10"
							title="<?php echo JText::_("CP.GUEST.PRINCIPAL.PHONE.LABEL").' - '.JText::_("CP.GUEST.CODEPHONE.LABEL.FORM"); ?>" />
							<select name="contact[phone_type]" class="campo_lista_pasajeros"
							id="phone_type">
								<option value="M">
								<?php echo JText::_("CP.GUEST.MOBILE.LABEL"); ?>
								</option>
								<option value="H">
								<?php echo JText::_("CP.GUEST.HOME.LABEL"); ?>
								</option>
						</select> <br /> <span class="description"><?php echo JText::_("CP.GUEST.CODCOUNTRY.LABEL.FORM"); ?>
						</span> &nbsp;<span class="description"><?php echo JText::_("CP.GUEST.CODZONE.LABEL.FORM"); ?>
						</span> &nbsp;<span class="description"><?php echo JText::_("CP.GUEST.CODEPHONE.LABEL.FORM"); ?>
						</span>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_("CP.GUEST.OBSERVATION.LABEL"); ?></td>
						<td><textArea name="contact[observation]" maxlength="1000"
								class="campo_form_reserva" rows="4"></textArea>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_("CP.GUEST.TRANSFER.ADDRESS.PICKUP.LABEL"); ?>*&nbsp;</td>
						<td><textArea name="contact[address_pickup]" maxlength="1000"
								class="campo_form_reserva required" rows="4"></textArea>
						</td>
					</tr>
				</table>
			</div>
			<div class="conte_payment">
				<div class="conte_guest_number">
				<?php echo JText::_("CP.GUEST.PAYMENTMETOD")?>
				</div>
				<div class="contain_payment">
				<?php if($this->data["haveStock"]):?>
					<input type="radio" name="payment" id="credit" value="credit" /> <label
						for="credit"><?php echo JText::_("CP.GUEST.PAYMENT.CREDIT")?> </label>
						<?php endif;?>
					<input type="radio" name="payment" id="pending" value="agency" /> <label
						for="pending"><?php echo JText::_("CP.GUEST.PAYMENT.PENDING")?> </label>
					<input type="radio" name="payment" id="destiny" value="destiny" />
					<label for="destiny"><?php echo JText::_("CP.GUEST.PAYMENT.DESTINY")?>
					</label>
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
							id="button_next"> <?php echo JText::_("CP.TRANSFER.BUTTON.DETAIL.SELECT")?>
						</a> <a onclick="return false;" class="button_next"
							id="button_wait" style="display: none;"> <?php echo JText::_("CP.GUEST.WAIT.BUTTON.SELECT")?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div id="messageBox"></div>
	</form>
</div>


<!-- ********************************Seccion de tarifas************************************* -->
								<?php $subtotal = 0;
								$taxes = 0;?>
<div class="conte_tarif">
	<div class="top_titulo_cp_detail"></div>
	<div class="detalles_precio_plan">
		<div class="conte_guest_number">
		<?php echo JText::_("CP.BOOKING.INFO")?>
			<a target="_BLANK"
				href="<?php echo JRoute::_("index.php?option=com_catalogo_planes&view=transfer&layout=pdfdetail&format=pdf");?>">
				<img alt="PDF"
				src="<?php echo $this->getImage("global", "/M_images/pdf_button.png");?>"
				title="<?php echo JText::_("CP.GUEST.PDF.QUOTE")?>"> </a> <a
				href="javascript:void(0)"
				title="<?php echo JText::_("CP.GUEST.MAIL.QUOTE")?>" id="mail"> <img
				alt="mail"
				src="<?php echo $this->getImage("global", "/M_images/emailButton.png");?>"
				title="<?php echo JText::_("CP.GUEST.MAIL.QUOTE")?>"> </a>
		</div>
		<div class="conte_resume">
			<div class="title">
			<?php echo $this->data["name"]?>
			</div>
			<div class="conte_city">
			<?php echo $this->data["city"]["name"]?>
				-
				<?php echo $this->data["country"]["name"]?>
			</div>
			<br />
			<div>
				<strong><?php echo JText::_("CP.TRANSFER.DATE.CHECKIN").": "?> </strong>
				<?php echo $this->data["date"]["checkin"]." ".$this->data["date"]["checkin_hour"]?>
			</div>
			<?php if(isset($this->data["date"]["checkout"]) && $this->data["date"]["checkout"]!=""):?>
			<div>
				<strong><?php echo JText::_("CP.TRANSFER.DATE.CHECKOUT").": "?> </strong>
				<?php echo $this->data["date"]["checkout"]." ".$this->data["date"]["checkout_hour"]?>
			</div>
			<?php endif;?>
			<div>
				<strong><?php echo JText::_("CP.TRANSFER.DESTINY").": "?> </strong>
				<?php echo $this->data["rates"][0]["pamam3"]["name"]?>
			</div>
			<div>
				<strong><?php echo JText::_("CP.TRANSFER.ADULTS").": "?> </strong>
				<?php echo $this->data["guest"]["adults"]?>
			</div>
		</div>
		<div class="conte_guest_number">
		<?php echo JText::_("CP.RATE.INFO")?>
		</div>
		<table>
			<tr>
				<th><?php echo JText::_("CP.TRANSFER.GUEST.ROOM.QUANTITY")?></th>
				<th><?php echo JText::_("CP.TRANSFER.GUEST.ROOM.SERVICE")?></th>
				<th><?php echo JText::_("CP.TRANSFER.GUEST.ROOM.RATE")?></th>
			</tr>
			<?php foreach($this->data["rates"] as $rate):?>
			<tr>
				<td style="text-align: center"><?php echo $rate["quantity"]?></td>
				<td><?php echo $rate["pamam1"]["name"]?></td>
				<td align="right"><?php echo $this->getFormatPrice($rate["totalPrice"],$this->data["currency"]["symbol"])?>
				</td>
			</tr>
			<?php endforeach;?>
			<?php if($this->data["guest"]["childs"]>0):?>
			<tr>
				<td style="text-align: center"><?php echo $this->data["guest"]["childs"]?>
				</td>
				<td><?php echo JText::_("CP.TRANSFER.CHILDS")." - ".$this->data["childs_feed"]["param3"]?>
				</td>
				<td align="right"><?php echo $this->getFormatPrice($this->data["childs_feed"]["totalPrice"],$this->data["currency"]["symbol"])?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(is_array($this->data["supplements"])):
			foreach($this->data["supplements"] as $supplement):?>
			<tr>
				<td style="text-align: center"><?php echo $supplement["quantity"]?>
				</td>
				<td><?php echo $supplement["name"]?></td>
				<td align="right"><?php echo $this->getFormatPrice($supplement["price"],$this->data["currency"]["symbol"])?>
				</td>
			</tr>
			<?php endforeach;
			endif;?>
			<tr>
				<td></td>
				<td><div class="subtotal">
				<?php echo JText::_("CP.TRANSFER.SUBTOTAL")?>
					</div></td>
				<td align="right"><?php echo $this->getFormatPrice($this->data["subtotal"],$this->data["currency"]["symbol"])?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><div class="subtotal">
				<?php echo JText::_("CP.TRANSFER.TAXES")?>
					</div></td>
				<td align="right"><?php echo $this->getFormatPrice($this->data["totalTax"],$this->data["currency"]["symbol"])?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><div class="subtotal">
				<?php echo JText::_("CP.TRANSFER.TOTAL")?>
					</div></td>
				<td align="right"><?php echo $this->getFormatPrice($this->data["total"],$this->data["currency"]["symbol"])?>
				</td>
			</tr>
		</table>
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
