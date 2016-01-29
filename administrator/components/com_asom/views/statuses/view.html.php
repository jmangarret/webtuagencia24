<?php
/**
 * @file com_asom/admin/controller.php
 * @ingroup _comp_adm
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class AsomViewStatuses extends JView
{

    public function display($tpl = null)
    {
        $model = $this->getModel();

        $this->assign('url', $model->getUrl());
        $this->assign('data', $model->getData());
        $this->assign('pagination', $model->getPagination());
        $this->assign('model', $model);

        $this->toolbar();

        parent::display($tpl);
    }

    /**
     * @brief Crea la barra de herramientas de la vista actual.
     */
    public function toolbar()
    {
        JToolBarHelper::title( JText::_( 'AOM_STATUSES_LIST' ), 'module.png' );
        JToolBarHelper::deleteList(JText::_('AOM_STATUSES_COMFIRM_REMOVE'), 'statuses.remove');
        JToolBarHelper::spacer();
        JToolBarHelper::editList('statuses.edit');
        JToolBarHelper::addNew('statuses.add');
    }

}
