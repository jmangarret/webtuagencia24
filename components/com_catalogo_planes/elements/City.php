<?php
defined('_JEXEC') or die('Restricted access');

/**
 * Renders a city element
 *
 * @package		Joomla
 * @subpackage	Banners
 * @since		1.5
 */
class JElementCity extends JElement {

  /**
   * Element name
   *
   * @access	protected
   * @var		string
   */
  var $_name = 'City';

  function fetchElement($name, $value, &$node, $control_name) {

    //Instance the plugin
    JPluginHelper::importPlugin('amadeus', 'catalogoPlanes');
    $dispatcher = & JDispatcher::getInstance();
    $retval = $dispatcher->trigger('connectCatalogoPlanes', array($this->getXMLCities($node->_attributes["product"])));
    $retval = $retval[0];

    $cities = array();
    if(count($retval["city"]["item"])>0){
      if(!isset($retval["city"]["item"][0])){
        $cities["item"][0] = $retval["city"]["item"];
      }else{
        $cities["item"] = $retval["city"]["item"];
      }
    }else{
      $cities["item"] = array();
    }


    $options = array();
    $options[] = JHTML::_('select.option', '0', JText::_('CP.CONFIG.CITIES.ALL'), 'cid', 'name');
    foreach($cities["item"] as $item){
      $options[] = JHTML::_('select.option', $item["city_id"], $item["city_name"], 'cid', 'name');
    }
    return JHTML::_('select.genericlist', $options, '' . $control_name . '[' . $name . ']', 'class="inputbox"', 'cid', 'name', $value, $control_name . $name);
  }

  /**
   *
   * this function return the xmlObject of request cities
   * @param $productName
   */
  private function getXMLCities($productName){
    $data = new SimpleXMLElement('<Data></Data>');
    $data->addChild("type", "General");
    $data->addChild("method", "getAvaibleCities");
    $data->addChild("product");
    $data->product->addChild("name", $productName);
    $data->addChild("region");
    $data->region->addChild("id", 0);
    return $data;
  }

}
