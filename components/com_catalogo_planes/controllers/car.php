<?php



/**
 *
 * This class controls the car flow
 * @author andres.ramirez
 *
 */
class CatalogoPlanesControllerCar extends JController {

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

  function __construct() {
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
  function availability() {
    //Get the app object
    $app =& JFactory::getApplication();
    //get the instance of the model
    $model = $this->getModel("car");
    //Obtain the data request
    $params = $model->getRequestData();
    //Get the lang object
    $lang = explode('-', JFactory::getLanguage()->_lang);
    //get the name of the index for session
    $indexSession = $this->helper->serializeAvailability($params, "car", $lang[0]);
    //if existe the sesion of the availability
    if (isset($_SESSION["car"][$indexSession]) && $_SESSION["ac"][$indexSession]!="") {
      //get the data of the session
      $data = $_SESSION["car"][$indexSession];
    } else {
      //build the object request
      $request = $model->buildAvailabilityRequest($params);
      //send the request to service
      $data = $this->helper->callService($request);
      //Set the data to the session
      $_SESSION["car"][$indexSession] = $data;
    }
    //Build the availability response object

    $data = $model->buidAvailabilityResponse($data);
    //instance the view
    $view = $this->getView("car","html");
    //set the data to the view
    $view->data = $data;
    //set the params to the view
    $view->params = $params;
    //get the instance of the pathway
    $pathway   =& $app->getPathway();
    //add item to the pathway
    $pathway->addItem(JText::_('CP.CAR.AVAILABILITY.BREADCRUMB'), '');
    parent::display();
  }

  function detail() {
    //Get the app object
    $app =& JFactory::getApplication();
    //get the instance of the model
    $model = $this->getModel("car");
    //get the product id
    $productId = JRequest::getVar('productId','');

    if ($productId!="") {
      //Get the instance of the pathway
      $pathway =& $app->getPathway();
      //build the request object
      $request = $model->buildDetailRequest($productId);
      //send the request to service
      $data = $this->helper->callService($request);
      //Build the detail response object
      $data = $model->buidDetailResponse($data);
      //instance the view
      $view = $this->getView("car","html");
      //get the configuration of the qs
      $configCar = $this->helper->getQuicksearchConfig();
      unset($_SESSION["car"]["qs"]);
      //get the params of  the session
      $params = array();
      if (isset($_SESSION["params_car"]) && JRequest::getVar('qs','')==1) {
        //add item to the pathway
        $pathway->addItem(JText::_('CP.CAR.AVAILABILITY.BREADCRUMB'), JRoute::_('index.php?option=com_catalogo_planes&view=car&layout=availability'));
        $params = $_SESSION["params_car"];
        $_SESSION["car"]["qs"] = 1;
      }
      //Set the details to session
      $_SESSION["detailsCar"] = $data;
      //set the data to the view
      $view->data = $data;
      $view->params = $params;
      $view->configQS = $configCar[0];


      $pathway->addItem($data["name"], '');
      parent::display();
    } else {
      $this->errorObject->handleError(0, "");
    }
  }

  function ratedetails() {

    //Get the request params
    $productId = JRequest::getVar('product_id','');
    $checkinDate = JRequest::getVar('checkin_date','');
    $checkinHour = JRequest::getVar('checkin_hour','');
    $checkinHourAP = JRequest::getVar('checkin_hour_ap','');
    $checkoutDate = JRequest::getVar('checkout_date','');
    $checkoutHour = JRequest::getVar('checkout_hour','');
    $checkoutHourAP = JRequest::getVar('checkout_hour_ap','');

    //get the instance of the model
    $model = $this->getModel("car");
    //get the real hour
    $checkinHour = $model->getHour($checkinHour, $checkinHourAP);
    $checkoutHour = $model->getHour($checkoutHour, $checkoutHourAP);
    //get the rate xml request
    $request = $model->buildRateRequest($productId, $checkinDate, $checkoutDate, $checkinHour, $checkoutHour);
    //send the request to service
    $data = $this->helper->callService($request);
    $data = $model->buildRateResponse($data);
    //Set the rate to session
    $_SESSION["rateCar"] = $data;
    //Encode the data to json
    die(Zend_Json_Encoder::encode($data["data"]));

  }

  function guest() {
    GLOBAL $mainframe;
    //Funcion encarga de impedir el cahce de esta pagina en el navegador
    $this->helper->noCache();
    //Get the app object
    $app =& JFactory::getApplication();
    //Obtengo los datos de la session
    $post = JRequest::get();
    //Si existe el post se sube a session
    if (isset($post["product_id"])) {
      $_SESSION["post_rate"] = $post;
    } else {
      $post = $_SESSION["post_rate"];
    }
    //Establesco el nombre de la sesion de detalles con el lenguaje para que tome en cuenta los cambios de lenguaje
    $sessionDetails = base64_encode("detailsCar".JFactory::getLanguage()->_lang.$post["product_id"].$post["checkin"].$post["checkout"]);


    //get the instance of the model
    $model = $this->getModel("car");
    //Si no existe la session de detalles se realiza el llamado al servicio
    if (!isset($_SESSION["detailsCar"]) || !is_array($_SESSION["detailsCar"])) {
      $mainframe->redirect(JRoute::_('index.php?option=com_catalogo_planes&view=car&layout=detail&productId='.$post["product_id"]),JText::_('CP.MSG.ERROR.SESSION.ORDER'),'error');
      $request = $model->buildDetailRequest($post["product_id"]);
      //send the request to service
      $details = $this->helper->callService($request);
      //Build the detail response object
      $details = $model->buidDetailResponse($details);
      $_SESSION["detailsCar"] = $details;
    } else {
      $details = $_SESSION["detailsCar"];
    }

    //Verifico si ya se encuentra establecida una moneda
    $currency=0;
    if (isset($_SESSION["currentCurrency"]) && $_SESSION["currentCurrency"]!="") {
      $currency = $_SESSION["currentCurrency"];
    }
    //Establesco el nombre de la sesion de tarifas con la moneda para que tome en cuenta los cambios de moneda
    $sessionRate = base64_encode("rateCar".$currency.$post["product_id"].$post["checkin"].$post["checkout"]);
    //Si no existe la session de tarifas se realiza el llamado al servicio
    if (!isset($_SESSION["rateCar"]) || !is_array($_SESSION["rateCar"])) {
      $mainframe->redirect(JRoute::_('index.php?option=com_catalogo_planes&view=car&layout=detail&productId='.$post["product_id"]),JText::_('CP.MSG.ERROR.SESSION.ORDER'),'error');
      //get the rate xml request
      $request = $model->buildRateRequest($post["product_id"], $post["checkin"], $post["checkout"]);
      //send the request to service
      $rates = $this->helper->callService($request);
      //Build the detail response object
      $rates = $model->buildRateResponse($rates);
      $_SESSION["rateCar"] = $rates;
    } else {
      $rates = $_SESSION["rateCar"];
    }
    //Obtengo los datos de la reserva ordanizados
    $data = $model->buildGuestResponse($details, $rates, $post);
    //Valido si se acabo el stock
    if (JRequest::getVar("payment","")=="false") {
      $data["haveStock"] = false;
    }
    //Subo los datos de la reserva a sesion
    $_SESSION["car"]["bookingDetail"] = $data;
    //Esta pantalla es diferente a las otras ya que no puede ser una opcion de menu
    //entonces se instancia la vista con el layout default
    $view = $this->getView("car","html");
    //get the instance of the pathway
    $pathway   =& $app->getPathway();
    $extraUrl = "";
    //Valido si viene del quicksearch
    if(isset($_SESSION["car"]["qs"]) && $_SESSION["car"]["qs"]==1){
      $extraUrl = "&qs=1";
      $pathway->addItem(JText::_('CP.CAR.AVAILABILITY.BREADCRUMB'), JRoute::_('index.php?option=com_catalogo_planes&view=car&layout=availability'));
    }

    //add item to the pathway
    $pathway->addItem($data["name"], JRoute::_('index.php?option=com_catalogo_planes&view=car&layout=detail&productId='.$data["id"].$extraUrl));
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
  function pdfdetail() {
    GLOBAL $mainframe;
    //Indica que se acabo la session
    if (!isset($_SESSION["car"]["bookingDetail"])) {
      $mainframe->redirect('index.php',JText::_('CP.GUEST.NO.SESSION.PDF'),'error');

    }
    //Obtengo los datos de sesion
    $data = $_SESSION["car"]["bookingDetail"];
    //Establesco la ruta del template del pdf
    $pathTpl = JPATH_SITE . '/components/com_catalogo_planes/views/car/tmpl/layout/';
    //Obtengo la instancia de la vista
    $view = $this->getView("car", "html");
    //obtengo el template
    $body = $view->fetch ($pathTpl . 'pdf.php');

    //Los indices para que sean relacionados
    $arrayIndices = array(
			"{product_name}",
			"{checkin_date}",
			"{checkout_date}",
			"{duration}",
			"{cartype}",
			"{rate_product}",
			"{subtotal}",
			"{taxes}",
			"{total}",
			"{disclaimer}",
			"{pickup_location}"
			);
			//Organizo las tarifas
			$rateProduct = "";
			foreach ($data["rates"] as $rate) {
			  $rateProduct .= "<tr>";
			  $rateProduct .= "<td>".$rate["quantity"]."</td>";
			  $rateProduct .= "<td>".$data["name"]."</td>";
			  $rateProduct .= "<td>".$view->getFormatPrice($rate["totalPrice"],$data["currency"]["symbol"])."</td>";
			  $rateProduct .= "</tr>";
			}

			//Si hay suplementos los asigno al listado
			if (is_array($data["supplements"])) {
			  foreach ($data["supplements"] as $supplement) {
			    $rateProduct .= "<tr>";
			    $rateProduct .= "<td>".$supplement["quantity"]."</td>";
			    $rateProduct .= "<td>".$supplement["name"]."</td>";
			    $rateProduct .= "<td>".$view->getFormatPrice($supplement["price"],$data["currency"]["symbol"])."</td>";
			    $rateProduct .= "</tr>";
			  }
			}

			$stars = "";
			for($star=0; $star<$data["stars"]; $star++) {
			  $stars .= '<img src="images/star.png" style="margin-left:1px"/>';
			}


			//Los valores para ser remplazados en los indices
			$arrayValues = array(
			$data["name"],
			$data["date"]["checkin"]." ".$data["date"]["checkin_hour"],
			$data["date"]["checkout"]." ".$data["date"]["checkout_hour"],
			$data["duration_text"],
			$data["category"]["name"],
			$rateProduct,
			$view->getFormatPrice($data["subtotal"],$data["currency"]["symbol"]),
			$view->getFormatPrice($data["totalTax"],$data["currency"]["symbol"]),
			$view->getFormatPrice($data["total"],$data["currency"]["symbol"]),
			$data["disclaimer"],
			$data["city"]["name"]
			);



			//Remplazo los campos por los datos en la plantilla del correo
			$pdfBody = str_replace($arrayIndices, $arrayValues, $body);
			ob_end_clean();
			$html2pdf = new HTML2PDF('P', 'A4', 'es');
			$html2pdf->WriteHTML($pdfBody);
			$html2pdf->Output('exemple.pdf');

			//die();
  }
  /**
   *
   * This function send mail with quote data
   */
  function maildetail() {
    //Indica que se acabo la session
    if (!isset($_SESSION["car"]["bookingDetail"])) {
      echo "no_session";
      die();
    }
    //Obtengo los datos de sesion
    $data = $_SESSION["car"]["bookingDetail"];
    //Establesco la ruta del template del mail
    $pathTpl = JPATH_SITE . '/components/com_catalogo_planes/views/car/tmpl/layout/';
    //Obtengo la instancia de la vista
    $view = $this->getView("car", "html");
    //obtengo el template
    $body = $view->fetch ($pathTpl . 'email.php');
    //Los indices para que sean relacionados
    $arrayIndices = array(
			"{product_name}",
			"{checkin_date}",
			"{checkout_date}",
			"{duration}",
			"{cartype}",
			"{rate_product}",
			"{subtotal}",
			"{taxes}",
			"{total}",
			"{disclaimer}",
			"{pickup_location}"
			);
			//Organizo las tarifas
			$rateProduct = "";
			foreach ($data["rates"] as $rate) {
			  $rateProduct .= "<tr>";
			  $rateProduct .= "<td>".$rate["quantity"]."</td>";
			  $rateProduct .= "<td>".$data["name"]."</td>";
			  $rateProduct .= "<td>".$view->getFormatPrice($rate["totalPrice"],$data["currency"]["symbol"])."</td>";
			  $rateProduct .= "</tr>";
			}

			//Si hay suplementos los asigno al listado
			if (is_array($data["supplements"])) {
			  foreach ($data["supplements"] as $supplement) {
			    $rateProduct .= "<tr>";
			    $rateProduct .= "<td>".$supplement["quantity"]."</td>";
			    $rateProduct .= "<td>".$supplement["name"]."</td>";
			    $rateProduct .= "<td>".$view->getFormatPrice($supplement["price"],$data["currency"]["symbol"])."</td>";
			    $rateProduct .= "</tr>";
			  }
			}

			//Los valores para ser remplazados en los indices
			$arrayValues = array(
			$data["name"],
			$data["date"]["checkin"]." ".$data["date"]["checkin_hour"],
			$data["date"]["checkout"]." ".$data["date"]["checkout_hour"],
			$data["duration_text"],
			$data["category"]["name"],
			$rateProduct,
			$view->getFormatPrice($data["subtotal"],$data["currency"]["symbol"]),
			$view->getFormatPrice($data["totalTax"],$data["currency"]["symbol"]),
			$view->getFormatPrice($data["total"],$data["currency"]["symbol"]),
			$data["disclaimer"],
			$data["city"]["name"]
			);

			//Remplazo los campos por los datos en la plantilla del correo
			$mailBody = str_replace($arrayIndices, $arrayValues, $body);
			//Obtengo el correo que digito el usuario
			$userEmail = JRequest::getVar("mail");
			//Llamo a la funcion que realiza el envio del correo
			$send = $this->helper->sendMail($userEmail, JText::_('CP.MAIL.CAR.SUBJECT'), $mailBody, true);
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
  function booking() {
    GLOBAL $mainframe;
    //Obtengo los datos de la session
    $post = JRequest::get();
    //Indica que se acabo la session
    if (!isset($_SESSION["car"]["bookingDetail"])) {
      $mainframe->redirect('index.php',JText::_('CP.GUEST.NO.SESSION'),'error');
    }
    //Obtengo los datos de la session
    $bookDetail = $_SESSION["car"]["bookingDetail"];
    //get the instance of the model
    $model = $this->getModel("car");
    //Envio los datos de la reserva y de los pasajeros al modelo para que los organize
    $data = $model->buildBookingArray($bookDetail, $post);
    //Asigno los datos completos de la reserva a session
    $_SESSION["car"]["bookingDetail"] = $data;
    //Si no es necesario el registro envio los datos directamente a la funcion que se va a encargar de ellos
    if ($post["user_type"]==0) {
      $this->send($data);
    } else {
      //Url donde retornara despues de realizar el registro en el portal
      $redirectUrl = urlencode(base64_encode(JROUTE::_('index.php?option=com_catalogo_planes&view=car&layout=send')));
      //Envia al formulario de registro
      $mainframe->redirect(JROUTE::_('index.php?option=com_user&task=register&return='.$redirectUrl));
    }
  }
  /**
   *
   * This function send the data to the order component
   * @param $data
   */
  function send($data=array()) {
    GLOBAL $mainframe;
    //Instancio la clase controlador de ordenes y envio los datos para ser guardados
    require_once 'components/com_orders/controller.php';
    $objContOrder = new OrdersController();
    //Si no llegan los datos por parametros los obtengo de sesion
    if (count($data)<=0) {
      //Indica que se acabo la session
      if (!isset($_SESSION["car"]["bookingDetail"])) {
        $mainframe->redirect('index.php',JText::_('CP.GUEST.NO.SESSION'),'error');
      }
      $data = $_SESSION["car"]["bookingDetail"];
    }

    //Si ya no existe la session muestro el mensaje y vuelvo al index
    if (!is_array($data) || !isset($data["name"])) {
      $mainframe->redirect(JRoute::_('/index.php'),JText::_('CP.MSG.ERROR.SESSION.ORDER'),'error');
    } elseif (!isset($data["booking"])) {
      $data["confirm"] = false;
      //Si hay stock llamo al servicio que descuenta
      if ($data["haveStock"]) {
        //get the instance of the model
        $model = $this->getModel("car");
        //Obtengo el xml para el llamado del booking
        $xmlRequest = $model->buildBookingXmlRequest($data);
        //send the request to service
        $response = $this->helper->callService($xmlRequest);
        //Asigno la respuesta del descuento de stock al objeto respuesta
        $data["booking_response"] = $response["data"]["response"];
        if ($response["data"]["response"]["id"]==1) {
          $data["confirm"] = true;
          //$objContOrder->confirmOrder($data["booking"]);
        }
      }
      if (!$data["confirm"] && $data["payment"]=="credit") {
        $data["haveStock"] = false;
        $mainframe->redirect(JRoute::_('index.php?option=com_catalogo_planes&view=car&layout=guest&payment=false'),JText::_('CP.MSG.ERROR.STOCK.INAVAIBLE'),'error');
      }
      //Esta session me indica que ya se envio la orden a guardar asi que no puede volver a la pantalla de pasajeros
      $_SESSION["car"]["booking"] = true;
      try{
        //Envio los datos al componente de ordenes para que sea guardado
        $arrayResponse = $objContOrder->saveProduct($data, "cars");
      } catch (Exception $e) {
        $mainframe->redirect('index.php',JText::_('CP.MSG.ERROR.HISTORY.ORDER'),'error');
        die();
      }
      //Si la respuesta es verdadera se realiza el descuento en el inventario si lo hay
      if (isset($arrayResponse) && $arrayResponse["response"]) {
        $haveStock = true;
        //Agrego el id del booking al identificador de la reserva
        $data["booking"] = (string)$arrayResponse["idOrder"];

      } else {//Si no envio a la pantalla de error
        if (!isset($data["booking"])) {
          $mainframe->redirect('index.php',JText::_('CP.MSG.ERROR.HISTORY.ORDER'),'error');
        }
      }
    }
    //Envio el resto de la informacion al componente de ordenes para que este la maneje
    $objContOrder->confirmOrder($data, 'cars');
  }
}