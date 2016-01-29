<?php
/**
 *
 */

defined('_JEXEC') or die;

/**
 *
 */
class FeesViewAdminFare extends JViewLegacy
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
        JToolBarHelper::title(JText::_('COM_ADMINFARE_EDIT'), 'module.png');

        $canDo = FeesHelper::getActions();

        if($canDo->get('core.create') || $canDo->get('core.edit'))
        {
            JToolBarHelper::apply('adminfare.apply');
            JToolBarHelper::save('adminfare.save');
        }

        JToolBarHelper::cancel('adminfare.cancel');
    }

    protected function getAirlineName($iata)
    {
        $model = $this->getModel();

        return $model->getAirlineName($iata);
    }

    protected function getAirlines()
    {
        $airlines = $this->getModel()->getAirlines();
        return JHTML::_('select.genericlist', $airlines, 'jform[airline]', null, 'codigo', 'nombre', $this->data->airline);
    }

    protected function addScripts()
    {
        $path = JURI::root().'administrator/components/com_fees/';
        $doc  = JFactory::getDocument();

        $doc->addStyleSheet($path.'css/style.css');
        $doc->addScript($path.'js/fees.js');

        $script  = 'jQuery(document).ready(function($){';
        $script .=   'TA.load();';
        $script .= '});';
        $doc->addScriptDeclaration($script);
    }

}

