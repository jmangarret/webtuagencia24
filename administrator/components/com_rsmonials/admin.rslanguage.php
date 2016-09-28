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
<div>
<div>&nbsp;</div>
<table width="100%" cellpadding="0" cellspacing="0"><tr><td><h1>Select the Language file to edit.</h1></td><td align="right"></td></tr></table>
<div>&nbsp;</div>
</div>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th class="title" style="text-align:left;" nowrap="nowrap">Language File</th>
			<th class="title" style="width:20%;">Edit</th>
			<th class="title" style="width:20%;">Restore Default</th>
		</tr>
	</thead>
		<tbody>
			<?php
			$dir = JPATH_BASE.DS.'..'.DS.'language';
			$display_path = 'language';
			$dh = opendir($dir);
			$cntr = 0;
			while (($file = readdir($dh)) !== false) {
				if(!is_dir($dir .DS. $file)) {
					continue;
				}
				else {
					$dname = trim($file);
					if(checkLangDir($dname)) {
						$langfiledir = $dir.DS.$dname.DS;
						$langfilename = $langfiledir.$dname.'.com_rsmonials.ini';
						$langdisplaypath = $display_path.DS.$dname.DS.$dname.'.com_rsmonials.ini';
						if(!file_exists($langfilename)) {
							$lfp = fopen($langfilename, 'a');
							fwrite($lfp, RSWEBSOLS_DEFAULT_LANGUAGE_FILE_CONTENT);
							fclose($lfp);
						}
			?>
						<tr class="row<?php echo $cntr; ?>">
							<td valign="top" nowrap="nowrap"><strong><?php echo $langdisplaypath; ?></strong></td>
							<td align="center" valign="top"><a href="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&action=edit&filepath=<?php echo $langdisplaypath; ?>" title="Edit Item"><img src="components/com_rsmonials/images/edit_f2.png" border="0" alt="Edit" /></a></td>
							<td align="center" valign="top"><a href="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&action=restore&filepath=<?php echo $langdisplaypath; ?>" title="Restore Default" onclick="javascript:if(confirm('Restore default value! Are you sure? This will remove all your css customization.')){return true;}else{return false;}"><img src="components/com_rsmonials/images/restore_f2.png" border="0" alt="Restore" /></a></td>
						</tr>
			<?php
						$cntr++;
					}
				}
			}
			closedir($dh);
			?>
		</tbody>
  </table>
</div>
<?php
	###############	
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsfooter.php");
}

function edit() {
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsheader.php");
	$langfile = JPATH_BASE.DS.'..'.DS.$_REQUEST['filepath'];
	$fp = fopen($langfile, "r");
	$cont = stripslashes(fread($fp, filesize($langfile)));
	fclose($fp);
	###############
?>
<div>
<div>&nbsp;</div>
<div><h1><?php echo ucfirst(strtolower($_REQUEST['action'])); ?> language file "<strong><?php echo $_REQUEST['filepath']; ?></strong></strong>".</h1></div>
<div>&nbsp;</div>
</div>
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
				<input type="hidden" name="filepath" id="filepath" value="<?php echo $_REQUEST['filepath']; ?>" />
					<table class="admintable" width="100%">
					<tr>
						<td valign="top" class="key">File Content:</td>
						<td colspan="2" valign="top">
						<textarea style="width:100%; height:400px;" name="lang_file_content"><?php echo $cont; ?></textarea>						</td>
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
	$fp = fopen(JPATH_BASE.DS.'..'.DS.$_POST['filepath'], "w");
	$val = $_POST['lang_file_content'];
	if(trim($val) == '') {
		$val = RSWEBSOLS_DEFAULT_LANGUAGE_FILE_CONTENT;
	}
	fwrite($fp, stripslashes($val));
	fclose($fp);
	header("location:index.php?option=".$_REQUEST['option']."&task=".$_REQUEST['task']."&page=".$_REQUEST['page']."&limit=".$_REQUEST['limit']."&result=".base64_encode('Content Successfully Saved')."");
	exit();
}

function restore() {
	$cont = RSWEBSOLS_DEFAULT_LANGUAGE_FILE_CONTENT;
	$fp = fopen(JPATH_BASE.DS.'..'.DS.$_REQUEST['filepath'], "w");
	fwrite($fp, $cont);
	fclose($fp);
	header("location:index.php?option=".$_REQUEST['option']."&task=".$_REQUEST['task']."&page=".$_REQUEST['page']."&limit=".$_REQUEST['limit']."&result=".base64_encode('Content Successfully Restored.')."");
	exit();
}
?>