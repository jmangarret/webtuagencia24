<?php
/**
 *
 * this function sort the array dates
 * @param unknown_type $a
 * @param unknown_type $b
 */
function cmp($a, $b) {
  if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$a)){
    list($diaA,$mesA,$anoA)=explode("/", $a);
  }
  if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$b)){
    list($diaB,$mesB,$anoB)=explode("/", $b);
  }

  if (strtotime("$mesA/$diaA/$anoA") == strtotime("$mesB/$diaB/$anoB")) return 0;
  if(strtotime("$mesA/$diaA/$anoA") < strtotime("$mesB/$diaB/$anoB")){
    return -1;
    echo "-1";
  }else{
    return 1;
    echo "1";
  }
}

/**
 *
 * Class of the helper of the cp
 * @author andres.ramirez
 *
 */
class CatalogoPlanesHelper{

  /**
   *
   * Call the service
   */
  function callService($objectXML){
    $mainframe =& JFactory::getApplication();
    //Instance the plugin
    try{
      JPluginHelper::importPlugin('amadeus', 'catalogoPlanes');
      $dispatcher = & JDispatcher::getInstance();

      $retval = $dispatcher->trigger('connectCatalogoPlanes', array($objectXML));

      if($retval[0]["status"]=="0"){
        $helperError = HelperErrors::getInstance();
        $errorCode = $retval[0]["error"]["code"];
        $errorMessage = $retval[0]["error"]["message"];
        $helperError->handleError($errorCode, $errorMessage, $objectXML->type);
        $mainframe->redirect("index.php");
      }
      return $retval[0];
    }catch(Exception $ex){
      $mainframe->redirect("index.php", JText::_("CATALOG.MSG.ERROR.EXCEPTION"), "error");
    }
  }

  /**
   *
   * this function format the date to send to web service
   * @param $date
   */
  function setDateFormatToService($date){
    if(isset($date) && $date!=""){
      $dateParts = explode("/",$date );
      return $dateParts[2]."-".$dateParts[1]."-".$dateParts[0];
    }else{
      return "";
    }

  }

  /**
   *
   * this function format the date to send to web service
   * @param $date
   */
  function setDateFormat($date){
    $dateParts = explode("-",$date );
    return $dateParts[2]."/".$dateParts[1]."/".$dateParts[0];
  }

  /**
   *
   * Serialize the object for send to session
   * @param $params
   * @param $product
   * @param $lang
   */
  function serializeAvailability($params, $product, $lang){
    $text = "";
    foreach($params as $param){
      $text .= $param;
    }
    $text .= $product.$lang;
    return md5($text);
  }

  /**
   * This function add day to date
   */
  function addDays($fecha,$ndias){
    if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
    list($dia,$mes,$ano)=explode("/", $fecha);


    if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
    list($dia,$mes,$ano)=explode("-",$fecha);
    $nueva = mktime(0,0,0, $mes,$dia,$ano) + $ndias * 24 * 60 * 60;
    $nuevafecha=date("d/m/Y",$nueva);
    return ($nuevafecha);
  }

  /**
   *
   * This function return thr config of the quicksearch component
   * @param $productType
   */
  function getQuicksearchConfig(){
    $db =& JFactory::getDBO();
    /*	Este query fue realizado debido al no fucionamiento de la sentencia:
	 *	$module = JModuleHelper::getModule('mod_quicksearchproducts');
	 */
    $db->setQuery("SELECT params FROM #__modules WHERE module='mod_quicksearchproducts'");
    $module = $db->loadObject();
    $module_params = new JRegistry();
    $module_params->loadString($module->params);
    //echo '<pre>';print_r($module_params->get('products'));echo '</pre>';
    return $module_params;
  }

  /**
   *
   * This function return the array of the avaible dates
   * @param $seasons
   */
  function getAvaibleDates($seasons){
    $arrayDates = array();
    foreach($seasons as $season){
      $arraDays = array();
      $arraDays = $this->getAvaibleDays($season["day"]);

      foreach($season["dates"]["date"] as $dateOfSeason){
        //Valido que no sean temporadas anteriores a la fecha actual
        if(strtotime(Date("Y-m-d"))<strtotime($dateOfSeason["end"])){
          //Valido que la fecha de inicio este despues de la fecha actual
          if(strtotime(Date("Y-m-d"))>strtotime($dateOfSeason["start"])){
            $dateStart = Date("Y-m-d");
          }else{
            $dateStart = $dateOfSeason["start"];
          }
          $arrayDates = array_merge($this->dates_between($dateStart, $dateOfSeason["end"], "d/m/Y", $arraDays), $arrayDates);
        }

      }

    }
    //Dejo el listado con fechas unicas
    $arrayDates = array_unique($arrayDates);
    //Organizo el listado de fechas
    usort($arrayDates, "cmp");

    return $arrayDates;
  }

  /**
   *
   * This function return the array of the days
   * @param array $days
   */
  function getAvaibleDays($days){
    $countDays = 1;
    $arrayDays = array();
    foreach($days as $day){
      if($day==1){
        $arrayDays[] = $countDays;
      }
      $countDays++;
      if($countDays==7){
        $countDays=0;
      }
    }
    return $arrayDays;
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
  function dates_between($startdate, $enddate, $format=null, $days){

    (is_int($startdate)) ? 1 : $startdate = strtotime($startdate);
    (is_int($enddate)) ? 1 : $enddate = strtotime($enddate);

    if($startdate > $enddate){
      return false; //The end date is before start date
    }

    while($startdate <= $enddate){
      if(in_array(date("w",$startdate), $days))
      $arr[] = ($format) ? date($format, $startdate) : $startdate;
      $startdate += 86400;
    }

    return $arr;
  }

  /**
   *
   * Return the diff of 2 date
   * @param date $date1
   * @param date $date2
   */
  function diffDate($date1, $date2){
    $date1Parts = explode("/", $date1);
    $date2Parts = explode("/", $date2);
    //calculo timestam de las dos fechas
    $timestamp1 = mktime(0,0,0,$date1Parts[1],$date1Parts[0],$date1Parts[2]);
    $timestamp2 = mktime(0,0,0,$date2Parts[1],$date2Parts[0],$date2Parts[2]);

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
   * This function send mail
   * @param unknown_type $recipient
   * @param unknown_type $subject
   * @param unknown_type $body
   * @param unknown_type $ishtml
   * @param unknown_type $image
   */
  function sendMail($recipient, $subject, $body, $ishtml = true, $image = NULL) {
    $mailer = & JFactory::getMailer();
    $config = & JFactory::getConfig();
    $sender = array(
    $config->getValue('config.mailfrom'),
    $config->getValue('config.fromname'));

    $mailer->setSender($sender);
    $mailer->addRecipient($recipient);
    $mailer->setSubject($subject);
    $mailer->isHTML($ishtml);
    $mailer->setBody($body);
    // Optionally add embedded image
    if ($image) {
      $mailer->AddEmbeddedImage($image['url'], $image['id'], $image['name'], $image['encode'], $image['mime']);
    }
    $send = & $mailer->Send();
    if ($send !== true) {
      return $send->message;
    } else {
      return true;
    }
  }

  /**
   *
   * Esta funcion se encarga de generar una pagina que no genere cache
   * es utilizada en la pagina de pasajeros para que no puedan retroceder a esta pagina
   */
  function noCache(){
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
  }

  /**
   * Get text content for article from its Id
   * @param <int> $articleId Identifier of article
   * @return <string> Article content
   */
  function getArticleById($articleId) {
    $db  =& JFactory::getDBO();
    $sql = "SELECT *
				FROM #__content
				WHERE id = ".intval($articleId);

    $db->setQuery($sql);
    $fullArticle = $db->loadAssoc();

    return $fullArticle;
  }



}