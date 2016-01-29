<div>
	<div>
	<?php echo JText::_("CP.BOOKING.INFO")?>
	</div>
	<br />
	<div>
	<?php echo $this->data["name"]?>
	</div>
	<br />
	<div>
	<?php for($star=0; $star<$this->data["stars"]; $star++):?>
		<img src="images/star.png" width="10" />
		<?php endfor;?>
	</div>
	<br />
	<div>
	<?php echo $this->data["city"]["name"]?>
		-
		<?php echo $this->data["country"]["name"]?>
	</div>
	<br />
	<div>
	<?php echo JText::_("CP.HOTEL.DATE.CHECKIN")?>
		:
		<?php echo $this->data["date"]["checkin"]?>
	</div>
	<br />
	<div>
	<?php echo JText::_("CP.HOTEL.DATE.CHECKOUT")?>
		:
		<?php echo $this->data["date"]["checkout"]?>
	</div>
	<br />
	<div>
	<?php echo JText::_("CP.HOTEL.NUMBER.NIGHTS")?>
		:
		<?php echo $this->data["nights"]?>
	</div>
	<br />
	<div>
	<?php echo JText::_("CP.HOTEL.ADULTS")?>
		:
		<?php echo $this->data["guest"]["adults"]?>
		</td>
	</div>
	<br />
	<?php if($this->data["guest"]["childs"]>0):?>
	<div>
	<?php echo JText::_("CP.HOTEL.CHILDS")?>
		:
		<?php echo $this->data["guest"]["childs"]?>
	</div>
	<br />
	<?php endif;?>
</div>
<br />
<div>
<?php echo JText::_("CP.RATE.INFO")?>
</div>
<br />
<table cellpadding="0" cellspacing="0">
	<tr>
		<th width="100"><?php echo JText::_("CP.HOTEL.GUEST.ROOM.QUANTITY")?>
		</th>
		<th width="400"><?php echo JText::_("CP.HOTEL.GUEST.ROOM.SERVICE")?></th>
		<th><?php echo JText::_("CP.HOTEL.GUEST.ROOM.RATE")?></th>
	</tr>
	<?php foreach($this->data["rates"] as $rate):?>
	<tr>
		<td width="100"><?php echo $rate["quantity"]?></td>
		<td width="400"><?php echo $rate["pamam1"]["name"]." - ".$rate["pamam2"]["name"]?>
		</td>
		<td><?php echo $this->getFormatPrice($rate["totalPrice"],$this->data["currency"]["symbol"])?>
		</td>
	</tr>
	<?php endforeach;?>
	<?php if($this->data["guest"]["childs"]>0):?>
	<tr>
		<td width="100"><?php echo $this->data["guest"]["childs"]?></td>
		<td width="400"><?php echo JText::_("CP.HOTEL.CHILDS")." - ".$this->data["childs_feed"]["param3"]?>
		</td>
		<td><?php echo $this->getFormatPrice($this->data["childs_feed"]["totalPrice"],$this->data["currency"]["symbol"])?>
		</td>
	</tr>
	<?php endif;?>
	<?php if(is_array($this->data["supplements"])):foreach($this->data["supplements"] as $supplement):?>
	<tr>
		<td width="100"><?php echo $supplement["quantity"]?></td>
		<td width="400"><?php echo $supplement["name"]?></td>
		<td><?php echo $this->getFormatPrice($supplement["price"],$this->data["currency"]["symbol"])?>
		</td>
	</tr>
	<?php endforeach;endif;?>
	<tr>
		<td width="486" align="right"><div class="subtotal">
		<?php echo JText::_("CP.HOTEL.SUBTOTAL")?>
			</div></td>
		<td><?php echo $this->getFormatPrice($this->data["subtotal"],$this->data["currency"]["symbol"])?>
		</td>
	</tr>
	<tr>
		<td width="486" align="right"><div class="subtotal">
		<?php echo JText::_("CP.HOTEL.TAXES")?>
			</div></td>
		<td><?php echo $this->getFormatPrice($this->data["totalTax"],$this->data["currency"]["symbol"])?>
		</td>
	</tr>
	<tr>
		<td width="486" align="right"><div class="subtotal">
		<?php echo JText::_("CP.HOTEL.TOTAL")?>
			</div></td>
		<td><?php echo $this->getFormatPrice($this->data["total"],$this->data["currency"]["symbol"])?>
		</td>
	</tr>
</table>
<div>
	<?php echo $this->data["disclaimer"]?>
</div>
