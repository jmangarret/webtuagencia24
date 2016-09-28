<?php

require_once DIR_ROOT.'/products/Products.php';

/**
 *
 * This class controls the service call
 * @author andres.ramirez
 *
 */
class ControllerService{

  /**
   * Object of the error class
   */
  private $_objError;

  /**
   *
   * Object of the service config
   * @var object
   */
  private $serviceConfig;

  /**
   * Construct of the class
   */
  function __construct(){
    $this->_objError = ServiceErrors::getInstance();
    $this->serviceConfig = new ServiceConfig();
  }

  /**
   *
   * this function parse the request XML
   * @param $xml
   */
  function parseXML($xml){

    try{


      //Cargo los datos del xml en un objeto
      $objectXML = simplexml_load_string($xml);

      //Verifico si el metodo existe
      $methodName = $this->getMethodName($objectXML);
      if(!$methodName){
        return($this->_objError->handleXMLError(2));
      }

      //Verifico si el producto existe
      $typeName = $this->getTypeName($objectXML);
      if(!$typeName){
        return($this->_objError->handleXMLError(3));
      }
      $agency = null;
      //Si el parametro de configuracion indica que se va a utilizar validacion de agencias
      //Entro y realizo el proceso de validacion de agencias
      if($this->serviceConfig->agencyValidate==1){
        $this->validateSID($objectXML, $methodName);
        if(isset($_SESSION[(string)$objectXML->sid]["group_id"])){
          $agency->idagency = $_SESSION[(string)$objectXML->sid]["agency_id"];
          $agency->idagency_group = $_SESSION[(string)$objectXML->sid]["group_id"];
          $agency->group = $_SESSION[(string)$objectXML->sid]["group_name"];
          $agency->group_code = $_SESSION[(string)$objectXML->sid]["group_code"];
        }
      }



      //Si se utilizan agencias se realiza la verificacion por agencia
      if($this->serviceConfig->agencyValidate==1 && $methodName!="login"){
        //Valido si la agencia existe
        if($agency==null){
          return($this->_objError->handleXMLError(6));
        }
      }else{
        $agency->idagency_group = 1;
      }

      /*
       * Parametro del servicio que me indica si el usuario se encuentra logueado
       * 0 no logueado
       * 1 logueado
       * 2 administrador
       */

      /*$user_logged = 0;
       if(isset($objectXML->guest)){
       $user_logged = $objectXML->user_logged;
       }*/

      //Instance the file and the object
      require_once DIR_ROOT.'/products/'.ucfirst($typeName).'.php';
      $object = new $typeName();
      //get the response of the method
      $xmlHeader = $this->setResponseXML($typeName, $methodName, $objectXML->language);

      //Establesco la agencia
      $object->setAgency($agency);
      //Establesco el estado del usuario para realizar las consultas
      $object->setUserAccess((int)$objectXML->user_logged);

      //Llamo al metodo correspondiente
      $objResponse = $object->$methodName($objectXML, $xmlHeader);
      //Return the xml
      return  $objResponse->asXML();
    }catch(Exception $e){
      return($this->_objError->handleXMLError(1, $e->toString()));
    }

  }

  /**
   *
   * This function return the method name
   * @param $objectXML
   */
  function getMethodName($objectXML){
    $methodName = "";
    if(isset($objectXML->method)){
      $methodName = (string)$objectXML->method;
    }
    if($methodName==""){
      $methodName = false;
    }
    return $methodName;
  }

  /**
   *
   * This function return the type name
   * @param $objectXML
   */
  function getTypeName($objectXML){
    $type = "";
    if(isset($objectXML->type)){
      $type = (string)$objectXML->type;
    }
    if($type==""){
      $type = false;
    }
    return $type;
  }


  function getAgencyCode($objectXML){
    $agencyCode = "";
    $objProduct = new Products();
    if(isset($objectXML->agency_code)){
      $agencyCode = (string)$objectXML->agency_code;
      $agency = $objProduct->getAgency($agencyCode);
      if(count($agency)>0){
        $agencyCode = $agency;
      }else{
        $agencyCode = false;
      }
    }
    if($agencyCode==""){
      $agencyCode = false;
    }

    return $agencyCode;
  }

  /**
   *
   * This function set the xml to response
   * @param $object
   * @param $productName
   * @param $methodName
   */
  function setResponseXML($typeName, $methodName, $language){
    $data = new SimpleXMLElement('<Data></Data>');
    $data->addChild("product", $typeName);
    $data->addChild("method", $methodName);
    $data->addChild("language", $language);
    $data->addChild("status", 1);
    return $data;
  }

  /**
   *
   * This function validate if isset the session id
   * @param $objectXML
   * @param $methodName
   */
  function validateSID($objectXML, $methodName){
    GLOBAL $isService;
    //die(print_r($objectXML));
    if(!isset($objectXML->sid) || $objectXML->sid==""){
      if($methodName!="login"){
        return($this->_objError->handleXMLError(6));
      }
    }else{
      if($isService){
        //Si existe el SID inicio la session con este id
        session_id($objectXML->sid);
        //Inicio la variable de sesion
        session_start();
      }
      if(!isset($_SESSION[(string)$objectXML->sid])){
        $_SESSION[(string)$objectXML->sid] = array();
      }


    }
  }


}