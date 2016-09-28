jQuery(document).ready(function(){
	//Paginate data
	jQuery('#contentProducts').tablePagination(paginatorOptions);
	//Order data
	jQuery("#order_field").change(function(){
		//Valido que tipo de ordenamiento se requiere
		//jQuery(".fila_resultado_planes[filter='']").css("display", "block");
		switch(jQuery(this).val()){
			case "1": //Por precio
				jQuery(".fila_resultado_planes").sort(function(a,b){
					
				    return  parseFloat(jQuery(b).attr("price")) - parseFloat(jQuery(a).attr("price"));
				}).each(function(){
				    jQuery("#contentProducts").prepend(this);
				})
				break;
			case "2": //Por nombre
				jQuery(".fila_resultado_planes").sort(function(a,b){
				    return jQuery(a).attr("name").toLowerCase() < jQuery(b).attr("name").toLowerCase()? 1 : -1;
				}).each(function(){
				    jQuery("#contentProducts").prepend(this);
				})
				break;
			default: //Ordenamiento por defecto
				jQuery(".fila_resultado_planes").sort(function(a,b){
				    return parseFloat(jQuery(a).attr("default_order")) - parseFloat(jQuery(b).attr("default_order"));
				}).each(function(){
				    jQuery("#contentProducts").prepend(this);
				})
				break;
		}
		//paginatorOptions.ignoreRows=[];
		paginatorOptions.currPage=1;
		//Paginate data
		jQuery('#contentProducts').tablePagination(paginatorOptions);		
	});
	
	//Set the map link
	jQuery(".link_map").click(function(){
		var rel = jQuery(this).attr("rel");
		jQuery('#dialog').dialog({
		      modal: true,      
		      width: 830,
		      create: function(event, ui) { 
				
			  }
		  }).dialog('open');
		showMapAvailability("mapDialog", rel);
	});
	
	jQuery(".url_map").click(function(){
		var rel = jQuery(this).attr("rel");
		jQuery('#dialog').dialog({
		      modal: true,      
		      width: 830
		  }).dialog('open');
		showMapUrl("mapDialog", rel, "805", "480");
	});
	
});

/*****************************************  Filtros *********************************/
var decSep = "";
var milSep = "";
var numDec = "";
var orderCurrency = "";
var isFilterPrice = false;
var isFilterZone = false;
var isFilterStars = false;
var isFilterAccomodationType = false;
var isFilterTourismType = false;
var filterMaxPrice = 0;
var filterMinPrice = 0;
var filterCurrency = "";
var elementPrice = "";
var elementZone = "";
var elementStar = "";
var elementAccomodation = "";
var elementTourismType = "";

/**
 * Establece los valores por defecto de los filtros que sean diferentes al que se le envia
 * @param currentFilter
 */
function setDefaults(currentFilter){
	if(isFilterPrice && currentFilter!="price"){
		jQuery( "#"+elementPrice ).slider( "option", "values", [ filterMinPrice, filterMaxPrice ] );
		showPricesRange(filterMinPrice, filterMaxPrice, filterCurrency);
	}
	if(isFilterZone && currentFilter!="zone"){
		jQuery( "#"+elementZone+" option[value='']" ).attr("selected",true);
		
	}
	if(isFilterStars && currentFilter!="star"){
		jQuery( "."+elementStar).attr("checked",true);
		
	}
	if(isFilterAccomodationType && currentFilter!="accomodation"){
		jQuery( "."+elementAccomodation).attr("checked",true);
		
	}
	if(elementTourismType && currentFilter!="tourismType"){
		jQuery( "#"+elementTourismType+" option[value='']" ).attr("selected",true);
		
	}	
	//Valida si toda la informacion esta oculta y muestra el mensaje correspondiente
	if(jQuery("#contentProducts").find(".fila_resultado_planes:visible").length==0){
		//jQuery("#message_not_found").show();
	}else{
		//jQuery("#message_not_found").hide();
	}
}

/**
 * Establece el filtro de precios
 * @param minPrice
 * @param maxPrice
 * @param currency
 */
function setFilterPrice(idElement, minPrice, maxPrice, currency){	
	isFilterPrice = true;
	filterMaxPrice = maxPrice;
	filterMinPrice = minPrice;
	filterCurrency = currency;
	elementPrice = idElement;
	jQuery("#"+idElement).slider({ 
		  range: true,
		  min: minPrice, 
		  max: maxPrice,
		  orientation: "horizontal",
		  values: [ minPrice, maxPrice ],
		  stop: function( event, ui ) {
		  	filterPrice(ui.values[0], ui.values[1], currency);			
		  }, 
		  slide: function(event,ui){
			  showPricesRange(ui.values[0], ui.values[1], currency);
			  jQuery("#priceSlider").parent().css("overflow","visible");
			  jQuery("#priceSlider").parent().attr("class","parent_slider");
		  }
	 });
}

/**
 * Realiza el filtro de precios segun los datos seleccionados
 * @param minPrice
 * @param maxPrice
 * @param currency
 */
function filterPrice(minPrice, maxPrice, currency){	
	showPricesRange(minPrice, maxPrice, currency);
	jQuery(".fila_resultado_planes").each(function(index,value){
		var precio = jQuery(value).attr("price");		
		if(precio<=maxPrice && precio>=minPrice){			
			jQuery(value).attr("filter","");
			jQuery(value).css("display","block");
		}else{			
			jQuery(value).attr("filter","price");
			jQuery(value).css("display","none");
		}
	});
	paginatorOptions.ignoreRows = jQuery(".fila_resultado_planes[filter='price']");
	paginatorOptions.currPage=1;
	//Paginate data
	jQuery('#contentProducts').tablePagination(paginatorOptions);
	
	//Set the defaults other filters
	setDefaults("price");

}

/**
 * Realiza el formateo y muestra los rangos de precio
 * @param minPrice
 * @param maxPrice
 * @param currency
 */
function showPricesRange(minPrice, maxPrice, currency){
	var priceIzq = number_format( minPrice, numDec, decSep, milSep );
	var priceDer = number_format( maxPrice, numDec, decSep, milSep );
	var cadenaIzq = orderCurrency;
	cadenaIzq = cadenaIzq.replace(/%c/, currency);
	cadenaIzq = cadenaIzq.replace(/%v/, priceIzq);	
	var cadenaDer = orderCurrency;
	cadenaDer = cadenaDer.replace(/%c/, currency);
	cadenaDer = cadenaDer.replace(/%v/, priceDer);	
	jQuery("#izqPrice").html(cadenaIzq);
	jQuery("#derPrice").html(cadenaDer);
}

/**
 * Establece el filtro por estrellas
 * @param classElement
 */
function setFilterStar(classElement){
	isFilterStars = true;
	elementStar = classElement;
	jQuery("."+classElement).click(function(){
		if(jQuery("."+classElement+":checked").length>0){
			filterStar(classElement);
		}else{
			jQuery(this).attr("checked", true);
		}
		
	});
}

/**
 * Realiza el filtro por estrellas
 * @param classElement
 */
function filterStar(classElement){
	
	jQuery("."+classElement).each(function(index,value){
		//alert(jQuery(this).val());
		if(jQuery(this).attr("checked")){
			jQuery(".fila_resultado_planes[stars='"+jQuery(this).val()+"']").attr("filter","");
			jQuery(".fila_resultado_planes[stars='"+jQuery(this).val()+"']").css("display","block");
		}else{
			jQuery(".fila_resultado_planes[stars='"+jQuery(this).val()+"']").attr("filter","stars");
			jQuery(".fila_resultado_planes[stars='"+jQuery(this).val()+"']").css("display","none");
		}
	});
	
	paginatorOptions.ignoreRows = jQuery(".fila_resultado_planes[filter='stars']");
	paginatorOptions.currPage=1;
	//Paginate data
	jQuery('#contentProducts').tablePagination(paginatorOptions);
	//Set the defaults other filters
	setDefaults("star");
}

/**
 * Establece el filtro por zonas
 * @param idElement
 */
function setFilterZone(idElement){
	isFilterZone = true;
	elementZone = idElement;
	jQuery("#"+idElement).change(function(){		
		filterZone(jQuery(this).val());
	});
}

/**
 * Realiza el filtro por zona
 * @param value
 */
function filterZone(value){
	
	
	if(value!=""){
		jQuery(".fila_resultado_planes").attr("filter","zone");
		jQuery(".fila_resultado_planes").css("display","none");
		jQuery(".fila_resultado_planes[zone='"+value+"']").attr("filter","");
		jQuery(".fila_resultado_planes[zone='"+value+"']").css("display","block");
	}else{
		jQuery(".fila_resultado_planes").attr("filter","");
		jQuery(".fila_resultado_planes").css("display","block");
	}
	
	
	paginatorOptions.ignoreRows = jQuery(".fila_resultado_planes[filter='zone']");
	paginatorOptions.currPage=1;
	//Paginate data
	jQuery('#contentProducts').tablePagination(paginatorOptions);
	//Set the defaults other filters
	setDefaults("zone");
}

/**
 * Establece el filtro por tipo de alojamiento
 * @param classElement
 */
function setFilterAccomodation(classElement){
	isFilterAccomodationType=true;
	elementAccomodation = classElement;
	jQuery("."+classElement).click(function(){
		if(jQuery("."+classElement+":checked").length>0){
			filterAccomodation(classElement);
		}else{
			jQuery(this).attr("checked", true);
		}
		
	});
}

/**
 * Realiza el filtro por tipo de alojamiento
 * @param classElement
 */
function filterAccomodation(classElement){
	
	jQuery("."+classElement).each(function(index,value){
		//alert(jQuery(this).val());
		if(jQuery(this).attr("checked")){
			jQuery(".fila_resultado_planes[accomodation='"+jQuery(this).val()+"']").attr("filter","");
			jQuery(".fila_resultado_planes[accomodation='"+jQuery(this).val()+"']").css("display","block");
		}else{
			jQuery(".fila_resultado_planes[accomodation='"+jQuery(this).val()+"']").attr("filter","accomodation");
			jQuery(".fila_resultado_planes[accomodation='"+jQuery(this).val()+"']").css("display","none");
		}
	});
	
	paginatorOptions.ignoreRows = jQuery(".fila_resultado_planes[filter='accomodation']");
	paginatorOptions.currPage=1;
	//Paginate data
	jQuery('#contentProducts').tablePagination(paginatorOptions);
	//Set the defaults other filters
	setDefaults("accomodation");
}

/**
 * Establece el filtro por tipo de turismo
 * @param idElement
 */
function setFilterTourismType(idElement){
	isFilterTourismType=true;
	elementTourismType = idElement;
	jQuery("#"+idElement).change(function(){		
		filterTourismType(jQuery(this).val());
	});
}

/**
 * Realiza el filtro por tipo de turismo
 * @param value
 */
function filterTourismType(value){	
	if(value!=""){
		jQuery(".fila_resultado_planes").attr("filter","tourismtype");
		jQuery(".fila_resultado_planes").css("display","none");
		jQuery(".tourismType_param[value='"+value+"']").parents(".fila_resultado_planes").attr("filter","");
		jQuery(".tourismType_param[value='"+value+"']").parents(".fila_resultado_planes").css("display","block");
	}else{
		jQuery(".fila_resultado_planes").attr("filter","");
		jQuery(".fila_resultado_planes").css("display","block");
	}
	
	
	paginatorOptions.ignoreRows = jQuery(".fila_resultado_planes[filter='tourismtype']");
	paginatorOptions.currPage=1;
	//Paginate data
	jQuery('#contentProducts').tablePagination(paginatorOptions);
	//Set the defaults other filters
	setDefaults("tourismType");
}