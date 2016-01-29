<?php
/**
 * @version 2.0
 * @package Joomla 2.5.x
 * @subpackage RS-Monials
 * @copyright (C) 2009 RS Web Solutions (http://www.rswebsols.com)
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;

/**
 * @param	array
 * @return	array
 */
function RSMonialsBuildRoute(&$query)
{
	$segments = array();
	
	if ($query['page'] > 0) {
		if($query['page'] == '1') {
			unset($query['page']);	
		} else {
			$segments[] = $query['page'];
			unset($query['page']);
		}
	}
	
	if (isset($query['view'])) {
		unset($query['view']);
	}
	return $segments;
}

/**
 * @param	array
 * @return	array
 */
function RSMonialsParseRoute($segments)
{
	$vars = array();

	$pg	= array_shift($segments);
	$vars['page'] = $pg;
	$vars['view'] = 'rsmonials';

	return $vars;
}
