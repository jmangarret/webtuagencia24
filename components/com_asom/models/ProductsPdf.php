<?php

//Importacion básica de JModel
jimport('joomla.application.component.model');

require_once('components' . DS . 'com_asom' . DS . 'lib' . DS . 'html2pdf' . DS . 'html2pdf.class.php');
 
class AsomModelProductsPdf extends JModel {

    /**
     *  Recieve the html and generate the pdf
     * @param string $html
     */
    function setOrderPdf($html, $fileName) {
        ob_end_clean();
        $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8',  array(mL, mT, mR, mB));
        //$html2pdf->addFont("times");
        $html2pdf->WriteHTML($html);
        $html2pdf->Output($fileName . '.pdf');
        die();
    }

  

    function getHtmlAir($XMLData,$Order,$XMLAdd,$template, $productTypeName){
    	 
    	//Set header mail text
        $confirmationText = JText::_('ORDER.MAIL.AIR.HEADER');
        //Instanciamos el plugin para el formato de precio
        $dispatcher = & JDispatcher::getInstance();

      
        //Importo los archivos de lenguaje del componente
        $language = & JFactory::getLanguage();
        $language->load('com_orders', JPATH_SITE);

       //Informacion Adicional 
		$xmlstr=$XMLAdd;
		$data = new SimpleXMLElement($xmlstr);
		$passenger=$data->AdditionalInfo->Travelers;
		 
		
		//Informacion del Booking
		 $xml = new SimpleXMLElement($XMLData);
		 $intinerary= $xml->AirReservation->OriginDestinationOptions->OriginDestinationOption->FlightSegment;
		 $states = 	  Array(1 => JText::_('ORDER.STATUS.1'),
					2=>  JText::_('ORDER.STATUS.2'),
					3 => JText::_('ORDER.STATUS.3'),
					4 => JText::_('ORDER.STATUS.4'),
					5 => JText::_('ORDER.STATUS.5'),
					6 => JText::_('ORDER.STATUS.6')

			);       
		foreach($intinerary as $vuelo):
		 $departure=str_replace("T", " ",$vuelo['DepartureDateTime']);
		 $arrival=str_replace("T", " ",$vuelo['ArrivalDateTime']);
		 $airportDep=explode(':',$vuelo->Comment[0]);
		 $cityDep=explode(':',$vuelo->Comment[2]);
		 $airportArr=explode(':',$vuelo->Comment[7]);
		 $cityArr=explode(':',$vuelo->Comment[9]);
		 $infoDep= $airportDep[1]." - ".$cityDep[1];
		 $infoArr= $airportArr[1]." - ".$cityArr[1];
		$intinerario="<tr>"; 
		$intinerario .="<td>".$departure."</td>";
		$intinerario.="<td>".$arrival."</td>";
		$intinerario.="<td>".$infoDep."</td>";
		$intinerario.="<td>".$infoArr."</td></tr>";
		$arrayItinerary[] = $intinerario;
		endforeach;
    
        for ($i = 0; $i < count($arrayItinerary); $i++) {
            
            $itinerary .= $arrayItinerary[$i];
             
        }
        
        $j=1;
        foreach ($passenger->TravelerInfo as $pax):
        $paxinfo.="<tr style='background-color: #213E7A ;'>
				<td style='color: #FFF; padding: 5px; font-weight: bold; width:700px'
					colspan='8'>".JText::_('ORDERS.ORDER.PAX')." (".$j.")</td>
			</tr>";  
		$paxinfo.="<tr>
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>".
					 JText::_('ORDERS.ORDER.PAX.NAME')."</td>
					 <td>".$pax->GivenName."</td>
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.LASTNAME')."</td>
					 <td>".$pax->Surname."</td>
			</tr>
			<tr>		
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.TYPE')."</td>
					 <td>".JText::_('ORDERS.ORDER.PAX.'.$pax->PassengerType)."</td>";
			if($pax->PassengerType=='ADT' || $pax->PassengerType=='YCD' ):	 
			$paxinfo.=	"<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.DOCUMENT.TYPE')."</td>
					 <td>".JText::_('ORDERS.ORDER.PAX.DOC.TYPE.'.$pax->DocumentTypeId)."</td>
			</tr>";
			endif;
			if($pax->PassengerType=='CHD' || $pax->PassengerType=='INF'):
			$paxinfo.=	"<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.DOCUMENT.TYPE')."</td>
					 <td>".JText::_('ORDERS.ORDER.PAX.DOC.TYPEDIF.'.$pax->DocumentTypeId)."</td>
			</tr>";
			endif;
			$paxinfo.="
			<tr>		
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					".JText::_('ORDERS.ORDER.PAX.DOCUMENT.NUMBER')."</td>
					 <td>".$pax->DocumentNumber."</td>
					 ";
			
			
			$paxinfo.="
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					".JText::_('ORDERS.ORDER.PAX.BORN')."</td>
					 <td>".$pax->BithDate."</td>
			</tr>
			<tr>
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.GENDER')."</td>
					 <td>".JText::_('ORDERS.ORDER.PAX.GENDER.'.$pax->Gender)."</td>";
			if($pax->PassengerType=='ADT'):
			$paxinfo.="
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.EMAIL')."</td>
					 <td>".$pax->Email."</td>";
			endif;
			$paxinfo.="
			</tr>
		 	<tr>
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					".JText::_('ORDERS.ORDER.PAX.VFREQ.NUMBER')."</td>
					 <td>".$pax->FrequentFlyerNumber."</td>
				<td
					style='color: #4A4A4A; font-weight: bold; padding: 2px 2px 2px 4px;'>
					". JText::_('ORDERS.ORDER.PAX.VFREQ.AIRLINE')."</td>
					 <td>".$pax->FrequentFlyerAirline."</td>
			</tr>";
					 $arrayPaxes[] =$paxinfo;
					 
					 $j++;
        endforeach; 
        
         $passengers = $arrayPaxes[count($arrayPaxes)-1];
        
        
        $arrayIndexBody = array(
	        "{order.number}",
	        "{order.state}",
	        "{order.record}",
        	"{order.price}",
	        "{order.taxes}",
	        "{order.fees}",
	        "{order.total}",
	        "{order.intinerary}",
	        "{order.passengers}" 
        );
 
        $arrayValuesBody = array(
        	$Order->id,
        	$states[$Order->status],
        	$Order->recloc,
        	number_format($Order->fare,2, ',', '.'),
        	number_format($Order->taxes,2, ',', '.'),
        	number_format($Order->fare_ta+$Order->taxes_ta ,2, ',', '.'),
        	number_format($Order->total,2, ',', '.'),
        	$itinerary,
        	$passengers
        );

        $template = str_replace($arrayIndexBody, $arrayValuesBody, $template);
 
        return $template;
    }

}
