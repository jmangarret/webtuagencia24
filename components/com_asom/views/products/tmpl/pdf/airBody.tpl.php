 <page backtop="7mm" backbottom="7mm" backleft="10mm" backright="20mm"> 
<table width="100%" style="  border-collapse: collapse; font-family: Arial;">
	<tr>
		<td colspan="3"
			style="border-bottom: 10px #213E7A solid;">
			<img src="images/top_emails.jpg"  width="710"  /></td>
	</tr>
	<tr>
		<td><br><br>
			<span style="font-size: 14px; color: #004274;">
				<?php echo JText::_('ORDERS.ORDER.RECORD')?>
			</span>
			<span style="color: #ED1C23; font-size: 14px; font-weight: bold;">{order.record}</span>
			<br />
			<span style="font-size: 14px; color: #004274;">
				<?php echo JText::_('ORDERS.ORDER.NUMBER')?>
			</span>
			<span style="color: #ED1C23; font-size: 14px; font-weight: bold;">{order.number}</span>
			<br />
			<span style="font-size: 14px; color: #004274;">
				<?php echo JText::_('ORDERS.ORDER.DETAIL.STATE.LABEL')?>
			</span>
			<span style="color: #ED1C23; font-size: 14px; font-weight: bold;">{order.state}</span>
			 	<br />
			<br />
		</td>
	</tr>
	</table>
 	<table width="100%" cellspacing="2" cellpadding="2">
		<tbody>
			<tr style="background-color: #213E7A ;">
				<td style="color: #FFF; padding: 5px; font-weight: bold; width:700px" colspan="4"><?php echo JText::_("ORDERS.ORDER.AIR.DETAIL")?></td>
			</tr>
			<tr style="background-color: #F2F2F2;">
				<td
					style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;">
					<?php echo JText::_('ORDERS.ORDER.DATE.DEPARTURE')?></td>
				<td
					style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;">
					<?php echo JText::_('ORDERS.ORDER.DATE.ARRIVAL')?></td>
				<td
					style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;">
					<?php echo JText::_('ORDERS.ORDER.AIRPORT.DEPARTURE')?></td>
				<td
					style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;">
					<?php echo JText::_('ORDERS.ORDER.AIRPORT.ARRIVAL')?></td>
			</tr>
			{order.intinerary}
		</tbody>
	</table>

	 
 
 
	<table>
		<tbody>
			<tr style="background-color: #213E7A ;">
				<td style="color: #FFF; padding: 5px; font-weight: bold; width:700px"
					colspan="8"><?php echo JText::_("ORDERS.ORDER.PAX.DETAIL")?></td>
			</tr>
			{order.passengers}
		</tbody>
	</table>
 	<table width="100%" cellspacing="2" cellpadding="2">
		<tbody>
			<tr style="background-color: #213E7A ;">
				<td style="color: #FFF; padding: 5px; font-weight: bold; width:700px" colspan="4"><?php echo JText::_("ORDERS.ORDER.TITLE.PRICE")?></td>
			</tr>
			<tr style="background-color: #F2F2F2;">
				<td
					style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;">
					<?php echo JText::_('ORDERS.ORDER.PRICE.NETO')?></td>
				<td
					style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;">
					<?php echo JText::_('ORDERS.ORDER.PRICE.IMP')?></td>
				<td
					style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;">
					<?php echo JText::_('ORDERS.ORDER.PRICE.CHR')?></td>
				<td
					style="color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;">
					<?php echo JText::_('ORDERS.ORDER.AIR.TOTAL')?></td>
			</tr>
			<tr style="background-color: #F9F9F9;">
				<td style="padding: 2px 2px 2px 4px;">Bs. {order.price}</td>
				<td style="padding: 2px 2px 2px 4px;">Bs. {order.taxes}</td>
				<td style="padding: 2px 2px 2px 4px;">Bs. {order.fees}</td>
				<td style="padding: 2px 2px 2px 4px;">Bs. {order.total}</td>
			</tr>
		</tbody>
	</table>
</page>

