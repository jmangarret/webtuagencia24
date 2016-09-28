<?php
/**
* @version 1.0.8
* @package PWebFBLikeBox
* @copyright © 2014 Perfect Web sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).'/helper.php');

$app = JFactory::getApplication();

$params->def('id', $module->id);
$cfg = JFactory::getConfig();
if ($cfg->get('debug') OR $app->input->getInt('debug')) $params->set('debug', 1);

$layout = $params->get('layout', 'box');
if (strpos($layout, 'static') === false)
{
	if ($app->input->get('tmpl') == 'component') return;
	
	// Set media path
	$media_url = JURI::base(true).'/media/mod_pwebfblikebox/';
	
	$doc = JFactory::getDocument();
	if (strpos($layout, 'tab') === false)
	{
		JHtml::_('behavior.framework');
		$doc->addScript($media_url.'js/mootools.likebox.js');
	}
	
	JHtml::_('stylesheet', 'mod_pwebfblikebox/likebox.css', array(), true, false, false, false);
	
	// IE CSS
	if (!defined('MOD_PWEB_FBLIKEBOX_IE')) {
		define('MOD_PWEB_FBLIKEBOX_IE', 1);
		$doc->addCustomTag(
			 '<!--[if lte IE 8]>'."\r\n"
			.'<link rel="stylesheet" href="'.$media_url.'css/ie.css" />'."\r\n"
			.'<![endif]-->'."\r\n"
		);
	}
}

// Auto RTL
$rtl = (int)JFactory::getLanguage()->isRTL();
$params->set('rtl', $rtl);
if ($rtl) $params->set('align', $params->get('align') == 'left' ? 'right' : 'left');

// Set params
modPWebFBLikeBoxHelper::setParams($params);

// Get LikeBox
$LikeBox = modPWebFBLikeBoxHelper::displayLikeBox();

require JModuleHelper::getLayoutPath('mod_pwebfblikebox', $layout);
