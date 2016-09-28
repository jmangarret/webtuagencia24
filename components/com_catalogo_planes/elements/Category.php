<?php
defined('_JEXEC') or die('Restricted access');

/**
 * Renders a category element
 *
 * @package		Joomla
 * @subpackage	Banners
 * @since		1.5
 */
class JElementCategory extends JElement {

  /**
   * Element name
   *
   * @access	protected
   * @var		string
   */
  var $_name = 'Category';

  function fetchElement($name, $value, &$node, $control_name) {

    //Instance the plugin
    JPluginHelper::importPlugin('amadeus', 'catalogoPlanes');
    $dispatcher = & JDispatcher::getInstance();
    $retval = $dispatcher->trigger('connectCatalogoPlanes', array($this->getXmlCategory($node->_attributes["product"])));
    $retval = $retval[0];

    $categories = array();
    if(count($retval["category"]["item"])>0){
      if(!isset($retval["category"]["item"][0])){
        $categories["item"][0] = $retval["category"]["item"];
      }else{
        $categories["item"] = $retval["category"]["item"];
      }
    }else{
      $categories["item"] = array();
    }


    $options = array();
    $options[] = JHTML::_('select.option', '0', JText::_('CP.CONFIG.CATEGORY.ALL'), 'cid', 'name');
    foreach($categories["item"] as $item){
      $options[] = JHTML::_('select.option', $item["category_id"], $item["category_name"], 'cid', 'name');
    }
    return JHTML::_('select.genericlist', $options, '' . $control_name . '[' . $name . ']', 'class="inputbox"', 'cid', 'name', $value, $control_name . $name);
  }

  /**
   *
   * Return the list of category
   * @param $product
   */
  function getXmlCategory($product){
    $data = new SimpleXMLElement('<Data></Data>');
    $data->addChild("type", "General");
    $data->addChild("method", "getCategory");
    $data->addChild("product");
    $data->product->addChild("name", $product);
    return $data;
  }

}
