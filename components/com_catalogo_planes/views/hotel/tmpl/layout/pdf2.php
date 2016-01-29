
<table width="750" cellspacing="2" cellpadding="0"
	style="margin-left: auto; margin-right: auto; font-family: Verdana, Geneva, sans-serif; white-space: normal;">
	<tr>
		<td colspan="2"
			style="font-family: Verdana, Geneva, sans-serif; font-size: 28px; color: #F68A08; font-weight: bold;">
			{product_name}</td>
	</tr>
	<tr>
		<td colspan="3"><div style="margin-left: 10px">{stars}</div></td>
	</tr>
	<tr>
		<th width="100"><?php echo JText::_("CP.QUOTE.CHECKINDATE")?></th>
		<td>{checkin_date}</td>
	</tr>
	<tr>
		<th><?php echo JText::_("CP.QUOTE.CHECKOUTDATE")?></th>
		<td>{checkout_date}</td>
	</tr>
	<tr>
		<th><?php echo JText::_("CP.QUOTE.NIGHTS")?></th>
		<td>{nights}</td>
	</tr>
	<tr>
		<th><?php echo JText::_("CP.QUOTE.ADULTS")?></th>
		<td>{adults}</td>
	</tr>
	<tr>
		<th><?php echo JText::_("CP.QUOTE.CHILDS")?></th>
		<td>{childs}</td>
	</tr>
	<tr>
		<td colspan="2"><?php echo JText::_("CP.QUOTE.NOTE")?></td>
	</tr>
	<tr>
		<td colspan="2">{disclaimer}</td>
	</tr>
</table>
<table>
	<tr>
		<td colspan="3"><?php echo JText::_("CP.QUOTE.RATE.RESUME")?></td>
	</tr>
	<tr style="background-color: #F2F2F2;">
		<th style="font-weight: bold; width: 100px;"><?php echo JText::_("CP.QUOTE.QUANTITY")?>
		</th>
		<th width="400"><?php echo JText::_("CP.QUOTE.SERVICE")?></th>
		<th width="200"><?php echo JText::_("CP.QUOTE.RATE")?></th>
	</tr>
	{rate_product}
	<tr>
		<td></td>
		<th style="background-color: #F2F2F2;"><?php echo JText::_("CP.HOTEL.SUBTOTAL")?>
		</th>
		<td>{subtotal}</td>
	</tr>
	<tr>
		<td></td>
		<th style="background-color: #F2F2F2;"><?php echo JText::_("CP.HOTEL.TAXES")?>
		</th>
		<td>{taxes}</td>
	</tr>
	<tr>
		<td></td>
		<th style="background-color: #F2F2F2;"><?php echo JText::_("CP.HOTEL.TOTAL")?>
		</th>
		<td>{total}</td>
	</tr>
</table>
