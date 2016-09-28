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
	case 'add':
	case 'edit':
		add_edit();
		break;
	case 'save':
		save();
		break;
	case 'publish':
		publish();
		break;
	case 'unpublish':
		unpublish();
		break;
	case 'delete':
		delete();
		break;
	case 'delsel':
		delsel();
		break;
	default:
		display();
		break;
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
	
	$database->setQuery("select count(*) as tot from `#__".RSWEBSOLS_TABLE_PREFIX."` where 1");
	$cnt = $database->loadObject();
	$total_page = ceil($cnt->tot/$limit2);
	
	$database->setQuery("select * from `#__".RSWEBSOLS_TABLE_PREFIX."` where 1 order by `date` desc, `id` desc limit ".$limit1.",".$limit2."");
	$items = $database->loadObjectList();
?>
<div>
<div>&nbsp;</div>
<script type="text/ecmascript">
function delTesti() {
	var f = document.adminform;
	var testims = '';
	var cnt = 0;
	for(var i=0; i<f.delchk.length; i++) {
		if(f.delchk[i].checked) {
			if(testims != '') {
				testims = testims + ',';
			}
			cnt++;
			testims = testims + f.delchk[i].value;
		}
	}
	if(cnt == 0) {
		alert("No testimonial is selected to delete.");
		return false;
	}
	if(confirm("Delete selected testimonials! Are you sure?")) {
		window.location = 'index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&action=delsel&ids='+testims+'&page=<?php echo $_REQUEST['page']; ?>&limit=<?php echo $_REQUEST['limit']; ?>';
	} else {
		return false;
	}
	
}

function chkUnchkAll() {
	var f = document.adminform;
	if(f.chkAll.checked) {
		for(var i=0; i<f.delchk.length; i++) {
			f.delchk[i].checked = true;
		}
	} else {
		for(var i=0; i<f.delchk.length; i++) {
			f.delchk[i].checked = false;
		}
	}
}
</script>
<table width="100%" cellpadding="0" cellspacing="0"><tr><td><h1>Manage Testimonials</h1></td><td align="right"><table cellpadding="0" cellspacing="0"><tr><td style="padding:0 10px;"><a href="javascript:void(0);" title="Delete Selected Testimonials" onclick="return delTesti();"><img src="components/com_rsmonials/images/delete_f2.png" border="0" alt="Delete Selected Testimonials" /></a></td><td style="padding:0 10px;"><a href="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&action=add&page=<?php echo $_REQUEST['page']; ?>&limit=<?php echo $_REQUEST['limit']; ?>" title="Add New Testimonial"><img src="components/com_rsmonials/images/new_f2.png" border="0" alt="Add" /></a></td></tr></table></td></tr></table>
</div>
<div>&nbsp;</div>
<div id="editcell">
<form action="index.php" method="post" name="adminform" id="adminform">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><input type="checkbox" name="chkAll" id="chkAll" onclick="return chkUnchkAll();" /></th>
            <th width="5">#</th>
            <?php if(fetchParam('show_image') == 'true') { ?>
            <th class="title">Image</th>
            <?php } ?>
			<th class="title" style="text-align:left;">Name</th>
			<th class="title" style="text-align:left;">Email</th>
			<?php if(fetchParam('show_about') == 'true') { ?><th class="title" style="text-align:left;">About</th><?php } ?>
			<?php if(fetchParam('show_location') == 'true') { ?><th class="title" style="text-align:left;">Location</th><?php } ?>
			<?php if(fetchParam('show_website') == 'true') { ?><th class="title">Website</th><?php } ?>
			<th class="title" style="text-align:left;">Comment</th>
			<th nowrap="nowrap">Published</th>
			<th width="5" nowrap="nowrap">ID</th>
			<th class="title">Edit</th>
			<th class="title">Delete</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="19">
				<script type="text/JavaScript">
				<!--
				function MM_jumpMenu(targ,selObj,restore){ //v3.0
				  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
				  if (restore) selObj.selectedIndex=0;
				}
				//-->
				</script>
				<del class="container"><div class="pagination">

<div class="limit">Display #:
<select name="limit" id="limit" class="inputbox" size="1" onchange="MM_jumpMenu('parent',this,0)">
	<option value="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&page=1&limit=5" <?php if ($limit2 == '5') : ?>selected="selected"<?php endif; ?>>5</option>
	<option value="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&page=1&limit=10" <?php if ($limit2 == '10') : ?>selected="selected"<?php endif; ?>>10</option>
	<option value="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&page=1&limit=15" <?php if ($limit2 == '15') : ?>selected="selected"<?php endif; ?>>15</option>
	<option value="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&page=1&limit=20" <?php if ($limit2 == '20') : ?>selected="selected"<?php endif; ?>>20</option>
	<option value="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&page=1&limit=25" <?php if ($limit2 == '25') : ?>selected="selected"<?php endif; ?>>25</option>
	<option value="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&page=1&limit=30" <?php if ($limit2 == '30') : ?>selected="selected"<?php endif; ?>>30</option>
	<option value="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&page=1&limit=50" <?php if ($limit2 == '50') : ?>selected="selected"<?php endif; ?>>50</option>
	<option value="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&page=1&limit=100" <?php if ($limit2 == '100') : ?>selected="selected"<?php endif; ?>>100</option>
	<option value="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&page=1&limit=999999" <?php if ($limit2 == '999999') : ?>selected="selected"<?php endif; ?>>all</option>
</select> | Page:
<select name="page" id="page" class="inputbox" size="1" onchange="MM_jumpMenu('parent',this,0)">
	<?php for($i=1;$i<=$total_page;$i++) { ?>
	<option value="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&page=<?php echo $i; ?>&limit=<?php echo $limit2; ?>" <?php if ($i == $pa) : ?>selected="selected"<?php endif; ?>><?php echo $i; ?></option>
	<?php } ?>
</select>
</div>
</div></del>			</td>
		</tr>
	</tfoot>
		<tbody>
			<?php
			if(count($items) > 0) {
				$cnt = 1;
			foreach ($items as $item) {
				foreach($item as $k=>$v) {
					$item->$k = stripslashes($v);
				}
			?>
			<tr class="row<?php echo (1-($cnt%2)); ?>">
				<td valign="top"><input type="checkbox" name="delchk" id="delchk_<?php echo $item->id; ?>" value="<?php echo $item->id; ?>" /></td>
                <td valign="top"><?php echo $cnt; ?></td>
				<?php if(fetchParam('show_image') == 'true') { ?>
                <td valign="top" align="center">
                	<?php
					$testi_pic_file = '';
					if(file_exists('../images/com_rsmonials/'.$item->id.'.gif')) {
						$testi_pic_file = '../images/com_rsmonials/'.$item->id.'.gif';
					} else if(file_exists('../images/com_rsmonials/'.$item->id.'.png')) {
						$testi_pic_file = '../images/com_rsmonials/'.$item->id.'.png';
					} else if(file_exists('../images/com_rsmonials/'.$item->id.'.jpg')) {
						$testi_pic_file = '../images/com_rsmonials/'.$item->id.'.jpg';
					} else if(file_exists('../images/com_rsmonials/'.$item->id.'.jpeg')) {
						$testi_pic_file = '../images/com_rsmonials/'.$item->id.'.jpeg';
					}
					if($testi_pic_file != '') {
		?>
            		<img src="<?php echo $testi_pic_file; ?>" width="48" />
                	<?php } ?>
                </td>
                <?php } ?>
                <td valign="top"><?php if(fetchParam('show_single_name_field') == 'true') { echo $item->fname; } else { echo $item->fname.' '.$item->lname; } ?><?php if($item->status=='2') { ?><img src="components/com_rsmonials/images/new_f2.png" border="0" alt="New Testimonials" height="16" width="16" /><?php } ?></td>
				<td valign="top"><?php echo $item->email; ?></td>
				<?php if(fetchParam('show_about') == 'true') { ?><td valign="top"><?php echo $item->about; ?></td><?php } ?>
				<?php if(fetchParam('show_location') == 'true') { ?><td valign="top"><?php echo $item->location; ?></td><?php } ?>
				<?php if(fetchParam('show_website') == 'true') { ?><td valign="top"><?php echo $item->website; ?></td><?php } ?>
				<td valign="top"><?php echo nl2br($item->comment); ?></td>
				<td align="center" valign="top">
				<?php if($item->status == 1) { ?>
				<a href="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&action=unpublish&id=<?php echo $item->id; ?>&page=<?php echo $_REQUEST['page']; ?>&limit=<?php echo $_REQUEST['limit']; ?>" title="Unpublish Item"><img src="components/com_rsmonials/images/tick.png" border="0" alt="Published" /></a>
				<?php } else { ?>
				<a href="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&action=publish&id=<?php echo $item->id; ?>&page=<?php echo $_REQUEST['page']; ?>&limit=<?php echo $_REQUEST['limit']; ?>" title="Publish Item"><img src="components/com_rsmonials/images/publish_x.png" border="0" alt="Unpublished" /></a>
				<?php } ?>				</td>
				<td align="center" valign="top"><?php echo $item->id; ?></td>
				<td align="center" valign="top"><a href="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&action=edit&id=<?php echo $item->id; ?>&page=<?php echo $_REQUEST['page']; ?>&limit=<?php echo $_REQUEST['limit']; ?>" title="Edit Item"><img src="components/com_rsmonials/images/edit_f2.png" border="0" alt="Edit" width="20" /></a></td>
				<td align="center" valign="top"><a href="index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&action=delete&id=<?php echo $item->id; ?>&page=<?php echo $_REQUEST['page']; ?>&limit=<?php echo $_REQUEST['limit']; ?>" title="Delete Item" onclick="javascript:if(confirm('Delete testimonial #<?php echo $item->id; ?>. Are you sure?')){return true;}else{return false;}"><img src="components/com_rsmonials/images/delete_f2.png" border="0" alt="Delete" width="20" /></a></td>
			</tr>
			<?php
				$cnt++;
			}
			} else {
			?>
			<tr><td colspan="19">No Testimonials Found.</td></tr>
			<?php
			}
			?>
		</tbody>
  </table>
</form>
</div>
<?php
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsfooter.php");
	###############
}

function publish() {
	$database =& JFactory::getDBO();
	$database->setQuery("update `#__".RSWEBSOLS_TABLE_PREFIX."` set `status`='1' where `id`='".$_REQUEST['id']."'");
	$database->query();
	header("location:index.php?option=".$_REQUEST['option']."&task=".$_REQUEST['task']."&page=".$_REQUEST['page']."&limit=".$_REQUEST['limit']."&result=".base64_encode('Testimonial Successfully Published.')."");
	exit();
}

function unpublish() {
	$database =& JFactory::getDBO();
	$database->setQuery("update `#__".RSWEBSOLS_TABLE_PREFIX."` set `status`='0' where `id`='".$_REQUEST['id']."'");
	$database->query();
	header("location:index.php?option=".$_REQUEST['option']."&task=".$_REQUEST['task']."&page=".$_REQUEST['page']."&limit=".$_REQUEST['limit']."&result=".base64_encode('Testimonial Successfully Unpublished.')."");
	exit();
}

function delete() {
	$database =& JFactory::getDBO();
	$database->setQuery("delete from `#__".RSWEBSOLS_TABLE_PREFIX."` where `id`='".$_REQUEST['id']."'");
	$database->query();
	header("location:index.php?option=".$_REQUEST['option']."&task=".$_REQUEST['task']."&page=".$_REQUEST['page']."&limit=".$_REQUEST['limit']."&result=".base64_encode('Testimonial Successfully Deleted.')."");
	exit();
}

function delsel() {
	$database =& JFactory::getDBO();
	$database->setQuery("delete from `#__".RSWEBSOLS_TABLE_PREFIX."` where `id` in (".$_REQUEST['ids'].")");
	$database->query();
	header("location:index.php?option=".$_REQUEST['option']."&task=".$_REQUEST['task']."&page=".$_REQUEST['page']."&limit=".$_REQUEST['limit']."&result=".base64_encode('Testimonial(s) Successfully Deleted.')."");
	exit();
}

function add_edit() {
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsheader.php");
	###############
	if($_REQUEST['id'] > 0) {
		$database =& JFactory::getDBO();
		$database->setQuery("select * from `#__".RSWEBSOLS_TABLE_PREFIX."` where `id`='".$_REQUEST['id']."'");
		$row = $database->loadObject();
		foreach($row as $k=>$v) {
			$row->$k = stripslashes($v);
		}
	}
	###############
?>
<div>
<div><h1><?php echo ucfirst(strtolower($_REQUEST['action'])); ?> Testimonial</h1></div>
<div>&nbsp;</div>
</div>
<fieldset class="adminform">
				<legend>Details</legend>
				<script type="text/javascript">
				function submitFormRS() {
					var f = document.adminFormRS;
					<?php if(fetchParam('show_single_name_field') == 'true') { ?>
					if(trim(f.fname.value) == '') {
						alert("Please enter name.");
						f.fname.focus();
						return false;
					}
					<?php } else { ?>
					if(trim(f.fname.value) == '') {
						alert("Please enter first name.");
						f.fname.focus();
						return false;
					}
					if(trim(f.lname.value) == '') {
						alert("Please enter last name.");
						f.lname.focus();
						return false;
					}
					<?php } ?>
					if(trim(f.email.value) == '') {
						alert("Please enter email address.");
						f.email.focus();
						return false;
					}
					if((/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z.]{2,5}$/).exec(f.email.value)==null) {
						alert("Please enter valid email");
						f.email.focus();
						return false;
					}
					if(trim(f.comments.value) == '') {
						alert("Please enter comments.");
						f.comments.focus();
						return false;
					}
					f.submit();
				}
				
				function cancelFormRS() {
					location.href='index.php?option=<?php echo $_REQUEST['option']; ?>&task=<?php echo $_REQUEST['task']; ?>&page=<?php echo $_REQUEST['page']; ?>&limit=<?php echo $_REQUEST['limit']; ?>';
				}
				
				function trim(str, chars) {
					return ltrim(rtrim(str, chars), chars);
				}
				
				function ltrim(str, chars) {
					chars = chars || "\\s";
					return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
				}
				
				function rtrim(str, chars) {
					chars = chars || "\\s";
					return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
				}
				</script>
				<form action="index.php" method="post" name="adminFormRS" id="adminFormRS" onsubmit="return submitFormRS();" enctype="multipart/form-data">
				<input type="hidden" name="option" id="option" value="<?php echo $_REQUEST['option']; ?>" />
				<input type="hidden" name="task" id="task" value="<?php echo $_REQUEST['task']; ?>" />
				<input type="hidden" name="action" id="action" value="save" />
				<input type="hidden" name="id" id="id" value="<?php echo $row->id; ?>" />
				<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST['page']; ?>" />
				<input type="hidden" name="limit" id="limit" value="<?php echo $_REQUEST['limit']; ?>" />
					<table class="admintable" style="width:50%;">
	<tr><td colspan="3" style="text-align:right;" class="key"><span style="color:red;">*</span> fields are mandatory.</td></tr>
	<?php if(fetchParam('show_single_name_field') == 'true') { ?>
    <tr><td class="key">Submitter Name:<span style="color:red;">*</span></td><td colspan="2"><input name="fname" id="fname" type="text" maxlength="50" style="width:100%;" value="<?php echo $row->fname; ?>" /></td></tr>
    <?php } else { ?>
    <tr><td class="key">Submitter First Name:<span style="color:red;">*</span></td><td colspan="2"><input name="fname" id="fname" type="text" maxlength="50" style="width:100%;" value="<?php echo $row->fname; ?>" /></td></tr>
	<tr><td class="key">Submitter Last Name:<span style="color:red;">*</span></td><td colspan="2"><input name="lname" id="lname" type="text" maxlength="50" style="width:100%;" value="<?php echo $row->lname; ?>" /></td></tr>
    <?php } ?>
	
    <tr><td class="key">Email Address:<span style="color:red;">*</span></td><td colspan="2"><input name="email" id="email" type="text" maxlength="100" style="width:100%;" value="<?php echo $row->email; ?>" /></td></tr>
	<?php if(fetchParam('show_about') == 'true') { ?>
    <tr><td class="key">About Submitter:</td><td colspan="2"><input name="about" id="about" type="text" style="width:100%;" value="<?php echo $row->about; ?>" /></td></tr>
    <?php } ?>
    <?php if(fetchParam('show_location') == 'true') { ?>
	<tr><td class="key">Submitter's Location:</td><td colspan="2"><input name="location" id="location" type="text" maxlength="255" style="width:100%;" value="<?php echo $row->location; ?>" /></td></tr>
	<?php } ?>
    <?php if(fetchParam('show_website') == 'true') { ?>
    <tr><td class="key">Submitter's Website:</td><td><input name="website" id="website" type="text" maxlength="255" style="width:100%;" value="<?php echo $row->website; ?>" /></td></tr>
    <?php } ?>
	<?php if(fetchParam('show_image') == 'true') { ?>
    <tr>
    	<td class="key" valign="top">Submitter's Image:</td>
        <td>
        <input type="file" name="testi_pic" id="testi_pic" />
        <div>Max allowed Width: <?php echo fetchParam('image_max_width'); ?> px.</div>
        <div>Max allowed Height: <?php echo fetchParam('image_max_height'); ?> px.</div>
        <div>Max allowed size: <?php echo fetchParam('image_max_size'); ?> kb.</div>
        <?php if($row->id > 0) {
			$testi_pic_file = '';
			if(file_exists('../images/com_rsmonials/'.$row->id.'.gif')) {
				$testi_pic_file = '../images/com_rsmonials/'.$row->id.'.gif';
			} else if(file_exists('../images/com_rsmonials/'.$row->id.'.png')) {
				$testi_pic_file = '../images/com_rsmonials/'.$row->id.'.png';
			} else if(file_exists('../images/com_rsmonials/'.$row->id.'.jpg')) {
				$testi_pic_file = '../images/com_rsmonials/'.$row->id.'.jpg';
			} else if(file_exists('../images/com_rsmonials/'.$row->id.'.jpeg')) {
				$testi_pic_file = '../images/com_rsmonials/'.$row->id.'.jpeg';
			}
			if($testi_pic_file != '') {
		?>
            <div><strong>Current Image:</strong></div>
            <div><img src="<?php echo $testi_pic_file; ?>" style="max-height:<?php echo fetchParam('image_max_height'); ?>px; max-width:<?php echo fetchParam('image_max_width'); ?>px;" /></div>
            <div><input type="checkbox" name="testi_pic_del" id="testi_pic_del" value="true" /> - Delete Current Image</div>
        <?php
        	}
		}
		?>
        </td>
    </tr>
    <?php } ?>
	<tr><td class="key">Date of Posting:<br />( yyyy-mm-dd )</td><td><input name="posting_date" id="posting_date" type="text" maxlength="255" style="width:100%;" value="<?php echo ($row->date ? $row->date : date('Y-m-d')); ?>" /></td></tr>
	<tr><td colspan="3" class="key" style="text-align:left;">Comments:<span style="color:red;">*</span></td></tr>
	<tr><td colspan="3"><textarea name="comments" id="comments" style="width:100%; height:150px;"><?php echo $row->comment; ?></textarea></td></tr>
	<tr><td colspan="3" style="text-align:center;"><input type="button" value="Submit Testimonial" class="button" style="width:auto;" onClick="return submitFormRS();" /> <input type="button" value="Cancel" class="button" style="width:auto;" onClick="return cancelFormRS();" /></td></tr>
	</table>
				</form>
</fieldset>
<?php
	###############
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsfooter.php");
	###############
}

function save() {
	/*foreach($_POST as $key=>$value) {
		$_POST[$key] = addslashes($value);
	}*/
	$database =& JFactory::getDBO();
	$postingArr = explode("-",$_POST['posting_date']);
	$mktime = mktime(12, 0, 0, $postingArr[1], $postingArr[2], $postingArr[0]);
	if(date('Y', $mktime) > 1979) {
		$date = date('Y-m-d', $mktime);
	}
	else {
		$date = date('Y-m-d');
	}
	if($_POST['id']>0) {
		$database->setQuery("update `#__".RSWEBSOLS_TABLE_PREFIX."` set `fname`='".$database->getEscaped($_POST['fname'])."', `lname`='".$database->getEscaped($_POST['lname'])."', `about`='".$database->getEscaped($_POST['about'])."', `location`='".$database->getEscaped($_POST['location'])."', `website`='".$database->getEscaped($_POST['website'])."', `email`='".$database->getEscaped($_POST['email'])."', `comment`='".$database->getEscaped($_POST['comments'])."', `date`='".$date."' where `id`='".$_POST['id']."'");
	}
	else {
		$database->setQuery("insert into `#__".RSWEBSOLS_TABLE_PREFIX."`(`id`, `fname`, `lname`, `about`, `location`, `website`, `email`, `comment`, `date`, `status`) values('', '".$database->getEscaped($_POST['fname'])."', '".$database->getEscaped($_POST['lname'])."', '".$database->getEscaped($_POST['about'])."', '".$database->getEscaped($_POST['location'])."', '".$database->getEscaped($_POST['website'])."', '".$database->getEscaped($_POST['email'])."', '".$database->getEscaped($_POST['comments'])."', '".$date."', '1')");
	}
	$database->query();
	
	if($_POST['id'] > 0) {
		$new_id = $_POST['id'];
	} else {
		$new_id = $database->insertid();
	}
	
	$img_ext = array('jpg', 'jpeg', 'gif', 'png');
	
	// Image Delete
	if($_POST['testi_pic_del'] == 'true') {
		$testi_pic_file = '';
		if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$_POST['id'].'.gif')) {
			$testi_pic_file = JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$_POST['id'].'.gif';
		} else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$_POST['id'].'.png')) {
			$testi_pic_file = JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$_POST['id'].'.png';
		} else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$_POST['id'].'.jpg')) {
			$testi_pic_file = JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$_POST['id'].'.jpg';
		} else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$_POST['id'].'.jpeg')) {
			$testi_pic_file = JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$_POST['id'].'.jpeg';
		}
		if($testi_pic_file != '') {
			unlink($testi_pic_file);
		}
	}
	
	// Image Upload
	if(is_uploaded_file($_FILES['testi_pic']['tmp_name'])) {
		$max_s = fetchParam('image_max_size');
		$max_h = fetchParam('image_max_height');
		$max_w = fetchParam('image_max_width');
		$err = '';
		$img_settings = getimagesize($_FILES['testi_pic']['tmp_name']);
		if(($img_settings[2] != 1) && ($img_settings[2] != 2) && ($img_settings[2] != 3)) {
			$err = 'IMAGE UPLOADING ERROR: File is not supported. Please upload jpg, gif or png file.';
		} else if($_FILES['testi_pic']['size'] > $max_s*1024) {
			$err = ' IMAGE UPLOADING ERROR: Image is larger than '.$max_s.' kb';
		} else if($img_settings[0] > $max_w) {
			$err = ' IMAGE UPLOADING ERROR: Image width is larger than '.$max_w.' px';
		} else if($img_settings[1] > $max_h) {
			$err = ' IMAGE UPLOADING ERROR: Image height is larger than '.$max_h.' px';
		} else {
			$upload_dir_path = JPATH_ROOT.DS.'images'.DS.'com_rsmonials';
			if(!file_exists($upload_dir_path)) {
				mkdir($upload_dir_path, 0755);	
			}
			$upload_path = $upload_dir_path.DS.$new_id.'.';
			if($img_settings[2] == 1) {
				$upload_path .= 'gif';
			} else if($img_settings[2] == 2) {
				$upload_path .= 'jpg';
			}else if($img_settings[2] == 3) {
				$upload_path .= 'png';
			}
			if(!move_uploaded_file($_FILES['testi_pic']['tmp_name'], $upload_path)) {
				$err = ' IMAGE UPLOADING ERROR: Failed to Upload Image.';
			}
		}
	}
	
	header("location:index.php?option=".$_REQUEST['option']."&task=".$_REQUEST['task']."&page=".$_REQUEST['page']."&limit=".$_REQUEST['limit']."&result=".base64_encode('Testimonial Successfully Saved.'.$err)."");
	exit();
}
?>