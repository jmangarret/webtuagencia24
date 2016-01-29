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
?>
<div><?php include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rscompany_h.php"); ?></div>
<div>
	<div id="submenu-box">
		<div class="t"><div class="t"><div class="t"></div></div></div>
		<div class="m">
			<ul id="submenu">
				<li><a href="index.php?option=com_rsmonials">Home</a></li>
				<li><a href="index.php?option=com_rsmonials&task=testi" <?php if($_REQUEST['task'] == 'testi') { ?>class="active"<?php } ?>>Testimonials</a></li>
				<li><a href="index.php?option=com_rsmonials&task=conf" <?php if($_REQUEST['task'] == 'conf') { ?>class="active"<?php } ?>>Settings</a></li>
                <li><a href="index.php?option=com_rsmonials&task=style" <?php if($_REQUEST['task'] == 'style') { ?>class="active"<?php } ?>>Display Style</a></li>
				<li><a href="index.php?option=com_rsmonials&task=css" <?php if($_REQUEST['task'] == 'css') { ?>class="active"<?php } ?>>CSS File</a></li>
				<li><a href="index.php?option=com_rsmonials&task=lang" <?php if($_REQUEST['task'] == 'lang') { ?>class="active"<?php } ?>>Language File</a></li>
				<li><a href="http://www.rswebsols.com/doc/rsmonials-support" target="_blank" <?php if($_REQUEST['task'] == 'doc') { ?>class="active"<?php } ?>>Help / Readme / Documentation</a></li>
			</ul>
			<div class="clr"></div>
		</div>
		<div class="b"><div class="b"><div class="b"></div></div></div>
	</div>
</div>
<?php if(trim(base64_decode($_REQUEST['result'])) != '') { ?>
<div class="clr"></div><dl id="system-message"><dt class="message">Message</dt><dd class="message message fade"><ul><li><?php echo trim(base64_decode($_REQUEST['result'])); ?></li></ul></dd></dl><div class="clr"></div>
<?php } ?>