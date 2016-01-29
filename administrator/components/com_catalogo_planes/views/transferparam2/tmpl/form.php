<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
$data =& $this->data;
jimport('joomla.html.pane');
$myTabs = & JPane::getInstance('tabs');
?>
<script type="text/javascript">

Joomla.submitbutton = function(pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

        // do field validation
        if (jQuery.trim(form.param2_name.value) == "") {
            alert("<?php echo JText::_('CP.TRANSFERPARAM2_ERROR_EMPTY_NAME', true); ?>");
        } else if (parseInt(form.capacity.value, 10) < 1) {
            alert("<?php echo JText::_('CP.TRANSFERPARAM2_ERROR_EMPTY_LOWER_LIMIT', true); ?>");
        } else if (parseInt(form.value.value, 10) < 1) {
            alert("<?php echo JText::_('CP.TRANSFERPARAM2_ERROR_EMPTY_UPPER_LIMIT', true); ?>");
        } else if (parseInt(form.capacity.value, 10) > parseInt(form.value.value, 10)) {
            alert("<?php echo JText::_('CP.TRANSFERPARAM2_ERROR_INVALID_LIMITS', true); ?>");
        } else {
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
    <?php if ($data->param2_id > 0 && !$this->editable): ?>
        <div id="system-message"><div class="notice"><ul><li><?php echo JText::_('CP.TRANSFERPARAM2_ERROR_EDIT_EXISTS_RELATED_PRODUCTS'); ?></li></ul></div></div><br />
    <?php endif; ?>
		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<label for="param2_name">
						<?php echo JText::_('CP.TRANSFERPARAM2_NAME'); ?>: *
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="param2_name" id="param2_name" size="32" maxlength="100" value="<?php echo htmlspecialchars($data->param2_name, ENT_COMPAT, 'UTF-8'); ?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<label for="capacity">
						<?php echo JText::_('CP.TRANSFERPARAM2_LOWER_LIMIT'); ?>: *
					</label>
				</td>
				<td>
                    <?php
                    $listselect = array();
                    $listselect[] = JHTML::_('select.option', '-1', JText::_('CP.SELECT'));

                    for ($i = 1; $i < 51; $i++) {
                        $listselect[] = JHTML::_('select.option', $i, $i);
                    }
                    echo JHTML::_('select.genericlist', $listselect, 'capacity', 'class="inputbox" size="1"', 'value', 'text', $data->capacity);
                    ?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<label for="value">
						<?php echo JText::_('CP.TRANSFERPARAM2_UPPER_LIMIT'); ?>: *
					</label>
				</td>
				<td>
                    <?php
                    $listselect = array();
                    $listselect[] = JHTML::_('select.option', '-1', JText::_('CP.SELECT'));

                    for ($i = 1; $i < 51; $i++) {
                        $listselect[] = JHTML::_('select.option', $i, $i);
                    }
                    echo JHTML::_('select.genericlist', $listselect, 'value', 'class="inputbox" size="1"', 'value', 'text', $data->value);
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
<?php
echo $myTabs->endPanel();

// Mostrar listado de productos relacionados si es un registro ya creado
if ($data->param2_id > 0) {
    echo $myTabs->startPanel(JText::_('CP.ROW_RELATED_PRODUCTS'), 'relatedProducts');
    if (empty($this->productList)) {
        echo JText::_('CP.ROW_RELATED_PRODUCTS_EMPTY');
    } else {
        $content = '';
        $i = 1;
        foreach ($this->productList as $row) {
            $content .= '<div class="relatedrows">' . $i . '. <a href="index.php?option=' . $option . '&view=transfers' . 
                '&task=edit&cid[]=' . $row->product_id . '">' . $row->product_name . '</a></div>';
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
<input type="hidden" name="param2_id" value="<?php echo $data->param2_id; ?>" />
<input type="hidden" name="task" value="" />
</form>