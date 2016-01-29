<?php
/**
 *
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Clase encargada de retornar la informacion de la orden
 */
class AsomClassOrder extends JObject
{

    private $_order = null;

    private $_raw   = null;
    
    private $_add   = null;
	private $_his   = null;

    private $_xpath = null;


    public function __construct($order)
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__aom_orders');

        if(is_array($order))
        {
            foreach($order as $key => $value)
            {
                $query->where($db->quoteName($key).' = '.$db->Quote($value));
            }
        }
        else
        {
            $query->where('id = '.$db->Quote((int) $order));
        }

        $db->setQuery($query);

        $order = $db->loadObject();
        if($order == NULL)
        {
            throw new Excecption('ERROR_ORDER');
        }

        $this->_order = $order;

        $this->_loadRaw();
        $this->_loadRawAditional();
        $this->_loadRawHistory();
       
    }


    private function _loadRaw()
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('data');
        $query->from('#__aom_source');
        $query->where('order_id = '.$db->Quote($this->_order->id));

        $db->setQuery($query);

        $raw = @unserialize($db->loadResult());
        
        if($raw)
            $this->_raw = $raw;
        else
            $this->_raw = $db->loadResult(); 
    }
    private function _loadRawAditional()
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('AdditionalInfo');
        $query->from('#__aom_source');
        $query->where('order_id = '.$db->Quote($this->_order->id));

        $db->setQuery($query);

        $raw = @unserialize($db->loadResult());
    
        if($raw)
            $this->_add = $raw;
        else
            $this->_add = $db->loadResult();
    }
   //Obtiene la informacion Historial
    
private function _loadRawHistory()
    {
   $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('a.* ,u.name');
        $query->from('#__aom_history a ,#__users u');
        $query->where('a.user_id =u.id and a.order_id = '.$db->Quote($this->_order->id));
        $query->order('fecsis DESC');
        $db->setQuery($query);
 
        $this->_his= $db->loadObjectList();;
    }
    
    
    /**
     * Obtiene la información de la orden
     */
    public function getOrder()
    {
        return $this->_order;
    }
    public function getAditionalInfo()
    {
        return $this->_add;
    }
    /**
     * Obtiene todos los valores de la orden, detallados
     * por el tipo de pasajero, además la cantidad de pasajeros.
     *
     *   array
     *   (
     *     'passengers' => array
     *     (
     *       'TYPE (ADT, CHD, INF)' => (int) quantity
     *     ),
     *
     *     'values' => array
     *     (
     *       'TAX TYPE' => array
     *       (
     *         'TYPE (ADT, CHD, INF)' => (float) amount
     *       )
     *     )
     *   )
     */
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

    /**
     *
     */
    public function getContactInformation()
    {
        $xpath = $this->_getXPathElement();
        $info  = new stdClass();

        $query  = '/ns0:OTA_AirBookRS/ns0:AirReservation/';

        // Localizador para bicar la reserva
        $results = $xpath->query($query.'/ns0:BookingReferenceID/@ID');
        foreach($results as $result)
        {
            $info->recloc = $result->nodeValue;
        }

        // Apellido para ubicar la reserva
        $results = $xpath->query($query.'/ns0:TravelerInfo/ns0:AirTraveler[1]/ns0:PersonName/ns0:Surname');
        foreach($results as $result)
        {
            $info->lastname = $result->nodeValue;
        }

        // Telefono dado por el cliente
        $results = $xpath->query($query.'/ns0:AirTraveler/ns0:Telephone/@PhoneNumber');
        foreach($results as $result)
        {
            $info->phone = $result->nodeValue;
        }

        // Correo de contacto
        $results = $xpath->query($query.'/ns0:AirTraveler/ns0:Email');
        foreach($results as $result)
        {
            $info->email = $result->nodeValue;
        }

        return $info;
    }

    /**
     *
     */
    public function getPassengers()
    { 
	
        $xpath      = $this->_getXPathElement();
        $passengers = array();

        $query  = '/ns0:OTA_AirBookRS/ns0:AirReservation/ns0:TravelerInfo/ns0:AirTraveler';

        $results = $xpath->query($query);
                
        foreach($results as $result)
        { 
            $info = new stdClass();

            // Tipo de pasajero
            $info->type   = $result->getAttribute('PassengerTypeCode');

            // Genero del pasajero
            $info->gender = $result->getAttribute('Gender');

            // Primer nombre
            $subresults = $xpath->query('ns0:PersonName/ns0:GivenName', $result);
            foreach($subresults as $subresult)
            {
                $info->firstname = $subresult->nodeValue;
            }

            // Segundo nombre
            $subresults = $xpath->query('ns0:PersonName/ns0:Surname', $result);
            foreach($subresults as $subresult)
            {
                $info->lastname = $subresult->nodeValue;
            }

            $passengers[] = $info;
        }

        return $passengers;
    }
    /**
     *
     */
    public function getItinerary()
    {
        $xpath     = $this->_getXPathElement();
        $itinerary = array();

        $query  = '/ns0:OTA_AirBookRS/ns0:AirReservation';
        $query .= '/ns0:OriginDestinationOptions/ns0:OriginDestinationOption';

        $results = $xpath->query($query);
       
        foreach($results as $result)
        {
            $segment    = array();
            $subresults = $xpath->query('ns0:FlightSegment', $result);
            
            foreach($subresults as $subresult)
            {

            	$flight = new stdClass();

                $bdate = JFactory::getDate($subresult->getAttribute('DepartureDateTime'));
                $edate = JFactory::getDate($subresult->getAttribute('ArrivalDateTime'));
				$flight->paradas	  = $subresult->getAttribute('StopQuantity');
                $flight->flightnumber = $subresult->getAttribute('FlightNumber');
                $flight->bdate        = $bdate->format(JText::_('AOM_ITINERARY_DATE_FORMAT'));
                $flight->edate        = $edate->format(JText::_('AOM_ITINERARY_DATE_FORMAT'));

                // Aerolinea
                $data = $xpath->query('ns0:OperatingAirline', $subresult);
                foreach($data as $info)
                {
                    $flight->airline = $info->getAttribute('CompanyShortName');
                }

                // Informacion de salida
                $data = $xpath->query('ns0:Comment', $subresult);
                foreach($data as $info)
                {
                    list($key, $value) = preg_split('/:\s/', $info->nodeValue);
                    switch($key)
                    {
                    case 'DepartureCityCode':
                        $flight->blocationcode = $value;
                        break;
                    case 'DepartureAirportName':
                        $flight->blocationname = $value;
                        break;
                    case 'DepartureCityName':
                        $flight->blocationcity = $value;
                        break;
                    case 'DepartureStateName':
                        $flight->blocationstate = $value;
                        break;
                    case 'DepartureCountryName':
                        $flight->blocationcountry = $value;
                        break;
                    case 'ArrivalCityCode':
                        $flight->elocationcode = $value;
                        break;
                    case 'ArrivalAirportName':
                        $flight->elocationname = $value;
                        break;
                    case 'ArrivalCityName':
                        $flight->elocationcity = $value;
                        break;
                    case 'ArrivalStateName':
                        $flight->elocationstate = $value;
                        break;
                    case 'ArrivalCountryName':
                        $flight->elocationcountry = $value;
                        break;
                    }
                }
                //datos de tipo de tipo de avion
                $equipment = $xpath->query('ns0:Equipment', $subresult);
                foreach($equipment as $tipo)
                {
                    $flight->tipoavion = $tipo->nodeValue;
                }
                //datos de tipo de tipo de cabina
                $cabina = $xpath->query('ns0:BookingClassAvails/@CabinType', $subresult);
                foreach($cabina as $tipocab)
                {
                    $flight->tipocabina = $tipocab->nodeValue;
                }
                $segment[] = $flight;
            }
            

            $itinerary[] = $segment;
        }
        //Falta la aerolinea validadora mams827

        return $itinerary;
    }

    /**
     *
     */
    public function getHistory()
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__aom_history');
        $query->where('order_id = '.$db->Quote($this->_order->id));
        $query->order('fecsis DESC, id DESC');

        $db->setQuery($query);

        return $db->loadObjectList();
    }

    /**
     * Obtiene los valores requeridos por cualquier medio de pago (Colombia)
     *   + Total
     *   + Base
     *   + Iva
     *   + Tarifa Administrativa
     *   + Base Tarifa Administrativa
     *   + Iva Tarifa Administrativa
     *   + Tarifa Aeroportuaria
     */
    public function getPaymentValues()
    {
        $data   = $this->getValues();
        $values = array(
            'fare'   => 0,
            'iva'    => 0,
            'taereo' => 0,
            'taxes'  => 0,
            'ta'     => 0,
            'total'  => 0
        );

        foreach($data->passengers as $pax => $quantity)
        {
            foreach($data->values[$pax] as $tax => $amount)
            {
                $money = (float)($amount * $quantity);

                // Se valida el concepto y se suma seguns corrsponda, para luego
                // organizarlos y enviarcelos al medio de pago
                if($tax == 'FARE')
                    $values['fare'] += $money;

                elseif($tax == 'ADMIN')
                    $values['ta'] += $money;

                elseif(preg_match('/^YS/', $tax))
                    $values['iva'] += $money;

                elseif(preg_match('/^CO/', $tax))
                    $values['taereo'] += $money;
                else
                    $values['taxes'] += $money;

                $values['total'] += $money;
            }
        }

        if($values['ta'] != $this->_order->fare_ta + $this->_order->taxes_ta)
            throw new Exception('TA ERROR');

        $paymentInfo = new stdClass();

        $paymentInfo->TotalAmount          = $values['total'] - $values['ta'] - $values['taereo'];
        $paymentInfo->TaxAmount            = $values['iva'];
        $paymentInfo->DevolutionBaseAmount = $paymentInfo->TaxAmount > 0 ? $values['fare'] : 0;
        $paymentInfo->ServiceFee           = $values['ta'];
        $paymentInfo->ServiceFeeDevolution = $this->_order->fare_ta;
        $paymentInfo->ServiceFeeTax        = $this->_order->taxes_ta;
        $paymentInfo->AirportTax           = $values['taereo'];

        return $paymentInfo;
    }

    /**
     * Obtiene la informacion si los vuelos de la orden actual
     * son nacionales o internacionales (true, false)
     */
    public function isNational()
    {
        // Inicializando el XML, para poder usar XPath
        $xml = new DOMDocument();
        $xml->loadXML($this->_raw);

        $xpath = new DOMXPath($xml);
        $xpath->registerNameSpace('ns0', 'http://www.opentravel.org/OTA/2003/05');

        $cities = array();
        // XPath para ubicar solo la primera recomendacion, ya que con esta es suficiente para hacer las validaciones
        $query  = '/ns0:OTA_AirBookRS';
        $query .= '/ns0:AirReservation';
        $query .= '/ns0:OriginDestinationOptions';
        $query .= '/ns0:OriginDestinationOption';

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

        return !$db->loadResult() ? true : false;
    }

    public function updateOrder($data, $note = '', $user = null)
    {
        // Se identifica el ID del usuario que actualiza la orden
        if($user == null)
        {
            $user = JFactory::getUser();
            $user = $user->get('id', 0);
        }
        //Datos de la Orden
        $info      = JText::_('AOM_CHANGED');
        $delimiter = '';
        foreach($data as $key => $value)
        {
            $this->_order->$key = $value;
            
            // informacion para mostrar en el log
            $info     .= $key.'='.$value.$delimiter;
            $delimiter = '; ';
        }

        // Incluyendo las tablas
        $tables = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_asom'.DS.'tables';
        JTable::addIncludePath($tables);

        $order = JTable::getInstance('orders');
        if(!$order->save($this->_order))
        {
            $this->setError($order->getError());
            return false;
        }


        //Datos del historial
        $dHistory = array(
            'order_id' => $this->_order->id,
            'user_id'  => $user,
            'note'     => ($note == '' ? '' : $note) ,
            'status'   => $order->status
        );

        $history = JTable::getInstance('history');

        if(!$history->save($dHistory))
        {
            $this->setError($history->getError());
            return false;
        }

        return true;
    }

    public function savePayment($paymentInfo)
    {
        $_payment = $this->getPayment();

        $tables = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_asom'.DS.'tables';
        JTable::addIncludePath($tables);

        $payment = JTable::getInstance('payments');
        $paymentInfo['order_id'] = $this->_order->id;

        if($_payment)
        {
            $paymentInfo['id'] = $_payment->id;
        }

        if(!$payment->save($paymentInfo))
        {
            $this->setError($payment->getError());
            return false;
        }

        return true;
    }


    public function getPayment()
    {
        if($this->_order == null)
        {
            return false;
        }

        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('*')
              ->from('#__aom_payments')
              ->where('order_id = '.$db->Quote($this->_order->id));

        $db->setQuery($query);

        $payment = $db->loadObject();
        if($payment == NULL)
        {
            return false;
        }

        return $payment;
    }


    public function getSource()
    {
        return $this->_raw;
    }
    public function getAditional()
    {
        return $this->_add;
    }
public function getHistorial()
    {
        return $this->_his;
    }  
    
    /**
     *
     */
    private function _getXPathElement()
    {
        if($this->_xpath == null)
        {
            // Inicializando el XML, para poder usar XPath
            $xml = new DOMDocument();
            $xml->loadXML($this->_raw);

            $this->_xpath = new DOMXPath($xml);
            $this->_xpath->registerNameSpace('ns0', 'http://www.opentravel.org/OTA/2003/05');
        }

        return $this->_xpath;
    }
    private function _getXPathElement_add()
    {
        if($this->_xpath_add == null)
        {
            // Inicializando el XML, para poder usar XPath
            $xml = new DOMDocument();
            $xml->loadXML($this->_add);
            $this->_xpath_add = new DOMXPath($xml);
            $this->_xpath_add->registerNameSpace('ns0', 'http://www.opentravel.org/OTA/2003/05');
        }

        return $this->_xpath_add;
    }
    /*
     * Funcion para traer los datos de nombre y codigo de provedor  
     * 
     */
    public function getProveedor()
    {
        $xpath = $this->_getXPathElement();

        //$query  = '/ns0:OTA_AirBookRS/ns0:AirReservation/';
        //$results    = $xpath->query($query.'/ns0:Ticketing');
        $query  = '/ns0:OTA_AirBookRS/ns0:AirReservation/';

        // Obteniendo el numero de pasajeros por tipo
        $proveedor = array();
        $results    = $xpath->query($query.'/ns0:Ticketing/ns0:TicketingVendor');
        foreach($results as $result)
        {
            $proveedor[] = $result->getAttribute('Code');
        }
        
        
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('dtl_cod,dtl_name');
        $query->from('#__am_dt_airline');
        $query->where("dtl_cod = '".$proveedor[0]."'");

        $db->setQuery($query);
//echo '<br>';print_r($db->loadObjectList());
        return $db->loadObjectList();

    }    
}

