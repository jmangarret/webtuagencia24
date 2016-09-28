<?php 

class WireTransfer implements IPaymentProcessor {
	
	var $type;
	var $name;
	
	public function initialize($data){
		$this->type =  $data->type;
		$this->name =  $data->name;
		$this->mode = $data->mode;
		$this->timeout = $data->timeout;
		if(isset($data->fields)) 
			$this->fields = $data->fields;
	}
	
	public function getPaymentGatewayUrl(){
	
	}
	
	public function getPaymentProcessorHtml(){
		$html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
		<li>
		    ".JText::_('LNG_WIRE_TRANSFER',true)."
		    </li>
		</ul>";
		
		return $html;
	}
	
	public function getHtmlFields() {
		$html  = '';
		return $html;
	}
	
	public function processTransaction($data){
		$result = new stdClass();
		$result->transaction_id = 0;
		$result->amount =  $data->cost > 0? $data->cost: $data->total;
		$result->payment_date = date("Y-m-d");
		$result->response_code = 0;
		$result->confirmation_id = $data->confirmation_id;
		$result->currency=  $data->reservationData->hotel->hotel_currency;
		$result->processor_type = $this->type;
		$result->status = PAYMENT_WAITING;
		$result->payment_status = PAYMENT_STATUS_WAITING;
		
		return $result;
	}
	
	public function processResponse($data){
		$result = new stdClass();
			
		return $result;
	}

	public function getPaymentDetails($paymentDetails, $amount, $cost){
		$result = "";
		
		$result = str_replace(EMAIL_MAX_DAYS_PAYD,	floor($this->timeout/(60*24)), JText::_('LNG_PROCESSOR_PAYMENT_MANUAL'));
		$toPay = ($cost==0)?$amount:$cost;
		$result .= str_replace(EMAIL_RESERVATION_COST, $toPay."(".$paymentDetails->currency.")", JText::_('LNG_PROCESSOR_BANK_TRANSFER_EMAIL_TEXT'));
		ob_start();
		?>
			<br/><br/>
			<TABLE>
				<?php
				foreach($this->fields as $column=>$value){?>
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_'.strtoupper($column),true);?> :</b>
					</TD> 
					<TD>
						<?php echo $value?>
					</TD>
				</TR>
				<?php } ?>
			</TABLE>
			<br/><br/>
		<?php
		$result = $result.ob_get_contents();
		$text = JText::_("LNG_PROCESSOR_BANK_TRANSFER_EMAIL_TEXT_IBAN");
		$result = $result.$text;
		$result = $result.JText::_("LNG_PROCESSOR_BANK_TRANSFER_EMAIL_TEXT_USER");
		ob_end_clean();
		
		return $result;
	}
}