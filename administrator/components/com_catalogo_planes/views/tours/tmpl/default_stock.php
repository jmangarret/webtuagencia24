<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
$data =& $this->data;

jimport('joomla.html.html');
jimport('joomla.html.pane');
JHTML::_('behavior.calendar');

$myTabs = & JPane::getInstance ('tabs');
?>
<script type="text/javascript">
    jQuery.noConflict()(document).ready(function ($) {

    <?php
    $stock_lower_limit = intval($this->params->get('cfg_stock_lower_limit'));
    $stock_upper_limit = intval($this->params->get('cfg_stock_upper_limit'));
    if ($stock_lower_limit && $stock_upper_limit) {
        $stock_red_alert_color = $this->params->get('cfg_stock_red_alert_color');
        $stock_green_alert_color = $this->params->get('cfg_stock_green_alert_color');
        $stock_yellow_alert_color = $this->params->get('cfg_stock_yellow_alert_color');
        if ($stock_green_alert_color && $stock_red_alert_color && $stock_yellow_alert_color) {
        	echo "var stock_lower_limit = $stock_lower_limit;\n";
        	echo "var stock_upper_limit = $stock_upper_limit;\n";
        	echo "var stock_red_alert_color = '$stock_red_alert_color';\n";
        	echo "var stock_green_alert_color = '$stock_green_alert_color';\n";
        	echo "var stock_yellow_alert_color = '$stock_yellow_alert_color';\n";
    ?>
    	function changeColorInput (field, value) {
            var exp = /[^0123456789]/g;
            // Valor entre 0 y límite 1
            if (exp.test(value) || value < stock_lower_limit) {
                field.css('background-color', stock_red_alert_color);
            } else if (value >= stock_lower_limit && value < stock_upper_limit) {
            	field.css('background-color', stock_yellow_alert_color);
            } else if (value >= stock_upper_limit) {
                field.css('background-color', stock_green_alert_color);
            }
        }

    	$("input.stock_input:enabled").each(function () {
        	var value = parseInt($(this).val(), 10);
        	changeColorInput($(this), value);
    	});
    	$("input.stock_input:enabled").keyup(function () {
            var value = parseInt($(this).val(), 10);
            changeColorInput($(this), value);
    	});
    <?php
        }
    }
    $lang =& JFactory::getLanguage();
    $langTag = substr($lang->getTag(), 0, 2);
    if ($langTag != 'en') {
    	echo 'if ($.datepicker.regional["' . $langTag . '"] != undefined) { $.datepicker.setDefaults($.datepicker.regional["' . $langTag . '"]); }';
    }
    ?>

        // Calendario de la izquierda
        $("#manual_start_date").datepicker({
            showOn: "button",
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true,
            defaultDate: "+0",
            numberOfMonths: 2,
            showButtonPanel: true,
            buttonImageOnly: true,
            buttonImage: "templates/system/images/calendar.png",
            onSelect: function (selectedDate, inst) {
                var field = $("#manual_end_date");
                var currentDate = $(this).datepicker("getDate");
                if (field && currentDate) {
                    field.datepicker('option', 'minDate', currentDate);
                    var limitDate = new Date(currentDate.getTime());
                    limitDate.setDate(limitDate.getDate() + 30);
                    field.datepicker('option', 'maxDate', limitDate);
                }
            }
        });

        // Calendario derecho
        $("#manual_end_date").datepicker({
            showOn: "button",
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true,
            defaultDate: "+30",
            numberOfMonths: 2,
            showButtonPanel: true,
            buttonImageOnly: true,
            buttonImage: "templates/system/images/calendar.png",
            beforeShow: function (input, inst) {
                var currentDate = $("#manual_start_date").datepicker("getDate");
                if (currentDate) {
                	inst.settings.minDate = currentDate;
                    var limitDate = new Date(currentDate.getTime());
                    limitDate.setDate(limitDate.getDate() + 30);
                    inst.settings.maxDate = limitDate;
                }
            }
        });

        // Calendario de la izquierda
        $("#auto_start_date").datepicker({
            showOn: "button",
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true,
            minDate: "+0",
            numberOfMonths: 2,
            buttonImageOnly: true,
            buttonImage: "templates/system/images/calendar.png",
            onSelect: function (selectedDate, inst) {
                var field = $("#auto_end_date");
                var currentDate = $(this).datepicker("getDate");
                if (field && currentDate) {
                    field.datepicker('option', 'minDate', currentDate);
                    var limitDate = new Date(currentDate.getTime());
                    limitDate.setDate(limitDate.getDate() + 365);
                    field.datepicker('option', 'maxDate', limitDate);
                }
            }
        });

        // Calendario derecho
        $("#auto_end_date").datepicker({
            showOn: "button",
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true,
            defaultDate: "+30",
            numberOfMonths: 2,
            buttonImageOnly: true,
            buttonImage: "templates/system/images/calendar.png",
            beforeShow: function (input, inst) {
                var currentDate = $("#auto_start_date").datepicker("getDate");
                if (currentDate) {
                	inst.settings.minDate = currentDate;
                    var limitDate = new Date(currentDate.getTime());
                    limitDate.setDate(limitDate.getDate() + 365);
                    inst.settings.maxDate = limitDate;
                }
            }
        });

        // Click en botón de consulta
        $("#search_stock").click(function () {
            document.adminForm.task.value = "showstock";
            document.adminForm.submit();
        });
	});

Joomla.submitbutton = function(pressbutton) {
        var form = document.adminForm;

        if (pressbutton == 'cancel') {
            submitform(pressbutton);
            return;
        }

        // Determinar cuál es la tarea a realizar
        var task = (jQuery("#manualLoad").hasClass("open"))? "manualloadstock": "autoloadstock";
        form.task.value = task;

        var submit = true;
        var exp = /\D/g;
        var value = null;
        if (task == "manualloadstock") {
            jQuery("input.stock_input").each(function () {
                value = jQuery.trim(jQuery(this).val());
	            if (submit && exp.test(value)) {
	            	submit = false;
	            	jQuery(this).focus();
	            	alert("<?php echo JText::_('CP.PRODUCT_STOCK_ERROR_INVALID_QUANTITY', true); ?>");
	            }
            });
        } else {
        	value = jQuery.trim(form.quantity.value);
	        if (value.length < 1 || exp.test(value)) {
	            submit = false;
	            form.quantity.focus();
	            alert("<?php echo JText::_('CP.PRODUCT_STOCK_ERROR_INVALID_QUANTITY', true); ?>");
	        }
	    }
        if (!submit) {
            return;
        }

        // Se manda qué botón se presionó
        form.realtask.value = pressbutton;

        submitform(task);
    }

</script>

<div id="stockInfo">
<form action="index.php" method="post" name="adminForm" id="adminForm">
<?php
echo $myTabs->startPane('stockInfo');
echo $myTabs->startPanel(JText::_('CP.PRODUCT_STOCK_MANUAL_LOAD_LABEL'), 'manualLoad');
?>
    <fieldset class="search_form">
        <legend><?php echo JText::_('CP.PRODUCT_STOCK_SEARCH_LABEL'); ?></legend>
        <label><?php echo JText::_('CP.PRODUCT_STOCK_START_DATE_LABEL'); ?></label>&nbsp;&nbsp;
        <input type="text" class="text_area" name="manual_start_date" id="manual_start_date" size="15" value="<?php echo $data->manual_start_date; ?>" readonly />
        &nbsp;&nbsp;&nbsp;&nbsp;<label><?php echo JText::_('CP.PRODUCT_STOCK_END_DATE_LABEL'); ?></label>&nbsp;&nbsp;
        <input type="text" class="text_area" name="manual_end_date" id="manual_end_date" size="15" value="<?php echo $data->manual_end_date; ?>" readonly />
        &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="search_stock" id="search_stock" value="<?php echo JText::_('CP.PRODUCT_STOCK_SEARCH_BUTTON'); ?>" />
    </fieldset>

    <?php
    if (!empty($data->stockParams)) {
        // Nombre corto de cada día
        $days = array();
        for ($i = 1; $i < 8; $i++) {
            $days[$i] = JText::_('CP.DAY_SHORT' . $i);
        }
        // Nombre corto de cada mes
        $months = array();
        for ($i = 1; $i < 13; $i++) {
            $months[$i] = JText::_('CP.MONTH_SHORT' . $i);
        }

        $content = '<table border="0" class="listrateparams liststockparams">';
    	// Celda mostrando las fechas de búsqueda
	    $text_dates = intval(substr($data->manual_start_date, 8)) . ' ' . $months[intval(substr($data->manual_start_date, 5, 2))] . ' ' . substr($data->manual_start_date, 0, 4);
	    $text_dates .= ' - ' . intval(substr($data->manual_end_date, 8)) . ' ' . $months[intval(substr($data->manual_end_date, 5, 2))] . ' ' . substr($data->manual_end_date, 0, 4);
	    $content .= '<tr><th class="principalrateparam">' . $text_dates . '</th>';

        $i = 1;
        // Recorre y muestra el listado de parámetros
        foreach ($data->stockParams as $key => $row) {
            $param_id = $row['param_id'];
            $class = ($i % 2)? 'odd': 'even';
            $content .= '<tr><td class="' . $class . '">' . $row['param_name'] . '</td></tr>';
            $i++;
        }
        $content .= '</table><div id="liststockdates"><table border="0" class="listrateparams"><tr>';

	    // Títulos con las fechas
		$day = $data->manual_start_date;
		$timestamp = strtotime($day);
		$end_timestamp = strtotime('+1 day', strtotime($data->manual_end_date));
		while ($timestamp < $end_timestamp) {
            $dayNumber = date('N', $timestamp);
            $class = ($dayNumber > 5)? ' bolded': '';
            $content .= '<th class="odd' . $class . '">' . $months[intval(substr($day, 5, 2))] . '<br />' . $days[$dayNumber] . ' ' . intval(substr($day, 8)) . '</th>';
            $timestamp = strtotime('+1 day', strtotime($day));
		    $day = date('Y-m-d', $timestamp);
		}
        $content .= '</tr>';

	    $i = 1;
        $today_timestamp = strtotime(date('Y-m-d'));
	    // Recorre el listado de parámetros y muestra el inventario que hay del producto para cada fecha
	    foreach ($data->stockParams as $key => $row) {
	    	$param_id = $row['param_id'];
	    	$class = ($i % 2)? 'odd': 'even';
	    	$content .= '<tr>';

	    	// Campos por cada fecha
	    	$day = $data->manual_start_date;
	        $timestamp = strtotime($day);
	        $end_timestamp = strtotime('+1 day', strtotime($data->manual_end_date));
	        while ($timestamp < $end_timestamp) {
	        	// Deshabilitar los campos de fechas menores a  hoy 
	        	$status = ($timestamp < $today_timestamp)? ' disabled': '';
	            $dayNumber = date('N', $timestamp);
	            // Si no hay stock, muestre en cero.
                $quantity = (isset($data->stockValues[$param_id][$day]))? $data->stockValues[$param_id][$day]: 0;
                $content .= '<td class="' . $class . '"><input type="text" name="stock[' . $param_id . '][' . $day . ']" value="' . $quantity . '" class="stock_input" size="4"' . $status . ' /></td>';
	            $timestamp = strtotime('+1 day', strtotime($day));
	            $day = date('Y-m-d', $timestamp);
	        }
		    $content .= '</tr>';
		    $i++;
	    }
	    echo $content . '</table></div>';
    }
    ?>
<?php
echo $myTabs->endPanel();
echo $myTabs->startPanel(JText::_('CP.PRODUCT_STOCK_AUTOMATIC_LOAD_LABEL'), 'automaticLoad');
?>
	<table border="0" cellpadding="5" cellspacing="1">
		<tbody>
            <tr>
                <td><label for=auto_start_date><strong><?php echo JText::_('CP.PRODUCT_STOCK_START_DATE_LABEL'); ?></strong></label></td>
                <td>
                    <input type="text" class="text_area" name="auto_start_date" id="auto_start_date" size="15" value="<?php echo $data->auto_start_date; ?>" readonly />
                </td>
			</tr>
            <tr>
                <td><label for=auto_end_date><strong><?php echo JText::_('CP.PRODUCT_STOCK_END_DATE_LABEL'); ?></strong></label></td>
                <td>
                    <input type="text" class="text_area" name="auto_end_date" id="auto_end_date" size="15" value="<?php echo $data->auto_end_date; ?>" readonly />
                </td>
			</tr>
            <tr>
                <td><label for=quantity><strong><?php echo JText::_('CP.PRODUCT_STOCK_QUANTITY_LABEL'); ?></strong></label></td>
                <td>
                    <input type="text" class="text_area" name="quantity" id="quantity" size="15" />
                    <input type="hidden" name="param_id[]" id="param_id[]" value="1" />
                </td>
			</tr>
		</tbody>
	</table>
<?php
echo $myTabs->endPanel();
echo $myTabs->endPane();
?>
<input type="hidden" name="view" value="<?php echo $this->viewName; ?>" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="product_id" value="<?php echo $data->product_id; ?>" />
<input type="hidden" name="cid[]" value="<?php echo $data->product_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="realtask" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>
</div>