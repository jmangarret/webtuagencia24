<?php

/*------------------------------------------------------------------------
# J DContact
# ------------------------------------------------------------------------
# author                Yanuel Leal
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

    $showdepartment  	     =        $params->get( 'showdepartment', '1' );
    $showsendcopy            =        $params->get( 'showsendcopy', '1' );
    $humantestpram           =        $params->get( 'humantestpram', '1' );
    $sales_address           =        $params->get( 'sales_address', 'sales@yourdomain.com' );
    $support_address         =        $params->get( 'support_address', 'support@yourdomain.com' );
    $billing_address         =        $params->get( 'billing_address', 'billing@yourdomain.com' );
    $backgroundcolor         =        $params->get( 'backgroundcolor', '#FFEFD5' );
    $wrp_width               =        $params->get( 'wrp_width', '320px' );
    $inputfield_width        =        $params->get( 'inputfield_width', '300px' );
    $inputfield_border       =        $params->get( 'inputfield_border', '#CCCCCC' );
    $result                  =        '';
    $name                    =        '';
    $email                   =        '';
    $phno                    =        '';
    $subject                 =        '';
    $msg                     =        '';
    $selfcopy                =        '';
    $sucs                    =        '';
    $varone                  =        rand(5, 15);
    $vartwo                  =        rand(5, 15);
    $sum_rand                =        $varone+$vartwo;

?>
    <link rel="stylesheet" href="<?php echo JURI::root(); ?>modules/mod_jdcontact/tmpl/lib/contact.css" media="screen" />
    <script src="<?php echo JURI::root(); ?>modules/mod_jdcontact/tmpl/lib/jquery-1.4.4.js"></script>
    <script src="<?php echo JURI::root(); ?>modules/mod_jdcontact/tmpl/lib/jquery-1.4.4.js">
    //Función para limitar un campo TEXT a solo números
		function justNumbers(e)
        {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
        return true;
         
        return /\d/.test(String.fromCharCode(keynum));
        }
    //--------------------------------------------------
    var radio_iv = localStorage["radio_iv"]  ||  'No seleccionado' ; //valor R en check
    var radio_i = localStorage["radio_i"]  ||  'No seleccionado' ; //valor O en chech
    var radio_m = localStorage["radio_m"]  ||  'No seleccionado' ; //valor M en chech
    var c_origen_iata = localStorage["c_origen_iata"]  ||  'defaultValue' ;
    var c_origen = localStorage["c_origen"]  ||  'defaultValue' ;
    var c_destino_iata = localStorage["c_destino_iata"]  ||  'defaultValue' ;
    var c_destino = localStorage["c_destino"]  ||  'defaultValue' ;
    var f_salida = localStorage["f_salida"]  ||  'defaultValue' ;
    var f_llegada = localStorage["f_llegada"]  ||  'Solo ida' ;
    var c_viaje = localStorage["c_viaje"]  ||  'defaultValue' ; //Economy / Business / First
    var aerolinea = localStorage["aerolinea"]  ||  'Cualquiera' ;
    var adultos = localStorage["adultos"]  ||  'defaultValue' ;
    var mayores = localStorage["mayores"]  ||  'defaultValue' ;
    var niños = localStorage["niños"]  ||  'defaultValue' ;
    var bebés = localStorage["bebés"]  ||  'defaultValue' ;

    //Cargar los valores en el formulario del PopUp
    if (radio_iv == "R"){
        document.getElementById("radio").value  = "Ida y Vuelta";
    }else if (radio_i == "O"){
        document.getElementById("radio").value  = "Solo Ida";
    }else if(radio_m == "M"){
        document.getElementById("radio").value  = "Multiples Destinos";
    }
    document.getElementById("c_origen").value  = c_origen;
    document.getElementById("c_destino").value  = c_destino;
    document.getElementById("f_salida").value  = f_salida;
    document.getElementById("f_llegada").value = f_llegada;
    document.getElementById("c_viaje").value  = c_viaje;
    document.getElementById("aerolinea").value  = aerolinea;
    document.getElementById("adultos").value  = adultos;
    document.getElementById("mayores").value  = mayores;
    document.getElementById("niños").value  = niños;
    document.getElementById("bebés").value  = bebés;

    </script>   

    <div id="contactform" style="background: <?php echo $backgroundcolor; ?>;width: <?php echo $wrp_width; ?>;border:1px solid <?php echo $inputfield_border; ?>;">
    <span id="closedailyp" style="cursor: pointer; position: relative; bottom: 15px; margin-left: 307px; font-weight: bold;">X</span>
            <div style="background-color: #1467b6; margin-left: -21px;   margin-top: -40px; width: <?php echo $wrp_width; ?>; border-top-left-radius: 1em 1em; border-top-right-radius: 1em 1em;"><br>
            <h3 style="color: #FFFFFF" align="center">Hemos registrado su solicitud.</h3> <p style="color: #FFFFFF; font-weight: bold; text-align: center;">Envíenos su información personal y seguro le contactaremos</p>
        </div>
        <form name="contactform" id="form" method="post" action="<?php $_SERVER['PHP_SELF']?>">
            <?php if($showdepartment=='1') : ?>
              <label><?php echo JText::_('MOD_JDCONTACT_DEPARTMENT'); ?></label><br />
              <select style="width: <?php echo $inputfield_width; ?>;border:1px solid <?php echo $inputfield_border; ?>;" name="dept" class="text">
              	<option value="sales"><?php echo JText::_('MOD_JDCONTACT_SALES'); ?></option>
              	<option value="support"><?php echo JText::_('MOD_JDCONTACT_SUPPORT'); ?></option>
              	<option value="billing"><?php echo JText::_('MOD_JDCONTACT_BILLING'); ?></option>
              </select><br />
            <?php endif; ?>
            <!--MODIFICADO SOLO EL NOMBRE DEL LABEL QUE SE MUESTRA EN EL FRONT-END, CAMPO ASUNTO DOCUMENTADO-->
            <!--Campo Teléfono anexado con la función "justNumbers" en un evento "onkeypress"-->
            <label class="name" style="color: #000000;"><?php echo "*Nombre";//echo JText::_('MOD_JDCONTACT_NAME'); ?><br /><input style="background-color: white; width: <?php echo $inputfield_width; ?>;" class="text" name="name" type="text" value="<?php echo $name; ?>" /><br /></label>
            <label class="email" style="color: #000000;"><?php echo"*Correo electrónico";//echo JText::_('MOD_JDCONTACT_EMAIL'); ?><br /><input style="background-color: white; width: <?php echo $inputfield_width; ?>;" class="text" name="email" type="text" value="<?php echo $email; ?>" /><br /></label>
            <label class="phno" style="color: #000000;"><?php echo"*Teléfono";//echo JText::_('MOD_JDCONTACT_TELEPHONE'); ?><br /><input style="background-color: white; width: <?php echo $inputfield_width; ?>;" class="text" name="phno" type="text" value="<?php echo $phno; ?>" onkeypress="return justNumbers(event);" /><br /></label>
            <!--<label class="subject"><?php //echo"*Asunto";//echo JText::_('MOD_JDCONTACT_SUBJECT'); ?><br /><input style="width: <?php echo $inputfield_width; ?>;border:1px solid <?php echo $inputfield_border; ?>;" class="text" name="subject" type="text" value="<?php echo $subject; ?>" /><br /></label>-->
            <label class="msg" style="color: #000000;"><?php echo"Información Adicional (Opcional)";//echo JText::_('MOD_JDCONTACT_MESSAGE'); ?><br /><textarea style="background-color: white; width: <?php echo $inputfield_width; ?>;" class="text" name="msg"><?php echo $msg; ?></textarea><br /></label>
            <label style="color: #000000; font-weight: bold;">*Campos Obligatorios.</label></br>
            <center><span style="font-size: 13pt; color: #21A73C;" ><img src="<?php echo JURI::root(); ?>images/img/ic-whatsapp.png" height="20px;" alt="ic-line" border="0">WhatsApp
            </span><span class="big-blue" style="font-weight: bold;">(0426) 401 2821</span>&nbsp;</center>
            <hr>


            <input type="hidden" id="radio" value="" class="text" name="radio">
            <input type="hidden" id="c_origen" value="" class="text" name="c_origen">
            <input type="hidden" id="c_destino" value="" class="text" name="c_destino">
            <input type="hidden" id="f_salida" value="" class="text" name="f_salida">
            <input type="hidden" id="f_llegada" value="" class="text" name="f_llegada">
            <input type="hidden" id="c_viaje" value="" class="text" name="c_viaje">
            <input type="hidden" id="aerolinea" value="" class="text" name="aerolinea">
            <input type="hidden" id="adultos" value="" class="text" name="adultos">
            <input type="hidden" id="mayores" value="" class="text" name="mayores">
            <input type="hidden" id="niños" value="" class="text" name="niños">
            <input type="hidden" id="bebés" value="" class="text" name="bebés">


            <!--////////////////////////////////////////////////////////////////////////////////////////////-->
            <?php if($showsendcopy=='1') : ?>
                <input type="checkbox" name="selfcopy" <?php if($selfcopy == "yes") echo "checked='checked'"; ?> value="yes" />
                <label><?php echo JText::_('MOD_JDCONTACT_SELFCOPY'); ?></label><br /><br />
            <?php endif; ?>
            <?php if($humantestpram=='1') : ?>
            <div style="border-bottom: 1px solid <?php echo $inputfield_border; ?>;border-top: 1px solid <?php echo $inputfield_border; ?>;padding-bottom: 2px;padding-top: 10px;">
                <label for='message'><?php echo JText::_('MOD_JDCONTACT_HUMANTEST'); ?></label>
                <?php echo '<b>'.$varone.'+'.$vartwo.'=</b>'; ?>
                <input id="human_test" name="human_test" size="3" type="text" class="text" style="border:1px solid <?php echo $inputfield_border; ?>;"><br>
                <input type="hidden" id="sum_test" name="sum_test" value="<?php echo $sum_rand; ?>" />
            </div>
            <?php endif; ?>
            <br />
            <input type="hidden" name="browser_check" value="false" />
            <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                <tr>
                    <td width="20%" valign="top" align="center">
                    <!--MODIFICADO EL VALOR DEL BOTÓN QUE SE MUESTRA EN EL FRONT-END-->
                        <input type="submit" name="submit" value="<?php echo"Enviar";//echo JText::_('MOD_JDCONTACT_SUBMIT'); ?>" id="submit" />
                    <!--////////////////////////////////////////////////////////////-->
                    </td>
                </tr>
                <tr>
                    <td width="100%" valign="top" align="center">
                        <div id="result"><?php if($result) echo "<div class='message'>".$result."</div>"; ?></div>
                    </td>
                </tr>
            </table>
        </form>

        <script type="text/javascript">
	    document.contactform.browser_check.value = "true";
	    $("#submit").click(function(){
		$('#result').html('<img src="<?php echo JURI::root(); ?>modules/mod_jdcontact/tmpl/images/loader.gif" class="loading-img" alt="loader image">').fadeIn();
        $("#submit").prop('disabled', true);
		var input_data = $('#form').serialize();
				$.ajax({
				   type: "POST",
				   url:  "<?php echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>",
				   data: input_data,
				   success: function(msg){
					   $('.loading-img').remove().fadeIn('slow'); //Removing the loader image because the validation is finished
					   $('<div class="message">').html(msg).appendTo('div#result').hide().fadeIn('slow'); //Appending the output of the php validation in the html div

                       if (msg=='<?php echo "<label style=\"color: #1467b6;\">Datos enviados correctamente, contactaremos con usted cuando dispongamos de un vuelo a su destino.</label>"; ?>') {
                        $('#submit').hide(700);
                            $("#dailyfullscreen").delay(6500).animate({ opacity: "toggle" }, function(){$("#dailyfullscreen").remove(); window.location.href ="index.php";});
                            
                       }else{
                        $("#submit").prop('disabled', false);
                       }
                        if(msg=='<?php echo JText::_("MOD_JDCONTACT_SUCCESSMSG"); ?>'){
                          $('#form').each (function(){
                            this.reset();
                          });
                       }
				   }
				});
            
			return false;
            //$(selector).hide(duracion,callback)//;
	    });
	    </script>
    </div>