<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
if (empty($this->data)) {
  $mainframe =& JFactory::getApplication();
  $mainframe->redirect('index.php', 'error', JText::_('CP.COMMENTS.RECORD.NOT.FOUND'));
}
$data = $this->data;
$greetings = sprintf(JText::_('CP.COMMENTS.FORM.GREETING'), $data->contact_name);
$document = JFactory::getDocument();
$document->addStyleDeclaration('div.rating-cancel {display: none !important;}');
?>
<script type="text/javascript">
<!--
jQuery.noConflict()(document).ready(function ($) {
    $("#send_comment").click(function() {
        if ($.trim($('#comment_text').val()) == '') {
            alert("<?php echo JText::_('CP.COMMENTS.FORM.COMMENTS.ERROR', true); ?>");
            return;
        }
        if ($('input[name="comment_rate"]:checked').length < 1) {
            alert("<?php echo JText::_('CP.COMMENTS.FORM.RATING.ERROR', true); ?>");
            return;
        }
        $("#commentForm").submit();
    });

    //Validación para que el textarea no pase de su longitud máxima
    $("#comment_text").live("keyup change", function() {
        var str = $(this).val();
        var mx = 400;
        if (str.length > mx) {
            $(this).val(str.substr(0, mx));
            return false;
        }
    });
});
//-->
</script>

<form action="index.php" method="post" name="commentForm"
	id="commentForm">
	<table style="border: 1px solid #CFCFCF; width: 100%" align="center">
		<tr>
			<td>
				<table width="525">
					<tr>
						<td align="justify" colspan="11"><?php echo $greetings; ?></td>
					</tr>
					<tr>
						<td width="30%"><?php echo JText::_('CP.COMMENTS.FORM.SERVICE.LABEL'); ?>:</td>
						<td colspan="10"><?php echo $data->product_name; ?></td>
					</tr>
					<tr>
						<td><?php echo JText::_('CP.COMMENTS.FORM.COMMENTS.LABEL'); ?>*:</td>
						<td colspan="10"><textarea rows="10" cols="40" name="comment_text"
								id="comment_text"></textarea></td>
					</tr>
					<tr>
						<td><?php echo JText::_('CP.COMMENTS.FORM.RATING.LABEL'); ?>*:</td>
						<td align="center"><input type="radio" value="1"
							name="comment_rate" class="comment_rate star" /></td>
						<td align="center"><input type="radio" value="2"
							name="comment_rate" class="comment_rate star" /></td>
						<td align="center"><input type="radio" value="3"
							name="comment_rate" class="comment_rate star" /></td>
						<td align="center"><input type="radio" value="4"
							name="comment_rate" class="comment_rate star" /></td>
						<td align="center"><input type="radio" value="5"
							name="comment_rate" class="comment_rate star" /></td>
						<td align="center"><input type="radio" value="6"
							name="comment_rate" class="comment_rate star" /></td>
						<td align="center"><input type="radio" value="7"
							name="comment_rate" class="comment_rate star" /></td>
						<td align="center"><input type="radio" value="8"
							name="comment_rate" class="comment_rate star" /></td>
						<td align="center"><input type="radio" value="9"
							name="comment_rate" class="comment_rate star" /></td>
						<td align="center"><input type="radio" value="10"
							name="comment_rate" class="comment_rate star" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="center">1</td>
						<td align="center">2</td>
						<td align="center">3</td>
						<td align="center">4</td>
						<td align="center">5</td>
						<td align="center">6</td>
						<td align="center">7</td>
						<td align="center">8</td>
						<td align="center">9</td>
						<td align="center">10</td>
					</tr>
					<tr>
						<td colspan="11" align="right"><a id="send_comment"
							class="button_next" href="javascript: void(0)"><?php echo JText::_('CP.COMMENTS.FORM.SEND.BUTTON.LABEL'); ?>
						</a></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<input type="hidden" name="view" value="<?php echo $this->viewName; ?>" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" /> <input
		type="hidden" name="id" value="<?php echo $data->comment_id; ?>" /> <input
		type="hidden" name="key" value="<?php echo $this->key; ?>" /> <input
		type="hidden" name="layout" value="save" />
</form>
