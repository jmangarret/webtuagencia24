<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: controller.php 2012-09-10 18:29:50 svn $
 * @author Yusely Palacios
 * @package Joomla
 * @subpackage catalogo_planes
 * @license Copyright (c) 2012 Amadeus - All Rights Reserved
 *
 *
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once("components".DS."com_catalogo_planes".DS."helper.php");
require_once("components".DS."com_catalogo_planes".DS."helperErrors.php");
require_once("components".DS."com_catalogo_planes".DS."lib".DS."json".DS."Encoder.php");
include_once(JPATH_BASE . DS . "components".DS."com_catalogo_planes".DS."lib" . DS . 'html2pdf' . DS . 'html2pdf.class.php');
jimport('joomla.application.component.controller');

/**
 * catalogo_planes Component Controller
 */
class Catalogo_planesController extends JController {
  //ESTA FUNCION ES NUEVA
  function execute($task) {

    if(!isset($task))
    $this->display();

    if($task)
    switch ($task):
      case "wsCity":
          $regionId = JRequest::getVar('regionId', '0', 'GET', 'string');
          $countryId = JRequest::getVar('countryId', '0', 'GET', 'string');
          $productName = JRequest::getVar('productName', '0', 'GET', 'string');
          $objectXML = $this->getXMLCities($regionId, $countryId, $productName);
          $retval = $this->callService($objectXML);
          $valueReturn = array();
          if(isset($retval["city"]["item"])):
          if(!isset($retval["city"]["item"][0]))
          $valueReturn["item"][0] = $retval["city"]["item"];
          else
          $valueReturn["item"] = $retval["city"]["item"];

          endif;
          die(Zend_Json_Encoder::encode($valueReturn));
        break;
      case "wsCountryName":
          $geographic = JRequest::getVar('Geographic', '', 'GET', 'string');
          $resultsGetGeographic = $this->getCountryName($geographic);
          die($resultsGetGeographic);
        break;
      endswitch;
  }
  /**
   *
   * Control the request
   */
  function display() {

    $mainframe =& JFactory::getApplication();

    try{

      $paramsCatalogo=&JComponentHelper::getParams('com_catalogo_planes');
      $document = JFactory::getDocument();
      if($paramsCatalogo->get('use_jquery') == 1){
        $document->addScript(JURI::base().'components/com_catalogo_planes/assets/js/jquery-1.8.1.min.js');
      }

      if($paramsCatalogo->get('use_jqueryUI') == 1){
        $document->addScript(JURI::base().'components/com_catalogo_planes/assets/js/jquery-ui-1.8.23.custom.min.js');
        $document->addScript(JURI::base().'components/com_catalogo_planes/assets/js/jquery.ui.datepicker-es.js');
        $document->addStyleSheet(JURI::base().'components/com_catalogo_planes/assets/css/jquery-ui-1.8.17.custom.css');
      }

      if($paramsCatalogo->get('use_jquery_validate') == 1){
        $document->addScript(JURI::base().'components/com_catalogo_planes/assets/js/jquery.validate.js');
      }

      // Make sure we have a default view
      $view = JRequest::getVar( 'view', 'catalogo_planes' );

      // Get the layout name
      $layout = JRequest::getVar( 'layout', 'default' );

      // Call the controller file
      include_once("controllers/".$view.".php");

      //set the first letter to upper case to call the right controller
      $controllerSufix = ucfirst($view);

      //Set the controller class name
      $controllerName='CatalogoPlanesController'.$controllerSufix;

      //instantiate te controller
      $controller =new $controllerName();

      //Verify if the method exists in the given controller
      if(!$this->methodExist($controller,$layout))
      $mainframe->redirect('index.php',JText::_('CATALOG.MSG.ERROR.METHOD.NOEXIST'),'error');
      //Validacion para que no pueda volver a entrar a la pantalla de pasajeros si ya se genero reserva
      if($layout=="guest" && isset($_SESSION[$view]["booking"])){
        unset($_SESSION[$view]["booking"]);
        $mainframe->redirect('index.php',JText::_('CATALOG.MSG.ERROR.EXIST.BOOKING'));
      }elseif(($layout=="availability" || $layout=="detail") && isset($_SESSION[$view]["booking"])){
        unset($_SESSION[$view]["booking"]);
      }

      //Get the data for the view
      $controller->$layout();

    }catch(Exception $e){
      die(print_r($e));
      $mainframe->redirect('index.php',JText::_('CATALOG.MSG.ERROR.FLOW'),'error');
    }


  }

  /**
   * Verify if the called method exist in the list of task of the given controller, if true then return true, otherwise return false
   */
  public function methodExist($controller,$calledMethodName){
    $tasksName=$controller->getTasks();
    foreach($tasksName as $taskName){
      if($taskName===$calledMethodName){
        return true;
      }
    }
    return false;
  }
  ///////DESDE AQUI EN NUEVO
  /**
  *
  * this function return the xmlObject of request cities
  * @param $regionId
  * @param $productName
  */
  function getXMLCities($regionId, $countryId, $productName){
    $data = new SimpleXMLElement('<Data></Data>');
    $data->addChild("type", "General");
    $data->addChild("method", "getAvaibleCities");
    $data->addChild("product");
    $data->product->addChild("name", $productName);
    $data->addChild("region");
    $data->addChild("country");
    $data->region->addChild("id", $regionId);
    $data->country->addChild("id", $countryId);
    return $data;
  }
  /**
  *
  * Return the countries for autocomplete
  * NOTE: This function was imported from plugin:autocomplete
  * @param $aCode
  */
  function getCountryName( $aCode ) {
      $mainframe =& JFactory::getApplication();
      $db = JFactory::getDBO();
      $q = "SELECT Name, Code, code2, LocalName, Region
    		FROM #__country
    		WHERE Name LIKE '".$aCode."%'";
      $db->setQuery( $q ) ;
      $countries = $db->loadObjectList();

      $xml ='';
      foreach ($countries as $country) {
          $xml .= "$country->code2|$country->Name";
      }
      return $xml;
  }
  /**
   *
   * This function import the plugin and execute the ws
   * @param $objectXML
   */
  function callService($objectXML){
    JPluginHelper::importPlugin('amadeus', 'catalogoPlanes');
    $dispatcher	=& JDispatcher::getInstance();
    $retval = $dispatcher->trigger('connectCatalogoPlanes', array($objectXML));
    return $retval[0];
  }
}
?>