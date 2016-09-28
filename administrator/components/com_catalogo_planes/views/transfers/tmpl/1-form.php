<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
$data =& $this->data;

jimport('joomla.html.editor');
$editor =& JFactory::getEditor();

jimport('joomla.html.html');
jimport('joomla.html.pane');
JHTML::_('behavior.calendar');
JHTML::_('behavior.modal');

$myTabs = & JPane::getInstance ('tabs');
$document =& JFactory::getDocument();
$document->addScript('https://maps.google.com/maps/api/js?sensor=false');
$latitude = ($data->latitude) ? $data->latitude: '3.598219';
$longitude = ($data->longitude) ? $data->longitude: '-73.087749';
$document->addScriptDeclaration('
    var map;
    var marker;
    var geocoder;
    var infowindow;
    var countryName;
    function initialize() {
        var latlng = new google.maps.LatLng(' . $latitude . ', ' . $longitude . ');
        infowindow = new google.maps.InfoWindow();
        geocoder = new google.maps.Geocoder();
        var myOptions = {
            zoom: 4,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        marker = new google.maps.Marker({
            map: map
        });
        ' . (($data->product_id && $data->latitude)? 'placeMarker(latlng, false);': '') . '

        google.maps.event.addListener(map, "click", function(event) {
            placeMarker(event.latLng, true);
        });
    }

    function placeMarker(location, save) {
        infowindow.setContent();
        countryName = "";
        geocoder.geocode({"latLng": location}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    if (save) {
                        // Put the coordinates in the forms fields
                        jQuery("#details_latitude").val(location.lat());
                        jQuery("#details_longitude").val(location.lng());
                    }
                    marker.setPosition(location);

                    // Show the location.
                    countryName = "";
                    for (var i = 0; i < results[0].address_components.length; i++) {
                        if (jQuery.inArray("country", results[0].address_components[i].types[0])) {
                            countryName = "<b>C&oacute;digo de Pa&iacute;s:   </b> " + results[0].address_components[i].short_name;
                        }
                    }
                    infowindow.setContent(
                    "<b>Latitud:   </b>" + location.lat() + "<br />" + 
                    "<b>Longitud:   </b>" + location.lng() + "<br />" +
                    "<b>Direcci&oacute;n:   </b>" + results[0].formatted_address + "<br />" + countryName);
                    infowindow.open(map, marker);
                }
            } else {
                alert("' . JText::_('CP.PRODUCT_ERROR_GEOCODING_STANDARD', true) . '");
            }
        });
    }
    jQuery(document).ready(function() {initialize();});
');

// Se están usando estados/regiones?
$showRegions = ($this->params->cfg_use_regions == '1')? true: false;
$addTaxImg = '</td><td><img border="0" class="addtax" alt="' . JText::_('CP.ADD') . '" src="' . COM_CATALOGO_PLANES_IMAGESDIR . '/add.png" />';
$nameLabel = JText::_('NAME');
$taxesLabel = JText::_('CP.TAXES');
$imageLabel = JText::_('CP.SUPPLEMENT_IMAGEURL');
$descriptionLabel = JText::_('CP.SUPPLEMENT_DESCRIPTION');
$deleteTaxImg = '<img border="0" class="deletetax" alt="' . JText::_('CP.DELETE') . '" src="' . COM_CATALOGO_PLANES_IMAGESDIR . '/cancel.png" />';
$supplement_image_folder = $this->params->cfg_supplement_image_folder;
$deleteSupplementLabel = '<img border="0" class="deletesupplement" alt="' . JText::_('CP.PRODUCT_DELETE_SUPPLEMENT') . 
    '" src="' . COM_CATALOGO_PLANES_IMAGESDIR . '/cancel.png" />&nbsp;<a href="#" class="deletesupplement">' . JText::_('CP.PRODUCT_DELETE_SUPPLEMENT') . '</a>';
$taxSelectOptions = array(JHTML::_('select.option', 0, $this->emptySelectOption, 'tax_id', 'tax_name'));
foreach ($this->taxes as $key => $row) {
	$row->tax_name .= '&nbsp;' . ($row->tax_value * 100) . '%';
	$taxSelectOptions[] = $row;
}
$siteURL = JURI::root();
$cadena = addslashes(JHTML::_('select.genericlist', $taxSelectOptions, 'selectsupplement_taxes', 'class="inputbox supplement_taxes" size="1"', 'tax_id', 'tax_name'));
$cadena = preg_replace("[\n|\r|\n\r]", '', $cadena);
?>
<script type="text/javascript">
	var templateSelect = "<?php echo $cadena; ?>";
	var assignedSupplementList = new Array();
	<?php
	    // Obtener los ids de los suplementos ya relacionados al producto
	    if (count($data->supplements)) {
	        $content = '';
	        foreach ($data->supplements as $key => $row) {
	            $content .= 'assignedSupplementList.push("' . $row->supplement_id . '");';
	        }
	        echo $content;
	    }
	?>

	// Función para actualizar el filtro tipos de turismo para suplementos
	function addSupplement (supplement_id) {
		jQuery("#supplement_search_message").html('');
	    var url = "index.php?option=<?php echo $option; ?>&view=supplement&task=getrawlist&format=raw&supplement_id=" + supplement_id;
	    jQuery.getJSON(url, function (data) {
	        if (data.length > 0) {
	            var id = parseInt(data[0].supplement_id, 10);
	            if (data[0].imageurl) {
		            imageurl = '<img src="<?php echo JURI::root(); ?>' + data[0].imageurl + '" name="imagelib' + id + '" class="supplement_image" />';
	            } else {
		            imageurl = '';
	            }
                var newContent = '<div class="supplement_info"><hr />' +
	                '<table border="0" cellpadding="5">' +
	                    '<tr>' +
	                        '<td><table border="0" cellpadding="5"><tr>' +
	                        '<td class="supplement_label_cell"><input type="hidden" name="supplements[' + id + '][supplement_id]" value="' + id + '" /><label for="supplement_name' + id + '"><?php echo $nameLabel; ?></label></td>' +
	                        '<td class="supplement_text_cell">' + data[0].supplement_name + '</td></tr>' +
	                        '<tr>' +
	                            '<td><label for="supplement_description' + id + '"><?php echo $descriptionLabel; ?></label></td>' +
	                            '<td class="supplement_text_cell">' + data[0].description + '</td>' +
	                        '</tr></table></td>' +
	                        '<td class="supplement_image_cell">' + imageurl + '</td>' +
	                        '<td><fieldset><legend><?php echo $taxesLabel; ?></legend><div class="taxescontent"><table border="0" cellspacing="5"><tr><td>' +
	                        templateSelect.replace("selectsupplement_taxes", "selectsupplement_taxes[" + id + "]") + 
	                        '<?php echo $addTaxImg; ?></td></tr></table></div></fieldset><br /><div align="right"><?php echo $deleteSupplementLabel; ?></div></td>' +
	                    '</tr>' +
	                '</table>' +
	            '</div>';
	            // Agregar a la lista de suplementos asignados.
	            jQuery(newContent).prependTo("#supplements_tab");
	            assignedSupplementList.push(data[0].supplement_id);
	            // Eliminar la opción seleccionada del combo Suplementos Disponibles
	            jQuery('select#supplement_list option:selected').remove();
	        }
	    });
	}

	jQuery.noConflict()(document).ready(function ($) {
        jQuery.ajaxSetup({ cache: false });
        <?php
        $lang =& JFactory::getLanguage();
        $langTag = substr($lang->getTag(), 0, 2);
        if ($langTag != 'en') {
        	echo 'if ($.datepicker.regional["' . $langTag . '"] != undefined) { $.datepicker.setDefaults($.datepicker.regional["' . $langTag . '"]); }';
        }
        if ($data->product_id < 1) {
            echo '$(\'#details_disclaimer\').val("' . JText::_('CP.TRANSFER_DISCLAIMER_DEFAULT_VALUE') . '");';
        }
        ?>

        var oneDay = 24*60*60*1000;
        var old_fn = $.datepicker._updateDatepicker;
        var beginsCalendar = true;
        var elementsOrder = ["product_name", "detailscountry_id", "detailsregion_id", "detailscity_id", 
                             "details_publish_up", "details_publish_down", "details_featured", 
                             "details_product_desc", "details_additional_description", 
                             "details_product_code", "details_disclaimer",  
                             "details_access", "detailscategories", "details_transfer_type1", 
                             "details_transfer_type2", "detailssupplier_id", "details_published1", 
                             "details_published0", "details_product_url", "rateparam1", 
                             "rateparam2", "rateparam3", "selecttaxes"];
        for (var ord = 0; ord < elementsOrder.length; ord++) {
			$(("#" + elementsOrder[ord])).attr("tabindex", (ord + 1));
		}
        $('#details_product_code').attr("maxlength", "10");
        $('#details_additional_description').attr("maxlength", "255");
        $('#details_publish_up').attr("readonly", "readonly");
        $('#details_publish_down').attr("readonly", "readonly");
      //Codigo para dejar solo lectura la latitud y longitud
        $('#details_latitude').attr("readonly", "readonly");
        $('#details_longitude').attr("readonly", "readonly");
        var a = "<a>test</a>";
        $('#details_longitude').parent().append('&nbsp;<a href="javascript: void(0)" id="reset_coordinates"><?php echo JText::_("CP.PRODUCT_CLEAR_COORDINATES")?></a>');
        $("#reset_coordinates").click(function(){
        	$('#details_latitude').val("");
            $('#details_longitude').val("");
        });
        $('#details_publish_up').datepicker({
            showOn: "button",
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true,
            buttonImage: "templates/system/images/calendar.png",
            buttonImageOnly: true,
            showButtonPanel: true,
            beforeShow: function(input, inst) {
                if (beginsCalendar) {
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
                beginsCalendar = false;
                $.datepicker._updateDatepicker = old_fn;
            },
            onSelect: function (selectedDate) {
                var start = $(this).datepicker('getDate');
                $('#details_publish_down').datepicker('option', 'minDate', new Date((start.getTime() + oneDay)));
            }
        });
        $('#details_publish_down').datepicker({
            showOn: "button",
            minDate: ($('#details_publish_down').val() != '0000-00-00')? new Date($('#details_publish_up').datepicker('getDate').getTime() + oneDay): '+1',
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true,
            buttonImage: "templates/system/images/calendar.png",
            buttonImageOnly: true,
            showButtonPanel: true,
            beforeShow: function(input, inst) {
                $.datepicker._updateDatepicker = function(inst) {
                    var start = $('#details_publish_up').datepicker('getDate');
                    $(this).datepicker('option', 'minDate', new Date((start.getTime() + oneDay)));
                    old_fn.call(this, inst);
                    var buttonPane = $(this).datepicker("widget").find(".ui-datepicker-buttonpane");
                    $("<button style='float:none;text-align:center;width:100%;' class='ui-datepicker-clean ui-state-default ui-priority-primary ui-corner-all'>Nunca despublicar</button>").appendTo(buttonPane).click(function(ev) {
                        $.datepicker._clearDate(inst.input);
                        $(inst.input).val('0000-00-00');
                        $.datepicker._hideDatepicker();
                    });
                };
            }
        });

	    // Actualizar el listado de ciudades y regiones (si aplica) cuando se cambia el país
	    $('select#detailscountry_id').change(function () {
            <?php
            // Generar el combo de regiones o un campo oculto
            if ($showRegions) : ?>
            var url = "index.php?option=<?php echo $option; ?>&view=region&task=getrawlist&format=raw&country_id=" + $(this).val();
            generateSelectByAjax(url, 'detailsregion_id', 'region_id', 'region_name', '', ' - <?php echo JText::_('NONE'); ?> - ');
            <?php endif; ?> 
            var url = "index.php?option=<?php echo $option; ?>&view=city&task=getrawlist&format=raw&country_id=" + $(this).val();
            generateSelectByAjax(url, 'detailscity_id', 'city_id', 'city_name', '', ' - <?php echo JText::_('CP.SELECT_A_CITY'); ?> - ');
	    });

	    // Actualizar el listado de ciudades cuando se cambia la región
	    $('select#detailsregion_id').change(function () {
            var url = "index.php?option=<?php echo $option; ?>&view=city&task=getrawlist&format=raw&country_id=" + $('select#detailscountry_id').val() + "&region_id=" + $(this).val();
            generateSelectByAjax(url, 'detailscity_id', 'city_id', 'city_name', '', ' - <?php echo JText::_('CP.SELECT_A_CITY'); ?> - ');
	    });

	    // Label de la opción vacía en "Suplementos Disponibles"
	    var emptySelectOption = "<?php echo $this->emptySelectOption; ?>";

        // Actualizar el listado de suplementos disponibles para asignar
        $("#supplement_product_types,#supplement_tourism_types").change(function() {
            $("#supplement_search_message").html('');
            var url = "index.php?option=<?php echo $option; ?>&view=supplement&task=getrawlist&format=raw&published=1&tourismtype_id=" + $('select#supplement_tourism_types').val() + "&product_type_id=" + $('select#supplement_product_types').val();
            generateSelectByAjax(url, 'supplement_list', 'supplement_id', 'supplement_name', '0', emptySelectOption, assignedSupplementList, "supplement_search_message", "<?php echo JText::_('CP.TRANSFER_SUPPLEMENT_SEARCH_NO_RESULTS_MESSAGE', true); ?>");
        });
        $("#supplement_product_types").change();

        // Asignar un suplemento al producto
        $("#assign_supplements").click(function() {
        	var supplement_id = $('select#supplement_list').val();
        	if (supplement_id > 0) {
                $("#supplement_search_message").html('');
            	addSupplement(supplement_id);
        	}
        });

        // Agregar impuesto al producto
        $("body").delegate("img.addtax", "click", function() {
        	var layer = $(this).closest("div.taxescontent");
            var field = layer.find("select");
            var selected = parseInt(field.val(), 10);
            if (selected > 0) {
                var selectedOption = field.find("option:selected");
                // Hacer que el panel de impuestos se pueda extender o encoger a medida que se agregar/eliminan
                $('#taxes-page ~ div.jpane-slider').css('height', 'auto');
                var hiddenFieldName = field.attr("name").substring(6); // eliminar la palabra select al principio del combo

                // Agregar SÓLO si el impuesto no se haya relacionado al producto.
                layer.find('table:not(:has(input[value="' + selected + '"]))').append('<tr><td><input type="hidden" name="' + hiddenFieldName + '[]" id="' + hiddenFieldName + '[]" value="' + selected + '" /><span>' + selectedOption.text() + '</span></td><td><img class="deletetax" border="0" alt="<?php echo JText::_('CP.DELETE'); ?>" src="<?php echo COM_CATALOGO_PLANES_IMAGESDIR; ?>/cancel.png" /></td></tr>');
                // Eliminar de la lista de impuestos.
                selectedOption.remove();

                // Seleccionar el valor por defecto
                field.val('0');
            }
        });

        // Eliminar impuesto del producto
        $("body").delegate("img.deletetax", "click", function() {
            var row = $(this).closest("tr");
            var selected = parseInt(row.find(":input").val(), 10);
            if (selected > 0) {
                // Nombre del impuesto
                var text = row.find("span").html();
                var field = $(this).closest("div.taxescontent").find("select");

                // Agregar el impuesto a la lista.
                field.append('<option value="' + selected + '">' + text + '</option>');

                // Eliminar de la fila de impuestos.
                row.remove();

                // Hacer que el panel de impuestos se pueda extender o encoger a medida que se agregar/eliminan
                $('#taxes-page ~ div.jpane-slider').css('height', 'auto');

                // Ordenar la lista por nombre
                field.html($("option", field).sort(function(a, b) {
                    if (a.value == "0") return -1;
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));

                // Seleccionar el valor por defecto
                field.val('0');
            }
        });

        // Eliminar suplemento del producto
        $("body").delegate(".deletesupplement", "click", function() {
            var row = $(this).closest("div.supplement_info");
            // Elimina el ID del suplemento del listado de los asignados
            var supplement_id = row.find('input[name$="[supplement_id]"]').val();
            if (supplement_id > 0) {
                var pos = assignedSupplementList.indexOf(supplement_id);
                if (pos >= 0) {
                    assignedSupplementList.splice(pos, 1);
                }
            }

            // Eliminar la fila de suplementos.
            row.remove();

            // Actualizar el filtro de Suplementos Disponibles
            $("#supplement_product_types").change();
        });
	});

Joomla.submitbutton = function(pressbutton) {
        var form = document.adminForm;

        if (pressbutton == 'cancel') {
            submitform(pressbutton);
            return;
        }

        // Limpiar los
        jQuery('#errorMessage').html("");

        // do field validation
        if (jQuery.trim(form.product_name.value) == "") {
            alert("<?php echo JText::_('CP.PRODUCT_ERROR_EMPTY_NAME', true); ?>");
            form.product_name.focus();
        } else if (form.detailscountry_id.selectedIndex == 0) {
            alert("<?php echo JText::_('CP.PRODUCT_ERROR_EMPTY_COUNTRY', true); ?>");
            form.detailscountry_id.focus();
        } else if (form.detailscity_id.selectedIndex == 0) {
            alert("<?php echo JText::_('CP.PRODUCT_ERROR_EMPTY_CITY', true); ?>");
            form.detailscity_id.focus();
        } else if (jQuery.trim(jQuery("#details_product_desc").val()) == "") {
            alert("<?php echo JText::_('CP.PRODUCT_ERROR_EMPTY_SHORT_DESCRIPTION', true); ?>");
            jQuery("#details_product_desc").focus();
        } else if (jQuery.trim(jQuery("#details_product_code").val()) == "") {
            alert("<?php echo JText::_('CP.PRODUCT_ERROR_EMPTY_CODE', true); ?>");
            jQuery("#details_product_code").focus();
        } else if (form.detailssupplier_id.selectedIndex == 0) {
            alert("<?php echo JText::_('CP.PRODUCT_ERROR_EMPTY_SUPPLIER', true); ?>");
            form.detailssupplier_id.focus();
        } else { //alert('value='+jQuery( "#detailscategories" ).val()+' leng='+form['details[categories][][]'].length);
            // Validar que se seleccione al menos un valor por cada parámetro de tarificación
            var submit = false;
            for (var i = 0; i < form['details[categories][][]'].length; i++) {
                alert(i+' selected='+form['details[categories][][]'][i].selected)
                if (form['details[categories][][]'][i].selected) {
                   
                    submit = true;
                    break;
                }
            }
            if (!submit) {
                alert("<?php echo JText::_('CP.TRANSFER_ERROR_EMPTY_CATEGORY', true); ?>");
                return;
            }

            var endDateField = document.getElementById('details_publish_down');
            if (endDateField.value != '0000-00-00') {
                var startDateField = document.getElementById('details_publish_up');
                var publish_date = startDateField.value.split('-');
                var startDate = new Date(publish_date[0], (parseInt(publish_date[1], 10) - 1), (parseInt(publish_date[2], 10)));
                var endDate = new Date(endDateField.value);
                if (startDate >= endDate) {
                    alert("<?php echo JText::_('CP.PRODUCT_ERROR_PUBLISHING_RANGE_NOT_VALID', true); ?>");
                    endDateField.focus();
                    return;
                }
            }

            // Validar que se seleccione al menos un valor por cada parámetro de tarificación
            var selectedParams2 = jQuery.grep(rangeArray, function(item, index) {
                return (jQuery('#rateparam2 option[value="' + item.id + '"]').filter(':selected').length);
            });
            if (selectedParams2.length < 1) {
                alert("<?php echo JText::_('CP.TRANSFER_ERROR_EMPTY_PARAM2', true); ?>");
                return;
            }
            // Validar no solapamiento de rango de personas
            var i = j = 0, min, max, min2, max2, resultwarning = "";
            submit = true;
            var l = selectedParams2.length;
            for (i = 0; i < l; i++) {
                min = selectedParams2[i].min;
                max = selectedParams2[i].max;
            	for (j = i + 1; j < l; j++) {
                    min2 = selectedParams2[j].min;
                    max2 = selectedParams2[j].max;
                	if ((min >= min2 && min <= max2) || (max >= min2 && max <= max2)) {
                		submit = false;
                		resultwarning += "<br />\"" + selectedParams2[i].name + "\" <?php echo JText::_('CP.AND', true); ?> \"" + selectedParams2[j].name + "\"";
                	}
            	}
            }
            if (!submit) {
            	jQuery('#errorMessage').html("<?php echo JText::_('CP.TRANSFER_RATE_ERROR_PARAM2_OVERLAPPING', true); ?>" + resultwarning);
                return;
            }

            submit = false;
            for (var i = 0; i < form['rateparam1[]'].length; i++) {
                if (form['rateparam1[]'][i].selected) {
                    submit = true;
                    break;
                }
            }
            if (!submit) {
                alert("<?php echo JText::_('CP.TRANSFER_ERROR_EMPTY_PARAM1', true); ?>");
                return;
            }
            submit = false;
            for (var i = 0; i < form['rateparam3[]'].length; i++) {
                if (form['rateparam3[]'][i].selected) {
                    submit = true;
                    break;
                }
            }
            if (!submit) {
                alert("<?php echo JText::_('CP.TRANSFER_ERROR_EMPTY_PARAM3', true); ?>");
                return;
            }

            submitform(pressbutton);
        }
    }

</script>

<div id="errorMessage"></div>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<?php
echo $myTabs->startPane('productInfo');
echo $myTabs->startPanel(JText::_('CP.BASIC_PARAMETERS'), 'productDetails');
?>
    <table border="0">
        <tr>
            <td valign="top">
                <table border="0">
                    <tr>
                        <td align="right" class="key2">
                            <label for="product_name">
                                <?php echo JText::_('CP.TRANSFER_NAME'); ?>*
                            </label>
                        </td>
                        <td colspan="7">
                            <input class="text_area" type="text" name="product_name" id="product_name" size="85" maxlength="255" value="<?php echo htmlspecialchars($data->product_name, ENT_COMPAT, 'UTF-8');?>" />
                        </td>
                    </tr>
                    <tr>
                        <td align="left" class="key2">
                            <label for="country_id">
                                <?php echo JText::_('CP.COUNTRY'); ?>*
                            </label>
                        </td>
                        <td>
                            <?php echo $data->countries; ?>
                        <?php 
                        // Generar el combo de regiones o un campo oculto
                        if ($showRegions) : ?>
                        </td>
                        <td align="left" class="key2">
                            <label for="detailsregion_id">
                                <?php echo JText::_('CP.REGION'); ?>
                            </label>
                        </td>
                        <td>
                            <?php echo $data->regions; ?>
                        <?php else: ?>
                            <input type="hidden" name="detailsregion_id" id="detailsregion_id" value="<?php echo $data->region_id; ?>" />
                        <?php endif; ?>
                        </td>
                        <td align="right" class="key2">
                            <label for="city">
                                <?php echo JText::_('CP.CITY'); ?>*
                            </label>
                        </td>
                        <td id="citylist">
                            <?php echo $data->cities; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8">
                            <div id="map_canvas" style="width: 100%; padding: 0px; height: 650px"></div>
                        </td>
                    </tr>
                </table>
            </td>
            <td valign="top">
                <?php
                // Se inician los paneles con posibilidad de encogerse
                $pane = &JPane::getInstance('sliders', array('allowAllClose' => true));

                // Se inicia el panel de parámetros básicos
                $content = $pane->startPane("basic-pane");
                $content .= $pane->startPanel(JText::_('CP.BASIC_PARAMETERS'), "basic-page");
                foreach($this->form->getFieldset() as $field){
                    $content .=  '<div class="cp-item">'.$field->label.$field->input.'</div>';
                }
                $content .= $pane->endPanel();

                // Se inicia el panel de parámetros de tarificación
                $content .= $pane->startPanel(JText::_('CP.RATING_PARAMETERS'), "rate-page");
                if ($data->product_id > 0) {
                    $content .= '<div id="system-message"><div class="notice"><ul><li>' . 
                        JText::_('CP.PRODUCT_RATE_PARAMS_EDIT_WARNING') . '</li></ul></div></div>';
                }
                $content .= '<table id="rate-table" width="100%" class="paramlist admintable" cellspacing="1">';
                // Colocarle asterisco a los parámetros inactivos
                foreach ($this->rateparams1 as $key => $row) {
                    if ($row->published != 1) {
                        $this->rateparams1[$key]->param1_name .= '*';
                    }
                }
                $rangeScript = "var rangeArray = new Array();\n";
                foreach ($this->rateparams2 as $key => $row) {
                    if ($row->published != 1) {
                        $this->rateparams2[$key]->param2_name .= '*';
                    }
                    $rangeScript .= "rangeArray.push({id: " . $row->param2_id . ", name: \"" . $row->param2_name . "\", min: " . $row->capacity . ", max: " . $row->value . "});\n";
                }
                $document->addScriptDeclaration($rangeScript);
                foreach ($this->rateparams3 as $key => $row) {
                    if ($row->published != 1) {
                        $this->rateparams3[$key]->param3_name .= '*';
                    }
                }
                $content .= '<tr><td class="paramlist_key"><span class="editlinktip">' . JText::_('CP.TRANSFERPARAM2') . '*</span></td>';
                $content .= '<td class="paramlist_value">';
                $content .= JHTML::_('select.genericlist', $this->rateparams2, 'rateparam2[]', 'class="multiselect" size="' . (count($this->rateparams2) + 1) . '" multiple="multiple"', 'param2_id', 'param2_name', $data->rateparam2);
                $content .= '</td></tr>';
                $content .= '<tr><td class="paramlist_key"><span class="editlinktip">' . JText::_('CP.TRANSFERPARAM1') . '*</span></td>';
                $content .= '<td class="paramlist_value">';
                $content .= JHTML::_('select.genericlist', $this->rateparams1, 'rateparam1[]', 'class="multiselect" size="' . (count($this->rateparams1) + 1) . '" multiple="multiple"', 'param1_id', 'param1_name', $data->rateparam1);
                $content .= '</td></tr>';
                $content .= '<tr><td class="paramlist_key"><span class="editlinktip">' . JText::_('CP.TRANSFERPARAM3') . '*</span></td>';
                $content .= '<td class="paramlist_value">';
                $content .= JHTML::_('select.genericlist', $this->rateparams3, 'rateparam3[]', 'class="multiselect" size="' . (count($this->rateparams3) + 1) . '" multiple="multiple"', 'param3_id', 'param3_name', $data->rateparam3);
                $content .= '</td></tr>';
                $content .= '</table>';
                $content .= $pane->endPanel();

                // Se inicia el panel de impuestos
                $selectedTaxes = '';
                $content .= $pane->startPanel(JText::_('CP.TAXES'), "taxes-page");
                $selectedTaxesList = array();
                // Generar listado de impuestos ya asignados
                foreach ($data->taxes as $key => $row) {
                	$selectedTaxesList[] = $row->tax_id;
                    // El nombre del impuesto debe contener asterisco (si está inactivo) y el valor del impuesto en porcentaje
                    $row->tax_name .= (($row->published != 1)? '*&nbsp;': '&nbsp;') . ($row->tax_value * 100) . '%';
                    $selectedTaxes .= '<tr><td><input type="hidden" name="taxes[]" id="taxes[]" value="' . $row->tax_id . '" /><span>' . $row->tax_name . '</span></td><td>' . $deleteTaxImg . '</td></tr>';
                }
                // Filtrar los impuestos que ya están asignados y generar lista seleccionable de los que no
                foreach ($taxSelectOptions as $key => $row) {
                	if (in_array($row->tax_id, $selectedTaxesList)) {
                		unset($taxSelectOptions[$key]);
                	}
                }
                $content .= '<div class="taxescontent"><table border="0" cellspacing="5"><tr><td>';
                $content .= JHTML::_('select.genericlist', $taxSelectOptions, 'selecttaxes', 'class="inputbox" size="1"', 'tax_id', 'tax_name');
                $content .= $addTaxImg . '</td></tr>' . $selectedTaxes . '</table></div>' . $pane->endPanel();

                // Fin de los paneles
                $content .= $pane->endPane();
                echo $content;
                ?>
            </td>
        </tr>
    </table>
<?php
echo $myTabs->endPanel();
echo $myTabs->startPanel(JText::_('CP.DETAILSPANEL'), 'productMedia');
?>
        <div align="left">
        <table align="center">
            <tr>
                <td align="left">
                    <label for="tag_name1">
                        <?php echo JText::_('CP.PRODUCT_TAG1_LABEL'); ?>
                    </label>
                </td>
                <td align="left">
                    <input class="text_area" type="text" name="tag_name1" id="tag_name1" size="60" maxlength="255" value="<?php echo $data->tag_name1; ?>" />
                </td>
                <td rowspan="6" width="50%" valign="top">
                    <div id="menu-pane" class="pane-sliders">
                    <div class="panel">
                        <h3 class="title"><?php echo JText::_('CP.PRODUCT_IMAGES'); ?></h3>
                        <div style="padding: 10px;">
                            <a rel="{handler: 'iframe', size: {x: 570, y: 450}}" href="<?php echo JURI::base(); ?>index.php?option=com_media&view=images&tmpl=component&e_name=imageurl&noeditor=1" class="modal-button"><img src="<?php echo JURI::base(); ?>components/com_media/images/folderup_32.png" /><br /><?php echo JText::_('CP.PRODUCT_IMAGES_DESC'); ?></a>
                            <?php echo JText::_('CP.PRODUCT_IMAGES_UPLOAD_DESC'); ?>
                            <ul id="galleryContainer" class="sortable">
                            <?php
                                if (is_array($data->mediafiles)) {
                                    $i = 0;
                                    foreach ($data->mediafiles as $image) {
                                        if (stristr($image->file_url, 'http://') === false && stristr($image->file_url, 'https://') === false) {
                                            $visibleURL = $siteURL . $image->file_url;
                                        } else {
                                            $visibleURL = $image->file_url;
                                        }
                                        echo '<li id="imgPos' . $i . '"><img src="' . $visibleURL . '" border="0" width="180" height="120" />
                                        <br /><a class="delImage" href="javascript:deleteImg(' . $i . ')">
                                        <img src="' . COM_CATALOGO_PLANES_IMAGESDIR . '/trashcan.png" border="0" width="16" height="16" /> 
                                        ' . JText::_('CP.DELETE') . '</a><input type="hidden" name="mediafiles[]" value="' . $image->file_url . '" /><br /></li>';
                                        $i++;
                                    }
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php
                        echo $editor->display('tag_content1', $data->tag_content1, '550', '300', '60', '20', true);
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td align="left">
                    <label for="tag_name2">
                        <?php echo JText::_('CP.PRODUCT_TAG2_LABEL'); ?>
                    </label>
                </td>
                <td align="left">
                    <input class="text_area" type="text" name="tag_name2" id="tag_name2" size="60" maxlength="255" value="<?php echo $data->tag_name2; ?>" />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php
                        echo $editor->display('tag_content2', $data->tag_content2, '550', '300', '60', '20', true);
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td align="left">
                    <label for="tag_name3">
                        <?php echo JText::_('CP.PRODUCT_TAG3_LABEL'); ?>
                    </label>
                </td>
                <td align="left">
                    <input class="text_area" type="text" name="tag_name3" id="tag_name3" size="60" maxlength="255" value="<?php echo $data->tag_name3; ?>" />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php
                        echo $editor->display('tag_content3', $data->tag_content3, '550', '300', '60', '20', true);
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td align="left">
                    <label for="tag_name4">
                        <?php echo JText::_('CP.PRODUCT_TAG4_LABEL'); ?>
                    </label>
                </td>
                <td align="left">
                    <input class="text_area" type="text" name="tag_name4" id="tag_name4" size="60" maxlength="255" value="<?php echo $data->tag_name4; ?>" />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php
                        echo $editor->display('tag_content4', $data->tag_content4, '550', '300', '60', '20', true);
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td align="left">
                    <label for="tag_name5"><br />
                        <?php echo JText::_('CP.PRODUCT_TAG5_LABEL'); ?>
                    </label>
                </td>
                <td align="left">
                    <input class="text_area" type="text" name="tag_name5" id="tag_name5" size="60" maxlength="255" value="<?php echo $data->tag_name5; ?>" />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php
                        echo $editor->display('tag_content5', $data->tag_content5, '550', '300', '60', '20', true);
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td align="left">
                    <label for="tag_name6">
                        <?php echo JText::_('CP.PRODUCT_TAG6_LABEL'); ?>
                    </label>
                </td>
                <td align="left">
                    <input class="text_area" type="text" name="tag_name6" id="tag_name6" size="60" maxlength="255" value="<?php echo $data->tag_name6; ?>" />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php
                        echo $editor->display('tag_content6', $data->tag_content6, '550', '300', '60', '20', true);
                    ?>
                </td>
            </tr>
        </table>
        </div>
<?php
echo $myTabs->endPanel();
echo $myTabs->startPanel(JText::_('CP.SUPPLEMENTS'), 'productSupplements');

// Si hay suplementos creados, debe cargarse su información en div's ocultos
$content = '';
if (count($data->supplements)) {
	foreach ($data->supplements as $key => $row) {
		$id = $row->supplement_id;
		$supplement_taxes = '';
        array_splice($taxSelectOptions, 1);
		foreach ($row->taxes as $keyTax => $rowTax) {
			$supplement_taxes .= '<tr><td><input type="hidden" name="supplement_taxes[' . $id . '][]" id="supplement_taxes[' . $id . '][]" value="' . $rowTax->tax_id . '" /><span>' . $rowTax->tax_name . '</span></td><td>' . $deleteTaxImg . '</td></tr>';
		}
		foreach ($this->taxes as $keyTax => $rowTax) {
			// El nombre del impuesto debe contener asterisco (si está inactivo) y el valor del impuesto en porcentaje
			if (!isset($row->taxes[$rowTax->tax_id])) {
				array_push($taxSelectOptions, $rowTax);
			}
		}
		if ($row->imageurl) {
			$imageurl = '<img src="' . $siteURL . $row->imageurl . '" name="imagelib' . $id . '" class="supplement_image" />';
		} else {
			$imageurl = '';
		}
        $content .= '<div class="supplement_info"><hr />
          <table border="0" cellpadding="5">
              <tr>
                  <td><table border="0" cellpadding="5"><tr><td class="supplement_label_cell"><input type="hidden" name="supplements[' . $id . '][supplement_id]" value="' . $id . '" /><label for="supplement_name' . $id . '">' . $nameLabel . '</label></td>
                  <td class="supplement_text_cell">' . $row->supplement_name . '</td></tr>
                  <tr>
                      <td><label for="supplement_description' . $id . '">' . $descriptionLabel . '</label></td>
                      <td class="supplement_text_cell">' . $row->description . '</td>
                  </tr></table></td>
                  <td class="supplement_image_cell">' . $imageurl . '</td>
                  <td><fieldset><legend>' . $taxesLabel . '</legend><div class="taxescontent"><table border="0" cellspacing="5"><tr><td>
                  ' . JHTML::_('select.genericlist', $taxSelectOptions, 'selectsupplement_taxes[' . $id . ']', 'class="inputbox supplement_taxes" size="1"', 'tax_id', 'tax_name') . 
                  $addTaxImg . '</td></tr>' . $supplement_taxes . '</table></div></fieldset><br /><div align="right">' . $deleteSupplementLabel . '</div></td>
              </tr>
          </table>
        </div>';
	}
}
?>
        <fieldset>
            <legend><?php echo JText::_('CP.TRANSFER_SUPPLEMENT_SEARCH_LABEL'); ?></legend>
			<table border="0" cellpadding="5" cellspacing="1">
				<tbody>
					<tr>
						<td><label for="supplement_product_types"><?php echo JText::_('CP.PRODUCT_TYPE'); ?></label></td>
						<td><?php echo JHTML::_('select.genericlist', $this->productTypes, 'supplement_product_types', 'class="inputbox"', 'product_type_id', 'product_type_name'); ?></td>
                        <td><label for="supplement_product_tourism_types"><?php echo JText::_('CP.TOURISM_TYPE'); ?></label></td>
                        <td><?php echo JHTML::_('select.genericlist', $this->tourismTypes, 'supplement_tourism_types', 'class="inputbox"', 'tourismtype_id', 'tourismtype_name'); ?></td>
                        <td><label for="supplement_list"><?php echo JText::_('CP.PRODUCT_AVAILABLE_SUPPLEMENTS_LABEL'); ?></label></td>
                        <td><?php echo JHTML::_('select.genericlist', array(JHTML::_('select.option', '0', $this->emptySelectOption)), 'supplement_list', 'class="inputbox"'); ?></td>
                        <td><input id="assign_supplements" type="button" value="<?php echo JText::_('CP.ASSIGN'); ?>" /></td>
                    </tr>
                    <tr>
                        <td colspan="7" id="supplement_search_message">
                        </td>
                    </tr>
					<tr>
                        <td colspan="7">
	                        <a rel="{handler: 'iframe', size: {x: 570, y: 550}}" href="<?php echo JURI::base(); ?>index.php?option=<?php echo $option; ?>&view=supplement&task=edit&tmpl=component" class="modal button_link">
	                           <?php echo JText::_('CP.PRODUCT_ADD_SUPPLEMENT_LABEL'); ?>
	                        </a>
                        </td>
					</tr>
				</tbody>
			</table>
		</fieldset><h2><?php echo JText::_('CP.PRODUCT_RELATED_SUPPLEMENTS_LABEL'); ?></h2>
        <div align="left" id="supplements_tab">
        <?php echo $content; ?>
        </div>
<?php
echo $myTabs->endPanel();
echo $myTabs->endPane();
?>

	<input type="hidden" name="view" value="<?php echo $this->viewName; ?>" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="product_id" id="product_id" value="<?php echo $data->product_id; ?>" />
    <input type="hidden" name="ordering" value="<?php echo $data->ordering; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="delMedia" value="" />
    <?php echo JHTML::_('form.token'); ?>
</form>
