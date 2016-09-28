<?php
class Products{

  /**
   *
   * Contain the currency list
   * @var unknown_type
   */
  protected  $currencyList=array();

  /**
   * Object of the error class
   */
  protected $_objError;

  /**
   *
   * Object of the agency
   * @var object
   */
  protected $agency;

  /**
   *
   * Object of the service config
   * @var object
   */
  protected $serviceConfig;

  /**
   *
   * status of the user
   * @var unknown_type
   */
  protected $userAccess;


  /**
   *
   * Establece la agencia
   * @param $agency
   */
  function setAgency($agency){
    $this->agency = $agency;
  }

  /**
   * Construct of the class
   */
  function __construct(){
    $this->_objError = ServiceErrors::getInstance();
    $this->serviceConfig = new ServiceConfig();
  }

  /**
   *
   * This function obtain the currency list
   * @param string $lang
   */
  protected function setCurrencyList($lang){
    $objConection = new DataConection();
    $sql = "CALL GetCurrencyList('".$lang."')";
    $objConection->setQuery($sql);
    $this->currencyList = $objConection->loadObjectList();
  }

  public function setUserAccess($userAccess){
    $this->userAccess = !$userAccess;
  }

  /**
   *
   * This function return the price on the required currency
   * @param double $price
   * @param int $currency | La moneda requerida
   * @param int $product_currency | La moneda del producto
   */
  protected function getTheCurrencyValue($price, $currency, $product_currency, $markup){
    $objReturn = new stdClass();
    $returnObject = false;
    //If isset the currency
    if($product_currency!=$currency && $currency!=0){
      //find the currency in the currency list and apply the TRM
      foreach($this->currencyList as $currencyObj){
        if($currency==$currencyObj->currency_id){

          $returnObject=true;
          $objReturn->currency_id = $currencyObj->currency_id;
          $objReturn->currency_name = $currencyObj->currency_name;
          $objReturn->symbol = $currencyObj->symbol;
          $objReturn->approx = $currencyObj->approx;
          $objReturn->trm = $currencyObj->trm;
          $objReturn->markup = $markup;
          $value = $this->getPriceMarkupValue($price, $markup);
          $value = $value/$currencyObj->trm;
          $objReturn->markupValue = $this->roundValue($value, $currencyObj->approx)-$this->roundValue(($price/$currencyObj->trm), $currencyObj->approx);
          $objReturn->price = $this->roundValue($value, $currencyObj->approx);
        }
      }

    }
    //If not find the currency return the default currency
    if(!$returnObject){
      foreach($this->currencyList as $currencyObj){
        if($currencyObj->default_currency==1){
          $objReturn->currency_id = $currencyObj->currency_id;
          $objReturn->currency_name = $currencyObj->currency_name;
          $objReturn->symbol = $currencyObj->symbol;
          $objReturn->approx = $currencyObj->approx;
          $objReturn->trm = $currencyObj->trm;
          $objReturn->markup = $markup;
          $value = $this->getPriceMarkupValue($price, $markup);
          $objReturn->markupValue = $this->roundValue($value, $currencyObj->approx)-$this->roundValue($price, $currencyObj->approx);
          $objReturn->price = $this->roundValue($value, $currencyObj->approx);
        }
      }
    }
    return $objReturn;
  }

  function getPriceMarkupValue($price, $markup){
    $value = $price /(1-($markup/100));
    return $value;
  }

  /**
   *
   * This function aproxximate the price
   * @param $price
   * @param $type
   */
  function roundValue($price, $type) {
    $returnPrice = 0;
    switch($type){
      case 1:
        $returnPrice = round($price, 0);
        break;
      case 2:
        $returnPrice = round($price, -1);
        break;
      case 3:
        $returnPrice = round($price, -2);
        break;
      case 4:
        $returnPrice = round($price, -3);
        break;
      case 5:
        $returnPrice = round($price, -4);
        break;
      case 6:
        $returnPrice = round($price, 1);
        break;
      case 7:
        $returnPrice = round($price, 2);
        break;
      default:
        $returnPrice = $price;
        break;
    }
    return $returnPrice;
  }

  /**
   *
   * This function recieve object and return the xml Object
   * @param $object
   * @param $items
   */
  protected function objectToXml($xmlHeader, $object, $items){
    $xmlHeader->addChild($items);
    $countItems = 0;
    if(is_array($object)){
      foreach($object as $objectItem){
        $xmlHeader->$items->addChild("item");
        foreach($objectItem as $index=>$value){
          $xmlHeader->$items->item[$countItems]->addChild($index, str_replace("&", "&amp;", $value));
        }
        $countItems++;
      }
    }

    return $xmlHeader;
  }

  /**
   *
   * This function return the agency info
   * @param string $agency_code
   */
  function getAgency($agencyCode){
    $objConection = new DataConection();
    //Se crea la consulta que va a obtener la agencia
    $sql = "SELECT a.*, g.name as group_name, g.id as group_code
					FROM #__agency a
						JOIN #__agency_group g ON a.idagency_group=g.id
					WHERE a.token='".$agencyCode."'
						AND a.active=1";

    //se envia la consulta a la clase conexion
    $objConection->setQuery($sql);
    //Se ejecuta la consulta
    $result = $objConection->loadObject();
    return($result);
  }
  /**
   *
   * This function validate if the group is active
   * @param int $idagency_group
   */
  function validateActiveGroup($idagency_group){
    $objConection = new DataConection();
    //Se crea la consulta que va a obtener la agencia
    $sql = "SELECT
				count(*) as total
			FROM #__agency_group
			WHERE id='".$idagency_group."'
				AND enabled=1";
    //se envia la consulta a la clase conexion
    $objConection->setQuery($sql);
    //Se ejecuta la consulta
    $result = $objConection->loadObject();
    if($result->total>0){
      return true;
    }else{
      return false;
    }
  }

  /**
   *
   * This function get the tableName and lang and return the language object
   * @param string $tableName
   * @param string $lang
   * @return multitype:
   */
  protected function getLangObjectByTable($tableName, $lang){
    $objConection = new DataConection();
    //Se crea la consulta que va a obtener el listado
    //de campos relacionados a la tabla y al lenguaje
    $sql = "SELECT c.reference_id, c.value, c.reference_field
		FROM #__jf_content c JOIN #__languages l ON c.language_id=l.lang_id
		WHERE c.reference_table='cp_plans_info' AND c.published=1 AND l.sef='$lang'";

    //se envia la consulta a la clase conexion
    $objConection->setQuery($sql);
    //Se ejecuta la consulta
    $result = $objConection->loadObjectList();
    $arrayResult = array();
    //Asociamos los resultados para que la consulta sea mas sencilla
    foreach($result as $object){
      $arrayResult[$object->reference_id][$object->reference_field] = $object->value;
    }
    return $arrayResult;
  }

  /**
   *
   * This function get the tableName and lang and return the language object
   * @param string $tableName
   * @param string $lang
   * @return multitype:
   */
  protected function getLangObjectByTableAndId($tableName, $id, $lang){
    $objConection = new DataConection();
    //Se crea la consulta que va a obtener el listado
    //de campos relacionados a la tabla y al lenguaje
    $sql = "SELECT c.reference_id, c.value, c.reference_field
    		FROM #__jf_content c JOIN #__languages l ON c.language_id=l.lang_id
    		WHERE c.reference_table='".$tableName."' AND c.published=1
			AND l.sef='$lang'
			AND c.reference_id=".$id;

    //se envia la consulta a la clase conexion
    $objConection->setQuery($sql);
    //Se ejecuta la consulta
    $result = $objConection->loadObjectList();
    $arrayResult = array();
    //Asociamos los resultados para que la consulta sea mas sencilla
    foreach($result as $object){
      $arrayResult[$object->reference_field] = $object->value;
    }
    return $arrayResult;
  }

  /**
   *
   * Return the diff of 2 date
   * @param date $date1
   * @param date $date2
   */
  protected function diffDate($date1, $date2){
    $date1Parts = explode("-", $date1);
    $date2Parts = explode("-", $date2);
    //calculo timestam de las dos fechas
    $timestamp1 = mktime(0,0,0,$date1Parts[1],$date1Parts[2],$date1Parts[0]);
    $timestamp2 = mktime(4,12,0,$date2Parts[1],$date2Parts[2],$date2Parts[0]);

    //resto a una fecha la otra
    $segundos_diferencia = $timestamp1 - $timestamp2;
    //echo $segundos_diferencia;

    //convierto segundos en días
    $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);

    //obtengo el valor absoulto de los días (quito el posible signo negativo)
    $dias_diferencia = abs($dias_diferencia);

    //quito los decimales a los días de diferencia
    $dias_diferencia = floor($dias_diferencia);

    return $dias_diferencia+1;
  }

  /**
   *
   * Return the list of zones
   * @param string $lang
   */
  protected function getZones($lang){
    $objConection = new DataConection();
    $sql = "CALL GetZone('".$lang."')";
    $objConection->setQuery($sql);
    $listZone = $objConection->loadObjectList();
    $arrayReturn = array();
    foreach($listZone as $zone){
      $arrayReturn[$zone->zone_id] = $zone->zone_name;
    }
    return $arrayReturn;
  }

  /**
   *
   * Return the list of categories
   * @param string $lang
   * @param string $nameProduct
   */
  protected function getCategory($lang, $nameProduct, $transfer_type='0'){
    $objConection = new DataConection();
    $sql = "CALL GetCategory('".$lang."', '".trim($nameProduct)."','".$transfer_type."')";
    $objConection->setQuery($sql);
    $listCategory = $objConection->loadObjectList();
    $arrayReturn = array();
    if(is_array($listCategory)){
      foreach($listCategory as $category){
        $arrayReturn[$category->category_id] = $category->category_name;
      }
    }

    return $arrayReturn;
  }

  /**
   *
   * Return the list of tourism type
   * @param string $lang
   * @param string $nameProduct
   */
  protected function getTourismType($lang, $nameProduct, $isquicksearch=false){
    $objConection = new DataConection();
	$isquicksearch=((int)$isquicksearch)?(int)$isquicksearch:0;
    $sql = "CALL GetTourismType('".$lang."', '".trim($nameProduct)."', '".$isquicksearch."')";
    $objConection->setQuery($sql);
    $listTourismType = $objConection->loadObjectList();
    $arrayReturn = array();
    if(isset($listTourismType))
    foreach($listTourismType as $tourismType){
      $arrayReturn[$tourismType->tourismtype_id] = $tourismType->tourismtype_name;
    }
    return $arrayReturn;
  }

  /**
   *
   * Return the list of images
   * @param int $productId
   * @param string $nameProduct
   */
  protected function getImageList($productId, $nameProduct){
    $objConection = new DataConection();
    $sql = "CALL GetFile('".$productId."', '".trim($nameProduct)."')";
    $objConection->setQuery($sql);
    $listFile = $objConection->loadObjectList();
    return $listFile;
  }

  /**
   *
   * This function get the comment list per product
   * @param int $productId
   * @param string $nameProduct
   * @return array
   */
  protected function getComment($productId, $nameProduct){
    $objConection = new DataConection();
    $sql = "CALL GetComment('".$productId."', '".trim($nameProduct)."')";

    $objConection->setQuery($sql);
    $listComment = $objConection->loadObjectList();
    return $listComment;
  }

  /**
   *
   * This function get the list of seasons
   * @param string $productId
   * @param string $nameProduct
   * @param string $language
   */
  protected function getSeasons($productId, $nameProduct, $language){
    $objConection = new DataConection();
    $sql = "CALL GetSeasons('".$language."', '".trim($nameProduct)."',".$productId.")";
    $objConection->setQuery($sql);
    $listSeasons = $objConection->loadObjectList();
    return $listSeasons;
  }

  /**
   *
   * This function get the list of params1
   * @param int $productId
   * @param string $nameProduct
   * @param string $language
   */
  protected function getParam1($productId, $nameProduct, $language){
    $objConection = new DataConection();
    $sql = "CALL GetParam1('".$language."', '".trim($nameProduct)."',".$productId.")";
    $objConection->setQuery($sql);
    $listParam = $objConection->loadObjectList();
    return $listParam;
  }
  /**
   *
   * This function get the list of params2
   * @param int $productId
   * @param string $nameProduct
   * @param string $language
   */
  protected function getParam2($productId, $nameProduct, $language){
    $objConection = new DataConection();
    $sql = "CALL GetParam2('".$language."', '".trim($nameProduct)."',".$productId.")";
    $objConection->setQuery($sql);
    $listParam = $objConection->loadObjectList();
    return $listParam;
  }
  /**
   *
   * This function get the list of params3
   * @param int $productId
   * @param string $nameProduct
   * @param string $language
   */
  protected function getParam3($productId, $nameProduct, $language){
    $objConection = new DataConection();
    $sql = "CALL GetParam3('".$language."', '".trim($nameProduct)."',".$productId.")";
    $objConection->setQuery($sql);
    $listParam = $objConection->loadObjectList();
    return $listParam;
  }

  /**
   *
   * This function get the list of supplements
   * @param int $productId
   * @param string $nameProduct
   * @param string $language
   */
  protected function getSupplements($productId, $nameProduct, $language){
    $objConection = new DataConection();
    $sql = "CALL GetSupplements('".$language."', '".trim($nameProduct)."',".$productId.")";
    $objConection->setQuery($sql);
    $listSupplements = $objConection->loadObjectList();
    return $listSupplements;
  }

  /**
   * Returns an array with the dates between to dates given.
   *
   * @link http://us3.php.net/manual/en/function.date.php#AEN25217
   *
   * @param mixed $startdate Timestamp or strtotime() recognizeable string
   * @param mixed $enddate Timestamp or strtotime() recognizeable string
   * @param string[optional] $format date() format string
   * @return mixed Array of timestamps or dates if given format
   */
  protected function dates_between($startdate, $enddate, $format=null){

    (is_int($startdate)) ? 1 : $startdate = strtotime($startdate);
    (is_int($enddate)) ? 1 : $enddate = strtotime($enddate);

    if($startdate > $enddate){
      return false; //The end date is before start date
    }

    while($startdate <= $enddate){
      $arr[] = ($format) ? date($format, $startdate) : $startdate;
      $startdate += 86400;
    }

    return $arr;
  }

  /**
   * Returns an array with the days between to dates given.
   *
   * @link http://us3.php.net/manual/en/function.date.php#AEN25217
   *
   * @param mixed $startdate Timestamp or strtotime() recognizeable string
   * @param mixed $enddate Timestamp or strtotime() recognizeable string
   * @param string[optional] $format date() format string
   * @return mixed Array of timestamps or dates if given format
   */
  protected function days_between($startdate, $enddate){
    (is_int($startdate)) ? 1 : $startdate = strtotime($startdate);
    (is_int($enddate)) ? 1 : $enddate = strtotime($enddate);
    if($startdate > $enddate){
      return false; //The end date is before start date
    }
    while($startdate <= $enddate){
      $day = date("w",$startdate);
      //Se cambia el numero del domingo de 0 a 7 para que se ajuste al modelo
      if($day==0){
        $day = 7;
      }
      $arr[$day] = 1;
      $startdate += 86400;
    }
    return $arr;
  }

  protected function getNumberWeekDay($startDate){
    $day = date("w",strtotime($startDate));

    //Se cambia el numero del domingo de 0 a 7 para que se ajuste al modelo
    if($day==0){
      $day = 7;
    }
    return $day;
  }

  /**
   *
   * This function compare the dates for hotels
   * @param date $date
   * @param date $dateStart
   * @param date $dateFinish
   * @param array $days
   */
  protected function compareDates($date, $dateStart, $dateFinish, $days){

    if(strtotime($dateStart)<=strtotime($date) && strtotime($date)<=strtotime($dateFinish)){
      $day = date("w",strtotime($date));
      switch($day){
        case 1:
          if($days->day1==1){
            return true;
          }
          break;
        case 2:
          if($days->day2==1){
            return true;
          }
          break;
        case 3:
          if($days->day3==1){
            return true;
          }
          break;
        case 4:
          if($days->day4==1){
            return true;
          }
          break;
        case 5:
          if($days->day5==1){
            return true;
          }
          break;
        case 6:
          if($days->day6==1){
            return true;
          }
          break;
        case 0:
          if($days->day7==1){
            return true;
          }
          break;
      }

    }
    return false;
  }


  /**
   *
   * This function return the list of taxes per product
   * @param $nameProduct
   * @param $language
   * @param $product_id
   */
  function getProductTaxes($nameProduct, $language, $product_id){
    $objConection = new DataConection();
    $sql = "CALL GetTaxesProduct('".$language."',".$product_id.", '".trim($nameProduct)."')";
    $objConection->setQuery($sql);
    $listTax = $objConection->loadObjectList();
    return $listTax;
  }

  /**
   *
   * This function return the list of taxes per supplement
   * @param $nameProduct
   * @param $language
   * @param $product_id
   */
  function getSupplementTaxes($nameProduct, $language, $product_id, $supplement_id){
    $objConection = new DataConection();
    $sql = "CALL GetTaxesSupplement('".$language."',".$product_id.",".$supplement_id.",'".trim($nameProduct)."')";
    $objConection->setQuery($sql);
    $listTax = $objConection->loadObjectList();
    return $listTax;
  }

  /**
   *
   * This function return the stock for the date range
   * @param $productName
   * @param $language
   * @param $productId
   * @param $arrayDates
   */
  function getProductStock($productName, $productId, $checkin_date, $checkout_date=''){
    if($checkout_date=='')
    $checkout_date=$checkin_date;
    $objConection = new DataConection();
    $sql = "CALL GetStock(".$productId.",'".$checkin_date."','".$checkout_date."','".trim($productName)."')";
    $objConection->setQuery($sql);
    $listStock = $objConection->loadObjectList();
    return $listStock;
  }

  /**
   *
   * This function return the data of the supplier
   * @param $idProduct
   * @param $language
   */
  function getProductSupplier($idSupplier, $language){
    $objConection = new DataConection();
    $sql = "CALL GetSupplier('".$language."',".$idSupplier.")";
    $objConection->setQuery($sql);
    $supplier = $objConection->loadObject();
    return $supplier;
  }


  /**
   * This function add day to date
   */
  function addDays($fecha,$ndias){
    if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
    list($dia,$mes,$ano)=exlode("/", $fecha);


    if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
    list($ano,$mes,$dia)=explode("-",$fecha);
    $nueva = mktime(0,0,0, $mes,$dia,$ano) - $ndias * 24 * 60 * 60;
    $nuevafecha=date("Y-m-d",$nueva);
    return ($nuevafecha);
  }


}