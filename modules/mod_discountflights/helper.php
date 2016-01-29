<?php
/**
 * Discount Flights
 *
 * @autor Dora Peña
 * @email   dora.pena@periferia-it.com
 * @date    November 2013
 */
// No direct access to this file
defined('_JEXEC') or die;

class moddiscountflightsHelper
{
  public function putResources(&$params)
  {
     
        $doc = JFactory::getDocument();

        $js  = 'jQuery(document).ready(function($){';
        $js .=   '$(" li[class^=journey]")'; //MICOD "div" cambiado a "li"
        $js .=   '.click(function(){';
        $js .=     '$(this).find("form").submit();';
        $js .=   '})';
        $js .= '});';
        $doc->addScriptDeclaration($js);
    }
  
  
  
  /**
   * Tabs method
   * @autor Dora Peña Antury
   * @email   dora.pena@periferia-it.com
   * @date    November 2013
   */
  public function getDiscountFlightsTabs()
  {
    $tabs = array();
    $firstlevel = array();
    $firstlevel = self::getFirstLevel();
   
    foreach($firstlevel as $name)
    {

      if($name->title_firstlevel)
      {
        $tabs[$name->id_firstlevel] = array(
                    'name' =>self::getNormalizeStr($name->title_firstlevel),
                    'label' => trim($name->title_firstlevel),
              'id' => $name->id_firstlevel
        );
      }
    }

    return $tabs;
  }
  /**
   * Get discount flights by type of flight
   * @autor Dora Peña Antury
   * @email   dora.pena@periferia-it.com
   * @date    November 2013
   */
  public function getDiscountFlights($tabs) {
    $db =& JFactory::getDBO();
    
    /*MICOD
    Sentencia SQL modificada para que traiga el valor del atributo "offset" y "image" para 
    las ofertas de fechas periódicas, que luego serán transformadas de datoos tipo "date"
    para que el webservice lo interprete correctamente. "image" es solo para la imagen de portada.
    El "lff.id" es para el trabajo de las publicidades*/
    $query = "SELECT ct1.title AS title_firstlevel, ct1.id AS id_firstlevel, ct2.title AS title_secondlevel,
      ct2.id AS id_secondlevel, originname, destinyname, value,  origin, destiny, departure, duration, offset, image, lff.id AS id
      FROM #__lf_flights lff INNER JOIN #__categories ct2 ON lff.category = ct2.id AND ct2.level=2 AND ct2.published=1 AND lff.published = 1
      INNER JOIN #__categories ct1 ON ct2.parent_id = ct1.id AND ct1.level=1 AND ct1.published=1
      ORDER BY ct1.id ASC, ct2.id ASC, lff.origin ASC";
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    //echo '<pre>';print_r($rows );echo '</pre>';
    foreach ($tabs as $key=>$tab):
      foreach ($rows as $k=>$row):
        $arrayFlights[(int)$row->id_firstlevel][(int)$row->id_secondlevel][$k] = $row;
      endforeach;
    endforeach;
    return $arrayFlights;
  }
  /**
   * Get Regions
   * @autor Dora Peña Antury
   * @email   dora.pena@periferia-it.com
   * @date    November 2013
   */
  public function getRegions() {

    $db =& JFactory::getDBO();
    $query = "SELECT ct1.id AS id_firstlevel, ct2.title AS title_secondlevel,
      ct2.id AS id_secondlevel
      FROM #__lf_flights lff INNER JOIN #__categories ct2 ON lff.category = ct2.id AND ct2.level=2 AND ct2.published=1 AND lff.published = 1
      INNER JOIN #__categories ct1 ON ct2.parent_id = ct1.id AND ct1.level=1 AND ct1.published=1
      GROUP BY id_secondlevel ORDER BY ct1.id ASC, ct2.id ASC, lff.origin ASC";
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    return $rows;
  }
  /**
   * Get First Level
   * @autor Dora Peña Antury
   * @email   dora.pena@periferia-it.com
   * @date    November 2013
   */
  public function getFirstLevel() {
    $db =& JFactory::getDBO();
    $query = "SELECT ct1.title AS title_firstlevel, ct1.id AS id_firstlevel
        FROM #__lf_flights lff INNER JOIN #__categories ct2 ON lff.category = ct2.id AND ct2.level=2 AND ct2.published=1 AND lff.published = 1
      INNER JOIN #__categories ct1 ON ct2.parent_id = ct1.id AND ct1.level=1 AND ct1.published=1
      GROUP BY id_firstlevel
      ORDER BY ct1.id ASC, ct2.id ASC, lff.origin ASC";
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    return $rows;
  }
  /**
   * This function normalize a string
   *
   * @param object  $string for normalize
   * @autor Dora Peña Antury
   * @email   dora.pena@periferia-it.com
   * @date    November 2013
   */
  function getNormalizeStr($string){
    utf8_encode($string);
    $a = array("Á","É","Í","Ó","Ú","á","é","í","ó","ú","à","è","ì","ò","ù","ä","ë","ï","ö","ü","â","ê","î","ô","û","ñ","ç"," ","&","$");
    $b = array("A","E","I","O","U","a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","n","c","","","");
    $string = str_replace($a, $b, $string);
    $string = preg_replace("[^A-Za-z0-9-]", "", $string);
    return strtolower($string);

  }
}