<?php 

class Jcc implements IPaymentProcessor {
	
	var $type;
	var $name;
	
	var $paypal_email;
	var $mode;
	var $paymentUrlTest = 'https://tjccpg.jccsecure.com/EcomPayment/RedirectAuthLink';
	var $paymentUrl = 'https://tjccpg.jccsecure.com/EcomPayment/RedirectAuthLink';
	
	var $notifyUrl;
	var $returnUrl;
	var $cancelUrl;
	
	var $currencyCode;
	var $amount;
	var $itemNumber;
	var $itemName;
	var $merchantId;
	var $acquirerId;
	var $password;
	
	public function initialize($data){
		$this->type =  $data->type;
		$this->name =  $data->name;
		$this->mode = $data->mode;
		$this->merchantId = $data->fields['merchant_id'];
		$this->acquirerId = $data->fields['acquirer_id'];
		$this->password	= $data->fields['password'];
	}
	
	
	public function getPaymentGatewayUrl(){
		if($this->mode=="test"){
			return $this->paymentUrlTest;
		}else{
			return $this->paymentUrl;
		}
	}
	
	public function getPaymentProcessorHtml(){
		$html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
		<li>
		    ".JText::_('LNG_REDIRECT',true)."
		    </li>
		</ul>";
		
		return $html;
	}
	
	public function getHtmlFields() {

		$this->notifyUrl.="&processor=jcc";
		$this->returnUrl.="&processor=jcc";
		
		//Version
		$version = "1.0.0";
		//Merchant ID
		$merchantID = $this->merchantId;
		//Acquirer ID
		$acquirerID = $this->acquirerId;
		
		//The SSL secured URL of the merchant to which JCC will send the transaction result
		//This should be SSL enabled – note https:// NOT http://
		//Purchase Amount
		$purchaseAmt = fmt($this->amount);
		//Pad the purchase amount with 0's so that the total length is 13 characters, i.e. 20.50 will become 0000000020.50
		$purchaseAmt = str_pad($purchaseAmt, 13, "0", STR_PAD_LEFT);
		//Remove the dot (.) from the padded purchase amount(JCC will know from currency how many digits to	consider as decimal)
		//0000000020.50 will become 000000002050 (notice there is no dot)
		$formattedPurchaseAmt = substr($purchaseAmt,0,10).substr($purchaseAmt,11);
		//Euro currency ISO Code; see relevant appendix for ISO codes of other currencies
		$currency = 978;
		//The number of decimal points for transaction currency, i.e. in this example we indicate that Euro has 2 decimal points
		$currencyExp = 2;
		//Order number
		$orderID = $this->itemNumber;
		
		
		//Specify we want not only to authorize the amount but also capture at the same time. Alternative value	could be M (for capturing later)

		$captureFlag = "A";
		//Password
		$password = $this->password;
		//Form the plaintext string to encrypt by concatenating Password, Merchant ID, Acquirer ID, Order ID,Formatter Purchase Amount and Currency
		//This will give 1234abcd | 0011223344 | 402971 | TestOrder12345 | 000000002050 | 978 (spaces and |	introduced here for clarity)
		$toEncrypt = $password.$merchantID.$acquirerID.$orderID.$formattedPurchaseAmt.$currency;
		
		//Produce the hash using SHA1
		//This will give b14dcc7842a53f1ec7a621e77c106dfbe8283779
		$sha1Signature = sha1($toEncrypt);
		//Encode the signature using Base64 before transmitting to JCC
		//This will give sU3MeEKlPx7HpiHnfBBt++goN3k=
		$base64Sha1Signature = base64_encode(pack("H*",$sha1Signature));
		//The name of the hash algorithm use to create the signature; can be MD5 or SHA1; the latter is preffered and is what we used in this example
		$signatureMethod = "SHA1";
		
		
		
		$html  = '';
		$html .= sprintf('<input type="hidden" name="Version" value="%s"/>',$version);
		$html .= sprintf('<input type="hidden" name="MerID" value="%s"/>',$merchantID);
		$html .= sprintf('<input type="hidden" name="AcqID" value="%s"/>',$acquirerID);
		$html .= sprintf('<input type="hidden" name="MerRespURL" value="%s"/>',$this->notifyUrl);
		$html .= sprintf('<input type="hidden" name="PurchaseAmt" value="%s"/>',$formattedPurchaseAmt);
		$html .= sprintf('<input type="hidden" name="PurchaseCurrency" value="%s"/>',$currency);
		$html .= sprintf('<input type="hidden" name="PurchaseCurrencyExponent" value="%s"/>',$currencyExp);
		$html .= sprintf('<input type="hidden" name="OrderID" value="%s"/>',$orderID);
		$html .= sprintf('<input type="hidden" name="CaptureFlag" value="%s"/>',$captureFlag);
		$html .= sprintf('<input type="hidden" name="Signature" value="%s"/>',$base64Sha1Signature);
		$html .= sprintf('<input type="hidden" name="SignatureMethod" value="%s"/>',$signatureMethod);
		//echo $html; exit;

		return $html;
	}
	
	public function processTransaction($data){
		$this->notifyUrl = JRoute::_('index.php?option=com_jhotelreservation&task=paymentoptions.processPaymentResponse',false,1);
		$this->cancelUrl = JRoute::_('index.php?option=com_jhotelreservation&task=paymentoptions.processCancelResponse',false,-1);;
		
		
		$this->amount = $data->cost > 0? $data->cost: $data->total;
		$this->itemName = $data->reservationData->hotel->hotel_name." Reservation from ".$data->reservationData->userData->start_date.' to '.$data->reservationData->userData->end_date;
		$this->itemNumber = $data->confirmation_id;
		$this->currencyCode = $data->reservationData->hotel->hotel_currency;
		
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
	
	
	public function processResponse($data){
		
		//Parameters returned from JCC
		$jccMerID = $data['MerID'];
		$jccAcqID = $data['AcqID'];
		$jccOrderID = $data['OrderID'];
		$jccResponseCode = intval($data['ResponseCode']);
		$jccReasonCode = intval($data['ReasonCode']);
		$jccReasonDescr = $data['ReasonCodeDesc'];
		$jccRef = $data['ReferenceNo'];
		$jccPaddedCardNo = $data['PaddedCardNo'];
		$jccSignature = $data['Signature'];
		//Authorization code is only returned in case of successful transaction, indicated with	a value of 1
		//for both response code and reason code
		if ($jccResponseCode==1 && $jccReasonCode==1){
			$jccAuthNo = $data['AuthCode'];
		}
		//The parameters used for creating the JCC signature as stored on the merchant server
		$merchantID = $this->merchantId;
		$acquirerID = $this->acquirerId;
		$password = $this->password;
		$orderID = $jccOrderID;

		//Form the plaintext string that JCC used to product the hash it sent by concatenating Password, Merchant ID, Acquirer ID and Order ID
		//This will give: 1234abcd | 0011223344 | 402971 | TestOrder12345 (spaces and | introducedhere for clarity)
		$toEncrypt = $password.$merchantID.$acquirerID.$orderID;
		//Produce the hash using SHA1
		//This will give fed389f2e634fa6b62bdfbfafd05be761176cee9
		$sha1Signature = sha1($toEncrypt);
		//Encode the signature using Base64
		//This will give /tOJ8uY0+mtivfv6/QW+dhF2zuk=
		$expectedBase64Sha1Signature = base64_encode(pack("H*",$sha1Signature));
		//JCC signature verification is performed simply by comparing the signature we produced with the one sent from JCC
		$verifyJCCSignature = ($expectedBase64Sha1Signature == $jccSignature);
		
		$result = new stdClass();
		$result->response_message = $jccReasonDescr;
		if(!$verifyJCCSignature)
			$result->error_message = "Invalid signature verification";
		//transaction approved
		else if($jccResponseCode==1){
			$result->status = PAYMENT_SUCCESS;
			$result->payment_status = PAYMENT_STATUS_PAID;
				
		}//Token,hash de-activation -0  && Declined = 2
		else if($jccResponseCode==0 || $jccResponseCode==2){
			$result->status = PAYMENT_ERROR;
			$result->payment_status = PAYMENT_STATUS_FAILURE;
			$result->error_message=$jccReasonDescr;
		} 
		
		$result->transaction_id = $data["ReferenceNo"];
		$result->amount = $data["mc_gross"];
		$result->payment_date = date('Y-m-d H:i:s');
		$result->payment_method= $this->type;
		$result->response_code = $jccResponseCode;
		$result->confirmation_id = $jccOrderID;
		$result->currency= $data["mc_currency"];
		$result->processor_type = $this->type;
		
		return $result;
	}

	public function getPaymentDetails($paymentDetails, $amount, $cost){
		return JText::_('LNG_PROCESSOR_JCC',true);
	}
}