<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
$data =& $this->data;
$tmpl = strtolower(JRequest::getVar('tmpl'));
jimport('joomla.html.pane');
$myTabs = & JPane::getInstance('tabs');
?>
<script type="text/javascript">
    jQuery.noConflict()(document).ready(function($) {
        // Prevenir el envío del formulario al presionar enter
    	$("form").bind("keypress", function(e) {
            if (e.keyCode == 13) return false;
        });

    	//Maintain array of dates
        var selectedDates = new Array();
        var today = new Date();
        today.setDate((today.getDate() - 1));
        <?php
        // Mostrar las fechas ya seleccionadas
        $result = "";
        if (is_array($data->dates) && count($data->dates) > 0) {
            foreach ($data->dates as $date) {
                $result .= "selectedDates.push(\"" . $date->start_date . "\");\n";
            }
        }
        echo $result;
        ?>

        function addDate(date) {if (jQuery.inArray(date, selectedDates) < 0) selectedDates.push(date);}
        function removeDate(index) {selectedDates.splice(index, 1);}

        // Adds a date if we don't have it yet, else remove it
        function addOrRemoveDate(date) {
          var index = jQuery.inArray(date, selectedDates); 
          if (index >= 0) {
            removeDate(index);
            return false;
          } else {
            addDate(date);
            return true;
          }
        }

        <?php
        $lang =& JFactory::getLanguage();
        $langTag = substr($lang->getTag(), 0, 2);
        if ($langTag != 'en') {
            echo 'if ($.datepicker.regional["' . $langTag . '"] != undefined) { $.datepicker.setDefaults($.datepicker.regional["' . $langTag . '"]); }';
        }
        ?>

        // Opción de seleccionar todos
        $("#season_dates").datepicker({
            minDate: (selectedDates[0] != undefined && ((new Date(selectedDates[0])) < today))? selectedDates[0]: '+0',
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            stepMonths: 2,
            defaultDate: (selectedDates[0] != undefined)? selectedDates[0]: null,
            changeMonth: true,
            numberOfMonths: 2,
            showButtonPanel: true,
            onSelect: function(dateText, inst) {
                if (addOrRemoveDate(dateText)) {
                    var field = "<input type=\"hidden\" name=\"dates[" + selectedDates.length + "][start_date]\" value=\"" + dateText + "\" />";
                    field = field + "<input type=\"hidden\" name=\"dates[" + selectedDates.length + "][end_date]\" value=\"" + dateText + "\" />";
                    $(this).closest("table").after(field);
                } else {
                    $('input[value="' + dateText + '"]').remove();
                }
                $("#totalDates").val(selectedDates.length);
            },
            beforeShowDay: function (date) {
                var selectedDate = $.datepicker.formatDate($(this).datepicker('option', 'dateFormat'), date);
                var gotDate = $.inArray(selectedDate, selectedDates);
                if (gotDate >= 0) {
                    <?php if ($this->editable): ?>
                    var inst = $(this).data("datepicker");
                    inst.selectedDay = inst.currentDay = date.getDate();
                    inst.selectedMonth = inst.currentMonth = date.getMonth();
                    inst.selectedYear = inst.currentYear = date.getFullYear();
                    // Enable date so it can be deselected. Set style to be highlighted
                    if (date < today) {
                        var editable = false;
                        var className = "unselectedcalendarcell";
                    } else {
                        var editable = true;
                        var className = "selectedcalendarcell";
                    }
                    return [editable, className];
                    <?php else: ?>
                    return [false, "unselectedcalendarcell"];
                    <?php endif; ?>
	            } else if (date < today) {
                    return [false, ""];
	            }
                // Dates not in the array are left enabled, but with no extra style
                 <?php if ($this->editable): ?>
                 return [true, "unselectedcalendarcell"];
                 <?php else: ?>
                 return [false, ""];
                 <?php endif; ?>
            }
        });
    });

    // Validación del formulario
    Joomla.submitbutton = function(pressbutton) {
        var form = document.adminForm;

        if (pressbutton == 'cancel') {
            submitform(pressbutton);
            return;
        }

        // do field validation
        if (jQuery.trim(form.season_name.value) == "") {
            alert("<?php echo JText::_('CP.SEASON_ERROR_EMPTY_NAME', true); ?>");
        } else {
            if (parseInt(form.totalDates.value, 10) < 1) {
                alert("<?php echo JText::_('CP.SEASON_SPECIAL_ERROR_EMPTY_DATES', true); ?>");
                return;
            }

            // validar que al menos seleccione un tipo de producto
            var submit = false;
            for (var i = 0; i < form['producttypes[]'].length; i++) {
                if (form['producttypes[]'][i].checked) {
                    submit = true;
                    break;
                }
            }
            if (!submit) {
                alert("<?php echo JText::_('CP.SEASON_ERROR_EMPTY_PRODUCT_TYPE', true); ?>");
                return;
            }

            <?php if ($tmpl == 'component'): ?>
            return true;
            <?php else: ?>
            submitform(pressbutton);
            <?php endif; ?>
        }
    }

    <?php if ($tmpl == 'component'): ?>
    function saveData () {
        document.adminForm.task.value = "apply";
        if (!submitbutton("apply")) {
            return;
        }
        jQuery("#adminForm input:button").attr("disabled", "disabled");
        jQuery.post("<?php echo JURI::base(true); ?>/index.php", jQuery("#adminForm").serialize(), function (data, textStatus, jqXHR) {
            if (data.result == 'message') {
                window.parent.addSeason(data.season_id);
                parent.SqueezeBox.close();
            } else {
                jQuery("#errorMessage").html(data.message);
                jQuery("#adminForm input:button").removeAttr("disabled");
            }
        }, 'json');
    }
    <?php endif; ?>

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
<?php if ($tmpl == 'component'): ?>
    <div id="errorMessage"></div>
    <fieldset class="adminform">
        <legend><?php echo JText::_('CP.SPECIAL_SEASON'); ?></legend>
        <input type="hidden" name="format" value="raw">
        <div style="float: right">
            <input type="button" onclick="saveData();" value="<?php echo JText::_('SAVE') ?>" />
            <input type="button" onclick="parent.SqueezeBox.close();" value="<?php echo JText::_('CANCEL') ?>" />
        </div>
    </fieldset>
<?php endif; ?>
<?php
echo $myTabs->startPane('rowpane');
echo $myTabs->startPanel(JText::_('CP.DETAILS'), 'details');
?>
    <?php if ($data->season_id > 0 && !$this->editable): ?>
        <div id="system-message"><div class="notice"><ul><li><?php echo JText::_('CP.SEASON_ERROR_EDIT_EXISTS_RELATED_RATES'); ?></li></ul></div></div><br />
    <?php endif; ?>
        <table class="admintable" width="100%" border="0">
            <tr>
                <td align="right" class="key" valign="top">
                    <label for="season_name">
                        <?php echo JText::_('CP.SEASON_NAME'); ?>: *
                    </label>
                </td>
                <td valign="top">
			    <?php if ($this->editable): ?>
			        <input class="text_area" type="text" name="season_name" id="season_name" size="50" maxlength="100" value="<?php echo htmlspecialchars($data->season_name, ENT_COMPAT, 'UTF-8'); ?>" />
			    <?php else: ?>
    			    <input class="text_area" type="text" name="season_name" id="season_name" size="50" maxlength="100" value="<?php echo htmlspecialchars($data->season_name, ENT_COMPAT, 'UTF-8'); ?>" disabled="disabled" />
			    <?php endif; ?>
                    
                </td>
                <td rowspan="6" valign="top">
                    <div id="season_dates"></div>
                    <br />
                    <table class="ui-widget-content">
                        <thead>
                            <tr><th colspan="2"><?php echo JText::_('CP.SEASON_SPECIAL_CALENDAR_DESCRIPTION_TITLE'); ?></th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ui-datepicker-current-day ui-datepicker-today"><a href="#" class="ui-state-highlight">XX</a></td>
                                <td><?php echo JText::_('CP.SEASON_SPECIAL_CALENDAR_DESCRIPTION_CURRENT_DATE'); ?></td>
                            </tr>
                            <tr>
                                <td><a href="#" class="ui-state-default">XX</a></td>
                                <td><?php echo JText::_('CP.SEASON_SPECIAL_CALENDAR_DESCRIPTION_ACTIVE_DATE'); ?></td>
                            </tr>
                            <tr>
                                <td class="selectedcalendarcell"><a href="#" class="ui-state-default">XX</a></td>
                                <td><?php echo JText::_('CP.SEASON_SPECIAL_CALENDAR_DESCRIPTION_SELECTED_DATE'); ?></td>
                            </tr>
                            <tr>
                                <td class="ui-datepicker-unselectable ui-state-disabled"><a href="#" class="ui-state-default">XX</a></td>
                                <td><?php echo JText::_('CP.SEASON_SPECIAL_CALENDAR_DESCRIPTION_DISABLED_DATE'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="right" class="key" valign="top">
                    <label for="producttypes">
                        <?php echo JText::_('CP.SEASON_PRODUCT_TYPES'); ?>: *
                    </label>
                </td>
                <td valign="top">
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
                            if (!$data->season_id || in_array($type->product_type_id, $selectedProductTypes)) {
                                $checked = ' checked';
                            } else {
                                $checked = '';
                            }
                            if (!$this->editable) {
                                $checked .= ' disabled="disabled"';
                            }
                            echo '<input type="checkbox" name="producttypes[]" value="' . $type->product_type_id . '"' .  $checked . ' />' .  $type->product_type_name . '<br />';
                        }
                    }
                    ?>
                </td>
            </tr>
            <?php if ($data->creator): ?>
            <tr>
                <td align="right" class="key" valign="top">
                    <label>
                        <?php echo JText::_('CP.ROW_CREATED_BY'); ?>:
                    </label>
                </td>
                <td valign="top">
                    <?php echo $data->creator; ?>
                </td>
            </tr>
            <?php endif; ?>
            <?php if ($data->created): ?>
            <tr>
                <td align="right" class="key" valign="top">
                    <label>
                        <?php echo JText::_('CP.ROW_CREATION_DATE'); ?>:
                    </label>
                </td>
                <td valign="top">
                    <?php echo JHTML::_('date', $data->created, 'd/m/Y H:i'); ?>
                </td>
            </tr>
            <?php endif; ?>
            <?php if ($data->editor): ?>
            <tr>
                <td align="right" class="key" valign="top">
                    <label>
                        <?php echo JText::_('CP.ROW_MODIFIED_BY'); ?>:
                    </label>
                </td>
                <td valign="top">
                    <?php echo $data->editor; ?>
                </td>
            </tr>
            <?php endif; ?>
            <?php if ($data->modified): ?>
            <tr>
                <td align="right" class="key" valign="top">
                    <label>
                        <?php echo JText::_('CP.ROW_MODIFIED_DATE'); ?>:
                    </label>
                </td>
                <td valign="top">
                    <?php echo JHTML::_('date', $data->modified, 'd/m/Y H:i'); ?>
                </td>
            </tr>
            <?php endif; ?>
        </table>
        <?php
        // Mostrar las fechas ya seleccionadas
        $contDates = 1;
        $result = "";
        if (is_array($data->dates) && count($data->dates) > 0) {
            foreach ($data->dates as $date) {
                $result .= "<input type=\"hidden\" name=\"dates[" . $contDates . "][start_date]\" value=\"" . $date->start_date . "\" />\n";
                $result .= "<input type=\"hidden\" name=\"dates[" . $contDates . "][end_date]\" value=\"" . $date->end_date . "\" />\n";
                $contDates++;
            }
        }
        // Los días se guardan activos
        for ($i = 1; $i < 8; $i++) {
        	$result .= "<input type=\"hidden\" name=\"day" . $i . "\" value=\"1\" />\n";
            $contDates++;
        }
        echo $result;
        ?>
        <div class="clr"></div>
<?php
echo $myTabs->endPanel();

// Mostrar listado de productos relacionados si es un registro ya creado
if ($data->season_id > 0) {
    echo $myTabs->startPanel(JText::_('CP.ROW_RELATED_PRODUCTS'), 'relatedProducts');
    if (empty($this->productList)) {
        echo JText::_('CP.ROW_RELATED_PRODUCTS_EMPTY');
    } else {
        $content = '';
        $i = 1;
        foreach ($this->productList as $row) {
            $content .= '<div class="relatedrows">' . $i . '. <a href="index.php?option=' . $option . '&view=' . $row->product_type_code . 
                'rate&product_id=' . $row->product_id . '">' . $row->product_type_name . ' - ' . $row->product_name . '</a></div>';
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
<input type="hidden" name="totalDates" id="totalDates" value="<?php echo count($data->dates); ?>" />
<input type="hidden" name="view" value="<?php echo $this->viewName; ?>" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="season_id" value="<?php echo $data->season_id; ?>" />
<input type="hidden" name="is_special" value="1" />
<input type="hidden" name="task" value="" />
</form>