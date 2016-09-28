<?php 

class EmailService{
	
	function getEmailTemplate($hotelId, $template, $loadDefault = false)
	{
		$db = JFactory::getDBO();
		$languageTag = JRequest::getVar( '_lang');
		
		$query = " SELECT a.*,hlt.content as email_content 
				   FROM #__hotelreservation_emails a
	     		   LEFT JOIN
					(select * from 
					 #__hotelreservation_language_translations 
					 where type = ".EMAIL_TEMPLATE_TRANSLATION."
					 and language_tag = '$languageTag'
					) as hlt on hlt.object_id = a.email_id
				   WHERE hotel_id='$hotelId' AND email_type = '".$template."'";
		$db->setQuery( $query );
		$templ= $db->loadObject();
		
		if(!isset($templ) && $loadDefault){
			$query = "SELECT a.*,hlt.content as email_content 
					  FROM #__hotelreservation_emails a
					  LEFT JOIN
							(select * from 
							 #__hotelreservation_language_translations 
							 where type = ".EMAIL_TEMPLATE_TRANSLATION."
							 and language_tag = '$languageTag'
							) as hlt on hlt.object_id = a.email_id
					WHERE email_type = '".$template."'";
			$db->setQuery( $query );
			$templ= $db->loadObject();
		}
		return $templ;
	}
	
	function sendHotelEmail($data){
		//dmp($data);
		$body = $data["email_note"];
		$mode		 = 1 ;//html
		//dmp($body);
		$ret = JMail::sendMail(
				$data["email_from_address"],
				$data["email_from_name"],
				$data["email_to_address"],
				"Share hotel",
				$body,
				$mode
		);
	
		if($data["copy_yourself"]==1){
			$ret = JMail::sendMail(
					$data["email_from_address"],
					$data["email_from_name"],
					$data["email_from_address"],
					"Share hotel",
					$body,
					$mode
			);
		}
		return $ret;
	}
	
	public static function sendConfirmationEmail($reservationDetails, $sendOnlyToAdmin = false){
	
		$emailTemplate = self::getEmailTemplate($reservationDetails->reservationData->hotel->hotel_id, RESERVATION_EMAIL,true);
		//var_dump($reservationDetails->reservationData->hotel->hotel_id);
		
		
		$content = EmailService::prepareReservationEmail($reservationDetails, $emailTemplate->email_content);
		/*print_r(EMAIL_COMPANY_LOGO);
		die;*/
		$from = $reservationDetails->reservationData->appSettings->company_email;
		$fromName = $reservationDetails->reservationData->appSettings->company_name;
		$toEmail =  $reservationDetails->reservationData->userData->email;
		$subject = $emailTemplate->email_subject;
		$subject = str_replace(EMAIL_RESERVATION_ID, JHotelUtil::getStringIDConfirmation($reservationDetails->confirmation_id), $subject);
		$isHtml = true;
	
		$bcc = array($from, $reservationDetails->reservationData->hotel->email);
		if($reservationDetails->reservationData->appSettings->hide_user_email == 1){
			$bcc = array($toEmail, $reservationDetails->reservationData->hotel->email);
			$toEmail = $from;
		}
		
		if($sendOnlyToAdmin){
			$subject = JText::_("LNG_WAITING_CONFIRMATION_EMAIL_ADMIN_SUBJECT");
			$subject = str_replace(EMAIL_RESERVATION_ID, JHotelUtil::getStringIDConfirmation($reservationDetails->confirmation_id), $subject);
			$bcc = null;
			$toEmail = $from;
		}
		
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml);
	}
	
	public static function sendClientInvoiceEmail($reservationDetails, $sendOnlyToAdmin = false){
	
		$emailTemplate = self::getEmailTemplate($reservationDetails->reservationData->hotel->hotel_id, INVOICE_CLIENT_EMAIL,true);
		
		$content = self::prepareInvoiceEmail($reservationDetails, $emailTemplate->email_content);
		$from = $reservationDetails->reservationData->appSettings->company_email;
		$fromName = $reservationDetails->reservationData->appSettings->company_name;
		$toEmail =  $reservationDetails->reservationData->userData->email;
		$subject = $emailTemplate->email_subject;
		$subject = str_replace(EMAIL_RESERVATION_ID, JHotelUtil::getStringIDConfirmation($reservationDetails->confirmation_id), $subject);
		$isHtml = true;
	
		$bcc = array($from, $reservationDetails->reservationData->hotel->email);
		if($reservationDetails->reservationData->appSettings->hide_user_email == 1){
			$bcc = array($toEmail, $reservationDetails->reservationData->hotel->email);
			$toEmail = $from;
		}
	
		if($sendOnlyToAdmin){
			$subject = JText::_("LNG_WAITING_CONFIRMATION_EMAIL_ADMIN_SUBJECT");
			$bcc = null;
			$toEmail = $from;
		}
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml);
	}
	
	
	
	function sendReviewEmail($reservationDetails)
	{
		$emailTemplate = self::getEmailTemplate($reservationDetails->reservationData->hotel->hotel_id,REVIEW_EMAIL );
	
		if( $emailTemplate ==null )
			return false;
		
		$content = EmailService::prepareReservationEmail( $reservationDetails,$emailTemplate->email_content);
		$from = $reservationDetails->reservationData->appSettings->company_email;
		$fromName = $reservationDetails->reservationData->appSettings->company_name;
		$toEmail =  $reservationDetails->reservationData->userData->email;
		
		$isHtml = 1;
	
		$subject = str_replace(EMAIL_HOTEL_NAME, $hotelName =$reservationDetails->reservationData->hotel->hotel_name
		,$emailTemplate->email_subject);

		return self::sendEmail($from, $fromName, $from, $toEmail, null, null, $subject, $content, $isHtml);	
	}
	
	function sendCancelationEmail($reservationDetails)
	{
		$emailTemplate = self::getEmailTemplate($reservationDetails->reservationData->hotel->hotel_id,CANCELATION_EMAIL );
	
		if( $emailTemplate ==null )
		return false;
	
		$content = EmailService::prepareReservationEmail( $reservationDetails,$emailTemplate->email_content);
		$from = $reservationDetails->reservationData->appSettings->company_email;
		$fromName = $reservationDetails->reservationData->appSettings->company_name;
		$toEmail =  $reservationDetails->reservationData->userData->email;
	
		$isHtml = 1;
		$bcc = array($from, $reservationDetails->reservationData->hotel->email);
		
		$subject = str_replace(EMAIL_HOTEL_NAME, $hotelName = $reservationDetails->reservationData->hotel->hotel_name, $emailTemplate->email_subject);
	
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml);
	}
	
	function sendReviewSubmitedEmail($reservationDetails, $review){
		$mode		 = 1 ;//html
		$body = JText::_('LNG_REVIEW_RECEIVED',true);
		$body = str_replace("<<hotelname>>", $reservationDetails->reservationData->hotel->hotel_name, $body);
		$companyLogo = "<img src=\"".JURI::root().PATH_PICTURES.$reservationDetails->reservationData->appSettings->logo_path."\" alt=\"Company logo\" />";
		$body = str_replace(EMAIL_COMPANY_LOGO, $companyLogo, $body);
		$body = str_replace(EMAIL_COMPANY_NAME, $reservationDetails->reservationData->appSettings->company_name, $body);
		
		$body .= "<br/><br/>";
		$body.=$review->review_short_description;
		$body .= "<br/>";
		$body.=$review->review_remarks;
		
		$subject = JText::_('LNG_NEW_REVIEW',true);
		$subject = str_replace("<<hotelname>>", $reservationDetails->reservationData->hotel->hotel_name, $subject);
	
		$from = $reservationDetails->reservationData->appSettings->company_email;
		$fromName = $reservationDetails->reservationData->appSettings->company_name;
		$toEmail =  $reservationDetails->reservationData->appSettings->company_email;;
		
		return self::sendEmail($from, $fromName, $from, $toEmail, null, null, $subject, $body, $mode);
	}
	
	public static function sendGuestListEmail($hotelId, $hotelName, $hotelEmail, $guestList, $arrivalDate){
		dmp("Send guest list for hotel ".$hotelName);
		dmp($guestList);
		$emailTemplate = self::getEmailTemplate($hotelId, GUEST_LIST_EMAIL, true);
		if(empty($emailTemplate))
			return;
		$subject = $emailTemplate->email_subject;
		$content = $emailTemplate->email_content;
		
		$appSettings = JHotelUtil::getInstance()->getApplicationSettings();
		
		$companyLogo = "<img src=\"".JURI::root().PATH_PICTURES.$appSettings->logo_path."\" alt=\"Company logo\" />";
		$content = str_replace(EMAIL_COMPANY_LOGO, $companyLogo, $content);
	
		$fromName	= $appSettings->company_name;
		$content = str_replace(EMAIL_COMPANY_NAME, $fromName, $content);
		
		$content = str_replace(EMAIL_GUEST_LIST, $guestList, $content);
		$content = str_replace(EMAIL_HOTEL_NAME, $hotelName, $content);
		$content = str_replace(EMAIL_ARRIVAL_DATE, JHotelUtil::convertToFormat($arrivalDate), $content);
		
		
		$appSettings = JHotelUtil::getInstance()->getApplicationSettings();
		
		self::sendEmail($appSettings->company_email, $appSettings->company_name, $appSettings->company_email,$hotelEmail, null, null, $subject, $content, true);
		
	}
	
	public static function sendNoAvailabilityEmail($hotelId, $startDate, $endDate){
		
		$log = Logger::getInstance();
		$log->LogDebug("No availabaility ".$hotelId." ".$startDate." ".$endDate);
		 
		$hotel=HotelService::getHotel($hotelId);
		
		$appSettings = JHotelUtil::getInstance()->getApplicationSettings();
		
		$datas =  JHotelUtil::convertToFormat($startDate);
		$datae =  JHotelUtil::convertToFormat($endDate);
	
		$mode		 = 1 ;//html
		$emailContent = JText::_('LNG_NO_AVAILABILITY_EMAIL',true);
		$emailContent = str_replace("<<hotel>>", $hotel->hotel_name, $emailContent);
		$emailContent = str_replace("<<start_date>>", $datas, $emailContent);
		$emailContent = str_replace("<<end_date>>", $datae, $emailContent);
	
		$email_subject = JText::_('LNG_NO_AVAILABILITY_EMAIL_SUBJECT',true);
		$email_subject = str_replace("<<hotel>>", $hotel->hotel_name, $email_subject);
		
		return self::sendEmail($appSettings->company_email, $appSettings->company_name, null, $appSettings->company_email, null, null, $email_subject, $emailContent, $mode);
	}
	
	public static function sendReservationFailureEmail($reservation){
		
		$appSettings = JHotelUtil::getInstance()->getApplicationSettings();
		$mode		 = 1 ;//html
		
		$log = Logger::getInstance();
		$log->LogDebug("Reservation failure ".serialize($reservation));
		
		$email = JText::_('LNG_RESERVAION_FAILURE_EMAIL',true);
		$email = str_replace("<<reservation_id>>", $reservation->confirmation_id, $email);
		$email = str_replace("<<start_date>>",  $reservation->start_date, $email);
		$email = str_replace("<<end_date>>",  $reservation->end_date, $email);
		$email = str_replace("<<name>>",  $reservation->last_name.' '. $reservation->first_name, $email);
		
		$email_subject = JText::_('LNG_RESERVAION_FAILURE_EMAIL_SUBJECT',true);
		$email_subject = str_replace("<<reservation_id>>", $reservation->confirmation_id, $email_subject);
		
		return self::sendEmail($appSettings->company_email, $appSettings->company_name, null, $appSettings->company_email, null, null, $email_subject, $email, $mode);
	}
	
	
	function prepareReservationEmail($reservationDetails, $emailTemplate){
		
		$datas = JHotelUtil::getDateGeneralFormat($reservationDetails->reservationData->userData->start_date);
		$datae = JHotelUtil::getDateGeneralFormat($reservationDetails->reservationData->userData->end_date);
	
		$ratingURL='<a href="'.JURI::root().'index.php?option='.getBookingExtName().'&controller=hotelratings&view=hotelratings&confirmation_id='.$reservationDetails->reservationData->userData->confirmation_id.'">'.JText::_('LNG_CLICK_TO_RATE',true).'</a>';
		$companyLogo = "<img src=\"".JURI::root().PATH_PICTURES.$reservationDetails->reservationData->appSettings->logo_path."\" alt=\"Company logo\" />";
	
		$chekInTime = $reservationDetails->reservationData->hotel->informations->check_in;
		$chekOutTime = $reservationDetails->reservationData->hotel->informations->check_out;
		$hotelName =$reservationDetails->reservationData->hotel->hotel_name;
		$cancellationPolicy =  $reservationDetails->reservationData->hotel->informations->cancellation_conditions;
		$touristTax = $reservationDetails->reservationData->hotel->informations->city_tax_percent==1? $reservationDetails->reservationData->hotel->informations->city_tax + '% ': JHotelUtil::fmt($reservationDetails->reservationData->hotel->informations->city_tax, 2);
	
		$emailTemplate = str_replace(EMAIL_COMPANY_LOGO, 								$companyLogo,						$emailTemplate);
		$emailTemplate = str_replace(EMAIL_SOCIAL_SHARING, 								"",									$emailTemplate);
	
		$gender = JText::_("LNG_EMAIL_GUEST_TYPE_".$reservationDetails->reservationData->userData->guest_type,true);
	
		$emailTemplate = str_replace(EMAIL_RESERVATIONGENDER, 								$gender,						$emailTemplate);

		$emailTemplate = str_replace(EMAIL_RESERVATIONFIRSTNAME, 							$reservationDetails->reservationData->userData->first_name,									$emailTemplate);
		$emailTemplate = str_replace(EMAIL_RESERVATIONLASTNAME, 							$reservationDetails->reservationData->userData->last_name,					$emailTemplate);
	
		$emailTemplate = str_replace(EMAIL_START_DATE, 										$datas,								$emailTemplate);
		$emailTemplate = str_replace(EMAIL_END_DATE,	 									$datae,								$emailTemplate);
		$emailTemplate = str_replace(EMAIL_CHECKIN_TIME, 									$chekInTime,						$emailTemplate);
		$emailTemplate = str_replace(EMAIL_CHECKOUT_TIME, 									$chekOutTime,						$emailTemplate);
	
		//ORIGINAL!!!!!!!!!!!!!!!!!!!!!!!
		//$emailTemplate = str_replace(EMAIL_RESERVATIONDETAILS,								$reservationDetails->reservationInfo, 	$emailTemplate);
		$emailTemplate = str_replace(EMAIL_RESERVATIONDETAILS,								$reservationDetails->reservationData->userData->confirmation_details, 	$emailTemplate);
		$emailTemplate = str_replace(EMAIL_BILINGINFORMATIONS,								$reservationDetails->billingInformation,$emailTemplate);
		$emailTemplate = str_replace(EMAIL_PAYMENT_METHOD,									$reservationDetails->paymentInformation,$emailTemplate);
		$emailTemplate = str_replace(EMAIL_GUEST_DETAILS,									"", 				$emailTemplate);
	
		$emailTemplate = str_replace(EMAIL_HOTEL_CANCELATION_POLICY, 						$cancellationPolicy,				$emailTemplate);
		$emailTemplate = str_replace(EMAIL_HOTEL_NAME, 										$hotelName,							$emailTemplate);
		$emailTemplate = str_replace(EMAIL_TOURIST_TAX, 									$touristTax,						$emailTemplate);
	
		$emailText = "";
		$emailTemplate = str_replace(EMAIL_BANK_TRANSFER_DETAILS,							$emailText, 						$emailTemplate);
		
		$emailTemplate = str_replace(EMAIL_RATING_URL,										$ratingURL, 						$emailTemplate);

		$fromName	= $reservationDetails->reservationData->appSettings->company_name;
		$emailTemplate = str_replace(EMAIL_COMPANY_NAME,									$fromName, 							$emailTemplate);



		/*Modificación del envío de la cotización de la reserva del hotel por E-Mail*/



/**
 * Código que muestra imagenes aleatorias
 *
 * Este código, busca las imagenes dentro de una carpeta especificada y muestra
 * una de ellas de forma aleatoria
 */

# indicamos la carpeta donde se encuentran las imagenes.
# NOTA;
#   Tiene que finalizar en /
#   Para buscar en la carpeta donde se encuentra el script, poner: $carpeta="";
$img_hotel = "/hotels/".$reservationDetails->reservationData->hotel->hotel_id."/";
$carpeta="/var/www/vhosts/tuagencia24.com/media/com_jhotelreservation/pictures".$img_hotel;

# definimos un array que contendra todas las imagenes de la carpeta
		if (is_dir($carpeta)){
			// Abre un gestor de directorios para la ruta indicada
			$gestor = opendir($carpeta);
			$contador=0;
			// Recorre todos los elementos del directorio
			while (($archivo = readdir($gestor)) !== false && ($contador!=6))  {
				
				$ruta_completa = $carpeta . "/" . $archivo;
 
				// Se muestran todos los archivos y carpetas excepto "." y ".."
				if ($archivo != "." && $archivo != "..") {
					// Si es un directorio se recorre recursivamente
					if (is_dir($ruta_completa)) {
						$imagen_muestra = "<img src=\"".JURI::root().PATH_PICTURES.$img_hotel.$archivo."\" alt=\"Muestra\" width=\"230px\" height=\"130px\"/>";
					} else {
						$imagen_muestra = "<img src=\"".JURI::root().PATH_PICTURES.$img_hotel.$archivo."\" alt=\"Muestra\" width=\"230px\" height=\"130px\"/>";
						$imagenes.=$imagen_muestra;
						$contador++;
					}
				}
			}
			// Cierra el gestor de directorios
			closedir($gestor);
		} 



		$emailTemplate = str_replace("[nombre_hotel]",			    	$reservationDetails->rooms[0]->hotel_name, 		         	$emailTemplate);
		
		$emailTemplate = str_replace("[imagen_muestra]",									$imagenes, 						$emailTemplate);

		$descripción = "<table style=\"max-width: 600px;\">".$reservationDetails->reservationData->hotel->hotelDescription."</table>";
		$emailTemplate = str_replace("[ofrecemos]",									$descripción, 						$emailTemplate);



//Contador para saber cuantos paquetes tiene el hotel seleccionado
$cont=count($extras = $reservationDetails->reservationData->extraOptions);
$carpeta="/extraoptions/"; //carpeta por defecto de las imágenes de los paquetes

for ($i=0; $i < $cont; $i++) { 
	if (isset($extras[$i])){

		$imagen_extra=$extras[$i]->image_path;
		$imagen_extra=$carpeta.$imagen_extra;
		$imagen_email = "<tr align=\"center\" ><td style=\"border-top-style: groove;\"><img src=\"".JURI::root().PATH_PICTURES.$imagen_extra."\" alt=\"Muestra\" max-width=\"110px\" height=\"110px\"/></td>";
		$extra="<strong>".$extras[$i]->name."</strong>";
		$extra.="<p>Bsf. ".$extras[$i]->price.", ";

		if ($extras[$i]->persons=="1"){
			$extra.="por persona";
		}

		if ($extras[$i]->is_per_day=="1") {
			$extra.=", por día";
		}
		$extra.="</p>";
		$extra.=$extras[$i]->description;

		$html_extra.=$imagen_email;
		$html_extra.="<td style=\"border-top-style: groove;\"><p>".$extra."</p></td></tr>";


	}

}

		if(isset($html_extra)){ //SE CARGAN LOS DATOS DE LOS PAQUETES DEL HOTEL EN LA ETIQUETA DE LA COTIZACIÓN

		$emailTemplate = str_replace("[extras]",			    						$html_extra, 				         	$emailTemplate);

		}elseif($html_extra==NULL) {  //EN CASO DE QUE EL HOTEL NO TENGA PAQUETES, SE ELIMINARÁ LA TABLA DE LA COTIZACIÓN	

		$emailTemplate = str_replace("<table style=\"width: 668px; height: 94px; margin-right: auto; margin-left: auto;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">", "", $emailTemplate);

		$emailTemplate = str_replace("<thead>
<tr><th style=\"border-style: solid solid none; border-color: #bebcb7; padding: 5px 9px 6px; line-height: 1em; width: 648px; background-color: #96c5e9;\">Tal vez le interese</th></tr>
</thead>",			    						"", 				         	$emailTemplate);

		$emailTemplate = str_replace("<tbody>
<tr>
<td style=\"border-style: solid; border-color: #bebcb7; padding: 7px 9px 9px; background-attachment: initial; background-color: #f8f7f5; background-position: initial; background-repeat: initial;\" valign=\"top\">
<p>Nuestros Paquetes Extras:</p>
<table style=\"height: 19px; width: 642px;\">
<tbody>",			    						"", 				         	$emailTemplate);

		$emailTemplate = str_replace("[extras]",			    						"", 				         	$emailTemplate);
		
	}
		
		session_start(); //Iniciamos Sesión PHP.
		if(isset($_SESSION["itinerario"])){

			$buff = str_replace("
			",			    						"\n", 				         	$_SESSION["itinerario"]); //Reemplasamos los saltos de línea del string por \n.

		$itinerario = nl2br ($buff); //Cada etiqueta \n será reemplazada por la etiqueta <br> de HTML.

		$emailTemplate = str_replace("[itinerarios]",			    						$itinerario, 				         	$emailTemplate); //Lo reemplazamos en el Tag del Email.

		}else{

			$emailTemplate = str_replace("<table style=\"width: 714px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
<thead>
<tr><th style=\"border-style: solid solid none; border-top-color: #bebcb7; border-right-color: #bebcb7; border-left-color: #bebcb7; padding: 5px 9px 6px; line-height: 1em; text-align: left;\" bgcolor=\"#d9e5ee\" width=\"48.5%\">Itinerario de Vuelos:</th></tr>
</thead>
<tbody>
<tr>
<td style=\"border-top-width: 0px; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; border-right-color: #bebcb7; border-bottom-color: #bebcb7; border-left-color: #bebcb7; padding: 7px 9px 9px; background: #f8f7f5;\" valign=\"top\"><span style=\"font-size: 8pt;\">[itinerarios]</span></td>
</tr>
</tbody>
</table>",			    						"", 				         	$emailTemplate);

		}

		



/*echo "<pre>";
//var_dump($reservationDetails->reservationData->userData->confirmation_details);
//var_dump($reservationDetails);
var_dump($emailTemplate);
echo "</pre>";

die;*/

	
		return $emailTemplate;
	}



	function prepareInvoiceEmail($reservationDetails, $emailTemplate)
	{
	
		$datas = JHotelUtil::getDateGeneralFormat($reservationDetails->reservationData->userData->start_date);
		$datae = JHotelUtil::getDateGeneralFormat($reservationDetails->reservationData->userData->end_date);
	
		$companyLogo = "<img src=\"".JURI::root().PATH_PICTURES.$reservationDetails->reservationData->appSettings->logo_path."\" alt=\"Company logo\" />";

		$currentDate = JHotelUtil::getDateGeneralFormat(date("Y-m-d H:i:s"));
		
		$chekInTime = $reservationDetails->reservationData->hotel->informations->check_in;
		$chekOutTime = $reservationDetails->reservationData->hotel->informations->check_out;
		$hotelName =$reservationDetails->reservationData->hotel->hotel_name;
		$cancellationPolicy =  $reservationDetails->reservationData->hotel->informations->cancellation_conditions;
		$touristTax = $reservationDetails->reservationData->hotel->informations->city_tax_percent==1? $reservationDetails->reservationData->hotel->informations->city_tax + '% ': JHotelUtil::fmt($reservationDetails->reservationData->hotel->informations->city_tax, 2);
	
		$emailTemplate = str_replace(EMAIL_COMPANY_LOGO, 								$companyLogo,						$emailTemplate);
	
		$gender = JText::_("LNG_EMAIL_GUEST_TYPE_".$reservationDetails->reservationData->userData->guest_type,true);
	
		$emailTemplate = str_replace(EMAIL_RESERVATIONGENDER, 								$gender,						$emailTemplate);

		$emailTemplate = str_replace(EMAIL_RESERVATIONFIRSTNAME, 							$reservationDetails->reservationData->userData->first_name,									$emailTemplate);
		$emailTemplate = str_replace(EMAIL_RESERVATIONLASTNAME, 							$reservationDetails->reservationData->userData->last_name,					$emailTemplate);
	
		$emailTemplate = str_replace(EMAIL_START_DATE, 										$datas,								$emailTemplate);
		$emailTemplate = str_replace(EMAIL_END_DATE,	 									$datae,								$emailTemplate);
		$emailTemplate = str_replace(EMAIL_CURRENT_DATE,	 								$currentDate,								$emailTemplate);
		$emailTemplate = str_replace(EMAIL_RESERVATION_ID, JHotelUtil::getStringIDConfirmation($reservationDetails->confirmation_id), $emailTemplate);
		
		
		$emailTemplate = str_replace(EMAIL_RESERVATIONDETAILS,								$reservationDetails->reservationInfo, 	$emailTemplate);
		$emailTemplate = str_replace(EMAIL_BILINGINFORMATIONS,								$reservationDetails->billingInformation,$emailTemplate);
		$emailTemplate = str_replace(EMAIL_PAYMENT_METHOD,									$reservationDetails->paymentInformation,$emailTemplate);
	
		$emailTemplate = str_replace(EMAIL_HOTEL_CANCELATION_POLICY, 						$cancellationPolicy,				$emailTemplate);
		$emailTemplate = str_replace(EMAIL_HOTEL_NAME, 										$hotelName,							$emailTemplate);
	
		$emailText = "";

		$fromName	= $reservationDetails->reservationData->appSettings->company_name;
		$emailTemplate = str_replace(EMAIL_COMPANY_NAME,									$fromName, 							$emailTemplate);
	
		return $emailTemplate;
		
	}
	
	public static function sendEmail($from, $fromName, $replyTo, $toEmail, $cc, $bcc, $subject, $content, $isHtml){
		jimport('joomla.mail.mail');
	
		$mail = new JMail();
		$mail->setSender(array($from, $fromName));
		if(isset($replyTo))
			$mail->addReplyTo($replyTo);
		$mail->addRecipient($toEmail);
		if(isset($cc))
			$mail->addCC($cc);
		if(isset($bcc))
			$mail->addBCC($bcc);
		$mail->setSubject($subject);
		$mail->setBody($content);
		$mail->IsHTML($isHtml);

		
		$ret = $mail->send();
		
		$log = Logger::getInstance();
		$log->LogDebug("E-mail with subject ".$subject." sent from ".$from." to ".$toEmail." ".serialize($bcc)." result:".$ret);
		
		return $ret;
	}
}

?>