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
            if($_POST)
            {
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
                $f_salida_banner            =       trim($_REQUEST['f_salida_banner']);
                $f_llegada_banner           =       trim($_REQUEST['f_llegada_banner']);
                $origen                     =       trim($_REQUEST['origen']);
                $destino                    =       trim($_REQUEST['destino']);
                $c_viaje                    =       trim($_REQUEST['c_viaje']);
                $aerolinea                  =       trim($_REQUEST['aerolinea']);
                $adultos                    =       trim($_REQUEST['adultos']);
                $mayores                    =       trim($_REQUEST['mayores']);
                $niños                      =       trim($_REQUEST['niños']);
                $bebés                      =       trim($_REQUEST['bebés']);
                //$subject                  =       trim($_REQUEST['subject']);
                $subject                    =       "Cliente: Reserva de vuelo pendiente.";
                $msg                        =       trim($_REQUEST['msg']);
                $msg_banner                =       trim($_REQUEST['msg_banner']);
                $sales_address              =       $params->get( 'sales_address', 'sales@yourdomain.com' );
                $support_address            =       $params->get( 'support_address', 'support@yourdomain.com' );
                $billing_address            =       $params->get( 'billing_address', 'billing@yourdomain.com' );
                $selfcopy                   =       isset($_REQUEST['selfcopy']) ? $_REQUEST['selfcopy'] : "";
                $humantest                  =       $_REQUEST['human_test'];
                $sum_test                   =       $_REQUEST['sum_test'];
                $humantestpram              =        $params->get( 'humantestpram', '1' );

                //RURIEPE 01/09/2016 - SE CAMBIA LA FORMA EN QUE SE ENVIABA EL CORREO Y CREACION DEL TICKET. PRIMERO SE ENVIABA EL CORREO SIN VERIFICIAR QUE EL TICKET SE CREARA. AHORA SE CREA EL TICKET DE PRIMERO Y SI ESTE RESULTA EXITOSO SE ENVIA UN CORREO NOTIFICANDO SU CREACION.

                //CABECERA DEL CORREO ELECTRONICO
                $headers  = 'MIME-Version: 1.0'."\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
                $headers .= 'From: '.$name.' <'.$email.'>'."\r\n";
                $msjenviado .= '<label style=color:#1467b6>Datos enviados correctamente,Nos contactaremos con usted cuando dispongamos de un vuelo a su destino.</label>';

                //RURIEPE 24/08/2016 - CONDICION PARA EVALUAR EL VALOR DE LA VARIABLE f_salida SI LA CONDICION SE HACE VERDADERA INDICA QUE LA SOLICITUD VIENE DEL BANNER (DESTINOS SOTOS).CUERPO DEL TEXTO QUE SE CREA EN 0STICKET
                if ($f_salida == "defaultValue")
                {

                    $itinerario = "
                    Datos del Cliente\n------------------------------------------------------------\n\t*Nombre del cliente: $name\n\t*Correo electrónico: $email\n\t*Número de teléfono: $phno\n\t*Información adicional: $msg_banner\n\nDatos del Vuelo\n------------------------------------------------------------\n\t*Destino Soto\n\t*Ciudad de origen: $origen\n\t*Ciudad de destino: $destino\n\t*Fecha de salida: $f_salida_banner\n\t*Fecha de regreso: $f_llegada_banner\n";
                }
                elseif ($radio == "Solo Ida")
                {
                    $itinerario = "
                    Datos del Cliente\n------------------------------------------------------------\n\t*Nombre del cliente: $name\n\t*Correo electrónico: $email\n\t*Número de teléfono: $phno\n\t*Información adicional: $msg\n\nDatos del Vuelo\n------------------------------------------------------------\n\tTipo de pasaje: $radio\n\t*Ciudad de origen: $c_origen\n\t*Ciudad de destino: $c_destino\n\t*Fecha de salida: $f_salida\n\t*Clase: $c_viaje    *Aerolínea: $aerolinea\n\t*Adultos: $adultos          *Mayores: $mayores\n\t*Niños: $niños              *Bebés: $bebés";
                }
                else 
                {
                    $itinerario = "
                    Datos del Cliente\n------------------------------------------------------------\n\t*Nombre del cliente: $name\n\t*Correo electrónico: $email\n\t*Número de teléfono: $phno\n\t*Información adicional: $msg\n\nDatos del Vuelo\n------------------------------------------------------------\n\tTipo de pasaje: $radio\n\t*Ciudad de origen: $c_origen\n\t*Ciudad de destino: $c_destino\n\t*Fecha de salida: $f_salida\n\t*Fecha de regreso: $f_llegada\n\t*Clase: $c_viaje    *Aerolínea: $aerolinea\n\t*Adultos: $adultos          *Mayores: $mayores\n\t*Niños: $niños             *Bebés: $bebés";
                }

                //Mensajes personalizados del PopUp de Contacto.
                $mjsnombre = "Por favor ingrese su nombre.";
                $msjmail = "Por favor ingrese un correo electrónico válido.";
                $msjtelf = "Por favor ingrese un número telefónico válido.";
                $msjdestino = "Debe seleccionar un destino.";
                $msjf_salida_banner = "Debe seleccionar la fecha de salida.";
                $msjf_regreso_banner = "Debe seleccionar la fecha de regreso.";
                $msjerror = "Error al enviar datos, por favor intentelo de nuevo más tarde.";

                $to1     =   $sales_address;
                $to2     =   $support_address;
                $to3     =   $billing_address;

                if ( $name == "" )
                {
                    $result = "".$mjsnombre."";
                }

                //Modificado: Colocar "i" al final del "preg.match" para no diferenciar mayúsculas y minúsculas.
                elseif (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email))
                {
                    $result = "".$msjmail."";
                }
                elseif($phno=="")
                {
                    $result = "".$msjtelf."";
                }
                //
                elseif($destino == "0" AND $f_salida== 'defaultValue')
                {
                    $result = "".$msjdestino."";
                }
                elseif ($f_salida_banner == "" AND $f_salida== 'defaultValue')
                {
                    $result = "".$msjf_salida_banner."";
                }
                elseif($f_llegada_banner =="" AND $f_salida== 'defaultValue')
                {
                    $result = "".$msjf_regreso_banner."";
                }
                     
                else if($humantestpram=='1' && $humantest!=$sum_test)
                {
                    $result = "".JText::_('MOD_JDCONTACT_CORRECTNUM')."";
                }
                else
                {
                    //SE CONECTA A LA BASE D EDATOS
                    $db =& JFactory::getDBO();

                    //SE INCLUYE ARCHIVO PARA LA API
                    include("ost-api.php");

                    if ($f_salida == "defaultValue")
                    {
                        $valores=$msg_banner.'%'.'Destino Soto'.'%'.$origen.'%'.$destino.'%'.$f_salida_banner.'%'.$f_llegada_banner.'%'.'-'.'%'.'-'.'%'.'-'.'%'.'-'.'%'.'-'.'%'.'-';
                    }
                    else
                    {
                        $valores=$msg.'%'.$radio.'%'.$c_origen.'%'.$c_destino.'%'.$f_salida.'%'.$f_llegada.'%'.$c_viaje.'%'.$aerolinea.'%'.$adultos.'%'.$mayores.'%'.$niños.'%'.$bebés;
                    }
                     
                    
                    //SE HACE LLAMADO DE LA CLASE Y FUNCION PARA PASAR LOS VALORES PARA LA CREACION DEL TICKET Y SE CAPTURA EN LA VARIABLE $ticket el valor retornado
                    $ticket = ost_api::send_ost_api($name,$email,$phno,$itinerario,$valores);
                    echo '<script language="javascript">alert("Retorno: '.$ticket.'");</script>';


                    //RURIEPE 02/09/2016 - SE CREA CONEXION CON LA BASE DE DATOS DE OSTICKET
                    $conexion_ost = new mysqli("humbermarccs.dyndns.tv", "adminroot", "adminr00t24", "osticket1911");
                    //RURIEPE 02/09/2016 - SE VERIFICA SI SE CREA LA CONEXION, EN CASO DE NO CREARSE SE ENVIA UN MENSAJE DE ERROR
                    if ($conexion_ost->connect_errno) 
                    {
                        echo "Fallo al conectar a MySQL: (" . $conexion_ost->connect_errno . ") " . $conexion_ost->connect_error;
                    }

                    //RURIEPE 02/09/2016 - SE REALILZA CONSULTA 
                    $consulta_ost_id = $conexion_ost->query("SELECT ost.ticket_id 
                    FROM ost_ticket AS ost 
                    WHERE ost.number = '$ticket'");

                    //RURIEPE 02/09/2016 - SE REALILZA CAPTURA EL RESULTADO DE LA BUSQUEDA EN UN VARIABLE PHP
                    while($resul=mysqli_fetch_array($consulta_ost_id))
                    {
                        $id_ticket=$resul['ticket_id'];
                    }

                    //RURIEPE 02/09/2016 - CONDICION PARA EVALUAR EL VALOR DE LA VARIABLE f_salida SI LA CONDICION SE HACE VERDADERA INDICA QUE LA SOLICITUD VIENE DEL BANNER (DESTINOS SOTOS).CUERPO DEL TEXTO QUE SE CREA EN EL CORREO
                    if ($f_salida == "defaultValue")
                    {

                        $message.='
                        <table>
                            <tr>
                                <th>----------------------------------------------------------------------------------------------------</th>
                            </tr>
                            <tr>
                                <th><i>Datos del Cliente</i></th>
                            </tr>
                            <tr>
                                <th>----------------------------------------------------------------------------------------------------</th>
                            </tr>
                            <tr>
                                <td align="center" ><b>Nombre del cliente:</b>'.$name.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Correo electrónico:</b>'.$email.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Número de teléfono:</b>'.$phno.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Información adicional:</b>'.$msg_banner.'</td>
                            </tr>
                            <tr>
                                <th>----------------------------------------------------------------------------------------------------</th>
                            </tr>
                            <tr>
                                <th><i>Datos del Vuelo</i></th>
                            </tr>
                            <tr>
                                <th>----------------------------------------------------------------------------------------------------</th>
                            </tr>
                            <tr>
                                <!--RURIEPE 05/09/2016 - LINK QUE SE CREA EN EL NUMERO DEL TICKET PARA REDIRECCIONAR AL OSTICKET-->
                                <td align="center"><b>Número de ticket:</b><a href="http://humbermarccs.dyndns.tv:8080/ostickets/upload/scp/tickets.php?id='.$id_ticket.'"> #'.$ticket.'</a></i></td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Destino SOTO</b></td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Ciudad de origen:</b>'.$origen.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Ciudad de destino:</b>'.$destino.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Fecha de salida:</b>'.$f_salida_banner.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Fecha de llegada:</b>'.$f_llegada_banner.'</td>
                            </tr>
                        </table>';  
                    }
                    elseif ($radio == "Solo Ida")
                    {

                        $message.='
                        <table>
                            <tr>
                                <th>----------------------------------------------------------------------------------------------------</th>
                            </tr>
                            <tr>
                                <th><i>Datos del Cliente</i></th>
                            </tr>
                            <tr>
                                <th>----------------------------------------------------------------------------------------------------</th>
                            </tr>
                            <tr>
                                <td align="center" ><b>Nombre del cliente:</b>'.$name.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Correo electronico:</b>'.$email.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Número de teléfono:</b>'.$phno.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Información adicional:</b>'.$msg.'</td>
                            </tr>
                            <tr>
                                <th>----------------------------------------------------------------------------------------------------</th>
                            </tr>
                            <tr>
                                <th><i>Datos del Vuelo</i></th>
                            </tr>
                            <tr>
                                <th>----------------------------------------------------------------------------------------------------</th>
                            </tr>
                            <tr>
                                <!--RURIEPE 05/09/2016 - LINK QUE SE CREA EN EL NUMERO DEL TICKET PARA REDIRECCIONAR AL OSTICKET-->
                                <td align="center"><b>Número de ticket:</b><a href="http://humbermarccs.dyndns.tv:8080/ostickets/upload/scp/tickets.php?id='.$id_ticket.'"> #'.$ticket.'</a></i></td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Tipo de vuelo:</b>'.$radio.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Ciudad de origen:</b>'.$c_origen.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Ciudad de destino:</b>'.$c_destino.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Fecha de salida:</b>'.$f_salida.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Clase:</b>' . $c_viaje.' - <b>Aerolinea:</b>' . $aerolinea.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Adultos:</b>'. $adultos.' - <b>Mayores:</b>' . $mayores.' - <b>Niños:</b>' . $niños. '- <b>Bebés:</b>' . $bebés.'</b></td>
                            </tr>
                        </table>'; 
                    }
                    else 
                    {
                        $message.='
                        <table>
                            <tr>
                                <th>----------------------------------------------------------------------------------------------------</th>
                            </tr>
                            <tr>
                                <th><i>Datos del Cliente</i></th>
                            </tr>
                            <tr>
                                <th>----------------------------------------------------------------------------------------------------</th>
                            </tr>
                            <tr>
                                <td align="center" ><b>Nombre del cliente:</b>'.$name.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Correo electronico:</b>'.$email.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Número de teléfono:</b>'.$phno.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Información adicional:</b>'.$msg.'</td>
                            </tr>
                            <tr>
                                <th>----------------------------------------------------------------------------------------------------</th>
                            </tr>
                            <tr>
                                <th><i>Datos del Vuelo</i></th>
                            </tr>
                            <tr>
                                <th>----------------------------------------------------------------------------------------------------</th>
                            </tr>
                            <tr>
                                <!--RURIEPE 05/09/2016 - LINK QUE SE CREA EN EL NUMERO DEL TICKET PARA REDIRECCIONAR AL OSTICKET-->
                                <td align="center"><b>Número de ticket:</b><a href="http://humbermarccs.dyndns.tv:8080/ostickets/upload/scp/tickets.php?id='.$id_ticket.'"> #'.$ticket.'</a></i></td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Tipo de vuelo:</b>'.$radio.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Ciudad de origen:</b>'.$c_origen.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Ciudad de destino:</b>'.$c_destino.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Fecha de salida:</b>'.$f_salida.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Fecha de llegada:</b>'.$f_llegada.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Clase:</b>' . $c_viaje.' - <b>Aerolinea:</b>' . $aerolinea.'</td>
                            </tr>
                            <tr>
                                <td align="center" ><b>Adultos:</b>'. $adultos.' - <b>Mayores:</b>' . $mayores.' - <b>Niños:</b>' . $niños. '- <b>Bebés:</b>' . $bebés.'</b></td>
                            </tr>
                        </table>';     
                    }

                    //SE REALIZA EL ENVIO DEL CORREO ELECTRONICO
                    if(@mail($to1, $subject, $message, $headers))
                    {
                        $sucs=1;
                    }

                    if( $selfcopy == "yes" )
                    {
                        if(@mail($email, $subject, $message, $headers))
                        {
                            $sucs=1;
                        }
                    }
                    if($sucs==1)
                    {
                        $result = "".$msjenviado."";
                    }
                    else
                    {
                        $result = "".$msjerror."";
                    }
                }
                //SE MOUESTRA MENSAJE DESPUES DEL ENVIO DEL CORREO Y EN CASO DE ERROR AL ENVIAR CORREO
                if($javascript_enabled == "true") 
                {   
                    echo $result;
                    die();
                }
            }
        }
    }
?>
