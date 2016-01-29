jQuery(function($) {
	 $( ".dateBirthday input" ).each(function (i) {
		    jQuery(this).datepicker({
		        showOn: "button",
		        numberOfMonths: 2,
		        changeMonth: true,
		        changeYear: true,
		        buttonImage: "templates/desarrolloColotpl/images/ico_calendar.gif",
		        buttonImageOnly: true,        
		        dateFormat: 'dd/mm/yy',
		        maxDate: '31/12/'+maxDate
		    });  
		 });	
  
});
