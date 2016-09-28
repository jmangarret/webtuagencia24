<?php 
/*
# ------------------------------------------------------------------------
# Extensions for Joomla 2.5.x - Joomla 3.x
# ------------------------------------------------------------------------
# Copyright (C) 2011-2013 Ext-Joom.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2.
# Author: Ext-Joom.com
# Websites:  http://www.ext-joom.com 
# Date modified: 04/08/2013 - 13:00
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die;
$doc 				= JFactory::getDocument();
$moduleclass_sfx	= $params->get('moduleclass_sfx');
$html 				= $params->get('html');
$css 				= $params->get('css'); 
$js 				= $params->get('js');

if (strlen($css) > 0) { $doc->addStyleDeclaration($css); }
if (strlen($js) > 0)  { $doc->addScriptDeclaration($js); }

require JModuleHelper::getLayoutPath('mod_ext_custom', $params->get('layout', 'default'));
?>