<?php
/**
 * @package TCVN Thumbnails Plugin for Joomla! 2.5
 * @author http://thecoders.vn
 * @copyright(C) 2011- Thecoders.vn. All rights reserved.
 * @license GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgUserTCVN_Autologin extends JPlugin
{
	//Constructeur
	function plgUserTCVN_Autologin(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}
	
	function onUserAfterSave($user, $isnew, $success, $msg)
	{
		// just startup
		global $app, $model;
		
		$app = &JFactory::getApplication();
		
		if($app->isSite() && $isnew)
		{
			# Login process
			$credentials = array(
				"username" => $user["username"], 
				"password" => $user["password_clear"]
			);
			
			if(is_dir(JPATH_BASE . DS . "components" . DS . "com_comprofiler")) {
				$database = &JFactory::getDBO();
				$sql_sync = "INSERT IGNORE INTO #__comprofiler(id, user_id) SELECT id,id FROM #__users";
				$database->setQuery($sql_sync);
				$database->query();
			}
			
			$app->login($credentials);
			
			// You can use the "return" hidden input in your form template
			$return = $this->params->get('return_url', JRoute::_('index.php'));
			
			if($return != "") {
			  $app->redirect($return);
			}
			else {
			  $app->redirect(JRoute::_('index.php'));
			}
		}
	}
}