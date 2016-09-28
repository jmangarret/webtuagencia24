<?php
/**
 *
 * This class have the methods of transfer
 * @author andres.ramirez
 *
 */
class Transfer extends Products{

  private $codeAgency;
  private $productName = "transfer";


  /**
   *
   * This function find the avaible transfers
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
    if(!isset($seach->type) || $seach->type==""){
      $type = 3;
    }else{
      if($seach->type==2 && $seach->checkin_date==$seach->checkout_date){
        $type = 1;
      }else{
        $type = $seach->type;
      }
    }
     
    //Valido si existe el parametro destacado
    if(!isset($seach->related) || $seach->related==""){
      $seach->related = "2";
    }
    //Valido si existe el parametro total de items, si no establesco el configurado
    if(!isset($seach->total_items) || $seach->total_items==""){
      $seach->total_items = $this->serviceConfig->totalItems;
    }
    //Llama al procedimiento almacenado enviandole los parametros
    $sql = "CALL FindTransfer(
						".$this->serviceConfig->applyMarkup.", 
						".((isset($seach->region) && $seach->region!="")?$seach->region:0).", 
						".((isset($seach->city) && $seach->city!="")?$seach->city:0).", 
						'".$seach->checkin_date."',
						'".$seach->checkout_date."',
						".((isset($seach->service_type) && $seach->service_type!="")?$seach->service_type:0).",
						".$type.",						
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
    //Valido que existan resultados
    if(count($result)>0){
      //Obtiene el xml organizado para enviarlo a la peticion
      $xmlObject = $this->objectToXmlAvailability($xmlHeader, $result, $seach->currency, $seach->type);
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
  private function objectToXmlAvailability($xmlHeader, $object, $currencyId, $transferType){
    $xmlHeader->addChild("products");
    $countItems = 0;
    //Get tha lang for the products
    $langArray = $this->getLangObjectByTable("cp_transfers_info", $xmlHeader->language);
    //get the zone list
    $zonesArray = $this->getZones($xmlHeader->language);
    //get the category list
    $categoryArray = $this->getcategory($xmlHeader->language, $this->productName, $transferType);

    //get the tourismtype list
    $tourismTypeArray = $this->getTourismType($xmlHeader->language, $this->productName);
    foreach($object as $objectItem){
      $xmlHeader->products->addChild("product");
      $xmlHeader->products->product[$countItems]->addChild("id", $objectItem->product_id);
      //Multilanguage to name
      if(isset($langArray[$objectItem->product_id]["product_name"])){
        $objectItem->product_name = $langArray[$objectItem->product_id]["product_name"];
      }
      $xmlHeader->products->product[$countItems]->addChild("name", $objectItem->product_name);
      //Multilanguage to description
      if(isset($langArray[$objectItem->product_id]["product_desc"])){
        $objectItem->product_desc = $langArray[$objectItem->product_id]["product_desc"];
      }
      $xmlHeader->products->product[$countItems]->addChild("description",$objectItem->product_desc);
      $xmlHeader->products->product[$countItems]->addChild("stars", $objectItem->stars);
      $xmlHeader->products->product[$countItems]->addChild("latitude", $objectItem->latitude);
      $xmlHeader->products->product[$countItems]->addChild("longitude", $objectItem->longitude);
      $xmlHeader->products->product[$countItems]->addChild("url", base64_encode(trim($objectItem->product_url)));
      $xmlHeader->products->product[$countItems]->addChild("image", $objectItem->image_url);
      $xmlHeader->products->product[$countItems]->addChild("code", $objectItem->product_code);
      $xmlHeader->products->product[$countItems]->addChild("rating", $objectItem->average_rating);
      $xmlHeader->products->product[$countItems]->addChild("featured", $objectItem->featured);
      	
      $xmlHeader->products->product[$countItems]->addChild("country");
      $xmlHeader->products->product[$countItems]->country->addChild("id",$objectItem->country_id);
      $xmlHeader->products->product[$countItems]->country->addChild("name",$objectItem->country_name);
      $xmlHeader->products->product[$countItems]->addChild("city");
      $xmlHeader->products->product[$countItems]->city->addChild("id",$objectItem->city_id);
      $xmlHeader->products->product[$countItems]->city->addChild("name",$objectItem->city_name);
      $xmlHeader->products->product[$countItems]->addChild("zone");
      $xmlHeader->products->product[$countItems]->zone->addChild("id",$objectItem->zone_id);
      $xmlHeader->products->product[$countItems]->zone->addChild("name",$zonesArray[$objectItem->zone_id]);
      $xmlHeader->products->product[$countItems]->addChild("categories");
      $categoryPart = explode(",", $objectItem->category_id);
      $countCategory = 0;
      foreach($categoryPart as $category){
        if(isset($categoryArray[$category])){
          $xmlHeader->products->product[$countItems]->categories->addChild("category");
          $xmlHeader->products->product[$countItems]->categories->category[$countCategory]->addChild("id",$category);
          $xmlHeader->products->product[$countItems]->categories->category[$countCategory]->addChild("name",$categoryArray[$category]);
          $countCategory++;
        }
      }
      	
      $xmlHeader->products->product[$countItems]->addChild("price");
      	
      	
      //Se obtiene el valor del markup para sumarselo a la tarifa
      $markup = $this->getMarkup($objectItem->product_id);
      	
      //Obtenemos la moneda en la cual se mostraran los resultados, ademas de los valores con los cambios de moneda calculados
      $objBasicPrice = $this->getTheCurrencyValue($objectItem->basic_price, $currencyId, $objectItem->currency_id, $objectItem->markup);
      $objPreviousPrice = $this->getTheCurrencyValue($objectItem->previous_value, $currencyId, $objectItem->currency_id, $objectItem->markup);
      	
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
   * This function return the details of the transfer
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
    $sql = "CALL DetailsTransfer(".$prodictId.", '".$requestXML->language."')";
    //se envia la consulta a la clase conexion
    $objConection->setQuery($sql);
    //Se ejecuta la consulta
    $result = $objConection->loadObject();
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
    $langArray = $this->getLangObjectByTableAndId("cp_transfers_info", $productId, $xmlHeader->language);
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
    //GEt the supplier data
    $supplierData = $this->getProductSupplier($object->supplier_id, $xmlHeader->language);
    //get the category list
    $categoryArray = $this->getcategoryTransfer($xmlHeader->language, $productId);

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
    $xmlHeader->data->addChild("description", $object->product_desc);
    //Multilanguage to additional_description
    if(isset($langArray["additional_description"])){
      $object->additional_description = $langArray["additional_description"];
    }
    $xmlHeader->data->addChild("additional_description", $object->additional_description);
    //Multilanguage to disclaimer
    if(isset($langArray["disclaimer"])){
      $object->disclaimer = $langArray["disclaimer"];
    }
    $xmlHeader->data->addChild("disclaimer", $object->disclaimer);
    $xmlHeader->data->addChild("code", $object->product_code);
    $xmlHeader->data->addChild("latitude", $object->latitude);
    $xmlHeader->data->addChild("longitude", $object->longitude);
    $xmlHeader->data->addChild("transfer_type", $object->transfer_type);
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
    $xmlHeader->data->addChild("categories");
    $countCategory = 0;
    foreach($categoryArray as $category){
      $xmlHeader->data->categories->addChild("category");
      $xmlHeader->data->categories->category[$countCategory]->addChild("id", $category->category_id);
      $xmlHeader->data->categories->category[$countCategory]->addChild("name", $category->category_name);
      $xmlHeader->data->categories->category[$countCategory]->addChild("transfer_type", $category->transfer_type);
      $countCategory++;
    }
    $xmlHeader->data->categories->addChild("categories");
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
    $xmlHeader->data->addChild("amenities");
    $contAmenities=0;
    foreach($amenityArray as $amenity){
      $xmlHeader->data->amenities->addChild("amenity");
      $xmlHeader->data->amenities->amenity[$contAmenities]->addChild("id", $amenity->amenity_id);
      $xmlHeader->data->amenities->amenity[$contAmenities]->addChild("name", $amenity->amenity_name);
      $xmlHeader->data->amenities->amenity[$contAmenities]->addChild("image", $amenity->imageurl);
      $contAmenities++;
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
      $xmlHeader->data->supplements->supplement[$contParams]->addChild("description", $supplement->description);
      $xmlHeader->data->supplements->supplement[$contParams]->addChild("code", $supplement->supplement_code);
      $xmlHeader->data->supplements->supplement[$contParams]->addChild("image", $supplement->imageurl);
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
    //Se obtienen el numero del dia de la fecha de recogida
    $numberCheckinDay = $this->getNumberWeekDay($requestXML->checkin_date);
    $numberCheckoutDay = 0;
     
    //Llama al procedimiento almacenado enviandole los parametros
    $sql = "CALL RateTransfer(
			".$this->serviceConfig->applyMarkup.", 
			".$requestXML->product_id.", 
			'".$requestXML->checkin_date."',			
			".$numberCheckinDay.",			
			".$this->agency->idagency_group." 
		)";		
    /*echo $sql;
     die();*/
    //se envia la consulta a la clase conexion
    $objConection->setQuery($sql);
    //Se ejecuta la consulta
    $result = $objConection->loadObjectList();
    //Obtengo los suplementos para la fecha de recogida
    $rateSupplementCheckin = $this->getSupplementTransfer($requestXML, $requestXML->checkin_date, $numberCheckinDay);
    //Arreglo con los costos de los suplementos de fecha de entrega
    $rateSupplementCheckout=array();
    //Si se trata de round trip se realiza la buscan los precios del viaje de vuelta
    if($requestXML->type_transfer==2){
      //Instancia la conexion
      $objConection = new DataConection();
      //Se obtienen el numero del dia de la fecha de llegada
      $numberCheckoutDay = $this->getNumberWeekDay($requestXML->checkout_date);
      //Llama al procedimiento almacenado enviandole los parametros
      $sqlReturn = "CALL RateTransfer(
				".$this->serviceConfig->applyMarkup.", 
				".$requestXML->product_id.", 
				'".$requestXML->checkout_date."',			
				".$numberCheckoutDay.",				
				".$this->agency->idagency_group." 
			)";						
      //se envia la consulta a la clase conexion
      $objConection->setQuery($sqlReturn);
      //Se ejecuta la consulta
      $resultReturn = $objConection->loadObjectList();
      //Obtengo los suplementos para la fecha de recogida
      $rateSupplementCheckout = $this->getSupplementTransfer($requestXML, $requestXML->checkout_date, $numberCheckoutDay);
      	
    }

    //Obtiene el xml organizado para enviarlo a la peticion
    $xmlObject = $this->objectToXmlRate($xmlHeader, $result, $resultReturn, $rateSupplementCheckin, $rateSupplementCheckout, $requestXML->product_id, $requestXML->checkin_date, $requestXML->checkout_date, $requestXML->currency, $requestXML->type_transfer);
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
  function objectToXmlRate($xmlHeader, $object, $objectReturn, $rateSupplementCheckin, $rateSupplementCheckout, $productId, $checkin_date, $checkout_date, $currencyId, $type){
    $rateData = array();
    $rateDataChild = array();
    $arrayRate = array();
    $arrayChild = array();

    //Obtengo el listado de impuestos
    $arrayTax = $this->getProductTaxes($this->productName, $xmlHeader->language, $productId);

    //Obtengo el stock correnpondiente al rango de fechas seleccionadas
    $arrayStock = $this->getProductStock($this->productName, $productId, $checkin_date);

    if($type==2){
      //Obtengo el stock correnpondiente al rango de fechas seleccionadas
      $arrayStockReturn = $this->getProductStock($this->productName, $productId, $checkout_date);
    }
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
      //Establesco el precio por los parametros de tarificacion
      if(!isset($rateData[$rate->param1][$rate->param2][$rate->param3])){//Si no se ha establecido el precio de adulto para la fecha correspondiente se puede entrar a la validacion
        $rateData[$rate->param1][$rate->param2][$rate->param3] = $rate->price;
      }
    }

    //Organizo igualmente los precios de la entrega
    if(is_array($objectReturn)){
      foreach($objectReturn as $rate){
        $currencyProduct = $rate->currency_id;
        //Asigno el markup del producto
        $markup = $rate->markup;
        //Valido si se trata de un niño
        //Establesco el precio por los parametros de tarificacion
        if(!isset($rateDataReturn[$rate->param1][$rate->param2][$rate->param3])){//Si no se ha establecido el precio de adulto para la fecha correspondiente se puede entrar a la validacion
          $rateDataReturn[$rate->param1][$rate->param2][$rate->param3] = $rate->price;
        }
      }
    }


    $arraySupplementsDate = array();
    //Arreglo donde se guarda el markup del suplemento
    $arrayMarkupSupplement = array();
    //Organizo los precios por cada uno de los suplementos de la fecha de salida
    if(is_array($rateSupplementCheckin)){
      foreach($rateSupplementCheckin as $rate){
        $arrayMarkupSupplement[$rate->supplement_id] = $rate->markup;
        if(!isset($arraySupplementsType2[$rate->supplement_id])){
          $arraySupplementsType2[$rate->supplement_id] = $rate->amount;
        }
      }
    }

    //Organizo los precios por cada uno de los suplementos de la fecha de regreso
    if(is_array($rateSupplementCheckout)){
      foreach($rateSupplementCheckout as $rate){
        $arrayMarkupSupplement[$rate->supplement_id] = $rate->markup;
        if(!isset($arraySupplementsType2Checkout[$rate->supplement_id])){
          $arraySupplementsType2Checkout[$rate->supplement_id] = $rate->amount;
        }
      }
    }



    //Asigno los datos al xml
    $xmlHeader->addChild("data");
    $xmlHeader->data->addChild("group", $this->agency->group);
    $xmlHeader->data->addChild("groupCode", $this->agency->group_code);
    $xmlHeader->data->addChild("rates");
    $contRates = 0;
    $dataCurrency=array();
    //$markup = $this->getMarkup($productId);
    	
    //Organizo el listado de tarifas
    foreach($rateData as $param1 => $arrayParam1){
      foreach($arrayParam1 as $param2=>$arrayParam2){
        foreach($arrayParam2 as $param3=>$value){
          //Si es un round trip
          if($type==2){
            //Valido que exista la tarifa en las dos fechas
            if(isset($rateDataReturn[$param1][$param2][$param3]) && $rateDataReturn[$param1][$param2][$param3]!=0){
              $value += $rateDataReturn[$param1][$param2][$param3];
            }else{
              continue;
            }
          }
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

    $xmlHeader->data->addChild("supplements");
    $contRates=0;

    //En este arreglo organizo el listado de suplementos de tipo 2 en el cual se encuentran los precios completos por todo el plan
    if(count($arraySupplementsType2)>0){
      foreach ($arraySupplementsType2 as $supplementId=>$price){
        //Si es un round trip
        if($type==2){
          //Valido que exista la tarifa en las dos fechas
          if(isset($arraySupplementsType2Checkout[$supplementId]) && $arraySupplementsType2Checkout[$supplementId]!=0){
            $price += $arraySupplementsType2Checkout[$supplementId];
          }else{
            continue;
          }
        }
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
    $contStock=0;
    $prevParam = 0;
    //Si es un round trip organizo el stock de la fecha de regreso
    if($type==2){
      $stockReturn = array();
      foreach($arrayStockReturn as $stock){
        $stockReturn[$stock->param_id] = $stock->quantity;
      }
    }
    //Recorro el listado de stock para asociarlo a la respuesta del servicio
    foreach($arrayStock as $stock){
      if($type==2){
        //Valido si existe stock para el parametro en la
        if(isset($stockReturn[$stock->param_id])){
          //Verifico si la cantidad de retorno es menor a la de recogida y la establesco en la respuesta del servicio
          if($stockReturn[$stock->param_id]<$stock->quantity){
            $stock->quantity = $stockReturn[$stock->param_id];
          }
        }else{
          continue;
        }
      }
      $xmlHeader->data->stocks->addChild("stock");
      $xmlHeader->data->stocks->stock[$contStock]->addChild("param", $stock->param_id);
      $xmlHeader->data->stocks->stock[$contStock]->addChild("quantity", $stock->quantity);
      $contStock++;
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
    //Instancia la conexion
    $objConection = new DataConection();

    if($requestXML->transfer_type==2 && !strcmp($requestXML->checkin_date, $requestXML->checkout_date)){
      $type = 1;
      $quantity = $requestXML->param->quantity*2;
    }else{
      $type = $requestXML->transfer_type;
      $quantity = $requestXML->param->quantity;
    }
     
    //Llama al procedimiento almacenado enviandole los parametros
    $sql = "CALL BookingTransfer(
			".$requestXML->product_id.", 
			".$requestXML->param->param_id.", 
			'".$requestXML->checkin_date."', 
			'".$requestXML->checkout_date."', 
			".$quantity.",
			".$type."
		)";		
    //se envia la consulta a la clase conexion
    $objConection->setQuery($sql);
    //Se ejecuta la consulta
    $result = $objConection->loadObject();
    if($result->result=='false'){
      $resultBoolean = false;
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

  private function getSupplementTransfer($requestXML, $checkoud_date, $numberCheckoutDay){
    //Instancia la conexion
    $objConection = new DataConection();
    //Llama al procedimiento almacenado enviandole los parametros
    $sql = "CALL GetSupplementRateTransfer(
			".$this->serviceConfig->applyMarkup.", 
			".$requestXML->product_id.", 
			'".$checkoud_date."',		 
			".$numberCheckoutDay.", 
			".$this->agency->idagency_group." 
		)";		
    //se envia la consulta a la clase conexion
    $objConection->setQuery($sql);
    //Se ejecuta la consulta
    $result = $objConection->loadObjectList();
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

  private function getcategoryTransfer($lang, $productId){
    $objConection = new DataConection();
    $sql = "CALL GetCategoryTransfer('".$lang."', ".$productId.")";
    $objConection->setQuery($sql);
    $listCategory = $objConection->loadObjectList();
    	
    return $listCategory;
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
    if(!isset($seach->type) || $seach->type==""){
      $type = 3;
    }else{
      if($seach->type==2 && $seach->checkin_date==$seach->checkout_date){
        $type = 1;
      }else{
        $type = $seach->type;
      }
    }
     
    //Valido si existe el parametro destacado
    if(!isset($seach->related) || $seach->related==""){
      $seach->related = "2";
    }
    //Valido si existe el parametro total de items, si no establesco el configurado
    if(!isset($seach->total_items) || $seach->total_items==""){
      $seach->total_items = $this->serviceConfig->totalItems;
    }
    //Llama al procedimiento almacenado enviandole los parametros
    $sql = "CALL FindTransferCities(
						".$this->serviceConfig->applyMarkup.", 
						'".$seach->checkin_date."',
						'".$seach->checkout_date."',						
						".$type.",
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
