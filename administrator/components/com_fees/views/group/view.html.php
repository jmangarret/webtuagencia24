<?php
/**
 *
 */

defined('_JEXEC') or die;

/**
 *
 */
class FeesViewGroup extends JViewLegacy
{


    public function display($tpl = null)
    {
        $model = $this->getModel();
        $data  = $model->getData($this->id);

        $this->addScripts();
        $this->addToolbar();

        $this->assign('data', $data);
        $this->assign('url', $model->getUrl());
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('COM_FEES_EDIT'), 'module.png');

        $canDo = FeesHelper::getActions();

        if($canDo->get('core.create') || $canDo->get('core.edit'))
        {
            JToolBarHelper::apply('groups.apply');
            JToolBarHelper::save('groups.save');
        }

        JToolBarHelper::cancel('groups.cancel');
    }

    protected function getGroupName($id)
    {
        $model = $this->getModel();

        return $model->getGroupName((int) $id);
    }

    protected function getGroups()
    {
        $groups = $this->getModel()->getGroups();
        return JHTML::_('select.genericlist', $groups, 'jform[usergroupid]', null, 'id', 'title', $this->data->usergroupid);
    }

    protected function addScripts()
    {
        $path = JURI::root().'administrator/components/com_fees/';
        $doc  = JFactory::getDocument();

        $doc->addScript($path.'js/fees.js');

        $script  = 'jQuery(document).ready(function($){';
        $script .=   'Fees.load();';
        $script .= '});';
        $doc->addScriptDeclaration($script);
    }

}

