<?php
/**
 * Discount Flights
 *
 * @autor	Dora Peña
 * @email   dora.pena@periferia-it.com
 * @date    November 2013
 */
// No direct access to this file
defined('_JEXEC') or die;
JLoader::register('moddiscountflightsHelper', JPATH_BASE.'/modules/mod_discountflights/helper.php');
JLoader::register('AawsHelperRoute', JPATH_SITE.DS.'components'.DS.'com_aaws'.DS.'helpers'.DS.'route.php');
//moddiscountflightsHelper::getGreeting();
 
moddiscountflightsHelper::putResources($params);
require JModuleHelper::getLayoutPath('mod_discountflights', 'default');
