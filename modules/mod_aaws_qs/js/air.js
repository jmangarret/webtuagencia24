/**
 * Elemento para almacenar el comportamiento del formulario de Aereo
 *
 * El objeto solo contiene los metodos que se deben exponer para
 * el correcto funcionamiento del formulario
 */
var AirAawsQS = { };

(function($, module){
    /**
     * Alamacena los parametros de configuracion del modulo, los
     * cuales se adminsitran por el Joomla.
     */
    var _CONFIG_DATA = { };

    /**
     * Cache de los strings que contienen la información de las ciudades.
     * El formato del string es igual al encontrado en el sitio oficial de
     * amadeus (amadeus.net)
     */
    var _CACHE = { };

    /**
     * Cache de los strings que contienen la información de las aerolineas.
     * Alamacena una cadena de texto con todas las aerolineas
     */
    var _CACHE_AIRLINE = '';

    /**
     * Funcion comun para los autocomplete, para validar el TAB en caso que el menu
     * este visible
     */
    var ACKeyDownValidation = function(event) {
        if (event.keyCode === $.ui.keyCode.TAB && $(this).data( "ui-autocomplete" ).menu.element.is(':visible')) {
            if($(this).data("ui-autocomplete").menu.active) {
                $(this).data("ui-autocomplete").menu.blur();
            } else {
                $(this).data("ui-autocomplete").menu.next();
                event.preventDefault();
            }
        }
    }


    // Funcion que se encarga de colocar los listeners a las casillas de auto-comlpetado
    var _loadAutoCompleteListeners = function(matches){
        /**
         * Inicializa los auto-completados de los campos de clase aereo.
         */
        $(matches).bind('blur', function(){
            /**
             * Se indica si lo digitado tiene 3 letras, lo pase directamente a la información
             * que se va a enviar, ya que probablemente puede ser un codigo IATA
             */
            var iata = $(this).val(), id = this.id, name = id.split(/-/);

            if(iata.match(/^\w{3}$/))
                $('#h-' + id).val(iata.toUpperCase());

            // Se valida en multiples destinos la ciudad de llegada, para que en el siguiente
            // trayecto sea la cidad de salida
            if($('#tt-multiple').is(':checked') && name[0] == 'arrival' && $('#h-departure-' + (~~name[1] + 1)).val() == '') {
                $('#departure-' + (~~name[1] + 1)).val($(this).val());
                $('#h-departure-' + (~~name[1] + 1)).val($('#h-' + id).val());               
            }

            // En caso que hal la generado un error antes, y ya se halla
            // completado, se quita la clase del error
            if($(this).val() != '') {
                $(this).removeClass('error');
            } else if($(this).val() == '') {
                $('#h-' + id).val('');
            }
        })
        .bind("keydown", ACKeyDownValidation)
        .autocomplete({
            delay: 100,

            position: (function(){
                switch(_CONFIG_DATA.ac_position){
                case '1':
                    return {collision: 'flip'};
                case '2':
                    return {my: 'left bottom', at: 'left top'};
                case '3':
                    return {my: 'left top', ar: 'left bottom'};
                case '4':
                    return {my: 'right top', at: 'left top'};
                case '5':
                    return {my: 'left top', at: 'right top'};
                }
            })(),

            minLength: 0,

            source: function(request, response){
                /**
                 * Obtiene los datos para usar en el menu que despliega el autocompletar
                 */

                // Se valida que se halla digitado algo en el campo para poder completar.
                if(!request.term.match(/[A-Za-z]/)) return;

                // Se obtiene la primera letra para traer el archivo de ciudades del servidor
                var term = request.term[0].toUpperCase();

                // Si el string de ciudades no se ha guardado en cache, se solicita mediante ajax
                if(!_CACHE[term]){
                    // Para no realizar multiples peticions, marcamos que la letra ya existe en cache, pero vacia
                    _CACHE[term] = '';

                    // Se obtienen las ciudades dependiendo del idioma y se guardan en la chache
                    $.ajax({
                        type : 'GET',
                        url  : 'modules/mod_aaws_qs/cities/ES/' + term + '.txt',
                        success: function(data){
                            _CACHE[term] = data;
                        }
                    });
                }else{
                    // Si continuo digitando en el campo, pero aun no tengo la información de las ciudades, no hago nada
                    if(_CACHE[term] != '')
                        response(uiqs.getSuggestions(request.term, _CACHE[term]));

                    return;
                }
            },

            search: function() {
                /**
                 * Valida si se debe realizar el autocompletado o no.
                 */

                // Se valida que se halla digitado algo en el campo para poder completar.
                if(this.value == '') return;

                // Se obtiene la primera letra para conocer si ya esta en la cache
                var term = this.value[0].toUpperCase();

                // En caso que el termino ya este en la chache, se valida que se hallan digitado
                // al menos 3 letras, para poder mostrar el menu (y asi limitar las opciones visibles)
                if(_CACHE[term]){
                    if (this.value.length < 3) {
                        $(this).autocomplete('close');
                        return false;
                    }
                }
            },

            focus: function(event, ui){
                /**
                 * Se ejecuta al seleccioanr alguna ciudad
                 */
                var id = this.id, name = id.split(/-/);

                // Se coloca la ciudad en el campo visible y el IATA en el campo que se va a enviar.
                $('#' + id).val(ui.item['label']);
                $('#h-' + id).val(ui.item['builder']._iata);

                // Se valida en multiples destinos la ciudad de llegada, para que en el siguiente
                // trayecto sea la cidad de salida
                if($('#tt-multiple').is(':checked') && name[0] == 'arrival' && $('#h-departure-' + (~~name[1] + 1)).val() == '') {
                    $('#departure-' + (~~name[1] + 1)).val($(this).val());
                    $('#h-departure-' + (~~name[1] + 1)).val($('#h-' + id).val());
                }
                return false;
            },

            select: function(event, ui){
                /**
                 * Se ejecuta al seleccionar una opcion
                 */
                var id = $(this).attr('id').split('-');

                switch(id[0])
                {
                case 'departure':
                    $('#arrival-' + id[1]).focus();
                    break;
                case 'arrival':
                    $('#departure_date-' + id[1]).focus();
                    break;
                }
            }
        }).each(function(index, element){
            $(element).data('ui-autocomplete')._renderItem = function( ul, item ) {
                /**
                 * Se personaliza la forma de presentar la información del autocompletado
                 */
                return $('<li>')
                    .append($('<a>').append($(item['builder'].toHTML($(this.element).val()))))
                    .appendTo( ul );
            };
        });
    }


    // Funcion que se encarga de colocar los listeners a las casillas de calendario
    var _loadCalendarListeners = function(matches){
        //Se busca cual es la fecha mayor, para dejarla como minima fecha del elemento nuevo
        var minDate = _CONFIG_DATA.offset_days;
        $('.datepicker-air').each(function(index, element){
            if($(element).hasClass('hasDatepicker') && $(element).datepicker('getDate') != null)
                minDate = $(element).datepicker('getDate')
        });

        /**
         * Inicializa el datepicker de los campos de clase aereo
         */
        $(matches)
        .bind('blur', function(){
            // En caso que halla generado un error antes, y ya se halla
            // completado, se quita la clase del error
            if($(this).val() != '') {
                $(this).removeClass('error');
            }
        })
        .bind("keydown", function(event) {
            if (event.keyCode === $.ui.keyCode.TAB && $(this).val() != '') {
                event.preventDefault();
            }
        })
        .datepicker({

            dateFormat: _CONFIG_DATA.format_date,

            minDate: minDate,

            numberOfMonths: _CONFIG_DATA.months,

            showOn: "both",

            buttonImage: "images/calendar.png",

            buttonImageOnly: true,

            onClose: function(selectedDate){
                // Si existe alguna fecha seleccionada se continua con el proceso
                if(selectedDate != '') {
                    // En caso que halla generado un error antes, y ya se halla
                    // completado, se quita la clase del error
                    $(this).removeClass('error');

                    // Se obtiene la clase que va a servir como relacion de los campos los cuales dependen de este.
                    var classParts = $(this).attr('class').match(/(\w{3}-\w{3}-\d+)/), id = $(this).attr('id'), that = $(this);

                    classParts = classParts[0].split('-');

                    // Se obtiene todos los campos que comparten la misma clase
                    $('.datepicker-air[class*=' + classParts[0] + '-' + classParts[1] + ']').each(function(index, element){
                        // Se valida que el campo actual sea mayor que al que se le asigno la fecha,
                        // en caso que sea mayor, se coloca como fecha minima la que se selecciono
                        if(index + 1 > classParts[2] && $(element).hasClass(classParts[0] + '-' + classParts[1] + '-' + (index + 1)))
                            $(element).datepicker('option', 'minDate', that.datepicker('getDate'))
                    });

                    // Se convierte el formato de la fecha para guardarla en el campo que se va a enviar
                    $('#h-' + id).val($.datepicker.formatDate('yy-mm-dd', that.datepicker('getDate')))

                    var id = $(this).attr('id').split('-'), next = ~~id[1] + 1;

                    if($('#departure-' + next + ':visible').length)
                        $('#departure-' + next).focus();
                    else if($('#departure_date-' + next + ':visible').length)
                        window.setTimeout(function(){
                            $('#departure_date-' + next).focus();
                        }, 50);
                    else
                        $('#adults').focus();
                }
            }
        });
    }



    // Funcion que se encarga de colocar los listeners a las casillas de auto-completado para las aerolineas
    var _loadAutoCompleteAirLineListeners = function(matches){
        /**
         * Inicializa los auto-completados de los campos de clase aerolinea.
         */
        $(matches).bind('blur', function(){
            /**
             * Se indica si lo digitado tiene 2 letras, lo pase directamente a la información
             * que se va a enviar, ya que probablemente puede ser un codigo IATA de aerolinea
             */
            var iata = $(this).val(), id = this.id, name = id.split(/-/);

            if(iata.match(/^\w{2}$/))
                $('#h-' + id).val(iata.toUpperCase());

            if($(this).val() == '') {
                $('#h-' + id).val('');
            }
        })
        .bind("keydown", ACKeyDownValidation)
        .autocomplete({
            delay: 100,

            position: (function(){
                switch(_CONFIG_DATA.ac_position){
                case '1':
                    return {collision: 'flip'};
                case '2':
                    return {my: 'left bottom', at: 'left top'};
                case '3':
                    return {my: 'left top', ar: 'left bottom'};
                case '4':
                    return {my: 'right top', at: 'left top'};
                case '5':
                    return {my: 'left top', at: 'right top'};
                }
            })(),

            minLength: 0,

            source: function(request, response){
                /**
                 * Obtiene los datos para usar en el menu que despliega el autocompletar
                 */

                // Se valida que se halla digitado algo en el campo para poder completar.
                if(request.term == '') return;

                // Si el string de aerolineas no se ha guardado en cache, se solicita mediante ajax
                if(!_CACHE_AIRLINE.length){
                    // Para no realizar multiples peticions, marcamos que ya existe el string con las aerolineas
                    // con una X
                    _CACHE_AIRLINE = 'X';

                    // Se obtienen las aerolineas y se guardan en la chache
                    $.ajax({
                        type : 'GET',
                        url  : 'modules/mod_aaws_qs/airlines/airlines.txt',
                        success: function(data){
                            _CACHE_AIRLINE = data;
                        }
                    });
                }else{
                    // Si continuo digitando en el campo, pero aun no tengo la información de las aerolineas, no hago nada
                    if(_CACHE_AIRLINE != 'X') {
                        response(uiqs.getSuggestionsAirLine(request.term, _CACHE_AIRLINE));
                    }

                    return;
                }
            },

            search: function() {
                /**
                 * Valida si se debe realizar el autocompletado o no.
                 */

                // Se valida que se halla digitado algo en el campo para poder completar.
                if(this.value == '') return;

                // Si ya existe la cadena de aerolineas en el cache, se valida que se hallan digitado
                // al menos 2 letras, para poder mostrar el menu (y asi limitar las opciones visibles)
                if(_CACHE_AIRLINE.length && _CACHE_AIRLINE != 'X'){
                    if (this.value.length < 2) {
                        $(this).autocomplete('close');
                        return false;
                    }
                }
            },

            focus: function(event, ui){
                /**
                 * Se ejecuta al seleccionar alguna aerolinea
                 */
                var id = this.id, name = id.split(/-/);

                // Se coloca la aerolinea en el campo visible y el IATA en el campo que se va a enviar.
                $('#' + id).val(ui.item['label']);
                $('#h-' + id).val(ui.item['code']);

                return false;
            }
        })
        .each(function(index, element){
            $(element).data('ui-autocomplete')._renderItem = function( ul, item ) {
                /**
                 * Se personaliza la forma de presentar la información del autocompletado
                 */
                var result = '',
                    strInputValue = $(this.element).val(),
                    index  = item.label.toLowerCase().indexOf(strInputValue.toLowerCase());

                if (index != -1) {
                    result = item.label.substr(0,index)
                             + item.label.substr(index,strInputValue.length).bold()
                             + item.label.substr(index+strInputValue.length,item.label.length);
                } else {
                    result = item.label;
                }

                return $('<li>')
                    .append($('<a>').append($('<div class="option">' + result + '</div>')))
                    .appendTo( ul );
            };
        });
    }



    /**
     * Funcion que valida los destinos de los multiples destinos e indica 
     * el comportamiento para los destinos actuales
     */
    var _validateDestinies = function(){
        if($('#quick-search .itinerary div[class^=segment]').length <= 2) {
            $('.m-close').hide();
        } else {
            $('.m-close').show();
        }

        if($('#quick-search .itinerary div[class^=segment]').length < _CONFIG_DATA.multiple_max
            && $('#quick-search .itinerary div[class^=segment]').length > 1) {
            $('.add-destiny').show({effect: 'blind', duration: 'slow'});
        } else {
            $('.add-destiny').hide({effect: 'blind', duration: 'slow'});
        }
    }


    /**
     * Function que coloca los listeners a los elmentos de multiples destinos,
     * como los botones de cerrar y el boton de agregar
     */
    var _loadMultipleDestiniesListeners = function(context){
        /**
         * Se valida primero que elementos se deben agregar
         */
        _validateDestinies();

        /**
         * Listener para borrar destinos cuando se este en multiples destinos
         */
        $('.m-header .m-close', context).click(function(){
            var segment  = $(this).parents('div[class^=segment]'),
                index    = ~~segment.attr('class').replace(/^.*segment-(\d).*$/, '$1');

            // Se valida que hallan minimo dos destinos, en caso contrario, se inactivan los botones
            // de cerrar
            if($('#quick-search .itinerary div[class^=segment]').length <= 2) return;

            // Quita el calendario y el autocomplete de los campos del elemento .segment-#,
            // para poder reemplazar unicamente los valores necesarios y volver a colocar
            // los listners
            var _removeUI = function(segment) {
                // Por alguna razon, al convertir el bloque de HTML en string, los valores
                // seleccionados en el autocompletado no se mantiene, por esta razon se guardan
                // aparte y se vuelven a colocar
                //
                // Almacena temporalmente los valores de los campos, para luego volverlos a colocar
                var info = {};
                $('input', segment).each(function(){
                    info[$(this).attr('id').replace(/^(.*)-\d$/, '$1')] = $(this).val();
                });

                $('.complete-air', segment).autocomplete('destroy');
                $('.datepicker-air', segment).datepicker('destroy');

                return info;
            }

            // Quitamos el segmento que se selecciono para eliminar
            segment.hide({effect: 'blind', duration: 'slow', complete: function(){
                segment.remove();

                for(var i = index + 1; i <= _CONFIG_DATA.multiple_max; i++) {

                    if(!$('.segment-' + i).length) break;

                    var _data, segmentHTML, indexEnd = i - 1,
                        rule = new RegExp('([-_ ])' + i, 'g');

                    _data = _removeUI($('.segment-' + i));
                    segmentHTML = $('.segment-' + i).html().replace(rule, '$1' + indexEnd);

                    $('.segment-' + i).html(segmentHTML).removeClass('segment-' + i).addClass('segment-' + indexEnd);

                    // Colocando los valores de los campos, de acuerdo a lo que se guardo previamente
                    $.each(_data, function(key, value){
                        $('#' + key + '-' + indexEnd).val(value);
                    });

                    // Agregando los listeners del autocompletado y calendario
                    _loadAutoCompleteListeners('.segment-' + indexEnd + ' .complete-air');
                    _loadCalendarListeners('.segment-' + indexEnd + ' .datepicker-air');
                }

                // Se vuelven a colocar los eventos de los cerrar y agregar destinos,
                // ya que al modificar y colocar de nuevo el html, se pierden las referencias
                // a los eventos anteriores
                _loadMultipleDestiniesListeners();

            }});

        });

    }



    /**
     * Función que inicializa todos los listeners de los campos del QS.
     */
    var load = function(){

        /**
         * Se capturan y almacenan algunos elementos para el correcto funcionamiento del QS
         */
        var __storedElements = { };
        __storedElements['date']      = $('.itinerary .dates .field-2').clone().hide();
        __storedElements['itinerary'] = $('.itinerary').clone();
        __storedElements['itinerary'].find('.field-2').remove();
        __storedElements['itinerary'].find('.segment-1').hide();
        __storedElements['itinerary'].find('.m-header').show();
        __storedElements['itinerary'] = __storedElements['itinerary'].html().replace(/([-_ ])1/g, '$1#');


        //Coloca los listener del autocompletado y el calendario
        _loadAutoCompleteListeners('.complete-air');
        _loadCalendarListeners('.datepicker-air');
        _loadAutoCompleteAirLineListeners('.complete-airline');


        // Funciones de uso general para los tipos de viaje
        var _removeAll = function(){
            var itinerary = $('.itinerary');

            $('.segment-1 .cities > div, .segment-1 .dates > div', itinerary)
                .filter(':not(.field-1)')
                .hide({effect: 'fade', duration: 'fast', complete: function(){
                    $(this).remove();
                }
            });

            $('div[class^=segment]', itinerary)
                .filter(':not(.segment-1)')
                .hide({effect: 'blind', duration: 'slow', complete: function(){
                    $(this).remove();
                }
            });

            $('.itinerary .segment-1 .m-header').hide({effect: 'blind', duration: 'slow', complete: function(){
                $('.m-close').show();
            }});
            $('.itinerary').removeClass('multiple');
            $('.add-destiny').hide({effect: 'blind', duration: 'slow'});

            $('#departure-1').focus();
        }


        /**
         * Se programa el comportamiento del radio de vuelos de solo ida
         */
        $('#tt-one-way').change(function(){
            if($(this).attr('checked') == 'checked'){
                _removeAll();
            }
        });


        /**
         * Se programa el comportamiento del radio de vuelos de ida y vuelta
         */
        $('#tt-round-trip').change(function(){
            if($(this).attr('checked') == 'checked'){
                _removeAll();
                $('.itinerary .dates .field-1').after(__storedElements['date'].clone(true).show({effect: 'fade', duration: 'fast'}));
                _loadCalendarListeners('.field-2 .datepicker-air');
            }
        });


        /**
         * Se programa el comportamiento del radio de vuelos de multiples destinos
         */
        $('#tt-multiple').change(function(){
            if($(this).attr('checked') == 'checked'){
                _removeAll();

                for(var i = 1; i < 3; i++)
                  $('.itinerary .segment-' + i).after(__storedElements['itinerary'].replace(/#/g, i + 1));

                $('.itinerary .m-header, .itinerary div[class^=segment]').show({effect: 'blind', duration: 'slow'});
                $('.itinerary').addClass('multiple');

                for(var i = 2; i <= 3; i++){
                    _loadAutoCompleteListeners('.segment-' + i + ' .complete-air');
                    _loadCalendarListeners('.segment-' + i + ' .datepicker-air');
                }

                _loadMultipleDestiniesListeners();
            }
        });

        /**
         * Cada vez que se cambia la cantidad de adultos, se generan tantos infantes como adultos
         * Esto con el fin de evitar que viajen mas adultos que infantes.
         *
         * Tambien se validan los niños, para eitar que viajen mas de 9 personas entre niños y adultos
         */
        $('#adults').change(function(){
            var value = $(this).val(), chd = $('#children').val(), inf = $('#babies').val(), options = '';
            
            for(var i = 0; i <= value; i++){
                options += '<option value="' + i + '">' + i + '</option>';
            }
            $('#babies').html(options).val(Math.min(inf, value));

            options = '';
            for(var i = 0; i <= 9 - value; i++){
                options += '<option value="' + i + '">' + i + '</option>';
            }
            $('#children').html(options).val(Math.min(chd, 9 - value));
        });


        /**
         * Se programa el comoprtamiento de agregar destino
         */
        $('.add-destiny input').click(function(){
            var i = $('#quick-search .itinerary div[class^=segment]').length,
                j = i + 1;

            if($('#quick-search .itinerary div[class^=segment]').length >= _CONFIG_DATA.multiple_max){
                return;
            }

            $('.itinerary .segment-' + i).after(__storedElements['itinerary'].replace(/#/g, j));
            $('.itinerary .m-header, .itinerary div.segment-' + j).show({effect: 'blind', duration: 'slow'});

            _loadAutoCompleteListeners('.segment-' + j + ' .complete-air');
            _loadCalendarListeners('.segment-' + j + ' .datepicker-air');
            _loadMultipleDestiniesListeners('.itinerary .segment-' + j);
        });


        /**
         * Se programa el comoprtamiento de agregar destino
         */
        $('#quick-search .button input').click(function(){
            var _error = false;

            $('#quick-search .required').each(function(index, element){
                var field = element.id.replace(/^h-/, '');
                if($(element).val() == '') {
                    $('#' + field).addClass('error').effect('highlight', {color: "#f9cacf"}, 400).effect('highlight', {color: "#f9cacf"}, 400);
                    _error = true;
                } else {
                    $('#' + field).removeClass('error');
                }
            });

            if(_error) {
                $('#quick-search .error').first().focus();
                return false;
            }

            return true;
        });


        /**
         * Listener de las opciones avanzadas
         */
        $('#adv-title').click(function(){
            $('.advanced .content').toggle({effect: 'blind', duration: 'slow'});
        });


        /**
         * Se inicializan las tabs
         */
        $('div[id^=quick-search] .tabs').tabs({
            beforeActivate: function(event, ui){
                var tab = $(ui.newTab).attr('class').replace(/ui-[^\s]*/g, '').trim();

                if(_CONFIG_DATA.links[tab] != undefined && _CONFIG_DATA.links[tab] != '') {
                    window.location.href = _CONFIG_DATA.links[tab];
                    return false;
                }

                return true;
            },
            // Esto es un truco para evitar que las tabs hagan conflicto con Joomla.
            // De acuerdo al bug reportado aqui (http://bugs.jqueryui.com/ticket/8851),
            // sa valida que no se haga ninguna peticion, ya que no es necesario debido
            // a que las tabs siempre funcionan con contenido local (dentro de la pagina actual)
            beforeLoad: function(event, ui) {
                return false;
            }
        });


        /**
         * En caso que la variable _data exista, se cargan los valores
         * a sus respectivos campos
         */
        (function(){
            // Se mezcla los valores por defecto en la variable _data, para mostrarlos en
            // el QS. En este caso solo aplica para la ciudad de salida por defecto
            if(_CONFIG_DATA.default_city != '') {
                if(window._data == undefined) {
                    window._data = {wsform: {}};
                    window._data.wsform.B_LOCATION_1 = '';
                }

                if(window._data.wsform.B_LOCATION_1 == '') {
                    window._data.wsform.B_LOCATION_1 = _CONFIG_DATA.default_city.substr(0, 3);
                    window._data.wsform.extra = _CONFIG_DATA.default_city;
                }
            }
            var num = 0;
            // Se cargan todos los campos ocultos, identificados por que son los que
            // tienen el atributo name con el valor de la llave (key)
            if(window._data != undefined && window._data.wsform != undefined) {
                $.each(window._data.wsform, function(key, value){
                    if(key == 'ajax' || key == 'extra') return;

                    // En caso que sean los radio, se ubica el seleccionado
                    if(key == 'TRIP_TYPE') {
                        $('input[name*=' + key + '][value=' + value + ']', '#quick-search-in').prop('checked', true).change();
                        return;
                    }

                    $('input[name*=' + key + '], select[name*=' + key + ']', '#quick-search-in').val(value);
                });

                // Se cargan los valores de los campos de autocompletado
                $('.complete-air').each(function(index, element){
                    if($('#h-' + element.id).val() != '') {
                        var pN = window._data.wsform.extra.indexOf($('#h-' + element.id).val() + '|'), pNA;
                        if(pN != -1) {
                            pNA = window._data.wsform.extra.indexOf(';', pN + 4) - pN - 4;
                            pNA = pNA < 0 ? undefined : pNA;
                            $(element).val(window._data.wsform.extra.substr(pN + 4, pNA));
                          


                            //----------------------------Cargar ciudad origen y destino en caché------------------------------
                            
                             num = num + 1;
                            
                            if (num == 1){
                               localStorage["c_origen"] = window._data.wsform.extra.substr(pN + 4, pNA);
                            }else if (num == 2){
                                localStorage["c_destino"] = window._data.wsform.extra.substr(pN + 4, pNA);
                            }

                        }
                    }
                });

                // Se cargan los valores de las fechas, en sus respectivos calendarios
                $('.datepicker-air').each(function(index, element){
                    if($('#h-' + element.id).val() != '') {
                        $(element).datepicker('setDate', $.datepicker.parseDate( "yy-mm-dd", $('#h-' + element.id).val()))
                    }
                });

                // Se cargan el valor de la aerolinea
                $('.complete-airline').each(function(index, element){
                    if($('#h-' + element.id).val() != '') {
                        var pN = window._data.wsform.extra.indexOf(';' + $('#h-' + element.id).val() + '|'), pNA;
                        if(pN != -1) {
                            pNA = window._data.wsform.extra.indexOf(';', pN + 4) - pN - 4;
                            pNA = pNA < 0 ? undefined : pNA;
                            $(element).val(window._data.wsform.extra.substr(pN + 4, pNA));
                        }
                    }
                });
            }
        })();


        // Se actualizan los select de los pasajeros
        $('#adults').change();
    }

    /**
     * Setter para colocar los parametros configurables del Joomla en el objeto
     */
    var set = function(data){
        $.extend(_CONFIG_DATA, data);
    }


    /**
     * Se exponen los metodos necesarios para que se pueda trabajar con el objeto
     */
    $.extend(module, {
        load: load,
        set: set
    });
})(jQuery, AirAawsQS);


//----------------------------------------------------------------------------------------
/*Funcion JQuery creado para capturar todos los datos del buscador de vuelos.
Los datos están almacenados en Caché y se dirigen al formulario J DContact para
enviarlos por E-Mail a las asesoras - freelances o satélites.*/


$(document).ready(function()
  {
  $("#accion").click(function () {

    localStorage["radio_iv"] = $('input:radio[id=tt-round-trip]:checked').val();
    localStorage["radio_i"] = $('input:radio[id=tt-one-way]:checked').val();
    localStorage["radio_m"] = $('input:radio[id=tt-multiple]:checked').val();
    localStorage["c_origen_iata"] = $('#h-departure-1').val();
    localStorage["c_destino_iata"] = $('#h-arrival-1').val();
    localStorage["f_salida"] = $('#h-departure_date-1').val();
    localStorage["f_llegada"] = $('#h-departure_date-2').val();
   // localStorage["tipo_click"] = val('buscador');
    if ($('#clase').val() == "Economy"){
        localStorage["c_viaje"] = "Turista";
    }else if ($('#clase').val() == "First"){
        localStorage["c_viaje"] = "Primera";
    }else if ($('#clase').val() == "Business"){
        localStorage["c_viaje"] = "Negocios";
    }
    localStorage["aerolinea"] = $('#airline').val();
    localStorage["adultos"] = $('#adults').val();
    localStorage["mayores"] = $('#seniors').val();
    localStorage["niños"] = $('#children').val();
    localStorage["bebés"] = $('#babies').val();

  });
});