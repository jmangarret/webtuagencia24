<table align="center" cellpadding="0" cellspacing="0"
	style="width: 200px; margin: 0px auto 0px auto; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif;">

	<tr>

		<td>
			<table width="500px" align="center" border="0" cellspacing="0"
				cellpadding="0">
				<tr>
					<td align="left" valign="top"
						style="width: 150px; font-family: Arial, Helvetica, sans-serif; font-size: 13px; text-align: center; color: #666666; line-height: 20px;">
						<h1 style="font-size: 18px; color: #333333; font-style: italic;">
						<?php echo JText::_('CP.MAIL.HOTEL.SUBJECT')?>
						</h1>
					</td>
				</tr>
				<tr>
					<td align="justify" style="padding-bottom: 10px;">
						<div style="width: 500px">
						<?php echo JText::_("CP.QUOTE.GENERAL.MESSAGE")?>
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
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px; text-align: left;"><?php echo JText::_("CP.QUOTE.CHECKINDATE")?>
								</td>
								<td
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px; text-align: left;"><?php echo JText::_("CP.QUOTE.CHECKOUTDATE")?>
								</td>
								<td
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px; text-align: left;"><?php echo JText::_("CP.QUOTE.NIGHTS")?>
								</td>
							</tr>
							<tr style="background-color: #F9F9F9;">
								<td style="padding: 2px 2px 2px 4px;">{checkin_date}</td>
								<td style="padding: 2px 2px 2px 4px;">{checkout_date}</td>
								<td style="padding: 2px 2px 2px 4px;">{nights}</td>
							</tr>
							<tr style="background-color: #F2F2F2;">
								<td
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.QUOTE.ADULTS")?>
								</td>
								<td
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.QUOTE.CHILDS")?>
								</td>
								<td align="left" valign="top"></td>
							</tr>

							<tr style='background-color: #F9F9F9;'>
								<td style="padding: 2px 2px 2px 4px;">{adults}</td>
								<td style="padding: 2px 2px 2px 4px;">{childs}</td>
								<td align="left" valign="top"></td>
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
								<td align="center" valign="top">&nbsp;</td>
								<td align="left" valign="top"
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.HOTEL.SUBTOTAL")?>
								</td>
								<td align="right" valign="top"
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;">{subtotal}</td>
							</tr>
							<tr style="background-color: #F2F2F2;">
								<td align="center" valign="top">&nbsp;</td>
								<td align="left" valign="top"
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.HOTEL.TAXES")?>
								</td>
								<td align="right" valign="top"
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;">{taxes}</td>
							</tr>
							<tr style="background-color: #F2F2F2;">
								<td align="center" valign="top">&nbsp;</td>
								<td align="left" valign="top"
									style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;"><?php echo JText::_("CP.HOTEL.TOTAL")?>
								</td>
								<td valign="top" align="right"
									style="color: #ED1C23; font-weight: bold; font-size: 15px; padding: 2px 2px 2px 4px;">{total}</td>
							</tr>
						</table>
						<hr>
						<h2
							style="font-size: 15px; color: #004274; font-style: italic; margin-bottom: 5px;">
							<?php echo JText::_("CP.QUOTE.NOTE")?>
						</h2> {disclaimer}</td>

				</tr>
			</table></td>
	</tr>

</table>

