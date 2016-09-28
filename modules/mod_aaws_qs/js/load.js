jQuery(function($){
    
    AirAawsQS.load();
 // Colocar para cambiar en combo, poner en li oculto los datos.
    var select = $("#tipo").val();
     
    if(select =="2"){  
    var valorcombo = $("#combo").val();
    $("#departure-1").remove();
    $("#h-departure-1").remove();
    $("#combito").append(valorcombo);	
    $("#h-departure-1 option[value='CCS']").attr("selected", "selected");
    }
    // Coloca el focus en el primer campo del formulario de aereo
    $('#departure-1').focus();

    // error no identificado al inicio
    var err_url = document.location.href;
    if (err_url.indexOf('index.php?e') > 0) {
        $('#system-message-container').html('<div id="cpmail" <div id="cpmail" style="background-color: #e6c0c0; color: #c00; border-top: 3px solid #DE7A7B; border-bottom: 3px solid #DE7A7B; padding-left: 1em; font-weight: bold; margin-bottom: 20px;"> <p>No se ha encontrado disponibilidad, por favor intentelo más tarde.</p> </div>');
        $('#cpmail').delay(4000).fadeOut();
        setTimeout(function() {
            $('#cpmail').remove();
        }, 5000);
    }

});
