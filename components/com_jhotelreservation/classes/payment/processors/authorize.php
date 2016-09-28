<?php
require_once JPATH_COMPONENT_SITE.DS.'classes'.DS.'payment'.DS.'processors'.DS.'authorize'.DS.'AuthorizeNet.php';
require_once JPATH_SITE.'/administrator/components/com_jhotelreservation/helpers/logger.php';

class authorize{    

    private $AUTHORIZENET_API_LOGIN_ID = "";
    private $AUTHORIZENET_TRANSACTION_KEY = "";
    private $AUTHORIZENET_SANDBOX = 'false';
    var $type;
    var $name;
  	var $mode;
	
	public function initialize($credentials){
		if (!function_exists('curl_init')) {
			throw new Exception('Authorize.net needs the CURL PHP extension.');
		}
		$this->type = $credentials->type; 
		$this->name = $credentials->name;
		$this->mode = $credentials->mode;
		
		$this->AUTHORIZENET_API_LOGIN_ID =$credentials->fields['username'];
		$this->AUTHORIZENET_TRANSACTION_KEY = $credentials->fields['transaction_key'];
		if(trim($credentials->mode)=='test')		
			$this->AUTHORIZENET_SANDBOX =  'true';
		else 	
			$this->AUTHORIZENET_SANDBOX =  'false';
	}
	      // Set multiple line items:
	
	public function processTransaction($data)
    {
    	$log = Logger::getInstance();
    	$log->LogDebug("process transaction authorize - ");
    	 
    	//creditCard,$order,$customer
    	$customer = (object)array();
    	$customer->first_name = $data->reservationData->userData->first_name;
    	$customer->last_name = $data->reservationData->userData->last_name;
    	$customer->address = $data->reservationData->userData->address;
    	$customer->city = $data->reservationData->userData->city	;
    	$customer->state = $data->reservationData->userData->state_name;
    	$customer->country = $data->reservationData->userData->country;
    	$customer->email = $data->reservationData->userData->email;
   		
    	$order = array(
			'description' => JText::_('LNG_ORDER_DESC').' '.$data->reservationData->hotel->hotel_name.'('.$data->reservationData->userData->start_date.'-'.$data->reservationData->userData->end_date.')',
			'invoice_num' => $data->confirmation_id
    	);
    	$result = new stdClass();
    	 
    	$result->card_name = JRequest::getVar("card_name",null);
    	$result->card_number = JRequest::getVar("card_number",null);
    	$result->card_expiration_year = JRequest::getVar("card_expiration_year",null);
    	$result->card_expiration_month = JRequest::getVar("card_expiration_month",null);
    	$result->card_security_code = JRequest::getVar("card_security_code",null);

    	$result->amount =  $data->cost > 0? $data->cost: $data->total;
    	 

    	
    	$creditCard = array(
			'exp_date' => $result->card_expiration_month."".substr($result->card_expiration_year,-2),
    		'card_num' => $result->card_number,
    		'amount' => $result->amount													            
    	);
    	
    	$sale = new AuthorizeNetAIM($this->AUTHORIZENET_API_LOGIN_ID,$this->AUTHORIZENET_TRANSACTION_KEY);
    	if($this->AUTHORIZENET_SANDBOX=='false')
			$sale->setSandbox(false);
		else 
			$sale->setSandbox(true);
        $sale->setFields($creditCard);
        $sale->setFields($order);
        $sale->setFields($customer);        
        $response = $sale->authorizeAndCapture();

        $log->LogDebug("process response authorize -  ".serialize($response));
        

        if(isset($response->approved) && $response->approved==1){
	        $result->status = PAYMENT_SUCCESS;
	        $result->payment_status = PAYMENT_STATUS_PAID;
        }
        else{
        	$result->status = PAYMENT_ERROR;
        	$result->payment_status = PAYMENT_STATUS_FAILURE;
        	$result->error_message = $response->error_message;
        }
        
        $result->transaction_id = 0;
        $result->payment_date = date("Y-m-d");
        $result->response_code = $response->approved;
        $result->confirmation_id = $data->confirmation_id;
        $result->processor_type = $this->type;
        
        
		return $result;
        
    }
        
	public function getPaymentProcessorHtml(){
		$html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
			<li>
					<TABLE width=100% valign=top class='table_data'>
							<TR>
								<TD colspan=3 align=left style='padding-top:10px;padding-bottom:10px;'>	
									-".JText::_('LNG_FIELDS_MARKED_WITH',true)."<span class='mand'>*</span> ".JText::_('LNG_ARE_MANDATORY',true)."
								</TD>
							</TR>
							<tr style=''>
								<TD colspan=3  align=left>
									-".JText::_('LNG_CREDIT_CARD_REQUIRED',true)."
								</TD>
							</TR>
							
							<tr style='background-color:##CCCCCC'>
								<TD colspan=1 width=20%  align=left>
									".JText::_('LNG_NAME_OF_CARD',true)."<span class='mand'>*</span>
								</TD>
								<TD colspan=2 align=left>
									<input 
										type 			= 'text'
										name			= 'card_name'
										id				= 'card_name'
										autocomplete	= 'off'
										size			= 50
										value			= ''
										class = 'validate[required] text-input'
									>
								</TD>
							</TR>
							<tr style='background-color:##CCCCCC'>
								<TD colspan=1 width=20%  align=left>
									".JText::_('LNG_CREDIT_CARD_NUMBER',true)." <span class='mand'>*</span>
								</TD>
								<TD colspan=2 align=left>
									<input 
										type 			= 'text'
										name			= 'card_number'
										id				= 'card_number'
										autocomplete	= 'off'
										size			= 50
										value			= ''
										class= 'validate[required,creditCard]'
									>
								</TD>
							</TR>
							<tr style='background-color:##CCCCCC'>
								<TD colspan=1 width=20%  align=left>
									".JText::_('LNG_EXPIRATION_DATE',true)." <span class='mand'>*</span>
								</TD>
								<TD align=left>
									<select name='card_expiration_month' id = 'card_expiration_month' class= 'validate[required]'>
										<option value='0'>
											&nbsp;
										</option>
										";
				for( $i=1; $i<=12;$i++){
					$html .= "<option value='".$i."'>	".$i." </option>";
				}
					$html .= "</select>
									&nbsp;
									<select name='card_expiration_year' id = 'card_expiration_year' class= 'validate[required]'>
										<option 
											value='0'
											
										>
											&nbsp;
										</option>";

				for( $i=date('Y'); $i<=date('Y')+5;$i++ ){
					$html .= "<option value='".$i."' > ".$i." </option>";
				}

				$html .= "</select>
								</TD>
							</TR>
							<tr style='background-color:##CCCCCC'>
								<TD colspan=1 width=20%  align=left>
									".JText::_('LNG_SECURITY_CODE',true)."
								</TD>
								<TD colspan=2 align=left>
									<input 
										type 			= 'text'
										name			= 'card_security_code'
										id				= 'card_security_code'
										autocomplete	= 'off'
										size			= 4
										maxlength		= 4
										value			= ''
										class= 'validate[required]'
									>
								</TD>
							</TR>
						</TABLE>
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
    	return $html;
    }
}
?>