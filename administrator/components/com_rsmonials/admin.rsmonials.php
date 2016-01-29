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

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);

require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rssettings.php");
require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsfunctions.php");

$task = $_REQUEST['task'];

switch($task) {
	case 'testi':
		require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."admin.rstestimonials.php");
		break;
	case 'conf':
		require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."admin.rssettings.php");
		break;
	case 'style':
		require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."admin.rsstylesettings.php");
		break;
	case 'css':
		require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."admin.rsstyle.php");
		break;
	case 'lang':
		require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."admin.rslanguage.php");
		break;
	case 'doc':
		require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."admin.rsdocumentation.php");
		break;
	default:
		display_main();
		break;
}

function display_main() {
?>
<div id="element-box">
	<div class="t"><div class="t"><div class="t"></div></div></div>
	<div class="m">
	<table class="adminform"><tr><td width="70%" valign="top">
	<div id="cpanel">
		
		<div style="float:left;"><div class="icon"><a href="index.php?option=com_rsmonials&task=testi" style="height:auto;"><img src="components/com_rsmonials/images/testi.gif" alt="Testimonials" /><span style="font-weight:bold; font-size:16px;">Testimonials<br /><br /></span></a></div></div>
		
		<div style="float:left;"><div class="icon"><a href="index.php?option=com_rsmonials&task=conf" style="height:auto;"><img src="components/com_rsmonials/images/conf.png" alt="Settings" /><span style="font-weight:bold; font-size:16px;">Settings<br /><br /></span></a></div></div>
		
        <div style="float:left;"><div class="icon"><a href="index.php?option=com_rsmonials&task=style" style="height:auto;"><img src="components/com_rsmonials/images/style.png" alt="Display Style" /><span style="font-weight:bold; font-size:16px;">Display<br />Style<br /></span></a></div></div>
		
        <div style="float:left;"><div class="icon"><a href="index.php?option=com_rsmonials&task=css" style="height:auto;"><img src="components/com_rsmonials/images/css.png" alt="CSS File" /><span style="font-weight:bold; font-size:16px;">CSS<br />File<br /></span></a></div></div>
		
		<div style="float:left;"><div class="icon"><a href="index.php?option=com_rsmonials&task=lang" style="height:auto;"><img src="components/com_rsmonials/images/language.png" alt="Language File" /><span style="font-weight:bold; font-size:16px;">Language<br />File<br /></span></a></div></div>
		
		<div style="float:left;"><div class="icon"><a href="http://www.rswebsols.com/doc/rsmonials-support" style="height:auto;" target="_blank"><img src="components/com_rsmonials/images/doc.gif" alt="Documentation" /><span style="font-weight:bold; font-size:16px;">Help /<br />Readme<br /></span></a></div></div>
		
	</div>
	</td><td valign="top">
	<?php include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rscompany.php"); ?>
	</td></tr></table>
	</div>
	<div class="b"><div class="b"><div class="b"></div></div></div>
</div>
<?php
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsfooter.php");
}
?>