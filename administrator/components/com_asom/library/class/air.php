<?php
/**
 *
 */
defined('_JEXEC') or die('Restricted access');

class AsomClassAir extends JObject
{

    private $_delegate = null;

    private $_order    = null;


    public function __construct(&$delegate)
    {
        // Incluimos la ruta de las tablas que se van a usar
        $path = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_asom'.DS.'tables';
        JTable::addIncludePath($path);

        $this->_delegate = &$delegate;
    }

    private function _saveOrder()
    {
        // Obtenemos el objeto para ser guardado
        $data = $this->_delegate->getOrder();

        $order = JTable::getInstance('orders');
        if(!$order->save($data))
        {
            $this->setError($order->getError());
            return false;
        }

        // Obtenemos el XML del booking, para almacenarlo y obtener de alli la información.
        // TODO La idea es que a medida que se evolucione, este metodo quedara obsoleto, ya
        // que toda la información se organizará y se almacenara en sus respectivas tablas.
        $dataRaw = $this->_delegate->getDataRaw();
        $dataRaw = is_string($dataRaw) ? $dataRaw : serialize($dataRaw);
		
        //Xml Adicional
        $AdditionalInfo = $this->xmlAdditionalInfo($_POST);
        
        
     
		$dataRaw = array(
            'order_id' => $order->id,
            'data'     => $dataRaw,
        	'AdditionalInfo'=>$AdditionalInfo
        //Agregar el campo nuevo de la tabla
        );

        $source = JTable::getInstance('source');
        if(!$source->save($dataRaw))
        {
            $order->delete();
            $this->setError($source->getError());
            return false;
        }

        // Se guarda el historico en el historial
        $dataHistory = array(
            'order_id' => $order->id,
            'user_id'  => $order->user_id,
            'note'     => 'AOM_ORDER_SAVED',
            'status'   => $order->status
        );

        $history = JTable::getInstance('history');
        if(!$history->save($dataHistory))
        {
            $source->delete();
            $order->delete();
            $this->setError($source->getError());
            return false;
        }

        // Se asigna el id de la orden para que la clase conozca la orden actual
        $this->_order = $order->id;
        
        $user      = JFactory::getUser();
       //Activar y logear el usuario si no existe
        if($user->id==0){
       	
        //Actualiza el estado del usuario	
   		$db =& JFactory::getDBO();
		$sql = "UPDATE #__users set block=0, activation=''
                WHERE id = ".$order->user_id;

		$db->setQuery($sql);
		$db->query();
		 
		//Saco de la bd el usuario y la contrase�a
        $query	= $db->getQuery(true);
		$query->select('username, password');
		$query->from('#__users');
		$query->where("id=$order->user_id");

		$db->setQuery($query);
		$result = $db->loadObject();

			// Get the log in credentials.
		$credentials = array();
		$credentials['username'] = $result->username;
		$credentials['password'] = $_SESSION['passnew'];
 
		//Logea el usuario 
		$app = JFactory::getApplication();
        $app->login($credentials);		
        }
       	//Redireccionar a el detalle de la reserva - Crear JFactory y Ridereccionar
        $app = JFactory::getApplication();
        $link   = JRoute::_('index.php?option=com_asom&task=orders.resume&order='.$order->id);
        
        $app->redirect($link);	
       
		
    }
 /**
     * La funcion genera el xml con los datos adicionales
     */
    public function xmlAdditionalInfo($dato){
 		 
    	$data = '<Data>';
    	$data .='<AdditionalInfo>';
    	$data .='<Remark1>'.$dato['wsform']['note1'][0] .'</Remark1>';
    	$data .='<Remark2>'.$dato['wsform']['note2'][0].'</Remark2>';
    	$data .='<Remark3>'.$dato['wsform']['note3'][0].'</Remark3>';
    	$data .='<TypeRequest>'.$dato['wsform']['paymentmethod'].'</TypeRequest>';
        $data .='<PaymentTypeId>'.$dato['wsform']['paymentmet'].'</PaymentTypeId>';
    	$data .='<fapa>'.$dato['wsform']['fapa'].'</fapa>';
    	$data .='<CreditCardType>'.$dato['wsform']['PayCard-Info'][0].'</CreditCardType>';
    	$data .='<CreditCardNumber>'.base64_encode($dato['wsform']['PaymentCardNumber'][0]).'</CreditCardNumber>';
    	$data .='<CreditCardExpiration>'.base64_encode($dato['wsform']['carddate-month'][0].'/'.$dato['wsform']['carddate-year'][0]).'</CreditCardExpiration>';
    	$data .='<CreditCardSecurityCode>'.base64_encode($dato['wsform']['PaymentCardCode'][0]).'</CreditCardSecurityCode>';
    	$data .='<CreditCardOwner>'.$dato['wsform']['PaymentCardName'][0] .'</CreditCardOwner>';
    	$data .='<CreditCardDocumentNumber>'.$dato['wsform']['PaymentCardIdent'][0].'</CreditCardDocumentNumber>';
    	$data .='<Tranference>'.$dato['wsform']['PaymentTrans'][0].'</Tranference>';
    	$data .='<Bank>'.$dato['wsform']['banco'][0].'</Bank>';
    	$data .='<Acount>'.$dato['wsform']['cuenta'][0].'</Acount>';
    	$data .='<TypeAcount>'.$dato['wsform']['tipocuenta'][0].'</TypeAcount>';
    	$data .='<InvoiceTo>'.$dato['wsform']['invoice-Info'][0].'</InvoiceTo>';
    	$data .='<InvoiceDocumentNumber>'.$dato['wsform']['InvoiceRif'][0].'</InvoiceDocumentNumber>';
    	$data .='<InvoiceCustomerName>'.$dato['wsform']['InvoiceName'][0].'</InvoiceCustomerName>';
    	$data .='<InvoicePhone>'.$dato['wsform']['InvoicePhone'][0].'</InvoicePhone>';
    	$data .='<InvoiceLocation>'.$dato['wsform']['InvoiceAddress'][0].'</InvoiceLocation>';
    	$data .='<InvoiceCity>'.$dato['wsform']['InvoiceCity'][0].'</InvoiceCity>';
    	$data .='<InvoiceUrbanization>'.$dato['wsform']['InvoiceUrban'][0].'</InvoiceUrbanization>';
    	$data .='<InvoiceAvenue>'.$dato['wsform']['InvoiceStreet'][0].'</InvoiceAvenue>';
    	$data .='<InvoiceBuilding>'.$dato['wsform']['InvoiceHome'][0].'</InvoiceBuilding>';
    	$data .='<InovicePostalCode>'.$dato['wsform']['InvoiceCode'][0].'</InovicePostalCode>';
     	
 			$totalPax = count($dato['wsform']['paxtype']);
	 	 $data .='<Travelers>';
		for ($i=0;$i<$totalPax;$i++) {
			$data .='<TravelerInfo>';
			$data .='<Treatment>'.$dato['wsform']['paxtreatment'][$i].'</Treatment>';
			$data .='<GivenName>'.$dato['wsform']['paxfirstname'][$i].'</GivenName>';
            $data .='<Surname>'.$dato['wsform']['paxlastname'][$i].'</Surname>';
            $data .='<Address>'.$dato['wsform']['paxfiscal'][$i].'</Address>';
			$data .='<Phone>'.$dato['wsform']['paxphone'][$i].'</Phone>';
			$data .='<PassengerType>'.$dato['wsform']['paxtype'][$i].'</PassengerType>';
			$data .='<DocumentTypeId>'.$dato['wsform']['paxdocumenttype'][$i].'</DocumentTypeId>';
			$data .='<DocumentNumber>'.$dato['wsform']['paxdocumentnumber'][$i].'</DocumentNumber>';
			$data .='<BithDate>'.$dato['wsform']['paxborndate-day'][$i].'/'. $dato['wsform']['paxborndate-month'][$i].'/'.$dato['wsform']['paxborndate-year'][$i].'</BithDate>';
			$data .='<Gender>'.$dato['wsform']['paxgender'][$i].'</Gender>';
			$data .='<FrequentFlyerNumber>'.$dato['wsform']['frecuentpassenger'][$i].'</FrequentFlyerNumber>';
			$data .='<FrequentFlyerAirline>'.$dato['wsform']['airline'][$i].'</FrequentFlyerAirline>';
			$data .='<Email>'.$dato['wsform']['paxemail'][$i].'</Email>';
			$data .='</TravelerInfo>';
		}  
		 $data .='</Travelers>';
 		 $data .='</AdditionalInfo>';
		 $data .= '</Data>';
 
		return $data;
    	
    	
    	
    }
    
    /**
     * La funcion valida que existan plugins para realizar el pago
     * y organiza la información para enviarsela al plugin
     */
    private function _afterSave()
    {
        // Se obtiene el objeto que representa la orden
        $order = new AsomClassOrder($this->_order);

        // Se obtiene la informacion relevante para el pago
        // de la orden
        $data        = $order->getOrder();
        $paymentInfo = $order->getPaymentValues();

        // Se termina de completar el objeto
        $paymentInfo->Reference   = $data->recloc;
        $paymentInfo->AirlineCode = $data->provider;

        // Se indica si el vuelo es nacional o internacional
        $paymentInfo->isNational = $order->isNational();

        // Se llaman todos los plugins que se tengan instalados para
        // el validaciones antes del pago
        JPluginHelper::importPlugin('amadeus');
        $dispatcher = JDispatcher::getInstance();

        $answer = $dispatcher->trigger('onBeforePayment', array($paymentInfo, $this->_delegate->getIDPaymentMethod()));

        $app = JFactory::getApplication();
        if(!count($answer))
            $app->redirect('index.php', JText::_('ORDER_SAVE'));
        elseif(in_array(false, $answer, true))
            $app->redirect('index.php', JText::_('PAYMENT ERROR'), 'error');

        return true;
    }

    public function saveOrder()
    {
        if(!$this->_saveOrder())
			return false;

       /* if(!$this->_afterSave())
            return false; */
        
        return true;
    }

}
