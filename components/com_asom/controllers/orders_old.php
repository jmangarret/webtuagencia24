<?php
/**
 * @file com_asom/admin/controllers/orders.php
 * @defgroup _comp_adm Componente (Administración)
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');

class AsomControllerOrders extends JController
{

    public function display()
    {
        $user = JFactory::getUser();
        if($user->get('id', 0) > 0)
        {
            JRequest::setVar('view', 'orders');

            $doc = JFactory::getDocument();
            $doc->addStyleSheet(JURI::root().'components/com_asom/css/style.css');

            $view = $this->getView('orders', 'html');
            $view->assign('order', $order);

            parent::display();
        }
        else
        {
            JFactory::getApplication()->redirect('index.php', JText::_('AOM_YOU_DONT_HAVE_PERMISSIONS'), 'error');
        }
    }

    public function confirm()
    {
        $params = JComponentHelper::getParams('com_p2p');

        $CustomerSiteID  = (isset($_POST['CustomerSiteID'])  ? $_POST['CustomerSiteID']: false);
        $PaymentResponse = (isset($_POST['PaymentResponse']) ? $_POST['PaymentResponse']: false);

        if ($CustomerSiteID == $params->get('site_code', '') && (!empty($PaymentResponse)))
        {
            /**
             * START Plugins para administrar los estados despues de pagar,
             * generalmente para actualizar el estado de acuerdo a la plataforma
             * de pago
             */
            JPluginHelper::importPlugin('amadeus');
            $dispatcher = JDispatcher::getInstance();

            $answer = $dispatcher->trigger('onAfterPayment', array($_POST['PaymentResponse'], 'P2P'));

            if(in_array(false, $answer, true))
                $app->redirect('index.php', JText::_('AFTER PAYMENT ERROR'), 'error');
            /**
             * END
             */

            $app = JFactory::getApplication();
            $app->redirect('index.php', 'Gracias por comprar con nosotros.');
        }
        else
        {
            $app = JFactory::getApplication();
            $app->redirect('index.php');
        }
    }

    //Presenta el resumen cuando se termina la reserva
    
  public function resume()
    {
        $order = JRequest::getVar('order', '');
        
        //Se valida que exista un hash con la orden generada, para mostrar asi el resultado, y evitar
        //que otros usuarios vean otras ordenes
        $user= JFactory::getUser();
        $orden = $this->checkOrder($order,$user->id);
    //mandar email
       
        //$email = $this->sendMailAir($order);
        
            $doc     = JFactory::getDocument();
            $doc->addStyleSheet(JURI::root().'components/com_asom/css/style.css');

            JRequest::setVar('view', 'products');

            $view = $this->getView('products', 'html');
            $view->assign('order', $order);
          

            parent::display();
         
    }
    
    //Presenta el detalle de la reserva cuando se consultan mis reservas
    
  public function detail()
    {
       $order = JRequest::getVar('order', '');

        //Se valida que exista un hash con la orden generada, para mostrar asi el resultado, y evitar
        //que otros usuarios vean otras ordenes
               $user= JFactory::getUser();
        $orden = $this->checkOrder($order,$user->id);
    
       
        if($order != '' && $orden != NULL){
            $pendent = JRequest::getVar('pendent', '', '', 'bool');
            $doc     = JFactory::getDocument();
            $doc->addStyleSheet(JURI::root().'components/com_asom/css/style.css');

            JRequest::setVar('view', 'order');

            $view = $this->getView('order', 'html');
            $view->assign('order', $order);

            parent::display();
        }
        else
        {
            JFactory::getApplication()->redirect('index.php', JText::_('AOM_YOU_DONT_HAVE_PERMISSIONS'), 'error');
        }
    }
    
    
    public function select()
    {
        $order = JRequest::getVar('cid', '', '', 'int');

        if($order != 0)
        {
            $config = JFactory::getConfig();
            $app    = JFactory::getApplication();
            $code   = sha1($order.$config->getValue('config.secret'));
            $link   = JRoute::_(AsomHelperRoute::getRoute('orders.detail&order='.$order), false);

            $app->setUserState('asom.code', $code);
            $app->redirect($link);
        }
        else
        {
            JFactory::getApplication()->redirect('index.php', JText::_('AOM_YOU_DONT_HAVE_PERMISSIONS'), 'error');
        }
    }
	public function checkOrder($order,$user){
	 		$db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__aom_orders');

        if(is_array($order))
        {
            foreach($order as $key => $value)
            {
                $query->where($db->quoteName($key).' = '.$db->Quote($value));
            }
        }
        else
        {
            $query->where('id = '.$db->Quote((int) $order).' and user_id='.$user);
        }

        $db->setQuery($query);
 
        $order = $db->loadObject();

        return  $order;
        
	}
    public function retry()
    {
        $order = (int) JRequest::getVar('order', '', 'post');

        //Se valida que exista un hash con la orden generada, para mostrar asi el resultado, y evitar
        //que otros usuarios vean otras ordenes
        $code   = JFactory::getApplication()->getUserState('asom.code', '');

        $config = new JConfig();
        $secret = sha1($order.$config->secret);

        if($order != 0 && $secret == $code)
        {
            // Directorio que contiene la libreria que consta de interfaces y clases para
            // integrarse con el AmadeuS Order Manager
            $library = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_asom'.DS.'library';

            // Se registra el directorio, para que dinamicamente cargue las clases necesarias.
            JLoader::registerPrefix('Asom', $library);

            $order = new AsomClassOrder($order);

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

            $paymentMethod = JRequest::getVar('payment', '', 'post');

            $answer = $dispatcher->trigger('onBeforePayment', array($paymentInfo, $paymentMethod));

            $app = JFactory::getApplication();
            if(!count($answer))
                $app->redirect('index.php', JText::_('ORDER_SAVE'));
            elseif(in_array(false, $answer, true))
                $app->redirect('index.php', JText::_('PAYMENT ERROR'), 'error');

            return true;
        }
        else
        {
            JFactory::getApplication()->redirect('index.php', JText::_('ORDER IS NOT VALID, TRY AGAIN'), 'error');
        }
    }
function displayPdfProduct() {
 
         
        //Incluyo la clase
        include_once('components' . DS . 'com_asom' . DS . 'models' . DS . 'ProductsPdf.php');
        //Instancio el modelo de productos pdf
        $model = new AsomModelProductsPdf();
 
        //Establesco la ruta del template del pdf
        $pathTpl = JPATH_SITE . '/components/com_asom/views/products/tmpl/pdf/';
        //Obtengo la instancia de la vista
        $view = $this->getView("products", "html");

        //obtengo el id de la orden
        $orderId = JRequest::getVar('idorder');

        //Lo desencripto
        $orderId = base64_decode($orderId);
        $orderId = substr($orderId, 0, strlen($orderId) - 2);
         
        //Instantiate model
        $modelOrders = &$this->getModel('products');
		$idProductType = JRequest::getVar('idprod');
   		$orderObject = $modelOrders->setOrderID($orderId);
   		$XMLData= $modelOrders->getSource();
        $Order= $modelOrders->getOrder();
     	$XMLAdd= $modelOrders->getAditional();
  
        //Segun el tipo de producto se obtiene la plantilla
        switch ($idProductType) {
            case 1:
                //obtengo el template
                $body = $view->fetch($pathTpl . 'airBody.tpl.php');
                
                //Obtengo el nombre del tipo de producto
                $productTypeName = JText::_('ORDERS.SEARCH.ORDER.TYPE.OPTION.AIR');
                //Llamo la funcion que contiene el template
                $body = $model->getHtmlAir($XMLData,$Order,$XMLAdd, $body, $productTypeName);
                break;
        }
        //Llamo a la funcion encargada de generar el pdf
        $model->setOrderPdf($body, "test");
    }
    
    function sendMailAir($id) {
    	  
         //Incluyo la clase
        include_once('components' . DS . 'com_asom' . DS . 'models' . DS . 'ProductsPdf.php');
        //Instancio el modelo de productos pdf
        $model = new AsomModelProductsPdf();
 
        //Establesco la ruta del template del pdf
        $pathTpl = JPATH_SITE . '/components/com_asom/views/products/tmpl/mail/';
        //Obtengo la instancia de la vista
        $view = $this->getView("products", "html");
    	 
       
        //Incluyo la clase
        include_once('components' . DS . 'com_asom' . DS . 'models' . DS . 'products.php');
        //Instancio el modelo de productos pdf
        $model = new AsomModelProducts();
        $order = $id; 
        //Instantiate model
   		$orderObject = $model->setOrderID($order);
   		$XMLData= $model->getSource();
        $Order= $model->getOrder();
     	$XMLAdd= $model->getAditional();
   
        $dispatcher = & JDispatcher::getInstance();
 
 		$airBody = $view->fetch($pathTpl . 'emailAirBody.tpl.php');
        //Set header mail text
        $confirmationText = JText::_('ORDER.MAIL.AIR.HEADER');


        //Informacion Adicional 
		$xmlstr=$XMLAdd;
		$data = new SimpleXMLElement($xmlstr);
		$passenger=$data->AdditionalInfo->Travelers;
		 
		
		//Informacion del Booking
		 $xml = new SimpleXMLElement($XMLData);
		 $proveedor = $xml->AirReservation->Ticketing->TicketingVendor;
		 $intinerary= $xml->AirReservation->OriginDestinationOptions->OriginDestinationOption->FlightSegment;
		 $states = 	  Array(1 => JText::_('ORDER.STATUS.1'),
					2=>  JText::_('ORDER.STATUS.2'),
					3 => JText::_('ORDER.STATUS.3'),
					4 => JText::_('ORDER.STATUS.4'),
					5 => JText::_('ORDER.STATUS.5'),
					6 => JText::_('ORDER.STATUS.6')

			);       
		foreach($intinerary as $vuelo):
	 
		 $departure=str_replace("T", " ",$vuelo['DepartureDateTime']);
		 $arrival=str_replace("T", " ",$vuelo['ArrivalDateTime']);
		 $airportDep=explode(':',$vuelo->Comment[0]);
		 $cityDep=explode(':',$vuelo->Comment[2]);
		 $iataDep=explode(':',$vuelo->Comment[1]);
		 $airportArr=explode(':',$vuelo->Comment[7]);
		 $cityArr=explode(':',$vuelo->Comment[9]);
		 $iataArr=explode(':',$vuelo->Comment[8]);
		 $infoDep= $airportDep[1]." - ".$cityDep[1]." (".$iataDep[1]." )";
		 $infoArr= $airportArr[1]." - ".$cityArr[1]." (".$iataArr[1]." )";
		 
		 $intinerario="<tr>"; 
		$intinerario .="<td>".$departure."</td>";
		$intinerario.="<td>".$arrival."</td>";
		$intinerario.="<td>".$infoDep."</td>";
		$intinerario.="<td>".$infoArr."</td>";
		$intinerario.="<td>".$vuelo->OperatingAirline[CompanyShortName]."</td>";
		$intinerario.="<td>".$vuelo['FlightNumber']."</td>";
		$intinerario.="<td>".$vuelo['StopQuantity']."</td>";
		$intinerario.="<td>".$vuelo->BookingClassAvails['CabinType']."</td></tr>";
		$arrayItinerary[] = $intinerario;
		endforeach;
    
        for ($i = 0; $i < count($arrayItinerary); $i++) {
            
            $itinerary .= $arrayItinerary[$i];
             
        }
        
        $j=1;
     
        foreach ($passenger->TravelerInfo as $pax):
        $paxinfo.="<tr style='background-color: #213E7A ;'>
				<td style='color: #FFF; padding: 5px; font-weight: bold; width:700px'
					colspan='8'>".JText::_('ORDERS.ORDER.PAX')." (".$j.")</td>
			</tr>";  
		$paxinfo.="<tr>
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>".
					 JText::_('ORDERS.ORDER.PAX.NAME')."</td>
					 <td>".$pax->GivenName."</td>
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.LASTNAME')."</td>
					 <td>".$pax->Surname."</td>
			</tr>
			<tr>		
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.TYPE')."</td>
					 <td>".JText::_('ORDERS.ORDER.PAX.'.$pax->PassengerType)."</td>";
			if($pax->PassengerType=='ADT' || $pax->PassengerType=='YCD' ):	 
			$paxinfo.=	"<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.DOCUMENT.TYPE')."</td>
					 <td>".JText::_('ORDERS.ORDER.PAX.DOC.TYPE.'.$pax->DocumentTypeId)."</td>
			</tr>";
			endif;
			if($pax->PassengerType=='CHD' || $pax->PassengerType=='INF'):
			$paxinfo.=	"<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.DOCUMENT.TYPE')."</td>
					 <td>".JText::_('ORDERS.ORDER.PAX.DOC.TYPEDIF.'.$pax->DocumentTypeId)."</td>
			</tr>";
			endif;
			$paxinfo.="
			<tr>		
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					".JText::_('ORDERS.ORDER.PAX.DOCUMENT.NUMBER')."</td>
					 <td>".$pax->DocumentNumber."</td>
					 ";
			
			
			$paxinfo.="
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					".JText::_('ORDERS.ORDER.PAX.BORN')."</td>
					 <td>".$pax->BithDate."</td>
			</tr>
			<tr>
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.GENDER')."</td>
					 <td>".JText::_('ORDERS.ORDER.PAX.GENDER.'.$pax->Gender)."</td>";
			if($pax->PassengerType=='ADT'):
			$paxinfo.="
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.EMAIL')."</td>
					 <td>".$pax->Email."</td>";
			endif;
			$paxinfo.="
			</tr>
		 	<tr>
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					".JText::_('ORDERS.ORDER.PAX.VFREQ.NUMBER')."</td>
					 <td>".$pax->FrequentFlyerNumber."</td>
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.VFREQ.AIRLINE')."</td>
					 <td>".$pax->FrequentFlyerAirline."</td>
			</tr>";
					 $arrayPaxes[] =$paxinfo;
					 
					 $j++;
        endforeach; 
  		
        $passengers = $arrayPaxes[count($arrayPaxes)-1];
        
        
     	$componentParams = JComponentHelper::getParams('com_asom');
       	//Traer los articulos
    
    		if($Order->product_name=='Vuelo Nacional'){
       		$article = $componentParams->get('article_nal');
    		}
    	if($Order->product_name=='Vuelo Internacional'){
       		$article = $componentParams->get('article_inter');
    		} 
       
         $articulo = $this->Article($article);
 
        $arrayIndexBody = array(
	        "{order.number}",
	        "{order.state}",
	        "{order.record}",
        	"{order.price}",
	        "{order.taxes}",
	        "{order.fees}",
	        "{order.total}",
	        "{order.intinerary}",
	        "{order.passengers}",
        	"{order.message}",
       		"{order.nota}",
        	"{order.validadora}"
        );
        
 		$mensa=JURI::base()."index.php?option=com_asom&task=orders.detail&order=$Order->id";
        $arrayValuesBody = array(
        	$Order->id,
        	$states[$Order->status],
        	$Order->recloc,
        	number_format($Order->fare,2, ',', '.'),
        	number_format($Order->taxes,2, ',', '.'),
        	number_format($Order->fare_ta+$Order->taxes_ta ,2, ',', '.'),
        	number_format($Order->total,2, ',', '.'),
        	$itinerary,
        	$passengers,
        	JText::sprintf('ORDERS.ORDER.MESSAGE',  $mensa),
        	$articulo [introtext],
        	$proveedor
        );

         $airBody = str_replace($arrayIndexBody, $arrayValuesBody, $airBody);
        
 
        //Get main body mail template
        $body = $view->fetch($pathTpl . 'default.php');
        $html = str_replace("{body}", $airBody, $body);
  		$user      = JFactory::getUser();
        $userEmail = $user->email;
       if($userEmail!=''){
       	$userEmail = $user->email;
       }else{
       	       	$userEmail = $Order->email;
       }
        $config = & JFactory::getConfig();
        $send = $this->sendMail($userEmail, JText::_('ORDER.MAIL.SUBJECT.AIR'), $html, true);

        if ($send !=true) {
            return $send;
        } else {
            return true;
        }
    	
    	
    }
    //Traer el articulo a mostrar
	
    public function Article($id){
    $db  =& JFactory::getDBO();
		$sql = "SELECT introtext
                FROM #__content
                WHERE id = ".intval($id);

		$db->setQuery($sql);
		$fullArticle = $db->loadAssoc();
		return $fullArticle;
    }
    
    //Funcion que me permite el envio de correo electronico
    function sendMail($recipient, $subject, $body, $ishtml = true, $image = NULL) {
        $mailer = & JFactory::getMailer();
        $config = & JFactory::getConfig();
        
        $sender = array(
            $config->getValue('config.mailfrom'),
            $config->getValue('config.fromname'));

        $mailer->setSender($sender);
        $mailer->addRecipient($recipient);
        $mailer->setSubject($subject);
        $mailer->isHTML($ishtml);
        $mailer->setBody($body);
        // Optionally add embedded image
        if ($image) {
            $mailer->AddEmbeddedImage($image['url'], $image['id'], $image['name'], $image['encode'], $image['mime']);
        }
        $send = & $mailer->Send();
       
        if ($send !== true) {
            return $send->message;
        } else {
            return true;
        }
    }
    
}
