<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
JPluginHelper::importPlugin('amadeus', 'numberFormat');

class CatalogoPlanesViewCar extends JView {

  var $pluginCatalogo;
  var $viewName = "car";
  var $catalogoComponentConfig;
  var $root_path;
  var $user;

  function __construct($tpl = null){
    //Importamos el plugin de formato de moneda
    $this->pluginCatalogo = & JDispatcher::getInstance();
    $this->catalogoComponentConfig=&JComponentHelper::getParams('com_catalogo_planes');
    $this->root_path = JURI::base().'components/com_catalogo_planes/assets/';
    parent::__construct($tpl);
  }

  function display($tpl = null) {
    //Obtenemos al usuario
    $this->user =& JFactory::getUser();
    //Establecemos el documento para agregar los estilos y scripts
    $document = &JFactory::getDocument();
    //Obtenemos la configuracion de la moneda
    $arrayConf = $this->pluginCatalogo->trigger('configurationData', array());
    //importamos el archivo javascript general del componente
    $document->addScript($this->root_path.'js/cp.general.js');
    switch($this->getLayout()){
      case "availability"://Se importan los estilos y los scripts de la disponibilidad
        $document->addStyleSheet($this->root_path.'css/availability.css');
        $document->addScript($this->root_path.'js/jquery.pagination.js');
        $document->addScript('https://maps.google.com/maps/api/js?sensor=false');
        //Las opciones del paginador
        $script = "var paginatorOptions = {
						'rowsPerPage': ".$this->catalogoComponentConfig->get("cfg_rows_per_page").",
						'firstArrow': '&lt;&lt;".JText::_("CP.CAR.PAGINATION.FIRST")."',
						'firstArrowTitle': '".JText::_("CP.CAR.PAGINATION.FIRST.TITLE")."',
						'prevArrow': '&lt;".JText::_("CP.CAR.PAGINATION.PREV")."',
						'prevArrowTitle': '".JText::_("CP.CAR.PAGINATION.PREV.TITLE")."',
						'lastArrow': '".JText::_("CP.CAR.PAGINATION.LAST")."&gt;&gt;',
						'lastArrowTitle': '".JText::_("CP.CAR.PAGINATION.LAST.TITLE")."',
						'nextArrow': '".JText::_("CP.CAR.PAGINATION.NEXT")."&gt;',
						'nextArrowTitle': '".JText::_("CP.CAR.PAGINATION.NEXT.TITLE")."',
						ignoreRows: []						 
					};
					var addressText = '".JText::_("CP.CAR.MAP.ADDRESS").":';";
        $document->addScriptDeclaration($script);

        $document->addScript($this->root_path.'js/car.availability.js');
        //The number format configuration
        $script2 = "
						var decSep = '".$arrayConf[0]["decimalSeparator"]."';
						var milSep = '".$arrayConf[0]["milSeparator"]."';
						var numDec = '".$arrayConf[0]["decimal"]."';
						var orderCurrency = '".$arrayConf[0]["order"]."';";
        $document->addScriptDeclaration($script2);
        break;
      case "detail"://Se importan los estilos y los scripts del detalle

        $document->addStyleSheet($this->root_path.'css/detail.css');
        $document->addStyleSheet($this->root_path.'css/galleria.classic.css');
        $document->addScript($this->root_path.'js/galleria-1.2.7.min.js');
        $document->addScript($this->root_path.'js/galleria.classic.min.js');

        //Se establece el arreglo de fechas validas
        $script2 = "var availableDates = [";
        $countDates = 0;
        foreach($this->data["avaibleDates"] as $date){
          if($countDates>0){
            $script2 .=",";
          }
          $script2 .= '"'.$date.'"';
          $countDates++;
        }
        $script2 .= "];";
        //Se establece el maximo rango de dias
        $dayRange=0;
        if($this->configQS["day_range"]){
          $dayRange = $this->configQS["day_range"];
        }
        $qs = false;
        //Se obtiene la fecha inicial de qs
        $dateStart = "";
        if(isset($this->params["date_start"]) && $this->params["date_start"]!=""){
          $qs = true;
          $dateStart = $this->params["date_start"];
        }
        //Se obtiene la fecha final del qs
        $dateFinish = "";
        if(isset($this->params["date_finish"]) && $this->params["date_finish"]!=""){
          $dateFinish = $this->params["date_finish"];
        }
        if($this->data["latitude"]=="" && $this->data["longitude"]==""){
          $script2 .= "var urlMap = '".base64_decode($this->data["url"])."';";
        }else{
          $script2 .= "var urlMap = '';";
        }
        //Valido si no viene del qs y establesco los valores preconfigurados
        if(!$qs){
          $pickUpDate = $this->getHourObject($this->catalogoComponentConfig->get("cfg_car_pick_up_date"));
          $this->params["hour_start"] = $pickUpDate["hour"];
          $this->params["time_start"] = $pickUpDate["time"];
          $deliveryDate = $this->getHourObject($this->catalogoComponentConfig->get("cfg_car_delivery_date"));
          $this->params["hour_finish"] = $deliveryDate["hour"];
          $this->params["time_finish"] = $deliveryDate["time"];
        }
        $script2 .= "var dayRangeCar = ".$dayRange.";";
        $script2 .= "var dateStart = '".$dateStart."';";
        $script2 .= "var dateFinish = '".$dateFinish."';";
        //Asigno los tipos de alimentacion a un arreglo javascript
        $script2 .= "var feedArray = new Array();";
        $contP3 = 0;
        $script2 .= "var messageErrorAdult = '".JText::_("CP.CAR.ADULT.ROOM.MESSAGE")."';";
        $script2 .= "var messageErrorChild = '".JText::_("CP.CAR.CHILD.ROOM.MESSAGE")."';";
        $document->addScriptDeclaration($script2);

        //Se agrega el script del detalle
        $document->addScript($this->root_path.'js/car.detail.js');
        //Se agrega el script de la galeria
        if(!empty($this->data["images"]) && count($this->data["images"])>0){
          $script = 'jQuery(document).ready(function($) {
					    // Initialize Galleria
					    Galleria.configure({
					    	debug: false,
					    	imageCrop: "height"
						});
					    Galleria.run("#galleriaProduct");
					});';
          $document->addScriptDeclaration($script);
        }
        //The number format configuration
        $script2 = "
						var decSep = '".$arrayConf[0]["decimalSeparator"]."';
						var milSep = '".$arrayConf[0]["milSeparator"]."';
						var numDec = '".$arrayConf[0]["decimal"]."';
						var orderCurrency = '".$arrayConf[0]["order"]."';";
        $document->addScriptDeclaration($script2);

        break;
      case "default"://Estos estilos son mas generales aunque se aplican a la pantalla de pasajeros
        $document->addStyleSheet($this->root_path.'css/default.css');
        //Se agrega el script
        $document->addScript($this->root_path.'js/car.guest.js');
        //Variable con los mensajes enviados al javascript
        $script = "var messsageRequired = '".JText::_("CP.GUEST.VALID.REQUIRED")."';
					var messageMail = '".JText::_("CP.GUEST.VALID.MAIL")."';
					var messsageRequiredPayment = '".JText::_("CP.GUEST.VALID.PAYMENT.REQUIRED")."';
					var messsageRequiredConditions = '".JText::_("CP.GUEST.VALID.CONDITIONS.REQUIRED")."';
					var buttonSendMailText = '".JText::_("CP.GUEST.SEND.MAIL")."';
					var messsageErrorMail = '".JText::_("CP.GUEST.VALID.SEND.MAIL.ERROR")."';
					var messsageNoSession = '".JText::_("CP.GUEST.NO.SESSION.MAIL")."';					
					var messsageOkMail = '".JText::_("CP.GUEST.VALID.SEND.MAIL.OK")."';
					var messsageSending = '".JText::_("CP.GUEST.SENDING.MAIL")."';
					var messageConfirmMail = '".JText::_("CP.GUEST.VALID.CONFIRM.MAIL")."';";
        if($this->user->guest){
          $script.="noRegister=true;";
        }
        $script.="var sendText='".JText::_("CP.GUEST.SEND")."';";
        $script.="var cancelText='".JText::_("CP.GUEST.CANCEL")."';";
        $script.="var emptyFieldsLogin='".JText::_("CP.GUEST.LOGIN.EMPTY")."';";
        $script.="var errorEmailLogin='".JText::_("CP.GUEST.LOGIN.EMAIL.ERROR")."';";


        $document->addScriptDeclaration($script);
        break;
    }

    parent::display($tpl);
  }

  /**
   *
   * This function return the price with format
   * @param $price
   * @param $currency
   */
  function getFormatPrice($price, $currency){
    //llamamos el plugin para darle el formato a la moneda
    $price = $this->pluginCatalogo->trigger('numberFormatWithCurrency', array($price,$currency));
    return $price[0];
  }

  /**
   *
   * this function return the tourism type array
   * @param $tourismType
   */
  function getTourismTypeArray($tourismType){
    $temp = array();
    if(count($tourismType["item"])>0){
      if(!isset($tourismType["item"][0])){
        $temp = $tourismType["item"];
        unset($tourismType["item"]);
        $tourismType["item"][0] = $temp;
      }
    }
    return $tourismType;
  }

  /**
   *
   * Return the image
   * @param $type
   * @param $image
   */
  function getImage($type, $image){
     
    switch($type){
    		case "amenity":
    		  //$imageFolder = $this->catalogoComponentConfig->get("cfg_caramenity_image_folder");
    		  return JURI::base().$image;
    		  break;
    		case "icon":
    		  return $this->root_path."images/".$image;
    		  break;
    		case "global":
    		  return JURI::base()."images/".$image;
    		  break;
    }
  }

  /**
   *
   * Set the map
   * @param $idMap
   * @param $latitude
   * @param $longitude
   */
  function setMap($idMap, $latitude, $longitude){
    $document = &JFactory::getDocument();
    $document->addScript('https://maps.google.com/maps/api/js?sensor=false');
    $script = "jQuery(document).ready(function(){
    					showMap('$idMap', $latitude, $longitude);
    				});
    				var addressText = '".JText::_("CP.CAR.MAP.ADDRESS").":';";
    $document->addScriptDeclaration($script);
  }

  /**
   * get the template from the path
   * @param <type> $file name of template
   * @return <type> template file
   */
  function fetch($file) {
    ob_start();                    // Start output buffering
    if (is_file($file)) {
      include($file);  // Include the file
      $contents = ob_get_contents(); // Get the contents of the buffer
      ob_end_clean();           // End buffering and discard
      return $contents;              // Return the contents
    }
  }

  /**
   *
   * Return the hour object
   * @param $dataHour
   */
  function getHourObject($dataHour){
    $hour = 0;
    $time = 0;
    if($dataHour<12){
      $hour = $dataHour;
      $time = 1;
      if($hour==0){
        $hour = 12;
      }
    }else{
      $hour = $dataHour-12;
      $time = 2;
    }
    return array("hour"=>$hour, "time"=>$time);
  }
}