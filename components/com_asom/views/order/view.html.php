<?php
/**
 * @file com_asom/admin/controller.php
 * @ingroup _comp_adm
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class AsomViewOrder extends JView
{

    public function display()
    {
        $model = $this->getModel();
        $model->setOrderID($this->order);

        $this->assign('XMLData', $model->getSource());
        $this->assign('Order', $model->getOrder());
		$this->assign('XMLAdd', $model->getAditional());
     	$this->assign('History', $model->getHistorial());
     	
     	

        // Se adiciona la hoja de estilos de la disponibilidad
        // del componente aaws.
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::root().'media/amadeus/com_aaws/css/air/styles.css');
        $doc->addScript(JUri::root().'components/com_asom/js/script.js');

        parent::display();
    }
public function transformArrayValues($info)
    {
        $result = array();

        foreach($info->values as $type => $values)
        {
            foreach($values as $tax => $value)
            {
                if(!isset($result[$tax]))
                    $result[$tax] = array();

                $result[$tax][$type] = $value;
            }
        }

        $data             = new stdClass();
        $data->passengers = $info->passengers;
        $data->values     = $result;

        return $data;
    }
 private function _getXPathElement()
    {
    	   $model = $this->getModel();
        $model->setOrderID($this->order);
        if($this->_xpath == null)
        {
            // Inicializando el XML, para poder usar XPath
            $xml = new DOMDocument();
            $xml->loadXML($model->getSource());

            $this->_xpath = new DOMXPath($xml);
            $this->_xpath->registerNameSpace('ns0', 'http://www.opentravel.org/OTA/2003/05');
        }

        return $this->_xpath;
    }
    
    public function getAirlines($air){
    	$db   = JFactory::getDBO();
       

        $query = $db->getQuery(true);

        $query->select('nombre')
              ->from('#__qs_airlines')
              ->where('codigo = '.$db->Quote($air));
        
        $db->setQuery($query);
 
        return  $db->loadObjectList();

        
    }
    
  public function getValues()
    {
        $xpath = $this->_getXPathElement();

        $query  = '/ns0:OTA_AirBookRS/ns0:AirReservation/ns0:PriceInfo/';
        $query .= 'ns0:PTC_FareBreakdowns/ns0:PTC_FareBreakdown';

        // Obteniendo el numero de pasajeros por tipo
        $passengers = array();
        $results    = $xpath->query($query.'/ns0:PassengerTypeQuantityType');
        foreach($results as $result)
        {
            $passengers[$result->getAttribute('Code')] = $result->getAttribute('Quantity');
        }

        $values = array();
        // Se itera por cada pasajero, para obtener la tarifa e impuestos
        foreach($passengers as $type => $quantity)
        {
            $values[$type] = array();

            // Subquery desde el cual se consultan los valores
            $subquery  = $query.'[ns0:PassengerTypeQuantityType/@Code = \''.$type.'\']';
            $subquery .= '/ns0:PassengerFare';

            // Obteniendo la tarifa
            $results = $xpath->query($subquery.'/ns0:BaseFare/@Amount');
            foreach($results as $result)
                $values[$type]['FARE'] = $result->nodeValue;

            // Obteniendo la tarifa administrativa
            $results = $xpath->query($subquery.'/ns0:Fees/@Amount');
            $values[$type]['ADMIN'] = 0;
            foreach($results as $result)
                $values[$type]['ADMIN'] = $result->nodeValue;

            //Obteniendo los impuestos
            $results = $xpath->query($subquery.'/ns0:Taxes/ns0:Tax');
            foreach($results as $result)
                $values[$type][$result->getAttribute('TaxCode')] = $result->getAttribute('Amount');
        }

        $data = new stdClass();
        $data->passengers = $passengers;
        $data->values     = $values;

        return  $data;
    }
}
