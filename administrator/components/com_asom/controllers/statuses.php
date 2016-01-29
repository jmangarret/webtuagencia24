<?php
/**
 * @file com_asom/admin/controllers/orders.php
 * @defgroup _comp_adm Componente (Administración)
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');

class AsomControllerStatuses extends AsomController
{

    public function __construct()
    {
        parent::__construct();
        $this->registerTask('apply', 'save');
        $this->registerTask('add', 'edit');
    }

    public function display()
    {
        $this->setResources();

        JRequest::setVar('view', 'statuses');
        parent::display();
    }

    public function edit()
    {
        $task = $this->getTask();
        $cid  = JRequest::getVar('cid', array(), 'request', 'array');

        try
        {
            if($task == 'edit' && count($cid) < 1)
                throw new Exception(JText::_('AOM_DATA_MISTAKE'));
            elseif($task == 'add')
                $cid = array(0);

            $this->setResources();

            $id = (int) $cid[0];

            JRequest::setVar('view', 'editstatus');

            $view = $this->getView('editstatus', 'html');
            $view->assign('task', $task);
            $view->assign('id', $id);

            parent::display();
        }
        catch(Exception $e)
        {
            $model   = $this->getModel('statuses');
            $link    = JRoute::_($model->getUrl(), false);
            $message = $e->getMessage();
            $type    = 'error';

            $this->setRedirect($link, $message, $type);
        }
    }

    public function setResources()
    {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet('components'.DS.'com_asom'.DS.'css'.DS.'jquery.minicolors.min.css');
        $doc->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js');
        $doc->addScriptDeclaration('jQuery.noConflict();');
        $doc->addScript('components'.DS.'com_asom'.DS.'js'.DS.'jquery.minicolors.min.js');
    }

    public function save()
    {
        $data = array(
            'id'             => JRequest::getVar('id', 0, 'integer', 'post'),
            'name'           => JRequest::getVar('name', '', 'string', 'post'),
            'color'          => JRequest::getVar('color', '', 'string', 'post'),
            'default_status' => JRequest::getVar('default_status', 0, 'integer', 'post')
        );

        try
        {
            if($data['name'] == '')
                throw new Exception(JText::_('AOM_DATA_MISTAKE'));

            $model = $this->getModel('editstatus');

            if(!($table = $model->saveData($data)))
                throw new Exception($model->getError());

            $type    = '';
            $message = $data['id'] == 0 ? 'AOM_STATUS_SAVED' : 'AOM_STATUS_EDITED';
            $message = JText::sprintf($message, $table->name);

            switch($this->getTask())
            {
            case 'save':
                $link = JRoute::_($model->getUrl(), false);
                break;
            case 'apply':
                $link = JRoute::_($model->getUrl('statuses.edit', array('cid[]' => $table->id)), false);
                break;
            }
        }
        catch(Exception $e)
        {
            $model   = $this->getModel('statuses');
            $link    = JRoute::_($model->getUrl(), false);
            $message = JText::_($e->getMessage());
            $type    = 'error';
        }

        $this->setRedirect($link, $message, $type);
    }

    public function saveColors()
    {
        $ids    = JRequest::getVar('cid', array(), 'array', 'post');
        $colors = JRequest::getVar('color', array(), 'array', 'post');

        try
        {
            $i     = 0;
            $model = $this->getModel('editstatus');

            foreach($ids as $id)
            {
                $data = $model->getData($id);

                if($data->id == 0)
                    throw new Exception(JText::_('AOM_DATA_MISTAKE'));

                $data->color = $colors[$i];

                if(!($table = $model->saveData($data)))
                    throw new Exception($model->getError());

                $i++;
            }

            $type    = '';
            $link    = JRoute::_($model->getUrl(), false);
            $message = JText::_('AOM_COLORS_SAVED');
        }
        catch(Exception $e)
        {
            $model   = $this->getModel('statuses');
            $link    = JRoute::_($model->getUrl(), false);
            $message = JText::_($e->getMessage());
            $type    = 'error';
        }

        $this->setRedirect($link, $message, $type);
    }

    public function remove()
    {
        $cid  = JRequest::getVar('cid', array(), 'request', 'array');

        try
        {
            if(count($cid) < 1)
                throw new Exception(JText::_('AOM_DATA_MISTAKE'));

            $model = $this->getModel('editstatus');

            foreach($cid as $id)
            {
                if(!$model->delete($id))
                    throw new Exception($model->getError());
            }

            $message = JText::_('AOM_STATUS_DELETED');
            $link    = JRoute::_($model->getUrl(), false);
        }
        catch(Exception $e)
        {
            $model   = $this->getModel('statuses');
            $link    = JRoute::_($model->getUrl(), false);
            $message = $e->getMessage();
            $type    = 'error';
        }

        $this->setRedirect($link, $message, $type);
    }

}
