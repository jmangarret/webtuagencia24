<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
$data =& $this->data;
jimport('joomla.html.pane');
$myTabs = & JPane::getInstance('tabs');
?>
<script type="text/javascript">
    jQuery.noConflict()(document).ready(function ($) {
        $('#country_id').change(function () {
            var url = "index.php?option=<?php echo $option; ?>&view=city&task=getrawlist&format=raw&country_id=" + $(this).val();
            generateSelectByAjax(url, 'city_id', 'city_id', 'city_name', '', ' - <?php echo JText::_('NONE'); ?> - ');
        });
        $("#phone").keypress(function(event) {
            return ((event.which > 47 && event.which < 58) || event.which == 8 || event.keyCode == 46);
        });
        $("#fax").keypress(function(event) {
            return ((event.which > 47 && event.which < 58) || event.which == 8 || event.keyCode == 46);
        });
    });

    function submitbutton(pressbutton) {
        var form = document.adminForm;

        if (pressbutton == 'cancel') {
            submitform(pressbutton);
            return;
        }

        // do field validation
        if (jQuery.trim(form.supplier_name.value) == "") {
            alert("<?php echo JText::_('CP.SUPPLIER_ERROR_EMPTY_NAME', true); ?>");
        } else if (jQuery.trim(form.supplier_code.value) == "") {
            alert("<?php echo JText::_('CP.SUPPLIER_ERROR_EMPTY_CODE', true); ?>");
        } else if (jQuery.trim(form.phone.value) != "" && !(/\d/.test(form.phone.value))) {
            alert("<?php echo JText::_('CP.SUPPLIER_ERROR_INVALID_PHONE', true); ?>");
        } else if (jQuery.trim(form.fax.value) != "" && !(/\d/.test(form.fax.value))) {
            alert("<?php echo JText::_('CP.SUPPLIER_ERROR_INVALID_FAX', true); ?>");
        } else {
            // validar que si se dio un email, sea válido
            if (form.email.value != "" && !isUserEmail(form.email.value)) {
                alert("<?php echo JText::_('CP.SUPPLIER_ERROR_INVALID_EMAIL', true); ?>");
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
                alert("<?php echo JText::_('CP.SUPPLIER_ERROR_EMPTY_PRODUCT_TYPE', true); ?>");
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
                    <label for="supplier_name">
                        <?php echo JText::_('CP.SUPPLIER_NAME'); ?>: *
                    </label>
                </td>
                <td>
                    <input class="text_area" type="text" name="supplier_name" id="supplier_name" size="32" maxlength="100" value="<?php echo htmlspecialchars($data->supplier_name, ENT_COMPAT, 'UTF-8'); ?>" />
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="supplier_code">
                        <?php echo JText::_('CP.SUPPLIER_CODE'); ?>: *
                    </label>
                </td>
                <td>
                    <input class="text_area" type="text" name="supplier_code" id="supplier_code" size="10" maxlength="5" value="<?php echo $data->supplier_code; ?>" />
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="country_id">
                        <?php echo JText::_('CP.COUNTRY'); ?>:
                    </label>
                </td>
                <td>
                    <?php echo $data->countries; ?>
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="city_id">
                        <?php echo JText::_('CP.CITY'); ?>:
                    </label>
                </td>
                <td>
                    <?php echo $data->cities; ?>
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="phone">
                        <?php echo JText::_('CP.PHONE'); ?>:
                    </label>
                </td>
                <td>
                    <input class="text_area" type="text" name="phone" id="phone" size="15" value="<?php echo $data->phone; ?>" maxlength="15" />
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="fax">
                        <?php echo JText::_('CP.FAX'); ?>:
                    </label>
                </td>
                <td>
                    <input class="text_area" type="text" name="fax" id="fax" size="15" value="<?php echo $data->fax; ?>" maxlength="15" />
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="url">
                        <?php echo JText::_('CP.SUPPLIER_URL'); ?>:
                    </label>
                </td>
                <td>
                    <input class="text_area" type="text" name="url" id="url" size="32" value="<?php echo $data->url; ?>" maxlength="100" />
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="email">
                        <?php echo JText::_('CP.SUPPLIER_EMAIL'); ?>:
                    </label>
                </td>
                <td>
                    <input class="text_area" type="text" name="email" id="email" size="32" value="<?php echo $data->email; ?>" maxlength="100" />
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="producttypes">
                        <?php echo JText::_('CP.SUPPLIER_PRODUCT_TYPES'); ?>: *
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
                            if (!$data->supplier_id || in_array($type->product_type_id, $selectedProductTypes)) {
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
if ($data->supplier_id > 0) {
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
<input type="hidden" name="supplier_id" value="<?php echo $data->supplier_id; ?>" />
<input type="hidden" name="task" value="" />
</form>