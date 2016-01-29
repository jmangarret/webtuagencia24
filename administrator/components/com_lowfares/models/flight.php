<?php
/**
 *
 */

defined('_JEXEC') or die("Invalid access");

/**
 *
 */
class LowFaresModelFlight extends JModel
{

    private $_table = null;


    public function __construct()
    {
        parent::__construct();

        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_lowfares'.DS.'tables');
        $this->_table = JTable::getInstance('flights');
    }
 
    public function getData($fare_id)
    {
        $fare_id = (int) $fare_id;
        $data    = JRequest::getVar('jform', array(), 'post', 'array');

        if(count($data) && $fare_id == 0)
        {
            $object = new stdClass();
            foreach($data as $key => $value)
            {
                $object->$key = $value;
            }

            return $object;
        }
        else
        {
            $this->_table->load($fare_id);
        }

        return $this->_table;
    }

    public function getUrl($task = '', $params = array())
    {
        $option = JRequest::getCmd('option');

        if($task != '')
            $task = '&task='.$task;
        else
            $task = '&task=flights.display';

        $extra = '';
        foreach($params as $param => $value)
            $extra = '&'.$param.'='.$value;

        return 'index.php?option='.$option.$task.$extra;
    }

    public function saveData($data)
    {
        if(!$this->_table->save($data))
        {
            $this->setError($this->_table->getError());
            return false;
        }

        return $this->_table;
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

}
