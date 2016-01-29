var Common = {};

(function($, module, event){
 
	/**
     * Function que formatea un numero
     */
    var formatNumber = function() {
        var element;

        element = $.isPlainObject(this) ? $(arguments[0]) : $(this);

        if(element.val().trim().match(/^[-+]?\d+([.,]\d*)?$/)) {
            var num = element.val().replace(/,/, '.');
            num = parseFloat(num);
            num = (Math.round(num * 100) / 100) + '';

            var splitStr   = num.split('.');
            var splitLeft  = splitStr[0];
            var splitRight = splitStr.length > 1 ? ',' + splitStr[1] : ',';
            splitRight = (splitRight + '00').replace(/,(\d{2})\d*$/, ',$1');

            while (/(\d+)(\d{3})/.test(splitLeft))
                splitLeft = splitLeft.replace(/(\d+)(\d{3})/, '$1' + '.' + '$2');

            element.val(splitLeft + splitRight);
        } else {
            element.val('');
        }
    }

    /**
     * Funcion que deformatea un numero
     */
    var unformatNumber = function() {
        var element;

        element = $.isPlainObject(this) ? $(arguments[0]) : $(this);

        if(!/^[+-]?\d+\.\d*$/.test(element.val()))
            element.val(element.val().replace(/\./g, '').replace(/,/, '.'));

        if(!$.isPlainObject(this))
            window.setTimeout(function(){element.select();}, 100);
    }

    /**
     * Funcion para validar que el campo solo contenga numeros
     */
    var forceNumeric = function(event){
        var key, keychar;

        if(window.event)
            key = window.event.keyCode;
        else if (event)
            key = event.which;
        else
            return true;

        keychar = String.fromCharCode(key);

        // control keys
        if((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
            return true;

        // numbers
        else if ((("0123456789.,").indexOf(keychar) > -1))
            return true;

        else
            return false;
    }

    $.extend(module, {
        forceNumeric: forceNumeric,
        formatNumber: formatNumber,
        unformatNumber: unformatNumber
    })

})(jQuery, Common);


var Fees = {};

(function($, module, event){

    var _load = function(){
        $('input.number')
            .focus(Common.unformatNumber)
            .blur(Common.formatNumber)
            .keypress(Common.forceNumeric);
    }
    $.extend(module, {
        load: _load
    })
})(jQuery, Fees);

 

 

var TA = {};

(function($, module){

    var _ROW = null;
    var _PREVIOUSVALUE = 'M';

    // Inicializa las grillas
    var GridInitialize = function(){
        // Se inhabilita el primer campo, por que este debe ser cero.
        $('.grid tbody tr input.min').attr('readonly', 'readonly');

        // Se agregan los eventos generales
        var element = $('.grid tbody tr input.number');

        $(element).on('focus', Common.unformatNumber);
        $(element).on('blur', Common.formatNumber);
        $(element).on('keypress', Common.forceNumeric);

        $('.grid tbody tr input.max').on('blur', valMaximum);
        $('.grid tbody tr input.max').on('focus', setMaximum);
        $('.grid tbody tr input[type=button]').on('click', removeRow);

        $('.grid tbody tr:last-child').addClass('last');
 	   if($("input[type='checkbox']").is(':checked')){
	    	$(".value_senior").attr('disabled','disabled');
	    	$(".value_child").attr('disabled','disabled');
	    	$(".value_infant").attr('disabled','disabled');
	    }else{
	    	$(".value_senior").removeAttr('disabled');
	    	$(".value_child").removeAttr('disabled');
	    	$(".value_infant").removeAttr('disabled');
	    }
    }

    var setMaximum = function(){
        _PREVIOUSVALUE = $(this).val();
    }

    var valMaximum = function(){
        var tr = $(this).parents('tr'), minField1, minField2, maxField1, maxField2, min1, min2, max1, max2, change;

        if(tr.next().length > 0)
        {
            minField1 = tr.find('input.min');
            minField2 = tr.next().find('input.min');

            maxField1 = tr.find('input.max');
            maxField2 = tr.next().find('input.max');

            Common.unformatNumber(minField1);
            Common.unformatNumber(minField2);
            Common.unformatNumber(maxField1);
            Common.unformatNumber(maxField2);

            min1 = parseFloat(minField1.val());
            min2 = parseFloat(minField2.val());
            max1 = parseFloat(maxField1.val());
            max2 = maxField2.val() != 'M' ? parseFloat(maxField2.val()) : 'M';

            change = true;

            if(min1 > max1){
                alert('El valor máximo debe ser mayor al valor minimo');
                $(maxField1).val(_PREVIOUSVALUE);
                change = false;
            }

            if(max1 > max2){
                alert("El rango actual solapa el siguiente rango");
                $(maxField1).val(_PREVIOUSVALUE);
                change = false;
            }

            if(change)
                minField2.val(max1 + 0.01);

            Common.formatNumber(minField1);
            Common.formatNumber(minField2);
            Common.formatNumber(maxField1);
            if(max2 != 'M')
                Common.formatNumber(maxField2);
        }
    }
    
 

    //verifica si se chequeo el combo de asignar ta a pasajeros
    var chequeo = function(){
    
    $("input[type='checkbox']").change(function() {
        if(this.checked) {
        	$(".value_senior").attr('disabled','disabled');
        	$(".value_child").attr('disabled','disabled');
        	$(".value_infant").attr('disabled','disabled');
        	$('input:checkbox:checked').val(1); 
        }else{
        	$(".value_senior").removeAttr('disabled');
        	$(".value_child").removeAttr('disabled');
        	$(".value_infant").removeAttr('disabled');
        	$("input[type='checkbox']").val(0); 
        }
    });
    }
    
    // Funcion para agregar filas
    var addRow = function(table, value){
        var row = _ROW.clone(true),
            minField = $(row).find('input.min'),
            maxField = $(row).find('input.max'),
            _tb = $(table).attr('id').split('-')[1];

        $(row).find('input[name]').each(function(i, e){
            $(e).attr('name', $(e).attr('name').replace(/ON/, _tb));
        });

        var element = $(row).find('input.number');

 
        $(element).on('focus', Common.unformatNumber);
        $(element).on('blur', Common.formatNumber);
        $(element).on('keypress', Common.forceNumeric);

        $(maxField).on('blur', valMaximum);
        $(maxField).on('focus', setMaximum);
        minField.val(value);
        Common.unformatNumber(minField);
        minField.val(parseFloat(minField.val()) + 0.01);
        Common.formatNumber(minField);

        $(table).find('tr').removeClass('last');
        $(row).find('input[type=button]').on('click', removeRow);
        $(table).find('tbody').append(row);
        if($("input[type='checkbox']").is(':checked')){
        	$(".value_senior").attr('disabled','disabled');
        	$(".value_child").attr('disabled','disabled');
        	$(".value_infant").attr('disabled','disabled');
        	$('input:checkbox:checked').val(1); 
        }else{
        	$(".value_senior").removeAttr('disabled');
        	$(".value_child").removeAttr('disabled');
        	$(".value_infant").removeAttr('disabled');
        	$("input[type='checkbox']").val(0); 
        }
 
        GridListeners('#' + $(table).attr('id'));
    }

    
    // Elimina una fila
    var removeRow = function(){
        var tr = $(this).parents('tr');
        tr.next().find('input.min').val(tr.find('input.min').val());
        tr.remove();
    }

    // Eventos para los elementos de la grilla
    var GridListeners = function(table){
        var table = table != undefined ? table : '',
            element = $(table + '.grid tbody tr:last-child input.max');

        $(element).on('blur.addrow', function(){
            var valor = $(this).val().trim();
            if(valor != 'M' && valor != '') {
                // Validando el rango
                var tr = $(this).parents('tr'), min, max;
                Common.unformatNumber(tr.find('input.min'));
                Common.unformatNumber(tr.find('input.max'));

                min = parseFloat(tr.find('input.min').val());
                max = parseFloat(tr.find('input.max').val());

                Common.formatNumber(tr.find('input.min'));
                Common.formatNumber(tr.find('input.max'));
                
                if(min > max) {
                    alert('El valor máximo debe ser mayor al valor minimo');
                    $(element).val(_PREVIOUSVALUE);
                }else{
                    $(this).off('blur.addrow');
                    addRow($(this).parents('table').get(0), $(this).val());
                }
                
            }else{
                $(element).val('M');
            }
        });
    }

    var _load = function(){
        $('#tabs').tabs();
        GridInitialize();
        GridListeners();
        chequeo();

        var num = $('#table-ON >tbody >tr').length - 1;
        _ROW = $('#table-ON >tbody >tr').get(num).clone(true);
    }

    $.extend(module, {
        load: _load
    })
})(jQuery, TA);

