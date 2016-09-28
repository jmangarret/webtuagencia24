<table align="center" cellpadding="0" cellspacing="0"
	style="width: 200px; margin: 0px auto 0px auto; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif;">
	<tr>
		<td colspan="3"
			style="width: 620px; height: 50px; border-bottom: 10px #ED1C24 solid;">
			<img src="images/top_emails.gif"
			alt="viajescolon.com - Información y reservas 2547 - 2547 Call Center"
			title="viajescolon.com - Información y reservas 2547 - 2547 Call Center"
			width="620" height="68" /></td>
	</tr>

	<tr>
		<td width="20" valign="top" align="center"><img width="20"
			height="300" alt="lat" src="images/lateral_email_izq.jpg"></td>
		<td>
			<table width="500px" align="center" border="0" cellspacing="0"
				cellpadding="0">
				<tr>
					<td align="left" valign="top"
						style="width: 150px; font-family: Arial, Helvetica, sans-serif; font-size: 13px; text-align: center; color: #666666; line-height: 20px;">
						<h1 style="font-size: 18px; color: #333333; font-style: italic;">
						<?php echo JText::_('CP.MAIL.PLAN.SUBJECT')?>
						</h1>
					</td>
				</tr>
				<tr>
					<td align="justify" style="padding-bottom: 10px;">
						<div style="width: 500px">
						<?php echo JText::_("CP.QUOTE.PDF.GENERAL.MESSAGE")?>
						</div>
						<h2
							style="font-size: 15px; color: #004274; font-style: italic; margin-bottom: 5px;">
							{product_name}</h2>

						<table width="500px" cellspacing="2" cellpadding="2">
							<tr style="background-color: #ED1C24;">
								<td colspan="3" style="color: #FFF; font-weight: bold;"><?php echo JText::_("CP.GENERAL.DATA")?>
								</td>
							</tr>
							<tr style="background-color: #F2F2F2;">
								<td
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.PLAN.DATE.CHECKIN")?>
								</td>
								<td
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.PLAN.NUMBER.NIGHTS")?>
								</td>
								<td
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.PLAN.HOTEL")?>
								</td>
							</tr>
							<tr style="background-color: #F9F9F9;">
								<td style="padding: 2px 2px 2px 4px;">{checkin_date}</td>
								<td style="padding: 2px 2px 2px 4px;">{nights}</td>
								<td style="padding: 2px 2px 2px 4px; padding-right: 10px;">{hotel}</td>
							</tr>
							<tr style="background-color: #F2F2F2;">
								<td
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px; padding-right: 10px; width: 120px;"><?php echo JText::_("CP.QUOTE.ADULTS")?>
								</td>
								<td
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px; padding-right: 10px; width: 160px;"><?php echo JText::_("CP.QUOTE.CHILDS")?>
								</td>
								<td
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px; padding-right: 10px; width: 150px;"><?php echo JText::_("CP.QUOTE.CITY")?>
								</td>
							</tr>

							<tr style="background-color: #F9F9F9;">
								<td style="padding: 2px 2px 2px 4px;">{adults}</td>
								<td style="padding: 2px 2px 2px 4px;">{childs}</td>
								<td style="padding: 2px 2px 2px 4px;">{city}</td>
							</tr>
							<tr style="background-color: #ED1C24;">
								<td colspan="3" style="color: #FFF; font-weight: bold;"><?php echo JText::_("CP.GENERAL.RATES")?>
								</td>
							</tr>
							<tr style="background-color: #F2F2F2;">
								<td
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.QUOTE.QUANTITY")?>
								</td>
								<td
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.QUOTE.SERVICE")?>
								</td>
								<td align="right"
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.QUOTE.RATE")?>
								</td>
							</tr>
							{rate_product}
							<tr style="background-color: #F2F2F2;">
								<td align="center" valign="top"></td>
								<td align="left" valign="top"
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.HOTEL.SUBTOTAL")?>
								</td>
								<td align="right" valign="top"
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px; padding-right: 10px;">{subtotal}</td>
							</tr>
							<tr style="background-color: #F2F2F2;">
								<td align="center" valign="top"></td>
								<td align="left" valign="top"
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.HOTEL.TAXES")?>
								</td>
								<td align="right" valign="top"
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px; padding-right: 10px;">{taxes}</td>
							</tr>
							<tr style="background-color: #F2F2F2;">
								<td align="center" valign="top"></td>
								<td align="left" valign="top"
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.HOTEL.TOTAL")?>
								</td>
								<td valign="top" align="right"
									style="color: #ED1C23; font-weight: bold; font-size: 15px; padding: 2px 2px 2px 4px; padding-right: 10px;">{total}</td>
							</tr>
						</table>
						<hr>
						<h2
							style="font-size: 15px; color: #004274; font-style: italic; margin-bottom: 5px;">
							<?php echo JText::_("CP.QUOTE.NOTE")?>
						</h2> {disclaimer}</td>

				</tr>
			</table></td>

		<td align="center" valign="top" style="width: 20px;"><img
			src="images/lateral_email_der.jpg" alt="" width="20" height="300" />
		</td>
	</tr>
	<tr>
		<td colspan="3"
			style="background-color: #333333; width: 50%; padding: 15px 5px;"></td>
	</tr>
	<tr>
		<td colspan="3"
			style="text-align: right; font-size: 11px; color: #333333; padding: 6px 0px;">|
			<a href="http://www.viajescolon.com" target="_blank"
			style="font-size: 11px; color: #333333; text-decoration: underline;"><?php echo JText::_("CP.FOOTER.PDF1")?>
		</a> | <?php echo JText::_("CP.FOOTER.PDF2").date('Y');?> | <?php echo JText::_("CP.FOOTER.PDF3")?>
		</td>
	</tr>
</table>

