jQuery.noConflict()(document).ready(function($) {
    var min_date = new Date();
    min_date.setDate(min_date.getDate() + 1);

	$('#booking_date').datepicker({
		showOn: "button",
		minDate: min_date,
		dateFormat: 'yy-mm-dd',
		changeYear: true,
		changeMonth: true,
		buttonImage: '/joomla/images/calendar.png',
		numberOfMonths: 2,
		buttonImageOnly: true,
		showButtonPanel: true
	});

	var identifier = window.location.hash;
	if (identifier === '#mailsuccess') {
		$('#system-message-container').html('<div id="cpmail" style="background-color: #c3d2e5; color: #0055bb; border-top: 3px solid #84a7db; border-bottom: 3px solid #84a7db; padding-left: 1em; font-weight: bold; margin-bottom: 20px;"> <p>Su solicitud ha sido porcesada, gracias por reservar con nosotros</p> </div>');
		$('#cpmail').delay(2000).fadeOut();
		setTimeout(function() {
			$('#cpmail').remove();
		}, 3500);
	}

    $("#productSaveCatalog").submit(function (event) {
		var errortd = $('#productSaveCatalog .mensaje_error');
		var error = false;
		
		// Validar que estan todos los campos.
		// Si no se ha ingresado el texto, se resalta el campo.
		$('#productSaveCatalog .required').each(function() {
			if ($.trim($(this).val()) == '') {
				$(this).addClass('invalid');
				if (!error) {
					$(this).focus();
					error = true;
				}
			} else {
				$(this).removeClass('invalid');
			}
		});

		if (error) {
			errortd.html(errorSaveProduct);
			return false;
		}
		// Verificar que se ingresó un correo válido.
		var emailField = $('#productSaveCatalog #client_email');
		var isEmail = isUserEmail(emailField.val());
		if (!isEmail) {
			emailField.focus();
			emailField.addClass('invalid');
			errortd.html(errorEmailSaveProduct);
			return false;
		}
		var confirmEmailField = $('#productSaveCatalog #client_confirm_email');
		if (emailField.val() != confirmEmailField.val()) {
			confirmEmailField.focus();
			confirmEmailField.addClass('invalid');
			errortd.html(errorConfirmEmailSaveProduct);
			return false;
		}
		var total_passengers = parseInt($('#productSaveCatalog #total_adults').val()) + parseInt($('#productSaveCatalog #total_children').val());
		if (total_passengers < 1) {
			$('#productSaveCatalog #total_adults').addClass('invalid');
			$('#productSaveCatalog #total_children').addClass('invalid');
			errortd.html(errorPassengersSaveProduct);
			return false;
		}

		errortd.html('');
		return true;
    });

    function isUserEmail (value) {
		// contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
		return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(value);
	}
});
