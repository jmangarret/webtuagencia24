 //jQuery.noConflict();
jQuery('#searchOrders').ready(function(){
jQuery(function() {
	jQuery.datepicker.regional['es'] = {
		      closeText: 'Cerrar',
		      prevText: '<Ant',
		      nextText: 'Sig>',
		      currentText: 'Hoy',
		      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		      monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
		      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado'],
		      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sab'],
		      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		      weekHeader: 'Sm',
		      dateFormat: 'dd/mm/yy',
		      firstDay: 1,
		      isRTL: false,
		      showMonthAfterYear: false,
		      yearSuffix: ''};
		  
	
        jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ "es" ] );
		jQuery( "#fechaDesde" ).datepicker({
			showOn: "button",
			buttonImage: "/images/calendar.png",
			buttonImageOnly: true,
                        dateFormat: 'dd/mm/yy',
                        changeYear: true,
                        yearRange: 'c-20:c',
                        minDate: '-20y',
                        maxDate: '0',
                        onSelect: function(dateText, inst) { 
                                jQuery( "#fechaHasta" ).datepicker( "option", "minDate", dateText );
                                jQuery( "#fechaHasta" ).datepicker( "option", "defaultDate", dateText );                            
                        }
		});
            jQuery( "#fechaHasta" ).datepicker({
			showOn: "button",
			buttonImage:  "/images/calendar.png",
			buttonImageOnly: true,
                        dateFormat: 'dd/mm/yy',
                        changeYear: true,
                        yearRange: 'c-20:c',
                        maxDate: '0'
		});
    });			
});			