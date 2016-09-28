<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * catalogo_planes Component plan Model
 *
 * @author      andres.ramirez
 * @package		Joomla
 * @subpackage	catalogo_planes
 * @since 1.5
 */
class CatalogoPlanesModelPlan extends JModel {

  private $productType = "plan";


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
   $mainframe =& JFactory::getApplication();
    //Se obtienen los datos del post
    $params = JRequest::getVar('plan',array());
    if(count($params)<=0){
      //si no hay post se toman los datos del get
      if(JRequest::getVar('is_menu','')==1){
        if(JRequest::getVar('city','')!="")
        $params["city_id"] = JRequest::getVar('city','');
        if(JRequest::getVar('related','')!="")
        $params["related"] = JRequest::getVar('related','');
        if(JRequest::getVar('category','')!="")
        $params["accommodation"] = JRequest::getVar('category','');
        if(JRequest::getVar('tourismtype','')!="")
        $params["tourism_type"] = JRequest::getVar('tourismtype','');
        $params["date_start"] = JRequest::getVar('date_start',date('d/m/Y'));
        $nDays = JRequest::getVar('date_finish',2);
        if(!is_numeric($nDays)){
          $nDays = 2;
        }
        $params["date_finish"] = $this->helper->addDays($params["date_start"], $nDays);
      }else{
        //Si no estan por get se toman por sesion
        if(isset($_SESSION["params_plan"])){
          $params = $_SESSION["params_plan"];
        }else{
          //Si no existen en session se redirecciona a la home
          $mainframe->redirect("index.php");
        }
      }
    }
    $_SESSION["params_plan"] = $params;
    return $params;
  }
  /**
   *
   * This function buld the object of availability
   * @param $params
   */
  function buildAvailabilityRequest($params){
    //Aqui se valida si existe el parametro de fecha de regreso y se calcula el rango de consulta
    $paramsCatalogo =& JComponentHelper::getParams('com_catalogo_planes');
    $paramsCatalogo=$paramsCatalogo ->get('params');

    $rangeAdd = $paramsCatalogo->cfg_range_days;
    if($rangeAdd==0 || $rangeAdd==""){
      $params["date_finish"] = $params["PLAN_DATE_START"];
    }else{
      $params["date_finish"] = $this->helper->addDays($params["PLAN_DATE_START"], $rangeAdd);
      $params["PLAN_DATE_START"] = $this->helper->addDays($params["PLAN_DATE_START"], -$rangeAdd);

      $actualDate = strtotime('now');
      $tmeStartDate = strtotime(str_replace("/", "-",$params["PLAN_DATE_START"]));
      if($actualDate >= $tmeStartDate){
        $params["PLAN_DATE_START"] = $this->helper->addDays(date("d/m/Y"), 1);
      }
    }
    $data = new SimpleXMLElement('<Data></Data>');
    $data->addChild("type", $this->productType);
    $data->addChild("method", "find");
    $data->addChild("search");
    if(isset($params["PLAN_REGION"]))
      $data->search->addchild("region", $params["PLAN_REGION"]);
    $data->search->addchild("country", $params["PLAN_COUNTRY"]);
    $data->search->addchild("city", $params["PLAN_CITY"]);
    $data->search->addchild("checkin_date", $this->helper->setDateFormatToService($params["PLAN_DATE_START"]));
    $data->search->addchild("checkout_date", $this->helper->setDateFormatToService($params["date_finish"]));
    $data->search->addchild("tourismtype", $params["PLAN_TOURISMTYPE"]);
    $data->search->addchild("adults", $params["PLAN_ADULTS"]);
    $data->search->addchild("childs", $params["PLAN_CHILDREN"]);
    if(isset($params["accommodation"]))
    $data->search->addchild("category", $params["accommodation"]);
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
  function buidDetailResponse($data, $params){

    //Lo convierto en arreglo para no tener que hacerlo en la vista
    $tempAmmenity = array();

    /*if(is_array($data["data"]["amenities"]) && count($data["data"]["amenities"])>0){
      if(count($data["data"]["amenities"]["amenity"])>0){
        if(!isset($data["data"]["amenities"]["amenity"][0])){
          $tempAmmenity = $data["data"]["amenities"]["amenity"];
          unset($data["data"]["amenities"]["amenity"]);
          $data["data"]["amenities"]["amenity"][0] = $tempAmmenity;
        }
      }
    }*/
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
    if(is_array($data["data"]["tourismtype"]) && count($data["data"]["tourismtype"])>0){
      if(count($data["data"]["tourismtype"]["item"])>0){
        if(!isset($data["data"]["tourismtype"]["item"][0])){
          $tempImage = $data["data"]["tourismtype"]["item"];
          unset($data["data"]["tourismtype"]["item"]);
          $data["data"]["tourismtype"]["item"][0] = $tempImage;
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
    $isSpecial = true;
    foreach($data["data"]["seasons"]["season"] as $season){
      if($season["special"]!=1){
        $isSpecial = false;
      }
    }
    $arrayDates = $this->helper->getAvaibleDates($data["data"]["seasons"]["season"]);

    if(isset($params["PLAN_DATE_START"])){
      if(in_array($params["PLAN_DATE_START"], $arrayDates)){
        $data["data"]["selectedDate"] = $params["PLAN_DATE_START"];
      }else{
        //die($_SESSION["plan"]["dateStart"]);
        $startDate = strtotime(str_replace("/", "-", $_SESSION["plan"]["dateStart"]));
        $startFinish = strtotime(str_replace("/", "-", $_SESSION["plan"]["dateFinish"]));
        for($i=$startDate; $i<=$startFinish; $i+=86400){
          if(in_array(date("d/m/Y", $i), $arrayDates)){
            $data["data"]["selectedDate"] = date("d/m/Y", $i);
            break;
          }
        }

      }
    }

    //Evaluo si existe el dia de hoy en el arreglo
    if(in_array(date("d/m/Y"), $arrayDates)){
      //Obtengo el indice del dia
      $index = array_search(date("d/m/Y"), $arrayDates);
      //Elimino el dia del arreglo
      unset($arrayDates[$index]);
    }

    //if(in_array())
    $data["data"]["avaibleDates"] = $arrayDates;
    $data["data"]["specialSeasons"] = $isSpecial;

    return $data["data"];
  }
  /**
   *
   * This function set the xml to request rate
   * @param $productId
   * @param $checkinDate
   * @param $checkoutDate
   */
  function buildRateRequest($productId, $checkinDate, $checkoutDate){
    $data = new SimpleXMLElement('<Data></Data>');
    $data->addChild("type", $this->productType);
    $data->addChild("method", "rates");
    $data->addChild("product_id", $productId);
    $data->addChild("checkin_date", $this->helper->setDateFormatToService($checkinDate));
    $data->addChild("checkout_date", $this->helper->setDateFormatToService($checkoutDate));

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
    if(isset($data["data"]["supplements"]["supplement"]) && count($data["data"]["supplements"]["supplement"])>0 && is_array($data["data"]["supplements"]["supplement"])){
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

    $arrayResponse = array();
    //Asigno los datos basicos del producto al arreglo a retornar
    $arrayResponse["id"] = $objectDetails["id"];
    $arrayResponse["name"] = $objectDetails["name"];
    $arrayResponse["description"] = $objectDetails["description"];
    $arrayResponse["code"] = $objectDetails["code"];
    $arrayResponse["category_details"] = $objectDetails["category"];
    $arrayResponse["city"] = array("id"=>$objectDetails["city"]["id"], "name"=>$objectDetails["city"]["name"]);
    $arrayResponse["region"] = array("id"=>$objectDetails["region"]["id"], "name"=>$objectDetails["region"]["name"]);
    $arrayResponse["country"] = array("id"=>$objectDetails["country"]["id"], "name"=>$objectDetails["country"]["name"]);
    $arrayResponse["guest"]["adults"] = $request["adults"];
    $arrayResponse["guest"]["childs"] = $request["childs"];
    $arrayResponse["date"]["checkin"] = $request["checkin"];
    $arrayResponse["duration"] = $objectDetails["duration"];
    $arrayResponse["duration_text"] = $objectDetails["duration_text"];
    $arrayResponse["disclaimer"] = $objectDetails["disclaimer"];
    $arrayResponse["supplier"] = $objectDetails["supplier"];
    $arrayResponse["currency"] = $objectRates["data"]["currency"];
    $arrayResponse["content"] = $objectDetails["details"];
    $arrayResponse["tourismtype"] = $objectDetails["tourismtype"]["item"];
    $arrayResponse["group"] = $objectRates["data"]["group"];
    $arrayResponse["groupCode"] = $objectRates["data"]["groupCode"];
    $arrayResponse["featured"] = $objectDetails["featured"];

    $arrayCantSelect = array();
    $arrayParam1 = array();
    $arrayParam2 = array();
    $arrayParam3 = array();
    //Organizo los parametros por id para ser asociado mas facilmente
    foreach($objectDetails["params1"]["param"] as $valueParam1){
      $arrayParam1[$valueParam1["id"]] = array("name"=>$valueParam1["name"], "stars"=>$valueParam1["value"], "address"=>$valueParam1["description"]);
    }
    $arrayParam2[0] = JText::_("CP.PLAN.CHILDS");
    //Organizo los parametros por id para ser asociado mas facilmente
    foreach($objectDetails["params2"]["param"] as $valueParam2){
      $arrayParam2[$valueParam2["id"]] = $valueParam2["name"];
      $arrayParam2Capacity[$valueParam2["id"]] = $valueParam2["capacity"];
    }
    //Organizo los parametros por id para ser asociado mas facilmente
    foreach($objectDetails["params3"]["param"] as $valueParam3){
      $arrayParam3[$valueParam3["id"]] = $valueParam3["name"];
    }
    //En esta variable se va a tener almacenada la cantidad de habitaciones seleccionada por tipo de habitacion
    $arrayRoomQuantity = array();
    $subtotal = 0;
    $subtotalTax = 0;
    $subtotalIva = 0;
    $arrayProductTaxes = array();

    $arrayTaxReturn = array();

    //Recorro los parametros con valor elegidos para obtener el parametro 1
    foreach($request["cantParams"] as $param1=>$value1){
      $price = 0;
      $tax = 0;
      $iva = 0;
      $totalPrice = 0;
      //Recorro los parametros con valor elegidos para obtener el parametro 2
      foreach($value1 as $param2=>$value){
        //Si el valor es mayor a 0 indica que se ha elegido por lo menos una habitacion
        if($value>0){
          $quantity = $value;
          //Multiplico la cantidad seleccionada por la capacidad de la habitacion
          $value = $value*$arrayParam2Capacity[$param2];
          $param3 = $request["params"][$param1][$param2];
          //Se recorre el arreglo de tarifas para obtener la tarifa de acuerdo a los tres parametros de tarificacion
          foreach($objectRates["data"]["rates"]["rate"] as $rate){
            //Si concuerdan los parametros se establece el precio
            if($rate["param1"]==$param1 && $rate["param2"]==$param2 && $rate["param3"]==$param3){
              $price = $rate["price"];
              $tax = $rate["totalTax"];
              $iva = $rate["totalIva"];
              $markupValue = $rate["markup_value"];
              $markupPorcentage = $rate["markup_porcentage"];
              $totalPrice = $rate["totalPrice"];
              if(isset($rate["taxes"]["tax"][0])){
                foreach($rate["taxes"]["tax"] as $taxObject){
                  $arrayProductTaxes[$taxObject["id"]] += $taxObject["value"]*$value;
                }
              }elseif(isset($rate["taxes"]["tax"]["id"])){
                $taxObject = $rate["taxes"]["tax"];
                $arrayProductTaxes[$taxObject["id"]] += $taxObject["value"]*$value;
              }

              break;
            }
          }
          //Asigno los precios unitarios de las habitaciones seleccionadas
          $arrayCantSelect[] = array(
						"pamam1"=>array("id"=>$param1, "name"=>$arrayParam1[$param1]["name"], "stars"=>$arrayParam1[$param1]["stars"], "address"=>$arrayParam1[$param1]["address"]),
						"pamam2"=>array("id"=>$param2, "name"=>$arrayParam2[$param2]),
						"pamam3"=>array("id"=>$param3, "name"=>$arrayParam3[$param3]),
						"quantity"=>$quantity,
						"markupPorcent"=>$markupPorcentage,
						"markupValue"=>$markupValue,
						"markupTotalValue"=>$markupValue*$quantity,
						"price"=>$price,
						"tax"=>$tax,
						"iva"=>$iva,
						"totalTax"=>$tax*$value,
						"totalIva"=>$iva*$value,
						"totalPrice"=>$price*$value,
						"totalPriceWhitTax"=>$totalPrice*$value
          );
          //Incremento la cantidad por el hotel
          @$arrayRoomQuantity[$param1] += isset($value) ? $value : '';

          //Voy sumando para obtener el subtotal
          $subtotal += $price*$value;
          $subtotalTax += $tax*$value;
          $subtotalIva += $iva*$value;
        }
      }
    }

    //Agrego los precios al arreglo a retornar
    $arrayResponse["rates"] = $arrayCantSelect;

    $arraySupplementDetail = array();
    if(isset($objectDetails["supplements"]["supplement"]) && is_array($objectDetails["supplements"]["supplement"])){
      //Organizo los suplementos por id para poder encontrarlos mas facilmente
      foreach($objectDetails["supplements"]["supplement"] as $supplementDetail){
        $arraySupplementDetail[$supplementDetail["id"]] = array("name"=>$supplementDetail["name"], "description"=>$supplementDetail["description"], "code"=>$supplementDetail["code"]);
      }
    }

    $arraySupplementPrice = array();
    //die(print_r($objectRates));
    //Organizo los precios de los suplementos
    if(isset($objectRates["data"]["supplements"]["supplement"]) && is_array($objectRates["data"]["supplements"]["supplement"])){
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



    if(isset($request["cantSupplement"]) && is_array($request["cantSupplement"])){
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

    //Recorro los precios de los ninos para sacar el precio segun el plan de alimentacion seleccionado
    if($request["childs"]>0){
      //Recorro los parametros del formulario
      foreach($request["cantParams_child"] as $param1=>$quantity){
        //Si se selecciono uno lo agrego al arreglo resultante
        if($quantity>0){
          $param3 = $request["params_child"][$param1];
          //Recorro el listado de tarifas para asociarlas al arreglo resultante
          foreach($objectRates["data"]["childs"]["child"] as $priceChild){
            if($priceChild["param3"]==$param3 && $priceChild["param1"]==$param1){
              if(isset($priceChild["taxes"]["tax"][0])){
                foreach($priceChild["taxes"]["tax"] as $taxObject){
                  $arrayProductTaxes[$taxObject["id"]] += $taxObject["value"]*$quantity;
                }
              }elseif(isset($priceChild["taxes"]["tax"]["id"])){
                $taxObject = $priceChild["taxes"]["tax"];
                $arrayProductTaxes[$taxObject["id"]] += $taxObject["value"]*$quantity;
              }
              $childPriceSelect = $priceChild["price"];
              $childTaxes = $priceChild["totalTax"];
              $childIva = $priceChild["totalIva"];
              $childTotalPrice = $priceChild["totalPrice"];
              $childMarkupValue = $priceChild["markup_value"];
              $childMarkupPorcent = $priceChild["markup_porcentage"];
              break;
            }
          }
          //Organizo los ninos
          $arrayResponse["childs"][] = array(
							"param1"=>array("id"=>$param1, "name"=>$arrayParam1[$param1]),
							"param3"=>array("id"=>$param3, "name"=>$arrayParam3[$param3]),
							"quantity"=>$quantity,
							"price"=>$childPriceSelect,
							"totalPrice"=>$childPriceSelect*$quantity,
							"tax"=>$childTaxes,
							"iva"=>$childIva,
							"totalTax"=>$childTaxes*$quantity,
							"totalIva"=>$childIva*$quantity,
							"totalPriceWithTax"=>$childTotalPrice*$quantity,
							"markupValue"=>$childMarkupValue,
							"markupTotalValue"=>$childMarkupValue*$request["childs"],
							"markupPorcent"=>$childMarkupPorcent
          );
          //Voy sumando para obtener el subtotal
          $subtotal += $childPriceSelect*$quantity;
          $subtotalTax += $childTaxes*$quantity;
          $subtotalIva += $childIva*$quantity;
        }
      }



    }
    $arrayDataStock = array();
    $haveStock = false;

    //Organizo el stock para que se pueda relacionar mas facilmente
    if(isset($objectRates["data"]["stocks"]["stock"]) && is_array($objectRates["data"]["stocks"]["stock"])){
      foreach($objectRates["data"]["stocks"]["stock"] as $stock){

        //Unicamente se puede seleccionar un hotel asi que el stock se valida solo por un parametro y un dia
        $tq_qty = $request["childs"] + $arrayRoomQuantity[$stock["param"]];
        if(isset($arrayRoomQuantity[$stock["param"]]) && $tq_qty<=$stock["dates"]["date"]["quantity"]){
          $haveStock=true;
        }
        //Agrego el stock al arreglo
        $arrayDataStock[$stock["param"]] = $stock["dates"]["date"]["quantity"];
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


    //Agrego los identificadores de los articulos que contienen las politicas
    $catalogoComponentConfig=&JComponentHelper::getParams('com_catalogo_planes');
    $arrayResponse["article"]["cancel_id"] = $catalogoComponentConfig->get("cfg_plans_service_cancelation");
    $arrayResponse["article"]["conditions_id"] = $catalogoComponentConfig->get("cfg_plans_service_conditions");

    $arrayResponse["roomQuantity"] = $arrayRoomQuantity;
    $arrayResponse["stock"] = $arrayDataStock;
    $arrayResponse["supplements"] = isset($arraySupplements)?$arraySupplements:array();
    $arrayResponse["haveStock"] = $haveStock;
    //Retorno los subtotales
    $arrayResponse["subtotal"] = $subtotal;
    $arrayResponse["totalTax"] = $subtotalTax;
    $arrayResponse["totalIva"] = $subtotalIva;
    $arrayResponse["total"] = $subtotal+$subtotalTax;
    //die(print_r($arrayResponse));
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
    $cancelPolicyArticle = $this->helper->getArticleById($catalogoComponentConfig->get("cfg_plans_service_cancelation"));
    $booking["article"]["cancel_policy"] = $cancelPolicyArticle["introtext"];
    //Agrego las condiciones de servicio para que sean guardadas
    $TermsServiceArticle = $this->helper->getArticleById($catalogoComponentConfig->get("cfg_plans_service_conditions"));
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
    $data->addChild("rooms");
    $contRoom = 0;
    //Organizo las cantidades y habitaciones en el xml
    foreach($bookDetail["roomQuantity"] as $room=>$quantity){
      $data->rooms->addChild("room");
      $data->rooms->room[$contRoom]->addChild("param_id", $room);
      $data->rooms->room[$contRoom]->addChild("quantity", $quantity);
      $contRoom++;
    }
    return $data;
  }












}




