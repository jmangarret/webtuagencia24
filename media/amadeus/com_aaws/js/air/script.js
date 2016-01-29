// Objecto que contiene los metodos publicos de disponibilidad de vuelos
var AirAvailability = {};

// Objeto que contiene los metodos publicos de pasajeros de vuelos
var AirDetail = {};

// Construccion del objeto de vuelos
(function($, container){

    // Altura minima del contenedor de los filtros
    var __minHeight = 0;

    // Expresion regular que incluye todos los filtros, para poder conocerlos
    var __FilterRegex = /f-price|f-time|f-airline|f-stops/;

    // Funcion para formatear un número a tipo moneda
    var _format = function (num, prefix){
        prefix = prefix || '';
        num += '';

        var splitStr = num.split('.'),
            splitLeft = splitStr[0],
            splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '',
            regx = /(\d+)(\d{3})/;

        while(regx.test(splitLeft))
            splitLeft = splitLeft.replace(regx, '$1' + '.' + '$2');

        return prefix + splitLeft + splitRight;
    }

    // Valida todos los filtros y decide si se debe mostrar o no
    var _validateRecommendation = function(element, visibility, reason){
        element = $(element), reason = 'f-' + reason;

        if(visibility == 'show'){
            if(element.hasClass('hide-option')){
                element.removeClass(reason);

                if(!element.attr('class').match(__FilterRegex))
                    element.addClass('show-option').removeClass('hide-option');
            }
        }else{
            element.removeClass('show-option');

            if(element.hasClass('hide-option'))
                element.addClass(reason);
            else
                element.addClass('hide-option ' + reason);
        }
        
    }
    
    // Oculta los elementos marcados como ocultos y muestra los marcados como visibles.
    var _showAndHiddenRecommendations = function(validateFlights){

        // En caso que se filtre por los vuelos (Filtros de aerolineas, horas y paradas), se valida que recomendacion
        // debe quitarse. (El parametro es el tipo de filtro que se aplica)
    	
    	
    
        if(validateFlights != undefined){
        
            // Se valida si la recomendacion se debe mostrar o no
            
            
        	if(validateFlights =='todo'){
           	  
        		
        	    // Se valida si la recomendacion se debe mostrar o no
                $('#recommendations .flights').each(function(index, element){
                    _validateRecommendation($(element).parents('.recommendation'), 'show', 'airline');
                    _validateRecommendation($(element).parents('.recommendation'), 'show', 'price');
                    _validateRecommendation($(element).parents('.recommendation'), 'show', 'stops');
                    _validateRecommendation($(element).parents('.recommendation'), 'show', 'time');
                    $('input:checkbox').attr("checked","checked");
                    $('#left-container').removeAttr('style');
                    
                });
                 
                var _heightRecommendation = $('#right-container').height() > __minHeight ?  $('#right-container').height() : __minHeight;
                    _offset               = _heightRecommendation + $('#right-container').offset().top - $(window).height();

                if(_offset > $('#right-container').height())
                    $('html, body').scrollTo(0, 0);
                else if($(window).scrollTop() > _offset)
                    $('html, body').scrollTo(0, _offset);

                    
        		
        	}else{
        		$('#recommendations .flights').each(function(index, element){
                    
                    var options = $.map($(element).children('div'), function(segment){
                        // Se dice que si de las opciones, todas se oculatan por el mismo filtro, la recomendacion
                        // se oculto por ese filtro. Entonces si de alguna manera, aplicando el mismo filtro (y este muestra
                        // al menos una recomendacion), este filtro se quita como razon de ocultar la recomendacion, y el 
                        // proceso mas adelanta decide si se debe mostrar o no.
                        return $(segment).children('.flight-option').length - $(segment).children('.flight-option.hide-option.f-' + validateFlights).length;
                    });
                 
                    if($.inArray(0, options) >= 0)
                        _validateRecommendation($(element).parents('.recommendation'), 'hide', validateFlights);
                    else
                        _validateRecommendation($(element).parents('.recommendation'), 'show', validateFlights);
                });
        	}  
        	if(validateFlights !='todo'){
        	 $('.show-option').filter(':not(:visible)').addClass('-tmp-showed').show();
             $('.hide-option').filter(':visible').addClass('-tmp-hidden').hide();

             var _heightRecommendation = $('#right-container').height() > __minHeight ?  $('#right-container').height() : __minHeight;
                 _offset               = _heightRecommendation + $('#right-container').offset().top - $(window).height();

             if(_offset > $('#right-container').height())
                 $('html, body').scrollTo(0, 0);
             else if($(window).scrollTop() > _offset)
                 $('html, body').scrollTo(0, _offset);

             $('.-tmp-hidden').show().removeClass('-tmp-hidden');
             $('.-tmp-showed').hide().removeClass('-tmp-showed');

             // Ocultando todos los detalles de los vuelos
             $('#recommendations .flight-detail').hide({effect: 'blind', duration: "slow"});

             $('.recommendation.hide-option').hide({effect: 'blind', duration: "slow"});
             $('.recommendation.show-option').show({effect: 'blind', duration: "slow"});

             $('#recommendations .flight-option.hide-option').hide({effect: 'blind', duration: "slow"});
             $('#recommendations .flight-option.show-option').show({effect: 'blind', duration: "slow"});

            // $('#left-container').animate({height: _heightRecommendation + 'px'}, 'slow');
             $('#left-container').removeAttr('style');
        	}
        }else{
        	 $('.show-option').filter(':not(:visible)').addClass('-tmp-showed').show();
             $('.hide-option').filter(':visible').addClass('-tmp-hidden').hide();

             var _heightRecommendation = $('#right-container').height() > __minHeight ?  $('#right-container').height() : __minHeight;
                 _offset               = _heightRecommendation + $('#right-container').offset().top - $(window).height();

             if(_offset > $('#right-container').height())
                 $('html, body').scrollTo(0, 0);
             else if($(window).scrollTop() > _offset)
                 $('html, body').scrollTo(0, _offset);

             $('.-tmp-hidden').show().removeClass('-tmp-hidden');
             $('.-tmp-showed').hide().removeClass('-tmp-showed');

             // Ocultando todos los detalles de los vuelos
             $('#recommendations .flight-detail').hide({effect: 'blind', duration: "slow"});

             $('.recommendation.hide-option').hide({effect: 'blind', duration: "slow"});
             $('.recommendation.show-option').show({effect: 'blind', duration: "slow"});

             $('#recommendations .flight-option.hide-option').hide({effect: 'blind', duration: "slow"});
             $('#recommendations .flight-option.show-option').show({effect: 'blind', duration: "normal"});

             $('#left-container').animate({height: _heightRecommendation + 'px'}, 'normal');
          
        	
        	
        }

          
      	if(validateFlights =='todo'){
      		
      
      	
      		
      		var pmin = $("#price-min").attr("data-min");
      		var pmax = $("#price-max").attr("data-max");
      		$("#pmin-value").text(pmin);
      		$("#pmax-value").text(pmax);
      		 vmin= pmin.split('.');
             tmin =vmin[0];
             vmax = pmax.split('.');
             tmax= vmax[0];
      	  // Slider para filtrar por precio
   
      $('#price-range').each(function(index, element){	
    	  var element = $(element),
          min = element.siblings('.r-min').data('min'),
          max = element.siblings('.r-max').data('max');
    	  
      	  element.slider({
              range:  true,
              min:    min,
              max:    max,
              values: [min, max],
              slide:  function(event, ui){
                  $(this).siblings('.r-min').children('.value').text(_format(ui.values[0]));
                  $(this).siblings('.r-max').children('.value').text(_format(ui.values[1]));
              }
          }).on('slidechange', function(event, ui){
              $('#recommendations .recommendation').each(function(index, element){
                  var value = $(element).data('value');
                  
              });

              
          });
      });
      	
        var hmin0 = $("#timemi-0").attr("data-min");
		var hmax0 = $("#timema-0").attr("data-max");
		$("#hmi-0").text(hmin0);
		$("#hma-0").text(hmax0);
		var hmin1 = $("#timemi-1").attr("data-min");
		var hmax1 = $("#timema-1").attr("data-max");
		$("#hmi-1").text(hmin1);
		$("#hma-1").text(hmax1);
		var hmin2 = $("#timemi-2").attr("data-min");
		var hmax2 = $("#timema-2").attr("data-max");
		$("#hmi-2").text(hmin0);
		$("#hma-2").text(hmax0);
      // Slider para filtrar por hora
      $('.jui-slider.time').each(function(index, element){
          var element = $(element),
              min = element.siblings('.r-min').data('min'),
              max = element.siblings('.r-max').data('max');

          min = min.split(':');
          max = max.split(':');

          min = (~~min[0] * 60) + ~~min[1];
          max = (~~max[0] * 60) + ~~max[1];

          element.slider({
              range:  true,
              min:    min,
              max:    max,
              values: [min, max],
              slide:  function(event, ui){
                  var time = ui.values[0], hour = '';
                  hour = ('00' + ~~(time/60)).replace(/0*(\d{2})$/, '$1') + ':' +('00' + ~~(time%60)).replace(/0*(\d{2})$/, '$1');
                  $(this).siblings('.r-min').children('.value').text(hour);

                  time = ui.values[1];
                  hour = ('00' + ~~(time/60)).replace(/0*(\d{2})$/, '$1') + ':' +('00' + ~~(time%60)).replace(/0*(\d{2})$/, '$1');
                  $(this).siblings('.r-max').children('.value').text(hour);
              }
          }).on('slidechange', function(event, ui){
              var segment = $(this).attr('id').split('-')[1];

              $('#recommendations .flight-option.segment-' + segment).each(function(index, element){
                  var departure = $(element).data('departure');

              
              });

               
          });
      });
      $('#left-container').removeAttr('style');
      if($('.recommendation.hide-option').hasClass('hide-option')){
    	  $('.recommendation.hide-option').removeClass('hide-option');
    	  $('.recommendation.f-time').removeClass('f-time');
    	  $('.recommendation').css("display", "block");
      }
      if($('#recommendations .flight-option').hasClass('hide-option')){
    	  $('#recommendations .flight-option').removeClass('hide-option');
    	  $('#recommendations .flight-option').removeClass('f-stops');
    	  $('#recommendations .flight-option').css("display", "block");
      }
      
      	}
    }

   


    //Se agregan los slides de los filtros (Precios y Horas), ademas de los otros filtros
    var initializeFilters = function(){

        //START PRE-PROCESO de la informacion
        //Se traducen las fechas a numeros, para poder aplicar los filtros mas rapidos
        $('#recommendations .flight-option').each(function(index, element){
            var time = $(element).data('departure');

            time = time.split(':');
            time = (~~time[0] * 60) + ~~time[1];

            $(element).data('departure', time).addClass('show-option');
        });


        //Se organizan las combinaciones para validar los vuelos seleccionados
        $('#recommendations .recommendation').each(function(index, element){
            var combinations = $(element).data('combinations');

            if(combinations != ''){
                combinations = $.map(combinations.split(/\)\(/), function(element){
                    return new Array(element.replace(/\)|\(/, '').split(/,/));
                });
                $(element).data('combinations', combinations);
                $(element).data('currentComb', new Array(combinations[0].length));
            }else{
                $(element).data('combinations', []);
            }
        });
        //END PRE-PROCESO de la informacion


        // Slider para filtrar por precio
        $('#price-range').each(function(index, element){
            var element = $(element),
                min = element.siblings('.r-min').data('min'),
                max = element.siblings('.r-max').data('max');

            element.slider({
                range:  true,
                min:    min,
                max:    max,
                values: [min, max],
                slide:  function(event, ui){
                    $(this).siblings('.r-min').children('.value').text(_format(ui.values[0]));
                    $(this).siblings('.r-max').children('.value').text(_format(ui.values[1]));
                }
            }).on('slidechange', function(event, ui){
                $('#recommendations .recommendation').each(function(index, element){
                    var value = $(element).data('value');

                    if(value < ui.values[0] || value > ui.values[1])
                        _validateRecommendation(element, 'hide', 'price');
                    else
                        _validateRecommendation(element, 'show', 'price');
                });

                _showAndHiddenRecommendations();
            });
        });


        // Slider para filtrar por hora
        $('.jui-slider.time').each(function(index, element){
            var element = $(element),
                min = element.siblings('.r-min').data('min'),
                max = element.siblings('.r-max').data('max');

            min = min.split(':');
            max = max.split(':');

            min = (~~min[0] * 60) + ~~min[1];
            max = (~~max[0] * 60) + ~~max[1];

            element.slider({
                range:  true,
                min:    min,
                max:    max,
                values: [min, max],
                slide:  function(event, ui){
                    var time = ui.values[0], hour = '';
                    hour = ('00' + ~~(time/60)).replace(/0*(\d{2})$/, '$1') + ':' +('00' + ~~(time%60)).replace(/0*(\d{2})$/, '$1');
                    $(this).siblings('.r-min').children('.value').text(hour);

                    time = ui.values[1];
                    hour = ('00' + ~~(time/60)).replace(/0*(\d{2})$/, '$1') + ':' +('00' + ~~(time%60)).replace(/0*(\d{2})$/, '$1');
                    $(this).siblings('.r-max').children('.value').text(hour);
                }
            }).on('slidechange', function(event, ui){
                var segment = $(this).attr('id').split('-')[1];

                $('#recommendations .flight-option.segment-' + segment).each(function(index, element){
                    var departure = $(element).data('departure');

                    if(departure < ui.values[0] || departure > ui.values[1])
                        _validateRecommendation(element, 'hide', 'time');
                    else
                        _validateRecommendation(element, 'show', 'time');
                });

                _showAndHiddenRecommendations('time');
            });
        });

        
       
        //Filtro de las aerolineas
        $('#filters .airlines input[type=checkbox]').change(function(){
            var airline = $(this).val(), check = $(this).is(':checked');
           
            $('#recommendations .flight-option.' + airline).each(function(index, element){
                if(!check)
                    _validateRecommendation(element, 'hide', 'airline');
                else
                    _validateRecommendation(element, 'show', 'airline');
            });

            _showAndHiddenRecommendations('airline');
            $('#left-container').removeAttr('style');
        });


        //Filtro de las paradas
        $('#filters .stops input[type=checkbox]').change(function(){
            var stops = $(this).val(), check = $(this).is(':checked');

            $('#recommendations .flight-option.stops-' + stops).each(function(index, element){
                if(!check)
                    _validateRecommendation(element, 'hide', 'stops');
                else
                    _validateRecommendation(element, 'show', 'stops');
            });

            _showAndHiddenRecommendations('stops');
            $('#left-container').removeAttr('style');
        });


        //Listener para las opciones de vuelos, para que aplique las combinaciones
        $('#recommendations input[id^=fo]').click(function(){
            var combinations   = $(this).parents('.recommendation').data('combinations'),
                segment        = ~~$(this).parents('.flight-option').attr('class').replace(/^.*segment-(\d+).*$/, '$1'),
                flight         = ~~$(this).attr('class').replace(/^.*flight-(\d+).*$/, '$1');

            // Se quitan las clases de seleccionado y error
            $('#recommendations .segment-' + segment).removeClass('selected error');
            $('#recommendations .error-msg').remove();
            $(this).parents('.flight-option').addClass('selected');

            if(combinations.length){
                var recommendation = $(this).parents('.recommendation'),
                    currentComb    = recommendation.data('currentComb'),
                    j              = combinations[0].length;

                // En caso que se seleccione una opcion que previamente fue deshabilitada, se
                // resetean todas las opciones, para que inicie a seleccionar de nuevo los vuelos
                if($(this).parents('.flight-option.disabled').length){
                    var self = this;
                    currentComb = new Array(currentComb.length);

                    recommendation.find('.flight-option input').filter(':checked').each(function(index, element){
                        if($(element).is(self)) return true;
                        $(element).prop('checked', false);
                    });

                    recommendation.find('.flight-option.disabled').removeClass('disabled');
                }

                // Se guarda la opcion seleccionada
                currentComb[segment] = flight;

                // Se deshabilitan todas las opciones, para seleccionar solo aquellas que si se pueden usar
                recommendation.find('.flight-option').filter(':not(.segment-' + segment + ')').addClass('disabled');

                $.each(combinations, function(index, element){
                    if(element[segment] == flight){

                        for(var i = 0; i < j; i++){
                            // Si la posicion corresponde al segmento actual, no se debe hacer nada
                            if(i == segment) continue;

                            // Siempre se analiza el vuelo, de acuerdo a las opciones seleccioandas previamente.
                            // Siempre debe haber al menos una opcion seleccionada, en caso que no este seleccionada
                            // no se toma en cuenta para el analisis
                            var showRecommendation = true;

                            // Se recorre los que se han seleccionado, para saber si el actual puede ser seleccionado
                            for(var k = 0; k < j; k++){
                                // Si el que se esta analizando y la posicion del valor comparado son iguales,
                                // no se analiza
                                if(k != i && currentComb[k] != undefined){
                                    if(element[k] == currentComb[k])
                                        showRecommendation = showRecommendation && true;
                                    else
                                        showRecommendation = showRecommendation && false;
                                }
                            }

                            if(showRecommendation)
                                recommendation.find('.flight-option.segment-' + i + '.flight-' + element[i] + '.disabled').removeClass('disabled');
                        }
                    }
                });

                recommendation.data('currentComb', currentComb);
            }
        });


        // Listener para los botones de enviar o submit
        $('.values .button input[type=submit]').click(function(){

            if($(this).data('sendClick')) return;
                
            var sendForm = true;

            //=========================================================================
            // $(this).data('sendClick', true); // Para que no reciba multiples clicks
            //
            //=========================================================================
            //$(this).data('sendClick', true); // Para que no reciba multiples clicks
            $('#recommendations .error-msg').remove();
            $('#recommendations .flight-option.error').removeClass('error');

            $(this).parents('.recommendation').find('.flights > div').each(function(index, element){
                element = $(element);
                var inputs = element.find('.segment-' + index + '.show-option input');
                if(inputs.length > 1){
                    if(!inputs.filter(':checked').length){
                        sendForm = false;
                        $('#recommendations .flight-detail').hide({effect: 'blind', duration: "slow"});
                        element.find('.flight-option').addClass('error');
                        element.find('.segment-header')
                            .append($('<div class="error-msg">Por favor selecciona un vuelo</div>'));
                        return false;
                    }
                } else {
                    element.find('.segment-' + index).removeClass('error');
                    $('#recommendations .error-msg').remove();
                    inputs.prop('checked', true);
                }
            });

            if(!sendForm)
                return false;

            $('#recommendation').val($(this).data('fare-code'));
        });


        // Listener para los titulos del los filtros, para que se puedan abrir y cerrar
        $('#filters > div > h4').click(function(){
            $(this).siblings('.content').toggle({effect: 'blind', duration: 'slow'});
            $(this).toggleClass('visible collapsed');
            $('#left-container').removeAttr('style');
        });

        $('#filters > div > h4').each(function(index, element){
            if($(element).hasClass('collapsed'))
                $(element).siblings('.content').hide();
            $('#left-container').removeAttr('style');
        });

    }

    // Inicializa los listeners de la matriz de aerolineas 
    var initializeAirlinesFilters = function(){
        // Inicializa el carruse de las aerolineas, si hay mas de 4
        if($('#airlines-list .als-item').length > 6){
            $("#airlines-list").als({
                visible_items: 6,
                scrolling_items: 1,
                orientation: "horizontal",
                circular: "yes",
                autoscroll: "no"
            }); 
        }

        // coloca el listener de todas las opciones de aerolineas existentes
        $('#airlines .fire-recommend').click(function(){
        	$('#left-container').removeAttr('style');
            var quoteid = $(this).data('recommendation');
            if(quoteid != ''){
                $('.recommendation').removeClass('show-option').addClass('hide-option');
                $('#' + quoteid).addClass('show-option').removeClass('hide-option');
                _showAndHiddenRecommendations();
            }
        });

        // Listener para mostrar todas las recomendaciones
        $('#airlines .all-airlines').click(function(){
        	$('#left-container').removeAttr('style');
            _showAndHiddenRecommendations('todo');
        });
    }


    // Listener para ver el detalle del vuelo
    var initializeDetailsListeners = function(){
        $('#recommendations .flight-option .detail .value').click(function(){
            var detail = $(this).parents('.flight-option').find('.flight-detail'),
                stop   = detail.find('.stop-info');

            // Se calcula el tiempo de espera, de acuerdo a la fecha del vuelo, en caso que tenga escalas
            if(stop.length && stop.data('arrival') != ''){
                stop.each(function(index, element){
                    var currentStop = $(element),
                        ad = currentStop.data('arrival').split(/-|T|:/),
                        dd = currentStop.data('departure').split(/-|T|:/),
                        minutes = 0;

                    // Transformando las fechas a javascript
                    ad = new Date(ad[0], ad[1] - 1, ad[2], ad[3], ad[4], ad[5]);
                    dd = new Date(dd[0], dd[1] - 1, dd[2], dd[3], dd[4], dd[5]);

                    minutes = (dd - ad) / (1000 * 60);

                    // Reemplazando los valores en el texto
                    currentStop.html(currentStop.html()
                        .replace(/%h/, Math.floor(minutes / 60))
                        .replace(/%m/, minutes % 60)
                        .replace(/([1-9]|[1-9][0-9]){([^}]*)}/g, '$1$2')
                        .replace(/[^1-9]0{([^}]*)}/g, '')
                    );

                    // Colocando vacio el valor, para que no calcule la duracion de nuevo
                    currentStop.data('arrival', '');
                });
            }

            $('#recommendations .flight-detail').not(detail).hide({effect: 'blind', duration: "slow"});
            detail.toggle({effect: 'blind', duration: "slow"});
           p=$(this).parents('.flight-option').find('.flight-detail').attr('id');
            
            //Llamo a mapa
            mapa(p); 
            
            var res = p.split('_');  
            
        	$(".contenido_tab_"+res[1]+'_'+res[2]).hide(); //Ocultar capas
        	$("#tab-detalles_"+res[1]+'_'+res[2]).show();
        	$("ul.tabs li:first").addClass("activa").show(); //Activar primera pesta�a
        	$(".contenido_tab"+res[1]+'_'+res[2]+":first").show(); //Mostrar contenido primera pesta�a

        	// Sucesos al hacer click en una pesta�a
        	$("ul.tabs li").click(function() {
        	$("ul.tabs li").removeClass("activa"); //Borrar todas las clases \activa
        	$(this).addClass("activa"); //Añadir clase "activa" a la pestaña seleccionada
        	$(".contenido_tab_"+res[1]+'_'+res[2]).hide(); //Ocultar todo el contenido de la pestaña
        	var activatab = $(this).find("a").attr("href"); //Leer el valor de href para identificar la pesta�a activa 
        	$(activatab).fadeIn(); //Visibilidad con efecto fade del contenido activo
          // mapa(p);
          google.maps.event.trigger( map, 'resize' );
          // c = new google.maps.LatLng(7.530810103397045,-66.12670706373598);
          map.setCenter(c); 
        	return false;
        	});
});

        $('#recommendations .flight-option .flight-detail .close').click(function(){
            $(this).parents('.flight-option').find('.flight-detail').toggle({effect: 'blind', duration: "slow"});
        });
    }


    var geocoder;
    var map;
    

    // Inicializa el mapa
    var mapa = function(algo){
  
      	   	var datos = algo
      	    var res = algo.split('_');  
    
      		 
      	  geocoder = new google.maps.Geocoder();
      	  var latlng = new google.maps.LatLng(-34.397, 150.644);
      	  var mapOptions = {
      	    zoom: 5,
      	    center: latlng,
      	    mapTypeId: google.maps.MapTypeId.ROADMAP
      	}

      	    map = new google.maps.Map(document.getElementById('mapcanvas_'+res[1]+'_'+res[2]), mapOptions);    
      	 var arrayCoordinates = new Array();  
      	
     	$(".getval_"+res[1]+'_'+res[2]).each(function() {
     		
     		var address = $(this).attr('value');
     		var airport =$(this).attr('airport');
 
            geocoder.geocode( { 'address': address}, function(results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                   
                });
                arrayCoordinates[arrayCoordinates.length] = results[0].geometry.location;
                var infowindow = new google.maps.InfoWindow({
                	
                    content: airport
                  });
                google.maps.event.addListener(marker, 'click', function () {          
                    infowindow.open(map,marker);       
                });
                
                
            	
              } else {
               // alert('Geocode was not successful for the following reason: ' + status);
              }
            
              if(arrayCoordinates.length >0){
                  var flightPath = new google.maps.Polyline({
                    path: arrayCoordinates,
                    strokeColor: "#FF0000",
                    strokeOpacity: 1.0,
                    strokeWeight: 2,
                    map : map
                  });
              }
           	var polyOptions = {
           		    strokeColor: '#000000',
           		    strokeOpacity: 1.0,
           		    strokeWeight: 3
           		  }
            });
         
     	});
     	
       
    }
    


    //Funcion que se encarga de ejecutar todos los inicializadores
    var load = function(){
        initializeFilters();
        initializeAirlinesFilters();
        initializeDetailsListeners();
        //$("#filters").jScroll({speed : 800});
    }


    // Funcion para setear el minimo alto del div de los filtros (aqui left-container)
    var setMinHeight = function(minHeight){
        __minHeight = minHeight;
    }

    // Funcion para calcular el alto inicial del div contenedor de los filtros
    var initialHeight = function() {
        setMinHeight($('#left-container').height());

        if($('#left-container').height() < $('#right-container').height())
            $('#left-container').height($('#right-container').height());
    }

    // Exponiendo los metodos necesarios
    $.extend(container, {
        load: load,
        initialHeight: initialHeight
    });
})(jQuery, AirAvailability);


(function($, container){

    var __EMAIL = '', __URL = '';

    // Funcion que agrega un elemento para mostrar un mensaje, al lado del div
    var putMessage = function(element, message, clase){
        var element = $(element);
        // Borramos previos mensajes
        element.siblings('span.msg-validation').remove();
        element.parent('div').removeClass('error, info, success');

        if(message == '') return;

        // Agregamos el nuevo mensaje
        var span = $('<span class="msg-validation ' + clase + '">' + message + '</span>');
        if(element.nextAll().length)
            element.nextAll().last().after(span);
        else
            element.after(span);

        element.parent('div').addClass(clase);

        // De acuerdo a como se despliegue el elemento, se mostrara
        if(span.css('display') == 'block')
            span.hide().show({effect: 'blind', duration: "fast"});
        else
            span.hide().show('fast');
    }


    // Listener para ver el detalle del vuelo
    var initializeDetailsListeners = function(){
        $('#passenger_main #resume-flight div.detail a').click(function(){
            var detail = $(this).siblings('.flight-detail'),
                stop   = detail.find('.stop-info');

            // Se calcula el tiempo de espera, de acuerdo a la fecha del vuelo, en caso que tenga escalas
            if(stop.length && stop.data('arrival') != ''){
                stop.each(function(index, element){
                    var currentStop = $(element),
                        ad = currentStop.data('arrival').split(/-|T|:/),
                        dd = currentStop.data('departure').split(/-|T|:/),
                        minutes = 0;

                    // Transformando las fechas a javascript
                    ad = new Date(ad[0], ad[1] - 1, ad[2], ad[3], ad[4], ad[5]);
                    dd = new Date(dd[0], dd[1] - 1, dd[2], dd[3], dd[4], dd[5]);

                    minutes = (dd - ad) / (1000 * 60);

                    // Reemplazando los valores en el texto
                    currentStop.html(currentStop.html()
                        .replace(/%h/, Math.floor(minutes / 60))
                        .replace(/%m/, minutes % 60)
                        .replace(/([1-9]|[1-9][0-9]){([^}]*)}/g, '$1$2')
                        .replace(/[^1-9]0{([^}]*)}/g, '')
                    );

                    // Colocando vacio el valor, para que no calcule la duracion de nuevo
                    currentStop.data('arrival', '');
                });
            }

            // Se posiciona en relacion del enlace, para que se muestre debajo, en caso que no se halla
            // posicionado.
            if(!detail.hasClass('positioned')) {
                detail.position({
                    my: 'right+20 top+7',
                    at: 'right bottom',
                    of: $(this),
                    collision: 'none'
                }).addClass('positioned');
            }

            $('#passenger_main .flight-detail').not(detail).hide({effect: 'fade'});
            detail.toggle({effect: 'fade'});
            
            p=$(this).siblings('.flight-detail').attr('id');
            
            //Llamo a mapa
            mapa(p); 
        });

        $('#passenger_main #resume-flight .flight-detail .close').click(function(){
            $(this).parents('.flight-detail').toggle({effect: 'fade'});
        });
        
        
        var geocoder;
        var map;
        

        // Inicializa el mapa
        var mapa = function(algo){
      
          	   	var datos = algo
          	    var res = algo.split('_');  
        
          		 
          	  geocoder = new google.maps.Geocoder();
          	  var latlng = new google.maps.LatLng(-34.397, 150.644);
          	  var mapOptions = {
          	    zoom: 6,
          	    center: latlng,
          	    mapTypeId: google.maps.MapTypeId.ROADMAP
          	}

          	    map = new google.maps.Map(document.getElementById('mapcanvas_'+res[1]+'_'+res[2]), mapOptions);    
          	 var arrayCoordinates = new Array();  
          	
         	$(".getval_"+res[1]+'_'+res[2]).each(function() {
         		
         		var address = $(this).attr('value');
         		var airport =$(this).attr('airport');
     
                geocoder.geocode( { 'address': address}, function(results, status) {
                  if (status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location,
                       
                    });
                    arrayCoordinates[arrayCoordinates.length] = results[0].geometry.location;
                    var infowindow = new google.maps.InfoWindow({
                    	
                        content: airport
                      });
                    google.maps.event.addListener(marker, 'click', function () {          
                        infowindow.open(map,marker);       
                    });
                    
                    
                	
                  } else {
                   // alert('Geocode was not successful for the following reason: ' + status);
                  }
                
                  if(arrayCoordinates.length >0){
                      var flightPath = new google.maps.Polyline({
                        path: arrayCoordinates,
                        strokeColor: "#FF0000",
                        strokeOpacity: 1.0,
                        strokeWeight: 2,
                        map : map
                      });
                  }
               	var polyOptions = {
               		    strokeColor: '#000000',
               		    strokeOpacity: 1.0,
               		    strokeWeight: 3
               		  }
                });
             
         	});
        }
    }


    // Listener encargado de validar el correo del cliente
    var validateUser = function(){
    
        var mail = $(this).val();
        var email= mail.replace(" ","");
        if(email == '') return;
        putMessage(this, '');
        if(email.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)){
            if(__EMAIL != email){
                __EMAIL = email;
                var request = $.ajax({
                    url:  'index.php?option=com_aaws&task=ajax.validateUser',
                    type: "POST",
                    data: {email: email},
                    dataType: "json"
                }), field = $(this);

                request.done(function(msg){ //MICOD: MODIFICADO...
                    if(msg.message != '' && msg.type != 'error'){
                      $('#passenger_main .pax-info select, #passenger_main .pax-info input, #paymentbutton').filter(':disabled').prop('disabled', null);
                        $( "#my-username" ).remove();
                        abandonLogin();
                        $('#information .fields').find('.password, .identify, .continue').hide(500);
                        $('#information .fields').find('.name').show(500);
                        
                    }else{
                      $('#passenger_main .pax-info select, #passenger_main .pax-info input, #paymentbutton').filter(':disabled').prop('disabled', null);
                        $( "#my-username" ).remove();
                        abandonLogin();
                        $('#information .fields').find('.password, .identify, .continue').hide(500);
                        $('#information .fields').find('.name').show(500);
                    }

                    /*-------------------------------------------------------------------------------------------------------------------------------------*/
                    /*MICOD
                    MUY IMPORTANE: La función "request.done(function(msg){});" es la responsable de verificar la existencia de un usuario en la base de datos
                    mediante el correo electrónico cuando se reserva un vuelo, si el usuario existe entonces da la posibiidad de iniciar sesión o mantenerce
                    en modo incógnito, ademas de crear un usuario si éste no existe utilizando el mismo correo electrónico. Lo que se busca es que no importa
                    si el usuario exista o no, la sesión debe quedar incógnita siempre.

                    Función "request.done(function(msg){});" Original:*/

                    /*request.done(function(msg){
                    if(msg.message != '' && msg.type != 'error'){
                        putMessage(field, msg.message, msg.type);
                        $('#information .fields').find('.name, .phone').hide(500);
                        $('#information .fields').find('.password, .identify, .continue').show(500);

                        // Selecciona la url a la cual debe hacer el llamado para obtener el html
                        if(typeof _data.url != 'undefined')
                            var url = _data.url;
                        else
                            var url = location.href;

                        var form = $('#passenger-form');
                        form.attr('action', url);

                        // Se agrega el username a un campo oculto para poder hacer el login
                        form.append(jQuery('<input type="hidden" id="my-username" value="' + msg.username + '" />'));

                        jQuery.each(_data.wsform, function(name, value){
                            if(name=='ajax') value = 0;
                            var element = null;
                            if((element = $('input[name="wsform[' + name +']"]')).length)
                                element.data('default', element.val()).val(value);
                            else{
                                var element = jQuery('<input type="hidden" name="wsform[' + name + ']" value="' + value + '" />');
                                form.append(element);
                            }
                        });
                        $('#passenger_main .pax-info select, #passenger_main .pax-info input, #paymentbutton').filter(':enabled').prop('disabled', 'disabled');
                        window.setTimeout(function(){
                            $('#contactpassword').focus();
                        }, 510);
                    }else{
                        $('#passenger_main .pax-info select, #passenger_main .pax-info input, #paymentbutton').filter(':disabled').prop('disabled', null);
            $( "#my-username" ).remove();
            abandonLogin();
                        $('#information .fields').find('.password, .identify, .continue').hide(500);
                        $('#information .fields').find('.name').show(500);
                    }
                });*/
                    /*-------------------------------------------------------------------------------------------------------------------------------------*/
                });
            }
        }else{
            putMessage(this, 'El correo no es válido', 'error');
        }
    }

    // Logue al usuario en el sistema
    var loginUser = function(){
        var username = $('#my-username').val(), password = $('#contactpassword').val();
        putMessage('#contactemail', '');
        putMessage('#contactpassword', '');
        if(username != '' && password != ''){
            var request = $.ajax({
                url:  'index.php?option=com_aaws&task=ajax.loginUser',
                type: "POST",
                data: {username: username, password: password},
                dataType: "json"
            });

            request.done(function(msg){
                if(msg.type=='success'){
                    $('#passenger-form').submit();
                }else{
                    putMessage('#contactpassword', msg.message, msg.type);
                }
            });
        }else{
            if(username == '')
                putMessage('#contactemail', 'El correo es obligatorio', 'error');
            if(password == '')
                putMessage('#contactpassword', 'La contraseña es obligatoria', 'error');
        }

    }

    // Listeners para el checkbox que abandona el login (Captura de datos para identificarse en el sitio)
    var abandonLogin = function(){
        $('#information .fields').find('.name, .phone').show(500);
        $('#information .fields').find('.password, .identify, .continue').hide(500);
        putMessage('#contactemail', '');
        window.setTimeout(function(){
            $('#contactphonecountry').focus();
        }, 510);
        $('#passenger-form').attr('action', __URL);
        jQuery.each(_data.wsform, function(name, value){
            var element = $('input[name="wsform[' + name +']"]');
            if(element.data('default') != undefined)
                element.val(element.data('default')).removeData('default');
            else
                element.remove();
        });
        $('#passenger_main .pax-info select, #passenger_main .pax-info input, #paymentbutton').filter(':disabled').prop('disabled', null);
        $('.email.confirm').show();
    }

    // Valida los datos ingresados en el formulario, para enviar la peticion
    var validateData = function(){
    	if($('input[name="wsform[paymentmethod]"]:checked').val() == undefined){
    		document.getElementById("placetopay").innerHTML='<span class="msg-validation error" style="display: block; left:0;">Por favor seleccione el tipo de solicitud.</span>';
          
            return false;
        }
    	
    	if($('#contactemail').val() != $('#contactemailconfirm').val()){
    		document.getElementById("placetopay").innerHTML='<span class="msg-validation error" style="display: block; left:0;">Revise la confirmación del correo de Contacto.</span>';
           
            return false;
        } 
    	
    	        // Significa que ya se envio una vez el formulario y no se debe enviar mas
        if($(this).data('sendClick')) return false;

        var _pass = true; 
        $('.required').each(function(){
            if($(this).val() == ''){
                _pass = false;
                $(this).focus().effect('highlight', {color: "#f9cacf"}, 400).effect('highlight', {color: "#f9cacf"}, 400);
                putMessage('#' + this.id, 'Por favor completa la información requerida.', 'error');
                return false;
            }
        });
      
        if(_pass) {
            if($('input[name="wsform[paymentmethod]"]:checked').val() == undefined){
                putMessage('#paymentmethodP', 'Por favor seleccione el tipo de solicitud.', 'error');
                _pass = false;
            }else if($('input[name="wsform[paymentmethod]"]:checked').val() == 0){
            	if($('input[name="wsform[paymentmet]"]:checked').val() == undefined){
            		putMessage('#payMetCard', 'Por favor seleccione la forma de pago.', 'error');
      			  _pass = false; 
                    
                }else{
                	if($('input[name="wsform[paymentmet]"]:checked').val() == 1){
                
                	if($('#PaymentTrans').val() ==''){
          			  putMessage('#PaymentTrans', 'Por favor ingrese el Codigo de transferencia.', 'error');
          			  _pass = false;  
          			  
                	}
                }
                	
                
            	  if($('input[name="wsform[paymentmet]"]:checked').val() == 0){
            		  
            		  //Valida para pago con tarjeta
            		  if($('#payCard-Info1').val() ==''){
                  		putMessage('#payCard-Info1', 'Por favor completa la información requerida.', 'error');
                  		 _pass = false; 
                  		}  
            		  if($('#PaymentCardNumber').val() ==''){
                    		putMessage('#PaymentCardNumber', 'Por favor completa la información requerida.', 'error');
                    		 _pass = false; 
                    		}
            		 
            		  if($('#carddate-month').val() ==''){
                    		putMessage('#carddate-month', 'Por favor completa la información requerida.', 'error');
                    		 _pass = false; 
            		  }
            
            		  if($('#carddate-year').val() ==''){
                    		putMessage('#carddate-year', 'Por favor completa la información requerida.', 'error');
                    		 _pass = false; 
                    		}
            		 
            		  if($('#PaymentCardName').val() ==''){
                    		putMessage('#PaymentCardName', 'Por favor completa la información requerida.', 'error');
                    		 _pass = false; 
                    		}
            		  if($('#PaymentCardIdent').val() ==''){
                    		putMessage('#PaymentCardIdent', 'Por favor completa la información requerida.', 'error');
                    		 _pass = false; 
                    		}
                  } 	
                	
                	//Se valida datos de facturacion	
                /*if($('#invoice-Info1 option:selected').val() ==1){
                		if($('#InvoiceRif').val() ==''){
                		putMessage('#InvoiceRif', 'Por favor completa la información requerida.', 'error');
                		 _pass = false; 
                		}
                		if($('#InvoiceName').val() ==''){
                    		putMessage('#InvoiceName', 'Por favor completa la información requerida.', 'error');
                    		 _pass = false; 
                    	}
                		if($('#InvoicePhone').val() ==''){
                    		putMessage('#InvoicePhone', 'Por favor completa la información requerida.', 'error');
                    		 _pass = false; 
                    	}
                		if($('#InvoiceAddress').val() ==''){
                    		putMessage('#InvoiceAddress', 'Por favor completa la información requerida.', 'error');
                    		 _pass = false; 
                    	}
                		
                	} */
                
                //Se valida datos de envio
                /*if($('#InvoiceCity').val() ==''){
                	putMessage('#InvoiceCity', 'Por favor completa la información requerida.', 'error');
           		 _pass = false; 
                }
				if($('#InvoiceUrban').val() ==''){
					putMessage('#InvoiceUrban', 'Por favor completa la información requerida.', 'error');
           		 _pass = false;             	
				                }
				if($('#InvoiceStreet').val() ==''){
					putMessage('#InvoiceStreet', 'Por favor completa la información requerida.', 'error');
           		 _pass = false; 
				}
				if($('#invoice-home').val() ==''){
					putMessage('#invoice-home', 'Por favor completa la información requerida.', 'error');
           		 _pass = false; 
				}
				if($('#InvoiceCode').val() ==''){
					putMessage('#InvoiceCode', 'Por favor completa la información requerida.', 'error');
           		 _pass = false; 
				}*/
               
                }
            }
           }
       


       


        if(_pass) {
            if($('#conditions:checked').val() == undefined){
                putMessage('#conditions', 'Por favor lea y acepte los terminos y condiciones del servicio.', 'error');
                _pass = false;
            }
        }

        if(_pass) {
            $(this).data('sendClick', true);
            $('#passenger_main').fadeOut({
                duration: 'slow',
                complete: function(){
                    var wait = '<div class="waiting">';
                    wait += '<div class="loading"></div>';
                    wait += '<div class="title-1">Por favor espere</div>';
                    wait += '<div class="title-2">Estamos procesando su solicitud.</div>';
                    wait += '</div>';

                    wait = $(wait);
                    $(this).html(wait).fadeIn();
                }
            });
        }

        return _pass;
    }

    // Valida el campo despues de ser digitado, para remover la indicacion de error
    var validateField = function(){
        if($(this).val() != '')
            putMessage('#' + this.id, '', 'error');
    }

    // Funcion para validar que el campo solo contenga numeros
    var forceNumeric = function(e){
        var key, keychar;

        if(window.event)
            key = window.event.keyCode;
        else if (e)
            key = e.which;
        else
            return true;

        keychar = String.fromCharCode(key);

        // control keys
        if((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
            return true;

        // numbers
        else if ((("0123456789").indexOf(keychar) > -1))
            return true;

        else
            return false;
    }

    // Funcion para validar que el campo solo contenga numeros y letras
    var forceAlNumeric = function(e){
        var key, keychar;

        if(window.event)
            key = window.event.keyCode;
        else if (e)
            key = e.which;
        else
            return true;

        keychar = String.fromCharCode(key);

        // control keys
        if((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
            return true;

        // numbers
        else if ((("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ").indexOf(keychar.toUpperCase()) > -1))
            return true;

        else
            return false;
    }
    
    // Funcion para validar que el campo sea email
    var forceMail = function(e){
        var key, keychar;

        if(window.event)
            key = window.event.keyCode;
        else if (e)
            key = e.which;
        else
            return true;

        keychar = String.fromCharCode(key);

        // control keys
        if((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
            return true;

        // numbers
        else if ((("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ@.-_").indexOf(keychar.toUpperCase()) > -1))
            return true;

        else
            return false;
    }
    // Funcion para validar que el campo sea email
    var forceSpace = function(e){
        var key, keychar;

        if(window.event)
            key = window.event.keyCode;
        else if (e)
            key = e.which;
        else
            return true;

        keychar = String.fromCharCode(key);

        // control keys
        if((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
            return true;

        // numbers
        else if ((("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ@.-_ ").indexOf(keychar.toUpperCase()) > -1))
            return true;

        else
            return false;
    }
    // Funcion que se encarga de cargar los listeners de los elementos
    var load = function(){
        initializeDetailsListeners();
        window.setTimeout(function(){
            $('#contactphonecountry').focus();
            $('#contactemail').focus();
        }, 700);

        __URL = $('#passenger-form').attr('action');

        if($('#contactemail').length){
            $('#passenger_main .pax-info select, #passenger_main .pax-info input').filter(':enabled').prop('disabled', 'disabled');
        }

        $('#contactemail').on('blur', validateUser);
        $('#identify').on('click', loginUser);
        $('#continue-button').on('change', abandonLogin);
        $('select[id^=paxborndate-month]').on('blur', function(){
            if($(this).val() == '02' && $(this).prev('select').val() > 29)
                $(this).prev('select').val(29);
            else if(['04', '06', '09', '11'].indexOf($(this).val()) > -1 && $(this).prev('select').val() > 30)
                $(this).prev('select').val(30);
        });
        $('.numeric').on('keypress', forceNumeric);
        $('#contactdocumentid').on('keypress.valpayer', forceNumeric);

        // Si es cedula, permite numeros, en caso contrario tambien permite letras
        $('#contactdocumenttype').change(function(){
            if($(this).val() == 'CC') {
                $('#contactdocumentid').off('keypress.valpayer');
                $('#contactdocumentid').on('keypress.valpayer', forceNumeric);
                if($('#contactdocumentid').val().match(/[A-Za-z]/)) {
                    $('#contactdocumentid').val('');
                }
            } else {
                $('#contactdocumentid').off('keypress.valpayer');
                $('#contactdocumentid').on('keypress.valpayer', forceAlNumeric);
            }
        });

        // Valida si el documento es colombiano, que la nacionalidad sea colombiana
        // validacion de numeros y letras para los documentos de los pasajeros
        $('select[id^=paxdocumenttype]').change(function(){
            var index = $(this).attr('id').split('_')[1];
            $('#frecuentpassenger_' + index).on('keypress.valpax', forceAlNumeric);
            $('#paxemail_' + index).on('keypress.valpax', forceMail);
            $('#paxemailconf_' + index).on('keypress.valpax', forceMail);
            $('#note1').on('keypress.valpax', forceSpace);
            $('#note2').on('keypress.valpax', forceSpace);
            $('#note3').on('keypress.valpax', forceSpace);
            if($(this).val() == '5') {
               // $('#paxdocumentnumber_' + index).off('keypress.valpax');
                $('#paxdocumentnumber_' + index).on('keypress.valpax', forceAlNumeric);
                if($('#paxdocumentnumber_' + index).val().match(/[A-Za-z]/)) {
                    $('#paxdocumentnumber_' + index).val('');
                }
                $('#paxnationality_' + index).val('VE');
            } else {
                $('#paxdocumentnumber_' + index).off('keypress.valpax');
                $('#paxdocumentnumber_' + index).on('keypress.valpax', forceAlNumeric);
                $('#paxnationality_' + index).val('');
            }
        });

        // Valida el tratamiento para seleccionar el genero del pasajero
        $('select[id^=paxtreatment]').change(function(){
            var index = $(this).attr('id').split('_')[1];
            if(['MS', 'MRS'].indexOf($(this).val()) > -1) {
                $('#paxgender_' + index).val('Female');
            } else {
                $('#paxgender_' + index).val('Male');
            }
        });

        $('#paymentbutton').on('click', validateData);
        $('.required').on('blur', validateField);

        // Se colocan la informacion de la recomendacion en un segmento aparte,
        // por si se tiene que recargar de nuevo la pagina mostrando errores
        var form = $('#passenger-form');
        jQuery.each(_data.wsform, function(name, value){
            if(name=='ajax') value = 0;
            var element = jQuery('<input type="hidden" name="wsform[detail][' + name + ']" value="' + value + '" />');
            form.append(element);
        });

        // Modal windows, para TOS y Restricciones
        $('.modalLink').modal({
            trigger: '.modalLinkAir',
            olay: 'div#overlay',
            animationEffect: 'fadeIn',
            animationSpeed: 400,
            moveModalSpeed: 500,
            background: '000',
            opacity: 0.4,
            close:'.closeBtn'
        });

        //Selecciona tipo de solicitud
        //Comprar
        $('#paymentmethodB').change(function(){
        	$( ".factura" ).show();
        	$( ".pagos" ).show();
		    $( ".reserva24" ).hide();
		    $( "#paymentbutton" ).val('Comprar');
        });
        //Reservar
        $('#paymentmethodP').change(function(){
        	$( ".factura" ).hide();
        	$( ".pagos" ).hide();
        	$( ".reserva24" ).show();
            $( "#paymentbutton" ).val('Reservar');
        });
        
        //pago con tarjeta
        $('#payMetCard').change(function(){
        	$( "#info-payTarjeta" ).show();
        	$( "#info-payTransfer" ).hide();
        });
        //pago con transferencia
        $('#paymentTransfer').change(function(){
        	$( "#info-payTransfer" ).show();
        	$( "#info-payTarjeta" ).hide();
        });
        // Se agrega la hoja de estilos a todos los iframes
        $('iframe')
            .contents()
            .find('head')
            .append($("<link/>", {rel: "stylesheet", href: "./media/amadeus/com_aaws/css/air/styles.css", type:"text/css" }));

        // Tabs Restricciones
        $('#restrictions .tabs').tabs({
            activate: function( event, ui ){
                var data, request, url;

                if($(ui.newPanel).find('.loading').length > 0 && !$(this).data('loading')) {
                    $(this).data('loading', true);

                    if(typeof _data.url != 'undefined'){
                        url = _data.url;
                    } else {
                        url = location.href;
                    }

                    data = _data;
                    data.wsform.code = $(ui.newPanel).attr('id').substring(5);

                    request = $.ajax({
                        url:  url,
                        type: "POST",
                        data: data,
                        dataType: "html"
                    });

                    var self = this;
                    request.done(function(msg){
                        $(ui.newPanel).html(msg);
                        $(self).data('loading', false);

                        if($($(self).find('.ui-tabs-active a').attr('href')).find('.loading').length > 0) {
                            var index = $(self).tabs('option', 'active');
                            $(self).tabs('option', 'active', 0);
                            $(self).tabs('option', 'active', index);
                        }
                    });
                }
            }
        }).addClass( "ui-tabs-vertical ui-helper-clearfix");
        $('#restrictions .tabs li').removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
    }

    // Funcion para cargar los datos que ya se digitaron, prar cuando se identifique datos en la respuesta
    var loadInfo = function(){
        // Se carga la informacion de los paises
        var request = $.ajax({
            url:  'index.php?option=com_aaws&task=ajax.getCountries',
            type: "GET",
            dataType: "json"
        });

     // Se carga la informacion de las aerolineas
        var requestAir = $.ajax({
            url:  'index.php?option=com_aaws&task=ajax.getAirlines',
            type: "GET",
            dataType: "json"
        });
        
        requestAir.done(function(msg) {
            var selects = $('select[id^=airline_]');
            for(var i = 0, j = msg.length; i < j; i++) {
                selects.append('<option value="' + msg[i].k + '">' + msg[i].n + '</option>');
            }

            selects.each(function(){
                $(this).val($(this).data('country'));
            });
        });
        
        //Se carga la info del combo de bancos
        var requestBanc = $.ajax({
            url:  'index.php?option=com_aaws&task=ajax.getBancos',
            type: "GET",
            dataType: "json"
        });
        requestBanc.done(function(msg) {
            var selects = $('select[id^=Bancos]');
         
            for(var i = 0, j = msg.length; i < j; i++) {
                selects.append('<option value="' + msg[i].k + '">' + msg[i].k + '</option>');
            }

            selects.each(function(){
                $(this).val($(this).data('bancos'));
            });
        });
        
        //Traer el texto de los bancos
      
        $('#Bancos').change(function(){
            var banco =  $('#Bancos').val();
            var requestDataB = $.ajax({
                url:  'index.php?option=com_aaws&task=ajax.getBancosInfo&banco='+banco,
                type: "GET",
                success:  function (response) {
                                   $("#valbanco").html(response);
                                }
            }); 
         
            
         });
        
        request.done(function(msg) {
            var selects = $('select[id^=paxnationality_]');
            for(var i = 0, j = msg.length; i < j; i++) {
                selects.append('<option value="' + msg[i].k + '">' + msg[i].n + '</option>');
            }

            selects.each(function(){
                $(this).val($(this).data('country'));
            });
        });

        if(typeof _data.info == 'undefined' || _data.info.length == 0) return;

        if(typeof _data.info['contactemail'] != 'undefined'){
            $('#contactdocumenttype').val(_data.info['contactdocumenttype']);
            $('#contactdocumentid').val(_data.info['contactdocumentid']);
            $('#contactname').val(_data.info['contactname']);
            $('#contactemail').val(_data.info['contactemail']);
        }

        $('#contactphonecountry').val(_data.info['contactphonecountry']);
        $('#contactphonecode').val(_data.info['contactphonecode']);
        $('#contactphonenumber').val(_data.info['contactphonenumber']);

        $('.pax-info select, .pax-info input').each(function(index, element){
            var name = element.id.split('_'), pos = ~~name[1] - 1, name = name[0];

            if(typeof _data.info[name] != 'undefined') {
                if(name == 'paxnationality') {
                    $(element).data('country', _data.info[name][pos]);
                }
                $(element).prop('disabled', null).val(_data.info[name][pos]);
            }
        });

        if(typeof _data.info['error'] != undefined && _data.info.error.message != null){
            if(_data.info.error.field != null){
                window.setTimeout(function(){
                    $('#' + _data.info.error.field).focus().effect('highlight', {color: "#f9cacf"}, 400).effect('highlight', {color: "#f9cacf"}, 400);
                    putMessage('#' + _data.info.error.field, _data.info.error.message, 'error');
                }, 800);
            } else {
                window.setTimeout(function(){
                    alert(_data.info.error.message);
                }, 800);
            }
        }
    }

    $.extend(container, {
        load:     load,
        loadInfo: loadInfo
    });
})(jQuery, AirDetail);



// Se inicializan todas las interacciones de vuelos
jQuery(document).ready(function($){

    // Selecciona la url a la cual debe hacer el llamado para obtener el html
    if(typeof _data.url != 'undefined')
        var url = _data.url;
    else
        var url = location.href;

    var request = $.ajax({
            url:  url,
            type: "POST",
            data: _data,
            dataType: "html"
        });

    function gup( name ){
    	var regexS = "[\\?&]"+name+"=([^&#]*)";
    	var regex = new RegExp ( regexS );
    	var tmpURL = url;
    	var results = regex.exec( tmpURL );
    	if( results == null )
    		return"";
    	else
    		return results[1];
    }

    ////////////////////////////////////////////////////////////////////////
    $('#labelForAnyPassager').click(function(){
      alert('ok');
    });
    ////////////////////////////////////////////////////////////////////////
    
     var showError = function(message,ban){
    	  // Selecciona la url a la cual debe hacer el llamado para obtener el html
    if(typeof _data.url != 'undefined')
        var url = _data.url;
    else
        var url = location.href;


var texto = 'disponibilidad';
var cadena= url;
if (cadena.indexOf(texto) != -1) {
  window.location.href ="index.php?e";
} 

    	 
         var container = $('.title-1').parent();
         container.addClass('error');
         container.find('.title-1').html($('.error-label').html());
         container.find('.title-2').html(message);
     }
             
    request.done(function(msg) {
        var response = $(msg);
    
        /**
         * Si el paso es disponiblidad, se realizan estas acciones
         */
        if(response.is('#availability_main'))
        {  
            var qsDiv = response.find('#aaws-qs-top').hide();
			var loadDiv = response.find('#load').hide();
			var rightDiv = response.find('#right-container').hide();
            var filtersDiv = response.find('#filters').hide();
			var	matrizSup = response.find('#availability_header').hide();

            // Se oculta el div de esperando, para luego hacer aparecer la diponibilidad
            $('#availability_main #load').fadeOut({
                duration: 'slow',
                complete: function(){
                    //Se agregan los nuevos divs al div principal
                    $('#availability_main #left-container').append(filtersDiv);
                    $('#availability_main #right-container').html(rightDiv.html());
                    $('#availability_main #availability_header').html(matrizSup.html());
					$('#availability_main #aaws-qs-top').html(qsDiv.html());
                    $('#availability_main #filters').fadeIn(1000);

                    // Se dispara el evento, despues que se hallan aplicado los estilos al contenido,
                    // para calcular las dimensiones reales
                    window.setTimeout(AirAvailability.initialHeight, 100);

                    AirAvailability.load();

                    // Se hace aparecer la respuesta, ya sea error o disponibilidad
                    $('#availability_main #right-container').fadeIn(1000);
					$('#availability_main #availability_header').fadeIn(1000);
					$('#availability_main #aaws-qs-top').fadeIn(1000)

                }
            });

            return;
        } 

        /**
         * Si el paso es detalle, se realizan estas acciones
         */
        if(response.is('#passenger_main'))
        { 
            // Se oculta el div de esperando, para luego hacer aparecer la diponibilidad
            $('#passenger_main').fadeOut({
                duration: 'slow',
                complete: function(){
                    //Se agregan los unevos divs al div principal
                    $('#passenger_main').html(response.html());

                    AirDetail.load();
                    AirDetail.loadInfo();

                    // Se hace aparecer la respuesta, ya sea error o disponibilidad
                    $('#passenger_main').fadeIn(1000)
                }
            });

            return;
        }

        // Si no se salio en los anteriores condicionales, es por que es un error y debe
        // mostrarse
        showError(response)
    });
                
    request.fail(function(jqXHR, textStatus) {
        showError(textStatus)
    });
});

 
jQuery(window).bind("load", function() {
    // Este es un truco, para que el javascript se ejecute despues que toda la pagina se halla
    // cargado, para que asi el tamaño calculado coincida con el tamaño final generado despues de
    // aplicar las hojas de estilos, fuentes y demas.
    //
    // Despues de realizar todos los cambios necesario, se ajusta el tamaño de ambos contenedores,
    // para que se realice el efecto de movimiento de los filtros
    $('*').bind("cut copy paste",function(e) {
        e.preventDefault();
      });
    AirAvailability.initialHeight();
});
