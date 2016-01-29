<?php
/**
 * @file com_geoplanes/admin/models/geoplanes.php
 * @ingroup _compadmin
 * Clase que administra los datos relativos a la administración del componente,
 * como el filtrado de registros, las columnas y sus ordenes,
 * cantidad de registros visibles por pantalla, entre otros.
 */

defined('_JEXEC') or die("Invalid access");

/**
 * @brief Clase que administra la obtencion de los datos.
 *
 * Esta clase se encarga de administrar tanto los datos relacionados con el
 * componente, como los registros, como los datos relacionados con el usuario
 * como el orden o filtro que tiene actualmente aplicados.
 */
class AsomModelEditStatus extends JModel
{

    private $_table = null;

    public function __construct()
    {
        parent::__construct();

        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_asom'.DS.'tables');
        $this->_table = JTable::getInstance('statuses');
    }
 
    public function getData($order_id)
    {
        $this->_table->load($order_id);

        return $this->_table;
    }

    public function getUrl($task = '', $params = array())
    {
        $option = JRequest::getCmd('option');

        if($task != '')
            $task = '&task='.$task;
        else
            $task = '&task=statuses.display';

        $extra = '';
        foreach($params as $param => $value)
            $extra = '&'.$param.'='.$value;

        return 'index.php?option='.$option.$task.$extra;
    }

    public function saveData($data)
    {
        try
        {
            if(!$this->_table->save($data))
                throw new Exception($this->_table->getError());

            if($this->_table->default_status != 0)
            {
                $db = $this->getDBO();
                $query  = 'UPDATE #__aom_statuses ';
                $query .= '   SET default_status = 0 ';
                $query .= ' WHERE id <> '.$db->Quote($this->_table->id);
                $db->setQuery($query);

                if(!$db->query())
                    throw new Exception($db->getError());
            }

            return $this->_table;
        }
        catch(Exception $e)
        {
            $this->setError($e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        $this->_table->load($id);

        try
        {
            if($this->_table->id != 0)
            {
                $db = $this->getDBO();

                $query  = 'SELECT COUNT(1) ';
                $query .= '  FROM #__aom_orders ';
                $query .= ' WHERE status = '.$db->Quote($this->_table->id);
                $db->setQuery($query);

                if($db->loadResult())
                    throw new Exception(JText::sprintf('AOM_STATUS_REFERENCED', $this->_table->name));

                $query  = 'SELECT COUNT(1) ';
                $query .= '  FROM #__aom_history ';
                $query .= ' WHERE status = '.$db->Quote($this->_table->id);
                $db->setQuery($query);

                if($db->loadResult())
                    throw new Exception(JText::sprintf('AOM_STATUS_REFERENCED', $this->_table->name));

                if(!$this->_table->delete())
                    throw new Exception($this->_table->getError());

                return true;
            }
            else
                throw new Exception(JText::_('AOM_DATA_MISTAKE'));
        }
        catch(Exception $e)
        {
            $this->setError($e->getMessage());
            return false;
        }
    }

}
