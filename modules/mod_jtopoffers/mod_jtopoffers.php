<?php

/**
 * @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_SITE.'/administrator/components/com_jhotelreservation/helpers/utils.php';
JHTML::_('stylesheet', 	'modules/mod_jtopoffers/assets/css/style.css');
if(JHotelUtil::isJoomla3()){
	JHtml::_('jquery.framework', true, true); //load jQuery before other js
	JHtml::_('behavior.framework');

}else{
	if(!defined('J_JQUERY_LOADED')) {
		JHTML::_('script','components/com_jhotelreservation/assets/js/jquery.min.js');
		define('J_JQUERY_LOADED', 1);
	}
}

require_once JPATH_SITE.'/administrator/components/com_jhotelreservation/helpers/defines.php';
require_once JPATH_SITE.'/administrator/components/com_jhotelreservation/helpers/utils.php';

// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );

$items = modJTopOffersHelper::getItems($params);

//var_dump($items);
// check if any results returned
if (empty( $items )) {
	return;
}

$language_tag = JRequest::getVar( '_lang' );
$language = JFactory::getLanguage();
$x = $language->load(
		'com_jhotelreservation' ,
		dirname(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jhotelreservation'. DS.'language') ,
		$language_tag,
		true
); 

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_jtopoffers', $params->get('base-layout','default'));
?>