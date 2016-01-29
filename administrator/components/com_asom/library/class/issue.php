<?php
/**
 *
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Clase encargada de retornar la informacion de la orden
 */
class AsomClassIssue extends JObject
{

    private $message     = '';

    private $serviceUrl  = null;

    private $DOMIssue    = null;
 
    private $DOMPayments = null;

    private $tickets     = '';


    public function __construct()
    {
        $componentParams = JComponentHelper::getParams('com_asom');

        if($componentParams->get('issue_service', '') == '')
        {
            throw new Exception('COM_ASOM_ISSUE_URL_ERROR');
        }

        $this->serviceUrl = $componentParams->get('issue_service');

        $this->DOMIssue = new DOMDocument('1.0');
    }

    /**
     * Seleccione:
     *
     * CC => Tarjeta de credito
     *   Si es tarjeta de credito, envie extra parametros,
     *   con la informacion de la tarjeta:
     *   array(
     *     'card'     => '' //Numero de la tarjeta
     *     'code'     => '' // Codigo de proveedor de la tarjeta de credito VI, AX, CA, DC
     *     'approval' => '' // Numero de aprovacion
     *   )
     * CA => Efectivo
     */
    public function setPaymentMethod($paymentMethod, $value, $extraInfo = array())
    {
        if($this->DOMPayments == null)
        {
            $this->DOMPayments = $this->DOMIssue->createElement('payments');
        }

        if(!in_array($paymentMethod, array('CC', 'CA')))
        {
            throw new Exception('COM_ASOM_PAYMENT_METHOD_ERROR');
        }

        $payment = $this->DOMIssue->createElement('payment');

        if($paymentMethod == 'CC')
        {
            if(!isset($extraInfo['card']) || !isset($extraInfo['code']) || !isset($extraInfo['approval']))
            {
                throw new Exception('COM_ASOM_CARD_INFO_ERROR');
            }

            if(!in_array($extraInfo['code'], array('VI', 'AX', 'CA', 'DC')))
            {
                throw new Exception('COM_ASOM_CARD_CODE_ERROR');
            }

            $expiryDate = date('m').date('y', strtotime(date("Y-m-d", mktime()) . " + 365 day"));
            $accountnumber = $this->encodeCard($extraInfo['card']);

            $payment->appendChild($this->DOMIssue->createElement('identification', 'CC'));
            $payment->appendChild($this->DOMIssue->createElement('creditcardcode', $extraInfo['code']));
            $payment->appendChild($this->DOMIssue->createElement('expirydate', $expiryDate));
            $payment->appendChild($this->DOMIssue->createElement('accountnumber', $accountnumber));
            $payment->appendChild($this->DOMIssue->createElement('approvalcode', $extraInfo['approval']));
        }
        else
        {
            $payment->appendChild($this->DOMIssue->createElement('identification', 'CA'));
            $payment->appendChild($this->DOMIssue->createElement('creditcardcode', ''));
            $payment->appendChild($this->DOMIssue->createElement('expirydate', ''));
            $payment->appendChild($this->DOMIssue->createElement('accountnumber', ''));
            $payment->appendChild($this->DOMIssue->createElement('approvalcode', ''));
        }

        //$payment->appendChild($this->DOMIssue->createElement('currencycode', ''));
        if((float)$value > 0)
        {
            $payment->appendChild($this->DOMIssue->createElement('amount', (float)$value));
        }

        $this->DOMPayments->appendChild($payment);
    }

    public function issue($pnr)
    {
        if(strlen($pnr) != 6)
        {
            throw new Exception('COM_ASOM_PNR_ERROR');
        }

        if($this->DOMPayments == null)
        {
            throw new Exception('COM_ASOM_PAYMENT_INFO_ERROR');
        }

        return $this->request($pnr, 'ISSUE');
    }

    private function request($pnr, $command)
    {
        $data = $this->DOMIssue->createElement('data');
        $data->appendChild($this->DOMIssue->createElement('serviceaction', $command));

        $servicedata = $this->DOMIssue->createElement('servicedata');
        $servicedata->appendChild($this->DOMIssue->createElement('booking', $pnr));
        //$servicedata->appendChild($this->DOMIssue->createElement('commission', ''));

        $servicedata->appendChild($this->DOMPayments);
        $data->appendChild($servicedata);
        $this->DOMIssue->appendChild($data);

        $this->DOMIssue->formatOutput = true;
        $xml = substr($this->DOMIssue->saveXML(), 22);

        /**
         * Se hace la peticion al servicio de emision
         */
        $ws = new SoapClient($this->serviceUrl, array('encoding' => 'UTF-8'));

        $request        = new stdClass();
        $request->value = $xml;
        $result         = $ws->GetSendData($request);
        $result         = $result->GetSendDataResult;
        /**
         * Se termina la peticion y se procesa la respuesta
         */

        $doc = new DOMDocument();
        $doc->loadXML($result);
        foreach($doc->getElementsByTagName('successful') as $data)
        {
            $status = strtolower($data->nodeValue) != 'false';
        }

        if(!$status)
        {
            foreach($doc->getElementsByTagName('errormessage') as $data)
            {
                $this->message = $data->nodeValue;
            }
        }
        else
        {
            $tickets = '<pre>';
            foreach($doc->getElementsByTagName('passenger') as $data)
            {
                $info = explode('/', $data->getElementsByTagName('ticketnumber')->item(0)->nodeValue);
                $tickets .= $info[0].' / '.$info[2].' / ';
                $tickets .= $data->getElementsByTagName('type')->item(0)->nodeValue.' / ';
                $tickets .= $data->getElementsByTagName('surname')->item(0)->nodeValue.' ';
                $tickets .= $data->getElementsByTagName('firstname')->item(0)->nodeValue;
                $tickets .= "\n";
            }
            $tickets .= '</pre>';

            $this->tickets = $tickets;
        }

        return $status;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getTickets()
    {
        return $this->tickets;
    }

    private function encodeCard($card)
    {
        // Revisar como ocultar esto...... mams827
        $key = '12EstaClave34es56dificil489ssswf';
        $iv  = 'Devjoker7.37hAES';

        return $this->_encrypt($card, $key, $iv);
    }

    private function _encrypt($data, $key, $iv)
    {
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $padding = $block - (strlen($data) % $block);
        $data .= str_repeat(chr($padding), $padding);

        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv));
        return $encrypted;
    }

}
