<?php
defined('_JEXEC') or die('Restricted access');

/**
 * Renders a category element
 *
 * @package		Joomla
 * @subpackage	Banners
 * @since		1.5
 */
class JElementSupplier extends JElement {

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
    $retval = $dispatcher->trigger('connectCatalogoPlanes', array($this->getXmlSupplier($node->_attributes["product"])));
    $retval = $retval[0];

    $categories = array();
    if(count($retval["supplier"]["item"])>0){
      if(!isset($retval["supplier"]["item"][0])){
        $categories["item"][0] = $retval["supplier"]["item"];
      }else{
        $categories["item"] = $retval["supplier"]["item"];
      }
    }else{
      $categories["item"] = array();
    }


    $options = array();
    $options[] = JHTML::_('select.option', '0', JText::_('CP.CONFIG.CATEGORY.ALL'), 'cid', 'name');
    foreach($categories["item"] as $item){
      $options[] = JHTML::_('select.option', $item["supplier_id"], $item["supplier_name"], 'cid', 'name');
    }
    return JHTML::_('select.genericlist', $options, '' . $control_name . '[' . $name . ']', 'class="inputbox"', 'cid', 'name', $value, $control_name . $name);
  }

  /**
   *
   * Return the list of category
   * @param $product
   */
  function getXmlSupplier($product){
    $data = new SimpleXMLElement('<Data></Data>');
    $data->addChild("type", "General");
    $data->addChild("method", "getSupplier");
    $data->addChild("product");
    $data->product->addChild("name", $product);
    return $data;
  }

}
