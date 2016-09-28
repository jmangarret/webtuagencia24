<?php
/**
 * @version 2.0
 * @package Joomla 2.5.x
 * @subpackage RS-Monials
 * @copyright (C) 2009 RS Web Solutions (http://www.rswebsols.com)
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

function fetchParam($name) {
	$db =& JFactory::getDBO();
	$db->setQuery("select `param_value` from `#__".RSWEBSOLS_TABLE_PREFIX."_param` where `param_name`='".$name."'");
	$data = $db->loadObject();
	return $data->param_value;
}

function fetchParamStyle($name) {
	$db =& JFactory::getDBO();
	$db->setQuery("select `param_value` from `#__".RSWEBSOLS_TABLE_PREFIX."_param_style` where `param_name`='".$name."'");
	$data = $db->loadObject();
	return $data->param_value;
}

function sendMail($smTo, $smSubject, $smBody) {
	global $mainframe;

	$subject = $smSubject;
	$message_body = nl2br($smBody);
	$to = $smTo;
	$from = fetchParam('admin_email');
	$fromName = fetchParam('admin_name');
	
	$mailer =& JFactory::getMailer();
	
	// Build e-mail message format
	$mailer->setSender(array(''.$from.'', ''.$fromName.''));
	$mailer->setSubject(stripslashes($subject));
	$mailer->setBody($message_body);
	$mailer->IsHTML(1);
	$mailer->addRecipient($to);

	// Send the Mail
	$rs	= $mailer->Send();

	// Check for an error
	if ( JError::isError($rs) ) {
		return false;
	} else {
		return true;
	}
}

function safePost() {
	foreach($_POST as $key=>$value) {
		if(!is_array($value)) {
			$_POST[''.$key.''] = addslashes($value);
		}
	}
}

function safeHTML($text="") {
	return htmlentities(stripslashes($text));
}

function checkLangDir($dname) {
	if(strpos($dname, '-') != 2) {
		return false;
	}
	if(strlen($dname)!=5) {
		return false;
	}
	if(!ctype_lower(substr($dname, 0, 2))) {
		return false;
	}
	if(!ctype_upper(substr($dname, 3, 2))) {
		return false;
	}
	return true;
}
?>