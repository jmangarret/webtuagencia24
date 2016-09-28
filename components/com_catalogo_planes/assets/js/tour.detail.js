jQuery(document).ready(function($) {
	
	
	//Establesco el ancho total de las pestanas
	var widthTabs = 0;
	var marginLeft = 0;
	var countTabChild = 0;
	var firstChildWidth = 0;
	var secondChildLeft = 0;
	jQuery(".tabs li").each(function(){
		if(countTabChild==0){
			firstChildWidth = jQuery(this).width();
		}
		if(countTabChild==1){
			secondChildLeft = jQuery(this).position().left;
		}
		widthTabs += jQuery(this).width();
		marginLeft = jQuery(this).css("margin-left");
		countTabChild++;
	});	
	var tabSepWidth = 0;
	var totalTabSepWidth = 0;
	//VAlido que por lo menos existan 2 tabs
	if(secondChildLeft>0 && firstChildWidth>0){		
		tabSepWidth = secondChildLeft-firstChildWidth;
		totalTabSepWidth = tabSepWidth*countTabChild;
	}
	
	//Si el ancho total de las pestanas es mayor al contenedor muestro la flecha
	if(widthTabs>jQuery(".conte_tags_arrows").width()){
		jQuery(".der_arrow").show();
	}
	
	jQuery(".der_arrow").click(function(){
		var totalWidthTab = totalTabSepWidth+widthTabs;		
		if((totalWidthTab+jQuery('.tabs').position().left)>jQuery('.conte_tags_arrows').width()){
			var newLeft = jQuery(".tabs").position().left-150;			
			jQuery(".tabs").css("left", newLeft+"px");
		}
		
		if(jQuery('.tabs').position().left<0){
			jQuery(".izq_arrow").show();
		}
		if((totalWidthTab+jQuery('.tabs').position().left)<jQuery('.conte_tags_arrows').width()){
			jQuery(".der_arrow").hide();
		}
	});
	
	jQuery(".izq_arrow").click(function(){
		var totalWidthTab = totalTabSepWidth+widthTabs;		
		if(jQuery('.tabs').position().left<0){
			var newLeft = jQuery(".tabs").position().left+150;			
			jQuery(".tabs").css("left", newLeft+"px");
		}
		
		if(jQuery('.tabs').position().left==0){
			jQuery(".izq_arrow").hide();
		}
		if((totalWidthTab+jQuery('.tabs').position().left)>jQuery('.conte_tags_arrows').width()){
			jQuery(".der_arrow").show();
		}
	});
	
	
	//Default Action
	jQuery(".tab_content").hide(); //Hide all content
	jQuery("ul.tabs li:first").addClass("active").show(); //Activate first tab
	jQuery(".tab_content:first").show(); //Show first tab content
	//On Click Event
	jQuery("ul.tabs li").click(function() {
		jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class
		jQuery(this).addClass("active"); //Add "active" class to selected tab
		jQuery(".tab_content").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		jQuery(activeTab).fadeIn(); //Fade in the active content
		if (jQuery(this).attr("filter")=="map") {			
			if(urlMap==""){
				google.maps.event.trigger(map, "resize");
				map.setCenter(latlng);
			}else{
				//Por alguna razon ie8 no carga la pagina de un iframe si este se encuentra oculto, es necesario volverlo a cargar
				jQuery('#map_canvas').attr("src", urlMap);
			}
		}
		return false;
	});
	//Arreglo de fechas iniciales
	var selectedDatesStart = new Array();
	if(dateStart!=""){
		selectedDatesStart.push(dateStart);
	}
	
	if(!specialSeasons){
		//Establesco el primer calendario	
		jQuery( "#date_start" ).datepicker({
			minDate: availableDates[0],
			maxDate: availableDates[availableDates.length-1],
			dateFormat: 'dd/mm/yy',
			beforeShowDay: function(date){
				var day;
				if(date.getDate()<10){
					day = "0"+date.getDate();
				}else{
					day = date.getDate();
				}
				var month;
				month = date.getMonth()+1;
				if(month<10){				
					month = "0"+month;
				}
				ymd = day + "/" + month + "/" + date.getFullYear();	
			    if (jQuery.inArray(ymd, availableDates) == -1) {		    	
			    	return [false,"unavaiblecalendarcell","Unavailable"];
			    } else {
			    	if(jQuery.inArray(ymd, selectedDatesStart) != -1){
			    		return [true, "selectedcalendarcell"];
			    	}else{
			    		return [true, "unselectedcalendarcell"];
			    	}
			    }
			},		
			onSelect: function(dateText, inst) {
				selectedDatesStart.push(dateText);
				dateStart=dateText;
				//Al seleccionar la fecha de entrada activo el accordion
				jQuery( "#conteCalendar" ).accordion( "option", "disabled", false );
				//Al estar sobre el titular de chekin se debe mostrar el cursor
				jQuery("#head1").hover(function(){
					//Ajusto las clases para que muestre bien el cursor
					jQuery("#head1").addClass("ui-state-hover");
					jQuery("#head1").removeClass("ui-accordion-disabled");
					jQuery("#head1").removeClass("ui-state-disabled");
				});			
				//Establesco el valor de la fecha en el hidden
				jQuery( "#hdnCheckin" ).val(dateText);			
	            jQuery("#checkin").html("("+dateText+")"); 
	          //Activo la segunda seccion del accordion
	            jQuery( "#conteCalendar" ).accordion( "option", "active", 1 );
	            
	        }
		});
	}else{
		if(dateStart!=""){
			jQuery("#hdnCheckin option[value='"+dateStart+"']").attr("selected",true);
		}
		
	}
	
	//Accordion de los calendarios
	jQuery("#conteCalendar").accordion({
		autoHeight: false,
		change: function (event, ui) {		
			var active = jQuery('#conteCalendar').accordion('option', 'active');
			
			switch(active){
				case 0:	
					if(jQuery("#hdnCheckin").val()==""){
						//Desactivo el accordion hasta que no seleccione la fecha
						jQuery( "#conteCalendar" ).accordion( "option", "disabled", true );						
					}
					break;
				case 1:					
					//Si se han realizado cambios en fechas se realiza la consulta
					if(prevStartDate!=jQuery("#hdnCheckin").val()){
						//Asigno los nuevos valores de fechas a los valores de validacion
						prevStartDate = jQuery("#hdnCheckin").val();						
						//Se establecen los precios en 0
						jQuery(".hdn_param_price").val(0);
						jQuery(".cant_params option[value=0]").attr("selected",true);
						jQuery(".cant_supplement option[value=0]").attr("selected",true);
						jQuery(".cant_params_childs option[value=0]").attr("selected",true);
						//Se oculta la seccion de seleccion de pasajeros
						jQuery("#pass_select").hide();
						//Al mostrarse la seccion de adultos y ninos realizo la consulta de tarifas 
						jQuery.ajax({
							type: "POST",
						  	url: "index.php?option=com_catalogo_planes&view=tour&layout=ratedetails",
						  	data: { 
								product_id: jQuery("#product_id").val(), 
								checkin_date: jQuery("#hdnCheckin").val()								 
							},
							timeout: 10000,
							dataType: "json",
							cache : false,
							beforeSend: function() {
								//Se muestra la imagen cargando
								jQuery("#conte_loader").show();
								//Oculto el mensaje de error
								jQuery("#message_error").hide();
								//Desactivo el accordion mientras se realiza la consulta
								jQuery( "#conteCalendar" ).accordion( "option", "disabled", true );
							},
							success: function(datos){
								//Activo el accordion para que se puedan realizar cambios
								jQuery( "#conteCalendar" ).accordion( "option", "disabled", false);
								//Se oculta la imagen cargando
								jQuery("#conte_loader").hide();
								//Se oculta los elementos de numero de pasajeros
								jQuery(".selectGuestsTour").hide();
								//Se elimina la variable de tarifa para adultos y niños antes de fijar variable de tarifas
								delete arrayRate[param2Adults];
								delete arrayRate[param2Childs];
								//Si no existen tarifas se muestra el mensaje de error								
								if(datos.rates.rate!=null){
									//Se le indica a la aplicacion que existen tarifas
									existRates=true;
				
									//Si no existen suplementos se oculta esta seccion
									if(datos.supplements.length == 0){
										jQuery("#seccion_supplements").hide();
									}else{
										jQuery("#seccion_supplements").show();
									}
									setRate(datos);
									//Se muestra la seccion de seleccion de pasajeros
									jQuery("#pass_select").show();
									//Se muestra la seccion de habiataciones
									jQuery( "#content_room" ).show();
									//Se muestra el boton continuar
									jQuery( "#conte_button" ).show();
								}else{
									//Se le indica a la aplicacion que no existen tarifas
									existRates=false;
									//Muestro el mensaje de error
									jQuery("#message_error").show();
								}
									
								
							}
						});
					}else{
						if(existRates){
							//Se muestra la seccion de habiataciones
							jQuery( "#content_room" ).show();
							//Se muestra el boton continuar
							jQuery( "#conte_button" ).show();	
						}
					}					
					break;
			}
		},
		changestart: function(event, ui) {
			switch(ui.newHeader.attr("id")){
				case "head1":
					if(jQuery("#hdnCheckin").val()==""){
						//Desactivo el accordion hasta que no seleccione la fecha
						jQuery( "#conteCalendar" ).accordion( "option", "disabled", true );						
					}
					//Oculto la seccion de seleccion de habitaciones
					jQuery( "#content_room" ).hide(20);
					//Oculto el boton continuar
					jQuery( "#conte_button" ).hide(20);
					break;				
				case "head3":
					jQuery( "#conteCalendar" ).accordion( "option", "disabled", false );
					//Muestra la seccion de seleccion de habitaciones
					//jQuery( "#content_room" ).show(20);
					break;
			}
		}
	});
	
	//Evento cuando se selecciona otra fecha en el combo de fechas
	jQuery(".dateSelect").change(function(){
		if(jQuery(this).val()==""){
			jQuery( "#conteCalendar" ).accordion( "option", "disabled", true );
			jQuery("#checkin").html(""); 
		}else{
			jQuery( "#conteCalendar" ).accordion( "option", "disabled", false );
			jQuery("#checkin").html("("+jQuery(this).val()+")"); 
	        //Activo la segunda seccion del accordion			
	        jQuery( "#conteCalendar" ).accordion( "option", "active", 1 );
		}
		
        
	});
	//Codigo para ocultar y mostrar suplementos
	jQuery(".supplement_info .link_mas_info_suple a").click(function(){
		//Obtengo el id
		var id = jQuery(this).attr("id");		
		if(jQuery(".despSupl"+id).css("display") != "block"){
			//Recorro las otras secciones para ocultarlas
			jQuery(".conte_mas_info_suple").each(function(){
				//Si son diferentes a la actual las oculta
				if(jQuery(this).attr("id")!=".despSupl"+id){
					jQuery(this).hide(300);					
				}
			});			
			jQuery(".despSupl"+id).show(300);
			
		}
		
	});
	//En caso de que cambie el número de niños
	jQuery("#childs").change(function(){
		calculateRate(this);
	});	
	//En caso de que cambie el número de adultos
	jQuery("#adults").change(function(){
		calculateRate(this);
	});		
	//En caso de que seleccione la cantidad de suplementos
	jQuery(".cant_supplement").change(function(){
		var supplement_id = jQuery(this).attr("rel");
		var price = arrayRateSupplent1[supplement_id]*jQuery(this).val();
		jQuery("#hdn_param_price_supplement1"+supplement_id).val(price);
		setTotal();
	});
	//El evento del boton siguiente que genera un submit
	jQuery("#button_next").click(function(){
		//Valido que el numero de ninos no sea mayor al doble del numero de habitaciones
		if(adultwithoutchild==0 && (parseInt(jQuery("#childs").val())>parseInt(jQuery("#adults").val()))){			
			showAlert(messageErrorChildwithoutadult, "");
			return false;
		}
		else if(parseInt(jQuery("#childs").val())==0 && parseInt(jQuery("#adults").val())==0){
			showAlert(messageErrorWithoutGuests, "");
			return false;			
		}
		jQuery("#frm_details").submit();
	});
	//Para mostrar las notas
	jQuery("#note").click(function(){
		var dialog = jQuery("#conte_note").dialog({
			title: jQuery(this).html(),		
			buttons: { 
				Ok: function(){
					jQuery(dialog).dialog("close");
				}
			},
			modal: true
		});
	});
	
	//Establesco la fecha seleccionada si existe
	if(dateStart!=""){
		jQuery( "#date_start" ).datepicker( "setDate", dateStart );		
        		
	}
	
	//Si llegan datos del qs se activa la seccion de huespedes y habitaciones
	if(dateStart!=""){
		jQuery( "#conteCalendar" ).accordion( "option", "active", 1 );
	}else{
		//Desactivo el accordion hasta que no seleccione la fecha
		jQuery( "#conteCalendar" ).accordion( "option", "disabled", true );
	}
});

//Bandera para verificar si se han realizado cambios en fechas
var prevStartDate=0;
//Bandera para verificar si existen tarifas
var existRates=false;
/**
 * This function display the room section
 * @param obj
 * @param idDestiny
 */
function displayRoom(obj, idDestiny){	
	jQuery("#"+idDestiny).show(30);
	
}

var arrayRate = new Array();
var arrayRateChild = new Array();
var arrayRateSupplent1 = new Array();
var arrayRateSupplent2 = new Array();

/**
 * This function set the rate table for the template
 * @param objRate
 */
function setRate(objRate){
	//Establesco el simbolo de la moneda
	currency = objRate.currency.symbol;
	
	//Estas son las tarifas por habitacion
	if(objRate.rates.rate!=null && objRate.rates.rate.length>0){
		//Recorro el listado de precios por parametros
		jQuery(objRate.rates.rate).each(function(index, value){			
			//Establesco el precio para el parametro 2 de tarificacion, el cual indica si es adulto=1 ó niño=2
		    arrayRate[value.param2]=value.totalPrice;   
		    jQuery('div[param="'+value.param2+'"]').show();
		    
		});			
		//Se calcula precio con con valores pre-establecidos
		calculateRate(this);	
	}

	jQuery(".supplement_div").hide();
	//Oculto todas las secciones de detalle de suplementos
	jQuery("#content_supplement").find(".conte_mas_info_suple").hide();	
	//Calculo las tarifas de los suplementos
	if(objRate.supplements.supplement!=null && objRate.supplements.supplement.length>0){		
		jQuery(objRate.supplements.supplement).each(function(index, value){
			jQuery("#supplement_div"+value.id).show();
			//Si el suplemento es tipo 2 se cobra por el tiempo de estancia en el plan
			if(value.type==2){
				var total = number_format( value.totalPrice, numDec, decSep, milSep );
				//Asigno el precio al label
				jQuery("#supplementPrice"+value.id).html(total);
				//Asigno el valor del suplemento al arreglo
				arrayRateSupplent1[value.id] = value.totalPrice;
			}else{//Si se cobra una sola vez se muestran los rangos de fechas para realizar el cobro
				//Limpio el contendor de fechas
				jQuery("#ConteSupplement"+value.id).html("");
				jQuery(value.datesRange).each(function(index2, valueDate){
					
					//Creo el div del contenedor de las fechas
					var divDate = jQuery("<div>",{
						id: "dateSupplement"+value.id+valueDate.contSupplement,
						"html":valueDate.dateStart+" a "+valueDate.endDate,
						"class":"conteDateSupplement"
					});
					//Creo el div contenedor del precio
					var divPrice = jQuery("<div>",{
						id: "priceSupplemen"+value.id+valueDate.contSupplement,
						"html": number_format( valueDate.totalPrice, numDec, decSep, milSep ),
						"class":"contePriceSupplement"
					});
					//Creo el select
					var selectGuest = jQuery("<select>",{
						id: "guestSupplement"+value.id+valueDate.contSupplement,
						name: "guestSupplement["+value.id+"]["+valueDate.contSupplement+"]",
						"supplementId":value.id,
						"dateSupplementId":valueDate.contSupplement,
						"class":"selectGuestSupplement"
					});
					//Creo un hidden donde se van a guardar los subtotales
					var hiddenPrice = jQuery("<hidden>",{
						id: "priceSupplement2"+value.id+valueDate.contSupplement,						
						"class":"hdn_param_price",
						"value":0
					});
					//creo cada una de las opciones y las agrego al combo
					for(var i=0; i<=10; i++){
						//Creo una opcion para el combo 
						var option = jQuery("<option>",{
							value: i,
							text:i
						});
						jQuery(selectGuest).append(option);
					}					
					//Creo el div que contiene el numero de viajeros
					var divGuest = jQuery("<div>",{												
						"class":"conteGuestSupplement"
					}).append(selectGuest);
					//Adjunto el hidden al div
					jQuery(divGuest).append(hiddenPrice);
					//Creo el contenedor general de fechas y precios
					var div = jQuery("<div>",{
						id: "cont_dates"+value.id,						
						"class":"conteGeneralDatesSupplement"
					}).append(divDate);					
					
					//Agrego el div del precio al contenedor
					jQuery(div).append(divPrice);
					//Agrego el div de huespedes al contenedor
					jQuery(div).append(divGuest);
					//Ajusto el div en el contenedor del suplemento
					jQuery("#ConteSupplement"+value.id).append(div);
					//Valido si el identificador de supplemento no es un arreglo y lo convierto en arreglo
					if(!is_array(arrayRateSupplent2[value.id])){
						arrayRateSupplent2[value.id] = new Array();
					}
					//Establesco el precio para los tres parametros de tarificacion
					arrayRateSupplent2[value.id][valueDate.contSupplement] = valueDate.totalPrice;	
				});
			}
			
		});

	}
	
}

var totalRate=0;
var totalRateChild=0;

/**
 * This function calculate the rate of the feed type select 
 * @param obj
 * @returns
 */
function calculateRate(obj){	
	//Tomo cantidad de adultos seleccionados
	var qtyAdults = parseInt(jQuery("#adults").val());
	//Tomo cantidad de niños seleccionados
	var qtyChilds = parseInt(jQuery("#childs").val());
	//Multiplico la cantidad seleccionada por la tarifa establecida.
	//isNaN, si no existen tarifas establecidas par adulto ó niño.
	var priceAdults = (isNaN(arrayRate[param2Adults]*qtyAdults) ? 0 : arrayRate[param2Adults]*qtyAdults);
	var priceChilds = (isNaN(arrayRate[param2Childs]*qtyChilds) ? 0 : arrayRate[param2Childs]*qtyChilds);
	//alert( priceAdults +'-'+priceChilds +'-'+ arrayRate[1]+'-'+arrayRate[2]);
	jQuery("#hdn_param_price_GuestsTour").val(priceAdults+priceChilds);	
	
	//var price = arrayRate[param1][param2][param3];//*quantity;
	var price = priceAdults+priceChilds;
	
	var priceFormat = number_format( price, numDec, decSep, milSep );
	/*if(price>100000000){
		jQuery("#price_GuestsTour").css("font-size","11px");
	}else{
		jQuery("#price_GuestsTour").css("font-size","12px");
	}
	jQuery("#price_GuestsTour").html(priceFormat);*/
	setTotal();

	return price;
}

/**
 * This function set the total of the product
 */
function setTotal() {
	var total = 0;
	jQuery(".hdn_param_price").each(function(){
		var price = parseFloat(jQuery(this).val());
		total+=price;
	});
	total = total.toFixed(12);
	total = parseFloat(total);
	price = number_format( total, numDec, decSep, milSep );
	jQuery("#subtotal").html(price);
}