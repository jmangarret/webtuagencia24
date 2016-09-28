<?php
/**
 *
 */
defined('_JEXEC') or die;

/**
 *
 */
function AsomBuildRoute(&$query)
{
	$segments = array();

    $app  = JFactory::getApplication();
	$menu = $app->getMenu();

    if(empty($query['Itemid']))
    {
		$menuItem = $menu->getActive();
    }
    else
    {
		$menuItem = $menu->getItem($query['Itemid']);
    }

    // Es un menu
    if(!isset($query['task']))
    {
        return $segments;
    }

    $action = substr($query['task'], strpos($query['task'], '.') + 1);
    switch($action)
    {
    case 'display':
        unset($query['task']);
        break;
    case 'select':
        $segments[] = 'select';
        unset($query['task']);
        $segments[] = $query['cid'];
        unset($query['cid']);
        break;
    case 'resume':
        unset($query['task']);
        $segments[] = $query['order'];
        unset($query['order']);
        break;
    }


	return $segments;
}

/**
 *
 */
function AsomParseRoute($segments)
{
	$vars = array();

    if(count($segments) > 1)
    {
        $vars['task'] = 'orders.'.$segments[0];
        $vars['cid']  = $segments[1];
    }
    elseif(count($segments) == 1)
    {
        $vars['task']  = 'orders.resume';
        $vars['order'] = $segments[0];
    }
    else
    {
        $vars['task']  = 'orders.display';
    }


	return $vars;
}
