<?php
//error_reporting(E_ALL);
//ini_set('display_errors','On');

require_once JPATH_COMPONENT_SITE.'/classes/payment/processors/stripe/Stripe.php';
require_once JPATH_SITE.'/administrator/components/com_jhotelreservation/helpers/logger.php';

class stripeProcessor{    

    private $TRANSACTION_KEY = "";
    private $SECRET_KEY = "";
    
    private $AUTHORIZENET_SANDBOX = 'false';
    var $type;
    var $name;
  	var $mode;
	
	public function initialize($credentials){
		if (!function_exists('curl_init')) {
			throw new Exception('stripe needs the CURL PHP extension.');
		}
		$this->type = $credentials->type; 
		$this->name = $credentials->name;
		$this->mode = $credentials->mode;
		
		$this->TRANSACTION_KEY = $credentials->fields['TRANSACTION_KEY'];
		$this->SECRET_KEY = $credentials->fields['SECRET_KEY'];
		
		Stripe::setApiKey($this->TRANSACTION_KEY);
		Stripe::setApiVersion("2014-11-05");

		if(trim($credentials->mode)=='test')		
			$this->AUTHORIZENET_SANDBOX =  'true';
		else 	
			$this->AUTHORIZENET_SANDBOX =  'false';
	}
	      // Set multiple line items:
	
	public function processTransaction($data)
    {
    	$log = Logger::getInstance();
    	$log->LogDebug("process transaction stripe - ");
    	$result = new stdClass();
    	
    	$result->status = PAYMENT_ERROR;
    	$result->payment_status = PAYMENT_STATUS_FAILURE;
		Stripe::setApiKey($this->SECRET_KEY);
		$result->amount =  $data->cost > 0? $data->cost: $data->total;
		$data->stripeToken=JRequest::getVar('stripeToken',null);
		
		$log->LogDebug("process transaction stripe - token -  ".$data->stripeToken);
		
		try {
			if (!isset($data->stripeToken)){
		    	$result->error_message = "There was an error in processing your request. Please try again later.";
		    	$log->LogDebug("The Stripe Token was not generated correctly");
		    }
		    //strtolower($data->reservationData->hotel->hotel_currency)
		    
		    else{
			    Stripe_Charge::create(array("amount" =>  $result->amount,
			                                "currency" => strtolower($data->reservationData->hotel->hotel_currency),
			                                "card" => $data->stripeToken));
			    $result->status = PAYMENT_SUCCESS;
			    $result->payment_status = PAYMENT_STATUS_PAID;
		    }
		  }
		catch (Exception $e) {
		  	$log->LogDebug($e->getMessage());
		  	$result->error_message = $e->getMessage();
		}
		
        $result->transaction_id = 0;
        $result->payment_date = date("Y-m-d");
        $result->response_code = 0;
        $result->confirmation_id = $data->confirmation_id;
        $result->processor_type = $this->type;
        
		return $result;
        
    }
        
	public function getPaymentProcessorHtml(){
		$html ="
	        <script type=\"text/javascript\" src=\"https://js.stripe.com/v1/\"></script>
	        

						
	         <script type=\"text/javascript\">

	         jQuery(document).on('submit', 'userForm', function(event){    alert(1);});
	         
 	         // this identifies your website in the createToken call below
    	     Stripe.setPublishableKey('$this->TRANSACTION_KEY');
             function stripeResponseHandler(status, response) {
             	//alert('responseHandeler');
                if (response.error) {
                	alert('error');
                    // re-enable the submit button
                    jQuery('.submit-button').removeAttr(\"disabled\");
                    // show the errors on the form
                    jQuery(\".payment-errors\").html(response.error.message);
                    return false;
                } else {
                    var form$ = jQuery(\"#userForm\");
                    // token contains id, last4, and card type
                    var token = response['id'];
                    //alert(token);
                    // insert the token into the form so it gets submitted to the server
                    form$.append(\"<input type='hidden' name='stripeToken' value='\" + token + \"' />\");
                    // and submit
                    form$.get(0).submit();
                }
            }
            var formUser$ = jQuery(\"#userForm\");
            
            jQuery('#userForm').submit(function(event) {
					event.preventDefault();
                	//alert('prepare token');
                    // disable the submit button to prevent repeated clicks
                    jQuery('.ui-button-text').attr(\"disabled\", \"disabled\");
 
                    // createToken returns immediately - the supplied callback submits the form if there are no errors
                    Stripe.createToken({
                        number: jQuery('#card_number').val(),
                        cvc: jQuery('#card_security_code').val(),
                        exp_month: jQuery('#card_expiration_month').val(),
                        exp_year: jQuery('#card_expiration_year').val()
                    }, stripeResponseHandler);
                    
                    
                    return false; // submit from callback
                });
        </script>
	        
	        
			<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
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