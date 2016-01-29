<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * catalogo_planes Component tour Model
 *
 * @author      andres.ramirez
 * @modified    dora.peña
 * @package		Joomla
 * @subpackage	catalogo_planes
 * @since 1.5
 */
class CatalogoPlanesModelTour extends JModel {

  private $productType = "tour";


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
    $arrayResponse["date"]["checkout"] = $request["checkout"];
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

    $subtotal = 0;
    $subtotalTax = 0;
    $subtotalIva = 0;
    $arrayProductTaxes = array();
    $arrayTaxReturn = array();

    //Organizo los precios de adultos y niños de forma independiente
    $arrayTourists = array();
    $arrayTouristsPrice = array();
    $arrayTourists[$objectDetails["param2Adults"]] = array('name'=>JText::_("CP.TOUR.ADULTS"),'qty'=>$request["adults"]);
    $arrayTourists[$objectDetails["param2Childs"]] = array('name'=>JText::_("CP.TOUR.CHILDS"),'qty'=>$request["childs"]);
    $totalTourists = 0;

    foreach($objectRates["data"]["rates"]["rate"] as $rate){
      if($rate["param2"]==$objectDetails["param2Adults"] || $rate["param2"]==$objectDetails["param2Childs"]){
        $arrayTouristsPrice[$rate["param2"]] = array(
				    "name" =>$arrayTourists[$rate["param2"]]['name'],
				    "quantity"=>$arrayTourists[$rate["param2"]]['qty'],
				    "markupPorcent"=>$rate["markup_porcentage"],
				    "markupValue"=>$rate["markup_value"],
				    "markupTotalValue"=>$rate["markup_value"]*$arrayTourists[$rate["param2"]]['qty'],
					"price"=>$rate["price"],
					"tax"=>$rate["totalTax"],
					"iva"=>$rate["totalIva"],
					"totalTax"=>$rate["totalTax"]*$arrayTourists[$rate["param2"]]['qty'],
					"totalIva"=>$rate["totalIva"]*$arrayTourists[$rate["param2"]]['qty'],
					"totalPrice"=>$rate["price"]*$arrayTourists[$rate["param2"]]['qty'],
					"totalPriceWhitTax"=>$rate["totalPrice"]*$arrayTourists[$rate["param2"]]['qty']
        );
        if(isset($rate["taxes"]["tax"][0])){
          foreach($rate["taxes"]["tax"] as $taxObject){
            $arrayProductTaxes[$taxObject["id"]] += $taxObject["value"]*$arrayTourists[$rate["param2"]]['qty'];
          }
        }elseif(isset($rate["taxes"]["tax"]["id"])){
          $taxObject = $rate["taxes"]["tax"];
          $arrayProductTaxes[$taxObject["id"]] += $taxObject["value"]*$arrayTourists[$rate["param2"]]['qty'];
        }
        //sumo el total de touristas para validarlo con el stock
        $totalTourists += $arrayTourists[$rate["param2"]]['qty'];
        //Voy sumando para obtener el subtotal
        $subtotal += $rate["price"]*$arrayTourists[$rate["param2"]]['qty'];
        $subtotalTax += $rate["totalTax"]*$arrayTourists[$rate["param2"]]['qty'];
        $subtotalIva += $rate["totalIva"]*$arrayTourists[$rate["param2"]]['qty'];
      }
    }

    //Agrego los precios al arreglo a retornar
    $arrayResponse["rates"] = $arrayTouristsPrice;

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
    $arrayDataStock = array();
    $haveStock = false;

    //Organizo el stock para que se pueda relacionar mas facilmente
    if(is_array($objectRates["data"]["stocks"]["stock"])){
      foreach($objectRates["data"]["stocks"]["stock"] as $stock){
        //Unicamente se puede seleccionar un hotel asi que el stock se valida solo por un parametro y un dia
        if(isset($totalTourists) && $totalTourists<=$stock["dates"]["date"]["quantity"]){
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
    $arrayResponse["article"]["cancel_id"] = $catalogoComponentConfig->get("cfg_tours_service_cancelation");
    $arrayResponse["article"]["conditions_id"] = $catalogoComponentConfig->get("cfg_tours_service_conditions");

    $arrayResponse["touristsQuantity"] = $totalTourists;
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
    $cancelPolicyArticle = $this->helper->getArticleById($catalogoComponentConfig->get("cfg_tours_service_cancelation"));
    $booking["article"]["cancel_policy"] = $cancelPolicyArticle["introtext"];
    //Agrego las condiciones de servicio para que sean guardadas
    $TermsServiceArticle = $this->helper->getArticleById($catalogoComponentConfig->get("cfg_tours_service_conditions"));
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
    $data->addChild("tourists",$bookDetail["touristsQuantity"]);
    return $data;
  }
}




