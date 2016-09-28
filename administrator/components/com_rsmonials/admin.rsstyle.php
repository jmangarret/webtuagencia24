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

$action = $_REQUEST['action'];

switch($action) {
	case 'edit':
		edit();
		break;
	case 'save':
		save();
		break;
	case 'restore':
		restore();
		break;
	default:
		display();
}

function display() {
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsheader.php");
	###############
	global $app;
	$limit1 = 0;
	$limit2 = 0;
	$pa = 0;
	if($_REQUEST['limit'] > 0) {
		$limit2 = $_REQUEST['limit'];
	}
	else {
		$limit2 = $app->getCfg('list_limit');
	}
	if($_REQUEST['page'] > 0) {
		$pa = $_REQUEST['page'];
	}
	else {
		$pa = 1;
	}
	$limit1 = $limit2 * ($pa-1);
	$database =& JFactory::getDBO();
	
	$database->setQuery("select count(*) as tot from `#__".RSWEBSOLS_TABLE_PREFIX."_param` where `ordering` > 0");
	$cnt = $database->loadObject();
	$total_page = ceil($cnt->tot/$limit2);
	
	$database->setQuery("select * from `#__".RSWEBSOLS_TABLE_PREFIX."_param` where `ordering` > 0 order by `ordering` limit ".$limit1.",".$limit2."");
	$items = $database->loadObjectList();
?>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th class="title" style="text-align:left;" nowrap="nowrap">CSS File</th>
			<th class="title" style="text-align:left;">Content</th>
			<th class="title">Edit</th>
			<th class="title">Restore Default</th>
		</tr>
	</thead>
		<tbody>
			<tr class="row0">
				<td valign="top" nowrap="nowrap">style.css</td>
				<td valign="top">
				<?php
				$fp = fopen(JPATH_BASE.DS.'..'.DS.'components'.DS.'com_rsmonials'.DS.'css'.DS.'style.css', "r");
				$cont = fread($fp, filesize(JPATH_BASE.DS.'..'.DS.'components'.DS.'com_rsmonials'.DS.'css'.DS.'style.css'));
				echo nl2br($cont);
				fclose($fp);
				?>				</td>
				<td align="center" valign="top"><a href="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&action=edit" title="Edit Item"><img src="components/com_rsmonials/images/edit_f2.png" border="0" alt="Edit" /></a></td>
				<td align="center" valign="top"><a href="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&action=restore" title="Restore Default" onclick="javascript:if(confirm('Restore default value! Are you sure? This will remove all your css customization.')){return true;}else{return false;}"><img src="components/com_rsmonials/images/restore_f2.png" border="0" alt="Restore" /></a></td>
			</tr>
		</tbody>
  </table>
</div>
<?php
	###############	
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsfooter.php");
}

function edit() {
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsheader.php");
	$fp = fopen(JPATH_BASE.DS.'..'.DS.'components'.DS.'com_rsmonials'.DS.'css'.DS.'style.css', "r");
	$cont = fread($fp, filesize(JPATH_BASE.DS.'..'.DS.'components'.DS.'com_rsmonials'.DS.'css'.DS.'style.css'));
	fclose($fp);
	###############
?>
<fieldset class="adminform">
				<legend>Details</legend>
				<script type="text/javascript">
				function cancelFormRS() {
					window.location.href="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&page=<?php echo $_REQUEST['page']; ?>&limit=<?php echo $_REQUEST['limit']; ?>";
				}
				</script>
				<form action="index.php" method="post" name="adminFormRS" id="adminFormRS">
				<input type="hidden" name="option" id="option" value="<?php echo $_REQUEST['option']; ?>" />
				<input type="hidden" name="task" id="task" value="<?php echo $_REQUEST['task']; ?>" />
				<input type="hidden" name="action" id="action" value="save" />
				<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST['page']; ?>" />
				<input type="hidden" name="limit" id="limit" value="<?php echo $_REQUEST['limit']; ?>" />
					<table class="admintable" width="100%">
					<tr>
						<td valign="top" class="key">Parameter Value:</td>
						<td colspan="2" valign="top">
						<textarea style="width:100%; height:300px;" name="css_value"><?php echo $cont; ?></textarea>						</td>
					</tr>
					<tr>
						<td class="key">&nbsp;</td>
						<td><input type="submit" name="submit" value="Submit" class="button" /> <input type="button" name="cancel" value="Cancel" class="button" onclick="return cancelFormRS();" /></td>
					</tr>
				</table>
				</form>
</fieldset>
<?php
	###############
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsfooter.php");
}

function save() {
	//safePost();
	$fp = fopen(JPATH_BASE.DS.'..'.DS.'components'.DS.'com_rsmonials'.DS.'css'.DS.'style.css', "w");
	$val = $_POST['css_value'];
	if(trim($val) == '') {
		$val = '/**
 * @version 1.5.3 $Id: style.css
 * @package Joomla 1.5.x
 * @subpackage RS-Monials
 * @copyright (C) 2009 RS Web Solutions (http://www.rswebsols.com)
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
		';
	}
	fwrite($fp, $val);
	fclose($fp);
	header("location:index.php?option=".$_REQUEST['option']."&task=".$_REQUEST['task']."&page=".$_REQUEST['page']."&limit=".$_REQUEST['limit']."&result=".base64_encode('CSS Style Successfully Saved')."");
	exit();
}

function restore() {
	$fp = fopen(JPATH_BASE.DS.'..'.DS.'components'.DS.'com_rsmonials'.DS.'css'.DS.'default_style.css', 'r');
	$cont = fread($fp, filesize(JPATH_BASE.DS.'..'.DS.'components'.DS.'com_rsmonials'.DS.'css'.DS.'default_style.css'));
	fclose($fp);
	if(trim($cont) == '') {
		$cont = '/**
 * @version 1.5.3 $Id: style.css
 * @package Joomla 1.5.x
 * @subpackage RS-Monials
 * @copyright (C) 2009 RS Web Solutions (http://www.rswebsols.com)
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
		';
	}
	$fp2 = fopen(JPATH_BASE.DS.'..'.DS.'components'.DS.'com_rsmonials'.DS.'css'.DS.'style.css', "w");
	fwrite($fp2, $cont);
	fclose($fp2);
	header("location:index.php?option=".$_REQUEST['option']."&task=".$_REQUEST['task']."&page=".$_REQUEST['page']."&limit=".$_REQUEST['limit']."&result=".base64_encode('CSS Style Successfully Restored.')."");
	exit();
}
?>