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

    public function display($tpl = null)
    {
        $model = $this->getModel();

        $this->assign('url'       , $model->getUrl());
        $this->assign('data'      , $model->getData());
        $this->assign('pagination', $model->getPagination());
        $this->assign('filters'   , $model->getFilters());

        $this->assign('direction' , $model->getState('filter_order_Dir'));
        $this->assign('order'     , $model->getState('filter_order'));
        $this->assign('id'    , $model->getState('id'));
        $this->assign('recloc'    , $model->getState('recloc'));
        $this->assign('contact'    , $model->getState('contact'));
        $this->assign('product_type'    , $model->getState('product_type'));
        $this->assign('fec_ini'    , $model->getState('fec_ini'));
        $this->assign('fec_fin'    , $model->getState('fec_fin'));

        $this->setResources();
        $this->toolbar();

        parent::display($tpl);
    }

    /**
     * @brief Crea la barra de herramientas de la vista actual.
     */
    public function toolbar()
    {
        JToolBarHelper::title( JText::_( 'AOM_ORDERS_LIST' ), 'module.png' );
        JToolBarHelper::preferences('com_asom');
        JToolBarHelper::editList();
    }

    public function filterStatus($id) 
    {
        $model = $this->getModel();

        $data = $model->getStatus($id);

        $options = array(JHTML::_('select.option', '', JText::_('AOM_SELECT')));
        foreach($data as $key => $value)
        {
            $options[] = JHTML::_('select.option', $key, $value['name']);
        }

        $attribs  = 'class="inputbox" ';
        //$attribs .= 'onchange="document.adminForm.submit();"';

        return JHTML::_('select.genericlist', $options, 'filter_status', $attribs, 'value', 'text', $model->getState('filter_status'));
    }

    public function getStatus($status = '')
    {
        return $this->getModel()->getStatus($status);
    }
    public function getFecModificacion($id)
    {
        return $this->getModel()->getFecModificacion($id);
    }
    public function setResources()
    {
        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration('jQuery.noConflict();');
        $doc->addScript('components'.DS.'com_asom'.DS.'js'.DS.'status.js');
    }

}
