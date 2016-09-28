
var jCat = jQuery.noConflict();

function isUserEmail (value) {
	// contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
	return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(value);
}

function generateSelectByAjax (url, resultFieldId, resultKey, resultValue, defaultKey, defaultValue, ignoreArray, noResultElement, noResultMessage) {
	if (!jCat.isArray(ignoreArray)) {
		ignoreArray = new Array();
	}
	jCat.getJSON(url, function (data) {
		var items = [];
		items.push('<option value="' + defaultKey + '">' + defaultValue + '</option>');
		jCat.each(data, function (key, val) {
			//console.log('resultKey: ' + val[resultKey] + ', resultValue: ' + val[resultValue] + ', resultado: ' + jCat.inArray(val[resultKey], ignoreArray));
			if (jCat.inArray(val[resultKey], ignoreArray) < 0) {
				items.push('<option value="' + val[resultKey] + '">' + val[resultValue] + '</option>');
			}
		});
		if (noResultElement && noResultMessage && data.length < 1) {
			jCat('#' + noResultElement).html(noResultMessage);
		} 
		jCat('#' + resultFieldId).html(items.join("\n"));
	});
}


jCat(document).ready(function($) {
    /*Evita el doble click
    isNotDoubleClick = function () {
        if (typeof (_linkEnabled) == "undefined") _linkEnabled = true;
        setTimeout("blockClick()", 100);
        return _linkEnabled;
    }
    blockClick = function () {
        _linkEnabled = false;
        setTimeout("_linkEnabled=true", 1000);
    }	
    jQuery('.button a.toolbar').each(function( index ) {
    	//alert($(this).text());
    	var newOnclick = jQuery(this).attr('onclick');
    	//alert(jQuery(this).attr('onclick'));
    	jQuery(this).attr('onclick',newOnclick.replace(/submitbutton/i, "if (isNotDoubleClick()) Joomla.submitbutton = function "));

    });*
    /*fin de funciones para evitar el doble click*/
    
    jCat.ajaxSetup({ cache: false });
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

    // Para colocarle el asterisco a los parámetros básicos obligatorios
    $(".requiredfield").each(function() {
	    var fieldid = '#' + $(this).attr('id') + '-lbl'; 
		$(fieldid).html($(fieldid).html() + '*');
    });

    // Lo que debe pasar cuando se da click en el botón de "Cargar Imagen"
    $("#menu-pane a.modal-button").click(function() {
	    registerInsertAction('adminForm');
	    return false;
    });

    $("#galleryContainer").sortable();
    $("#galleryContainer").disableSelection();

    $('#basic-pane input:text').attr("maxlength", "100");
});
function registerInsertAction(id) {
	if (!jCat("#sbox-window").is(':visible')) {
		setTimeout(function(){registerInsertAction(id);}, 200); //wait
		return;
	}
	var f = jCat('#sbox-window iframe');

	if (f[0] === undefined) {
            setTimeout(function(){registerInsertAction(id);}, 200); //wait
            return;
	} else {
            f.load(function(){//wait
                    var imageManager = f[0].contentWindow.ImageManager;
                    imageManager.onok = function() {
                            setMedia(imageManager.fields.url.value, id);
                    };
            });
	}
}

function deleteImg(pos) {
	jCat('#imgPos'+pos).remove();
}

function setMedia(url, id) {
	if (url.length > 0) {
		if (url.indexOf('http://') < 0 && url.indexOf('https://') < 0) {
			visibleURL = siteURL + url;
		} else {
			visibleURL = url;
		}
		jCat('#galleryContainer').append('<li id="imgPos' + mediaCount + '"><img src="' + visibleURL + '" border="0" width="180" height="120" /><br /><a class="delImage" href="javascript:deleteImg(' + mediaCount + ')"><img src="' + catalogueImagesURL + '/trashcan.png" border="0" width="16" height="16" /> ' + delText + '</a><input type="hidden" name="mediafiles[]" value="' + url + '" /></li>');
	   mediaCount++;
	}
}
/**
 * Obtiene el numero de dias entre dos fechas
 * @param date1
 * @param date2
 * @returns
 */
function daysBetween(date1, date2){   
   var one_day = 1000*60*60*24;
   var daysApart = Math.abs(Math.ceil((date1.getTime()-date2.getTime())/one_day));
   return daysApart;
}

/**
 * Retorna la fecha con formato Date
 * @param date1
 * @returns
 */
function getDateFormat(date1){
	if (date1.indexOf("-") != -1) { date1 = date1.split("-"); } else if (date1.indexOf("/") != -1) { date1 = date1.split("/"); } else { return 0; }
	if (parseInt(date1[0], 10) >= 1000) {		
       var sDate = new Date(date1[0]+"/"+date1[1]+"/"+date1[2]);
   } else if (parseInt(date1[2], 10) >= 1000) {
       var sDate = new Date(date1[2]+"/"+date1[0]+"/"+date1[1]);
   } else {
       return 0;
   }
	return sDate;
}

/******************************** funciones para agregar dias a una fecha****************/
var aFinMes = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

function finMes(nMes, nAno){
 return aFinMes[nMes - 1] + (((nMes == 2) && (nAno % 4) == 0)? 1: 0);
}

 function padNmb(nStr, nLen, sChr){
  var sRes = String(nStr);
  for (var i = 0; i < nLen - String(nStr).length; i++)
   sRes = sChr + sRes;
  return sRes;
 }

 function makeDateFormat(nDay, nMonth, nYear){
  var sRes;
  sRes = padNmb(nYear, 4, "0") + "-" + padNmb(nMonth, 2, "0") + "-" + padNmb(nDay, 2, "0");
  return sRes;
 }

function incDate(sFec0){
 var nAno = parseInt(sFec0.substr(0, 4), 10);
 var nMes = parseInt(sFec0.substr(5, 2), 10);
 var nDia = parseInt(sFec0.substr(8, 2), 10); 
 nDia += 1;
 if (nDia > finMes(nMes, nAno)){
  nDia = 1;
  nMes += 1;
  if (nMes == 13){
   nMes = 1;
   nAno += 1;
  }
 }
 return makeDateFormat(nDia, nMes, nAno);
}

function decDate(sFec0){
 var nAno = Number(sFec0.substr(0, 4));
 var nMes = Number(sFec0.substr(5, 2));
 var nDia = Number(sFec0.substr(8, 2));
 nDia -= 1;
 if (nDia == 0){
  nMes -= 1;
  if (nMes == 0){
   nMes = 12;
   nAno -= 1;
  }
  nDia = finMes(nMes, nAno);
 }
 return makeDateFormat(nDia, nMes, nAno);
}

function addToDate(sFec0, sInc){
 var nInc = Math.abs(parseInt(sInc));
 var sRes = sFec0;
 if (parseInt(sInc) >= 0)
  for (var i = 0; i < nInc; i++) sRes = incDate(sRes);
 else
  for (var i = 0; i < nInc; i++) sRes = decDate(sRes);
 return sRes;
}

function recalcF1(){
 with (document.formulario){
  fecha1.value = addToDate(fecha0.value, increm.value);
 }
}