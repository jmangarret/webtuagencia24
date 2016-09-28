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
	
	
	//Para que muestre la cantidad de ninos al frente de la alimentacion al momento de actualizar la pagina
	jQuery("#cant_child").html(jQuery("#childs").val());
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
			//Establesco los valores del segundo calendario
			//Le sumo un dia a la fecha inicial ya que se cuenta por noche
			var dateText1 = addToDate(dateText, 1);
            jQuery( "#date_finish" ).datepicker( "option", "minDate", dateText1 );
            jQuery( "#date_finish" ).datepicker( "option", "maxDate", null);
            jQuery( "#date_finish" ).datepicker( "option", "currentText", dateText );
            //Activo la segunda seccion del accordion
            jQuery( "#conteCalendar" ).accordion( "option", "active", 1 );
            //Pongo el valor en el label
            jQuery("#checkin").html("("+dateText+")");            
            
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
            if(dayRangeHotel>0 && contDias>dayRangeHotel){
            	var partsDate = dateText.split("/");            
                var date = new Date(parseInt(partsDate[2],10), parseInt(partsDate[1],10)-1, parseInt(partsDate[0],10)+dayRangeHotel);            
                                 
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
		    	if(jQuery.inArray(addToDate(ymd, -1), availableDates)==-1){
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
			jQuery( "#conteCalendar" ).accordion( "option", "active", 2 );
			jQuery("#checkout").html("("+dateText+")");
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
        if(dayRangeHotel>0 && contDias>dayRangeHotel){
        	var partsDate = dateStart.split("/");            
            var date = new Date(parseInt(partsDate[2],10), parseInt(partsDate[1],10)-1, parseInt(partsDate[0],10)+dayRangeHotel);            
                             
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
					break;
				case 2:
					
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
				
						//Al mostrarse la seccion de adultos y ninos realizo la consulta de tarifas 
						jQuery.ajax({
							type: "POST",
						  	url: "index.php?option=com_catalogo_planes&view=hotel&layout=ratedetails",
						  	data: { 
								product_id: jQuery("#product_id").val(), 
								checkin_date: jQuery("#hdnCheckin").val(), 
								checkout_date: jQuery("#hdnCheckout").val() 
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
									//Si no existen ninos se oculta el combo 
									if(datos.childs.length == 0){
										jQuery("#childsSelect").hide();
										jQuery("#seccion_childs").hide(); 										
									}else{
										jQuery("#childsSelect").show();
									}
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
	//Codigo para ocultar y mostrar suplementos
	jQuery("a.view_more_supp").click(function(){
		
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
	jQuery(".conte_mas_info_suple").hide();
	
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
	jQuery("select.selectFeed").change(function(){
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
		var totalCapacity = 0;
		var totalRoom = 0;
		//Recorro las habitaciones seleccionadas para obtener el numero de adultos que se pueden hospedar en ellas
		jQuery(".cant_params[value!=0]").each(function(index,value){
			totalCapacity += parseInt(jQuery(this).attr("capacity"))*parseInt(jQuery(this).val());
			totalRoom += parseInt(jQuery(this).val());
		});		
		//Valido si el total de la capacidad de habitaciones es igual a la capacidad de ellas
		if(totalCapacity!=jQuery("#adults").val()){			
			showAlert(messageErrorAdult, "");
			return false;
		}
		totalRoom = 2*totalRoom;		
		//Valido que el numero de ninos no sea mayor al doble del numero de habitaciones
		if(parseInt(jQuery("#childs").val())>totalRoom){			
			showAlert(messageErrorChild, "");
			return false;
		}
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
	jQuery(".selectFeed").html("");
	//Establesco el simbolo de la moneda
	currency = objRate.currency.symbol;	
	//Estas son las tarifas por habitacion
	if(objRate.rates.rate!=null && objRate.rates.rate.length>0){
		//Oculto todos los elemntos de tipo param1
		jQuery(".content_param1").hide();
		//Oculto todos los elemntos de tipo param2
		jQuery(".detail_param2").hide();
		//Recorro el listado de precios por parametros
		jQuery(objRate.rates.rate).each(function(index, value){
			//Muestro la seccion de param 1 que tiene precio asociado
			jQuery("#content_param1"+value.param1).show();
			//Muestro la seccion param2 que tiene precio asociado
			jQuery("#content_param2"+value.param1+value.param2).show();
			//Creo una opcion para el combo con el tipo de alimentacion que tiene precio asignado
			var option = jQuery("<option>",{
				value: value.param3,
				text:feedArray[value.param3]
			});
			//Agrego la opcion al combo
			jQuery("#select_param3"+value.param1+value.param2).append(option);
			//Valido si el param1 no es un arreglo y lo convierto en arreglo
			if(!is_array(arrayRate[value.param1])){
				arrayRate[value.param1] = new Array();
			}
			//Valido si el param2 no es un arreglo y lo convierto en arreglo
			if(!is_array(arrayRate[value.param1][value.param2])){
				arrayRate[value.param1][value.param2] = new Array();
			}
			//Establesco el precio para los tres parametros de tarificacion
			arrayRate[value.param1][value.param2][value.param3] = value.totalPrice;			
		});
		var minPrice = 0;
		var objMinPrice=null;
		//Se recorren los combos de tipo de alimentacion para establecer el precio minimo
		jQuery(".selectFeed").each(function(){
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
	//Estas son las tarifas por nino
	if(objRate.childs.child!=null && objRate.childs.child.length>0){
		//limpio el combo de alimentacion de ninos
		jQuery("#select_childs").html("");
		//Recorro el listado de precios de ninos por parametros
		jQuery(objRate.childs.child).each(function(index, value){
			
			//Creo una opcion para el combo con el tipo de alimentacion que tiene precio asignado
			var option = jQuery("<option>",{
				value: value.param3,
				text:feedArray[value.param3]
			});
			
			//Agrego la opcion al combo
			jQuery("#select_childs").append(option);			
			//Establesco el precio para los tres parametros de tarificacion
			arrayRateChild[value.param3] = value.totalPrice;
			
		});
		if(jQuery("#childs").val()>0){
			jQuery("#seccion_childs").show();
		}
		calculateRateChild(jQuery("#select_childs"));
	}
	jQuery(".supplement_div").hide();
	//Calculo las tarifas de los suplementos
	if(objRate.supplements.supplement!=null && objRate.supplements.supplement.length>0){		
		jQuery(objRate.supplements.supplement).each(function(index, value){
			jQuery("#supplement_div"+value.id).show();
			//Si el suplemento es tipo 2 se cobra por el tiempo de estancia en el hotel
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
		//Cuando cambie el numero de huespedes se cambia el precio
		jQuery(".selectGuestSupplement").change(function(){
			var price = arrayRateSupplent2[jQuery(this).attr("supplementId")][jQuery(this).attr("dateSupplementId")]*jQuery(this).val();
			jQuery("#priceSupplement2"+jQuery(this).attr("supplementId")+jQuery(this).attr("dateSupplementId")).val(price);
			setTotal();
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
	var param1 = jQuery(obj).attr("param1");
	var param2 = jQuery(obj).attr("param2");	
	var param3 = jQuery("#select_param3"+param1+param2).val();	
	var quantity = jQuery("#cant_params"+param1+param2).val();
	if(quantity==0){
		jQuery("#hdn_param_price"+param1+param2).val(0);
		quantity=1;
	}else{
		jQuery("#hdn_param_price"+param1+param2).val(arrayRate[param1][param2][param3]*quantity);
	}
	var price = arrayRate[param1][param2][param3];//*quantity;
	
	var priceFormat = number_format( price, numDec, decSep, milSep );
	if(price>100000000){
		jQuery("#price"+param1+param2).css("font-size","11px");
	}else{
		jQuery("#price"+param1+param2).css("font-size","12px");
	}
	jQuery("#price"+param1+param2).html(priceFormat);
	setTotal();
	return price;
}
/**
 * This function calculate the rate of the child per feed type
 * @param obj
 * @returns 
 */
function calculateRateChild(obj) {	
	var param3 = jQuery(obj).val();
	var price = arrayRateChild[param3];//*parseInt(jQuery("#childs").val());
	jQuery("#hdn_param_price_child").val(price*parseInt(jQuery("#childs").val()));
	price = number_format( price, numDec, decSep, milSep );
	jQuery("#price_child").html(price);
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