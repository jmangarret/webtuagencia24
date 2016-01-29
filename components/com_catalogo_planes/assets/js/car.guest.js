jQuery(document).ready(function(){
	//Evento para mostrar la pantalla para envio de mail
	jQuery("#mail").click(function(){
		//Si no se muestra el formulario de logueo		
		var dialogMail = jQuery("#dialog_mail").dialog({			
			modal: true,
			draggable: false,
			resizable: false,
			buttons:[{
				text:buttonSendMailText,
				"id": "btnEnviar",
				click:function(){
					if(jQuery(".ui-dialog").find("#to_mail").val()!=""){
						if(isUserEmailMPA(jQuery(".ui-dialog").find("#to_mail").val())){
							jQuery.ajax({
								type: "POST",
							  	url: "index.php?option=com_catalogo_planes&view=car&layout=maildetail",
							  	data: { 
									mail: jQuery(".ui-dialog").find("#to_mail").val()
								},						
								beforeSend: function() {
									jQuery("#mensagge_mail").html(messsageSending+"...");
								},
								success: function(datos){
									if(datos=="no_session"){
										dialogMail.dialog("close");
										showAlert(messsageNoSession, "");
									}else{
										jQuery(".ui-dialog").find("#mensagge_mail").html("");
										dialogMail.dialog("close");
										if(datos=="ok"){									
											showAlert(messsageOkMail, "");
										}else{
											showAlert(messsageErrorMail, "");
										}
									}
														
								}
							});
						}else{
							jQuery("#mensagge_mail").html(errorEmailLogin);
						}
					}else{
						jQuery("#mensagge_mail").html(messsageRequired);
						jQuery(".ui-dialog").find("#to_mail").addClass("error");
					}
					
				}
			}]
		});
		//Cuando vuelva a oprimir otra tecla el campo de texto queda en blanco
		jQuery(".ui-dialog").find("#to_mail").keypress(function(){			
			jQuery("#mensagge_mail").html("");
			jQuery(this).removeClass("error");
		});
	});
	
	//Evento para enviar el submit al formulario
	jQuery("#button_next").click(function(){
		errorCount = 0;		
		jQuery("#frm_guest").submit();		
	});
	//Recorro todos los campos y les agrego el mensaje por defecto de validacion
	jQuery(document).ready(function(){
		jQuery('#frm_guest').find('.required').each(function() {
			jQuery(this).rules("add", {
	    		messages: {
	    		 	required: messsageRequired
				}
			});
		});
	});
	
    
	//Validacion del formulario
	jQuery("#frm_guest").validate({
		rules:{
			"contact[mail]": { email: true},
			"contact[repeat_mail]": { email: true, equalTo: "#mail_contact" },
			"payment":{required:true},
			"conditions_check":{required:true}
		},
		messages:{
			"contact[mail]": { 
				email: messageMail
			},
			"contact[repeat_mail]": {
				email: messageMail,
				equalTo: messageConfirmMail
			},
			"payment":{required:messsageRequiredPayment},
			"conditions_check":{required:messsageRequiredConditions}
		},
		errorPlacement: function(error, element) {
			if (errorCount > 0) return;
			jQuery("#messageBox").html(error);
			jQuery("#messageBox").prepend("");
			jQuery("#messageBox").append("<br /><br />");
			errorCount++;
		},
		submitHandler: function(form){
			jQuery("#button_next").hide();
			jQuery("#button_wait").show();
			//Cuando se realiza el submit se valida si se encuentra logueado
			if(noRegister){
				//Si no se muestra el formulario de logueo
				var dialog = jQuery("#dialog_registro").dialog({
					width: 480,
					modal: true,
					draggable: false,
					resizable: false,
					close: function( event, ui ) {
						if(!submit){
							jQuery("#button_next").show();
							jQuery("#button_wait").hide();
						}
					}
				});
				//Si se da click en el boton login se llama la funcion de logueo
				jQuery(".ui-dialog").find("#login").click(function() {					
					loginCMSMPA(form, dialog);
				});
				//Este es el caso en que se oprima la tecla enter tambien se tiene que realizar el logueo
				jQuery("#form-login input.campo_vent_usuario").bind("keypress", function(e) {
					if (e.keyCode == 13) {						
						loginCMSMPA(form, dialog);
						return false;
					}
				});
				//En caso de que se quiera crear un nuevo usuario
				jQuery(".ui-dialog").find("#newUser").click(function(){		
					jQuery("#user_type").val("1");
					noRegister=false;
					form.submit();
				});				
				jQuery("#form-modlgn input.reveal-campo").bind("keypress", function(e) {
					if (e.keyCode == 13) {
						$('#form-modlgn .boton_modal').click();
						return false;
					}
				});
			}else{
						
				//Se realiza el submit
				form.submit();
				
				
			}
		}
	});
	
});
var errorCount=0;
var noRegister=false;
var submit = false;

/**
 * Function para el logueo
 * @param form
 * @returns {Boolean}
 */
function loginCMSMPA(form, dialog){
	var errortd = jQuery('.ui-dialog').find('.mensaje_error');	
	
	var error = false;
	// Validar que estén todos los campos.
	// Si no se ha ingresado el texto, se resalta el campo.
	jQuery('.ui-dialog').find('.campo_vent_usuario').each(function() {
		if (jQuery(this).val() == '') {
			jQuery(this).addClass('invalid');
			if (!error) {
				jQuery(this).focus();
				error = true;
			}
		} else {
			jQuery(this).removeClass('invalid');
		}
	});
	if (error) {
		errortd.html(emptyFieldsLogin);
	} else {
		
		// Verificar que se ingresó un correo válido.
		var isEmail = isUserEmailMPA(jQuery('.ui-dialog').find('#modlgnmod_username').val());		
		if (isEmail) {
			jQuery.ajax({
			type: 'POST',
			url: 'index.php?option=com_user',
			data: jQuery('.ui-dialog').find('#form-login').serialize() + '&dataType=json&format=raw&tmpl=component',
			beforeSend: function() {
				jQuery('.ui-dialog').find('#login').hide();
				jQuery('.ui-dialog').find('#wait_login').show();				
			},
			complete: function(data) {
				if (data.responseText == 'OK') {
					noRegister=false;					
					jQuery(form).submit();
					submit = true;
					jQuery(dialog).dialog( "close" );
				} else {
					jQuery('.ui-dialog').find('#login').show();
					jQuery('.ui-dialog').find('#wait_login').hide();	
					errortd.html(data.responseText);
				}
			}
		});
		} else {
			errortd.html(errorEmailLogin);
		}
	}
	return false; 
}
/**
 * Function que valida el correo
 * @param value
 * @returns
 */
function isUserEmailMPA(value) {
	// contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
	return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(value);
}
