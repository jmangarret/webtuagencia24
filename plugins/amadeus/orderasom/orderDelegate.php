<?php
/**
 * @file com_sales/admin/libsales/gdsData.php
 * @ingroup _plg_eretail
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


/**
 *
 */
class OrderDelegate implements AsomInterfaceAir
{
    private $_raw  = null;

    private $_user = null;
  private $_paymentMethod = null;

    public function __construct(&$response, &$user)
    {
        $this->_raw  = &$response;
        $this->_user = &$user;
    }

    public function getOrder()
    {
 
        // Objeto que va a almacenar la informacion de la orden
        $order = new stdClass();
         
      
        // Inicializando el XML, para poder usar XPath
        $xml = new DOMDocument();
        $xml->loadXML($this->_raw);

        $xpath = new DOMXPath($xml);
        $xpath->registerNameSpace('ns0', 'http://www.opentravel.org/OTA/2003/05');

        // obteniendo el usuario
        $order->user_id = $this->_user->id;

        // Obteniendo el record
        $results = $xpath->query('/ns0:OTA_AirBookRS/ns0:AirReservation/ns0:BookingReferenceID/@ID');
        foreach($results as $result)
            $order->recloc = $result->nodeValue;

        $query = '/ns0:OTA_AirBookRS/ns0:AirReservation/ns0:TravelerInfo/ns0:AirTraveler';
        // Obteniendo el nombre
         $order->firstname = $_POST['wsform']['contactname'];
        
/*
        // Obteniendo el apellido
        $results = $xpath->query($query.'/ns0:PersonName/ns0:Surname');
        foreach($results as $result)
        {
            $order->lastname = $result->nodeValue;
            break;
        }
*/
        // Obteniendo el correo
        $results = $xpath->query($query.'/ns0:Email');
        foreach($results as $result)
            $order->email = $result->nodeValue;

        // Obteniendo el telefono
        $results = $xpath->query($query.'/ns0:Telephone/@PhoneNumber');
        foreach($results as $result)
            $order->phone = $result->nodeValue;

        // Obteniendo el total de la reserva
        $results = $xpath->query('/ns0:OTA_AirBookRS/ns0:AirReservation/ns0:PriceInfo/ns0:ItinTotalFare/ns0:TotalFare/@Amount');
        foreach($results as $result)
            $order->total = $result->nodeValue;

        // Obteniendo el total de la tarifa
        $results = $xpath->query('/ns0:OTA_AirBookRS/ns0:AirReservation/ns0:PriceInfo/ns0:ItinTotalFare/ns0:BaseFare/@Amount');
        foreach($results as $result)
            $order->fare = $result->nodeValue;

        // Obteniendo el total de los impuestos
        $results = $xpath->query('/ns0:OTA_AirBookRS/ns0:AirReservation/ns0:PriceInfo/ns0:ItinTotalFare/ns0:Taxes/@Amount');
        foreach($results as $result)
            $order->taxes = $result->nodeValue;

            
         //Tipo de Producto 
		$order->product_type= $_POST['wsform']['product_type'];
        if($_POST['wsform']['paymentmethod']==0){
		$order->status = 2;
        }else if($_POST['wsform']['paymentmethod']==1){
        $order->status = 1;	
        }
		
		//Verifica si el vuelo es nacional o Internacional

		
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
		//Guarda el nombre del producto
        $order->product_name= !$db->loadResult() ? 'Vuelo Nacional' :'Vuelo Internacional';
		
       
		
		
        // Parametro de configuracion del componente de tarifa administrativa
        //$params = JComponentHelper::getParams('com_adminfee');
        $params = JComponentHelper::getParams('com_fees');
        $IvaTA  = $params->get('valIvaTaPortal', 16);

        // Obteniendo el total de la tarifa administrativa
        $ta = 0;
        $results = $xpath->query('/ns0:OTA_AirBookRS/ns0:AirReservation/ns0:PriceInfo/ns0:ItinTotalFare/ns0:Fees/@Amount');
        foreach($results as $result)
            $ta = (float)$result->nodeValue;

        $order->taxes_ta = round(($ta / (1 + ($IvaTA / 100))) * ($IvaTA / 100), 2);
        $order->fare_ta  = $ta - $order->taxes_ta;

        // Obteniendo la aerolinea validadora
        $results = $xpath->query('/ns0:OTA_AirBookRS/ns0:AirReservation/ns0:Ticketing/ns0:TicketingVendor/@Code');
        foreach($results as $result)
            $order->provider = $result->nodeValue;

        return $order;
    }
	
 
    public function getPassengers(){}

    public function getItinerary(){}

    public function getValues(){}

    public function getDataRaw()
    {
        return $this->_raw;
    }

    public function getIDPaymentMethod()
    {
        return $this->_paymentMethod;
    }

}

