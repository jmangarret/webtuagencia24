<?php
/**
 * @file com_asom/admin/controllers/orders.php
 * @defgroup _comp_adm Componente (Administración)
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');


class FeesControllerAdminFare extends JController
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
        FeesHelper::addSubmenu('adminfare');

        JRequest::setVar('view', 'adminfares');
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
                throw new Exception(JText::_('FEE_DATA_MISTAKE'));
            }
            elseif($task == 'add')
            {
                $cid = array(0);
            }

            $id = (int) $cid[0];

            JRequest::setVar('view', 'adminfare');

            $view = $this->getView('adminfare', 'html');
            $view->assign('id', $id);

            parent::display();
        }
        catch(Exception $e)
        {
            $model   = $this->getModel('adminfare');
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
        // Check if exist selec option all 
		if(!$data['all']){
			$data['all']=0;
		}
           
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
            $model = $this->getModel('adminfare');

            // Validando que la aerolinea sea valida
            $airlinename = $model->getAirlineName($data['airline']);
            if($airlinename == '')
            {
                throw new Exception(JText::_('COM_ADMINFARE_AIRLINE_INVALID'));
            }

            // Validando que el tipo de valor sea valido
            if(!in_array($data['valuetype'], array('V', 'P')))
            {
                throw new Exception(JText::_('COM_ADMINFARE_VALUETYPE_INVALID'));
            }
 
            // obteniendo los rangos y sus valores
            $grid = array();
            foreach(array('ON', 'RN', 'OI', 'RI') as $type)
            {
                if(!isset($data['grid'][$type]))
                {
                    throw new Exception(JText::sprintf('COM_ADMINFARE_RANGE_NOT_EXIST', $type));
                }

                $grid[$type] = array();
                $i = 0;
                foreach($data['grid'][$type]['min'] as $value)
                {
                    $grid[$type][$i++]['min']   = (float) str_replace(',', '.', str_replace('.', '', $value));
                }

                $i = 0;
                foreach($data['grid'][$type]['max'] as $value)
                {
                    $grid[$type][$i++]['max'] = (float) str_replace(',', '.', str_replace('.', '', $value));
                }

                $i = 0;
                foreach($data['grid'][$type]['value_adult'] as $value)
                {
                    $grid[$type][$i++]['value_adult'] = (float) str_replace(',', '.', str_replace('.', '', $value));
                }
              $i = 0;
                foreach($data['grid'][$type]['value_senior'] as $value)
                {
                    $grid[$type][$i++]['value_senior'] = (float) str_replace(',', '.', str_replace('.', '', $value));
                }
              $i = 0;
                foreach($data['grid'][$type]['value_child'] as $value)
                {
                    $grid[$type][$i++]['value_child'] = (float) str_replace(',', '.', str_replace('.', '', $value));
                }
              $i = 0;
                foreach($data['grid'][$type]['value_infant'] as $value)
                {
                    $grid[$type][$i++]['value_infant'] = (float) str_replace(',', '.', str_replace('.', '', $value));
                }
            }

            // Validando los rangos
            $valuetype = $data['valuetype'];
            foreach(array('ON', 'RN', 'OI', 'RI') as $type)
            {
                $last = count($grid[$type]) - 1;
                foreach($grid[$type] as $rkey => $range)
                {
                
                    if($range['max'] != 0 && $last == $rkey)
                    {
                        throw new Exception(JText::sprintf('COM_ADMINFARE_RANGE_ERROR', $type.' -  '.($rkey + 1)));
                    }
                	
                    if($range['min'] > $range['max'] && $last > $rkey)
                    {
                        throw new Exception(JText::sprintf('COM_ADMINFARE_RANGE_ERROR', $type.' - '.($rkey + 1)));
                    }

                    if($valuetype == 'P' && $range['value_adult'] > 100)
                    {
                        throw new Exception(JText::sprintf('COM_ADMINFARE_RANGE_VALUETYPE_ERROR',JText::_("ADMINFARE_".$type).' - '.($rkey + 1)));
                    }
                 if($valuetype == 'P' && $range['value_senior'] > 100)
                    {
                        throw new Exception(JText::sprintf('COM_ADMINFARE_RANGE_VALUETYPE_ERROR', JText::_("ADMINFARE_".$type).' - '.($rkey + 1)));
                    }
                 if($valuetype == 'P' && $range['value_child'] > 100)
                    {
                        throw new Exception(JText::sprintf('COM_ADMINFARE_RANGE_VALUETYPE_ERROR', JText::_("ADMINFARE_".$type).' - '.($rkey + 1)));
                    }
                 if($valuetype == 'P' && $range['value_infant'] > 100)
                    {
                        throw new Exception(JText::sprintf('COM_ADMINFARE_RANGE_VALUETYPE_ERROR', JText::_("ADMINFARE_".$type).' -  '.($rkey + 1)));
                    }
                    
                }
            }
            

            if(!($table = $model->saveData($data, $grid)))
            {
                throw new Exception($model->getError());
            }

            $message = $data['id'] == 0 ? 'COM_ADMINFARE_SAVED' : 'COM_ADMINFARE_EDITED';
            $message = JText::sprintf($message, $airlinename);

            switch($this->getTask())
            {
            case 'save':
                $link = JRoute::_($model->getUrl(), false);
                break;
            case 'apply':
                $link = JRoute::_($model->getUrl('adminfare.edit', array('cid[]' => $table->id)), false);
                break;
            }

            $this->setRedirect($link, $message);
        }
        catch(Exception $e)
        {
            $app = JFactory::getApplication();
            $app->enqueueMessage($e->getMessage(), 'error');

            JRequest::setVar('view', 'adminfare');
            
            if($data['id'] != 0)
            {
                $id = (int) $data['id'];
            }
            else
            {
                $id = 0;
            }

            $view = $this->getView('group', 'html');
            $view->assign('id', $id);

            parent::display();
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
                throw new Exception('COM_FEES_NO_ITEM_SELECTED');
            }

            $model = $this->getModel('adminfare');

            JArrayHelper::toInteger($cid);

            if (!$model->delete($cid))
            {
                throw new Exception($model->getError());
            }

            $message = JText::_('COM_FEES_DELETED');
            $link    = JRoute::_($model->getUrl(), false);

            $this->setRedirect($link, $message);
        }
        catch(Exception $e)
        {
            $link  = JRoute::_($model->getUrl(), false);
            $this->setRedirect($link);
        }
    }

    public function cancel()
    {
        $model = $this->getModel('adminfare');
        $link  = JRoute::_($model->getUrl(), false);
        $this->setRedirect($link);
    }

}
