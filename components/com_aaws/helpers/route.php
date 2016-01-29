<?php
/**
 *
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');

/**
 *
 */
abstract class AawsHelperRoute
{
    protected static $lookup;

    public static function getFlowRoute($task, $language = 0)
    {
        $needles = array(
            'task'  => $task
        );
        //Create the link
        $link = 'index.php?option=com_aaws&task=' . $task;

        if ($language && $language != "*" && JLanguageMultilang::isEnabled())
        {
            $db    = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('a.sef AS sef');
            $query->select('a.lang_code AS lang_code');
            $query->from('#__languages AS a');
            $query->where('a.lang_code = ' .$language);
            $db->setQuery($query);
            $langs = $db->loadObjectList();

            foreach ($langs as $lang)
            {
                if ($language == $lang->lang_code)
                {
                    $language = $lang->sef;
                    $link .= '&lang='.$language;
                }
            }
        }

        if ($item = self::_findItem($needles))
            $link .= '&Itemid='.$item;

        elseif ($item = self::_findItem())
            $link .= '&Itemid='.$item;

        return $link;
    }

    protected static function _findItem($needles = null)
    {
        $app   = JFactory::getApplication();
        $menus = $app->getMenu('site');

        // Prepare the reverse lookup array.
        if (self::$lookup === null)
        {
            self::$lookup = array();

            $component = JComponentHelper::getComponent('com_aaws');
            $items     = $menus->getItems('component_id', $component->id);
            foreach ($items as $item)
            {
                if (isset($item->query) && isset($item->query['task']))
                {
                    $task = $item->query['task'];
                    if (!isset(self::$lookup[$task])) {
                        self::$lookup[$task] = $item->id;
                    }
                }
            }
        }

        if ($needles)
        {
            foreach ($needles as $task)
            {
                if (isset(self::$lookup[$task]))
                    return self::$lookup[$task];
            }
        }
        else
        {
            $active = $menus->getActive();
            if ($active && $active->component == 'com_aaws') {
                return $active->id;
            }
        }

        return null;
    }
}
