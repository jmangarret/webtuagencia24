<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * catalogo_planes Component transfer Model
 *
 * @author      andres.ramirez
 * @package		Joomla
 * @subpackage	catalogo_planes
 * @since 1.5
 */
class CatalogoPlanesModelTransfer extends JModel {

  private $productType = "transfer";


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
    $params = JRequest::getVar('transfer',array());
    if(count($params)<=0){
      //si no hay post se toman los datos del get
      if(JRequest::getVar('is_menu','')==1){
        if(JRequest::getVar('city','')!="")
        $params["city_id"] = JRequest::getVar('city','');
        if(JRequest::getVar('related','')!="")
        $params["related"] = JRequest::getVar('related','');
        if(JRequest::getVar('type','')!="")
        $params["type"] = JRequest::getVar('type','');
        if(JRequest::getVar('category','')!="")
        $params["service_type"] = JRequest::getVar('category','');
        $params["date_start"] = JRequest::getVar('date_start',date('d/m/Y'));
        $nDays = JRequest::getVar('date_finish',0);
        if(!is_numeric($nDays)){
          $nDays = 0;
        }
        $params["date_finish"] = $this->helper->addDays($params["date_start"], $nDays);
      }else{
        //Si no estan por get se toman por sesion
        if(isset($_SESSION["params_transfer"])){
          $params = $_SESSION["params_transfer"];
        }else{
          //Si no existen en session se redirecciona a la home
          $mainframe->redirect("index.php");
        }
      }
    }
    $_SESSION["params_transfer"] = $params;
    //Establesco en session el tipo de traslado para que sea utilizado en la pantalla de detalles
    $_SESSION["transfer_type"] = $params["type"];
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
    $data->search->addchild("service_type", $params["service_type"]);
    $data->search->addchild("type", $params["type"]);
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
    if(is_array($data["data"]["categories"]) && count($data["data"]["categories"])>0){
      if(count($data["data"]["categories"]["category"])>0){
        if(!isset($data["data"]["categories"]["category"][0])){
          $tempImage = $data["data"]["categories"]["category"];
          unset($data["data"]["categories"]["category"]);
          $data["data"]["categories"]["category"][0] = $tempImage;
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
  function buildRateRequest($productId, $checkinDate, $checkoutDate, $type){
    $data = new SimpleXMLElement('<Data></Data>');
    $data->addChild("type", $this->productType);
    $data->addChild("method", "rates");
    $data->addChild("product_id", $productId);
    $data->addChild("checkin_date", $this->helper->setDateFormatToService($checkinDate));
    $data->addChild("type_transfer", $type);
    if($type==2){
      $data->addChild("checkout_date", $this->helper->setDateFormatToService($checkoutDate));
    }
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
  function buildRateResponse($data, $detail, $passengers, $isPassengers){
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



    //die(print_r($data));
    $arrayParam2 = array();
    //Organizo los parametros de tarificacion
    foreach($detail["params2"]["param"] as $param){
      $arrayParam2[$param["id"]] = array("min"=>$param["capacity"], "max"=>$param["value"]);
    }

    $arrayPassRange = array();
    if(!$isPassengers){
      	
      //Organizo el rango de pasajeros
      foreach($data["data"]["rates"]["rate"] as $index=>$rate){
        $dataParam2 = $arrayParam2[$rate["param2"]];
        //Si no es esta en la seleccion de pasajeros, organizo los pasajeros disponibles
        for($i=$dataParam2["min"]; $i<=$dataParam2["max"]; $i++){
          if(!in_array($i, $arrayPassRange)){
            $arrayPassRange[] = $i;
          }
        }
      }
      sort($arrayPassRange);
      //Valido que la cantidad de pasajeros seleccionada se encuentre en el arreglo de pasajeros
      if(!in_array($passengers, $arrayPassRange)){
        //Si no asigno el numero de pasajeros como el primero del arreglo
        $passengers = $arrayPassRange[0];
      }
      //Asigno los rangos de pasajeros a la respuesta
      $data["data"]["passengerRange"] = $arrayPassRange;

    }

    $idParam2 = 0;
    foreach($data["data"]["rates"]["rate"] as $index=>$rate){
      $dataParam2 = $arrayParam2[$rate["param2"]];
      //Si no se encuentra en el rango de personas lo elimino del listado
      if($passengers<$dataParam2["min"] || $passengers>$dataParam2["max"]){
        $data["data"]["rates"]["rate"][$index] = array();
      }else{
        $data["data"]["rates"]["rate"][$index]["price"] *= $passengers;
        $data["data"]["rates"]["rate"][$index]["totalTax"] *= $passengers;
        $data["data"]["rates"]["rate"][$index]["totalIva"] *= $passengers;
        $data["data"]["rates"]["rate"][$index]["totalPrice"] *= $passengers;
        $idParam2 = $data["data"]["rates"]["rate"][$index]["param2"];
      }
    }
    $data["data"]["param2"] = $idParam2;


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

    $arrayResponse = array();
    //Asigno los datos basicos del producto al arreglo a retornar
    $arrayResponse["id"] = $objectDetails["id"];
    $arrayResponse["name"] = $objectDetails["name"];
    $arrayResponse["description"] = $objectDetails["description"];
    $arrayResponse["code"] = $objectDetails["code"];
    $arrayResponse["category"] = $objectDetails["category"];
    $arrayResponse["city"] = array("id"=>$objectDetails["city"]["id"], "name"=>$objectDetails["city"]["name"]);
    $arrayResponse["country"] = array("id"=>$objectDetails["country"]["id"], "name"=>$objectDetails["country"]["name"]);
    $arrayResponse["region"] = array("id"=>$objectDetails["region"]["id"], "name"=>$objectDetails["region"]["name"]);
    $arrayResponse["guest"]["adults"] = $request["adults"];
    $arrayResponse["date"]["checkin"] = $request["checkin"];
    $arrayResponse["date"]["checkin_hour"] = $request["checkin_hour"].":00 ".(($request["checkin_hour_ap"]==1)?"am":"pm");
    $arrayResponse["transfer_type"] = $objectDetails["transfer_type"];
    $arrayResponse["categories"] = $objectDetails["categories"]["category"];
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

    $arrayResponse["disclaimer"] = $objectDetails["disclaimer"];
    $arrayResponse["supplier"] = $objectDetails["supplier"];
    $arrayResponse["currency"] = $objectRates["data"]["currency"];
    $arrayResponse["content"] = $objectDetails["details"];
    $arrayResponse["time"] = $objectRates["data"]["time"];

    $param1 = $request["param1"][$request["destiny_id"]];
    $param3 = $request["destiny_id"];
    $arrayCantSelect = array();
    $arrayRateInfo = array();
    $arrayProductTaxes = array();

    $arrayTaxReturn = array();
    foreach($objectRates["data"]["rates"]["rate"] as $rate){
      if(isset($rate["param1"])){
        if($rate["param1"]==$param1 && $param3==$rate["param3"]){
          $arrayRateInfo = $rate;
          //Asigno el valor de los impuestos
          if(isset($rate["taxes"]["tax"][0])){
            foreach($rate["taxes"]["tax"] as $taxObject){
              $arrayProductTaxes[$taxObject["id"]] += $taxObject["value"]*$request["adults"];
            }
          }elseif(isset($rate["taxes"]["tax"]["id"])){
            $taxObject = $rate["taxes"]["tax"];
            $arrayProductTaxes[$taxObject["id"]] += $taxObject["value"]*$request["adults"];
          }
          break;
        }
      }
    }

    //Organizo los parametros por id para ser asociado mas facilmente
    foreach($objectDetails["params1"]["param"] as $valueParam1){
      $arrayParam1[$valueParam1["id"]] = $valueParam1["name"];
    }
    //Organizo los parametros por id para ser asociado mas facilmente
    foreach($objectDetails["params2"]["param"] as $valueParam2){
      $arrayParam2[$valueParam2["id"]] = $valueParam2["name"];
    }
    //Organizo los parametros por id para ser asociado mas facilmente
    foreach($objectDetails["params3"]["param"] as $valueParam3){
      $arrayParam3[$valueParam3["id"]] = $valueParam3["name"];
    }

    //Asigno los precios unitarios por el traslado seleccionado
    $arrayCantSelect[] = array(
			"pamam1"=>array("id"=>$param1, "name"=>$arrayParam1[$param1]),
    //"pamam2"=>array("id"=>$param2, "name"=>$arrayParam2[$param2]),
			"pamam3"=>array("id"=>$param3, "name"=>$arrayParam3[$param3]),		
			"quantity"=>$request["adults"],
			"price"=>$arrayRateInfo["price"],
			"tax"=>$arrayRateInfo["totalTax"],
			"iva"=>$arrayRateInfo["totalIva"],
			"totalTax"=>$arrayRateInfo["totalTax"],
			"totalIva"=>$arrayRateInfo["totalIva"],
			"totalPrice"=>$arrayRateInfo["price"],
			"markupPorcent"=>$arrayRateInfo["markup_porcentage"],
			"markupValue"=>$arrayRateInfo["markup_value"],
			"markupTotalValue"=>$arrayRateInfo["markup_value"]*$request["adults"],
			"totalPriceWhitTax"=>$arrayRateInfo["totalPrice"]
    );
    //Inicio los subtotales con el precio del auto
    $subtotal = $arrayRateInfo["price"];
    $subtotalTax = $arrayRateInfo["totalTax"];
    $subtotalIva = $arrayRateInfo["totalIva"];

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
        //Organizo los impuestos de los suplementos
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
      foreach($objectRates["data"]["stocks"]["stock"] as $stock){
        //Valido que se encuentre con el parametro seleccionado
        if($stock["param"]==$objectRates["data"]["param2"]){
          if($minStock==0){
            $minStock = $stock["quantity"];
          }else{
            if($minStock > $stock["quantity"]){
              $minStock = $stock["quantity"];
            }
          }
        }
      }
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

    $haveStock = true;
    //Determino si hay stock para ser despachados
    if($minStock==0 || $minStock<$arrayResponse["guest"]["adults"]){
      $haveStock = false;
    }


    //Agrego los identificadores de los articulos que contienen las politicas
    $catalogoComponentConfig=&JComponentHelper::getParams('com_catalogo_planes');
    $arrayResponse["article"]["cancel_id"] = $catalogoComponentConfig->get("cfg_transfers_service_cancelation");
    $arrayResponse["article"]["conditions_id"] = $catalogoComponentConfig->get("cfg_transfers_service_conditions");

    $arrayResponse["param2"] = $objectRates["data"]["param2"];
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
    $cancelPolicyArticle = $this->helper->getArticleById($catalogoComponentConfig->get("cfg_transfers_service_cancelation"));
    $booking["article"]["cancel_policy"] = $cancelPolicyArticle["introtext"];
    //Agrego las condiciones de servicio para que sean guardadas
    $TermsServiceArticle = $this->helper->getArticleById($catalogoComponentConfig->get("cfg_transfers_service_conditions"));
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
    $data->addChild("param");
    $data->param->addChild("param_id", $bookDetail["param2"]);
    $data->param->addChild("quantity", $bookDetail["guest"]["adults"]);
    $data->addChild("transfer_type", $bookDetail["transfer_type"]);


    return $data;
  }












}




