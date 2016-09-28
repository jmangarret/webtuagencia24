<?php 

class EenmaligeIncasso implements IPaymentProcessor {
	
	var $type;
	var $name;
	
	public function initialize($data){
		$this->type =  $data->type;
		$this->name =  $data->name;	
	}
	
	public function getPaymentGatewayUrl(){
	
	}
	
	public function getPaymentProcessorHtml(){
		$html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
		<li>
		    ".JText::_('LNG_INFO_EENMALIGE_INCASO',true)."
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
		$result->payment_status = PAYMENT_STATUS_PAID;
		$result->status = PAYMENT_SUCCESS;
		
		return $result;
	}
	

	public function getPaymentDetails($paymentDetails, $amount, $cost){
		$result = "<strong>".JText::_('LNG_PROCESSOR_EENMALIGE_INCASO')."</strong><br/>";
	
		$emailText = JText::_('LNG_PROCESSOR_EENMALIGE_INCASO_EMAIL_TEXT');
		$emailText = str_replace(EMAIL_RESERVATION_COST, number_format($cost,2),	$emailText);
		$result.= $emailText;
		
		return $result;
	}
}