<?php
/**
 * @file com_asom/admin/controllers/orders.php
 * @defgroup _comp_adm Componente (Administración)
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.event.dispatcher');

class AsomControllerOrders extends AsomController
{

    public function __construct()
    {
        parent::__construct();
        $this->registerTask('apply', 'save');
    }

    public function display()
    {
        JRequest::setVar('view', 'orders');
        parent::display();
    }

    public function edit()
    {
        $cid = JRequest::getVar('cid', array(), 'request', 'array');

        try
        {
            if(count($cid) < 1)
                throw new Exception('AOM_DATA_MISTAKE');

            $id = (int) $cid[0];

            JRequest::setVar('view', 'editair');

            $view = $this->getView('editair', 'html');
            $view->assign('id', $id);

            parent::display();
        }
        catch(Exception $e)
        {
            $model   = $this->getModel('orders');
            $link    = JRoute::_($model->getUrl(), false);
            $message = $e->getMessage();
            $type    = 'error';

            $this->setRedirect($link, JText::_($message), $type);
        }
    }

    public function save()
    {
        $model   = $this->getModel('orders');

        try
        {
            $order = JRequest::getVar('id', 0, 'int', 'post');
            if(!is_numeric($order))
            {
                throw new Exception(JText::_('AOM_DATA_MISTAKE'));
            }
             $note = JRequest::getVar('nota', '', 'string', 'post');
            $status = JRequest::getVar('status', '', 'string', 'post');
            
            $enviar_mail = JRequest::getVar('enviar_mail', '', 'string', 'post');
            
            
            if ($enviar_mail=='si'){
                $old_status_name = JRequest::getVar('old_state_name', '', 'string', 'post');
                $new_status_name=$model->getStatus($status);

                $user = JFactory::getUser();
                $user = $user->get('name', 0);
                $config	= JFactory::getConfig();
                $mail_usuario = JRequest::getVar('mail_usuario', '', 'string', 'post');
                $emailSubject	= JText::sprintf('AOM_SUBJECT',$order);
                $emailBody ='
                    <table><tr>
                    <td>
                        N&uacute;mero de pedido : </td>
                        <td>'.$order.'</td></tr><tr>
                    <td>
                        Status anterior :   </td>
                        <td>'.$old_status_name.'</td></tr><tr>
                    <td>
                        Nuevo Status :   </td>
                        <td>'.$new_status_name['name'].'</td></tr><tr>
                    <td>
                        Observaciones :   </td>
                        <td>'.nl2br($note).'</td></tr><tr>
                    <td>
                        Fecha y hora de la modificaci&oacute;n :   </td>
                        <td>'.JHTML::date('now', 'l, d F Y g:i a').'</td></tr><tr>
                    <td>
                        Usuario que realiz&oacute; la modificaci&oacute;n :  </td>
                        <td>'.$user.'</td></tr></table>';

                $correo_enviado = JFactory::getMailer();
                $correo_enviado->isHTML(true);
                $correo_enviado->sendMail($config->get('mailfrom'),$config->get('fromname'), $mail_usuario, $emailSubject, $emailBody);
            }
            
            if($note == '')
            {
                throw new Exception(JText::_('AOM_DATA_MISTAKE'));
            }

            $library = JPATH_COMPONENT.DS.'library';
            // Se registra el directorio, para que dinamicamente cargue las clases necesarias.
            JLoader::registerPrefix('Asom', $library);
            
            $order  = new AsomClassOrder($order);

            //if($order->getOrder()->note != $note)
            //{
                $data = array(
                    'note' => $note,
                    'status' => $status
                );
            
                //if(!$order->updateOrder($data, JText::_('AOM_UPDATE_NOTE')))
                if(!$order->updateOrder($data, $note))
                {
                    throw new Exception(JText::_('AOM_ERROR_UPDATE_PROCESS'));
                }
            //}

            $type    = '';
            
            $message = JText::_('AOM_ORDER_UPDATED');
            

            switch($this->getTask())
            {
            case 'save':
                $link = JRoute::_($model->getUrl(), false);
                break;
            case 'apply':
                $link = JRoute::_($model->getUrl('orders.edit', array('cid[]' => $order->getOrder()->id)), false);
                break;
            }
        }
        catch(Exception $e)
        {
            $type    = 'error';
            $link    = JRoute::_($model->getUrl(), false);
            $message = $e->getMessage();
        }
        $application = JFactory::getApplication();
        if ($correo_enviado!=1){
            $application->enqueueMessage(JText::_('AOM_ERROR_OCCURRED'), 'error');
        }else{
            $application->enqueueMessage(JText::_('AOM_SUCESS'), 'sucess');
        }
        $this->setRedirect($link, $message, $type);
    }

    public function status()
    {
        try
        {
            $order = JRequest::getVar('cid', array(), 'array', 'post');
            if(!is_array($order) || !isset($order[0]) || !is_numeric($order[0]))
            {
                throw new Exception(JText::_('AOM_DATA_MISTAKE'));
            }

            $order = $order[0];


            $note = JRequest::getVar('note', '', 'string', 'post');
            if($note == '')
            {
                throw new Exception(JText::_('AOM_DATA_MISTAKE'));
            }


            $status = JRequest::getVar('status',0, 'int', 'post');
            if($status == 0)
            {
                throw new Exception(JText::_('AOM_DATA_MISTAKE'));
            }

            $library = JPATH_COMPONENT.DS.'library';
            // Se registra el directorio, para que dinamicamente cargue las clases necesarias.
            JLoader::registerPrefix('Asom', $library);
            
            $order  = new AsomClassOrder($order);

            $data = array(
                'status' => $status
            );
            
            if(!$order->updateOrder($data, $note))
            {
                throw new Exception(JText::_('AOM_ERROR_UPDATE_PROCESS'));
            }

            $type    = '';
            $message = JText::_('AOM_STATUS_CHANGED');
        }
        catch(Exception $e)
        {
            $type    = 'error';
            $message = $e->getMessage();
        }

        $model   = $this->getModel('orders');
        $link    = JRoute::_($model->getUrl(), false);
        $this->setRedirect($link, $message, $type);
    }


    public function cancel()
    {
        $model   = $this->getModel('orders');
        $link    = JRoute::_($model->getUrl(), false);
        $this->setRedirect($link, $message, $type);
    }

}
