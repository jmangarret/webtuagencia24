<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
JPluginHelper::importPlugin('amadeus', 'numberFormat');

class CatalogoPlanesViewHotel extends JView {

  var $pluginCatalogo;
  var $viewName = "hotel";
  var $catalogoComponentConfig;
  var $root_path;
  var $user;

  function __construct($tpl = null){
    //Importamos el plugin de formato de moneda
    $this->pluginCatalogo = & JDispatcher::getInstance();
    $this->catalogoComponentConfig = &JComponentHelper::getParams('com_catalogo_planes');
    $this->catalogoComponentConfig=$this->catalogoComponentConfig ->get('params');
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
						'rowsPerPage': ".$this->catalogoComponentConfig->cfg_rows_per_page.",
						'firstArrow': '« ".JText::_("CP.HOTEL.PAGINATION.FIRST")."',
						'firstArrowTitle': '".JText::_("CP.HOTEL.PAGINATION.FIRST.TITLE")."',
						'prevArrow': '« ".JText::_("CP.HOTEL.PAGINATION.PREV")."',
						'prevArrowTitle': '".JText::_("CP.HOTEL.PAGINATION.PREV.TITLE")."',
						'lastArrow': '".JText::_("CP.HOTEL.PAGINATION.LAST")." »',
						'lastArrowTitle': '".JText::_("CP.HOTEL.PAGINATION.LAST.TITLE")."',
						'nextArrow': '".JText::_("CP.HOTEL.PAGINATION.NEXT")." »',
						'nextArrowTitle': '".JText::_("CP.HOTEL.PAGINATION.NEXT.TITLE")."',
						ignoreRows: []
					};
					var addressText = '".JText::_("CP.HOTEL.MAP.ADDRESS").":';";
        $document->addScriptDeclaration($script);

        $document->addScript($this->root_path.'js/hotel.availability.js');
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
        //Se obtiene la fecha inicial de qs
        $dateStart = "";

        if(isset($this->params["HOTEL_DATE_START"]) && $this->params["HOTEL_DATE_START"]!=""){
          $dateStart = $this->params["HOTEL_DATE_START"];
        }

        //Se obtiene la fecha final del qs
        $dateFinish = "";
        if(isset($this->params["HOTEL_DATE_FINISH"]) && $this->params["HOTEL_DATE_FINISH"]!=""){
          $dateFinish = $this->params["HOTEL_DATE_FINISH"];
        }
        $script2 .= "var dayRangeHotel = ".$dayRange.";";
        $script2 .= "var dateStart = '".$dateStart."';";
        $script2 .= "var dateFinish = '".$dateFinish."';";
        //Asigno los tipos de alimentacion a un arreglo javascript
        $script2 .= "var feedArray = new Array();";
        $contP3 = 0;
        if($this->data["latitude"]=="" && $this->data["longitude"]==""){
          $script2 .= "var urlMap = '".base64_decode($this->data["url"])."';";
        }else{
          $script2 .= "var urlMap = '';";
        }

        foreach($this->data["params3"]["param"] as $param3){
          $script2 .= "feedArray[".$param3["id"]."] = '".$param3["name"]."';";
          $contP3++;
        }
        $script2 .= "var messageErrorAdult = '".JText::_("CP.HOTEL.ADULT.ROOM.MESSAGE")."';";
        $script2 .= "var messageErrorChild = '".JText::_("CP.HOTEL.CHILD.ROOM.MESSAGE")."';";
        $document->addScriptDeclaration($script2);

        //Se agrega el script del detalle
        $document->addScript($this->root_path.'js/hotel.detail.js');
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
        $document->addScript($this->root_path.'js/hotel.guest.js');
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
					var nationalityMinchars = '".JText::_("CP.GUEST.AUTOCOMPLETE.MIN.CHARS.NACIONALITY")."';
					var ResultMsg      = '". JText::_("AUTOCOMPLETE.NO.RESULTS")."';
					var messageConfirmMail = '".JText::_("CP.GUEST.VALID.CONFIRM.MAIL")."';
				    var requireRegister = '".$this->catalogoComponentConfig->cfg_require_register."';";

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
    if(is_array($tourismType)){
      if(count($tourismType["item"])>0){
        if(!isset($tourismType["item"][0])){
          $temp = $tourismType["item"];
          unset($tourismType["item"]);
          $tourismType["item"][0] = $temp;
        }
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
    		  //$imageFolder = $this->catalogoComponentConfig->get("cfg_hotelamenity_image_folder");
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
    				var addressText = '".JText::_("CP.HOTEL.MAP.ADDRESS").":';";
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
}