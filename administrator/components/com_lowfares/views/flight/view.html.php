<?php
/**
 *
 */

defined('_JEXEC') or die;

/**
 *
 */
class LowFaresViewFlight extends JViewLegacy
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
        JToolBarHelper::title(JText::_('COM_LOWFARES_EDIT'), 'module.png');

        $canDo = LowFaresHelper::getActions();

        if($canDo->get('core.create') || $canDo->get('core.edit'))
        {
            JToolBarHelper::apply('flights.apply');
            JToolBarHelper::save('flights.save');
        }

        JToolBarHelper::cancel('flights.cancel');
    }

    protected function addScripts()
    {
        jimport('joomla.filesystem.file.php');

        if(!JFile::exists(JPATH_SITE.DS.'modules'.DS.'mod_aaws_qs'.DS.'cities'.DS.'ES'.DS.'K.txt'))
        {
            return false;
        }

        $path = JURI::root().'administrator/components/com_lowfares/';
        $doc  = JFactory::getDocument();

        $doc->addScript($path.'js/common.js');
        $doc->addScript($path.'js/fares.js');
        $doc->addStyleSheet($path.'css/style.css');

        $script  = 'jQuery(document).ready(function($){';
        $script .=   'AirAawsQS.load();';
        $script .= '});';
        $doc->addScriptDeclaration($script);
    }

}

