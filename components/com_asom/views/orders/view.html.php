<?php
/**
 * @file com_asom/admin/controller.php
 * @ingroup _comp_adm
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class AsomViewOrders extends JView
{

    public function display()
    {
        $model = $this->getModel();

        //$this->assign('url',        $model->getUrl());
        $this->assign('data',       $model->getData());
        $this->assign('pagination', $model->getPagination());
        $this->assign('model',      $model);

        $this->addScripts();
        parent::display();
    }

    private function addScripts()
    {
        $doc = JFactory::getDocument();

        $script  = 'if(window.Joomla == undefined) window.Joomla = {};';
        $script .= 'window.Joomla.tableOrdering = function(order,dir,task){';
        $script .= 'var form = document.adminForm;';
        $script .= 'form.filter_order.value = order;';
        $script .= 'form.filter_order_Dir.value = dir;';
        $script .= 'document.adminForm.submit( task );}';
            
        $doc->addScriptDeclaration($script);
    }

}
