jQuery(document).ready(function($) {

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
	//Arreglo de fechas finales
	var selectedDatesFinish = new Array();
	if(dateFinish!=""){
		selectedDatesFinish.push(dateFinish);
	}
		
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
			//Se debe poder seleccionar la fecha de salida asi que se le asocia el evento para que active el accordion
			jQuery("#head1").click(function(){
				//Al seleccionar la fecha de salida activo el accordion
				jQuery( "#conteCalendar" ).accordion( "option", "disabled", false );
				jQuery( "#conteCalendar" ).accordion( "option", "active", 0 );
			});
			//Establesco el valor de la fecha en el hidden
			jQuery( "#hdnCheckin" ).val(dateText);
			
            //Activo la segunda seccion del accordion
            jQuery( "#conteCalendar" ).accordion( "option", "active", 1 );
            var timeap = ""; 
            if(jQuery("#checkin_hour_ap").val()==1){
            	timeap = "am";
            }else{
            	timeap = "pm";
            }
            //Pongo el valor en el label
            jQuery("#checkin").html("("+dateText+" "+jQuery("#checkin_hour").val()+":00 "+timeap+")");            
            
            //Se cuentan los dias para ocultar los no disponibles
            var contDias = 0;
            var fechaComp= null;
            //Si es round tryp se realizan los ajustes al segundo calendario
            if(transferType==2){
            	//Establesco los valores del segundo calendario			
                jQuery( "#date_finish" ).datepicker( "option", "minDate", dateText );
                jQuery( "#date_finish" ).datepicker( "option", "maxDate", null);
                jQuery( "#date_finish" ).datepicker( "option", "currentText", dateText );
            	//Quito la fecha final del arreglo y actualizo el calendario
    			if(dateFinish!=""){
    				var indexCheckout = jQuery.inArray(dateFinish, selectedDatesFinish);
    				if(indexCheckout!=-1){
    					selectedDatesFinish.splice(indexCheckout, 1);
    				}
    				jQuery( "#date_finish" ).datepicker( "refresh" );
    				jQuery("#checkout").html("");
    				jQuery( "#hdnCheckout" ).val("");
    			}
            }
            
            
            
        }
	});
	
	//Si es round tryp establesco el segundo calendario
	if(transferType==2){
		//Establesco el segundo calendario
		jQuery( "#date_finish" ).datepicker({
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
				//Se compara con el arreglo de fechas disponibles establecido en el view.html
				if (jQuery.inArray(ymd, availableDates) == -1) {		    	
			    	if(jQuery.inArray(ymd, availableDates)==-1){
			    		return [false,"unavaiblecalendarcell","Unavailable"];
			    	}else{		    		
			    		return [true, "unselectedcalendarcell"];
			    	}		    	
			    } else {
			    	if(jQuery.inArray(ymd, selectedDatesFinish) != -1){
			    		return [true, "selectedcalendarcell"];
			    	}else{
			    		return [true, "unselectedcalendarcell",""];
			    	}
			    			        
			    }
			    
			},
			onSelect: function(dateText, inst) {
				//Al seleccionar la fecha de salida activo el accordion
				jQuery( "#conteCalendar" ).accordion( "option", "disabled", false );
				
				selectedDatesFinish.push(dateText);			
				dateFinish=dateText;
				
				var timeap = ""; 
				if(jQuery("#checkout_hour_ap").val()==1){
	            	timeap = "am";
	            }else{
	            	timeap = "pm";
	            }
				jQuery( "#conteCalendar" ).accordion( "option", "active", 2 );
				jQuery("#checkout").html("("+dateText+" "+jQuery("#checkout_hour").val()+":00 "+timeap+")");
				jQuery( "#hdnCheckout" ).val(dateText);
			}
		});
	}
	
	
	//Establesco la fecha seleccionada si existe
	if(dateStart!=""){
		jQuery( "#date_start" ).datepicker( "setDate", dateStart );
		//Le sumo un dia a la fecha inicial ya que se cuenta por noche
		var dateText1 = addToDate(dateStart, 1);
        jQuery( "#date_finish" ).datepicker( "option", "minDate", dateText1 );
		//Se cuentan los dias para ocultar los no disponibles
        var contDias = 0;
        var fechaComp= null;
        for(var i=jQuery.inArray(dateStart, availableDates); i<availableDates.length; i++){
        	fechaComp = addToDate(dateStart, contDias); 
        	//alert(fechaComp);
        	if(fechaComp!=availableDates[i]){            		
        		jQuery( "#date_finish" ).datepicker( "option", "maxDate", addToDate(fechaComp, -1) );            		
        		contDias++;
        		break;
        	}            		
        	contDias++;
        }
        //Se establece el maximo de dias configurado en el backend
        if(dayRangeTransfer>0 && contDias>dayRangeTransfer){
        	var partsDate = dateStart.split("/");            
            var date = new Date(parseInt(partsDate[2],10), parseInt(partsDate[1],10)-1, parseInt(partsDate[0],10)+dayRangeTransfer);            
                             
            var month = parseInt(date.getMonth())+1;
            jQuery( "#date_finish" ).datepicker( "option", "maxDate", date.getDate()+"/"+month+"/"+date.getFullYear());
        }		
	}
		
	
	if(dateFinish!=""){
		jQuery( "#date_finish" ).datepicker( "setDate", dateFinish );		
	}
		
	//Accordion de los calendarios
	jQuery("#conteCalendar").accordion({
		autoHeight: false,
		change: function (event, ui) {		
			var active = jQuery('#conteCalendar').accordion('option', 'active');
			switch(active){
				case 0:	
					if(jQuery("#hdnCheckin").val()=="" || jQuery("#hdnCheckout").val()==""){
						//Desactivo el accordion hasta que no seleccione la fecha
						jQuery( "#conteCalendar" ).accordion( "option", "disabled", true );						
					}
					break;
				case 1:
					//Si es round trip se valida que exista fecha de regreso
					if(transferType==2){
						if(jQuery("#hdnCheckout").val()==""){
							//Desactivo el accordion hasta que no seleccione la fecha
							jQuery( "#conteCalendar" ).accordion( "option", "disabled", true );
						}
					}else{
						//Se oculta la seccion de seleccion de pasajeros
						jQuery("#pass_select").hide();
						//Si es one way se ejecuta el ajax que trae las tarifas
						ajaxTransfer(false);
					}
					//Establesco el label con la fecha y hora seleccionada de recogida
					if(jQuery("#checkin_hour_ap").val()==1){
		            	timeap = "am";
		            }else{
		            	timeap = "pm";
		            }
					jQuery("#checkin").html("("+jQuery("#hdnCheckin").val()+" "+jQuery("#checkin_hour").val()+":00 "+timeap+")");
					
					break;
				case 2:
					//Oculto el mensaje de error
					jQuery("#msg_error_hour").hide();
					jQuery("#pass_select").show();
					//Establesco el label con la fecha y hora seleccionada de recogida
					if(jQuery("#checkin_hour_ap").val()==1){
		            	timeap = "am";
		            }else{
		            	timeap = "pm";
		            }
					jQuery("#checkin").html("("+jQuery("#hdnCheckin").val()+" "+jQuery("#checkin_hour").val()+":00 "+timeap+")");
					
					
					//Establesco el label con la fecha y hora seleccionada de entrega
					if(jQuery("#checkout_hour_ap").val()==1){
		            	timeap = "am";
		            }else{
		            	timeap = "pm";
		            }
					jQuery("#checkout").html("("+jQuery("#hdnCheckout").val()+" "+jQuery("#checkout_hour").val()+":00 "+timeap+")");
					//Valido si la fecha de recogida y entrega son iguales
					if(jQuery("#hdnCheckin").val()==jQuery("#hdnCheckout").val()){
						var checkinHour = jQuery("#checkin_hour").val()*jQuery("#checkin_hour_ap").val();
						var checkoutHour = jQuery("#checkout_hour").val()*jQuery("#checkout_hour_ap").val();
						if(checkinHour>=checkoutHour){
							jQuery("#content_room").hide();
							jQuery("#pass_select").hide();
							jQuery("#conte_button").hide();							
							jQuery("#msg_error_hour").show();
							return;
						}
					}
					//Si se han realizado cambios en fechas se realiza la consulta
					if(prevStartDate!=jQuery("#hdnCheckin").val() || prevEndDate!=jQuery("#hdnCheckout").val()){
						//Asigno los nuevos valores de fechas a los valores de validacion
						prevStartDate = jQuery("#hdnCheckin").val();
						prevEndDate = jQuery("#hdnCheckout").val();
						//Se establecen los precios en 0
						jQuery(".hdn_param_price").val(0);					
						jQuery(".cant_params option[value=0]").attr("selected",true);
						jQuery(".cant_supplement option[value=0]").attr("selected",true);						
						//Se oculta la seccion de seleccion de pasajeros
						jQuery("#pass_select").hide();
						//Llamo a la funcion que ejecuta el ajax que trae las tarifas
						ajaxTransfer(false);
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
					if(jQuery("#hdnCheckin").val()=="" || jQuery("#hdnCheckout").val()==""){
						//Desactivo el accordion hasta que no seleccione la fecha
						jQuery( "#conteCalendar" ).accordion( "option", "disabled", true );						
					}
					//Oculto la seccion de seleccion de habitaciones
					jQuery( "#content_room" ).hide(20);
					//Oculto el boton continuar
					jQuery( "#conte_button" ).hide(20);
					break;
				case "head2":
					if(jQuery("#hdnCheckout").val()==""){
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
	//Si llegan datos del qs se activa la seccion de seleccion destino si es round trip y ya selecciono fechas
	if(dateStart!="" && dateFinish!="" && transferType==2){
		jQuery( "#conteCalendar" ).accordion( "option", "active", 2 );
	}else{
		if(transferType==1 && dateStart!=""){
			jQuery( "#conteCalendar" ).accordion( "option", "active", 1 );
		}else{
			//Desactivo el accordion hasta que no seleccione la fecha
			jQuery( "#conteCalendar" ).accordion( "option", "disabled", true );	
		}
		
	}
	
	//Codigo para ocultar y mostrar acomodaciones
	jQuery(".title_params").click(function(){
		//Obtengo el id
		var id = jQuery(this).attr("rel");
		jQuery("#hdn_param3").val(id);
		//Si la seccion esta visible no hago nada
		if(jQuery("#contain_param"+id).css("display") != "block"){
			//Quito la clase activo a todos los titulos de plan
			jQuery(".title_params").removeClass("active");
						
			
			//Recorro las otras secciones para ocultarlas
			jQuery(".contain_destiny").each(function(){
				//Si son diferentes a la actual las oculta
				if(jQuery(this).attr("id")!="#contain_param"+id){
					jQuery(this).hide();
				}
			});
			//Establesco como activo el titulo de la actual
			jQuery(this).addClass("active");
			//Muestro la actual
			jQuery("#contain_param"+id).show();
		}
		//Calculo el total de la tarifa
		setTotal();
			
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
		jQuery("#frm_details").submit();
	});
	
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
	
	jQuery("#adults").change(function(){
		ajaxTransfer(true);
	});
	
	jQuery(".selectParam1").change(function(){		
		calculateRate(this);
	});
	
});
//Bandera para verificar si se han realizado cambios en fechas
var prevStartDate=0;
var prevEndDate=0;
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

var arrayRateSupplent1 = new Array();
var arrayRateSupplent2 = new Array();

/**
 * This function set the rate table for the template
 * @param objRate
 */
function setRate(objRate, isPassangers){
	jQuery(".selectFeed").html("");
	//Establesco el simbolo de la moneda
	currency = objRate.currency.symbol;
	jQuery(".selectParam1").html("");
	//Si el evento no es producido por el combo de pasajeros
	if(!isPassangers){
		//Limpio el combo de adultos
		jQuery("#adults").html("");		
	}

	
	//Estas son las tarifas por habitacion
	if(objRate.rates.rate!=null && objRate.rates.rate.length>0){
		//Oculto todos los elemntos de tipo param1
		jQuery(".content_param1").hide();
		//Si el evento no es producido por el combo de pasajeros
		if(!isPassangers){
			//Recorro el listado de personas para agregarlo al combo
			jQuery(objRate.passengerRange).each(function(index, value){
				var optPass = jQuery("<option value='"+value+"'>"+value+"</option>")
				jQuery("#adults").append(optPass);
			});
		}
		//Recorro el listado de precios por parametros
		jQuery(objRate.rates.rate).each(function(index, value){
			//Muestro la seccion de param 3 que tiene precio asociado
			jQuery("#content_param3"+value.param3).show();
			
			//Creo una opcion para el combo con el tipo de alimentacion que tiene precio asignado
			var option = jQuery("<option>",{
				value: value.param1,
				text:serviceTypeArray[value.param1]
			});
			//Agrego la opcion al combo
			jQuery("#select_param1"+value.param3).append(option);
			//Valido si el param1 no es un arreglo y lo convierto en arreglo			
			if(!is_array(arrayRate[value.param1])){
				arrayRate[value.param1] = new Array();
			}			
			//Establesco el precio para los tres parametros de tarificacion
			arrayRate[value.param1][value.param3] = value.totalPrice;			
		});
		var minPrice = 0;
		var objMinPrice=null;
		
		//Se recorren los combos de tipo de alimentacion para establecer el precio minimo
		jQuery(".selectParam1").each(function(){
			if(jQuery(this).val()!=null){
				var price = calculateRate(this);
				if(minPrice==0 || price<minPrice){
					minPrice = price;					
					objMinPrice = this;
				}
			}				
		});
		//Oculto todas las secciones
		jQuery("#content_params").find(".contain_params").hide();
		//Dejo visible la seccion que contenga el menor precio
		jQuery(objMinPrice).parents(".contain_params").show();		
	}
	jQuery("#hdn_param3").val(jQuery(objMinPrice).parents(".contain_params").attr("rel"));
	setTotal();
	
	jQuery(".supplement_div").hide();
	//Calculo las tarifas de los suplementos
	if(objRate.supplements.supplement!=null && objRate.supplements.supplement.length>0){		
		jQuery(objRate.supplements.supplement).each(function(index, value){
			jQuery("#supplement_div"+value.id).show();			
			var total = number_format( value.totalPrice, numDec, decSep, milSep );
			//Asigno el precio al label
			jQuery("#supplementPrice"+value.id).html(total);
			//Asigno el valor del suplemento al arreglo
			arrayRateSupplent1[value.id] = value.totalPrice;			
		});
		//Cuando cambie el numero de huespedes se cambia el precio
		jQuery(".selectGuestSupplement").change(function(){
			var price = arrayRateSupplent2[jQuery(this).attr("supplementId")][jQuery(this).attr("dateSupplementId")]*jQuery(this).val();
			jQuery("#priceSupplement2"+jQuery(this).attr("supplementId")+jQuery(this).attr("dateSupplementId")).val(price);
			setTotal();
		});
	}
	
}

var totalRate=0;




function ajaxTransfer(isPassangers){
	var urlText = "index.php?option=com_catalogo_planes&view=transfer&layout=ratedetails";
	if(isPassangers){
		urlText += "&ispassenger=true";
	}
	//Al mostrarse la seccion de tarifas las consulto mediante ajax 
	jQuery.ajax({
		type: "POST",
	  	url: urlText,
	  	data: { 
			product_id: jQuery("#product_id").val(), 
			checkin_date: jQuery("#hdnCheckin").val(), 
			checkout_date: jQuery("#hdnCheckout").val(),
			type: jQuery("#type").val(),
			passengers: jQuery("#adults").val()
		},
		timeout: 1000000,
		dataType: "json",
		cache : false,
		beforeSend: function() {
			if(isPassangers){
				jQuery("#content_room").hide();
				jQuery("#conte_button").hide();				
			}
				
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
				setRate(datos, isPassangers);
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
				
			
		},
		error: function(x, t, m) {
			//Activo el accordion para que se puedan realizar cambios
			jQuery( "#conteCalendar" ).accordion( "option", "disabled", false);
			//Se le indica a la aplicacion que no existen tarifas
			existRates=false;
			//Se oculta la imagen cargando
			jQuery("#conte_loader").hide();
			//Oculto el mensaje de error
			jQuery("#message_error").show();
	    }
	});
}



/**
 * This function calculate the rate of the feed type select 
 * @param obj
 * @returns
 */
function calculateRate(obj){
	var param3 = jQuery(obj).attr("param3");	
	var param1 = jQuery("#select_param1"+param3).val();	
	
	var quantity = jQuery("#adults").val();
	if(quantity==0){
		jQuery("#hdn_param_price"+param3).val(0);
		quantity=1;
	}else{
		jQuery("#hdn_param_price"+param3).val(arrayRate[param1][param3]);
	}	
	var price = arrayRate[param1][param3];//*quantity;
	
	var priceFormat = number_format( price, numDec, decSep, milSep );
	
	jQuery("#price"+param3).html(priceFormat);
	setTotal();
	return price;
}

/**
 * This function set the total of the product
 */
function setTotal() {
	var total = 0;
	jQuery(".hdn_param_price").each(function(){
		if(jQuery(this).parents(".contain_destiny").css("display")=="block"){
			if(jQuery(this).val()>0){
				var price = parseFloat(jQuery(this).val());
				total+=price;
			}
		}
	});
	jQuery(".hdn_param_price_supplement").each(function(){		
		if(jQuery(this).val()>0){
			var price = parseFloat(jQuery(this).val());
			total+=price;
		}		
	});
	
	total = total.toFixed(12);
	total = parseFloat(total);
	price = number_format( total, numDec, decSep, milSep );
	jQuery("#subtotal").html(price);
}