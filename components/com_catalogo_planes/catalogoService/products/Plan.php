<?php
/*
 * This class have the methods of plan
 * @author andres.ramirez
 *
 */
class Plan extends Products{

  private $codeAgency;
  private $productName = "plan";

  /**
   *
   * This function find the avaible plans
   * @param $requestXML
   * @param $xmlHeader
   */
  function find($requestXML, $xmlHeader){
    //Establece la lisa de monedas
    $this->setCurrencyList($requestXML->language);
    //Instancia la conexion
    $objConection = new DataConection();
    //Establece los datos de la busqueda en la etiqueta search
    $seach = $requestXML->search;
    //set the agency code
    $this->codeAgency = $requestXML->agency_code;
    //Valido si existe el parametro destacado
    if(!isset($seach->related) || $seach->related==""){
      $seach->related = "2";
    }
    //Valido si existe el parametro total de items, si no establesco el configurado
    if(!isset($seach->total_items) || $seach->total_items==""){
      $seach->total_items = $this->serviceConfig->totalItems;
    }

    //Llama al procedimiento almacenado enviandole los parametros
    $sql = "CALL FindPlan(
						".$this->serviceConfig->applyMarkup.",
						".$this->serviceConfig->applyTours.",
						".((isset($seach->region) && $seach->region!="")?$seach->region:0).",
						".((isset($seach->country) && $seach->country!="")?$seach->country:0).",
						".((isset($seach->city) && $seach->city!="")?$seach->city:0).",
						'".$seach->checkin_date."',
						'".$seach->checkout_date."',
						".((isset($seach->category) && $seach->category!="")?$seach->category:0).",
						".((isset($seach->tourismtype) && $seach->tourismtype!="")?$seach->tourismtype:0).",
						".((isset($seach->adults) && $seach->adults!="")?$seach->adults:0).",
						".((isset($seach->childs) && $seach->childs!="")?$seach->childs:0).",
						".$this->agency->idagency_group.",
						".($this->userAccess+1).",
						".$seach->related.",
						".$seach->total_items."
					)";
    //$this->userAccess+1)//Este valor debe ser validado por el guardado en el back
    //se envia la consulta a la clase conexion
    $objConection->setQuery($sql);

    //Se ejecuta la consulta
    $result = $objConection->loadObjectList();

    if(!isset($seach->currency) || $seach->currency==""){
      $seach->currency = 0;
    }
    if(count($result)>0){
      //Obtiene el xml organizado para enviarlo a la peticion
      $xmlObject = $this->objectToXmlAvailability($xmlHeader, $result, $seach->currency);

      return 	$xmlObject;
    }else{
      return($this->_objError->handleObjectError(5));
    }
  }

  /**
   *
   * This function recieve object and return the xml Object for availability
   * @param $object
   * @param $items
   */
  private function objectToXmlAvailability($xmlHeader, $object, $currencyId){
    $xmlHeader->addChild("products");
    $countItems = 0;
    $langArray = array();
    //Get tha lang for the products
    $langArray['plan'] = $this->getLangObjectByTable("cp_plans_info", $xmlHeader->language);
    $langArray['tour'] = $this->getLangObjectByTable("cp_tours_info", $xmlHeader->language);


    //get the zone list
    $zonesArray = $this->getZones($xmlHeader->language);

    //get the category list
    $categoryArray['plan']= $this->getcategory($xmlHeader->language, "plan");
    $categoryArray['tour']= $this->getcategory($xmlHeader->language, "tour");
    //get the tourismtype list
    $tourismTypeArray = $this->getTourismType($xmlHeader->language, "plan");
    foreach($object as $objectItem){
      $xmlHeader->products->addChild("product");
      $xmlHeader->products->product[$countItems]->addChild("id", $objectItem->product_id);
      //Multilanguage to name
      if(isset($langArray[$objectItem->product_type][$objectItem->product_id]["product_name"])){
        $objectItem->product_name = $langArray[$objectItem->product_type][$objectItem->product_id]["product_name"];
      }
      $xmlHeader->products->product[$countItems]->addChild("name", $objectItem->product_name);
      //Multilanguage to description
      if(isset($langArray[$objectItem->product_type][$objectItem->product_id]["product_desc"])){
        $objectItem->product_desc = $langArray[$objectItem->product_type][$objectItem->product_id]["product_desc"];
      }
      $xmlHeader->products->product[$countItems]->addChild("description",$objectItem->product_desc);
      $xmlHeader->products->product[$countItems]->addChild("latitude", $objectItem->latitude);
      $xmlHeader->products->product[$countItems]->addChild("longitude", $objectItem->longitude);
      $xmlHeader->products->product[$countItems]->addChild("url", base64_encode(trim($objectItem->product_url)));
      $xmlHeader->products->product[$countItems]->addChild("image", $objectItem->image_url);
      $xmlHeader->products->product[$countItems]->addChild("code", $objectItem->product_code);
      $xmlHeader->products->product[$countItems]->addChild("rating", $objectItem->average_rating);
      //Multilanguage to duration
      if(isset($langArray[$objectItem->product_type][$objectItem->product_id]["duration"])){
        $objectItem->duration_text = $langArray[$objectItem->product_type][$objectItem->product_id]["duration"];
      }
      $xmlHeader->products->product[$countItems]->addChild("duration", $objectItem->duration);
      $xmlHeader->products->product[$countItems]->addChild("duration_text", $objectItem->duration_text);
      $xmlHeader->products->product[$countItems]->addChild("featured", $objectItem->featured);
      $xmlHeader->products->product[$countItems]->addChild("product_type", $objectItem->product_type);

      $xmlHeader->products->product[$countItems]->addChild("country");
      $xmlHeader->products->product[$countItems]->country->addChild("id",$objectItem->country_id);
      $xmlHeader->products->product[$countItems]->country->addChild("name",$objectItem->country_name);
      $xmlHeader->products->product[$countItems]->addChild("city");
      $xmlHeader->products->product[$countItems]->city->addChild("id",$objectItem->city_id);
      $xmlHeader->products->product[$countItems]->city->addChild("name",$objectItem->city_name);
      $xmlHeader->products->product[$countItems]->addChild("category");
      $xmlHeader->products->product[$countItems]->category->addChild("id",$objectItem->category_id);
      if(isset($categoryArray[$objectItem->product_type][$objectItem->category_id]))
        $xmlHeader->products->product[$countItems]->category->addChild("name",$categoryArray[$objectItem->product_type][$objectItem->category_id]);
      $xmlHeader->products->product[$countItems]->addChild("price");


      //Se obtiene el valor del markup para sumarselo a la tarifa
      $markup = $this->getMarkup($objectItem->product_id);

      //Obtenemos la moneda en la cual se mostraran los resultados, ademas de los valores con los cambios de moneda calculados
      $objBasicPrice = $this->getTheCurrencyValue($objectItem->basic_price, $currencyId, $objectItem->currency_id, $markup);
      $objPreviousPrice = $this->getTheCurrencyValue($objectItem->previous_value, $currencyId, $objectItem->currency_id, $markup);

      $xmlHeader->products->product[$countItems]->price->addChild("currency");
      $xmlHeader->products->product[$countItems]->price->currency->addChild("id", $objBasicPrice->currency_id);
      $xmlHeader->products->product[$countItems]->price->currency->addChild("name", $objBasicPrice->currency_name);
      $xmlHeader->products->product[$countItems]->price->currency->addChild("symbol", $objBasicPrice->symbol);
      $xmlHeader->products->product[$countItems]->price->addChild("value",$objBasicPrice->price);
      $xmlHeader->products->product[$countItems]->price->addChild("previous_value",$objPreviousPrice->price);

      //se obtienen los tipos de turismo en un arreglo
      $tourismTypeParts = explode(",", $objectItem->tourismtype);
      $xmlHeader->products->product[$countItems]->addChild("tourismtype");
      //Se recorren los tipos de turismo y se agregan al objeto
      for($i=0; $i<count($tourismTypeParts); $i++){
        $xmlHeader->products->product[$countItems]->tourismtype->addChild("item");
        $xmlHeader->products->product[$countItems]->tourismtype->item[$i]->addChild("id", $tourismTypeParts[$i]);
        $xmlHeader->products->product[$countItems]->tourismtype->item[$i]->addChild("name", $tourismTypeArray[$tourismTypeParts[$i]]);
      }
      $countItems++;
    }
    return $xmlHeader;
  }

  /**
   *
   * This function return the details of the plan
   * @param $requestXML
   * @param $xmlHeader
   */
  function details($requestXML, $xmlHeader){
    //Establece la lisa de monedas
    $this->setCurrencyList($requestXML->language);
    //Instancia la conexion
    $objConection = new DataConection();
    //Establece los datos de la busqueda en la etiqueta search
    $prodictId = $requestXML->product_id;
    //set the agency code
    $this->codeAgency = $requestXML->agency_code;
    //Llama al procedimiento almacenado enviandole los parametros
    $sql = "CALL DetailsPlan(".$prodictId.", '".$requestXML->language."')";

    //se envia la consulta a la clase conexion
    $objConection->setQuery($sql);
    //Se ejecuta la consulta
    $result = $objConection->loadObject();
    //print_r($result);
    //Obtiene el xml organizado para enviarlo a la peticion
    $xmlObject = $this->objectToXmlDetails($xmlHeader, $result, $prodictId);

    return $xmlObject;

  }

  /**
   *
   * This function recieve an object and return the cml object
   * @param $xmlHeader
   * @param $object
   * @param $currencyId
   */
  private function objectToXmlDetails($xmlHeader, $object, $productId){

    $xmlHeader->addChild("data");
    //Get tha lang for the products
    $langArray = $this->getLangObjectByTableAndId("cp_plans_info", $productId, $xmlHeader->language);
    //Get the image list
    $imageArray = $this->getImageList($productId, $this->productName);

    //get the amenity list
    $amenityArray = $this->getAmenities($xmlHeader->language, $productId);

    //Get the image list
    $commentArray = $this->getComment($productId, $this->productName);
    //Get the seasons list
    $seasonsArray = $this->getSeasons($productId, $this->productName, $xmlHeader->language);

    //Get the param1 list
    $param1Array = $this->getParam1($productId, $this->productName, $xmlHeader->language);

    //Get the param2 list
    $param2Array = $this->getParam2($productId, $this->productName, $xmlHeader->language);
    //Get the param3 list
    $param3Array = $this->getParam3($productId, $this->productName, $xmlHeader->language);

    //Get the supplement list
    $supplementArray = $this->getSupplements($productId, $this->productName, $xmlHeader->language);

    //get the tourismtype list
    $tourismTypeArray = $this->getTourismType($xmlHeader->language, $this->productName);

    //GEt the supplier data
    $supplierData = $this->getProductSupplier($object->supplier_id, $xmlHeader->language);

    //get the category list
    $categoryArray = $this->getcategory($xmlHeader->language, $this->productName);

    //Assign tha data to xml object
    $xmlHeader->data->addChild("id", $productId);
    //Multilanguage to name
    if(isset($langArray["product_name"])){
      $object->product_name = $langArray["product_name"];
    }
    $xmlHeader->data->addChild("name", $object->product_name);
    //Multilanguage to description
    if(isset($langArray["product_desc"])){
      $object->product_desc = $langArray["product_desc"];
    }
    //Multilanguage to disclaimer
    if(isset($langArray["disclaimer"])){
      $object->disclaimer = $langArray["disclaimer"];
    }
    $xmlHeader->data->addChild("disclaimer", $object->disclaimer);
    $xmlHeader->data->addChild("code", $object->product_code);
    $xmlHeader->data->addChild("description", $object->product_desc);
    $xmlHeader->data->addChild("latitude", $object->latitude);
    $xmlHeader->data->addChild("longitude", $object->longitude);
    //Multilanguage to duration
    if(isset($langArray["duration"])){
      $object->duration = $langArray["duration"];
    }
    $xmlHeader->data->addChild("duration", $object->days_total);
    $xmlHeader->data->addChild("duration_text", $object->duration);
    $xmlHeader->data->addChild("url",  base64_encode(trim($object->product_url)));
    $xmlHeader->data->addChild("featured", $object->featured);
    $xmlHeader->data->addChild("country");
    $xmlHeader->data->country->addChild("id", $object->country_id);
    $xmlHeader->data->country->addChild("name", $object->country_name);
    $xmlHeader->data->country->addChild("code", $object->country_code);
    $xmlHeader->data->addChild("city");
    $xmlHeader->data->city->addChild("id", $object->city_id);
    $xmlHeader->data->city->addChild("name", $object->city_name);
    $xmlHeader->data->city->addChild("code", $object->city_code);
    $xmlHeader->data->addChild("region");
    $xmlHeader->data->region->addChild("id", $object->region_id);
    $xmlHeader->data->region->addChild("name", $object->region_name);
    $xmlHeader->data->region->addChild("code", $object->region_code);
    $xmlHeader->data->addChild("category");
    $xmlHeader->data->category->addChild("id",$object->category_id);
    $xmlHeader->data->category->addChild("name",$categoryArray[$object->category_id]);

    $xmlHeader->data->addChild("supplier");
    $xmlHeader->data->supplier->addChild("id", $supplierData->supplier_id);
    $xmlHeader->data->supplier->addChild("name", $supplierData->supplier_name);
    $xmlHeader->data->supplier->addChild("code", $supplierData->supplier_code);
    $xmlHeader->data->supplier->addChild("phone", $supplierData->phone);
    $xmlHeader->data->supplier->addChild("fax", $supplierData->fax);
    $xmlHeader->data->supplier->addChild("url", $supplierData->url);
    $xmlHeader->data->supplier->addChild("email", $supplierData->email);
    $xmlHeader->data->supplier->addChild("country", $supplierData->country_name);
    $xmlHeader->data->supplier->addChild("city", $supplierData->city_name);

    //se obtienen los tipos de turismo en un arreglo
    $tourismTypeParts = explode(",", $object->tourismtype);
    $xmlHeader->data->addChild("tourismtype");
    $contTourism = 0;
    //Se recorren los tipos de turismo y se agregan al objeto
    for($i=0; $i<count($tourismTypeParts); $i++){
      if($tourismTypeArray[$tourismTypeParts[$i]]!=""){
        $xmlHeader->data->tourismtype->addChild("item");
        $xmlHeader->data->tourismtype->item[$contTourism]->addChild("id", $tourismTypeParts[$i]);
        $xmlHeader->data->tourismtype->item[$contTourism]->addChild("name", $tourismTypeArray[$tourismTypeParts[$i]]);
        $contTourism++;
      }
    }


    $xmlHeader->data->addChild("details");
    $contDetails=0;
    if($object->tag_name1!="" && $object->tag_content1!=""){
      //Multilanguage to tag_name1
      if(isset($langArray["tag_name1"])){
        $object->tag_name1 = $langArray["tag_name1"];
      }
      //Multilanguage to tag_content1
      if(isset($langArray["tag_content1"])){
        $object->tag_content1 = $langArray["tag_content1"];
      }
      $xmlHeader->data->details->addChild("tag");
      $xmlHeader->data->details->tag[$contDetails]->addChild("name", base64_encode($object->tag_name1));
      $xmlHeader->data->details->tag[$contDetails]->addChild("content", base64_encode($object->tag_content1));
      $contDetails++;
    }
    if($object->tag_name2!="" && $object->tag_content2!=""){
      //Multilanguage to tag_name2
      if(isset($langArray["tag_name2"])){
        $object->tag_name2 = $langArray["tag_name2"];
      }
      //Multilanguage to tag_content1
      if(isset($langArray["tag_content2"])){
        $object->tag_content2 = $langArray["tag_content2"];
      }
      $xmlHeader->data->details->addChild("tag");
      $xmlHeader->data->details->tag[$contDetails]->addChild("name", base64_encode($object->tag_name2));
      $xmlHeader->data->details->tag[$contDetails]->addChild("content", base64_encode($object->tag_content2));
      $contDetails++;
    }
    if($object->tag_name3!="" && $object->tag_content3!=""){
      //Multilanguage to tag_name3
      if(isset($langArray["tag_name3"])){
        $object->tag_name3 = $langArray["tag_name3"];
      }
      //Multilanguage to tag_content3
      if(isset($langArray["tag_content3"])){
        $object->tag_content3 = $langArray["tag_content3"];
      }
      $xmlHeader->data->details->addChild("tag");
      $xmlHeader->data->details->tag[$contDetails]->addChild("name", base64_encode($object->tag_name3));
      $xmlHeader->data->details->tag[$contDetails]->addChild("content", base64_encode($object->tag_content3));
      $contDetails++;
    }
    if($object->tag_name4!="" && $object->tag_content4!=""){
      //Multilanguage to tag_name2
      if(isset($langArray["tag_name4"])){
        $object->tag_name4 = $langArray["tag_name4"];
      }
      //Multilanguage to tag_content1
      if(isset($langArray["tag_content4"])){
        $object->tag_content4 = $langArray["tag_content4"];
      }
      $xmlHeader->data->details->addChild("tag");
      $xmlHeader->data->details->tag[$contDetails]->addChild("name", base64_encode($object->tag_name4));
      $xmlHeader->data->details->tag[$contDetails]->addChild("content", base64_encode($object->tag_content4));
      $contDetails++;
    }
    if($object->tag_name5!="" && $object->tag_content5!=""){
      //Multilanguage to tag_name2
      if(isset($langArray["tag_name5"])){
        $object->tag_name5 = $langArray["tag_name5"];
      }
      //Multilanguage to tag_content1
      if(isset($langArray["tag_content5"])){
        $object->tag_content5 = $langArray["tag_content5"];
      }
      $xmlHeader->data->details->addChild("tag");
      $xmlHeader->data->details->tag[$contDetails]->addChild("name", base64_encode($object->tag_name5));
      $xmlHeader->data->details->tag[$contDetails]->addChild("content", base64_encode($object->tag_content5));
      $contDetails++;
    }
    if($object->tag_name6!="" && $object->tag_content6!=""){
      //Multilanguage to tag_name2
      if(isset($langArray["tag_name6"])){
        $object->tag_name6 = $langArray["tag_name6"];
      }
      //Multilanguage to tag_content1
      if(isset($langArray["tag_content6"])){
        $object->tag_content6 = $langArray["tag_content6"];
      }
      $xmlHeader->data->details->addChild("tag");
      $xmlHeader->data->details->tag[$contDetails]->addChild("name", base64_encode($object->tag_name6));
      $xmlHeader->data->details->tag[$contDetails]->addChild("content",base64_encode($object->tag_content6));
      $contDetails++;
    }
    $xmlHeader->data->addChild("images");
    $contImages=0;
    foreach($imageArray as $image){
      $xmlHeader->data->images->addChild("image");
      $xmlHeader->data->images->image[$contImages]->addChild("id", $image->file_id);
      $xmlHeader->data->images->image[$contImages]->addChild("url", $image->file_url);
      $contImages++;
    }
    $xmlHeader->data->addChild("comments");
    $contComment=0;
    if(is_array($commentArray)){
      foreach($commentArray as $comment){
        $xmlHeader->data->comments->addChild("comment");
        $xmlHeader->data->comments->comment[$contComment]->addChild("text",$comment->comment_text);
        $xmlHeader->data->comments->comment[$contComment]->addChild("rate",$comment->comment_rate);
        $xmlHeader->data->comments->comment[$contComment]->addChild("created_date",$comment->created);
        $xmlHeader->data->comments->comment[$contComment]->addChild("user",$comment->created_by);
        $contComment++;
      }
    }

    $xmlHeader->data->addChild("seasons");
    $contSeason=-1;
    $prevSeason = 0;
    $contDate = 0;
    foreach($seasonsArray as $season){
      if($prevSeason!=$season->season_id){
        $contSeason++;
        $xmlHeader->data->seasons->addChild("season");
        $xmlHeader->data->seasons->season[$contSeason]->addChild("name", $season->season_name);
        $xmlHeader->data->seasons->season[$contSeason]->addChild("special", $season->is_special);
        $xmlHeader->data->seasons->season[$contSeason]->addChild("day");

        $xmlHeader->data->seasons->season[$contSeason]->day->addChild("d1", $season->day1);
        $xmlHeader->data->seasons->season[$contSeason]->day->addChild("d2", $season->day2);
        $xmlHeader->data->seasons->season[$contSeason]->day->addChild("d3", $season->day3);
        $xmlHeader->data->seasons->season[$contSeason]->day->addChild("d4", $season->day4);
        $xmlHeader->data->seasons->season[$contSeason]->day->addChild("d5", $season->day5);
        $xmlHeader->data->seasons->season[$contSeason]->day->addChild("d6", $season->day6);
        $xmlHeader->data->seasons->season[$contSeason]->day->addChild("d7", $season->day7);
        $xmlHeader->data->seasons->season[$contSeason]->addChild("dates");
        $prevSeason = $season->season_id;
        $contDate=0;
      }
      $xmlHeader->data->seasons->season[$contSeason]->dates->addChild("date");
      $xmlHeader->data->seasons->season[$contSeason]->dates->date[$contDate]->addChild("start", $season->start_date);
      $xmlHeader->data->seasons->season[$contSeason]->dates->date[$contDate]->addChild("end", $season->end_date);
      $contDate++;
    }
    $xmlHeader->data->addChild("params1");
    $contParams=0;

    foreach($param1Array as $param){
      $xmlHeader->data->params1->addChild("param");
      $xmlHeader->data->params1->param[$contParams]->addChild("id", $param->param1_id);
      $xmlHeader->data->params1->param[$contParams]->addChild("name", $param->param1_name);
      $xmlHeader->data->params1->param[$contParams]->addChild("value", $param->value);
      $xmlHeader->data->params1->param[$contParams]->addChild("capacity", $param->capacity);
      $xmlHeader->data->params1->param[$contParams]->addChild("description", $param->description);
      $contParams++;
    }

    $xmlHeader->data->addChild("params2");
    $contParams=0;
    foreach($param2Array as $param){
      $xmlHeader->data->params2->addChild("param");
      $xmlHeader->data->params2->param[$contParams]->addChild("id", $param->param2_id);
      $xmlHeader->data->params2->param[$contParams]->addChild("name", $param->param2_name);
      $xmlHeader->data->params2->param[$contParams]->addChild("value", $param->value);
      $xmlHeader->data->params2->param[$contParams]->addChild("capacity", $param->capacity);
      $contParams++;
    }

    $xmlHeader->data->addChild("params3");
    $contParams=0;
    foreach($param3Array as $param){
      $xmlHeader->data->params3->addChild("param");
      $xmlHeader->data->params3->param[$contParams]->addChild("id", $param->param3_id);
      $xmlHeader->data->params3->param[$contParams]->addChild("name", $param->param3_name);
      $xmlHeader->data->params3->param[$contParams]->addChild("value", $param->value);
      $xmlHeader->data->params3->param[$contParams]->addChild("capacity", $param->capacity);
      $contParams++;
    }

    $xmlHeader->data->addChild("supplements");
    $contParams=0;
    foreach($supplementArray as $supplement){
      $xmlHeader->data->supplements->addChild("supplement");
      $xmlHeader->data->supplements->supplement[$contParams]->addChild("id", $supplement->supplement_id);
      $xmlHeader->data->supplements->supplement[$contParams]->addChild("name", $supplement->supplement_name);
      $xmlHeader->data->supplements->supplement[$contParams]->addChild("code", $supplement->supplement_code);
      $xmlHeader->data->supplements->supplement[$contParams]->addChild("image", $supplement->imageurl);
      $xmlHeader->data->supplements->supplement[$contParams]->addChild("description", $supplement->description);
      $xmlHeader->data->supplements->supplement[$contParams]->addChild("apply_once", $supplement->apply_once);
      $contParams++;
    }

    return $xmlHeader;
  }

  /**
   *
   * This function return a xml with rates
   * @param $requestXML
   * @param $xmlHeader
   */
  function rates($requestXML, $xmlHeader){
    //Establece la lista de monedas
    $this->setCurrencyList($requestXML->language);
    //Instancia la conexion
    $objConection = new DataConection();
    //set the agency code
    $this->codeAgency = $requestXML->agency_code;
    //Se obtienen el numero del dia
    $numberWeek = $this->getNumberWeekDay($requestXML->checkin_date);
    //Llama al procedimiento almacenado enviandole los parametros
    $sql = "CALL RatePlan(
			".$this->serviceConfig->applyMarkup.",
			".$requestXML->product_id.",
			'".$requestXML->checkin_date."',
			".$this->agency->idagency_group.",
			".$numberWeek."
		)";
    //se envia la consulta a la clase conexion
    $objConection->setQuery($sql);
    //Se ejecuta la consulta
    $result = $objConection->loadObjectList();

    //Se obtienen las tarifas de los suplementos
    $rateSupplement = $this->getSupplementPlan($requestXML, $numberWeek);
    //Obtiene el xml organizado para enviarlo a la peticion
    $xmlObject = $this->objectToXmlRate($xmlHeader, $result, $rateSupplement, $requestXML->product_id, $requestXML->checkin_date, $requestXML->currency);
    return $xmlObject;
  }

  /**
   *
   * This function organize the data into xml
   * @param $xmlHeader
   * @param $object
   * @param $productId
   * @param $checkin_date
   * @param $checkout_date
   * @param $currencyId
   */
  function objectToXmlRate($xmlHeader, $object, $rateSupplement, $productId, $checkin_date, $currencyId){
    $rateData = array();
    $rateDataChild = array();
    $arrayRate = array();
    $arrayChild = array();

    //Obtengo el listado de impuestos
    $arrayTax = $this->getProductTaxes($this->productName, $xmlHeader->language, $productId);

    //Obtengo el stock correnpondiente al rango de fechas seleccionadas
    $arrayStock = $this->getProductStock($this->productName, $productId, $checkin_date);
    //die(print_r($object));
    //las siguientes dos variables me ayudan a controlar el flujo de las temporadas especiales
    //como son las primeras que llegan apenas se asigna el valor de la fecha se establece el valor de la variable correspondiente en 1
    $arrayPriceChild=array();
    $arrayPriceAdult=array();
    $currencyDefault = "";

    //Organizo los precios por cada uno de los parametros de tarificacion y obtengo el total de la tarifa por las noches seleccionadas
    foreach($object as $rate){
      $currencyProduct = $rate->currency_id;
      //Asigno el markup del producto
      $markup = $rate->markup;
      //Valido si se trata de un niño
      if($rate->is_child==1){
        //Establesco el precio por los parametros de tarificacion
        if(!isset($rateDataChild[$rate->param1][$rate->param2][$rate->param3])){//Si no se ha establecido el precio de adulto para la fecha correspondiente se puede entrar a la validacion
          $rateDataChild[$rate->param1][$rate->param2][$rate->param3] = $rate->price;
        }
      }else{
        //Establesco el precio por los parametros de tarificacion
        if(!isset($rateData[$rate->param1][$rate->param2][$rate->param3])){//Si no se ha establecido el precio de adulto para la fecha correspondiente se puede entrar a la validacion
          $rateData[$rate->param1][$rate->param2][$rate->param3] = $rate->price;
        }
      }

    }

    $arraySupplementsDate = array();
    //Arreglo donde se guarda el markup del suplemento
    $arrayMarkupSupplement = array();
    //Organizo los precios por cada uno de los suplementos

    foreach($rateSupplement as $rate){
      $arrayMarkupSupplement[$rate->supplement_id] = $rate->markup;
      if(!isset($arraySupplementsType2[$rate->supplement_id])){
        $arraySupplementsType2[$rate->supplement_id] = $rate->amount;
      }
    }


    //Asigno los datos al xml
    $xmlHeader->addChild("data");
    $xmlHeader->data->addChild("rates");
    $xmlHeader->data->addChild("group", $this->agency->group);
    $xmlHeader->data->addChild("groupCode", $this->agency->group_code);
    $contRates = 0;
    $dataCurrency=array();
    //$markup = $this->getMarkup($productId);

    //Organizo el listado de tarifas
    foreach($rateData as $param1 => $arrayParam1){
      foreach($arrayParam1 as $param2=>$arrayParam2){
        foreach($arrayParam2 as $param3=>$value){
          //Obtengo el precio con el formato de moneda
          $price = $this->getTheCurrencyValue($value, $currencyId, $currencyProduct, $markup);
          //Valido que no hayan quedado precios en 0
          if($price->price>0){
            $xmlHeader->data->rates->addChild("rate");
            $xmlHeader->data->rates->rate[$contRates]->addChild("param1", $param1);
            $xmlHeader->data->rates->rate[$contRates]->addChild("param2", $param2);
            $xmlHeader->data->rates->rate[$contRates]->addChild("param3", $param3);

            //Asigno el currency que se va a utilizar en todo el producto
            if($currencyDefault==""){
              $currencyDefault = $price;
            }
            if(empty($dataCurrency)){
              $dataCurrency=$price;
            }
            $totalTax = 0;
            $totalIva = 0;
            $countTaxes = 0;
            $xmlHeader->data->rates->rate[$contRates]->addChild("taxes");
            foreach($arrayTax as $tax){
              if($tax->iva==1){
                $totalIva += $price->price*$tax->tax_value;
              }
              $totalTax += $price->price*$tax->tax_value;
              $xmlHeader->data->rates->rate[$contRates]->taxes->addChild("tax");
              $xmlHeader->data->rates->rate[$contRates]->taxes->tax[$countTaxes]->addChild("id", $tax->tax_id);
              $xmlHeader->data->rates->rate[$contRates]->taxes->tax[$countTaxes]->addChild("value", $price->price*$tax->tax_value);
              $countTaxes++;
            }
            $xmlHeader->data->rates->rate[$contRates]->addChild("price", $price->price);
            $xmlHeader->data->rates->rate[$contRates]->addChild("totalTax", $this->roundValue($totalTax, $price->approx));
            $xmlHeader->data->rates->rate[$contRates]->addChild("totalIva", $this->roundValue($totalIva, $price->approx));
            $xmlHeader->data->rates->rate[$contRates]->addChild("totalPrice", $this->roundValue($price->price+$totalTax, $price->approx));
            $xmlHeader->data->rates->rate[$contRates]->addChild("markup_porcentage", $price->markup);
            $xmlHeader->data->rates->rate[$contRates]->addChild("markup_value", $this->roundValue($price->markupValue, $price->approx));
            $contRates++;
          }
        }
      }
    }
    $xmlHeader->data->addChild("childs");
    $contRates=0;
    //Organizo el listado de precio para ninos
    foreach($rateDataChild as $param1=>$arrayParam1){
      foreach($arrayParam1 as $param2=>$arrayParam2){
        foreach($arrayParam2 as $param3=>$value){

          $xmlHeader->data->childs->addChild("child");
          $xmlHeader->data->childs->child[$contRates]->addChild("param1", $param1);
          $xmlHeader->data->childs->child[$contRates]->addChild("param2", $param2);
          $xmlHeader->data->childs->child[$contRates]->addChild("param3", $param3);
          $price = $this->getTheCurrencyValue($value, $currencyId, $currencyProduct, $markup);
          $totalTax = 0;
          $totalIva = 0;
          $countTaxes=0;
          $xmlHeader->data->childs->child[$contRates]->addChild("taxes");
          foreach($arrayTax as $tax){
            if($tax->iva==1){
              $totalIva += $price->price*$tax->tax_value;
            }
            $totalTax += $price->price*$tax->tax_value;
            $xmlHeader->data->childs->child[$contRates]->taxes->addChild("tax");
            $xmlHeader->data->childs->child[$contRates]->taxes->tax[$countTaxes]->addChild("id", $tax->tax_id);
            $xmlHeader->data->childs->child[$contRates]->taxes->tax[$countTaxes]->addChild("value", $price->price*$tax->tax_value);
            $countTaxes++;
          }
          $xmlHeader->data->childs->child[$contRates]->addChild("price", $price->price);
          $xmlHeader->data->childs->child[$contRates]->addChild("totalTax", $this->roundValue($totalTax, $price->approx));
          $xmlHeader->data->childs->child[$contRates]->addChild("totalIva", $this->roundValue($totalIva, $price->approx));
          $xmlHeader->data->childs->child[$contRates]->addChild("totalPrice", $this->roundValue($price->price+$totalTax, $price->approx));
          $xmlHeader->data->childs->child[$contRates]->addChild("markup_porcentage", $price->markup);
          $xmlHeader->data->childs->child[$contRates]->addChild("markup_value",  $this->roundValue($price->markupValue, $price->approx));
          $contRates++;

        }
      }
    }
    $xmlHeader->data->addChild("supplements");
    $contRates=0;

    //En este arreglo organizo el listado de suplementos de tipo 2 en el cual se encuentran los precios completos por todo el plan
    if(isset($arraySupplementsType2) && count($arraySupplementsType2)>0){
      foreach ($arraySupplementsType2 as $supplementId=>$price){
        $xmlHeader->data->supplements->addChild("supplement");
        //Obtengo el markup por suplemento
        $markupSupplement = $arrayMarkupSupplement[$supplementId];
        //Obtengo el listado de impuestos por suplemento
        $arrayTaxSupplement = $this->getSupplementTaxes($this->productName, $xmlHeader->language, $productId,$supplementId );
        $xmlHeader->data->supplements->supplement[$contRates]->addChild("id", $supplementId);
        $xmlHeader->data->supplements->supplement[$contRates]->addChild("type", 2);
        $price = $this->getTheCurrencyValue($price, $currencyId, $currencyProduct, $markupSupplement);
        //Recorro el listado de impuestos para asignarselo al precio del suplemento
        $totalTax = 0;
        $countTax = 0;
        $totalIva = 0;
        $xmlHeader->data->supplements->supplement[$contRates]->addChild("taxes");
        foreach($arrayTaxSupplement as $tax){
          $xmlHeader->data->supplements->supplement[$contRates]->taxes->addChild("tax");
          $xmlHeader->data->supplements->supplement[$contRates]->taxes->tax[$countTax]->addChild("id", $tax->tax_id);
          $xmlHeader->data->supplements->supplement[$contRates]->taxes->tax[$countTax]->addChild("name", $tax->tax_name);
          $xmlHeader->data->supplements->supplement[$contRates]->taxes->tax[$countTax]->addChild("code", $tax->tax_code);
          $xmlHeader->data->supplements->supplement[$contRates]->taxes->tax[$countTax]->addChild("value", $tax->tax_value);
          $xmlHeader->data->supplements->supplement[$contRates]->taxes->tax[$countTax]->addChild("iva", $tax->iva);
          $xmlHeader->data->supplements->supplement[$contRates]->taxes->tax[$countTax]->addChild("price", $price->price*$tax->tax_value);
          if($tax->iva==1){
            $totalIva += $price->price*$tax->tax_value;
          }
          $totalTax += $price->price*$tax->tax_value;
          $countTax++;
        }
        $xmlHeader->data->supplements->supplement[$contRates]->addChild("price", $price->price);
        $xmlHeader->data->supplements->supplement[$contRates]->addChild("totalTax", $this->roundValue($totalTax, $price->approx));
        $xmlHeader->data->supplements->supplement[$contRates]->addChild("totalIva", $this->roundValue($totalIva, $price->approx));
        $xmlHeader->data->supplements->supplement[$contRates]->addChild("totalPrice",  $this->roundValue($totalTax+$price->price, $price->approx));
        $xmlHeader->data->supplements->supplement[$contRates]->addChild("markup_porcentage", $price->markup);
        $xmlHeader->data->supplements->supplement[$contRates]->addChild("markup_value",  $this->roundValue($price->markupValue, $price->approx));
        $contRates++;
      }
    }
    $xmlHeader->data->addChild("taxes");
    $contRates=0;
    //Recorro el listado de impuestos y los agrego al xml
    foreach($arrayTax as $tax){
      $xmlHeader->data->taxes->addChild("tax");
      $xmlHeader->data->taxes->tax[$contRates]->addChild("id", $tax->tax_id);
      $xmlHeader->data->taxes->tax[$contRates]->addChild("name", $tax->tax_name);
      $xmlHeader->data->taxes->tax[$contRates]->addChild("code", $tax->tax_code);
      $xmlHeader->data->taxes->tax[$contRates]->addChild("value", $tax->tax_value);
      $xmlHeader->data->taxes->tax[$contRates]->addChild("iva", $tax->iva);
      $contRates++;
    }
    $xmlHeader->data->addChild("stocks");
    $contStock=-1;
    $prevParam = 0;
    //Recorro el listado de stock para asociarlo a la respuesta del servicio
    if(is_array($arrayStock)){
      foreach($arrayStock as $stock){
        if($prevParam!=$stock->param_id){
          $prevParam=$stock->param_id;
          $contStock++;
          $contDate = 0;
          $xmlHeader->data->stocks->addChild("stock");
          $xmlHeader->data->stocks->stock[$contStock]->addChild("param", $stock->param_id);
          $xmlHeader->data->stocks->stock[$contStock]->addChild("dates");
        }
        $xmlHeader->data->stocks->stock[$contStock]->dates->addChild("date");
        $xmlHeader->data->stocks->stock[$contStock]->dates->date[$contDate]->addChild("day", $stock->day);
        $xmlHeader->data->stocks->stock[$contStock]->dates->date[$contDate]->addChild("quantity", $stock->quantity);
        $contDate++;
      }
    }

    $xmlHeader->data->addChild("currency");
    $xmlHeader->data->currency->addChild("id", $currencyDefault->currency_id);
    $xmlHeader->data->currency->addChild("name", $currencyDefault->currency_name);
    $xmlHeader->data->currency->addChild("symbol", $currencyDefault->symbol);
    $xmlHeader->data->currency->addChild("trm", $currencyDefault->trm);
    return $xmlHeader;

  }

  function booking($requestXML, $xmlHeader){
    $resultBoolean = true;
    //Recorro el listado de habitaciones para poder descontar
    foreach($requestXML->rooms->room as $room){
      //Instancia la conexion, se debe instanciar por cada llamado del stored procedure debido a los problemas de consultas que presenta
      $objConection = new DataConection();
      //Llama al procedimiento almacenado enviandole los parametros
      $sql = "CALL BookingPlan(".$requestXML->product_id.", ".$room->param_id.", '".$requestXML->checkin_date."', ".$room->quantity." )";
      //se envia la consulta a la clase conexion
      $objConection->setQuery($sql);
      //Se ejecuta la consulta
      $result = $objConection->loadObject();
      if($result->result=='false'){
        $resultBoolean = false;
      }
    }
    //Organizo el objeto xml para que sea retornado
    $xmlHeader->addChild("data");
    $xmlHeader->data->addChild("status", 1);
    $xmlHeader->data->addChild("response");
    //Si se logra descontar se envia como respuesta la reserva confirmada para que se pueda realizar el pago
    if($resultBoolean){
      $xmlHeader->data->response->addChild("id", 1);
      $xmlHeader->data->response->addChild("name", "The reservation is confirmed");
    }else{
      $xmlHeader->data->response->addChild("id", 2);
      $xmlHeader->data->response->addChild("name", "The reservation is not confirmed");
    }
    return $xmlHeader;
  }

  private function getSupplementPlan($requestXML, $dayOfWeek){
    //Instancia la conexion
    $objConection = new DataConection();
    //Llama al procedimiento almacenado enviandole los parametros
    $sql = "CALL GetSupplementRatePlan(
			".$this->serviceConfig->applyMarkup.",
			".$requestXML->product_id.",
			'".$requestXML->checkin_date."',
			".$dayOfWeek.",
			".$this->agency->idagency_group."
		)";

    //se envia la consulta a la clase conexion
    $objConection->setQuery($sql);
    //Se ejecuta la consulta
    $result = $objConection->loadObjectList();
    //die(print_r($sql));
    return $result;
  }
  /**
   *
   * This function return the list of amenities
   * @param string $language
   * @param int $productId
   */
  private function getAmenities($language, $productId){
    $objConection = new DataConection();
    $sql = "CALL GetAmenity('".$language."', ".$productId.")";
    $objConection->setQuery($sql);
    $listAmenity = $objConection->loadObjectList();
    return $listAmenity;
  }



  /**
   *
   * This function return the value of the markup per product
   * falta definir el procedimiento
   * @param $productId
   */
  private function getMarkup($product){

    return 0;
  }

  /**
   *
   * This function return the value of the markup per supplement
   * falta definir el procedimiento
   * @param $productId
   */
  private function getSupplementMarkup($supplementId){
    return 0;
  }

  /**
   *
   * This function find the avaible cities with rates
   * @param $requestXML
   * @param $xmlHeader
   */
  function findByCity($requestXML, $xmlHeader){
    //Establece la lisa de monedas
    $this->setCurrencyList($requestXML->language);
    //Instancia la conexion
    $objConection = new DataConection();
    //Establece los datos de la busqueda en la etiqueta search
    $seach = $requestXML->search;
    //set the agency code
    $this->codeAgency = $requestXML->agency_code;
    //Valido si existe el parametro destacado
    if(!isset($seach->related) || $seach->related==""){
      $seach->related = "2";
    }
    //Valido si existe el parametro total de items, si no establesco el configurado
    if(!isset($seach->total_items) || $seach->total_items==""){
      $seach->total_items = $this->serviceConfig->totalItems;
    }
    //Llama al procedimiento almacenado enviandole los parametros
    $sql = "CALL FindPlanCities(
						".$this->serviceConfig->applyMarkup.",
						'".$seach->checkin_date."',
						".((isset($seach->tourismtype) && $seach->tourismtype!="")?$seach->tourismtype:0).",
						".$this->agency->idagency_group.",
						".$this->userAccess.",
						".$seach->related.",
						".$seach->total_items."
					);";

    //se envia la consulta a la clase conexion
    $objConection->setQuery($sql);
    //Se ejecuta la consulta
    $result = $objConection->loadObjectList();

    if(!isset($seach->currency) || $seach->currency==""){
      $seach->currency = 0;
    }
    if(count($result)>0){
      //Obtiene el xml organizado para enviarlo a la peticion
      $xmlObject = $this->objectToXmlFindCities($xmlHeader, $result, $seach->currency);
      return 	$xmlObject;
    }else{
      return($this->_objError->handleObjectError(5));
    }
  }

  /**
   *
   * This function organize de city list
   * @param $xmlHeader
   * @param $result
   * @param $currency
   */
  private function objectToXmlFindCities($xmlHeader, $object, $currencyId){
    $xmlHeader->addChild("products");
    $countItems = 0;
    foreach($object as $objectItem){
      $xmlHeader->products->addChild("product");

      $xmlHeader->products->product[$countItems]->addChild("country");
      $xmlHeader->products->product[$countItems]->country->addChild("id",$objectItem->country_id);
      $xmlHeader->products->product[$countItems]->country->addChild("name",$objectItem->country_name);
      $xmlHeader->products->product[$countItems]->addChild("city");
      $xmlHeader->products->product[$countItems]->city->addChild("id",$objectItem->city_id);
      $xmlHeader->products->product[$countItems]->city->addChild("name",$objectItem->city_name);

      $xmlHeader->products->product[$countItems]->addChild("price");

      //Obtenemos la moneda en la cual se mostraran los resultados, ademas de los valores con los cambios de moneda calculados
      $objBasicPrice = $this->getTheCurrencyValue($objectItem->basic_price, $currencyId, $objectItem->currency_id, $objectItem->markup);
      $objPreviousPrice = $this->getTheCurrencyValue($objectItem->previous_value, $currencyId, $objectItem->currency_id, $objectItem->markup);

      $xmlHeader->products->product[$countItems]->price->addChild("currency");
      $xmlHeader->products->product[$countItems]->price->currency->addChild("id", $objBasicPrice->currency_id);
      $xmlHeader->products->product[$countItems]->price->currency->addChild("name", $objBasicPrice->currency_name);
      $xmlHeader->products->product[$countItems]->price->currency->addChild("symbol", $objBasicPrice->symbol);
      $xmlHeader->products->product[$countItems]->price->addChild("value",$objBasicPrice->price);
      $xmlHeader->products->product[$countItems]->price->addChild("previous_value",$objPreviousPrice->price);

      $countItems++;
    }
    return $xmlHeader;
  }



}