<?php
/**
 *
 */
defined('_JEXEC') or die;

abstract class ProcessHelper
{

    public function getItineraries($category, $itinerary, $state = 1)
    {
        $db    = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('id, originname, destinyname')
              ->from('#__lf_flights');

        if($category != 0)
        {
            $query->where('category = '.$db->Quote($category));
        }

        if($itinerary != 0)
        {
            $query->where('id = '.$db->Quote($itinerary));
        }

        if($state != '')
        {
            $query->where('published = '.$db->Quote((int)$state));
        }

        $db->setQuery($query);

        return $db->loadObjectList();
    }

    public function processItinerary($id)
    {
        if($id == 0)
        {
            return array('type' => 'error', 'msg' => JText::_('COM_LOWFARES_ID_EMPTY'));
        }
        else
        {
            JLoader::register('AawsController', JPATH_ROOT.DS.'components'.DS.'com_aaws'.DS.'controller.php');
            JLoader::register('AawsControllerAir', JPATH_ROOT.DS.'components'.DS.'com_aaws'.DS.'controllers'.DS.'air.php');
            JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_lowfares'.DS.'tables');

            $table = JTable::getInstance('flights');
            $table->load($id);

            $Air = new AawsControllerAir();		

            if($table->get('offset', '') == '')
            {
                $date1 = $table->get('departure');
            }
            else
            {
                $date1 = date('Y-m-d', strtotime('+'.$table->get('offset').' days'));
            }

            $parts = explode('-', $date1);
            $date2 = date('Y-m-d', strtotime('+'.$table->get('duration').' days', mktime(0, 0, 0, $parts[1], $parts[2], $parts[0])));

            $data = array();
            $data['TRIP_TYPE']          = 'R'; 
            $data['B_LOCATION_1']       = $table->get('origin');
            $data['E_LOCATION_1']       = $table->get('destiny');
            $data['B_DATE_1']           = $date1;
            $data['B_DATE_2']           = $date2;
            $data['TRAVELLER_TYPE_ADT'] = '1';
            $data['TRAVELLER_TYPE_CHD'] = '0';
            $data['TRAVELLER_TYPE_INF'] = '0';
            $data['CABIN']              = 'Economy';   
            $data['AIRLINE']            = '';        
            $data['MAX_CONNECTIONS']    = '';			
		     	
            try
            {
                $response = $Air->processData($data);

                /**
                 * Procesando la respuesta
                 */
                $xml = new DOMDocument();
                $xml->loadXML($response);

                $xpath = new DOMXPath($xml);
                $xpath->registerNameSpace('ns0', 'http://www.opentravel.org/OTA/2003/05');

                $query  = '/ns0:OTA_AirLowFareSearchRS';
                $query .= '/ns0:PricedItineraries';
                $query .= '/ns0:PricedItinerary[1]';
                $query .= '/ns0:AirItineraryPricingInfo';
                $query .= '/ns0:ItinTotalFare';
                $query .= '/ns0:TotalFare';
                $query .= '/@Amount';

                $value   = 0;
                $results = $xpath->query($query);
                foreach($results as $val)
                {
                    $value = $val->value;
                }

                $table->value = $value;

                $table->store();

                return array('type' => 'success', 'msg' => number_format($value, 2, ',', '.'));
            }
            catch(Exception $e)
            {
                $table->value     = 0;
                $table->published = 0;

                $table->store();

                return array('type' => 'error', 'msg' => $e->getMessage());
            }
        }
    }

}

