<?php
/**
 *
 */
defined('_JEXEC') or die;

/**
 *
 */
function AawsBuildRoute(&$query)
{
	$segments = array();

    $app  = JFactory::getApplication();
	$menu = $app->getMenu();

    if(empty($query['Itemid']))
		$menuItem = $menu->getActive();
    else
		$menuItem = $menu->getItem($query['Itemid']);

    // Let's validate that the menu item will be equal to the current option
    if(($menuItem instanceof stdClass) && isset($menuItem->query['task']) && $menuItem->query['task'] == $query['task'])
    {
        unset($query['task']);
        return $segments;
    }

	return $segments;
}

/**
 *
 */
function AawsParseRoute($segments)
{
	$vars = array();

    $vars['task'] = $segments[0].'.'.$segments[1];

	return $vars;
}
