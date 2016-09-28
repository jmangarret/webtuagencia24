<?php
defined('_JEXEC') or die('Restricted access');

/**
 * Renders a category element
 *
 * @package		Joomla
 * @subpackage	Banners
 * @since		1.5
 */
class JElementTourismType extends JElement {

  /**
   * Element name
   *
   * @access	protected
   * @var		string
   */
  var $_name = 'TourismType';

  function fetchElement($name, $value, &$node, $control_name) {

    //Instance the plugin
    JPluginHelper::importPlugin('amadeus', 'catalogoPlanes');
    $dispatcher = & JDispatcher::getInstance();
    $retval = $dispatcher->trigger('connectCatalogoPlanes', array($this->getXmlTourismType($node->_attributes["product"])));
    	
    $retval = $retval[0];

    $tourismType = array();
    if(count($retval["tourismType"]["item"])>0){
      if(!isset($retval["tourismType"]["item"][0])){
        $tourismType["item"][0] = $retval["tourismType"]["item"];
      }else{
        $tourismType["item"] = $retval["tourismType"]["item"];
      }
    }else{
      $tourismType["item"] = array();
    }


    $options = array();
    $options[] = JHTML::_('select.option', '0', JText::_('CP.CONFIG.TOURISMTYPE.ALL'), 'cid', 'name');
    foreach($tourismType["item"] as $item){
      $options[] = JHTML::_('select.option', $item["tourismtype_id"], $item["tourismtype_name"], 'cid', 'name');
    }
    return JHTML::_('select.genericlist', $options, '' . $control_name . '[' . $name . ']', 'class="inputbox"', 'cid', 'name', $value, $control_name . $name);
  }

  /**
   *
   * Return the list of category
   * @param $product
   */
  function getXmlTourismType($product){
    $data = new SimpleXMLElement('<Data></Data>');
    $data->addChild("type", "General");
    $data->addChild("method", "getTourismType");
    $data->addChild("product");
    $data->product->addChild("name", $product);
    return $data;
  }

}
