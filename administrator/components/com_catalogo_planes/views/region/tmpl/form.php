<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
$data =& $this->data;

?>
<script type="text/javascript">

    function submitbutton(pressbutton) {
        var form = document.adminForm;

        if (pressbutton == 'cancel') {
                submitform(pressbutton);
                return;
        }

        // do field validation
        if (jQuery.trim(form.region_name.value) == "") {
                alert("<?php echo JText::_('CP.REGION_ERROR_EMPTY_NAME', true); ?>");
        } else if (form.country_id.selectedIndex == 0) {
                alert("<?php echo JText::_('CP.REGION_ERROR_EMPTY_COUNTRY', true); ?>");
        } else if (jQuery.trim(form.region_code.value) == "") {
                alert("<?php echo JText::_('CP.REGION_ERROR_EMPTY_CODE', true); ?>");
        } else {
                submitform(pressbutton);
        }
    }

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_('CP.DETAILS'); ?></legend>
		<table class="admintable">
            <tr>
                <td align="right" class="key">
                    <label for="country_id">
                        <?php echo JText::_('CP.COUNTRY'); ?>: *
                    </label>
                </td>
                <td>
                    <?php echo $data->countries; ?>
                </td>
            </tr>
			<tr>
				<td align="right" class="key">
					<label for="region_name">
						<?php echo JText::_('CP.REGION_NAME'); ?>: *
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="region_name" id="region_name" size="32" maxlength="100" value="<?php echo htmlspecialchars($data->region_name, ENT_COMPAT, 'UTF-8'); ?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<label for="region_code">
						<?php echo JText::_('CP.REGION_CODE'); ?>: *
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="region_code" id="region_code" size="10" maxlength="5" value="<?php echo $data->region_code; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<label for="published">
						<?php echo JText::_('CP.STATE'); ?>: *
					</label>
				</td>
				<td>
					<?php echo JHTML::_('select.booleanlist', 'published', null, $data->published, JText::_('CP.ACTIVE'), JText::_('CP.INACTIVE'), false); ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="view" value="<?php echo $this->viewName; ?>" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="region_id" value="<?php echo $data->region_id; ?>" />
<input type="hidden" name="task" value="" />
</form>