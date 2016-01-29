<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * catalogo_planes Component car Model
 *
 * @author      andres.ramirez
 * @package		Joomla
 * @subpackage	catalogo_planes
 * @since 1.5
 */
class CatalogoPlanesModelCar extends JModel {

  private $productType = "car";


  var $helper;
  /**
   * Constructor
   */
  function __construct() {
    $this->helper = new CatalogoPlanesHelper();
    parent::__construct();
  }
  /**
   *
   * This function get the data of the request or the session
   */
  function getRequestData(){
    GLOBAL $mainframe;
    //Se obtienen los datos del post
    $params = JRequest::getVar('car',array());
    if(count($params)<=0){
      //si no hay post se toman los datos del get
      if(JRequest::getVar('is_menu','')==1){
        if(JRequest::getVar('city','')!="")
        $params["city_id"] = JRequest::getVar('city','');
        if(JRequest::getVar('related','')!="")
        $params["related"] = JRequest::getVar('related','');
        if(JRequest::getVar('category','')!="")
        $params["category"] = JRequest::getVar('category','');
        if(JRequest::getVar('supplier','')!="")
        $params["supplier"] = JRequest::getVar('supplier','');
        $params["date_start"] = JRequest::getVar('date_start',date('d/m/Y'));
        $nDays = JRequest::getVar('date_finish',0);
        if(!is_numeric($nDays)){
          $nDays = 0;
        }
        $params["date_finish"] = $this->helper->addDays($params["date_start"], $nDays);
      }else{
        //Si no estan por get se toman por sesion
        if(isset($_SESSION["params_car"])){
          $params = $_SESSION["params_car"];
        }else{
          //Si no existen en session se redirecciona a la home
          $mainframe->redirect("index.php");
        }
      }
    }
    $_SESSION["params_car"] = $params;
    return $params;
  }
  /**
   *
   * This function buld the object of availability
   * @param $params
   */
  function buildAvailabilityRequest($params){
    $data = new SimpleXMLElement('<Data></Data>');
    $data->addChild("type", $this->productType);
    $data->addChild("method", "find");
    $data->addChild("search");
    $data->search->addchild("region", $params["region_id"]);
    $data->search->addchild("city", $params["city_id"]);
    $data->search->addchild("checkin_date", $this->helper->setDateFormatToService($params["date_start"]));
    $data->search->addchild("checkout_date", $this->helper->setDateFormatToService($params["date_finish"]));
    $data->search->addchild("category", $params["category"]);
    $data->search->addchild("supplier", $params["supplier"]);
    if(isset($params["related"]) && $params["related"]!=""){
      $data->search->addchild("related", $params["related"]);
    }
    //Set the currency id
    $currency=0;
    if(isset($_SESSION["currentCurrency"]) && $_SESSION["currentCurrency"]!=""){
      $currency = $_SESSION["currentCurrency"];
    }
    $data->search->addchild("currency", $currency);
    return $data;
  }
  /**
   *
   * This function organize the availability response
   * @param $data
   */
  function buidAvailabilityResponse($data){
    $temp = array();
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(count($data["products"]["product"])>0){
      if(!isset($data["products"]["product"][0])){
        $temp = $data["products"]["product"];
        unset($data["products"]["product"]);
        $data["products"]["product"][0] = $temp;
      }
    }
    return $data;
  }

  /**
   *
   * This function set the deatils request
   * @param $productId
   */
  function buildDetailRequest($productId){
    $data = new SimpleXMLElement('<Data></Data>');
    $data->addChild("type", $this->productType);
    $data->addChild("method", "details");
    $data->addChild("product_id", $productId);
    return $data;
  }

  /**
   *
   * This function build the details response
   * @param $data
   */
  function buidDetailResponse($data){
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    $tempAmmenity = array();
    if(is_array($data["data"]["amenities"]) && count($data["data"]["amenities"])>0){
      if(count($data["data"]["amenities"]["amenity"])>0){
        if(!isset($data["data"]["amenities"]["amenity"][0])){
          $tempAmmenity = $data["data"]["amenities"]["amenity"];
          unset($data["data"]["amenities"]["amenity"]);
          $data["data"]["amenities"]["amenity"][0] = $tempAmmenity;
        }
      }
    }
    $tempImage = array();
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(is_array($data["data"]["images"]) && count($data["data"]["images"])>0){
      if(count($data["data"]["images"]["image"])>0){
        if(!isset($data["data"]["images"]["image"][0])){
          $tempImage = $data["data"]["images"]["image"];
          unset($data["data"]["images"]["image"]);
          $data["data"]["images"]["image"][0] = $tempImage;
        }
      }
    }
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(is_array($data["data"]["delivery_cities"]) && count($data["data"]["delivery_cities"])>0){
      if(count($data["data"]["delivery_cities"]["city"])>0){
        if(!isset($data["data"]["delivery_cities"]["city"][0])){
          $tempImage = $data["data"]["delivery_cities"]["city"];
          unset($data["data"]["delivery_cities"]["city"]);
          $data["data"]["delivery_cities"]["city"][0] = $tempImage;
        }
      }
    }
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(is_array($data["data"]["comments"]) && count($data["data"]["comments"])>0){
      if(count($data["data"]["comments"]["comment"])>0){
        if(!isset($data["data"]["comments"]["comment"][0])){
          $tempImage = $data["data"]["comments"]["comment"];
          unset($data["data"]["comments"]["comment"]);
          $data["data"]["comments"]["comment"][0] = $tempImage;
        }
      }
    }
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(is_array($data["data"]["seasons"]) && count($data["data"]["seasons"])>0){
      if(count($data["data"]["seasons"]["season"])>0){
        if(!isset($data["data"]["seasons"]["season"][0])){
          $tempImage = $data["data"]["seasons"]["season"];
          unset($data["data"]["seasons"]["season"]);
          $data["data"]["seasons"]["season"][0] = $tempImage;
        }
      }
    }
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(is_array($data["data"]["details"]) && count($data["data"]["details"])>0){
      if(count($data["data"]["details"]["tag"])>0){
        if(!isset($data["data"]["details"]["tag"][0])){
          $tempImage = $data["data"]["details"]["tag"];
          unset($data["data"]["details"]["tag"]);
          $data["data"]["details"]["tag"][0] = $tempImage;
        }
      }
    }
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(is_array($data["data"]["params1"]) && count($data["data"]["params1"])>0){
      if(count($data["data"]["params1"]["param"])>0){
        if(!isset($data["data"]["params1"]["param"][0])){
          $tempImage = $data["data"]["params1"]["param"];
          unset($data["data"]["params1"]["param"]);
          $data["data"]["params1"]["param"][0] = $tempImage;
        }
      }
    }
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(is_array($data["data"]["params2"]) && count($data["data"]["params2"])>0){
      if(count($data["data"]["params2"]["param"])>0){
        if(!isset($data["data"]["params2"]["param"][0])){
          $tempImage = $data["data"]["params2"]["param"];
          unset($data["data"]["params2"]["param"]);
          $data["data"]["params2"]["param"][0] = $tempImage;
        }
      }
    }
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(is_array($data["data"]["params3"]) && count($data["data"]["params3"])>0){
      if(count($data["data"]["params3"]["param"])>0){
        if(!isset($data["data"]["params3"]["param"][0])){
          $tempImage = $data["data"]["params3"]["param"];
          unset($data["data"]["params3"]["param"]);
          $data["data"]["params3"]["param"][0] = $tempImage;
        }
      }
    }
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(is_array($data["data"]["supplements"]) && count($data["data"]["supplements"])>0){
      if(count($data["data"]["supplements"]["supplement"])>0){
        if(!isset($data["data"]["supplements"]["supplement"][0])){
          $tempImage = $data["data"]["supplements"]["supplement"];
          unset($data["data"]["supplements"]["supplement"]);
          $data["data"]["supplements"]["supplement"][0] = $tempImage;
        }
      }
    }
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    for($i=0; $i<count($data["data"]["seasons"]["season"]) ;$i++){
      if(is_array($data["data"]["seasons"]["season"][$i]["dates"]) && count($data["data"]["seasons"]["season"][$i]["dates"]["date"])>0){
        if(count($data["data"]["seasons"]["season"][$i]["dates"]["date"])>0){
          if(!isset($data["data"]["seasons"]["season"][$i]["dates"]["date"][0])){
            $tempImage = $data["data"]["seasons"]["season"][$i]["dates"]["date"];
            unset($data["data"]["seasons"]["season"][$i]["dates"]["date"]);
            $data["data"]["seasons"]["season"][$i]["dates"]["date"][0] = $tempImage;
          }
        }
      }
    }
    //Decodifico el contenido informativo
    if(is_array($data["data"]["details"])){
      for($i=0; $i<count($data["data"]["details"]["tag"]); $i++){
        $data["data"]["details"]["tag"][$i]["content"] = base64_decode($data["data"]["details"]["tag"][$i]["content"]);
        $data["data"]["details"]["tag"][$i]["name"] = base64_decode($data["data"]["details"]["tag"][$i]["name"]);
      }
    }

    $arrayDates = $this->helper->getAvaibleDates($data["data"]["seasons"]["season"]);
    $data["data"]["avaibleDates"] = $arrayDates;
    return $data["data"];
  }
  /**
   *
   * This function set the xml to request rate
   * @param $productId
   * @param $checkinDate
   * @param $checkoutDate
   */
  function buildRateRequest($productId, $checkinDate, $checkoutDate, $checkinHour, $checkoutHour){
    $data = new SimpleXMLElement('<Data></Data>');
    $data->addChild("type", $this->productType);
    $data->addChild("method", "rates");
    $data->addChild("product_id", $productId);
    $data->addChild("checkin_date", $this->helper->setDateFormatToService($checkinDate));
    $data->addChild("checkin_hour", $checkinHour);
    $data->addChild("checkout_date", $this->helper->setDateFormatToService($checkoutDate));
    $data->addChild("checkout_hour", $checkoutHour);

    //Set the currency id
    $currency=0;
    if(isset($_SESSION["currentCurrency"]) && $_SESSION["currentCurrency"]!=""){
      $currency = $_SESSION["currentCurrency"];
    }
    $data->addchild("currency", $currency);
    return $data;
  }
  /**
   *
   * This function build the response of the rate service
   * @param $data
   */
  function buildRateResponse($data){
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(is_array($data["data"]["rates"]) && count($data["data"]["rates"])>0){
      if(count($data["data"]["rates"]["rate"])>0){
        if(!isset($data["data"]["rates"]["rate"][0])){
          $tempImage = $data["data"]["rates"]["rate"];
          unset($data["data"]["rates"]["rate"]);
          $data["data"]["rates"]["rate"][0] = $tempImage;
        }
      }
    }
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(is_array($data["data"]["supplements"]) && count($data["data"]["supplements"])>0){
      if(count($data["data"]["supplements"]["supplement"])>0){
        if(!isset($data["data"]["supplements"]["supplement"][0])){
          $tempImage = $data["data"]["supplements"]["supplement"];
          unset($data["data"]["supplements"]["supplement"]);
          $data["data"]["supplements"]["supplement"][0] = $tempImage;
        }
      }
    }
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(is_array($data["data"]["childs"]) && count($data["data"]["childs"])>0){
      if(count($data["data"]["childs"]["child"])>0){
        if(!isset($data["data"]["childs"]["child"][0])){
          $tempImage = $data["data"]["childs"]["child"];
          unset($data["data"]["childs"]["child"]);
          $data["data"]["childs"]["child"][0] = $tempImage;
        }
      }
    }
    //Lo convierto en arreglo para no tener que hacerlo en la vista
    if(is_array($data["data"]["stocks"]) && count($data["data"]["stocks"])>0){
      if(count($data["data"]["stocks"]["stock"])>0){
        if(!isset($data["data"]["stocks"]["stock"][0])){
          $tempImage = $data["data"]["stocks"]["stock"];
          unset($data["data"]["stocks"]["stock"]);
          $data["data"]["stocks"]["stock"][0] = $tempImage;
        }
      }
    }
    //Recorro los suplementos y los de tipo 1 los organizo en rangos de fechas
    $prevPrice = 0;
    $prevEndDate = '';
    $startDate = '';
    $objDate = null;
    $arrayDates = array();
    if(count($data["data"]["supplements"]["supplement"])>0 && is_array($data["data"]["supplements"]["supplement"])){
      foreach($data["data"]["supplements"]["supplement"] as $index=>$supplement){
        $prevPrice = 0;
        $prevEndDate = '';
        $startDate = '';
        $objDate = null;
        $arrayDates = array();
        $contSupplement = 0;
        //Si el supplemento es de tipo 1
        if($supplement["type"]==1){
          //Se recorren las fechas disponibles
          if(isset($supplement["dates"]["date"][0])){
            foreach($supplement["dates"]["date"] as $date){
              //Se realiza una comparacion para saber si hubo cambio de precio
              if($prevPrice != $date["totalPrice"]){
                //Si existe la fecha inicial realizo creo el objeto con la fecha inicial y la final
                if($startDate!=""){
                  $arrayDates[] = array(
										"contSupplement"=>$contSupplement,
										"season"=>$objDate["season"],
										"price"=>$objDate["price"],
										"totalPrice"=>$objDate["totalPrice"],
										"totalTax"=>$objDate["totalTax"],
										"dateStart"=>$this->helper->setDateFormat($startDate),
										"endDate"=>$this->helper->setDateFormat($prevEndDate)
                  );
                }
                //Establesco de nuevo las fechas y datos basicos para la comparacion
                $startDate = $date["date"];
                $prevPrice = $date["totalPrice"];
                $objDate = $date;
                $contSupplement++;
              }
              $prevEndDate = $date["date"];
            }

            $arrayDates[] = array(
							"contSupplement"=>$contSupplement,
							"season"=>$objDate["season"],
							"price"=>$objDate["price"],
							"totalPrice"=>$objDate["totalPrice"],
							"totalTax"=>$objDate["totalTax"],
							"dateStart"=>$this->helper->setDateFormat($startDate),
							"endDate"=>$this->helper->setDateFormat($prevEndDate)
            );
          }else{
            $arrayDates[] = array(
							"contSupplement"=>$contSupplement,
							"season"=>$supplement["dates"]["date"]["season"],
							"price"=>$supplement["dates"]["date"]["price"],
							"totalPrice"=>$supplement["dates"]["date"]["totalPrice"],
							"totalTax"=>$supplement["dates"]["date"]["totalTax"],
							"dateStart"=>$supplement["dates"]["date"]["date"],
							"endDate"=>$supplement["dates"]["date"]["date"]
            );
          }
          	
          $data["data"]["supplements"]["supplement"][$index]["datesRange"] = $arrayDates;
        }

      }
    }
    return $data;
  }

  /**
   *
   * This function organize the data for the guest template
   * @param $objectDetails
   * @param $objectRates
   * @param $request
   */
  function buildGuestResponse($objectDetails, $objectRates, $request){
    /*print_r($objectDetails);
     print_r($objectRates);
     print_r($request);
     die();*/
    $arrayTaxReturn = array();
    $arrayResponse = array();
    //Asigno los datos basicos del producto al arreglo a retornar
    $arrayResponse["id"] = $objectDetails["id"];
    $arrayResponse["name"] = $objectDetails["name"];
    $arrayResponse["description"] = $objectDetails["description"];
    $arrayResponse["code"] = $objectDetails["code"];
    $arrayResponse["category"] = $objectDetails["category"];
    $arrayResponse["category_details"] = $objectDetails["category"];
    $arrayResponse["city"] = array("id"=>$objectDetails["city"]["id"], "name"=>$objectDetails["city"]["name"]);
    $arrayResponse["country"] = array("id"=>$objectDetails["country"]["id"], "name"=>$objectDetails["country"]["name"]);
    $arrayResponse["guest"]["adults"] = 1;
    $arrayResponse["date"]["checkin"] = $request["checkin"];
    $arrayResponse["date"]["checkin_hour"] = $request["checkin_hour"].":00 ".(($request["checkin_hour_ap"]==1)?"am":"pm");
    $arrayResponse["group"] = $objectRates["data"]["group"];
    $arrayResponse["groupCode"] = $objectRates["data"]["groupCode"];
    $arrayResponse["featured"] = $objectDetails["featured"];

    if($request["checkin_hour_ap"]==1){
      $checkinHourMilit = $request["checkin_hour"];
    }else{
      $checkinHourMilit = $request["checkin_hour"]+12;
      if($checkinHourMilit==24){
        $checkinHourMilit = 0;
      }
    }
    if($checkinHourMilit<10){
      $checkinHourMilit = "0".$checkinHourMilit;
    }

    $arrayResponse["date"]["checkin_hour_milit"] = $checkinHourMilit;
    $arrayResponse["date"]["checkout"] = $request["checkout"];
    $arrayResponse["date"]["checkout_hour"] = $request["checkout_hour"].":00 ".(($request["checkout_hour_ap"]==1)?"am":"pm");

    if($request["checkout_hour_ap"]==1){
      $checkoutHourMilit = $request["checkout_hour"];
    }else{
      $checkoutHourMilit = $request["checkout_hour"]+12;
      if($checkinHourMilit==24){
        $checkoutHourMilit = 0;
      }
    }
    if($checkoutHourMilit<10){
      $checkoutHourMilit = "0".$checkinHourMilit;
    }
    $arrayResponse["date"]["checkout_hour_milit"] = $checkoutHourMilit;
    $arrayResponse["nights"] = $this->helper->diffDate($request["checkin"], $request["checkout"])-1;
    $arrayResponse["disclaimer"] = $objectDetails["disclaimer"];
    $arrayResponse["supplier"] = $objectDetails["supplier"];
    $arrayResponse["currency"] = $objectRates["data"]["currency"];
    $arrayResponse["content"] = $objectDetails["details"];
    $arrayResponse["time"] = $objectRates["data"]["time"];


    $arrayResponse["duration_text"] = (($arrayResponse["time"]["days"]>0)?(($arrayResponse["time"]["days"]==1)?$arrayResponse["time"]["days"]." ".JText::_("CP.CAR.DAY"):$arrayResponse["time"]["days"]." ".JText::_("CP.CAR.DAYS")):"")." ".(($arrayResponse["time"]["hours"]>0)?(($arrayResponse["time"]["hours"]==1)?$arrayResponse["time"]["hours"]." ".JText::_("CP.CAR.HOUR"):$arrayResponse["time"]["hours"]." ".JText::_("CP.CAR.HOURS")):"");
    //Organizar la ciudad de entrega si llega vacia es la misma ciudad de recogida
    if($request["delivery_city"]==""){
      $arrayResponse["delivery_city"] = $objectDetails["city"]["name"];
    }else{
      $cityName = "";
      //Recorro el listado de ciudades de entrega
      foreach($objectDetails["delivery_cities"]["city"] as $city){
        //Al encontrar el id de la ciudad seleccionada la asocio y salgo del ciclo
        if($city["id"]==$request["delivery_city"]){
          $cityName = $city["name"];
        }
      }
      //die();
      $arrayResponse["delivery_city"] = $cityName;
    }

    $arrayCantSelect = array();


    //Asigno los precios unitarios por el carro seleccionado
    $arrayCantSelect[] = array(
			"quantity"=>1,
			"price"=>$objectRates["data"]["rates"]["rate"][0]["price"],
			"tax"=>$objectRates["data"]["rates"]["rate"][0]["totalTax"],
			"iva"=>$objectRates["data"]["rates"]["rate"][0]["totalIva"],
			"markupPorcent"=>$objectRates["data"]["rates"]["rate"][0]["markup_porcentage"],
			"markupValue"=>$objectRates["data"]["rates"]["rate"][0]["markup_value"],
			"totalTax"=>$objectRates["data"]["rates"]["rate"][0]["totalTax"],
			"totalIva"=>$objectRates["data"]["rates"]["rate"][0]["totalIva"],
			"totalPrice"=>$objectRates["data"]["rates"]["rate"][0]["price"],
			"totalPriceWhitTax"=>$objectRates["data"]["rates"]["rate"][0]["totalPrice"]
    );

    $arrayProductTaxes = array();
    if(isset($objectRates["data"]["rates"]["rate"][0]["taxes"]["tax"][0])){
      foreach($objectRates["data"]["rates"]["rate"][0]["taxes"]["tax"] as $taxObject){
        $arrayProductTaxes[$taxObject["id"]] += $taxObject["value"];
      }
    }elseif(isset($objectRates["data"]["rates"]["rate"][0]["taxes"]["tax"]["id"])){
      $taxObject = $objectRates["data"]["rates"]["rate"][0]["taxes"]["tax"];
      $arrayProductTaxes[$taxObject["id"]] += $taxObject["value"];
    }

    //Inicio los subtotales con el precio del auto
    $subtotal = $objectRates["data"]["rates"]["rate"][0]["price"];
    $subtotalTax = $objectRates["data"]["rates"]["rate"][0]["totalTax"];
    $subtotalIva = $objectRates["data"]["rates"]["rate"][0]["totalIva"];

    //Agrego los precios al arreglo a retornar
    $arrayResponse["rates"] = $arrayCantSelect;

    $arraySupplementDetail = array();
    if(is_array($objectDetails["supplements"]["supplement"])){
      //Organizo los suplementos por id para poder encontrarlos mas facilmente
      foreach($objectDetails["supplements"]["supplement"] as $supplementDetail){
        $arraySupplementDetail[$supplementDetail["id"]] = array("name"=>$supplementDetail["name"], "description"=>$supplementDetail["description"], "code"=>$supplementDetail["code"]);
      }
    }

    $arraySupplementPrice = array();
    //Organizo los precios de los suplementos
    if(is_array($objectRates["data"]["supplements"]["supplement"])){
      foreach($objectRates["data"]["supplements"]["supplement"] as $rateSupplement){
        if(isset($rateSupplement["taxes"]["tax"][0])){
          foreach($rateSupplement["taxes"]["tax"] as $taxData){
            if(isset($request["cantSupplement"][$rateSupplement["id"]]) && $request["cantSupplement"][$rateSupplement["id"]]>0){
              $arrayTaxReturn[] = array(
								"id"=>$taxData["id"],
								"name"=>$taxData["name"],
								"code"=>$taxData["code"],
								"value"=>$taxData["value"],
								"total"=>$taxData["price"]*$request["cantSupplement"][$rateSupplement["id"]],
								"type"=>$rateSupplement["id"]
              );
            }

          }
        }elseif(isset($rateSupplement["taxes"]["tax"]["id"])){
          $taxData = $rateSupplement["taxes"]["tax"];
          if(isset($request["cantSupplement"][$rateSupplement["id"]]) && $request["cantSupplement"][$rateSupplement["id"]]>0){
            $arrayTaxReturn[] = array(
							"id"=>$taxData["id"],
							"name"=>$taxData["name"],
							"code"=>$taxData["code"],
							"value"=>$taxData["value"],
							"total"=>$taxData["price"]*$request["cantSupplement"][$rateSupplement["id"]],
							"type"=>$rateSupplement["id"]
            );
          }
          	
        }
        $arraySupplementPrice[$rateSupplement["id"]] = array(
					"price"=>$rateSupplement["price"],
					"totalPrice"=>$rateSupplement["totalPrice"],
					"totalTax"=>$rateSupplement["totalTax"],
					"markupValue"=>$rateSupplement["markup_value"],
					"markupPorcent"=>$rateSupplement["markup_porcentage"],
					"totalIva"=>$rateSupplement["totalIva"]
        );
      }
    }




    if(is_array($request["cantSupplement"])){
      //cantSupplement contiene los suplementos que se tarifan sobre la totalidad de la duracion
      foreach($request["cantSupplement"] as $idSupplement=>$quantity){
        //Si se ha seleccionado por lo menos uno se organiza en el listado para ser mostrado
        if($quantity>0){
          $arraySupplements[] = array(
						"id"=> $idSupplement,
						"name"=> $arraySupplementDetail[$idSupplement]["name"],
						"code"=> $arraySupplementDetail[$idSupplement]["code"],
						"quantity" => $quantity,
						"price" => $arraySupplementPrice[$idSupplement]["price"],
						"totalPrice" => $arraySupplementPrice[$idSupplement]["price"]*$quantity,
						"totalPriceWithTax" => $arraySupplementPrice[$idSupplement]["totalPrice"]*$quantity,
						"tax" => $arraySupplementPrice[$idSupplement]["totalTax"],
						"iva" => $arraySupplementPrice[$idSupplement]["totalIva"],
						"totalTax" => $arraySupplementPrice[$idSupplement]["totalTax"]*$quantity,
						"totalIva" => $arraySupplementPrice[$idSupplement]["totalIva"]*$quantity,
						"markupValue" => $arraySupplementPrice[$idSupplement]["markupValue"],
						"markupTotalValue" => $arraySupplementPrice[$idSupplement]["markupValue"]*$quantity,
						"markupPorcent" => $arraySupplementPrice[$idSupplement]["markupPorcent"],
						"type"=>2
          );
          //Voy sumando para obtener el subtotal
          $subtotal += $arraySupplementPrice[$idSupplement]["price"]*$quantity;
          $subtotalTax += $arraySupplementPrice[$idSupplement]["totalTax"]*$quantity;
          $subtotalIva += $arraySupplementPrice[$idSupplement]["totalIva"]*$quantity;
        }
      }
    }


    //die(print_r());
    $minStock = 0;
    $arrayDataStock = array();
    //Organizo el stock para que se pueda relacionar mas facilmente
    if(is_array($objectRates["data"]["stocks"]["stock"])){
      if(is_array($objectRates["data"]["stocks"]["stock"][0]["dates"]["date"]) && isset($objectRates["data"]["stocks"]["stock"][0]["dates"]["date"]["day"])){
        $arrayDataStock[0] = $objectRates["data"]["stocks"]["stock"][0]["dates"]["date"];
        unset($objectRates["data"]["stocks"]["stock"][0]["dates"]["date"]);
        $objectRates["data"]["stocks"]["stock"][0]["dates"]["date"] = $arrayDataStock;
      }
      //Valido que exista stock para todos los dias del rango de fechas
      if(count($objectRates["data"]["stocks"]["stock"][0]["dates"]["date"]) == $this->helper->diffDate($request["checkin"], $request["checkout"])){
        //Recorro las fechas para obtener el de mas bajo stock
        foreach($objectRates["data"]["stocks"]["stock"][0]["dates"]["date"] as $date){
          if($minStock==0){
            $minStock = $date["quantity"];
          }else{
            if($minStock > $date["quantity"]){
              $minStock = $date["quantity"];
            }
          }
        }
      }
    }

    $haveStock = true;
    //Determino si hay autos en stock para ser despachados
    if($minStock==0 || $minStock<1){
      $haveStock = false;
    }

    //Organizo los impuestos del producto
    if(isset($objectRates["data"]["taxes"]["tax"][0])){
      	
      foreach($objectRates["data"]["taxes"]["tax"] as $tax){
        $arrayTaxReturn[] = array(
					"id"=>$tax["id"],
					"name"=>$tax["name"],
					"code"=>$tax["code"],
					"value"=>$tax["value"],
					"total"=>$arrayProductTaxes[$tax["id"]],
					"type"=>"product"
					);
      }
    }elseif(isset($objectRates["data"]["taxes"]["tax"]["id"])){
      $tax = $objectRates["data"]["taxes"]["tax"];
      $arrayTaxReturn[] = array(
				"id"=>$tax["id"],
				"name"=>$tax["name"],
				"code"=>$tax["code"],
				"value"=>$tax["value"],
				"total"=>$arrayProductTaxes[$tax["id"]],
				"type"=>"product"
				);
    }

    //Asocio los impuestos del producto al arreglo para que pueda ser guardado
    $arrayResponse["taxes"] = $arrayTaxReturn;

    //Agrego los identificadores de los articulos que contienen las politicas
    $catalogoComponentConfig=&JComponentHelper::getParams('com_catalogo_planes');
    $arrayResponse["article"]["cancel_id"] = $catalogoComponentConfig->get("cfg_cars_service_cancelation");
    $arrayResponse["article"]["conditions_id"] = $catalogoComponentConfig->get("cfg_cars_service_conditions");

    $arrayResponse["roomQuantity"] = $arrayRoomQuantity;
    $arrayResponse["stock"] = $arrayDataStock;
    $arrayResponse["supplements"] = $arraySupplements;
    $arrayResponse["haveStock"] = $haveStock;
    //Retorno los subtotales
    $arrayResponse["subtotal"] = $subtotal;
    $arrayResponse["totalTax"] = $subtotalTax;
    $arrayResponse["totalIva"] = $subtotalIva;
    $arrayResponse["total"] = $subtotal+$subtotalTax;

    return $arrayResponse;
  }

  /**
   *
   * This function organize the guest data into the book array
   * @param $booking
   * @param $guest
   */
  function buildBookingArray($booking, $guest){
    $catalogoComponentConfig=&JComponentHelper::getParams('com_catalogo_planes');
    $booking["guest"]["info"] = $guest["guest"];
    $booking["contact"] = $guest["contact"];
    $booking["payment"] = $guest["payment"];
    //Agrego las politicas de cancelacion para que sean guardadas
    $cancelPolicyArticle = $this->helper->getArticleById($catalogoComponentConfig->get("cfg_cars_service_cancelation"));
    $booking["article"]["cancel_policy"] = $cancelPolicyArticle["introtext"];
    //Agrego las condiciones de servicio para que sean guardadas
    $TermsServiceArticle = $this->helper->getArticleById($catalogoComponentConfig->get("cfg_cars_service_conditions"));
    $booking["article"]["conditions_service"] = $TermsServiceArticle["introtext"];
    return $booking;
  }

  /**
   *
   * This function create the request booking
   * @param $bookDetail
   */
  function buildBookingXmlRequest($bookDetail){
    $data = new SimpleXMLElement('<Data></Data>');
    $data->addChild("type", $this->productType);
    $data->addChild("method", "booking");
    $data->addChild("product_id", $bookDetail["id"]);
    $data->addChild("checkin_date", $this->helper->setDateFormatToService($bookDetail["date"]["checkin"]));
    $data->addChild("checkout_date", $this->helper->setDateFormatToService($bookDetail["date"]["checkout"]));
    $data->addChild("quantity", 1);
    return $data;
  }


  function getHour($hour, $ap){
    //Si la jornada es tarde modifico la hora
    if($ap==2){
      $hour = $hour + 12;
    }
    return $hour;
  }









}




