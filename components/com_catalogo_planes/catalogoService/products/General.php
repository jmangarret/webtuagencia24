<?php
/**
 *
 * This class manage the general params
 * @author andres.ramirez
 *
 */
Class General extends Products{

  /**
   *
   * This function valid the login on the service
   * @param $requestXML
   * @param $xmlHeader
   */
  function login($requestXML, $xmlHeader){
    GLOBAL $isService;
    $agencyCode = (string)$requestXML->agency_code;
    $agency = $this->getAgency($agencyCode);
    //Obtengo la ip del cliente
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    //Si se requiere validacion de ip la realizo
    if($this->serviceConfig->ipValidate==1){

      //Desde la ip local se permite la consulta
      if($ipAddress!="127.0.0.1"){
        //Tomo las ip guardadas en la base de datos
        $ipAgencyParts = explode(",", $agency->ip_address);
        $valid = false;
        //Las recorro si existe alguna igual a la del cliente se establece en verdadero la validacion
        foreach($ipAgencyParts as $ipAgency){
          if(trim($ipAgency)==$ipAddress){
            $valid = true;
          }
        }
        //Si no paso la validacion la ip retorno un mensaje de error
        if(!$valid){
          return($this->_objError->handleObjectError(4));
        }
      }
    }
    //Valido si retorno algun grupo
    if(isset($agency->idagency_group)){
      if(!$this->validateActiveGroup($agency->idagency_group)){
        $agency->idagency_group = 1;
        $agency->group_name = "";
        $agency->group_code = "";
      }
      //Genero el id de session
      $sid = sha1(microtime()."CPSESSION");
      //Si existe el SID inicio la session con este id
      if($isService){
        session_id($sid);
        session_start();
      }

      //Subo el grupo actual a la sesion
      $_SESSION[(string)$sid]["group_id"] = $agency->idagency_group;
      $_SESSION[(string)$sid]["agency_id"] = $agency->id;
      $_SESSION[(string)$sid]["group_name"] = $agency->group_name;
      $_SESSION[(string)$sid]["group_code"] = $agency->group_code;

      //Adjunto el id de sesion a la respuesta
      $xmlHeader->addchild("sid", $sid);
      return $xmlHeader;
    }else{
      return($this->_objError->handleObjectError(4));
    }
  }


  /**
   *
   * This function return the currency list
   * @param $requestXML
   * @param $xmlHeader
   */
  function getCurrencyList($requestXML, $xmlHeader){
    $objConection = new DataConection();
    $sql = "CALL GetCurrencyList('".$requestXML->language."')";
    $objConection->setQuery($sql);
    $listCurrency = $objConection->loadObjectList();
    $xmlObject = $this->objectToXml($xmlHeader, $listCurrency, "currency");
    return $xmlObject;
  }

  /**
   *
   * This function return the avaible region per product list
   * @param $requestXML
   * @param $xmlHeader
   */
  function getAvaibleRegion($requestXML, $xmlHeader){
    $objConection = new DataConection();
    $nameProduct = (string)$requestXML->product->name;
    $sql = "CALL GetAvaibleRegions('".(string)$requestXML->language."', '".trim($nameProduct)."')";

    $objConection->setQuery($sql);
    $listRegion = $objConection->loadObjectList();
    $xmlObject = $this->objectToXml($xmlHeader, $listRegion, "region");
    return $xmlObject;
  }
  /**
   *
   * This function return the avaible countries per product list
   * @param $requestXML
   * @param $xmlHeader
   */
  function getAvaibleCountries($requestXML, $xmlHeader){
    $objConection = new DataConection();
    $nameProduct = (string)$requestXML->product->name;
    $sql = "CALL GetAvaibleCountries('".(string)$requestXML->language."', '".trim($nameProduct)."')";
    $objConection->setQuery($sql);
    $listCountries = $objConection->loadObjectList();
    $xmlObject = $this->objectToXml($xmlHeader, $listCountries, "countries");
    return $xmlObject;
  }
  /**
   *
   * This function return the avaible cities per product list
   * @param $requestXML
   * @param $xmlHeader
   */
  function getAvaibleCities($requestXML, $xmlHeader){
    $objConection = new DataConection();
    $nameProduct = (string)$requestXML->product->name;
    $regionId = (string)$requestXML->region->id;
    $countryId = (string)$requestXML->country->id;
    if($countryId=="")
    $countryId="0";
    if($regionId=="")
    $regionId="0";
    $sql = "CALL GetAvaibleCities('".(string)$requestXML->language."', '".trim($regionId)."' , '".trim($countryId)."' , '".trim($nameProduct)."')";
    $objConection->setQuery($sql);
    $listCity = $objConection->loadObjectList();
    $xmlObject = $this->objectToXml($xmlHeader, $listCity, "city");
    return $xmlObject;
  }

  /**
   *
   * This funcrion return the list of tourism type
   * @param $requestXML
   * @param $xmlHeader
   */
  function getTourismType($requestXML, $xmlHeader){
    $objConection = new DataConection();
    $nameProduct = (string)$requestXML->product->name;
    $isqs=((string)$requestXML->isquicksearch)?(string)$requestXML->isquicksearch:0;
    $sql = "CALL GetTourismType('".(string)$requestXML->language."', '".trim($nameProduct)."', '".$isqs."')";
    $objConection->setQuery($sql);
    $listTourismType = $objConection->loadObjectList();
    $xmlObject = $this->objectToXml($xmlHeader, $listTourismType, "tourismType");
    return $xmlObject;
  }

  /**
   *
   * This function return the list of category
   * @param $requestXML
   * @param $xmlHeader
   */
  function getCategory($requestXML, $xmlHeader){
    $objConection = new DataConection();
    $nameProduct = (string)$requestXML->product->name;
    $sql = "CALL GetCategory(
			'".(string)$requestXML->language."',
			'".trim($nameProduct)."',
			'".(string)$requestXML->product->transfer_type."'
			)";

    $objConection->setQuery($sql);
    $listTourismType = $objConection->loadObjectList();
    $xmlObject = $this->objectToXml($xmlHeader, $listTourismType, "category");
    return $xmlObject;
  }

  /**
   *
   * This function get the list of service type
   * @param $requestXML
   * @param $xmlHeader
   * @return object
   */
  function getServiceType($requestXML, $xmlHeader){
    $objConection = new DataConection();
    $nameProduct = (string)$requestXML->product->name;
    $sql = "CALL GetServiceType('".(string)$requestXML->language."')";
    $objConection->setQuery($sql);
    $listTourismType = $objConection->loadObjectList();
    $xmlObject = $this->objectToXml($xmlHeader, $listTourismType, "serviceType");
    return $xmlObject;
  }

  /**
   *
   * This function return the list of supplier
   * @param $requestXML
   * @param $xmlHeader
   */
  function getSupplier($requestXML, $xmlHeader){
    $objConection = new DataConection();
    $nameProduct = (string)$requestXML->product->name;
    $sql = "CALL GetSupplierList('".(string)$requestXML->language."', 3)";
    $objConection->setQuery($sql);
    $listTourismType = $objConection->loadObjectList();
    $xmlObject = $this->objectToXml($xmlHeader, $listTourismType, "supplier");
    return $xmlObject;
  }





}