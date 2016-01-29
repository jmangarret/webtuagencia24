<?php
/**
 * @file com_sales/admin/libsales/gdsData.php
 * @ingroup _plg_eretail
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

/**
 * desc
 */
class plgAmadeusAdministrativeFee extends JPlugin
{
    function plgAdministrativeFee(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }

    function onAfterAirResponse(&$response, $command)
    {
        $transform = new AdministrativeFeeTransform($response, $command);

        return $transform->applyTransform();
    }
}

class AdministrativeFeeTransform
{

    private $_response = null;

    private $_command  = null;

    private $_xml      = null;


    public function __construct(&$response, $command)
    {
        $this->_response = &$response;
        $this->_command  = $command;
    }

    private function _flightType()
    {
        $xml = new DOMDocument();
        $xml->loadXML($this->_response);

        $xpath = new DOMXPath($xml);
        $xpath->registerNameSpace('ns0', 'http://www.opentravel.org/OTA/2003/05');

        $cities = array();
        // XPath para ubicar solo la primera recomendacion, ya que con esta es suficiente para hacer las validaciones
        if(strpos($this->_response, 'OTA_AirLowFareSearch'))
        {
            $query  = '/ns0:OTA_AirLowFareSearchRS';
            $query .= '/ns0:PricedItineraries';
            $query .= '/ns0:PricedItinerary[1]';
            $query .= '/ns0:AirItinerary';
            $query .= '/ns0:OriginDestinationOptions';
            $query .= '/ns0:OriginDestinationOption';
        }
        else
        {
            $query  = '/ns0:OTA_AirBookRS';
            $query .= '/ns0:AirReservation';
            $query .= '/ns0:OriginDestinationOptions';
            $query .= '/ns0:OriginDestinationOption';
        }

        // Se obtiene los codigos IATA de origen y llegada de la disponbilidad, para saber si es nacional o internacional
        $results = $xpath->query($query.'/ns0:FlightSegment[1]/ns0:DepartureAirport/@LocationCode');
        foreach($results as $code)
            $cities[$code->value] = true;

        $results = $xpath->query($query.'/ns0:FlightSegment[position() = last()]/ns0:ArrivalAirport/@LocationCode');
        foreach($results as $code)
            $cities[$code->value] = true;


        $db   = JFactory::getDBO();
        $stmt = $db->getQuery(true);
        $lang = JFactory::getLanguage(); 
        $lang = substr($lang->getTag(), 3);

        // Select que me indica si hay ciudades fuera del pais nacional
        $stmt->select('COUNT(1)');
        $stmt->from('#__qs_cities AS qc');
        $stmt->join('inner', '#__qs_countries AS qp ON qc.country = qp.id');

        $stmt->where('qc.iata IN (\''.join('\', \'', array_keys($cities)).'\')');
        // TODO mams827
        // Es necesario dejar configurable el pais, debe validarse en donde debe quedar centralizada dicha informacion
        $stmt->where('qp.codigo <> '.$db->Quote('VE'));
        $stmt->where('qc.lenguaje = '.$db->Quote($lang));
        $stmt->where('qp.lenguaje = '.$db->Quote($lang));

        $db->setQuery($stmt);

        $national = !$db->loadResult() ? 1 : 3;

        // Se valida cuantos segementos tiene la disponibilidad, para saber si es OW o RT
        $segments = 0;
        if(strpos($this->_response, 'OTA_AirLowFareSearch'))
        {
            $results  = $xpath->query($query.'[@RefNumber=1]');
        }
        else
        {
            $results  = $xpath->query($query.'[@RefNumber=2]');
        }

        if($results->length)
            $segments = 1;

        return $national + $segments;
    }

    private function _getAdministrativeFeeXMLVersion()
    {
        // Conexion a la base de datos
        $db    = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Parametro de configuracion del componente de tarifa administrativa
        $params = JComponentHelper::getParams('com_fees');
        $valIva = $params->get('valIvaTaPortal', 16);
		
        // Documento XML para agregar la informacion de la tarifa
        $doc      = new DOMDocument('1.0');
        $root     = $doc->appendChild($doc->createElement('AdministrativeFee'));
        $airlines = $root->appendChild($doc->createElement('AirLines'));
        
        $query->select('ta.valuetype AS Type, ta.airline AS Code, ta.published, vta.minfare AS FromValue, vta.maxfare AS ToValue, 
						vta.charge_adult AS ValueAdult, vta.charge_senior AS ValueSenior, vta.charge_child AS ValueChild, vta.charge_infant ValueInfant ,  vta.trip AS FlightType, IF(vta.minfare = 0 AND vta.maxfare = 0, 1, 2) AS isRange ');
        $query->from('#__fee_adminfare AS ta');
        
        $query->join('INNER', '#__qs_airlines  AS a ON ta.airline = a.codigo');
        $query->join('INNER', '#__fee_values AS vta  ON ta.id = vta.fare_id');
        
        $query->where('ta.published = 1');
        
        if($this->_flightType() == 1){ $flightType = 'ON'; }
    	if($this->_flightType() == 2){ $flightType = 'RN'; }
    	if($this->_flightType() == 3){ $flightType = 'OI'; }
    	if($this->_flightType() == 4){ $flightType = 'RI'; }
        
        $query->where("vta.trip = '$flightType'");
        
        // Se ordenan los resultados por los valores (para que el proceso coincida con los rangos)
        $query->order('vta.id ASC');
		
        
        $db->setQuery($query);
        $rows    = $db->loadObjectList();
        //print_R($rows); Die();
        
        $airCode = 'XXX';
        foreach($rows as $row)
        {
            if($airCode != $row->Code)
            {
                $airline = $doc->createElement('AirLine');
                if($row->Code == '--' ){
                	$airline->setAttributeNode(new DOMAttr('Code', ''));
                }
                else{
                	$airline->setAttributeNode(new DOMAttr('Code', $row->Code));
                }
				$airline->setAttributeNode(new DOMAttr('Type', $row->Type));
                $airlines->appendChild($airline);
                $airCode = $row->Code;
                
            }

            if($row->isRange == 1)
            {
                $valueAdult = $doc->createElement('ValueAdult', $row->ValueAdult);
                $valueSenior= $doc->createElement('ValueSenior', $row->ValueSenior);
                $valueChild= $doc->createElement('ValueChild', $row->ValueChild);
                $valueInfant= $doc->createElement('ValueInfant', $row->ValueInfant);
                $airline->appendChild($valueAdult);
                $airline->appendChild($valueSenior);
                $airline->appendChild($valueChild);
                $airline->appendChild($valueInfant);
            }
            elseif($row->isRange == 2)
            {
                $range = $doc->createElement('Range');
                $range->setAttributeNode(new DOMAttr('From', $row->FromValue));
                $range->setAttributeNode(new DOMAttr('To', $row->ToValue));

                $valueAdult = $doc->createElement('ValueAdult', $row->ValueAdult);
                $valueSenior= $doc->createElement('ValueSenior', $row->ValueSenior);
                $valueChild= $doc->createElement('ValueChild', $row->ValueChild);
                $valueInfant= $doc->createElement('ValueInfant', $row->ValueInfant);
                $range->appendChild($valueAdult);
                $range->appendChild($valueSenior);
                $range->appendChild($valueChild);
                $range->appendChild($valueInfant);
                $airline->appendChild($range);
            }
        }

        // Agregando el IVA
        $iva = $doc->createElement('Iva');
        $iva->setAttributeNode(new DOMAttr('Porcent', $valIva));
        $root->appendChild($iva);
		
        
        // Obteneido el valor del descuento
        $groups = array(0);
        $user   = JFactory::getUser();
        if($user->get('id') != 0)
            $groups = $user->get('groups');
		
         
        $query = $db->getQuery(true);
        $query->select('gr.discount AS Discount');
        $query->from('#__fee_groups AS gr');
        $query->where('gr.usergroupid = '. current($groups));
		
        $db->setQuery($query);
        $valDiscount = $db->loadObject();
        
        if($valDiscount)
            $valDiscount = $valDiscount->Discount;
        else
            $valDiscount = 0;

        $discount = $doc->createElement('Discount');
        $discount->setAttributeNode(new DOMAttr('Porcent', $valDiscount));
        $root->appendChild($discount);
		
        //$doc->formatOutput = true;
        //echo $doc->saveXML();
        //die();
        
        return substr($doc->saveXML(), 22);
    }

    public function applyTransform()
    {
        preg_match('#(</OTA_[^>]*>)#', $this->_response, $matches);

        $xmlString  = explode($matches[1], $this->_response);
        $xmlString  = $xmlString[0];
        $xmlString .= $this->_getAdministrativeFeeXMLVersion();
        $xmlString .= $matches[1];

   
        $xml = new DOMDocument();
        $xml->loadXML($xmlString);
 

        $path = JPATH_SITE.DS.'plugins'.DS.'amadeus'.DS.'administrativefee'.DS.'transform.xslt';
        if(!JFile::exists($path))
            throw new Exception(JText::_('PLG_AAWS_TEMPLATE_DOES_NOT_EXIST'));

        $xsl = new DOMDocument();
        $xsl->load($path);

        $proc = new xsltprocessor();
        $proc->importStyleSheet($xsl);

        $this->_response = substr($proc->transformToXML($xml), 22);

        return true;
    }
}
