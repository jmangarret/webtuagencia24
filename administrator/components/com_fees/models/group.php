<?php
/**
 *
 */

defined('_JEXEC') or die("Invalid access");

/**
 *
 */
class FeesModelGroup extends JModel
{

    private $_table = null;


    public function __construct()
    {
        parent::__construct();

        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fees'.DS.'tables');
        $this->_table = JTable::getInstance('groups');
    }
 
    public function getData($group_id)
    {
        $group_id = (int) $group_id;
        $data    = JRequest::getVar('jform', array(), 'post', 'array');

        if(count($data))
        {
            $data['discount'] =  preg_replace(array('/\./', '/,/'), array('', '.'), $data['discount']);
            $data['fee']      =  preg_replace(array('/\./', '/,/'), array('', '.'), $data['fee']);

            $object = new stdClass();
            foreach($data as $key => $value)
            {
                $object->$key = $value;
            }

            return $object;
        }
        else
        {
            $this->_table->load($group_id);
        }

        return $this->_table;
    }

    public function getUrl($task = '', $params = array())
    {
        $option = JRequest::getCmd('option');

        if($task != '')
            $task = '&task='.$task;
        else
            $task = '&task=groups.display';

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

    public function getGroupName($id)
    {
        $db = $this->getDBO();

        $query = $db->getQuery(true);
        $query->select('title')
              ->from('#__usergroups')
              ->where('id = '.$db->Quote($id));

        $db->setQuery($query);

        return $db->loadResult();
    }

    public function getGroups()
    {
        $db = $this->getDBO();

        $query = $db->getQuery(true);
        $query->select('g.id, g.title')
              ->from('#__usergroups AS g')
              ->join('LEFT', '#__fee_groups AS fg ON fg.usergroupid = g.id')
              ->where('fg.id IS NULL');

        $db->setQuery($query);

        return $db->loadObjectList();
    }

}
