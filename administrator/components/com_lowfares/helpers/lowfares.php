<?php
/**
 *
 */
defined('_JEXEC') or die;

abstract class LowFaresHelper
{

    public static function getActions($messageId = 0)
    {       
        jimport('joomla.access.access');

        $user      = JFactory::getUser();
        $result    = new JObject;
        $assetName = 'com_lowfares';
        $actions   = JAccess::getActions('com_lowfares', 'component');

        foreach($actions as $action)
        {
            $result->set($action->name, $user->authorise($action->name, $assetName));
        }

        return $result;
    }

	public static function addSubmenu($vName)
    {
		JSubMenuHelper::addEntry(
			JText::_('LFS_FLIGHTS'),
            'index.php?option=com_lowfares&task=flights.display',
			$vName == 'flights'
		);

		JSubMenuHelper::addEntry(
			JText::_('LFS_CATEGORIES'),
			'index.php?option=com_categories&extension=com_lowfares',
			$vName == 'categories'
		);
    }

}

