<?php
/**
 *
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class AawsViewPassenger extends JView
{

    protected $module = null;

	function display($tpl = null)
	{
        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration('var _data = '.json_encode($this->data));

        $params = JComponentHelper::getParams('com_aaws');
        $this->assign('tos_id', $params->get('tos_id', 0));

        parent::display($tpl);
    }

}
