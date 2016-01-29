jQuery(document).ready(function($){

    var initializeDetailsListeners = function(){
        $('#resume-order #resume-flight div.detail a').click(function(){
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

            $('#resume-order .flight-detail').not(detail).hide({effect: 'fade'});
            detail.toggle({effect: 'fade'});
        });

        $('#resume-order #resume-flight .flight-detail .close').click(function(){
            $(this).parents('.flight-detail').toggle({effect: 'fade'});
        });
    }

    initializeDetailsListeners();

});
