<?php

/*------------------------------------------------------------------------
# J DContact
# ------------------------------------------------------------------------
# author                Md. Shaon Bahadur
# copyright             Copyright (C) 2014 j-download.com. All Rights Reserved.
# @license -            http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites:             http://www.j-download.com
# Technical Support:    http://www.j-download.com/request-for-quotation.html
-------------------------------------------------------------------------*/

defined('_JEXEC') or die;
error_reporting(E_ALL);
        ini_set('display_errors', '1');
class modJdcontactHelper
{
	static function preLoadprocess(&$params)
	{
         if($_POST){
            $javascript_enabled         =       trim($_REQUEST['browser_check']);
            $department                 =       trim($_REQUEST['dept']);
            $name                       =       trim($_REQUEST['name']);
            $email                      =       trim($_REQUEST['email']);
            $phno                       =       trim($_REQUEST['phno']);
            $radio                      =       trim($_REQUEST['radio']);
            $c_origen                   =       trim($_REQUEST['c_origen']);
            $c_destino                  =       trim($_REQUEST['c_destino']);
            $f_salida                   =       trim($_REQUEST['f_salida']);
            $f_llegada                  =       trim($_REQUEST['f_llegada']);
            $c_viaje                    =       trim($_REQUEST['c_viaje']);
            $aerolinea                  =       trim($_REQUEST['aerolinea']);
            $adultos                    =       trim($_REQUEST['adultos']);
            $mayores                    =       trim($_REQUEST['mayores']);
            $niños                      =       trim($_REQUEST['niños']);
            $bebés                      =       trim($_REQUEST['bebés']);
            //$subject                    =       trim($_REQUEST['subject']);
            $subject                    =       "Cliente: Reserva de vuelo pendiente.";
            $msg                        =       trim($_REQUEST['msg']);
            $sales_address              =       $params->get( 'sales_address', 'sales@yourdomain.com' );
            $support_address            =       $params->get( 'support_address', 'support@yourdomain.com' );
            $billing_address            =       $params->get( 'billing_address', 'billing@yourdomain.com' );
            $selfcopy                   =       isset($_REQUEST['selfcopy']) ? $_REQUEST['selfcopy'] : "";
            $humantest                  =       $_REQUEST['human_test'];
            $sum_test                   =       $_REQUEST['sum_test'];
            $humantestpram              =        $params->get( 'humantestpram', '1' );



            $headers  = 'MIME-Version: 1.0rn';
            $headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
            $headers .= 'From: '.$name.' <'.$email.'>'."\r\n";

            if ($radio == "Solo Ida"){

            $message = "NOMBRE DEL CLIENTE: $name\nCORREO DEL CLIENTE: $email\nTELÉFONO DEL CLIENTE: $phno\n\nMENSAJE ADICIONAL DEL CLIENTE:\n $msg\n\n\n
            ------------------------------------------------------
            TIPO DE PASAJE: $radio\n\nCIUDAD DE ORIGEN: $c_origen   \n  CIUDAD DE DESTINO: $c_destino\n\nFECHA DE SALIDA: $f_salida \n\n
            CLASE: $c_viaje  \n   AEROLÍNEA: $aerolinea\n\nADULTOS: $adultos \n  MAYORES: $mayores \n  NIÑOS: $niños  \n BEBÉS: $bebés
            ------------------------------------------------------\n";  

            }else{

            $message = "NOMBRE DEL CLIENTE: $name\nCORREO DEL CLIENTE: $email\nTELÉFONO DEL CLIENTE: $phno\n\nMENSAJE ADICIONAL DEL CLIENTE:\n $msg\n\n\n
            ------------------------------------------------------
            TIPO DE PASAJE: $radio\n\nCIUDAD DE ORIGEN: $c_origen   \n  CIUDAD DE DESTINO: $c_destino\n\nFECHA DE SALIDA: $f_salida  \n  FECHA DE REGRESO: $f_llegada\n\n
            CLASE: $c_viaje  \n   AEROLÍNEA: $aerolinea\n\nADULTOS: $adultos \n  MAYORES: $mayores \n  NIÑOS: $niños  \n BEBÉS: $bebés
            ------------------------------------------------------\n";    

            }
            
            //Mensajes personalizados del PopUp de Contacto.
            $mjsnombre = "Por favor ingrese su nombre.";
            $msjmail = "Por favor ingrese un correo electrónico válido.";
            $msjtelf = "Por favor ingrese un número telefónico válido.";
            $msjenviado = "<label style=\"color: #1467b6;\">Datos enviados correctamente, contactaremos con usted cuando dispongamos de un vuelo a su destino.</label>";
            $msjerror = "Error al enviar datos, por favor intentelo de nuevo más tarde.";



        	/*if ( $department == "sales")        $to     =   $sales_address;
        	elseif ( $department == "support")  $to     =   $support_address;
        	elseif ( $department == "billing")  $to     =   $billing_address;
            else                                $to     =   $sales_address;*/



            $to1     =   $sales_address;
            $to2     =   $support_address;
            $to3     =   $billing_address;

        	if ( $name == "" )
        	{
        		//$result = "".JText::_('MOD_JDCONTACT_VLDNAME')."";
                $result = "".$mjsnombre."";
        	}
        	//Modificado: Colocar "i" al final del "preg.match" para no diferenciar mayúsculas y minúsculas.
            elseif (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email))
        	{
        		//$result = "".JText::_('MOD_JDCONTACT_VALIDEMAIL')."";
                $result = "".$msjmail."";
        	}
        	else if($phno=="")
        	{
        		//$result = "".JText::_('MOD_JDCONTACT_PHONENUMB')."";
                $result = "".$msjtelf."";
        	}
        	/*elseif ( $subject == "" )
        	{
        		$result = "".JText::_('MOD_JDCONTACT_MSGSUBJECT')."";
        	}
        	elseif ( strlen($msg) < 10 )
        	{
        		$result = "".JText::_('MOD_JDCONTACT_MORETENWRD')."";
        	}*/
            else if($humantestpram=='1' && $humantest!=$sum_test){
        	    $result = "".JText::_('MOD_JDCONTACT_CORRECTNUM')."";
            }
        	else
        	{

//Metodo pra enviar aleatoriamente los correos
/*$mail = range(1, 3);
shuffle($mail);

foreach ($mail as $valor) {
$email = "$valor";
}
    if ($email == 1){

        if(@mail($to1, $subject, $message, $headers)){

                            $sucs=1;

                        }
    }elseif ($email == 2) {

        if(@mail($to2, $subject, $message, $headers)){

                            $sucs=1;

                        }
    }elseif ($email == 3) {

        if(@mail($to3, $subject, $message, $headers)){

                            $sucs=1;

                        }
    }*/

        	    if(@mail($to1, $subject, $message, $headers)){
$sucs=1;
                    /*if(@mail($to2, $subject, $message, $headers)){

                        if(@mail($to3, $subject, $message, $headers)){

                            

                        }
                    }*/
                    
        	    }


        		if( $selfcopy == "yes" ){
        		    if(@mail($email, $subject, $message, $headers)){
                        $sucs=1;
        		    }
                }
                if($sucs==1){
        		    //$result = "".JText::_('MOD_JDCONTACT_SUCCESSMSG')."";
                    $result = "".$msjenviado."";
                }
                else{
                    //$result = "".JText::_('MOD_JDCONTACT_MAILSERVPROB')."";
                    $result = "".$msjerror."";
                }
                $db =& JFactory::getDBO();

/*$query = "INSERT INTO kqzro_boleto_popup (name_passenger, email_passenger, phone_passanger, flight_type, departure, 
         arrival, departure_date, arrival_date, flight_class, airline, adult, senior, child, baby) 
         VALUES ('".$name."', '".$email."', '".$phno."', '".$radio."', '".$c_origen."', '".$c_destino."', '".$f_salida."', '".$f_llegada."', '".$c_viaje."', '".$aerolinea."', '".$adultos."', '".$mayores."', '".$niños."', '".$bebes."')";
$db->setQuery($query);
$db->query();
//$results = $db->loadObjectList();
$query2 = "SELECT * FROM kqzro_boleto_popup";
$db->setQuery($query2);
$results = $db->loadObjectList();*/


include("ost_api.php");

if($config=ost_api::config()){
        if ($radio == "Solo Ida"){
            $itinerary = "------------------------------------------------------
            TIPO DE PASAJE: $radio\n\nCIUDAD DE ORIGEN: $c_origen   \n  CIUDAD DE DESTINO: $c_destino\n\nFECHA DE SALIDA: $f_salida \n\n
            CLASE: $c_viaje  \n   AEROLÍNEA: $aerolinea\n\nADULTOS: $adultos \n  MAYORES: $mayores \n  NIÑOS: $niños  \n BEBÉS: $bebes
            ------------------------------------------------------\n";  
        }else{
            $itinerary = "------------------------------------------------------
            TIPO DE PASAJE: $radio\n\nCIUDAD DE ORIGEN: $c_origen   \n  CIUDAD DE DESTINO: $c_destino\n\nFECHA DE SALIDA: $f_salida  \n  FECHA DE REGRESO: $f_llegada\n\n
            CLASE: $c_viaje  \n   AEROLÍNEA: $aerolinea\n\nADULTOS: $adultos \n  MAYORES: $mayores \n  NIÑOS: $niños  \n BEBÉS: $bebes
            ------------------------------------------------------\n";    
        }
        if($data=ost_api::data($name,$email,$phno,$itinerary)){
            if(ost_api::curl_post($config,$data)){
                echo"EXITO";
            }else{
                echo"FRACASO";
            }
        }
}




/*
$config = array(
        'url'=>'http://127.0.0.1/ticket.tuagencia24.com/upload/api/tickets.json',  // URL to site.tld/api/tickets.json //Antes era -> http://your.domain.tld/api/tickets.json
    'key'=>'E9059472F7AC91544A453862B5D10C7B'  // API Key goes here //PUTyourAPIkeyHERE
);

$data=array(
    'name'      =>      $name,  // from name aka User/Client Name
    'email'     =>      $email,  // from email aka User/Client Email
    'phone'     =>      $phone,  // phone number aka User/Client Phone Number
    'subject'   =>      'Administracion',  // test subject, aka Issue Summary
    'message'   =>      $itinerary,  // test ticket body, aka Issue Details.
    //'ip'        =>      $_SERVER['REMOTE_ADDR'], // Should be IP address of the machine thats trying to open the ticket.
    'topicId'   =>      '19' // the help Topic that you want to use for the ticket 
    //'Agency'  =>    '58', //this is an example of a custom list entry. This should be the number of the entry.
    //'Site'  =>    'Bermuda'; // this is an example of a custom text field.  You can push anything into here you want. 
    //  'attachments' => array()
    );

function_exists('curl_version') or die('CURL support required');
function_exists('json_encode') or die('JSON support required');

#set timeout
set_time_limit(30);

#curl post
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $config['url']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.8');
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$config['key']));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result2=curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($code != 201)
    die('Unable to create ticket: '.$result2);

$ticket_id = (int) $result2;

# Continue onward here if necessary. $ticket_id has the ID number of the
# newly-created ticket

function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}

*/
        	}

        	if($javascript_enabled == "true") {
        		echo $result;
        		die();
        	}
        }
	}
}

?>