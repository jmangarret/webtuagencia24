<?php 
$app =& JFactory::getApplication();
$template = $app->getTemplate();
$template = "molina"; // Set name template to use in admin module to send notifications
$lang =& JFactory::getLanguage();
$lang->load('tpl_'.$template, JPATH_SITE);
?>
{body}


