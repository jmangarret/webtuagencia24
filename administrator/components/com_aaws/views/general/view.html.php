<?php
/**
 *
 */

defined('_JEXEC') or die;

/**
 *
 */
class AawsViewGeneral extends JViewLegacy
{

    public function display($tpl = null)
    {
        $this->url       = 'index.php?option=com_aaws&task=general.display';
        $this->form      = $this->get('Form');
        $this->component = $this->get('Component');

        $this->addToolbar();
        $this->addScripts();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('COM_AAWS_GENERAL_INFO'), 'module.png');

        $canDo = AawsHelper::getActions();

        if($canDo->get('core.edit'))
        {
            JToolBarHelper::apply('general.save');
        }

        JToolBarHelper::cancel('general.cancel');

        if ($canDo->get('core.admin'))
        {
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_aaws');
        }

    }

    private function addScripts()
    {
        $script  = 'jQuery(document).ready(function($){';
        $script .=   'var _DATA, onLoad;';
        $script .=   '_DATA = $("#adminForm").find("[name^=jform]").clone();';
        $script .=   'onLoad = function(){';
        $script .=     '$(this).contents().find("form").append($("<div class=\'hidden\' style=\'display:none;\'/>"));';
        $script .=     '$(this).contents().find("form div.hidden").append(_DATA);';
        $script .=   '};';
        $script .=   '$("#toolbar-popup-options a").click(function(){';
        $script .=     'window.setTimeout(function(){';
        $script .=       '$("#sbox-content iframe").load(onLoad);';
        $script .=     '}, 300);';
        $script .=   '});';
        $script .= '});';

        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration($script);
    }

}

