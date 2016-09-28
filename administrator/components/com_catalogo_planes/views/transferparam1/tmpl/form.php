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
        if (jQuery.trim(form.param1_name.value) == "") {
                alert("<?php echo JText::_('CP.TRANSFERPARAM1_ERROR_EMPTY_NAME', true); ?>");
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
<table class="admintable">
        <tr>
                <td align="right" class="key">
                        <label for="param1_name">
                                <?php echo JText::_('CP.TRANSFERPARAM1_NAME'); ?>: *
                        </label>
                </td>
                <td>
                        <input class="text_area" type="text" name="param1_name" id="param1_name" size="32" maxlength="100" value="<?php echo htmlspecialchars($data->param1_name, ENT_COMPAT, 'UTF-8'); ?>" />
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
if ($data->param1_id > 0) {
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
<input type="hidden" name="param1_id" value="<?php echo $data->param1_id; ?>" />
<input type="hidden" name="task" value="" />
</form>