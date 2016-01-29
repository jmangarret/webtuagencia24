<?php
require_once JPATH_SITE.'/administrator/components/com_jhotelreservation/helpers/logger.php';

class bkt{    
    var $type;
    var $name;
  	var $mode;
  	var $paymentUrlTest; 
  	var $paymentUrl;
  	 
	
	public function initialize($data){

		$this->type =  $data->type;
		$this->name =  $data->name;
		$this->mode = $data->mode;
		$this->client_id = $data->fields['username'];
		$this->store_key = $data->fields['transaction_key'];
		$this->paymentUrlTest = 'https://testsanalpos2.est.com.tr/servlet/est3Dgate';
		$this->paymentUrl ='https://bktvpos.bkt.com.al/servlet/est3Dgate';
	}
	      // Set multiple line items:
	
	public function processTransaction($data){

		$this->amount = $data->cost > 0? $data->cost: $data->total;
		$this->itemName = $data->reservationData->hotel->hotel_name." Reservation from ".$data->reservationData->userData->start_date.' to '.$data->reservationData->userData->end_date;
		$this->itemNumber = $data->confirmation_id;
		$this->currencyCode = $data->reservationData->hotel->hotel_currency;
		$this->confirmation_id = $data->confirmation_id;
		$this->first_name =	$data->reservationData->userData->first_name;
		$this->last_name =	$data->reservationData->userData->last_name;
		$this->country	 =	$data->reservationData->userData->country;
		$this->address =	$data->reservationData->userData->address;
		$this->city =	$data->reservationData->userData->city;
		$this->postal_code =	$data->reservationData->userData->postal_code;
		$this->state_name =	$data->reservationData->userData->state_name;

		$result = new stdClass();
		$result->transaction_id = 0;
		$result->amount =  $data->cost > 0? $data->cost: $data->total;
		$result->payment_date = date("Y-m-d");
		$result->response_code = 0;
		$result->confirmation_id = $data->confirmation_id;
		$result->currency=  $data->reservationData->hotel->hotel_currency;
		$result->processor_type = $this->type;
		$result->status = PAYMENT_REDIRECT;
		$result->payment_status = PAYMENT_STATUS_PENDING;
		
		return $result;
	}
	
	function processResponse($post){
		
		$log = Logger::getInstance();
		$log->LogDebug("process respont bkt - ".serialize($post));
		
	
		$number = $post["md"];
		$orderId = $post["oid"];
		$payerAuthenticationCode = $post["cavv"];
		$payerSecurityLevel = $post["eci"];
		$payerTxnId = $post["xid"];
		$mdStatus = $post["mdStatus"];
		$cardExpMonth = $post["Ecom_Payment_Card_ExpDate_Month"];
		$cardExpYear= $post["Ecom_Payment_Card_ExpDate_Year"];
		$clientId= $post["clientid"];
		$ipAddress = $post["clientIp"];
		$email = $post["Email"];
		$response = $post["Response"];
		$procReturnCode = $post["ProcReturnCode"];
		$hashVal = $post["HASH"];
		$hashParamsVal = $post["HASHPARAMSVAL"];
		$amount= $post["total1"];
		
	
		$hashstr = $clientId.$orderId.$post["AuthCode"] . $procReturnCode . $response .$mdStatus. $payerAuthenticationCode .$payerSecurityLevel . $number.$this->store_key;
		$hash = base64_encode(pack('H*',sha1($hashstr)));
	
		//print_r($post);
		//exit;
	
		if($hash!=$hashVal){
			$log->LogDebug("process response bkt - hash fail due to wrong bkt params");
			//throw new Exception("Notification not received from BKT");
		}
	
		$status = true;
		
		$result = new stdClass();
		if($response == "Approved"){
			$result->status = PAYMENT_SUCCESS;
			$result->payment_status = PAYMENT_STATUS_PAID;
		}
		else{
			$result->status = PAYMENT_ERROR;
			$result->payment_status = PAYMENT_STATUS_FAILURE;
		}
	
		$result->transaction_id = $payerTxnId;
		$result->payment_date = date("Y-m-d");
		$result->confirmation_id = $orderId;
		$result->processor_type = $this->type;
		$result->currency= "";
		$result->response_message = $post["mdErrorMsg"];
		$result->response_code = 0;
		$result->amount = $amount;

		return $result;
	}
	
	
        
	public function getPaymentProcessorHtml(){
		$html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
		<li>
		    ".JText::_('LNG_REDIRECT')."
		    </li>
		</ul>";
		
		return $html;
	}
	
	public function getPaymentDetails($paymentDetails, $amount, $cost){
		$result = "";
		ob_start();
		?>
			<br/>
			<TABLE>
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_PAYMENT_METHOD',true)?> : </b>
					</TD> 
					<TD>
						 <?php echo $this->name?>
					</TD>
				</TR>
			</TABLE>
			<br/><br/>

		<?php
		$result = $result.ob_get_contents();
	
		ob_end_clean();
		
		return $result;
	}
    public function getHtmlFields() {
		$html  = '';

		$clientId = $this->client_id; //""; //Merchant Id defined by bank to user
		$amount = $this->amount;
	
		$oid = $this->confirmation_id; //Order Id. Must be unique. If left blank, system will generate a unique one.
		$okUrl = JURI::base()."index.php?option=com_jhotelreservation&task=paymentoptions.processPaymentResponse&processor=".$this->type; //URL which client be redirected if authentication is successful
		$failUrl = JURI::base()."index.php?option=com_jhotelreservation&task=paymentoptions.processPaymentResponse&processor=".$this->type; //URL which client be redirected if authentication is not successful
		$rnd = microtime(); //A random number, such as date/time
		$currencyVal = "978"; //Currency code, 949 for TL, ISO_4217 standard
		$storekey = $this->store_key;//""; //Store key value, defined by bank.
		$storetype = "3D_PAY_HOSTING"; //3D authentication model
			
		$lang = "en"; //Language parameter, "tr" for Turkish (default), "en" for English
		$instalment = ""; //Instalment count, if there's no instalment should left blank
		$transactionType = "Auth"; //transaction type
	
		$hashstr = $clientId . $oid . $amount . $okUrl . $failUrl .$transactionType. $instalment .$rnd . $storekey;
		$hash = base64_encode(pack('H*',sha1($hashstr)));
		
		$html .= sprintf('<input type="hidden" name="clientid" id="clientid" value="%s">',$clientId);
		$html .= sprintf('<input type="hidden" name="amount" id="amount" value="%s">',$amount);
		$html .= sprintf('<input type="hidden" name="islemtipi" id="islemtipi" value="%s">',$transactionType);
		$html .= sprintf('<input type="hidden" name="taksit" id="taksit" value="%s">',$instalment);
		$html .= sprintf('<input type="hidden" name="oid" id="oid" value="%s">',$oid);
		$html .= sprintf('<input type="hidden" name="okUrl" id="okUrl" value="%s">',$okUrl);
		$html .= sprintf('<input type="hidden" name="failUrl" id="failUrl" value="%s">',$failUrl);
		$html .= sprintf('<input type="hidden" name="clientid" id="clientid" value="%s">',$clientId);
		$html .= sprintf('<input type="hidden" name="rnd" id="rnd" value="%s">',$rnd);
		$html .= sprintf('<input type="hidden" name="hash" id="hash" value="%s">',$hash);
		$html .= sprintf('<input type="hidden" name="storetype" id="storetype" value="%s">',$storetype);
		$html .= sprintf('<input type="hidden" name="lang" id="lang" value="%s">',$lang);
		$html .= sprintf('<input type="hidden" name="currency" id="currency" value="%s">',$currencyVal);
		$html .= sprintf('<input type="hidden" name="refreshtime" id="refreshtime" value="10">');
		
		$html .= sprintf('<input type="hidden" name="Fismi" id="Fismi" value="%s">',$this->first_name." ".$this->last_name);
		$html .= sprintf('<input type="hidden" name="faturaFirma" id="faturaFirma" value="0">');
		$html .= sprintf('<input type="hidden" name="Fadres" id="Fadres" value="%s">',$this->address);				
		$html .= sprintf('<input type="hidden" name="Fadres2" id="Fadres2" value="0">');
		$html .= sprintf('<input type="hidden" name="Fil" id="Fil" value="%s">',$this->state_name);
		$html .= sprintf('<input type="hidden" name="Filce" id="Filce" value="%s">',$this->city);
		$html .= sprintf('<input type="hidden" name="Email" id="Email" value="%s">',$this->email);
		$html .= sprintf('<input type="hidden" name="tel" id="tel" value="0">');
		
		$html .= sprintf('<input type="hidden" name="fulkekod" id="fulkekod" value="%s">',$this->country);
		$html .= sprintf('<input type="hidden" name="nakliyeFirma" id="nakliyeFirma" value="%s">',$this->first_name." ".$this->last_name);
		$html .= sprintf('<input type="hidden" name="tismi" id="tismi" value="%s">',$this->first_name." ".$this->last_name);
		$html .= sprintf('<input type="hidden" name="tadres2" id="tadres2" value="%s">',$this->address);
		$html .= sprintf('<input type="hidden" name="til" id="til" value="%s">',$this->state_name);
		$html .= sprintf('<input type="hidden" name="tilce" id="tilce" value="%s">',$this->city);
		$html .= sprintf('<input type="hidden" name="tpostakodu" id="tpostakodu" value="%s">',$this->postal_code);
		$html .= sprintf('<input type="hidden" name="tulkekod" id="tulkekod" value="%s">',$this->country);
		$html .= sprintf('<input type="hidden" name="itemnumber1" id="itemnumber1" value="0">');
		$html .= sprintf('<input type="hidden" name="itemnumber1" id="itemnumber1" value="0">');
		$html .= sprintf('<input type="hidden" name="qty1" id="qty1" value="1">');
		$html .= sprintf('<input type="hidden" name="desc1" id="desc1" value="%s">',$this->itemName);
		$html .= sprintf('<input type="hidden" name="id1" id="id1" value="0">');
		
		$html .= sprintf('<input type="hidden" name="price1" id="price1" value="%s">',$amount);
		$html .= sprintf('<input type="hidden" name="total1" id="total1" value="%s">',$amount);
		return $html;
    }
    
    public function getPaymentGatewayUrl(){
    	if($this->mode=="test"){
    		return $this->paymentUrlTest;
    	}else{
    		return $this->paymentUrl;
    	}
    }
}
?>