<?php

//error_reporting(E_ALL);
// Environment setup
define('DIR_ROOT', dirname(__FILE__));
$infraiz = DIR_ROOT."/../../../";
define('RAIZ_ROOT', $infraiz);
$isService = true;

//Call the library of soap
require_once RAIZ_ROOT.'libraries/nusoap/lib/nusoap.php';
require_once DIR_ROOT.'/ServiceErrors.php';
require_once DIR_ROOT.'/ServiceConfig.php';
require_once DIR_ROOT.'/ControllerService.php';
require_once DIR_ROOT.'/DataConection.php';
header("Content-Type: text/html;charset=utf-8");

//Needed parameters for the Service Server
$ns = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];

//NuSOAP Class instantiation
$soap_server = new soap_server();

//WSDL setup
$soap_server->configureWSDL('catalogoProductos', $ns);
$soap_server->wsdl->schemaTargetNamespace = $ns;
$soap_server->soap_defencoding = 'UTF-8';
$soap_server->xml_encoding = "utf-8";

// Here we receive the XML with the information in form of a string called dataCustomer
$soap_server->register('catalogo',array('data' => 'xsd:string'),array('value' => 'xsd:string'),$ns);


/**
 *
 * WebMethod queries the catalogo
 * @param string $data
 */
function catalogo($value){
  $objectController = new ControllerService();
  $result = $objectController->parseXML(utf8_encode($value));
  $result = str_replace('<?xml version="1.0"?>', '', $result);
  return new soapval('value', 'xsd:string', $result);
}

//Publish the data to return
if ( !isset( $HTTP_RAW_POST_DATA ) ) $HTTP_RAW_POST_DATA = file_get_contents( 'php://input' );
$soap_server->service($HTTP_RAW_POST_DATA);