<?php
/**
 * @file com_asom/admin/controllers/orders.php
 * @defgroup _comp_adm Componente (Administración)
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');


class LowFaresControllerFlights extends JController
{

    public function __construct()
    {
        parent::__construct();
        $this->registerTask('apply', 'save');
        $this->registerTask('add', 'edit');
        $this->registerTask('unpublish', 'publish');
    }

    public function display()
    {
        LowFaresHelper::addSubmenu('flights');

        JRequest::setVar('view', 'flights');
        parent::display();
    }

    public function edit()
    {
        $task   = $this->getTask();
        $cid    = JRequest::getVar('cid', array(), 'request', 'array');
        $option	= JRequest::getCmd('option');

        // Check if the user is authorized to do this.
        if (!JFactory::getUser()->authorise('core.edit', $option))
        {
            JFactory::getApplication()->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
            return;
        }

        try
        {
            if($task == 'edit' && count($cid) < 1)
            {
                throw new Exception(JText::_('P2P_DATA_MISTAKE'));
            }
            elseif($task == 'add')
            {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);

                $query->select('COUNT(1)')
                      ->from('#__categories')
                      ->where('extension = "com_lowfares"');

                $db->setQuery($query);

                if($db->loadResult() < 1)
                {
                    $link  = 'index.php?option=com_categories&extension=com_lowfares';

                    $this->setRedirect($link, JText::_('COM_LOWFARES_NO_CATEGORIES'), 'warning');
                    return false;
                }
                $cid = array(0);
            }

            $id = (int) $cid[0];

            JRequest::setVar('view', 'flight');

            $view = $this->getView('flight', 'html');
            $view->assign('id', $id);

            parent::display();
        }
        catch(Exception $e)
        {
            $model   = $this->getModel('flight');
            $link    = JRoute::_($model->getUrl(), false);
            $message = $e->getMessage();
            $type    = 'error';

            $this->setRedirect($link, $message, $type);
        }
    }

    public function save()
    {
        $data   = JRequest::getVar('jform', array(), 'post', 'array');
        $option	= JRequest::getCmd('option');

        // Check if the user is authorized to do this.
        if (!JFactory::getUser()->authorise('core.create', $option) && $data['id'] == 0)
        {
            JFactory::getApplication()->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
            return;
        }

        // Check if the user is authorized to do this.
        if (!JFactory::getUser()->authorise('core.edit', $option) && $data['id'] != 0)
        {
            JFactory::getApplication()->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
            return;
        }

        try
        {
            $data['category']  = (int) $data['category'];
            $data['duration']  = (int) $data['duration'];
            $data['published'] = (int) $data['published'];

            // Validando datos
            // Se valida que las ciudades sean codigos IATA
            if(!$this->_getCity($data['origin']) || !$this->_getCity($data['destiny']))
            {
                throw new Exception(JText::_('COM_LOWFARES_IATACODES_WRONG'));
            }

            if($data['origin'] == $data['destiny'])
            {
                throw new Exception(JText::_('COM_LOWFARES_IATACODES_EQUALS'));
            }

            $data['origin']  = strtoupper($data['origin']);
            $data['destiny'] = strtoupper($data['destiny']);

            if((!isset($data['offset']) && !isset($data['departure'])) ||
               (isset($data['offset']) && $data['offset'] == '') ||
               (isset($data['departure']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['departure'])))
            {
                throw new Exception(JText::_('COM_LOWFARES_EXIT_WRONG'));
            }

            // Organizando la informacion para guardar
            if(!isset($data['originname']) || $data['originname'] == '')
            {
                $data['originname'] = $this->_getCity($data['origin']);
            }

            if(!isset($data['destinyname']) || $data['destinyname'] == '')
            {
                $data['destinyname'] = $this->_getCity($data['destiny']);
            }

            if(isset($data['offset']))
            {
                $data['offset']    = (int) $data['offset'];
                $data['departure'] = null;
            }
            else
            {
                $data['offset'] = null;
            }

            $model = $this->getModel('flight');
            if(!($table = $model->saveData($data)))
            {
                throw new Exception($model->getError());
            }

            $message = $data['id'] == 0 ? 'COM_LOWFARES_STATUS_SAVED' : 'COM_LOWFARES_STATUS_EDITED';
            $message = JText::sprintf($message, $table->originname.' - '.$table->destinyname);

            switch($this->getTask())
            {
            case 'save':
                $link = JRoute::_($model->getUrl(), false);
                break;
            case 'apply':
                $link = JRoute::_($model->getUrl('flights.edit', array('cid[]' => $table->id)), false);
                break;
            }

            $this->setRedirect($link, $message);
        }
        catch(Exception $e)
        {
            $app = JFactory::getApplication();
            $app->enqueueMessage($e->getMessage(), 'error');

            JRequest::setVar('view', 'flight');
            parent::display();
        }
    }

    public function publish()
    {
        $cid    = JRequest::getVar('cid', array(), 'post', 'array');
        $option	= JRequest::getCmd('option');

        // Check if the user is authorized to do this.
        if (!JFactory::getUser()->authorise('core.edit.state', $option))
        {
            JFactory::getApplication()->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
            return;
        }

        try
        {
            if (!is_array($cid) || count($cid) < 1)
            {
                throw new Exception('COM_LOWFARES_NO_ITEM_SELECTED');
            }

            $model = $this->getModel('flight');
            $data  = array('publish' => 1, 'unpublish' => 0);
            $value = JArrayHelper::getValue($data, $this->getTask(), 0, 'int');

            JArrayHelper::toInteger($cid);

            if(!$model->publish($cid, $value))
            {
                throw new Exception($model->getError());
            }

            $message = $value == 1 ? 'COM_LOWFARES_PUBLISHED' : 'COM_LOWFARES_UNPUBLISHED';
            $message = JText::_($message);
            $link    = JRoute::_($model->getUrl(), false);

            $this->setRedirect($link, $message);
        }
        catch(Exception $e)
        {
            $model = $this->getModel('flights');
            $link  = JRoute::_($model->getUrl(), false);
            $this->setRedirect($link);
        }
    }

    public function delete()
    {
        $cid    = JRequest::getVar('cid', array(), '', 'array');
        $option	= JRequest::getCmd('option');

        // Check if the user is authorized to do this.
        if (!JFactory::getUser()->authorise('core.delete', $option))
        {
            JFactory::getApplication()->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
            return;
        }

        try
        {
            if (!is_array($cid) || count($cid) < 1)
            {
                throw new Exception('COM_LOWFARES_NO_ITEM_SELECTED');
            }

            $model = $this->getModel('flight');

            JArrayHelper::toInteger($cid);

            if (!$model->delete($cid))
            {
                throw new Exception($model->getError());
            }

            $message = JText::_('COM_LOWFARES_DELETED');
            $link    = JRoute::_($model->getUrl(), false);

            $this->setRedirect($link, $message);
        }
        catch(Exception $e)
        {
            $model = $this->getModel('flights');
            $link  = JRoute::_($model->getUrl(), false);
            $this->setRedirect($link);
        }
    }

    public function cancel()
    {
        $model = $this->getModel('flights');
        $link  = JRoute::_($model->getUrl(), false);
        $this->setRedirect($link);
    }



    private function _getCity($iata)
    {
        $db    = JFactory::getDBO();
        $query = $db->getQuery(true);
        $lang  = JFactory::getLanguage(); 
        $lang  = substr($lang->getTag(), 3);

        $query->select('ciudad, idcity')
            ->from('#__qs_cities')
            ->where('iata = '.$db->Quote($iata))
            ->where('lenguaje = '.$db->Quote($lang));

        $db->setQuery($query);
        $row = $db->loadObject();

        if($row != null)
        {
            if($row->ciudad == null)
            {
                $query->clear('where');

                $query->where('id = '.$db->Quote($row->idcity))
                    ->where('lenguaje = '.$db->Quote($lang));

                $db->setQuery($query);
                $city = $db->loadObject();

                $row ->ciudad = $city->ciudad;
            }

            return $row->ciudad;
        }
        else
        {
            return false;
        }
    }

}
