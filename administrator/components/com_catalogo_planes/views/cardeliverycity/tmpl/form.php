<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
$data =& $this->data;

?>
<script type="text/javascript">

Joomla.submitbutton = function(pressbutton) {
        var form = document.adminForm;
    
        if (pressbutton == 'cancel') {
            submitform(pressbutton);
            return;
        }

        // do field validation
        if (jQuery.trim(form.city_name.value) == "") {
            form.city_name.focus();
            alert("<?php echo JText::_('CP.CARDELIVERYCITY_ERROR_EMPTY_NAME', true); ?>");
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
                    <label for="city_name">
                        <?php echo JText::_('CP.CARDELIVERYCITY_NAME'); ?>: *
                    </label>
                </td>
                <td>
                    <input class="text_area" type="text" name="city_name" id="city_name" size="32" maxlength="100" value="<?php echo htmlspecialchars($data->city_name, ENT_COMPAT, 'UTF-8'); ?>" />
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
<input type="hidden" name="city_id" value="<?php echo $data->city_id; ?>" />
<input type="hidden" name="task" value="" />
</form>