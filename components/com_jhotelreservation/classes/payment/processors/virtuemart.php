<?php
define('JPATH_VM_ADMINISTRATOR', JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart');
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'models'.DS.'product.php';
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'tables'.DS.'products.php';
require_once JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'cart.php';

/* 
 * replace in administrator/components/com_virtuemart/helpers/vmtable.php on line 115 JPATH_COMPONENT_ADMINISTRATOR with JPATH_VM_ADMINISTRATOR
 * disable in administrator/components/com_virtuemart/models product.php(store,updateXrefAndChildTables) media.php(storeMedia) 
 *
 */

class virtuemart{    

    private $userId = "";
    private $AUTHORIZENET_TRANSACTION_KEY = "";
    private $AUTHORIZENET_SANDBOX = 'false';
    var $type;
    var $name;
  	var $mode;
	
	public function initialize($credentials){
		if (!class_exists('VirtueMartModelProduct')) {
			throw new Exception('Virtuemart needs to be installed for this to work');
		}
		$this->type = $credentials->type; 
		$this->name = $credentials->name;
		$this->mode = $credentials->mode;
		
		$this->userId =$credentials->fields['username'];
	
	}
	      // Set multiple line items:
	public function getPaymentGatewayUrl(){
		return 'index.php?option=com_virtuemart&view=cart&task=checkout';
		exit;
		//return JRoute::_('index.php?option=com_virtuemart&view=cart&task=checkout', FALSE);
	}
    public function processTransaction($data)
    {
    	if(!$this->loginUser())
		throw new Exception("Cannot login admin"); 

	   	$userObj = UserService::getUserByEmail($data->reservationData->userData->email);
	   	if(isset($userObj->id)){ 	
		   JUserHelper::addUserToGroup($userObj->id,8);
	    	}
		else 
		    throw new Exception("Cannot find client account ".$data->reservationData->userData->email);
	     
    	if(!$this->loginClient($data->reservationData->userData->email, $data->userData->password))
		throw new Exception("Cannot login"); 

	   	//error_reporting(E_ALL);
		//ini_set('display_errors','On');
		
		// create reservation product
    	$vmProduct = JModel::getInstance("Product","VirtueMartModel");
    	$product=array();
    	$product['product_name']=$data->reservationData->hotel->hotel_name." Reservation(".$data->confirmation_id.") from ".$data->reservationData->userData->start_date.' to '.$data->reservationData->userData->end_date;
    	$product['slug']="hotel_reservation";
    	$product['notification_template']=1;
    	$product['product_unit']='KG';
    	$product['product_available_date']= date("Y-m-d");
    	$product['mprices']= array('product_price'=>array($data->cost > 0? $data->cost: $data->total));
    	$product['mprices']['basePrice']= array(0);
    	//$product['mprices']['product_currency']=array(191);
    	$product['mprices']['product_tax_id']= array(0);
    	$product['mprices']['salesPrice']= array('');
    	$product['mprices']['price_quantity_start']= array('');
    	$product['mprices']['price_quantity_end']= array('');
    	$product['mprices']['product_override_price']= array('');
    	$product['mprices']['virtuemart_product_price_id']= array('');
    	$product['mprices']['product_override_price']= array('');
    	$product['mprices']['virtuemart_shoppergroup_id']= array('');
    	$product['mprices']['product_discount_id']= array(0);
    	$product['mprices']['product_price_publish_up']= array('');
    	$product['mprices']['product_price_publish_down']= array('');
    	$product['mprices']['override']= array('');
    	$vmProduct->store($product);
    	
    	//add product to cart    	
    	$cart = VirtueMartCart::getCart();
    	$_POST['virtuemart_product_id'] = $vmProduct->_id;
    	JRequest::setVar('virtuemart_product_id',$vmProduct->_id);
    	JRequest::setVar('quantity',array(1));
    	
    	 $cart->add();
    	 
    	 
    	//update Bill TO info virtuemart 
    	$lastName = $data->reservationData->userData->last_name;
    	$name= $data->reservationData->userData->first_name." ".$data->reservationData->userData->last_name;

		$db =JFactory::getDBO();
		$country = $data->reservationData->userData->country;
	    	$query = "select * from #__virtuemart_countries where lower(country_name) like lower('%$country%') limit 0,1";
	    	$db->setQuery( $query );
	   	$countryData 	=$db->loadObject();
	   	$vmCountryId = 0; 
	   	
	
	   	if(count($countryData)>0)
	   	   $vmCountryId =$countryData->virtuemart_country_id; 
	    	$query = "insert into #__virtuemart_userinfos(`virtuemart_user_id`,`address_type`,`name`,`first_name`,`last_name`,`phone_1`,`address_1`,`city`,`virtuemart_country_id`,`zip`,`created_by`) values( '".JFactory::getUser()->id."','BT','".$name."','".$data->reservationData->userData->first_name."','".$lastName."','".$data->reservationData->userData->phone."', '".$data->reservationData->userData->address."','".$data->reservationData->userData->city."',$vmCountryId ,'".$data->reservationData->userData->postal_code."','".$data->confirmation_id."')";
	    	$db->setQuery( $query );
	    	if (!$db->query())
		   throw new Exception("Cannot update billing info"); 
	
		JUserHelper::removeUserFromGroup(JFactory::getUser()->id,8);
	
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
    	$db =JFactory::getDBO();
   		$query = "select b.* from #__virtuemart_orders a left join #__virtuemart_userinfos b  USING (virtuemart_user_id)  where order_number='".$data['invoice']."' order by b.virtuemart_userinfo_id desc limit 0,1";
    	$db->setQuery( $query );
    	$cData 	=$db->loadObject();
    	
    	$result = new stdClass();
    	$result->transaction_id = $data["txn_id"];
    	$result->amount = $data["mc_gross"];
    	$result->payment_date = $data["payment_date"];
    	$result->response_code = $data["payment_status"];
    	$result->confirmation_id = $cData->created_by;
    	$result->currency= $data["mc_currency"];
    	$result->processor_type = $this->type;
    	$result->status = PAYMENT_SUCCESS;
    	$result->payment_status = PAYMENT_STATUS_PAID;
    	
    	return $result;
    }
    
    function loginUser(){
    
	    $app =JFactory::getApplication('site');
	    $app->initialise();
	    
	    $credentials = array();
	    $credentials['username'] = "cmsjunkie";
	    $credentials['password'] = "cmsjunkie77";
	    
	    if($app->login($credentials)) 
	    	return true; 
	    else 
	    	return false;
    }
    function loginClient($username,$password){
    
    	$app =JFactory::getApplication('site');
    	$app->initialise();
    	 
    	$credentials = array();
    	$credentials['username'] = $username;
    	$credentials['password'] = UserService::generateAceesPassword($username,true);

    	if($app->login($credentials))
	 return true;
    	else
    	 return false;
    }
    function logoutUser(){
    
    	$app =JFactory::getApplication('site');
    	$app->initialise();
    	 
    	//echo "<br>";print_r($app);
    	 
    	if($app->logout())
    	return true;
    	else
    	return false;
    }
    
	public function getPaymentProcessorHtml(){
		$html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
		<li>
		    ".JText::_('LNG_REDIRECT',true)."
		    </li>
		</ul>";
		
		return $html;
	}
    public function getPaymentDetails($paymentDetails, $amount, $cost){
    	return JText::_('Virtuemart',true);
    }
    public function getHtmlFields() {
    	$html  = '';
    	return $html;
    }
}
?>