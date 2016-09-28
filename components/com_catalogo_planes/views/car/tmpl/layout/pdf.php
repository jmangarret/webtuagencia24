<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="top">
			<table width="656" border="0" cellpadding="0" cellspacing="0"
				bgcolor="#FFFFFF">
				<tr>
					<td height="92" align="left" valign="top"><img
						src="images/fondo_top_correo_citurc.jpg" width="656" height="92" />
					</td>
				</tr>
				<tr>
					<td
						style="text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #2255B5; font-weight: bold;"><?php echo JText::_('CP.MAIL.CAR.SUBJECT')?>
					</td>
				</tr>
				<tr>
					<td align="center" valign="top">
						<table width="620" border="0" cellspacing="0" cellpadding="0"
							style="padding-bottom: 10px;">
							<tr>
								<td align="justify" style="padding-bottom: 10px;">
									<h1
										style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #2255B5; font-weight: bold;">
										{product_name}</h1>
									<table width="100%" border="0" cellspacing="2" cellpadding="4"
										style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #4B4B4B;">
										<tr
											style="background-color: #F3F3F3; padding-top: 5px; padding-bottom: 5px; color: #2255B5; font-weight: bold;">
											<td align="left" valign="top"
												style="width: 280px; padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.CAR.DATE.HOUR.CHECKIN")?>
											</td>
											<td align="left" valign="top"
												style="width: 180px; padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.CAR.TYPE")?>
											</td>
											<td align="left" valign="top"
												style="width: 180px; padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.QUOTE.DURATION")?>
											</td>
										</tr>
										<tr>
											<td align="left" valign="top">{checkin_date}</td>
											<td align="left" valign="top">{cartype}</td>
											<td align="left" valign="top">{duration}</td>
										</tr>
										<tr
											style="background-color: #F3F3F3; padding-top: 5px; padding-bottom: 5px; color: #2255B5; font-weight: bold;">
											<td align="left" valign="top"
												style="width: 250px; padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.CAR.DATE.HOUR.CHECKOUT")?>
											</td>

											<td align="left" valign="top"
												style="padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.CAR.PICKUP.CITY")?>
											</td>
											<td align="left" valign="top"
												style="padding-top: 8px; padding-bottom: 8px;"></td>
										</tr>
										<tr>
											<td align="left" valign="top">{checkout_date}</td>
											<td align="left" valign="top">{pickup_location}</td>
											<td align="left" valign="top"></td>
										</tr>
										<tr
											style="background-color: #F3F3F3; padding-top: 5px; padding-bottom: 5px; color: #2255B5; font-weight: bold;">
											<td align="left" valign="top"
												style="padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.QUOTE.QUANTITY")?>
											</td>
											<td align="left" valign="top"
												style="padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.QUOTE.SERVICE")?>
											</td>
											<td align="left" valign="top"
												style="padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.QUOTE.RATE")?>
											</td>
										</tr>
										{rate_product}
										<tr
											style="background-color: #E79000; color: #FFF; font-weight: bold;">
											<td colspan="2" align="left" valign="top"
												style="padding-bottom: 10px; padding-top: 10px; font-size: 14px;">
												<?php echo JText::_("CP.CAR.SUBTOTAL")?><br /> <?php echo JText::_("CP.CAR.TAXES")?><br />
											<br /> <?php echo JText::_("CP.CAR.TOTAL")?></td>
											<td align="left" valign="top"
												style="text-align: right; padding-bottom: 10px; padding-top: 10px; font-size: 14px;">
												{subtotal}<br /> {taxes}<br />
											<br> {total}</td>
										</tr>
									</table>
									<hr>
									<h1
										style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #2255B5; font-weight: bold;">
										<?php echo JText::_("CP.QUOTE.NOTE")?>
									</h1>
									<div
										style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #4B4B4B; width: 300px">
										{disclaimer}</div></td>
							</tr>
						</table></td>
				</tr>
				<tr>
					<td height="76" align="left" valign="top"><img
						src="images/fondo_pie_correo_citurc.jpg" width="656" height="76" />
					</td>
				</tr>
			</table></td>
	</tr>
</table>
