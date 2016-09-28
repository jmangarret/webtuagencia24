jQuery(document).ready(function($) {
    $(".requiredfield").each(function() {
	    var fieldid = '#' + $(this).attr('id') + '-lbl'; 
		$(fieldid).html($(fieldid).html() + '*');
    });

    $("#jform_country_code").change(function() {
	    $.ajax({
	        type: "POST",
	        url: "index.php?option=com_cp&view=cpproducts&task=getCities&format=raw&tmpl=component",
	        data: 'country_code=' + $(this).val(),
	        success: function(data) {
				$('#citylist').html(data);
	        }
	    });
    });

    $("#menu-pane a.modal-button").click(function() {
	    return false;
    });

	$("#galleryContainer").sortable();
	$("#galleryContainer").disableSelection();

	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		weekHeader: 'Sm',
		dateFormat: 'yy-mm-dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
	};
	$.datepicker.setDefaults($.datepicker.regional['es']);
	var oneDay = 24*60*60*1000;
	var old_fn = $.datepicker._updateDatepicker;
    // The product must be publicated at least one day.
	var publish_date = $('#jform_publish_up').val().split('-');
    var min_publish_date = new Date(publish_date[0], (parseInt(publish_date[1]) - 1), (parseInt(publish_date[2]) + 1));
    var today = new Date();
    if ($('#jform_publish_up').val() != '0000-00-00' && min_publish_date <= today) {
    	publish_up = min_publish_date;
    } else {
    	publish_up = today;
    }

	$('#jform_publish_up').datepicker({
		showOn: "button",
		dateFormat: 'yy-mm-dd',
		changeYear: true,
		changeMonth: true,
		minDate: publish_up,
		buttonImage: 'components/com_cp/assets/css/smoothness/images/ico_calendar.jpg',
		buttonImageOnly: true,
		showButtonPanel: true,
		beforeShow: function( input, inst ) {
			$.datepicker._updateDatepicker = old_fn;
		},
	    onSelect: function (selectedDate){
	        var value = $('#jform_publish_down').val();
	        var start = $(this).datepicker('getDate');
	        $('#jform_publish_down').datepicker('option', 'minDate', new Date((start.getTime() + oneDay)));
        	$('#jform_publish_down').val(value);
	    }
	});
	$('#jform_publish_down').datepicker({
		showOn: "button",
		minDate: min_publish_date,
		dateFormat: 'yy-mm-dd',
		changeYear: true,
		changeMonth: true,
		buttonImage: 'components/com_cp/assets/css/smoothness/images/ico_calendar.jpg',
		buttonImageOnly: true,
		showButtonPanel: true,
		beforeShow: function( input, inst ) {
			$.datepicker._updateDatepicker = function(inst) {
				old_fn.call(this, inst);
				var buttonPane = $(this).datepicker("widget").find(".ui-datepicker-buttonpane");
				$("<button style='float:none;text-align:center;width:100%;' class='ui-datepicker-clean ui-state-default ui-priority-primary ui-corner-all'>Nunca despublicar</button>").appendTo(buttonPane).click(function(ev) {
					$.datepicker._clearDate(inst.input);
					$(inst.input).val('0000-00-00');
					$.datepicker._hideDatepicker();
				});
			}
      	}
	});
});

var oldImageURL = '';
function jInsertFieldValue(value, id) {
	var old_value = jQuery("#" + id).value;
	if (old_value != value) {
		var elem = jQuery("#" + id);
		elem.value = value;
		setMedia(value);
	}
}

function setMedia(url) {
	if (url.length > 0) {
	   jQuery('#galleryContainer').append('<li id="imgPos' + mediaCount + '"><img src="' + siteURL + url + '" border="0" width="180" height="120" /><br /><a class="delImage" href="javascript:deleteImg(' + mediaCount + ')">' + delText + '</a><input type="hidden" name="mediafiles[]" value="' + url + '" /></li>');
	   mediaCount++;
	}
}

function deleteImg(pos) {
   jQuery('#imgPos'+pos).remove();
}
