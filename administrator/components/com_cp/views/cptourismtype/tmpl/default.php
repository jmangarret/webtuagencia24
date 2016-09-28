<?php
defined('_JEXEC') or die('Restricted access');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

$text = empty($this->item->tourismtype_id) ? JText::_('COM_CP_NEW') : JText::_('COM_CP_EDIT');
$title = $text . ' ' . JText::_('COM_CP_TOURISMTYPE_PAGE_TITLE');
?>
<script type="text/javascript">
function getVarsUrl(){
    var url= location.search.replace("?", "");
    var arrUrl = url.split("&");
    var urlObj={};   
    for(var i=0; i<arrUrl.length; i++){
        var x= arrUrl[i].split("=");
        urlObj[x[0]]=x[1]
    }
    return urlObj;
}

jQuery(document).ready(function() {
	var vGet = getVarsUrl();
	if (vGet.category_id == undefined) {
		jQuery("#toolbar-apply").remove();
	}
});

control_save = false;
control_save2new = false;
Joomla.submitbutton = function(task) {
	if (task == 'cptourismtype.save') {
		if (control_save) {
			window.location = 'index.php?option=com_cp&view=cptourismtypelist';
			return false;
		}
		control_save = true;
	}

	if (task == 'cptourismtype.save2new') {
		if (control_save2new) {
			window.location = 'index.php?option=com_cp&view=cptourismtype&layout=edit';
			return false;
		}
		control_save2new = true;
	}

	if (task == 'cptourismtype.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		Joomla.submitform(task, document.getElementById('adminForm'));
	} else {
		alert('<?php echo $this->escape(JText::_('COM_CP_TOURISM_TYPE_MUST_HAVE_A_NAME')); ?>');
	}
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_cp&view=cptourismtype&tourismtype_id='.(int) $this->item->tourismtype_id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_CP_DETAILS'); ?></legend>
	<table style="border: none;">
		<tr>
			<td><?php echo $this->form->getLabel('tourismtype_name'); ?></td>
			<td><?php echo $this->form->getInput('tourismtype_name'); ?></td>
		</tr>
		<tr>
			<td><?php echo $this->form->getLabel('published'); ?></td>
			<td><?php echo $this->form->getInput('published'); ?></td>
		</tr>
	</table>
	<?php echo $this->form->getInput('tourismtype_id'); ?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
	<?php echo JHtml::_('form.token'); ?>
</fieldset>
</form>