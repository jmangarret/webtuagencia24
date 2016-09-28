<?php
/**
 *
 */
defined('_JEXEC') or die;

abstract class AawsHelper
{

    public static function getActions($messageId = 0)
    {       
        jimport('joomla.access.access');

        $user      = JFactory::getUser();
        $result    = new JObject;
        $assetName = 'com_aaws';
        $actions   = JAccess::getActions('com_aaws', 'component');

        foreach($actions as $action)
        {
            $result->set($action->name, $user->authorise($action->name, $assetName));
        }

        return $result;
    }

}

