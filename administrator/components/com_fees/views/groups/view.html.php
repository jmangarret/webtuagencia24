<?php
/**
 *
 */

defined('_JEXEC') or die;

/**
 *
 */
class FeesViewGroups extends JViewLegacy
{

    public function display($tpl = null)
    {
        $model = $this->getModel();

        $this->assign('url', $model->getUrl());
        $this->assign('data', $model->getData());
        $this->assign('pagination', $model->getPagination());
        $this->assign('model', $model);

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        $canDo = FeesHelper::getActions();

        JToolBarHelper::title(JText::_('COM_FEES_LIST'), 'module.png');

        if ($canDo->get('core.create'))
        {
            JToolBarHelper::addNew('groups.add');
        }

        if ($canDo->get('core.edit'))
        {
            JToolBarHelper::editList('groups.edit');
        }

        if ($canDo->get('core.delete'))
        {
            JToolBarHelper::divider();
            JToolBarHelper::deleteList(JText::_('COM_FEES_COMFIRM_DELETE'), 'groups.delete', 'JTOOLBAR_DELETE');
        }

        if ($canDo->get('core.admin'))
        {
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_fees');
        }
    }

}

