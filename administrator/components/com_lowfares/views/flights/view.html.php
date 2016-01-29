<?php
/**
 *
 */

defined('_JEXEC') or die;

/**
 *
 */
class LowFaresViewFlights extends JViewLegacy
{

    public function display($tpl = null)
    {
        $model = $this->getModel();

        $this->assign('url', $model->getUrl());
        $this->assign('data', $model->getData());
        $this->assign('pagination', $model->getPagination());
        $this->assign('model', $model);

        $this->addScripts();
        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        $canDo = LowFaresHelper::getActions();

        JToolBarHelper::title(JText::_('COM_LOWFARES_LIST'), 'module.png');

        if ($canDo->get('core.create'))
        {
            JToolBarHelper::addNew('flights.add');
        }

        if ($canDo->get('core.edit'))
        {
            JToolBarHelper::editList('flights.edit');
        }

        if ($canDo->get('core.edit.state'))
        {
            JToolBarHelper::divider();
            JToolBarHelper::publish('flights.publish', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::unpublish('flights.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::divider();
            JToolBarHelper::custom('', 'refresh.png', 'refresh_f2.png', 'JTOOLBAR_GETFARES', false);
        }

        if ($canDo->get('core.delete'))
        {
            JToolBarHelper::divider();
            JToolBarHelper::deleteList(JText::_('COM_LOWFARES_COMFIRM_DELETE'), 'flights.delete', 'JTOOLBAR_DELETE');
        }

        if ($canDo->get('core.admin'))
        {
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_lowfares');
        }
    }

    private function addScripts()
    {
        $path = JURI::root().'administrator/components/com_lowfares/';
        $doc  = JFactory::getDocument();

        $doc->addScript($path.'js/fares.js');
        $doc->addStyleSheet($path.'css/style.css');

        $script  = 'jQuery(document).ready(function($){';
        $script .=   'AirAawsQS.process();';
        $script .= '});';

        $doc->addScriptDeclaration($script);
    }

    public function filterPublished($process = false)
    {
        if($process)
        {
            $data = array(
                ''  => JText::_('COM_LOWFARES_ALL_STATES')
            );

            $attribs = null;
        }
        else
        {
            $data = array(
                ''  => JText::_('JOPTION_SELECT_PUBLISHED')
            );

            $attribs  = 'class="inputbox" ';
            $attribs .= 'onchange="document.adminForm.submit();"';
        }

        $data['1'] = JText::_('JPUBLISHED');
        $data['0'] = JText::_('JUNPUBLISHED');

        $options = array();
        foreach($data as $key => $value)
        {
            $options[] = JHTML::_('select.option', $key, $value);
        }
        
        $value = $this->model->getState('filter_published');

        return JHTML::_('select.genericlist', $options, 'filter_published', $attribs, 'value', 'text', $value == '' && $process ? 1 : $value);
    }

    public function filterCategories($process = false)
    {
        if($process)
        {
            $info = array('' => JText::_('COM_LOWFARES_ALL_CATEGORIES'));

            $attribs = null;
        }
        else
        {
            $info = array('' => JText::_('JOPTION_SELECT_CATEGORY'));

            $attribs  = 'class="inputbox" ';
            $attribs .= 'onchange="document.adminForm.submit();"';
        }

        $options = JHtml::_('category.options', 'com_lowfares');
        $options = array_merge($info, $options);

        return JHTML::_('select.genericlist', $options, 'filter_categories', $attribs, 'value', 'text', $this->model->getState('filter_categories'));
    }

}

