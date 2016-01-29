<?php
/**
 *
 */
defined('_JEXEC') or die;

abstract class FeesHelper
{

    public static function getActions($messageId = 0)
    {       
        jimport('joomla.access.access');

        $user      = JFactory::getUser();
        $result    = new JObject;
        $assetName = 'com_fees';
        $actions   = JAccess::getActions('com_fees', 'component');

        foreach($actions as $action)
        {
            $result->set($action->name, $user->authorise($action->name, $assetName));
        }

        return $result;
    }

	public static function addSubmenu($vName)
    {
		JSubMenuHelper::addEntry(
			JText::_('FEE_GROUPS'),
            'index.php?option=com_fees&task=groups.display',
			$vName == 'groups'
		);

		JSubMenuHelper::addEntry(
			JText::_('FEES_ADMINFARE'),
			'index.php?option=com_fees&task=adminfare.display',
			$vName == 'adminfare'
		);
    }

}

