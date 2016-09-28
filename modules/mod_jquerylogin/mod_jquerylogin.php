<?php
/**
 * @version		$Id: mod_login.php 22338 2011-11-04 17:24:53Z github_bot $
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
require_once JPATH_SITE.'/administrator/components/com_jhotelreservation/helpers/defines.php'; 
require_once JPATH_SITE.'/administrator/components/com_jhotelreservation/helpers/utils.php'; 

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';


if(JHotelUtil::isJoomla3()){
	JHtml::_('jquery.framework', true, true);
}else{
	if(!defined('J_JQUERY_LOADED')) {
		JHotelUtil::includeFile('script', 		'jquery.js', 'components/com_jbusinessdirectory/assets/js/');
		define('J_JQUERY_LOADED', 1);
	}
}
JHotelUtil::includeFile('script', 		'jquery.blockUI.js', 'administrator/components/com_jhotelreservation/assets/js/');
JHotelUtil::includeFile('stylesheet', 	'jquerylogin.css', 		    'modules/mod_jquerylogin/css/');

$params->def('greeting', 1);

$type	= modJQueryLoginHelper::getType();
$return	= modJQueryLoginHelper::getReturnURL($params, $type);
$user	= JFactory::getUser();

$document  =JFactory::getDocument();
$document->addScriptDeclaration('
			window.onload = function()	{
				 jQuery.noConflict();
			};  
');

require JModuleHelper::getLayoutPath('mod_jquerylogin', $params->get('layout', 'default'));
