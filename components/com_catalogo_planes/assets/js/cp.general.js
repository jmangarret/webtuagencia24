//Para que no genere conflicto el jquery
jQuery.noConflict();
/***********************************************MAPA*****************************************/
var infowindow;
var geocoder;
var map;
var latlng;
var marker;
var geocoder;
var infowindow;
var map;

/**
 * Establece el mapa 
 */
function showMap(mapId, lat, Lng){	
	latlng = new google.maps.LatLng(lat, Lng);
	infowindow = new google.maps.InfoWindow();
    geocoder = new google.maps.Geocoder();
    var myOptions = {
      zoom: 12,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      scrollwheel: false,
      zoomControl: true,
      mapTypeControl: true

    };

    map = new google.maps.Map(document.getElementById(mapId), myOptions); 
    
    marker = new google.maps.Marker({
		map: map
	});
    placeMarker(latlng);
}

/**
 * Establece el mapa 
 */
function showMapAvailability(mapId, latLng){
	var partsLatLng = latLng.split(":");
	showMap(mapId, partsLatLng[0], partsLatLng[1])	
}

/**
 * Genera la marca en el mapa
 * @param location
 */
function placeMarker(location) {
	infowindow.setContent();
    geocoder.geocode({"latLng": location}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (results[0]) {
				marker.setPosition(location);
				infowindow.setContent("<b>"+addressText+" </b>" + results[0].formatted_address + "<br />");
				infowindow.open(map, marker);
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.open(map, marker);
			     });
	        }
		}
	});
}

/********************************  Mapa google  *******************************/

function showMapUrl(mapId, url, width, height){	
	var iframe = jQuery("<iframe>",{
		src:url,
		width: width,
		height: height,
		frameborder:0
	});
	jQuery("#"+mapId).html("");
	jQuery("#"+mapId).append(iframe);
}

/************************************* General Scripts ***************************/
var decSep = "";
var milSep = "";
var numDec = "";
var orderCurrency = "";
var currency = "";

//Set the format of the numbers
function number_format( number, decimals, dec_point, thousands_sep ) {
    // http://kevin.vanzonneveld.net
    // + original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // + improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // + bugfix by: Michael White (http://crestidg.com)
    // + bugfix by: Benjamin Lupton
    // + bugfix by: Allan Jensen (http://www.winternet.no)
    // + revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // * example 1: number_format(1234.5678, 2, '.', '');
    // * returns 1: 1234.57
	/*
	 * Se agrega ajuste para que salgan todos los decimales que vengan en el número.
	 */
	if (isNaN(decimals = Math.abs(decimals))) {
		c = 0;
	} else {
		var newValue = new String(number);
		var separatorPosition = newValue.search(/\D/gi);
		if (separatorPosition < 0) {
			c = 0;
		} else {
			c = newValue.length - separatorPosition - 1;
			if(c>2){
				c=2;
			}
		}
	}
     
    //var n = number, c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;
    var n = number;
    var d = dec_point == undefined ? "," : dec_point;
    var t = thousands_sep == undefined ? "." : thousands_sep, s = n < 0 ? "-" : "";
    var i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    
    var value = s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : ""); 
    
    if(currency!=""){
    	value = orderCurrency.replace(/%v/, value);
    	value = value.replace(/%c/, currency);    	
    }
    return value;
}

/**
 * This function return true if param is array
 * @param input
 * @returns {Boolean}
 */
function is_array(input){
	return typeof(input)=='object'&&(input instanceof Array);
}

/**
 * This function add days to a date
 * @param date
 * @param days
 * @returns {String}
 */
function addDays(date, days){
    //milisegundos=parseInt(35*24*60*60*1000);
    var dateParts = date.split("/");
    fecha=new Date();
    
    fecha.setMonth(dateParts[1] - 1); //en javascript los meses van de 0 a 11
    fecha.setDate(dateParts[0]);
    fecha.setYear(dateParts[2]);    
    
    //Obtenemos los milisegundos desde media noche del 1/1/1970
    tiempo=fecha.getTime();
    //Calculamos los milisegundos sobre la fecha que hay que sumar o restar...
    milisegundos=parseInt(days*24*60*60*1000);
    //Modificamos la fecha actual
    total=fecha.setTime(tiempo+milisegundos);
    day=fecha.getDate();
    if(day<10){
    	day = "0"+day;
    }
    month=fecha.getMonth()+1;
    year=fecha.getFullYear();

    return(day+"/"+month+"/"+year);
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
  sRes = padNmb(nDay, 2, "0") + "/" + padNmb(nMonth, 2, "0") + "/" + padNmb(nYear, 4, "0");
  return sRes;
 }

function incDate(sFec0){
 var nDia = parseInt(sFec0.substr(0, 2), 10);
 var nMes = parseInt(sFec0.substr(3, 2), 10);
 var nAno = parseInt(sFec0.substr(6, 4), 10);
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
 var nDia = Number(sFec0.substr(0, 2));
 var nMes = Number(sFec0.substr(3, 2));
 var nAno = Number(sFec0.substr(6, 4));
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

/************************************* Generales **********************************************/
function showAlert(mesage, title){
	var dialog = jQuery("<div>",{
			"html": mesage
		}).dialog({
		title: title,		
		buttons: { 
			Ok: function(){
				jQuery(dialog).dialog("close");
			}
		},
		modal: true
	});
}

function numberValidate(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8 || tecla==0) return true; //Tecla de retroceso (para poder borrar)
    patron =/[0-9]/;
    te = String.fromCharCode(tecla);
    return patron.test(te);
};

jQuery(document).ready(function(){
	jQuery(".modal").click(function(){
		var hrefData = jQuery(this).attr("href");
		var dialog = jQuery("<div>",{
				"html": "<iframe src='"+hrefData+"' width='570' height='550' marginwidth='0' marginheight='0' style='border:0;' border='0' frameborder='0'/>"
			}).dialog({
			title: "",
			width:610,
			height: 600,
			modal: true
		});
		return false;
	});
});