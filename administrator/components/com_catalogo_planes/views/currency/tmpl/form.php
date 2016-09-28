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
		if (jQuery.trim(form.currency_name.value) == "") {
            form.currency_name.focus();
			alert("<?php echo JText::_('CP.CURRENCY_ERROR_EMPTY_NAME', true); ?>");
		} else if (jQuery.trim(form.currency_code.value) == "") {
            form.currency_code.focus();
			alert("<?php echo JText::_('CP.CURRENCY_ERROR_EMPTY_CODE', true); ?>");
		} else if (jQuery.trim(form.trm.value) == "") {
            form.trm.focus();
			alert("<?php echo JText::_('CP.CURRENCY_ERROR_EMPTY_TRM', true); ?>");
		} else if (form.approx.selectedIndex == 0) {
            form.approx.focus();
			alert("<?php echo JText::_('CP.CURRENCY_ERROR_EMPTY_APPROX', true); ?>");
		} else {

            // Verificar que la TRM sea válida (decimal con puntos, no comas)
            var reg = /[^0123456789\.]/g;
            var valor = jQuery.trim(jQuery("#trm").val());
            var dots = valor.match(/\./g);
            if (reg.test(valor) || (dots && dots.length > 1)) {
                jQuery("#trm").focus();
                alert("<?php echo JText::_('CP.CURRENCY_ERROR_INVALID_PRICE', true); ?>");
                return;
            }

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
					<label for="currency_name">
						<?php echo JText::_('CP.CURRENCY_NAME'); ?>: *
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="currency_name" id="currency_name" size="32" maxlength="100" value="<?php echo htmlspecialchars($data->currency_name, ENT_COMPAT, 'UTF-8'); ?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<label for="currency_code">
						<?php echo JText::_('CP.CURRENCY_CODE'); ?>: *
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="currency_code" id="currency_code" size="10" maxlength="10" value="<?php echo $data->currency_code; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<label for="trm">
						<?php echo JText::_('CP.CURRENCY_TRM'); ?>: *
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="trm" id="trm" size="10" maxlength="10" value="<?php echo $data->trm; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<label for="approx">
						<?php echo JText::_('CP.CURRENCY_APPROX'); ?>: *
					</label>
				</td>
				<td>
					<?php
					$listselect = array();
					$listselect[] = JHTML::_('select.option', '-1', JText::_('CP.SELECT'));
			
					for ($i = 0; $i < count($this->approxOptions); $i++) {
						$listselect[] = JHTML::_('select.option', $this->approxOptions[$i][0], $this->approxOptions[$i][1]);
					}
					echo JHTML::_('select.genericlist', $listselect, 'approx', 'class="inputbox" style="width:150px;" size="1"', 'value', 'text', $data->approx);
					?>
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
<input type="hidden" name="currency_id" value="<?php echo $data->currency_id; ?>" />
<input type="hidden" name="task" value="" />
</form>