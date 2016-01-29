<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
$data =& $this->data;
JHTML::_('behavior.modal');
?>
<script type="text/javascript">
    //Maintain array of dates
    var selectedDates = new Array();
    var today = new Date();
    today.setDate((today.getDate() - 1));
    <?php
    // Mostrar las fechas ya seleccionadas si la vigencia es especial
    if ($data->is_special == 1 && is_array($data->dates) && count($data->dates) > 0) {
        $result = "";
        foreach ($data->dates as $date) {
        	$result .= "\nselectedDates.push(\"" . $date->start_date . "\");";
        }
        echo $result;
    }
    ?>

    // Guardar en caché las temporadas que ya se han buscado.
    var cacheSeasons = new Array();

    // Opción de seleccionar todos
    var datepickerOptions = {
    	minDate: (selectedDates[0] != undefined && ((new Date(selectedDates[0])) < today))? selectedDates[0]: '+0',
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        stepMonths: 2,
        defaultDate: (selectedDates[0] != undefined)? selectedDates[0]: null,
        changeMonth: true,
        numberOfMonths: 2,
        showButtonPanel: true,
        beforeShowDay: function (date) {
            var selectedDate = jQuery.datepicker.formatDate(jQuery(this).datepicker('option', 'dateFormat'), date);
            var gotDate = jQuery.inArray(selectedDate, selectedDates);
            if (gotDate >= 0) {
                return [false, "unselectedcalendarcell"];
            }
            // Dates not in the array are left enabled, but with no extra style
            return [false, "selectedcalendarcell"];
        }
    };

    jQuery.noConflict()(document).ready(function($) {
        jQuery.ajaxSetup({ cache: false });
	    <?php
	    $lang =& JFactory::getLanguage();
	    $langTag = substr($lang->getTag(), 0, 2);
	    if ($langTag != 'en') {
	        echo 'if ($.datepicker.regional["' . $langTag . '"] != undefined) { $.datepicker.setDefaults($.datepicker.regional["' . $langTag . '"]); }';
	    }

        // Seleccionar la vigencia del tarifario
        if ($data->season_id > 0) {
        	echo "\n\n$(\"select#season_id\").val(\"" . $data->season_id . "\");\n\n";
        }

	    // Mostrar las fechas ya seleccionadas
	    if ($data->rate_id > 0) {
		    if ($data->is_special == 1) {
		        echo '$("#special_season_dates").datepicker(datepickerOptions);';
		    } else {
		        echo '$("#standard_season_dates").css("display", "block");';
		    }
	    }
	    ?>

	    $("body").delegate("#season_id", "change", function() {
            // Se obtiene la vigencia seleccionada.
            var season_id = parseInt($(this).val(), 10);
            // Se limpia la zona de las fechas.
            $("#special_season_dates").removeClass("hasDatepicker");
            $("#special_season_dates").html("");
            $("#standard_season_dates").css("display", "none");
            $('#errorMessage').html("");

            // Si es una vigencia válida, traiga sus fechas y días seleccionados.
            if (season_id > 0) {
                var url = "index.php?option=<?php echo $option; ?>&view=season&task=getrawrow&format=raw&season_id=" + season_id;
                // Verifica si los datos de la vigencia están en caché, si no, los trae por ajax
                if (cacheSeasons[season_id] == undefined) {
	                $.getJSON(url, function (data) {
	                    showResults(data);
	                    cacheSeasons[season_id] = data;
	                });
                } else {
                    // Mostrar temporada con datos en caché
                    showResults(cacheSeasons[season_id]);
                }
            }
        });
    });

    // Verifica si un día de una vigencia está seleccionado y activa o desactiva su checkbox
    function checkSeasonDay (day, value) {
        if (value == "1") {
            jQuery("#day" + day).attr("checked", "checked");
        } else {
            jQuery("#day" + day).attr("checked", false);
        }
    }

    // Muestra los datos de la temporada
    function showResults (data) {
        // Se genera el calendario si es vigencia especial
        if (data.is_special == 1) {
            // Se limpia el arreglo con las fechas seleccionadas
            selectedDates.splice(0, selectedDates.length);
            jQuery.each(data.dates, function (key, val) {
                selectedDates.push(val.start_date);
            });
            // Se pinta el calendario.
            datepickerOptions.minDate = (selectedDates[0] != undefined && ((new Date(selectedDates[0])) < today))? selectedDates[0]: '+0';
            datepickerOptions.defaultDate = (selectedDates[0] != undefined)? selectedDates[0]: null,
            jQuery("#special_season_dates").datepicker(datepickerOptions);
        } else {
            // Generar los checkbox de los días
            checkSeasonDay(1, data.day1);
            checkSeasonDay(2, data.day2);
            checkSeasonDay(3, data.day3);
            checkSeasonDay(4, data.day4);
            checkSeasonDay(5, data.day5);
            checkSeasonDay(6, data.day6);
            checkSeasonDay(7, data.day7);

            // Limpiar las fechas de la tabla de vigencia estándar
            jQuery("#season_dates_list tr").slice(1).remove();
            jQuery.each(data.dates, function (key, val) {
                row = '<tr><td valign="top">' + 
                    '<input class="text_area season_initial_date" type="text" name="season_start_date" size="15" value="' + val.start_date + '" disabled />' + 
                '</td><td valign="top">' + 
                    '<input class="text_area season_end_date" type="text" name="season_end_date" size="15" value="' + val.end_date + '" disabled />' + 
                '</td></tr>';
                jQuery(row).appendTo("#season_dates_list");
            });
            jQuery("#standard_season_dates").css("display", "block");
        }
    }

    // Función para actualizar el filtro de vigencias después de crear una nueva
    function addSeason (season_id) {
        var url = "index.php?option=<?php echo $option; ?>&view=season&task=getrawlistbyproducttype&format=raw&product_type_code=tours";
        url = url + "&product_id=<?php echo $data->product_id; ?>&season_id=<?php echo $data->season_id; ?>&rate_id=<?php echo $data->rate_id; ?>";
        <?php
            // Seleccionar la vigencia del tarifario
            if ($data->season_id > 0) {
                echo "\nurl = url + \"&season_id=" . $data->season_id . "\";";
            }
        ?>
        // Actualizar el listado de vigencias
        jQuery.getJSON(url, function (data) {
            var itemsSpecial = ['<optgroup label="<?php echo JText::_('CP.SPECIAL_SEASON'); ?>">'];
            var itemsStandard = ['<option value="0"> - <?php echo JText::_('CP.SELECT'); ?> - </option><optgroup label="<?php echo JText::_('CP.STANDARD_SEASON'); ?>">'];
            jQuery.each(data, function (key, val) {
                if (val["is_special"] == "1") {
                    itemsSpecial.push('<option value="' + val["season_id"] + '">' + val["season_name"] + '</option>');
                } else {
                    itemsStandard.push('<option value="' + val["season_id"] + '">' + val["season_name"] + '</option>');
                }
            });
            jQuery('#season_id').html(itemsStandard.join("\n") + "</optgroup>" + itemsSpecial.join("\n") + "</optgroup>");
        });
        jQuery("#special_season_dates").removeClass("hasDatepicker");
        jQuery("#special_season_dates").html("");
        jQuery("#standard_season_dates").css("display", "none");
        jQuery('#errorMessage').html("");

        var url = "index.php?option=<?php echo $option; ?>&view=season&task=getrawrow&format=raw&season_id=" + season_id;
        jQuery.getJSON(url, function (data) {
            cacheSeasons[season_id] = data;
        });
    }

    // Validación del formulario
    function submitbutton(pressbutton) {
        var form = document.adminForm;

        if (pressbutton == 'cancel') {
            submitform(pressbutton);
            return;
        }

        // do field validation
        var day_rate_field = jQuery('#day_rate');
        if (form.season_id.selectedIndex == 0) {
            alert("<?php echo JText::_('CP.PRODUCT_RATE_ERROR_EMPTY_SEASON', true); ?>");
            form.season_id.focus();
        } else if (jQuery.trim(form.basic_price.value) == "" || parseFloat(jQuery.trim(form.basic_price.value)) <= 0) {
            alert("<?php echo JText::_('CP.PRODUCT_RATE_ERROR_EMPTY_BASIC_PRICE', true); ?>");
            form.basic_price.focus();
        } else {
            var submit = true;
            var reg = /[^0123456789\.]/g;
            var dots = null;

            // Validar que al menos se haya ingresado un precio para adulto
            jQuery("input.rateparams").each(function (index, el) {
                if (!submit) {
                    return;
                }
                var valor = jQuery.trim(jQuery(el).val());
                if (valor) {
                    // Verifitour que la tarifa sea válida (decimal con puntos, no comas)
                    dots = valor.match(/\./g);
                    if (reg.test(valor) || (dots && dots.length > 1)) {
                        alert("<?php echo JText::_('CP.PRODUCT_RATE_ERROR_INVALID_PRICE', true); ?>");
                        jQuery(el).focus();
                        submit = false;
                    }
                }
            });
            if (!submit) {
                return;
            }

            // Verifitour que los precios de niños ingresados sean válidos
            jQuery("#basic_price, #previous_value, input.supplementrate").each(function (index, el) {
                if (!submit) {
                    return;
                }
                var valor = jQuery.trim(jQuery(el).val());
                if (valor == "" || valor == "0") return;
                // Verifitour que la tarifa sea válida (decimal con puntos, no comas)
                dots = valor.match(/\./g);
                if (reg.test(valor) || (dots && dots.length > 1)) {
                    jQuery(el).focus();
                    submit = false;
                    alert("<?php echo JText::_('CP.PRODUCT_RATE_ERROR_INVALID_PRICE', true); ?>");
                }
            });
            if (!submit) {
                return;
            }

            // Validar que no haya solapamiento de vigencias dentro del producto
	        var url = "index.php?option=<?php echo $option; ?>&view=tourrate&task=checkseasonoverlapping&format=raw&season_id=";
	        url = url + jQuery("select#season_id").val() + "&product_id=<?php echo $data->product_id; ?>&rate_id=<?php echo $data->rate_id; ?>";
            jQuery.getJSON(url, function (data) {
                if (data.approved) {
                    submitform(pressbutton);
                } else {
                    var results = "";
	                jQuery.each(data.list, function (key, val) {
	                    if (val["is_special"] == "1") {
	                    	results += "<br />" + val["season_name"] + ": " + val["start_date"];
	                    } else {
                            results += "<br />" + val["season_name"] + ": " + val["start_date"] + " - " + val["end_date"];
	                    }
	                });
	                submit = false;
	                jQuery('#errorMessage').html("<?php echo JText::_('CP.PRODUCT_RATE_ERROR_SEASON_OVERLAPPING', true); ?>" + results);
	                form.season_id.focus();
                }
            });
        }
    }

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
    <fieldset class="adminform">
        <div id="errorMessage"></div>
        <table class="admintable" border="0">
            <tr>
                <td valign="top">
                    <fieldset>
                        <legend><?php echo JText::_('CP.SEASON'); ?></legend>
	                    <br />
	                    <?php
	                    // Mostrar el combo de vigencias
	                    $standardSeasons = '';
	                    $specialSeasons = '';
	                    foreach ($this->seasons as $index => $season) {
	                    	// Generar opciones combo de vigencia
	                    	if ($season->is_special == 1) {
	                            $specialSeasons .= '<option value="' . $season->season_id . '">' . $season->season_name . '</option>';
	                    	} else {
	                            $standardSeasons .= '<option value="' . $season->season_id . '">' . $season->season_name . '</option>';
	                    	}
	                    }
	                    $content = '<select name="season_id" id="season_id" class="inputbox">';
	                    $content .= '<option value="0"> - ' . JText::_('CP.SELECT') . ' - </option>';
	                    $content .= '<optgroup label="' . JText::_('CP.STANDARD_SEASON') . '">' . $standardSeasons;
	                    $content .= '</optgroup><optgroup label="' . JText::_('CP.SPECIAL_SEASON') . '">';
	                    $content .= $specialSeasons . '</optgroup></select>';
	                    echo $content;
	                    ?>
	                    <br /><br />
	                    <a rel="{handler: 'iframe', size: {x: 925 y: 330}}" href="<?php echo JURI::base(true); ?>/index.php?option=<?php echo $option; ?>&view=season&task=edit&tmpl=component" class="modal add_standard_season">
	                    <img border="0" alt="<?php echo JText::_('CP.ADD_STANDARD_SEASON'); ?>" src="<?php echo COM_CATALOGO_PLANES_IMAGESDIR; ?>/add_season.png" /></a>
                        &nbsp;
                        <a rel="{handler: 'iframe', size: {x: 925, y: 330}}" href="<?php echo JURI::base(true); ?>/index.php?option=<?php echo $option; ?>&view=season&task=edit&tmpl=component" class="modal add_standard_season">
                        <?php echo JText::_('CP.ADD_STANDARD_SEASON'); ?></a><br />
                        <a rel="{handler: 'iframe', size: {x: 925, y: 545}}" href="<?php echo JURI::base(true); ?>/index.php?option=<?php echo $option; ?>&view=season&task=newSpecialSeason&tmpl=component" class="modal add_special_season">
	                    <img border="0" alt="<?php echo JText::_('CP.ADD_SPECIAL_SEASON'); ?>" src="<?php echo COM_CATALOGO_PLANES_IMAGESDIR; ?>/add_season.png" /></a>
                        &nbsp;
                        <a rel="{handler: 'iframe', size: {x: 925, y: 545}}" href="<?php echo JURI::base(true); ?>/index.php?option=<?php echo $option; ?>&view=season&task=newSpecialSeason&tmpl=component" class="modal add_special_season">
                        <?php echo JText::_('CP.ADD_SPECIAL_SEASON'); ?></a><br />
                    </fieldset>
                    <table class="admintable" border="0">
                        <tr>
                            <td><label for="basic_price"><strong><?php echo JText::_('CP.PRODUCT_RATE_BASIC_PRICE_LABEL'); ?>*</strong></label></td>
                            <td><input type="text" name="basic_price" id="basic_price" value="<?php echo $data->basic_price; ?>" class="isdecimal" /></td>
                            <td><?php echo JText::_('CP.TOUR_RATE_BASIC_PRICE_DESCRIPTION'); ?></td>
                        </tr>
                        <tr>
                            <td><label for="previous_value"><strong><?php echo JText::_('CP.PRODUCT_RATE_PREVIOUS_VALUE_LABEL'); ?></strong></label></td>
                            <td><input type="text" name="previous_value" id="previous_value" value="<?php echo $data->previous_value; ?>" class="isdecimal" /></td>
                            <td><?php echo JText::_('CP.TOUR_RATE_PREVIOUS_VALUE_DESCRIPTION'); ?></td>
                        </tr>
                    </table>
                </td>
                <td valign="top">
                    <!-- Para dibujar el calendario en vigencias especiales -->
                    <div id="special_season_dates"></div>
                    <!-- Para mostrar vigencias estándar -->
                    <table border="0" cellpadding="0" id="standard_season_dates" class="season_dates">
                        <tr>
                            <td valign="top" rowspan="8">
                                <div id="details_season">
                                <table class="admintable" border="0" id="season_dates_list">
                                    <tr>
                                        <th align="left"><?php echo JText::_('CP.SEASON_INITIAL_DATE'); ?></th>
                                        <th align="left"><?php echo JText::_('CP.SEASON_END_DATE'); ?></th>
                                    </tr>
                                    <?php
                                    // Mostrar las fechas ya seleccionadas de una vigencia estándar
                                    if ($data->is_special == 0 && is_array($data->dates) && count($data->dates) > 0) {
                                        foreach ($data->dates as $date) {
                                    ?>
                                    <tr>
                                        <td valign="top">
                                            <input class="text_area season_initial_date" type="text" name="season_start_date" size="15" value="<?php echo $date->start_date; ?>" disabled />
                                        </td>
                                        <td valign="top">
                                            <input class="text_area season_end_date" type="text" name="season_end_date" size="15" value="<?php echo $date->end_date; ?>" disabled />
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </table></div>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <?php
                                // Mostrar los días
                                $days = array();
                                for ($cont = 1; $cont < 8; $cont++) {
                                    $day = 'day' . $cont;
                                    if (isset($data->$day) && $data->$day == 1) {
                                        $checked = ' checked';
                                    } else {
                                        $checked = '';
                                    }
                                    $days[] = '<input type="checkbox" name="day' . $cont . '" id="day' . $cont . '" value="1"' .  $checked . ' disabled /> ' .  JText::_('CP.DAY_LONG' . $cont);
                                }
                                echo implode('</td></tr><tr><td valign="top">', $days);
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top" colspan="2">
					<?php
                    $prices = array();
                    // Se organiza los precios del tarifario en un arreglo.
                    foreach ($data->prices as $row) {
                    	$param1 = $row->param1;
                    	$param2 = $row->param2;
                    	if (!isset($prices[$param1])) {
                    		$prices[$param1] = array();
                    	}
                    	if (!isset($prices[$param1][$param2])) {
                    		$prices[$param1][$param2] = array();
                    	}
                    	$prices[$param1][$param2] = $row->price;
                    }

                    // Procesar los suplementos
                    $supplements = array();
                    foreach ($data->supplements as $row) {
                    	$supplements[$row->supplement_id] = $row->amount;
                    }
                ?>
                <fieldset>
                    <legend><?php echo JText::_('CP.RATES_LABEL'); ?></legend>
                    <br />
                    <table class="listrateparams">
                        <tr>
                            <th class="principalrateparam"></th>
                            <th class="odd"><?php echo JText::_('CP.TOUR_RATE_PARAM2_ID1_LABEL'); ?></th>
                            <th class="odd"><?php echo JText::_('CP.TOUR_RATE_PARAM2_ID2_LABEL'); ?></th>
                        </tr>
                        <tr>
                            <td class="even"><?php echo JText::_('CP.TOUR_RATE_PARAM1_ID1_LABEL'); ?></td>
                            <td class="even">
                                <input type="text" name="prices[0][price]" value="<?php echo (isset($prices[1][1]))? $prices[1][1]: 0; ?>" class="rateparams isdecimal" />
                                <input type="hidden" name="prices[0][param1]" value="1" />
                                <input type="hidden" name="prices[0][param2]" value="1" />
                            </td>
                            <td class="even">
                                <input type="text" name="prices[1][price]" value="<?php echo (isset($prices[1][2]))? $prices[1][2]: 0; ?>" id="day_rate" class="rateparams isdecimal" />
                                <input type="hidden" name="prices[1][param1]" value="1" />
                                <input type="hidden" name="prices[1][param2]" value="2" />
                            </td>
                        </tr>
                    </table>
                <br />
                </fieldset>
                <br />
                <fieldset>
                    <legend><?php echo JText::_('CP.SUPPLEMENTS'); ?></legend>
                    <?php if (!empty($this->supplements)): ?>
                    <table class="listratesupplements" border="0">
                        <tr>
                            <th align="center"><?php echo JText::_('NAME'); ?></th>
                            <th align="center"><?php echo JText::_('CP.AMOUNT'); ?></th>
                        </tr>
                        <?php foreach ($this->supplements as $key => $supplement) { ?>
                        <tr>
                            <td><?php echo $supplement->supplement_name; ?></td>
                            <td><input type="text" name="supplements[<?php echo $key; ?>][amount]" value="<?php echo (isset($supplements[$supplement->supplement_id]))? $supplements[$supplement->supplement_id]: 0; ?>" class="supplementrate isdecimal" />
                                <input type="hidden" name="supplements[<?php echo $key; ?>][supplement_id]" value="<?php echo $supplement->supplement_id; ?>" /></td>
                        </tr>
                        <?php } ?>
                    </table>
                    <?php else: ?>
                    <?php echo '<br />' . JText::_('CP.PRODUCT_EMPTY_RELATED_SUPPLEMENTS'); ?>
                    <?php endif; ?>
                </fieldset>
                </td>
            </tr>
        </table>
    </fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="view" value="<?php echo $this->viewName; ?>" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="product_id" value="<?php echo $data->product_id; ?>" />
<input type="hidden" name="rate_id" value="<?php echo $data->rate_id; ?>" />
<input type="hidden" name="task" value="" />
</form>