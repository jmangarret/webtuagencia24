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
/*error_reporting(E_ALL);
        ini_set('display_errors', '1');*/
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

include("ost-api.php");

if ($radio == "Solo Ida"){

            $itinerario = "NOMBRE DEL CLIENTE: $name\nCORREO DEL CLIENTE: $email\nTELÉFONO DEL CLIENTE: $phno\n\nMENSAJE ADICIONAL DEL CLIENTE:\n $msg\n\n\n
            ------------------------------------------------------
            TIPO DE PASAJE: $radio\n\nCIUDAD DE ORIGEN: $c_origen   \n  CIUDAD DE DESTINO: $c_destino\n\nFECHA DE SALIDA: $f_salida \n\n
            CLASE: $c_viaje  \n   AEROLÍNEA: $aerolinea\n\nADULTOS: $adultos \n  MAYORES: $mayores \n  NIÑOS: $niños  \n BEBÉS: $bebés
            ------------------------------------------------------\n";  

            }else{

            $itinerario = "NOMBRE DEL CLIENTE: $name\nCORREO DEL CLIENTE: $email\nTELÉFONO DEL CLIENTE: $phno\n\nMENSAJE ADICIONAL DEL CLIENTE:\n $msg\n\n\n
            ------------------------------------------------------
            TIPO DE PASAJE: $radio\n\nCIUDAD DE ORIGEN: $c_origen   \n  CIUDAD DE DESTINO: $c_destino\n\nFECHA DE SALIDA: $f_salida  \n  FECHA DE REGRESO: $f_llegada\n\n
            CLASE: $c_viaje  \n   AEROLÍNEA: $aerolinea\n\nADULTOS: $adultos \n  MAYORES: $mayores \n  NIÑOS: $niños  \n BEBÉS: $bebés
            ------------------------------------------------------\n";    

            }
ost_api::send_ost_api($name,$email,$phno,$itinerario);

        	}

        	if($javascript_enabled == "true") {
        		echo $result;
        		die();
        	}
        }
	}
}

?>