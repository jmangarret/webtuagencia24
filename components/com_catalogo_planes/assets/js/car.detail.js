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
	//Cuando cambie la hora tambien es necesario volver a consultar el servicio
	jQuery(".hour_car_select").change(function(){
		prevStartDate = "";
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
			//Establesco los valores del segundo calendario			
            jQuery( "#date_finish" ).datepicker( "option", "minDate", dateText );
            jQuery( "#date_finish" ).datepicker( "option", "maxDate", null);
            jQuery( "#date_finish" ).datepicker( "option", "currentText", dateText );
            //Activo la segunda seccion del accordion
            jQuery( "#conteCalendar" ).accordion( "option", "active", 1 );
            var timeap = ""; 
            if(jQuery("#checkout_hour_ap").val()==1){
            	timeap = "am";
            }else{
            	timeap = "pm";
            }
            //Pongo el valor en el label
            jQuery("#checkin").html("("+dateText+" "+jQuery("#checkin_hour").val()+":00 "+timeap+")");            
            
            //Se cuentan los dias para ocultar los no disponibles
            var contDias = 0;
            var fechaComp= null;
            for(var i=jQuery.inArray(dateText, availableDates); i<availableDates.length; i++){
            	fechaComp = addToDate(dateText, contDias); 
            	//alert(fechaComp);
            	if(fechaComp!=availableDates[i]){            		
            		jQuery( "#date_finish" ).datepicker( "option", "maxDate", addToDate(fechaComp, 0) );            		
            		contDias++;
            		break;
            	}            		
            	contDias++;
            }
            //Se establece el maximo de dias configurado en el backend
            if(dayRangeCar>0 && contDias>dayRangeCar){
            	var partsDate = dateText.split("/");            
                var date = new Date(parseInt(partsDate[2],10), parseInt(partsDate[1],10)-1, parseInt(partsDate[0],10)+dayRangeCar);            
                                 
                var month = parseInt(date.getMonth())+1;
                jQuery( "#date_finish" ).datepicker( "option", "maxDate", date.getDate()+"/"+month+"/"+date.getFullYear());
            }
            
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
	});
	
	
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
        if(dayRangeCar>0 && contDias>dayRangeCar){
        	var partsDate = dateStart.split("/");            
            var date = new Date(parseInt(partsDate[2],10), parseInt(partsDate[1],10)-1, parseInt(partsDate[0],10)+dayRangeCar);            
                             
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
					if(jQuery("#hdnCheckout").val()==""){
						//Desactivo el accordion hasta que no seleccione la fecha
						jQuery( "#conteCalendar" ).accordion( "option", "disabled", true );
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
					
					//Si se han realizado cambios en fechas se realiza la consulta
					if(prevStartDate!=jQuery("#hdnCheckin").val() || prevEndDate!=jQuery("#hdnCheckout").val()){
						
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
						
						
						//Asigno los nuevos valores de fechas a los valores de validacion
						prevStartDate = jQuery("#hdnCheckin").val();
						prevEndDate = jQuery("#hdnCheckout").val();
						//Se establecen los precios en 0
						jQuery(".hdn_param_price").val(0);					
						jQuery(".cant_params option[value=0]").attr("selected",true);
						jQuery(".cant_supplement option[value=0]").attr("selected",true);						
						//Se oculta la seccion de seleccion de pasajeros
						jQuery("#pass_select").hide();
						//Al mostrarse la seccion de adultos y ninos realizo la consulta de tarifas 
						jQuery.ajax({
							type: "POST",
						  	url: "index.php?option=com_catalogo_planes&view=car&layout=ratedetails",
						  	data: { 
								product_id: jQuery("#product_id").val(), 
								checkin_date: jQuery("#hdnCheckin").val(), 
								checkin_hour: jQuery("#checkin_hour").val(),
								checkin_hour_ap: jQuery("#checkin_hour_ap").val(),
								checkout_date: jQuery("#hdnCheckout").val(),
								checkout_hour: jQuery("#checkout_hour").val(),
								checkout_hour_ap: jQuery("#checkout_hour_ap").val()								
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
	//Si llegan datos del qs se activa la seccion de huespedes y habitaciones
	if(dateStart!="" && dateFinish!=""){
		jQuery( "#conteCalendar" ).accordion( "option", "active", 2 );
	}else{
		//Desactivo el accordion hasta que no seleccione la fecha
		jQuery( "#conteCalendar" ).accordion( "option", "disabled", true );
	}
	
	//Codigo para ocultar y mostrar acomodaciones
	jQuery(".title_params").click(function(){
		var id = jQuery(this).attr("rel");
		if(jQuery("#contain_param"+id).css("display") == "block"){
			jQuery(this).removeClass("active");
			jQuery("#contain_param"+id).hide(300);
		}else{
			jQuery(this).addClass("active");
			jQuery("#contain_param"+id).show(300);
		}
			
	});
	//En caso de que cambie el combo de ninos se muestra o no la seleccion de alimentacion para ninos
	jQuery("#childs").change(function(){
		//Si el numero de ninos es mayor a 0 se muestra la seccion de ninos
		if(parseInt(jQuery(this).val())>0){			
			jQuery("#seccion_childs").show();
		}else{			
			jQuery("#seccion_childs").hide();			
		}
		//Si el numero de ninos es mayor a 0 se muestra calcula el precio con el numero de ninos
		calculateRateChild(jQuery("#select_childs"));
		jQuery("#cant_child").html(jQuery(this).val());
	});
	//En caso de que cambie el tipo de alimentacion en una habitacion
	jQuery(".selectFeed").change(function(){
		calculateRate(this);
	});
	//En caso de que cambie el numero de habitaciones calcula nuevamente el precio
	jQuery(".cant_params").change(function(){
		calculateRate(this);
	});
	//En caso de que cambie el tipo de alimentacion para ninos
	jQuery("#select_childs").change(function(){
		calculateRateChild(this);
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
	//Establesco el precio total por el tiempo establecido
	jQuery("#total_rate_car").val(objRate.rates.rate[0].totalPrice);
	var priceFormat = number_format( objRate.rates.rate[0].totalPrice, numDec, decSep, milSep );
	jQuery("#price_car_subtotal").html(priceFormat);
	//Los datos calculados de dias y horas se ingresan en las cajas de texto
	jQuery("#calc_days").val(objRate.time.days);
	jQuery("#calc_hours").val(objRate.time.hours);
	//Oculto todos los suplementos para solo mostrar los que tienen precios asignados
	jQuery(".supplement_div").hide();
	//Calculo las tarifas de los suplementos
	if(objRate.supplements.supplement!=null && objRate.supplements.supplement.length>0){		
		jQuery(objRate.supplements.supplement).each(function(index, value){
			jQuery("#supplement_div"+value.id).show();
			//Si el suplemento es tipo 2 se cobra por el tiempo de estancia en el car			
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
	setTotal();
}

var totalRate=0;
var totalRateChild=0;
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