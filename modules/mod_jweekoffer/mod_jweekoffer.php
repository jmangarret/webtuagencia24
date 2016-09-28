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
JHTML::_('stylesheet', 	'modules/mod_jweekoffer/assets/css/style.css');
require_once JPATH_SITE.'/administrator/components/com_jhotelreservation/helpers/defines.php';
require_once JPATH_SITE.'/administrator/components/com_jhotelreservation/helpers/utils.php';

// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );

$language = JFactory::getLanguage();
$language_tag 	= $language->getTag();
$x = $language->load('com_jhotelreservation' ,dirname(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jhotelreservation'. DS.'language') ,
				$language_tag,true);

$offer = modJWeekOfferHelper::getItem($params);

//var_dump($items);
// check if any results returned
if (empty( $offer )) {
	return;
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_jweekoffer', $params->get('layout', 'default'));
?>