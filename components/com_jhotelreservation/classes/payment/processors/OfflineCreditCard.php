<?php 

class OfflineCreditCard implements IPaymentProcessor {
	
	var $type;
	var $name;
	
	public function initialize($data){
		$this->type =  $data->type;
		$this->name =  $data->name;
		$this->mode = $data->mode;
	}
	
	public function getPaymentGatewayUrl(){
	
	}
	
	public function getPaymentProcessorHtml(){
		$html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
			<li>
					<TABLE width=100% valign=top class='table_data'>
							<TR>
								<TD colspan=3 align=left style='padding-top:10px;padding-bottom:10px;'>	
									-".JText::_('LNG_FIELDS_MARKED_WITH')."<span class='mand'>*</span> ".JText::_('LNG_ARE_MANDATORY')."
								</TD>
							</TR>
							<tr style=''>
								<TD colspan=3  align=left>
									-".JText::_('LNG_CREDIT_CARD_REQUIRED')."
								</TD>
							</TR>
							
							<tr style='background-color:##CCCCCC'>
								<TD colspan=1 width=20%  align=left>
									".JText::_('LNG_NAME_OF_CARD')."<span class='mand'>*</span>
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
									".JText::_('LNG_CREDIT_CARD_NUMBER')." <span class='mand'>*</span>
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
									".JText::_('LNG_EXPIRATION_DATE')." <span class='mand'>*</span>
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
									".JText::_('LNG_SECURITY_CODE')."
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
		
		//offline credit card data
		$result->card_name = JRequest::getVar("card_name",null);
		$result->card_number = JRequest::getVar("card_number",null);
		$result->card_expiration_year = JRequest::getVar("card_expiration_year",null);	
		$result->card_expiration_month = JRequest::getVar("card_expiration_month",null);
		$result->card_security_code = JRequest::getVar("card_security_code",null);
		
		return $result;
	}
	
	public function processResponse($data){
		$result = new stdClass();
			
		return $result;
	}

	public function getPaymentDetails($paymentDetails, $amount, $cost){
		$result = "";
		$app = JFactory::getApplication();
		$isAdmin = $app->isAdmin();
		
		ob_start();
		?>
			<br/><br/>
			<TABLE>
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_PAYMENT_METHOD',true)?> : </b>
					</TD> 
					<TD>
						 <?php echo $this->name?>
					</TD>
				</TR>
			
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_NAME_OF_CARD',true)?> : </b>
					</TD> 
					<TD>
						 <?php echo $paymentDetails->card_name?>
					</TD>
				</TR>
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_CREDIT_CARD_NUMBER',true)?> :</b>
					</TD> 
					<TD>
						<input type="hidden" name="card_number" value="<?php echo $paymentDetails->card_number?>">
						<?php echo $isAdmin?$paymentDetails->card_number:JHotelUtil::getInstance()->secretizeCreditCard($paymentDetails->card_number);?>
					</TD>
				</TR>
				
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo  JText::_('LNG_EXPIRATION_DATE',true)?> :</b>
					</TD> 
					<TD>
						<?php echo $paymentDetails->card_expiration_month." - ". $paymentDetails->card_expiration_year;?>
					</TD>
				</TR>
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_SECURITY_CODE',true)?> :</b>
					</TD> 
					<TD>
						<?php echo $paymentDetails->card_security_code?>
					</TD>
				</TR>
				<?php if($isAdmin){?>
				<TR>
					<TD align=left width=40% nowrap>
						&nbsp;
					</TD> 
					<TD>
						<button type="button" onClick="jQuery('#task').val('reservation.secretizeCard');jQuery('#adminForm').submit()">Secretize</button>
					</TD>
				</TR>
				<?php }?>

			</TABLE>
			<br/><br/>

		<?php
		$result = $result.ob_get_contents();
	
		ob_end_clean();
		
		return $result;
	}
}