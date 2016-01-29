<?php
/**
 * @file com_asom/admin/controllers/orders.php
 * @defgroup _comp_adm Componente (Administración)
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');


class FeesControllerGroups extends JController
{

    public function __construct()
    {
        parent::__construct();
        $this->registerTask('apply', 'save');
        $this->registerTask('add', 'edit');
    }

    public function display()
    {
        FeesHelper::addSubmenu('groups');

        JRequest::setVar('view', 'groups');
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

            $model = $this->getModel('group');
            if(!count($model->getGroups()) && $task == 'add')
            {
                throw new Exception(JText::_('COM_FEES_GROUPS_EMPTY'));
            }

            JRequest::setVar('view', 'group');

            $view = $this->getView('group', 'html');
            $view->assign('id', $id);

            parent::display();
        }
        catch(Exception $e)
        {
            $model   = $this->getModel('group');
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
            $data['usergroupid'] = (int) $data['usergroupid'];
            $data['discount']    =  preg_replace(array('/\./', '/,/'), array('', '.'), $data['discount']);
            $data['fee']         =  preg_replace(array('/\./', '/,/'), array('', '.'), $data['fee']);

            // Se valida la informacion a guardar
            // Se vlida que el grupo sea valido
            if($data['usergroupid'] < 1)
            {
                throw new Exception(JText::_('COM_FEE_ERROR_ISNOT_GROUP'));
            }

            // Se valida que el porcentaje de descuento no supere 100.00
            if($data['discount'] < 0.00 || $data['discount'] > 100.00)
            {
                throw new Exception(JText::_('COM_FEE_ERROR_DISCOUNT_ISNOT_VALID'));
            }

            // Se valida que si el valor esta configurado con porcentaje, este no supere 100
            if($data['feetype'] == 'P' && ($data['fee'] < 0.00 || $data['fee'] > 100.00))
            {
                throw new Exception(JText::_('COM_FEE_ERROR_DISCOUNT_ISNOT_VALID'));
            }

            $model = $this->getModel('group');
            if(!($table = $model->saveData($data)))
            {
                throw new Exception($model->getError());
            }

            $message = $data['id'] == 0 ? 'COM_FEES_GROUP_SAVED' : 'COM_FEES_GROUP_EDITED';
            $message = JText::sprintf($message, $model->getGroupName($table->usergroupid));

            switch($this->getTask())
            {
            case 'save':
                $link = JRoute::_($model->getUrl(), false);
                break;
            case 'apply':
                $link = JRoute::_($model->getUrl('groups.edit', array('cid[]' => $table->id)), false);
                break;
            }

            $this->setRedirect($link, $message);
        }
        catch(Exception $e)
        {
            $app = JFactory::getApplication();
            $app->enqueueMessage($e->getMessage(), 'error');

            JRequest::setVar('view', 'group');
            
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

            $model = $this->getModel('group');

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
            $model = $this->getModel('groups');
            $link  = JRoute::_($model->getUrl(), false);
            $this->setRedirect($link);
        }
    }

    public function cancel()
    {
        $model = $this->getModel('groups');
        $link  = JRoute::_($model->getUrl(), false);
        $this->setRedirect($link);
    }

}
