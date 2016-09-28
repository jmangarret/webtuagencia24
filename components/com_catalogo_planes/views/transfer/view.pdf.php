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

  function __construct($tpl = null){
    //Importamos el plugin de formato de moneda
    $this->pluginCatalogo = & JDispatcher::getInstance();
    $this->catalogoComponentConfig=&JComponentHelper::getParams('com_catalogo_planes');
    $this->root_path = JURI::base().'components/com_catalogo_planes/assets/';
    parent::__construct($tpl);
  }

  function display($tpl = null) {
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

}