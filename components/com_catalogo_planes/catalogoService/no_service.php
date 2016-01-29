<?php

//error_reporting(E_ALL);
// Environment setup
define('DIR_ROOT', dirname(__FILE__));
$infraiz = DIR_ROOT."/../../../";
define('RAIZ_ROOT', $infraiz);
$isService = false;

//Call the library of soap
//require_once RAIZ_ROOT.'libraries/nusoap/lib/nusoap.php';
require_once DIR_ROOT.'/ServiceErrors.php';
require_once DIR_ROOT.'/ServiceConfig.php';
require_once DIR_ROOT.'/ControllerService.php';
require_once DIR_ROOT.'/DataConection.php';
//header("Content-Type: text/html;charset=utf-8");
class NoService{
  /**
   *
   * WebMethod queries the catalogo
   * @param string $data
   */
  function catalogo($value){
    $objectController = new ControllerService();
    $result = $objectController->parseXML(utf8_encode($value));
    $result = str_replace('<?xml version="1.0"?>', '', $result);
    return $result;
  }
}