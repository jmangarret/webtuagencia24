<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
JPluginHelper::importPlugin('amadeus', 'numberFormat');

class CatalogoPlanesViewTour extends JView {

  var $pluginCatalogo;
  var $viewName = "tour";
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
      /*case "availability"://Se importan los estilos y los scripts de la disponibilidad
       $document->addStyleSheet($this->root_path.'css/availability.css');
       $document->addScript($this->root_path.'js/jquery.pagination.js');
       $document->addScript('https://maps.google.com/maps/api/js?sensor=false');
       //Las opciones del paginador
       $script = "var paginatorOptions = {
       'rowsPerPage': ".$this->catalogoComponentConfig->get("cfg_rows_per_page").",
       'firstArrow': '&lt;&lt;".JText::_("CP.PLAN.PAGINATION.FIRST")."',
       'firstArrowTitle': '".JText::_("CP.PLAN.PAGINATION.FIRST.TITLE")."',
       'prevArrow': '&lt;".JText::_("CP.PLAN.PAGINATION.PREV")."',
       'prevArrowTitle': '".JText::_("CP.PLAN.PAGINATION.PREV.TITLE")."',
       'lastArrow': '".JText::_("CP.PLAN.PAGINATION.LAST")."&gt;&gt;',
       'lastArrowTitle': '".JText::_("CP.PLAN.PAGINATION.LAST.TITLE")."',
       'nextArrow': '".JText::_("CP.PLAN.PAGINATION.NEXT")."&gt;',
       'nextArrowTitle': '".JText::_("CP.PLAN.PAGINATION.NEXT.TITLE")."',
       ignoreRows: []
       };
       var addressText = '".JText::_("CP.PLAN.MAP.ADDRESS").":';";
       $document->addScriptDeclaration($script);

       $document->addScript($this->root_path.'js/plan.availability.js');
       //The number format configuration
       $script2 = "
       var decSep = '".$arrayConf[0]["decimalSeparator"]."';
       var milSep = '".$arrayConf[0]["milSeparator"]."';
       var numDec = '".$arrayConf[0]["decimal"]."';
       var orderCurrency = '".$arrayConf[0]["order"]."';";
       $document->addScriptDeclaration($script2);
       break;*/
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

        //Se obtiene la fecha inicial de qs
        $dateStart = "";
        if(isset($this->data["selectedDate"]) && $this->data["selectedDate"]!=""){
          $dateStart = $this->data["selectedDate"];
        }


        $script2 .= "var dateStart = '".$dateStart."';";
        if($this->data["specialSeasons"]){
          $script2 .= "var specialSeasons = true;";
        }else{
          $script2 .= "var specialSeasons = false;";
        }
        //Asigno los tipos de alimentacion a un arreglo javascript
        $script2 .= "var feedArray = new Array();";
        if($this->data["latitude"]=="" && $this->data["longitude"]==""){
          $script2 .= "var urlMap = '".base64_decode($this->data["url"])."';";
        }else{
          $script2 .= "var urlMap = '';";
        }
        /*$contP3 = 0;
         foreach($this->data["params3"]["param"] as $param3){
         $script2 .= "feedArray[".$param3["id"]."] = '".$param3["name"]."';";
         $contP3++;
         }*/
        $script2 .= "var messageErrorAdult = '".JText::_("CP.TOUR.ADULT.ROOM.MESSAGE")."';";
        $script2 .= "var messageErrorChild = '".JText::_("CP.TOUR.CHILD.ROOM.MESSAGE")."';";
        $script2 .= "var messageErrorChildwithoutadult = '".JText::_("CP.TOUR.CHILD.WITHOUT.ADULT.MESSAGE")."';";
        $script2 .= "var messageErrorWithoutGuests = '".JText::_("CP.TOUR.CHILD.WITHOUT.GUESTS.MESSAGE")."';";
        $script2 .= "var param2Adults = '".$this->data['param2Adults']."';";
        $script2 .= "var param2Childs = '".$this->data['param2Childs']."';";
        $script2 .= "var adultwithoutchild = '".$this->data['adultwithoutchild']."';";
        $document->addScriptDeclaration($script2);

        //Se agrega el script del detalle
        $document->addScript($this->root_path.'js/tour.detail.js');
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
        $document->addScript($this->root_path.'js/tour.guest.js');
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
					var messageConfirmMail = '".JText::_("CP.GUEST.VALID.CONFIRM.MAIL")."';
					var nationalityMinchars = '".JText::_("CP.GUEST.AUTOCOMPLETE.MIN.CHARS.NACIONALITY")."';
					var ResultMsg      = '". JText::_("AUTOCOMPLETE.NO.RESULTS")."';
				    var requireRegister = '".$this->catalogoComponentConfig->get("cfg_require_register")."';";
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
    		  //$imageFolder = $this->catalogoComponentConfig->get("cfg_planamenity_image_folder");
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
    				var addressText = '".JText::_("CP.PLAN.MAP.ADDRESS").":';";
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
   * This function use ucwords with an exception
   */
  function ucwordss($str, $exceptions) {
    $out = "";
    foreach (explode(" ", $str) as $word) {
      $out .= (!in_array($word, $exceptions)) ? strtoupper($word{0}) . substr($word, 1) . " " : $word . " ";
    }
    return rtrim($out);
  }
}