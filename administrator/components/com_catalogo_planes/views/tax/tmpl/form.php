<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
$data =& $this->data;
jimport('joomla.html.pane');
$myTabs = & JPane::getInstance('tabs');
?>
<script type="text/javascript">

	function submitbutton(pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		// do field validation
		if (jQuery.trim(form.tax_name.value) == "") {
			form.tax_name.focus();
			alert("<?php echo JText::_('CP.TAX_ERROR_EMPTY_NAME', true); ?>");
		} else if (jQuery.trim(form.tax_code.value) == "") {
            form.tax_code.focus();
			alert("<?php echo JText::_('CP.TAX_ERROR_EMPTY_CODE', true); ?>");
		} else if (jQuery.trim(form.tax_value.value) == "") {
            form.tax_value.focus();
			alert("<?php echo JText::_('CP.TAX_ERROR_EMPTY_VALUE', true); ?>");
		} else {
			// validar que se de un porcentaje numérico (decimal con puntos, no comas)
            var reg = /[^0123456789\.]/g;
            dots = form.tax_value.value.match(/\./g);
            if (reg.test(form.tax_value.value) || (dots && dots.length > 1)) {
                form.tax_value.focus();
                alert("<?php echo JText::_('CP.TAX_ERROR_INVALID_VALUE', true); ?>");
                return;
            }

			// validar que al menos seleccione un tipo de producto
            var submit = false;
            for (var i = 0; i < document.forms['adminForm']['producttypes[]'].length; i++) {
                if (document.forms['adminForm']['producttypes[]'][i].checked) {
                    submit = true;
                    break;
                }
            }
            if (!submit) {
                alert("<?php echo JText::_('CP.TAX_ERROR_EMPTY_PRODUCT_TYPE', true); ?>");
                return;
            }
			submitform(pressbutton);
		}
	}

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
<?php
echo $myTabs->startPane('rowpane');
echo $myTabs->startPanel(JText::_('CP.DETAILS'), 'details');
?>
	<table class="admintable">
		<tr>
			<td align="right" class="key">
				<label for="tax_name">
					<?php echo JText::_('CP.TAX_NAME'); ?>: *
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="tax_name" id="tax_name" size="32" maxlength="100" value="<?php echo htmlspecialchars($data->tax_name, ENT_COMPAT, 'UTF-8'); ?>" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="tax_code">
					<?php echo JText::_('CP.TAX_CODE'); ?>: *
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="tax_code" id="tax_code" size="10" maxlength="10" value="<?php echo $data->tax_code; ?>" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="tax_value">
					<?php echo JText::_('CP.TAX_VALUE'); ?>: *
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="tax_value" id="tax_value" size="10" maxlength="10" value="<?php echo $data->tax_value; ?>" />&nbsp;%
			</td>
		</tr>
            <tr>
                <td align="right" class="key">
                    <label for="producttypes">
                        <?php echo JText::_('CP.TAX_PRODUCT_TYPES'); ?>: *
                    </label>
                </td>
                <td>
                    <?php
                    // Mostrar los tipos de producto a los que aplica
                    $selectedProductTypes = array();
                    if (is_array($data->producttypes)) {
                        foreach ($data->producttypes as $type) {
                            $selectedProductTypes[] = $type;
                        }
                    }
                    if (is_array($this->productTypes)) {
                        foreach ($this->productTypes as $type) {
                            if (!$data->tax_id || in_array($type->product_type_id, $selectedProductTypes)) {
                                $checked = ' checked';
                            } else {
                                $checked = '';
                            }
                            echo '<input type="checkbox" name="producttypes[]" value="' . $type->product_type_id . '"' .  $checked . ' />' .  $type->product_type_name . '<br />';
                        }
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="published">
                        <?php echo JText::_('CP.TAX_IVA'); ?>:
                    </label>
                </td>
                <td>
                    <?php echo JHTML::_('select.booleanlist', 'iva', null, $data->iva, JText::_('CP.JYES'), JText::_('CP.JNO'), false); ?>
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
<?php
echo $myTabs->endPanel();

// Mostrar listado de productos y suplementos relacionados si es un registro ya creado
if ($data->tax_id > 0) {
    echo $myTabs->startPanel(JText::_('CP.ROW_RELATED_PRODUCTS'), 'relatedProducts');
    if (empty($this->productList)) {
        echo JText::_('CP.ROW_RELATED_PRODUCTS_EMPTY');
    } else {
    	$content = '';
        $i = 1;
        foreach ($this->productList as $row) {
            $content .= '<div class="relatedrows">' . $i . '. <a href="index.php?option=' . $option . '&view=' . $row->product_type_code . 
                '&task=edit&cid[]=' . $row->product_id . '">' . $row->product_type_name . ' - ' . $row->product_name . '</a></div>';
            $i++;
        }
        echo $content;
    }
    echo $myTabs->endPanel();
}
echo $myTabs->endPane();
?>
</div>
<div class="clr"></div>

<input type="hidden" name="view" value="<?php echo $this->viewName; ?>" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="tax_id" value="<?php echo $data->tax_id; ?>" />
<input type="hidden" name="task" value="" />
</form>