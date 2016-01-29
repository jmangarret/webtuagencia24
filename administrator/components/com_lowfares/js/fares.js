/**
 * Elemento para almacenar el comportamiento del formulario de Aereo
 *
 * El objeto solo contiene los metodos que se deben exponer para
 * el correcto funcionamiento del formulario
 */
var AirAawsQS = { };

(function($, module){

    /**
     * Cache de los strings que contienen la información de las ciudades.
     * El formato del string es igual al encontrado en el sitio oficial de
     * amadeus (amadeus.net)
     */
    var _CACHE = { };

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
        $(matches).blur(function(){
            if($(this).val().length > 3){
                $(this).val('');
            }
        }).
        bind("keydown", ACKeyDownValidation)
        .autocomplete({
            delay: 100,

            position: {collision: 'flip'},

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
                        url  : '../modules/mod_aaws_qs/cities/ES/' + term + '.txt',
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
                var id = this.id;

                // Se colocan los valores en sus respectivos campos
                $('#' + id).val(ui.item['builder']._iata);
                $('#' + id + 'name').val(ui.item['builder']._cityName);

                return false;
            },

            select: function(event, ui){
                var id = this.id;
                $('#' + id + 'name').focus();
                return false;
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

    // Funcion para validar que el campo solo contenga numeros
    var forceNumeric = function(){
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

    // Valida que en una caja de texto solo se ingresen numeros
    var _forceNumeric = function(matches){
        $(matches).on('keypress', forceNumeric);
    }

    // Funcionamiento del tipo de salida
    var _departureTypeListener = function(){
        $('#type_1').click(function(){
            $('#departure')
            .off('keypress', forceNumeric)
            .datepicker({
                dateFormat: "yy-mm-dd",
                buttonText: '&Xi;',
                minDate: 0,
                showOn: "both"
            })
            .attr('name', 'jform[departure]')
            .val('');
        });

        $('#type_2').click(function(){
            $('#departure')
            .datepicker('destroy')
            .attr('name', 'jform[offset]')
            .val(0);
            _forceNumeric('#departure');
        });
    }


    // Funcion que carga los eventos y propiedades de javascript, necesarias para el componente
    var _load = function(){
        var _val = $('#departure').val();

        _loadAutoCompleteListeners(".complete-air");
        _departureTypeListener();
        _forceNumeric("#duration");

        if($('#departure').val().match(/^\d+$/)){
            $('#type_2').click();
        }else{
            $('#type_1').click();
        }

        $('#departure').val(_val);
    }

    // Funcion que se encarga de obtener el precio de una tarifa
    var _getValue = function(data, total){
        var progress   = $('#window-process .loader .progress'),
            percentTag = $('#window-process .loader .percent').val('0 %'),
            percent    = parseInt(((total - data.length) / total) * 100);

        progress.width(percent + '%');
        percentTag.html(percent + ' %');

        if(data.length){
            var _log = $('#window-process .log ul');
            _log.append('<li><label class="start">' + data[0].originname + ' - ' + data[0].destinyname + '</label></li>');
            $('#window-process .log').scrollTop(5000);

            $.ajax({
                type: 'post',
                url: 'index.php?option=com_lowfares&task=ajax.processItinerary',
                dataType: 'json',
                data: {id: data.shift().id}
            }).done(function(msg){
                _logResponse(msg);
                _getValue(data, total);
            });
        }else{
            $('#window-process .button input').val('Cerrar');
        }
    }

    // Coloca en la vista de logs el resultado del llamado
    var _logResponse = function(info){
        var _log = $('#window-process .log ul');

        if(info.type == 'success'){
            _log.find('li:last').append('<label class="ok">' + info.msg + ' OK' + '</label>');
        }else{
            _log.find('li:last').append('<label class="fail">' + 'FAIL' + '</label>');
            _log.append('<li><label class="error">' + info.msg + '</label></li>');
        }
    }

    // Ventana de proceso que obtiene las tarifas de todos los destinos seleccionados
    var _process = function(){
        var start  = false;
        var option = $('#window-process select[name=filter_itineraries]').children().get(0);

        $('#toolbar-refresh a')
        .attr('onclick', null)
        .click(function(){
            start = false;

            $(document.body).append('<div id="overlay"></div>');
            $('#overlay').height($(document).height() + 'px');

            $('#window-process select[name=filter_itineraries]').empty().append(option);
            $('#window-process select[name=filter_categories]').val('');

            $('#window-process').show('fade');
            return false;
        });

        // Ajax para cargar los itinerarios por categoria
        $('#window-process select[name=filter_categories]').change(function(){
                $.ajax({
                    type: 'post',
                    url: 'index.php?option=com_lowfares&task=ajax.getItinerariesFromCategory',
                    dataType: 'json',
                    data: {category: $(this).val()}
                }).done(function(msg){
                    $('#window-process select[name=filter_itineraries]').empty().append(option);
                    if(msg.length){
                        var select = $('#window-process select[name=filter_itineraries]');
                        for(var i = 0, j = msg.length; i < j; i++){
                            select.append('<option value="' + msg[i].id + '">' + msg[i].originname + ' - ' + msg[i].destinyname + '</option>')
                        }
                    }
                });
        });

        // Boton para iniciar el calculo
        $('#window-process .loader .container').click(function(){
            if(!start){
                var data = { };

                $('#window-process .loader .container').removeClass('processing');
                start = true;

                data.category  = $('#window-process select[name=filter_categories]').val();
                data.state     = $('#window-process select[name=filter_published]').val();
                data.itinerary = $('#window-process select[name=filter_itineraries]').val();

                $('#window-process select').attr('disabled', 'disabled');
                $('#window-process .loader .percent').html('0 %');

                $.ajax({
                    type: 'post',
                    url: "index.php?option=com_lowfares&task=ajax.getItineraries",
                    dataType: 'json',
                    data: data
                }).done(function(msg){
                    _getValue(msg, msg.length + 1);
                });
            }
        });

        // Boton cancelar
        $('#window-process .button input').click(function(){
            if($('#window-process .loader .container.processing').length){
                $('#overlay').remove();
                $('#window-process').hide('fade');
            }else{
                document.forms.adminForm.submit();
            }
        });
    }

    /**
     * Se exponen los metodos necesarios para que se pueda trabajar con el objeto
     */
    $.extend(module, {
        load: _load,
        process: _process
    });

})(jQuery, AirAawsQS);

