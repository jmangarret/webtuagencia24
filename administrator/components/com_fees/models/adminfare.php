<?php
/**
 *
 */

defined('_JEXEC') or die("Invalid access");

/**
 *
 */
class FeesModelAdminFare extends JModel
{

    private $_table = null;


    public function __construct()
    {
        parent::__construct();

        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fees'.DS.'tables');
        $this->_table = JTable::getInstance('adminfare');
    }
 
    public function getData($afare)
    {
        $afare = (int) $afare;
        $data  = JRequest::getVar('jform', array(), 'post', 'array');

        if($afare === 0 && !count($data))
        {
            $object = new stdClass();

            $object->grid = array(
                'ON' => array(
                    'min' => array(0),
                    'max' => array('M'),
                    'value_adult' => array(0),
            		'value_senior' => array(0),
		            'value_child' => array(0),
		            'value_infant' => array(0)
                 ),
                'RN' => array(
                    'min' => array(0),
                    'max' => array('M'),
                    'value_adult' => array(0),
            		'value_senior' => array(0),
		            'value_child' => array(0),
		            'value_infant' => array(0)
                 ),
                'OI' => array(
                    'min' => array(0),
                    'max' => array('M'),
                    'value_adult' => array(0),
            		'value_senior' => array(0),
		            'value_child' => array(0),
		            'value_infant' => array(0)
                 ),
                'RI' => array(
                    'min' => array(0),
                    'max' => array('M'),
                    'value_adult' => array(0),
            		'value_senior' => array(0),
		            'value_child' => array(0),
		            'value_infant' => array(0)
                 )
            );

            return $object;
        }

        if(count($data))
        {
            $object = new stdClass();
            foreach($data as $key => $value)
            {
                $object->$key = $value;
            }

            foreach($object->grid as $range => $grid)
            {
                foreach($grid as $key => $arrvalue)
                {
                    foreach($arrvalue as $skey => $value)
                    {
                        $object->grid[$range][$key][$skey] = (float) str_replace(',', '.', str_replace('.', '', $value));
                    }
                }
            }

            return $object;
        }
        else
        {
            $this->_table->load($afare);
            $object = $this->_table;

            $db    = $this->getDBO();
            $query = $db->getQuery(true);

            $query->select('*')
                ->from('#__fee_values')
                ->where('fare_id = '.$db->Quote($afare))
                ->order('id');

            $db->setQuery($query);
            $result = $db->loadObjectList();

            $object->grid = array();
            foreach($result as $value)
            {
                if(!isset($object->grid[$value->trip]))
                {
                    $object->grid[$value->trip] = array();
                }
                $object->grid[$value->trip]['min'][] = $value->minfare;
                $object->grid[$value->trip]['max'][] = $value->maxfare;
                $object->grid[$value->trip]['value_adult'][] = $value->charge_adult;
                $object->grid[$value->trip]['value_senior'][] = $value->charge_senior;
                $object->grid[$value->trip]['value_child'][] = $value->charge_child;
                $object->grid[$value->trip]['value_infant'][] = $value->charge_infant;
            }

            foreach(array('ON', 'RN', 'OI', 'RI') as $range)
            {
                $last = count($object->grid[$range]['max']) - 1;
                $object->grid[$range]['max'][$last] = 'M';
            }
        }

        return $object;
    }

    public function getUrl($task = '', $params = array())
    {
        $option = JRequest::getCmd('option');

        if($task != '')
            $task = '&task='.$task;
        else
            $task = '&task=adminfare.display';

        $extra = '';
        foreach($params as $param => $value)
            $extra = '&'.$param.'='.$value;

        return 'index.php?option='.$option.$task.$extra;
    }

    /*
     * Esta funcion se encarga de Insertar la Cabecera 
     * y detalle de la TA
     * */
    
    public function saveData($data, $values)
    {
    	$this->deleteValues($data['id']);
 
        if(!$this->_table->save($data))
        {
            $this->setError($this->_table->getError());
            return false;
        }

        $id = $this->_table->id;
        //valida si esta activo el check de pasajeros
      	if($data['all']==1){
      	   foreach($values as $key => $range)
        {
            foreach($range as $row)
            {
                $detail = JTable::getInstance('values');
                $data_detail = array(
                    'fare_id' => $id,
                    'trip' => $key,
                    'minfare' => $row['min'],
                    'maxfare' => $row['max'],
                    'charge_adult' => $row['value_adult'],
                	'charge_senior' => $row['value_adult'],
               		'charge_child' => $row['value_adult'],
                	'charge_infant' => $row['value_adult'],
                );
                $detail->save($data_detail);
            }
        }
      	} else {
    
        foreach($values as $key => $range)
        {
            foreach($range as $row)
            {
                $detail = JTable::getInstance('values');
                $data_detail = array(
                    'fare_id' => $id,
                    'trip' => $key,
                    'minfare' => $row['min'],
                    'maxfare' => $row['max'],
                    'charge_adult' => $row['value_adult'],
                	'charge_senior' => $row['value_senior'],
               		'charge_child' => $row['value_child'],
                	'charge_infant' => $row['value_infant'],
                );
                $detail->save($data_detail);
            }
        }
      	}

        return $this->_table;
    }

    /* Metodo que elimina Todos los Registros de la tabla
     * #__fee_values con el ID que tiene como parametro
     * */
    
    public function deleteValues($id)
    {
    	$db = JFactory::getDbo();
 
		$query = $db->getQuery(true);
		$conditions = array('fare_id=' . $id);
 
		$query->delete($db->quoteName('#__fee_values'));
		$query->where($conditions);
 
		$db->setQuery($query);
 
		try {
   			$result = $db->query();
		} catch (Exception $e) {
   		
		}
    	
    }
    
    public function publish(&$pks, $value = 1)
    {
        // Attempt to change the state of the records.
        if (!$this->_table->publish($pks, $value))
        {
            $this->setError($this->_table->getError());
            return false;
        }

        return true;
    }

    public function delete($pks)
    {
        foreach($pks as $id)
        {
        	$this->deleteValues($id);
            $this->_table->load($id);

            if($this->_table->id != 0)
            {
                if(!$this->_table->delete())
                {
                    $this->setError($this->_table->getError());
                    return false;
                }
            }
        }

        return true;
    }

    public function getAirlineName($iata)
    {
        $db = $this->getDBO();

        $query = $db->getQuery(true);
        $query->select('nombre')
              ->from('#__qs_airlines')
              ->where('codigo = '.$db->Quote($iata));

        $db->setQuery($query);

        return $db->loadResult();
    }

    public function getAirlines()
    {
        $db = $this->getDBO();

        $query = $db->getQuery(true);
        $query->select('a.codigo, a.nombre')
              ->from('#__qs_airlines AS a')
              ->join('LEFT', '#__fee_adminfare AS af ON af.airline = a.codigo')
              ->where('af.airline IS NULL');

        $db->setQuery($query);

        return $db->loadObjectList();
    }

}
