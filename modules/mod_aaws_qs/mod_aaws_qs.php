<?php
/**
 * 
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

AawsQsHelper::putResources($params);

require JModuleHelper::getLayoutPath('mod_aaws_qs', 'default');
