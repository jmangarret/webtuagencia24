<?php
/**
 * @version		$Id: $
 * @author		Codextension
 * @package		Joomla!
 * @subpackage	Module
 * @copyright	Copyright (C) 2008 - 2012 by Codextension. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
$user	 	  =& JFactory::getUser();
$url  	 	  = urlencode($params->get("url","http://www.facebook.com/platform"));
$width   	  = $params->get("width","292");
$height   	  = $params->get("height","587");
$color   	  = $params->get("color","0");
$connections  = $params->get("connections","10");
$stream  	  = $params->get("stream","1");
$header  	  = $params->get("header","1");

if($color){
	$color = "dark";
}else{
	$color = "light";
}

if($stream){
	$stream = "true";
}else{
	$stream = "false";
}

if($header){
	$header = "true";
}else{
	$header = "false";
}


require(JModuleHelper::getLayoutPath('mod_fb_fanbox'));