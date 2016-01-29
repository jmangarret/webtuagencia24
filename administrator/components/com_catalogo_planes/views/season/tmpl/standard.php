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
        <?php
        $lang =& JFactory::getLanguage();
        $langTag = substr($lang->getTag(), 0, 2);
        if ($langTag != 'en') {
            echo 'if ($.datepicker.regional["' . $langTag . '"] != undefined) { $.datepicker.setDefaults($.datepicker.regional["' . $langTag . '"]); }';
        }

        if ($this->editable) {
        ?>
        // Opción de seleccionar todos
        $("#day0").change(function () {
            var state = false;
            if ($(this).attr("checked")) {
                state = true;
            }
            $(".season_day").attr("checked", state);
        });

        // Opción seleccionar/eliminar todos los días
        $(".season_day").change(function () {
            if (!$(this).attr("checked")) {
                $("#day0").attr("checked", false);
            }
        });

        // Calendario nuevo
        var newCalendarOptions = {
            showOn: "button",
            minDate: "+0",
            dateFormat: 'yy-mm-dd',
            stepMonths: 2,
            changeYear: true,
            changeMonth: true,
            numberOfMonths: 2,
            buttonImageOnly: true,
            showButtonPanel: true,
            buttonImage: "templates/system/images/calendar.png",
            onSelect: function (selectedDate, inst) {
                var field = $(this).closest("tr").find(".season_end_date");
                if (field && selectedDate) {
                    field.datepicker('option', 'minDate', selectedDate);
                }
            }
        };

        // Calendario de la izquierda
        var totalCalendars = <?php echo count($data->dates); ?>;
        var initiedCalendars = 0;
        var initialCalendarOptions = {
            showOn: "button",
            dateFormat: 'yy-mm-dd',
            stepMonths: 2,
            changeYear: true,
            changeMonth: true,
            numberOfMonths: 2,
            buttonImageOnly: true,
            showButtonPanel: true,
            buttonImage: "templates/system/images/calendar.png",
            beforeShow: function(input, inst) {
                if (initiedCalendars <= totalCalendars) {
                    var selectedDate = input.value;
                    var start = $(this).datepicker('getDate');
                    var today = new Date();
                    today.setDate((today.getDate() - 1));
                    if (selectedDate && (start < today)) {
                        inst.settings.minDate = selectedDate;
                    } else {
                        inst.settings.minDate = "+0";
                    }
                }
                initiedCalendars++;
            },
            onSelect: function (selectedDate, inst) {
                var field = $(this).closest("tr").find(".season_end_date");
                if (field && selectedDate) {
                    field.datepicker('option', 'minDate', selectedDate);
                }
            }
        };

        // Calendario derecho
        var endCalendarOptions = {
            showOn: "button",
            dateFormat: 'yy-mm-dd',
            stepMonths: 2,
            changeYear: true,
            changeMonth: true,
            numberOfMonths: 2,
            buttonImageOnly: true,
            showButtonPanel: true,
            buttonImage: "templates/system/images/calendar.png",
            beforeShow: function (input, inst) {
                var field = $(this).closest("tr").find(".season_initial_date");
                if (field) {
                    var start = field.datepicker('getDate');
                    if (start == null) {
                    	start = "+0";
                    }
                    $(this).datepicker('option', 'minDate', start);
                }
            }
        };

        // Borrado de fecha.
        function deleteSeasonDate (el) {
            $(el).closest("tr").remove();
        };

        // Se agrega fecha
        function addSeasonDate (el) {
            var row = $(el).closest("tr");

            var newrow = '<tr class="season_dates_row"><td><input class="text_area season_initial_date" type="text" name="dates[' + contDates + '][start_date]" size="15" value="" readonly /></td>';
            newrow += '<td><input class="text_area season_end_date" type="text" name="dates[' + contDates + '][end_date]" size="15" value="" readonly />';
            newrow += '</td><td><img border="0" class="add_season_date" alt="<?php echo JText::_('CP.ADD'); ?>" src="<?php echo COM_CATALOGO_PLANES_IMAGESDIR; ?>/add.png" /> <img class="delete_season_date" border="0" alt="<?php echo JText::_('CP.DELETE'); ?>" src="<?php echo COM_CATALOGO_PLANES_IMAGESDIR; ?>/cancel.png" /></td></tr>';
            row.after(newrow);
            resetCalendars(true);
            contDates++;
        };

        // Función para reiniciar manejo de calendarios 
        function resetCalendars (isNew) {
            // Crea calendarios de la izquierda
            if (isNew) {
                $(".season_initial_date").unbind().datepicker(newCalendarOptions);
            } else {
                $(".season_initial_date").unbind().datepicker(initialCalendarOptions);
            }

            // Crea calendarios de la derecha
            $(".season_end_date").unbind().datepicker(endCalendarOptions);

            // Asigna evento para borrar fechas
            $(".delete_season_date").unbind().click(function () {
                deleteSeasonDate(this);
            });

            // Asigna evento Agregar Fecha
            $(".add_season_date").unbind().click(function () {
                addSeasonDate(this);
            });
        }

        // Iniciar calendarios
        <?php if ($data->season_id) { ?>
        resetCalendars(false);
        <?php } else { ?>
        resetCalendars(true);
        <?php } ?>
        <?php } else { ?>
        // Desactivar los campos de fechas cuando la vigencia tenga productos asociados
        $(".season_initial_date, .season_end_date").attr("disabled", "disabled");
        <?php } ?>
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
            // validar que al menos seleccione un día
            var submit = false;
            for (var i = 1; i < 8; i++) {
                if (form[('day' + i)].checked) {
                    submit = true;
                    break;
                }
            }
            if (!submit) {
                alert("<?php echo JText::_('CP.SEASON_ERROR_EMPTY_DAYS', true); ?>");
                return;
            }

            // validar que al menos seleccione una fecha
            var end = "";
            var start = "";
            var submit = true;
            var end_date = "";
            var start_date = "";
            var cont_dates = 0;
            var end_date_value = "";
            var start_date_value = "";
            var approved_dates = new Array();
            var selected_dates = new Array();
            var startDateF;
            var endDateF;
            var total_days=0;
            jQuery("tr.season_dates_row:visible").each(function () {                
                if (!submit) return;
                start_date_field = jQuery(this).find("input.season_initial_date");
                end_date_field = jQuery(this).find("input.season_end_date");
                start_date_value = jQuery(start_date_field).val();
                end_date_value = jQuery(end_date_field).val();

                // Verificar que estén ambas fechas del rango
                if (start_date_value == "" || end_date_value == "") {
                	jQuery(start_date_field).focus();
                    alert("<?php echo JText::_('CP.SEASON_ERROR_INVALID_DATE', true); ?> ");
                    submit = false;
                    return;
                } else {
                    start = start_date_value.split('-');
                    end = end_date_value.split('-');
                    if (start.length < 3 || end.length < 3) {
                    	jQuery(start_date_field).focus();
                        alert("<?php echo JText::_('CP.SEASON_ERROR_INVALID_DATE', true); ?> ");
                        submit = false;
                        return;
                    } else {
	                    // Verificar que la fecha de inicio sea menor/igual que la final
	                    start_date = new Date(start[0], (parseInt(start[1], 10) - 1), (parseInt(start[2], 10)), 0, 0, 0, 0);
	                    end_date = new Date(end[0], (parseInt(end[1], 10) - 1), (parseInt(end[2], 10)), 0, 0, 0, 0);
	                    if (start_date > end_date) {
	                    	jQuery(start_date_field).focus();
	                        alert("<?php echo JText::_('CP.SEASON_ERROR_INVALID_DATE_RANGE', true); ?>");
	                        submit = false;
	                        return;
	                    }
	                    name = jQuery(start_date_field).attr("name");
	                    i = name.replace(/\D/g, "");
	                    approved_dates.push({"start_date": start_date, "start_date_value": start_date_value, 
	                        "end_date": end_date, "end_date_value": end_date_value, "index": i});
	                }
                }
                //Paso las fechas a formato Date
                startDateF = getDateFormat(start_date_value);
                endDateF = getDateFormat(end_date_value);
                
                //Voy sumando el numero de dias 
                total_days = daysBetween(startDateF, endDateF)+1;
                cont_dates += daysBetween(startDateF, endDateF)+1;

                //Si el total de los dias es menor a 7 agrego el numero de dia a un arreglo
                if (cont_dates < 7) {                	
                    var newDate;
                    for(var i=0; i<total_days; i++) {
                    	newDate = getDateFormat(addToDate(start_date_value, i));
                    	var dayNumber = newDate.getUTCDay();
                    	//La funcion devuelve el domingo como 0 y el aplicativo lo esta manejando con el numero 7
                    	if(dayNumber==0){
                    		dayNumber = 7;
                    	}
                    	selected_dates.push(dayNumber);
                    }
                }
            });
            if (!submit) {
                return;
            }

            //Valido si solo se ha agregado un rango de fechas 
            if (cont_dates < 7) {
            	var validate_day_number = true;
            	var count_days = 0;            	
            	//Es necesario recorrerlos todos para poder saber el numero de dia ya que no existe nada que me lo indique en el elemento
            	jQuery(".season_day").each(function() {
            		count_days++;
            		
            		//Valido si esta checkeado y lo tomo en cuenta
            		if (jQuery(this).is(':checked')) {            			
                    	if (jQuery.inArray(count_days, selected_dates) == -1) {
                    		validate_day_number = false;                		
                    	}
            		}


            	});
            	if (!validate_day_number) {
            		alert("<?php echo JText::_('CP.SEASON_ERROR_INVALID_DAY_SELECTED', true); ?>");
                    return false;
            	}            	
            }
            if (!submit) {
                return;
            }
                
            if (approved_dates.length < 1) {
                form['dates[0][start_date]'].focus();
                alert("<?php echo JText::_('CP.SEASON_STANDARD_ERROR_EMPTY_DATES', true); ?>");
                return;
            }
            // Se verifica que no haya solapamiento.
            for (var i = 0; i < approved_dates.length; i++) {
                start = approved_dates[i].start_date;
                start_value = approved_dates[i].start_date_value;
                for (var j = 0; j < approved_dates.length; j++) {
                    if (i != j) {
                        if ((start > approved_dates[j].start_date && start < approved_dates[j].end_date) || 
                        	start_value == approved_dates[j].start_date_value || start_value == approved_dates[j].end_date_value) {
                            var index = approved_dates[i].index;
                            form['dates[' + index + '][start_date]'].focus();
                            alert("<?php echo JText::_('CP.SEASON_ERROR_DATE_OVERLAP', true); ?> \n" + 
                                    form['dates[' + index + '][start_date]'].value + " - " + form['dates[' + index + '][end_date]'].value + 
                                    "\n <?php echo JText::_('CP.AND', true); ?> \n" + approved_dates[j].start_date_value + " - " + approved_dates[j].end_date_value);
                            return;
                        }
                    }
                }
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
        if (!Joomla.submitbutton("apply")) {
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
        <legend><?php echo JText::_('CP.STANDARD_SEASON'); ?></legend>
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
        <table class="admintable" style="float: left;">
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
                <td>
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
                <td>
                    <?php echo JHTML::_('date', $data->modified, 'd/m/Y H:i'); ?>
                </td>
            </tr>
            <?php endif; ?>
        </table>
		<fieldset class="adminform" style="float: left;">
			<table border="0" cellpadding="0" class="season_dates">
				<tr>
					<td valign="top" style="padding-right: 10px;">
						<table border="0">
							<tr>
								<th align="left" valign="top"><?php echo JText::_('CP.SEASON_INITIAL_DATE'); ?></th>
								<th align="left" valign="top"><?php echo JText::_('CP.SEASON_END_DATE'); ?></th>
								<th align="left" valign="top">&nbsp;</th>
							</tr>
							<?php
							// Mostrar las fechas ya seleccionadas
							$contDates = 0;
							if (is_array($data->dates) && count($data->dates) > 0) {
								foreach ($data->dates as $date) {
									?>
							<tr class="season_dates_row">
								<td valign="top">
								   <input class="text_area season_initial_date" type="text" name="dates[<?php echo $contDates; ?>][start_date]" size="15" value="<?php echo $date->start_date; ?>" readonly />
								</td>
								<td valign="top">
								   <input class="text_area season_end_date" type="text" name="dates[<?php echo $contDates; ?>][end_date]" size="15" value="<?php echo $date->end_date; ?>" readonly />
								</td>
								<td valign="top">
								<?php if ($this->editable) { ?>
								   <img border="0" class="add_season_date" alt="<?php echo JText::_('CP.ADD'); ?>" src="<?php echo COM_CATALOGO_PLANES_IMAGESDIR; ?>/add.png" />
                                    <?php
									// Mostrar las fechas ya seleccionadas
									if ($contDates > 0) { ?>
                                        <img border="0" class="delete_season_date" alt="<?php echo JText::_('CP.DELETE'); ?>" src="<?php echo COM_CATALOGO_PLANES_IMAGESDIR; ?>/cancel.png" />
                                    <?php
									}
								}
									?>
								</td>
							</tr>
							<?php
							$contDates++;
								}
							} else {
								?>
							<tr class="season_dates_row">
								<td valign="top">
								   <input class="text_area season_initial_date" type="text" name="dates[0][start_date]" size="15" value="" readonly />&nbsp;&nbsp;
								</td>
								<td valign="top">
								   <input class="text_area season_end_date" type="text" name="dates[0][end_date]" size="15" value="" readonly />
								</td>
								<td valign="top">
								   <img border="0" class="add_season_date" alt="<?php echo JText::_('CP.ADD'); ?>" src="<?php echo COM_CATALOGO_PLANES_IMAGESDIR; ?>/add.png" />
							    </td>
							</tr>
							<?php
							$contDates++;
							}
							$document =& JFactory::getDocument();
							$document->addScriptDeclaration('var contDates = ' . $contDates . ';');
				
							// Verificar si todos los días han sido seleccionados
							$total = 0;
							for ($cont = 1; $cont < 8; $cont++) {
								$day = 'day' . $cont;
								$total += (int) $data->$day;
							}
							if ($total == 7) {
								$checked = ' checked';
							} else {
								$checked = '';
							}
                            if (!$this->editable) {
                                $checked .= ' disabled="disabled"';
                            }
							?>
						</table>
					</td>
					<td valign="top">
						<div class="dayinput">
						  <input type="checkbox" value="1" name="day0" id="day0"<?php echo $checked; ?> /> <b><?php echo JText::_('CP.SEASON_ALL_DAYS'); ?></b>
						</div>
						<?php
						// Mostrar los días
						$days = '';
						for ($cont = 1; $cont < 8; $cont++) {
							$day = 'day' . $cont;
							if ($data->$day == 1) {
								$checked = ' checked';
							} else {
								$checked = '';
							}
                            if (!$this->editable) {
                                $checked .= ' disabled="disabled"';
                            }
							$days .= '<div class="dayinput"><input type="checkbox" name="day' . $cont . '" id="day' . $cont . '" class="season_day" value="1"' .  $checked . ' /> ' .  JText::_('CP.DAY_LONG' . $cont) . '</div>';
						}
						echo $days;
						?>
					</td>
				</tr>
			</table>
		</fieldset>
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

<input type="hidden" name="view" value="<?php echo $this->viewName; ?>" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="season_id" value="<?php echo $data->season_id; ?>" />
<input type="hidden" name="is_special" value="0" />
<input type="hidden" name="task" value="" />
</form>