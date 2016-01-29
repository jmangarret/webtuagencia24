<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="top">
			<table width="656" border="0" cellpadding="0" cellspacing="0"
				bgcolor="#FFFFFF">
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
											style="background-color: #F3F3F3; padding-top: 8px; padding-bottom: 8px; color: #2255B5; font-weight: bold;">
											<td align="left" valign="top"
												style="width: 180px; padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.TRANSFER.DETAIL.CITY")?>
											</td>
											<td align="left" valign="top"
												style="width: 250px; padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.TRANSFER.DETAIL.REGION")?>
											</td>
											<td align="left" valign="top"
												style="padding-top: 8px; padding-bottom: 8px; width: 200px;"></td>
										</tr>
										<tr>
											<td align="left" valign="top">{city}</td>
											<td align="left" valign="top">{region}</td>
											<td align="left" valign="top"></td>
										</tr>
										<tr
											style="background-color: #F3F3F3; padding-top: 8px; padding-bottom: 8px; color: #2255B5; font-weight: bold;">
											<td align="left" valign="top"
												style="width: 180px; padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.TRANSFER.DATE.CHECKIN")?>
											</td>
											<td align="left" valign="top"
												style="width: 250px; padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.TRANSFER.DATE.CHECKOUT")?>
											</td>
											<td align="left" valign="top"
												style="padding-top: 8px; padding-bottom: 8px; width: 200px;"><?php echo JText::_("CP.TRANSFER.ADULTS")?>
											</td>
										</tr>
										<tr>
											<td align="left" valign="top">{checkin_date}</td>
											<td align="left" valign="top">{checkout_date}</td>
											<td align="left" valign="top">{adults}</td>
										</tr>
										<tr
											style="background-color: #F3F3F3; padding-top: 5px; padding-bottom: 5px; color: #2255B5; font-weight: bold;">
											<td align="left" valign="top"
												style="padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.TRANSFER.PARAM2.TITLE2")?>
											</td>
											<td align="left" valign="top"
												style="padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.TRANSFER.DESTINY")?>
											</td>
											<td align="left" valign="top"
												style="padding-top: 8px; padding-bottom: 8px;"><?php echo JText::_("CP.QUOTE.RATE")?>
											</td>
										</tr>
										<tr>
											<td align="left" valign="top">{service}</td>
											<td align="left" valign="top">{destiny}</td>
											<td align="left" valign="top">{rate}</td>
										</tr>
										{suplements}

										<tr
											style="background-color: #E79000; color: #FFF; font-weight: bold;">
											<td colspan="2" align="left" valign="top"
												style="padding-bottom: 10px; padding-top: 10px; font-size: 14px;">
												<?php echo JText::_("CP.TRANSFER.SUBTOTAL")?><br /> <?php echo JText::_("CP.TRANSFER.TAXES")?><br />
											<br /> <?php echo JText::_("CP.TRANSFER.TOTAL")?></td>
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
									<p
										style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #4B4B4B;">
										{disclaimer}</p></td>
							</tr>
						</table></td>
				</tr>
			</table></td>
	</tr>
</table>
