<?php



/**
 *
 * This class controls the transfer flow
 * @author andres.ramirez
 *
 */
class CatalogoPlanesControllerTransfer extends JController {

  /**
   *
   * The helper object
   * @var object
   */
  private $helper;

  /**
   *
   * The error object
   * @var object
   */
  private $errorObject;

  function __construct(){
    //instance the helper
    $this->helper = new CatalogoPlanesHelper();
    //Get the instance of the error object
    $this->errorObject = HelperErrors::getInstance();
    parent::__construct();
  }


  /**
   *
   * This function send the request to service
   */
  function availability(){
    //Get the app object
    $app =& JFactory::getApplication();
    //get the instance of the model
    $model = $this->getModel("transfer");
    //Obtain the data request
    $params = $model->getRequestData();
    //Get the lang object
    $lang = explode('-', JFactory::getLanguage()->_lang);
    //get the name of the index for session
    $indexSession = $this->helper->serializeAvailability($params, "transfer", $lang[0]);
    //if existe the sesion of the availability
    if(isset($_SESSION["transfer"][$indexSession]) && $_SESSION["ac"][$indexSession]!=""){
      //get the data of the session
      $data = $_SESSION["transfer"][$indexSession];
    }else{
      //build the object request
      $request = $model->buildAvailabilityRequest($params);
      //send the request to service
      $data = $this->helper->callService($request);
      //Set the data to the session
      $_SESSION["transfer"][$indexSession] = $data;
    }
    //Build the availability response object
    $data = $model->buidAvailabilityResponse($data);
    //instance the view
    $view = $this->getView("transfer","html");
    //set the data to the view
    $view->data = $data;
    //set the params to the view
    $view->params = $params;
    //get the instance of the pathway
    $pathway   =& $app->getPathway();
    //add item to the pathway
    $pathway->addItem(JText::_('CP.TRANSFER.AVAILABILITY.BREADCRUMB'), '');
    parent::display();
  }

  function detail(){
    //Get the app object
    $app =& JFactory::getApplication();
    //get the instance of the model
    $model = $this->getModel("transfer");
    //get the product id
    $productId = JRequest::getVar('productId','');

    if($productId!=""){
      //Get the instance of the pathway
      $pathway =& $app->getPathway();
      //build the request object
      $request = $model->buildDetailRequest($productId);
      //send the request to service
      $data = $this->helper->callService($request);

      //Build the detail response object
      $data = $model->buidDetailResponse($data);
      //instance the view
      $view = $this->getView("transfer","html");
      //get the configuration of the qs
      $configTransfer = $this->helper->getQuicksearchConfig();
      unset($_SESSION["transfer"]["qs"]);
      //get the params of  the session
      $params = array();
      if(isset($_SESSION["params_transfer"]) && JRequest::getVar('qs','')==1){
        //add item to the pathway
        $pathway->addItem(JText::_('CP.TRANSFER.AVAILABILITY.BREADCRUMB'), JRoute::_('index.php?option=com_catalogo_planes&view=transfer&layout=availability'));
        $params = $_SESSION["params_transfer"];
        $_SESSION["transfer"]["qs"] = 1;
      }
      if(!isset($params["type"]) || $params["type"]==""){
        if(isset($_SESSION["transfer_type"])){
          $params["type"] = $_SESSION["transfer_type"];
        }else{
          $params["type"] = 1;
        }

      }
      //Set the transfer type
      $data["transfer_type"] = $params["type"];
      //Set the details to session
      $_SESSION["detailsTransfer"] = $data;
      //set the data to the view
      $view->data = $data;
      $view->params = $params;
      $view->configQS = $configTransfer[0];


      $pathway->addItem($data["name"], '');
      parent::display();
    }else{
      $this->errorObject->handleError(0, "");
    }
  }

  function ratedetails(){

    //Get the request params
    $productId = JRequest::getVar('product_id','');
    $checkinDate = JRequest::getVar('checkin_date','');
    $checkoutDate = JRequest::getVar('checkout_date','');
    $type = JRequest::getVar('type','');
    $passengers = JRequest::getVar('passengers','');
    $isPassengers = JRequest::getVar('ispassenger',false);
    //get the instance of the model
    $model = $this->getModel("transfer");

    //get the name of the index for session
    $indexSession = md5($productId.$checkinDate.$checkoutDate.$type);

    //Valido si no existe la session del producto
    if(!isset($_SESSION[$indexSession]) || !$isPassengers){
      //get the rate xml request
      $request = $model->buildRateRequest($productId, $checkinDate, $checkoutDate, $type);
      //send the request to service
      $data = $this->helper->callService($request);
      $_SESSION[$indexSession] = $data;
    }else{
      $data = $_SESSION[$indexSession];
    }

    //Obtengo los detalles de la reserva
    $details = $_SESSION["detailsTransfer"];
    //Organizo las tarifas
    $data = $model->buildRateResponse($data, $details, $passengers, $isPassengers);
    //Set the rate to session
    $_SESSION["rateTransfer"] = $data;
    //Encode the data to json
    die(Zend_Json_Encoder::encode($data["data"]));

  }

  function guest(){
    GLOBAL $mainframe;
    //Funcion encarga de impedir el cahce de esta pagina en el navegador
    $this->helper->noCache();
    //Get the app object
    $app =& JFactory::getApplication();
    //Obtengo los datos de la session
    $post = JRequest::get();
    //Si existe el post se sube a session
    if(isset($post["product_id"])){
      $_SESSION["post_rate"] = $post;
    }else{
      $post = $_SESSION["post_rate"];
    }
    //Establesco el nombre de la sesion de detalles con el lenguaje para que tome en cuenta los cambios de lenguaje
    $sessionDetails = base64_encode("detailsTransfer".JFactory::getLanguage()->_lang.$post["product_id"].$post["checkin"].$post["checkout"]);


    //get the instance of the model
    $model = $this->getModel("transfer");
    //Si no existe la session de detalles se realiza la redureccion al detalle del producto
    if(!isset($_SESSION["detailsTransfer"]) || !is_array($_SESSION["detailsTransfer"])){
      $mainframe->redirect(JRoute::_('index.php?option=com_catalogo_planes&view=transfer&layout=detail&productId='.$post["product_id"]),JText::_('CP.MSG.ERROR.SESSION.ORDER'),'error');

      $request = $model->buildDetailRequest($post["product_id"]);
      //send the request to service
      $details = $this->helper->callService($request);
      //Build the detail response object
      $details = $model->buidDetailResponse($details);
      $_SESSION["detailsTransfer"] = $details;
    }else{
      $details = $_SESSION["detailsTransfer"];
    }

    //Verifico si ya se encuentra establecida una moneda
    $currency=0;
    if(isset($_SESSION["currentCurrency"]) && $_SESSION["currentCurrency"]!=""){
      $currency = $_SESSION["currentCurrency"];
    }
    //Establesco el nombre de la sesion de tarifas con la moneda para que tome en cuenta los cambios de moneda
    $sessionRate = base64_encode("rateTransfer".$currency.$post["product_id"].$post["checkin"].$post["checkout"]);
    //Si no existe la session de tarifas se realiza el llamado al servicio
    if(!isset($_SESSION["rateTransfer"]) || !is_array($_SESSION["rateTransfer"])){
      $mainframe->redirect(JRoute::_('index.php?option=com_catalogo_planes&view=transfer&layout=detail&productId='.$post["product_id"]),JText::_('CP.MSG.ERROR.SESSION.ORDER'),'error');
      //get the rate xml request
      $request = $model->buildRateRequest($post["product_id"], $post["checkin"], $post["checkout"]);
      //send the request to service
      $rates = $this->helper->callService($request);
      //Build the detail response object
      $rates = $model->buildRateResponse($rates);
      $_SESSION["rateTransfer"] = $rates;
    }else{
      $rates = $_SESSION["rateTransfer"];
    }
    //Obtengo los datos de la reserva ordanizados
    $data = $model->buildGuestResponse($details, $rates, $post);
    //Valido si se acabo el stock
    if(JRequest::getVar("payment","")=="false"){
      $data["haveStock"] = false;
    }
    //Subo los datos de la reserva a sesion
    $_SESSION["transfer"]["bookingDetail"] = $data;
    //Esta pantalla es diferente a las otras ya que no puede ser una opcion de menu
    //entonces se instancia la vista con el layout default
    $view = $this->getView("transfer","html");
    //get the instance of the pathway
    $pathway   =& $app->getPathway();
    $extraUrl = "";
    //Valido si viene del quicksearch
    if(isset($_SESSION["transfer"]["qs"]) && $_SESSION["transfer"]["qs"]==1){
      $extraUrl = "&qs=1";
      $pathway->addItem(JText::_('CP.TRANSFER.AVAILABILITY.BREADCRUMB'), JRoute::_('index.php?option=com_catalogo_planes&view=transfer&layout=availability'));
    }
    //add item to the pathway
    $pathway->addItem($data["name"], JRoute::_('index.php?option=com_catalogo_planes&view=transfer&layout=detail&productId='.$data["id"].$extraUrl));
    $pathway->addItem(JText::_('CP.GUEST.BREADCRUMB'), '');
    //asigno los datos a la vista
    $view->data = $data;
    $view->params = $post;
    $view->display("guest");
  }

  /**
   *
   * This function show a pdf with the quote data
   */
  function pdfdetail(){
    GLOBAL $mainframe;
    //Indica que se acabo la session
    if(!isset($_SESSION["transfer"]["bookingDetail"])){
      $mainframe->redirect('index.php',JText::_('CP.GUEST.NO.SESSION.PDF'),'error');

    }
    //Obtengo los datos de sesion
    $data = $_SESSION["transfer"]["bookingDetail"];
    //Establesco la ruta del template del pdf
    $pathTpl = JPATH_SITE . '/components/com_catalogo_planes/views/transfer/tmpl/layout/';
    //Obtengo la instancia de la vista
    $view = $this->getView("transfer", "html");
    //obtengo el template
    $body = $view->fetch($pathTpl . 'pdf.php');

    //Los indices para que sean relacionados
    $arrayIndices = array(
			"{product_name}",
			"{checkin_date}",
			"{checkout_date}",
			"{destiny}",
			"{adults}",
			"{service}",
			"{subtotal}",
			"{taxes}",
			"{total}",
			"{disclaimer}",
			"{rate}",
			"{suplements}",
			"{city}",
			"{region}"
			);
			//Organizo las tarifas
			$rateProduct = "";
			foreach($data["rates"] as $rate){
			  $rateProduct .= "<tr>";
			  $rateProduct .= "<td style='border-bottom:#F3F3F3 solid 2px;'>".$rate["quantity"]."</td>";
			  $rateProduct .= "<td style='border-bottom:#F3F3F3 solid 2px;'>".$rate["pamam1"]["name"]."</td>";
			  $rateProduct .= "<td style='border-bottom:#F3F3F3 solid 2px;'>".$view->getFormatPrice($rate["totalPrice"],$data["currency"]["symbol"])."</td>";
			  $rateProduct .= "</tr>";
			}

			if(count($data["supplements"])>0){
			  $rateSupplement = '<tr style="background-color: #F3F3F3; padding-top: 5px; padding-bottom: 5px; color: #2255B5; font-weight: bold;">
								<td align="left" valign="top" style="padding-top: 8px; padding-bottom: 8px;" >'.JText::_("CP.QUOTE.QUANTITY").'</td>
								<td align="left" valign="top" style="padding-top: 8px; padding-bottom: 8px;">'. JText::_("CP.TRANSFER.SELECT.SUPPLEMENT").'</td>
								<td align="left" valign="top" style="padding-top: 8px; padding-bottom: 8px;">'. JText::_("CP.QUOTE.RATE").'</td>
							</tr>';


			  //Si hay suplementos los asigno al listado
			  if(is_array($data["supplements"])){
			    foreach($data["supplements"] as $supplement){
			      $rateSupplement .= "<tr>";
			      $rateSupplement .= "<td style='border-bottom:#F3F3F3 solid 2px;'>".$supplement["quantity"]."</td>";
			      $rateSupplement .= "<td style='border-bottom:#F3F3F3 solid 2px;'>".$supplement["name"]."</td>";
			      $rateSupplement .= "<td style='border-bottom:#F3F3F3 solid 2px;'>".$view->getFormatPrice($supplement["price"],$data["currency"]["symbol"])."</td>";
			      $rateSupplement .= "</tr>";
			    }
			  }
			}



			//Los valores para ser remplazados en los indices
			$arrayValues = array(
			$data["name"],
			$data["date"]["checkin"]." ".$data["date"]["checkin_hour"],
			($data["date"]["checkout"]!="")?$data["date"]["checkout"]." ".$data["date"]["checkout_hour"]:"N/A",
			$data["rates"][0]["pamam3"]["name"],
			$data["guest"]["adults"],
			$data["rates"][0]["pamam1"]["name"],
			$view->getFormatPrice($data["subtotal"],$data["currency"]["symbol"]),
			$view->getFormatPrice($data["totalTax"],$data["currency"]["symbol"]),
			$view->getFormatPrice($data["total"],$data["currency"]["symbol"]),
			$data["disclaimer"],
			$view->getFormatPrice($data["rates"][0]["totalPrice"], $data["currency"]["symbol"]),
			$rateSupplement,
			$data["city"]["name"],
			$data["region"]["name"]
			);



			//Remplazo los campos por los datos en la plantilla del correo
			$pdfBody = str_replace($arrayIndices, $arrayValues, $body);

			ob_end_clean();
			$html2pdf = new HTML2PDF('P', 'A4', 'es');
			$html2pdf->WriteHTML($pdfBody);
			$html2pdf->Output('exemple.pdf');

			die();
  }
  /**
   *
   * This function send mail with quote data
   */
  function maildetail(){
    //Indica que se acabo la session
    if(!isset($_SESSION["transfer"]["bookingDetail"])){
      echo "no_session";
      die();
    }
    //Obtengo los datos de sesion
    $data = $_SESSION["transfer"]["bookingDetail"];
    //Establesco la ruta del template del mail
    $pathTpl = JPATH_SITE . '/components/com_catalogo_planes/views/transfer/tmpl/layout/';
    //Obtengo la instancia de la vista
    $view = $this->getView("transfer", "html");
    //obtengo el template
    $body = $view->fetch($pathTpl . 'email.php');
    //Los indices para que sean relacionados
    $arrayIndices = array(
			"{product_name}",
			"{checkin_date}",
			"{checkout_date}",
			"{destiny}",
			"{adults}",
			"{service}",
			"{subtotal}",
			"{taxes}",
			"{total}",
			"{disclaimer}",
			"{rate}",
			"{suplements}",
			"{city}",
			"{region}"
			);


			if(count($data["supplements"])>0){
			  $rateSupplement = '<tr style="background-color: #F3F3F3; padding-top: 5px; padding-bottom: 5px; color: #2255B5; font-weight: bold;">
								<td align="left" valign="top" style="padding-top: 8px; padding-bottom: 8px;" >'.JText::_("CP.QUOTE.QUANTITY").'</td>
								<td align="left" valign="top" style="padding-top: 8px; padding-bottom: 8px;">'. JText::_("CP.TRANSFER.SELECT.SUPPLEMENT").'</td>
								<td align="left" valign="top" style="padding-top: 8px; padding-bottom: 8px;">'. JText::_("CP.QUOTE.RATE").'</td>
							</tr>';


			  //Si hay suplementos los asigno al listado
			  if(is_array($data["supplements"])){
			    foreach($data["supplements"] as $supplement){
			      $rateSupplement .= "<tr>";
			      $rateSupplement .= "<td style='border-bottom:#F3F3F3 solid 2px;'>".$supplement["quantity"]."</td>";
			      $rateSupplement .= "<td style='border-bottom:#F3F3F3 solid 2px;'>".$supplement["name"]."</td>";
			      $rateSupplement .= "<td style='border-bottom:#F3F3F3 solid 2px;'>".$view->getFormatPrice($supplement["price"],$data["currency"]["symbol"])."</td>";
			      $rateSupplement .= "</tr>";
			    }
			  }
			}

			//Los valores para ser remplazados en los indices
			$arrayValues = array(
			$data["name"],
			$data["date"]["checkin"]." ".$data["date"]["checkin_hour"],
			($data["date"]["checkout"]!="")?$data["date"]["checkout"]." ".$data["date"]["checkout_hour"]:"N/A",
			$data["rates"][0]["pamam3"]["name"],
			$data["guest"]["adults"],
			$data["rates"][0]["pamam1"]["name"],
			$view->getFormatPrice($data["subtotal"],$data["currency"]["symbol"]),
			$view->getFormatPrice($data["totalTax"],$data["currency"]["symbol"]),
			$view->getFormatPrice($data["total"],$data["currency"]["symbol"]),
			$data["disclaimer"],
			$view->getFormatPrice($data["rates"][0]["totalPrice"], $data["currency"]["symbol"]),
			$rateSupplement,
			$data["city"]["name"],
			$data["region"]["name"]
			);

			//Remplazo los campos por los datos en la plantilla del correo
			$mailBody = str_replace($arrayIndices, $arrayValues, $body);

			//Obtengo el correo que digito el usuario
			$userEmail = JRequest::getVar("mail");
			//Llamo a la funcion que realiza el envio del correo
			$send = $this->helper->sendMail($userEmail, JText::_('CP.MAIL.TRANSFER.SUBJECT'), $mailBody, true);
			//Valido si se envio correctamente el correo
			if ($send !== true) {
			  echo $send;
			} else {
			  echo "ok";
			}
			die();
  }

  /**
   *
   * This function receive the request and organize the booking array
   */
  function booking(){
    GLOBAL $mainframe;
    //Obtengo los datos de la session
    $post = JRequest::get();
    //Indica que se acabo la session
    if(!isset($_SESSION["transfer"]["bookingDetail"])){
      $mainframe->redirect('index.php',JText::_('CP.GUEST.NO.SESSION'),'error');
    }
    //Obtengo los datos de la session
    $bookDetail = $_SESSION["transfer"]["bookingDetail"];
    //get the instance of the model
    $model = $this->getModel("transfer");
    //Envio los datos de la reserva y de los pasajeros al modelo para que los organize
    $data = $model->buildBookingArray($bookDetail, $post);
    //Asigno los datos completos de la reserva a session
    $_SESSION["transfer"]["bookingDetail"] = $data;
    //Si no es necesario el registro envio los datos directamente a la funcion que se va a encargar de ellos
    if($post["user_type"]==0){
      $this->send($data);
    }else{
      //Url donde retornara despues de realizar el registro en el portal
      $redirectUrl = urlencode(base64_encode(JROUTE::_('index.php?option=com_catalogo_planes&view=transfer&layout=send')));
      //Envia al formulario de registro
      $mainframe->redirect(JROUTE::_('index.php?option=com_user&task=register&return='.$redirectUrl));
    }
  }
  /**
   *
   * This function send the data to the order component
   * @param $data
   */
  function send($data=array()){
    GLOBAL $mainframe;
    //Instancio la clase controlador de ordenes y envio los datos para ser guardados
    require_once 'components/com_orders/controller.php';
    $objContOrder = new OrdersController();
    //Si no llegan los datos por parametros los obtengo de sesion
    if(count($data)<=0){
      //Indica que se acabo la session
      if(!isset($_SESSION["transfer"]["bookingDetail"])){
        $mainframe->redirect('index.php',JText::_('CP.GUEST.NO.SESSION'),'error');
      }
      $data = $_SESSION["transfer"]["bookingDetail"];
    }

    //Si ya no existe la session muestro el mensaje y vuelvo al index
    if(!is_array($data) || !isset($data["name"])){
      $mainframe->redirect(JRoute::_('/index.php'),JText::_('CP.MSG.ERROR.SESSION.ORDER'),'error');
    }elseif(!isset($data["booking"])){
      $data["confirm"] = false;
      //Si hay stock llamo al servicio que descuenta
      if($data["haveStock"]){
        //get the instance of the model
        $model = $this->getModel("transfer");
        //Obtengo el xml para el llamado del booking
        $xmlRequest = $model->buildBookingXmlRequest($data);
        //send the request to service
        $response = $this->helper->callService($xmlRequest);
        //Asigno la respuesta del descuento de stock al objeto respuesta
        $data["booking_response"] = $response["data"]["response"];
        if($response["data"]["response"]["id"]==1){
          $data["confirm"] = true;
          //$objContOrder->confirmOrder($data["booking"]);
        }
      }
      if(!$data["confirm"] && $data["payment"]=="credit"){
        $data["haveStock"] = false;
        $mainframe->redirect(JRoute::_('index.php?option=com_catalogo_planes&view=transfer&layout=guest&payment=false'),JText::_('CP.MSG.ERROR.STOCK.INAVAIBLE'),'error');
      }
      //Esta session me indica que ya se envio la orden a guardar asi que no puede volver a la pantalla de pasajeros
      $_SESSION["transfer"]["booking"] = true;

      try{
        //Envio los datos al componente de ordenes para que sea guardado
        $arrayResponse = $objContOrder->saveProduct($data, "transfers");
      }catch(Exception $e){
        $mainframe->redirect('index.php',JText::_('CP.MSG.ERROR.HISTORY.ORDER'),'error');
        die();
      }

      //Si la respuesta es verdadera se realiza el descuento en el inventario si lo hay
      if (isset($arrayResponse) && $arrayResponse["response"]) {
        $haveStock = true;
        //Agrego el id del booking al identificador de la reserva
        $data["booking"] = (string)$arrayResponse["idOrder"];

      }else{//Si no envio a la pantalla de error
        if(!isset($data["booking"])){
          $mainframe->redirect('index.php',JText::_('CP.MSG.ERROR.HISTORY.ORDER'),'error');
        }
      }
    }
    //Envio el resto de la informacion al componente de ordenes para que este la maneje
    $objContOrder->confirmOrder($data, 'transfers');
  }
}