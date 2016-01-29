<?php
/**
 *
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class AawsViewAvailability extends JView
{

    protected $module = null;

	function display($tpl = null)
	{
        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration('var _data = '.json_encode($this->data));

        jimport( 'joomla.application.module.helper' );

        // El modulo debe estar configurado de acuerdo a las necesidades del componente
        $module = JModuleHelper::getModule('mod_aaws_qs');

        if($module->id != 0)
        {
            $params = new JRegistry($module->params);
            $module->params = $params->toString();
        }
        else
        {
            $module = false;
        }

        $this->module = $module;

        parent::display($tpl);
    }

}
