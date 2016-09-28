<?php
class ServiceErrors{

  private static $instancia;
  private $_error;
  function __construct(){
    $this->_error = array(
    1=>"Se ha presentado un error en el servicio",
    2=>"El metodo es requerido",
    3=>"El tipo es requerido",
    4=>"La agencia no existe o no esta autorizada",
    5=>"No existe disponibilidad",
    6=>"La sesion no existe o ya caduco",
    7=>"La hora de entrega debe ser mayor a la hora de recogida"
    );
  }

  /**
   * Singleton function
   */
  public function getInstance(){
    if(!self::$instancia instanceof self){
      self::$instancia = new self;
    }
    return self::$instancia;
  }

  /**
   *
   * This function handle the error
   * @param $error
   */
  function handleXMLError($error, $message=""){
    $data = $this->getErrorObject($error, $message);
    return $data->asXML();
  }

  /**
   *
   * This function handle the error object
   * @param $error
   */
  function handleObjectError($error, $message=""){
    $data = $this->getErrorObject($error, $message);
    return $data;
  }

  private function getErrorObject($error, $message=""){
    $data = new SimpleXMLElement('<Data></Data>');
    $codError = 1;
    //Verify if exist the error
    if(isset($this->_error[$error])){
      $codError = $error;
      $messageError = $this->_error[$error];
    }
    if($message!=""){
      $messageError = $message;
    }
    $data->addChild("status", "0");
    $data->addChild("error");
    $data->error->addChild("code", $codError);
    $data->error->addChild("message", $messageError);
    return $data;
  }
}