<?php
/**
 * @file com_sales/admin/libsales/gdsData.php
 * @ingroup _plg_eretail
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

/**
 * desc
 */
class plgAmadeusOrderASOM extends JPlugin
{
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    public function onAfterBooking(&$response, &$user)
    {
      
    	
        // Directorio que contiene la libreria que consta de interfaces y clases para
        // integrarse con el AmadeuS Order Manager
        $library = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_asom'.DS.'library';
 
        // Se registra el directorio, para que dinamicamente cargue las clases necesarias.
        JLoader::registerPrefix('Asom', $library);
        JLoader::register('OrderDelegate', dirname(__FILE__).DS.'orderDelegate.php');
 
        // La clase `SaveOrderAsomHelper` debe implementar la interfaz de Air del
        // componente ASOM, para que pueda ser pasada al Helper y así automatizar el
        // proceso.
        $delegate = new OrderDelegate($response, $user);
 
	 
        // La clase AsomClassAir se encarga de coordinar los metodos declarados en
        // la clase delegate, para guardar la informacion en el AmadeuS Order Manager.
        //
        // En caso de ser necesario, puede sobreescribir esta clase para ajustarla a sus
        // necesidades
        
       
        $order = new AsomClassAir($delegate);
	 	 
        return $order->saveOrder();
    }



    public function onBeforePayment(&$paymentObject, $IDPaymentMethod)
    {
        // Directorio que contiene la libreria que consta de interfaces y clases para
        // integrarse con el AmadeuS Order Manager
        $library = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_asom'.DS.'library';

        // Se registra el directorio, para que dinamicamente cargue las clases necesarias.
        JLoader::registerPrefix('Asom', $library);

        switch($IDPaymentMethod)
        {
        case 'P2P':
            // Se obtiene la orden de acuerdo al localizador
            $order  = new AsomClassOrder(array('recloc' => $paymentObject->Reference));
            $config = JFactory::getConfig();

            $db = JFactory::getDBO();
            $componentParams = JComponentHelper::getParams('com_asom');
            $minutosLimite = $componentParams->get('timelimitpay');

            // Obteniendo los parametros de configuracion de P2P
            $params = JComponentHelper::getParams('com_p2p');
            $offset = JFactory::getDate('now', $config->getValue('config.offset'));
            $offset = $offset->getOffsetFromGMT(true);

            // Se valida que la orden este activa para el apgo.
            $query = $db->getQuery(true);
            $query->select('COUNT(1)')
                  ->from('#__aom_orders AS od')
                  ->join('inner', '#__aom_statuses AS st ON od.status = st.id')
                  ->where('od.id = '.$db->Quote($order->getOrder()->id));

            // Ademas de las ordenes activas, tambien se pueden pagar las rechazadas o fallidas, 
            // si no ha pasado mas de 30 minutos, esto con el fin de reintentar el pago. En caso que
            // halla pasado los 30 minutos o tiempo configurado, se debe activar manualmente desde
            // el backend
            $condition  = '(';
            $condition .=   'st.default_status = 1 AND ';
            $condition .=   '(';
            $condition .=     'DATE_ADD(od.fecsis, INTERVAL  0  HOUR) > (DATE_SUB(NOW(), INTERVAL ' . $minutosLimite . ' MINUTE)) OR '; //mams827 dejar esto configurable
            $condition .=     '(';
            $condition .=       'od.status = '.$db->Quote($params->get('st_rejected', '')).' OR ';
            $condition .=       'od.status = '.$db->Quote($params->get('st_failed', ''));
            $condition .=     ')';
            $condition .=   ')';
            $condition .= ')';

            $query->where($condition);

            $db->setQuery($query);

            if($db->loadResult() == 0)
            {
                JFactory::getApplication()->enqueueMessage(JText::_('AOM_P2P_TIME_EXCEEDED'), 'error');
                return false;
            }

            // De acuerdo a P2P, se debe validar que el usuario no tenga ordenes pendientes, para
            // poder pagar la orden actual
            $query = $db->getQuery(true);
            $query->select('id')
                  ->from('#__aom_orders')
                  ->where('status = '.$db->Quote($params->get('st_pendent', 1)))
                  ->where('user_id = '.$db->Quote($order->getOrder()->user_id));

            $db->setQuery($query);

            if($id = $db->loadResult())
            {
                $config = JFactory::getConfig();
                $app    = JFactory::getApplication();
                $code   = sha1($id.$config->getValue('config.secret'));
                $link   = JRoute::_('index.php?option=com_asom&task=orders.resume&order='.$id.'&pendent=1', false);

                $app->setUserState('asom.code', $code);
                $app->redirect($link);
                // Send error
                return false;
            }

            // Se actualiza la orden, en estado pendiente
            $data = array(
                'status' => $params->get('st_pendent', 1)
            );

            if(!$order->updateOrder($data, JText::_('AOM_REDIRECT_P2P'), -1))
            {
                // Send error
                return false;
            }

            // Se crea el objeto para que redireccione a P2P
            $payment = new PaymentP2P($paymentObject);
            return $payment->redirect();

            break;
        }

        return true;
    }

    public function onAfterPayment(&$PaymentResponse, $IDPaymentMethod)
    {
        // Registrando la clase de Place To Pay para la encriptacion
        JLoader::register('PlacetoPay', JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_p2p'.DS.'library'.DS.'EGM'.DS.'PlacetoPay.php');

        switch($IDPaymentMethod)
        {
        case 'P2P':
            $params = JComponentHelper::getParams('com_p2p');

            if(!$PaymentResponse instanceof PlacetoPay)
            {
                $p2p = new PlacetoPay();
                $p2p->setGPGProgramPath($params->get('gpg_path', '/usr/bin/gpg'));
                $p2p->setGPGHomeDirectory($params->get('gpg_dir', ''));
		
                $KeyID          = $params->get('cust_keyid', '');
                $Passprhase     = $params->get('passprhase', '');
                $RecipientKeyID = $params->get('p2p_keyid', '');

                $rc = $p2p->getPaymentResponse($RecipientKeyID, $Passprhase, $PaymentResponse);
            }
            else
            {
                $p2p = $PaymentResponse;
                $rc  = $p2p->_rc;
            }

            $recloc = $p2p->getReference();

            //if(!$model->savePayment($order->order->id, $PaymentResponse, $p2p->getAuthorization()))
                //throw new Exception($model->getError());

            $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

            $status = '';
            switch($rc)
            {
            case PlacetoPay::P2P_ERROR:
                $data   = array('status' => $params->get('st_failed', ''));
                $status = JText::_('AOM_P2P_ERROR');
                $note   = $p2p->getErrorCode().' - '.$p2p->getErrorMessage().' '.($ip != '' ? '/ IP '.$ip : '');
				break;
            case PlacetoPay::P2P_DECLINED:
                $data   = array('status' => $params->get('st_rejected', ''));
                $status = JText::_('AOM_P2P_DECLINED');
                $note   = $p2p->getErrorCode().' - '.$p2p->getErrorMessage().' '.($ip != '' ? '/ IP '.$ip : '');
				break;
            case PlacetoPay::P2P_APPROVED:
            case PlacetoPay::P2P_DUPLICATE:
			case PlacetoPay::P2P_PENDING:
                if(PlacetoPay::P2P_PENDING == $rc)
                {
                    $data   = array('status' => $params->get('st_pendent', ''));
                    $status = JText::_('AOM_P2P_PENDING');

                    // Si se ejecuta mediante el cron, no guarda ordenes pendinetes
                    if(php_sapi_name() == 'cli')
                    {
                        return true;
                    }
                }
                else
                {
                    $data   = array('status' => $params->get('st_paid', ''));
                    $status = JText::_('AOM_P2P_APPROVED');
                }

                if($p2p->getCreditCardNumber() == '')
                {
                    $note = $p2p->getBankName().' - '.$p2p->getReceipt();
                }
                else
                {
                    $note = $p2p->getFranchise().'-'.$p2p->getFranchiseName().' / Auth. '.$p2p->getAuthorization().' / CCard. ***'.substr($p2p->getCreditCardNumber(), -4);
                }

                $note .= ' / '.$p2p->getShopperName().'<'.$p2p->getShopperEmail().'> '.($ip != '' ? '/ IP '.$ip : '');

				break;
            }

            // Directorio que contiene la libreria que consta de interfaces y clases para
            // integrarse con el AmadeuS Order Manager
            $library = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_asom'.DS.'library';

            // Se registra el directorio, para que dinamicamente cargue las clases necesarias.
            JLoader::registerPrefix('Asom', $library);

            $order = new AsomClassOrder(array('recloc' => $recloc));

            // Se envia la informacion del pago, para que se guarde
            $extra = $p2p->getExtraData();
            $extra = explode('|', $extra);
            $extra = count($extra) == 2 ? $extra : array(0, 0);
            $paymentInfo = array(
                'id'               => 0,
                'company'          => $params->get('name', ''),
                'nit'              => $params->get('nit', ''),
                'reason'           => $p2p->getErrorCode().' - '.$p2p->getErrorMessage(),
                'fare'             => $p2p->getTotalAmount(),
                'airportfare'      => (float) $extra[1],
                'fee'              => (float) $extra[0],
                'currency'         => $p2p->getCurrency(),
                'franchise'        => $p2p->getFranchise(),
                'franchiseName'    => $p2p->getFranchiseName(),
                'creditCardNumber' => substr($p2p->getCreditCardNumber(), -4),
                'bank'             => $p2p->getBankName(),
                'cus'              => $p2p->getAuthorization(),
                'receipt'          => $p2p->getReceipt(),
                'reference'        => $p2p->getReference(),
                'description'      => $params->get('name', '').' - '.$params->get('website', '').' - '.$p2p->getReference(),
                'ip'               => $ip,
                'customer'         => $p2p->getShopperName(),
                'customerEmail'    => $p2p->getShopperEmail(),
                'status'           => $status,
                'statusCode'       => $rc,
                'fectrans'         => $p2p->getTransactionDate()
            );

            $order->savePayment($paymentInfo);
            if(!$order->updateOrder($data, $note, -1))
            {
                return false;
            }
			
            
                    
            $issue = $this->params->get('issue', 0);
            if($issue && (PlacetoPay::P2P_APPROVED || PlacetoPay::P2P_DUPLICATE)) // Se debe emitir el tiquete
            {
                $ok    = $this->params->get('st_succesful', 1);
                $error = $this->params->get('st_error', 1);

                $issue = new AsomClassIssue();

                if($p2p->getCreditCardNumber() == '')
                {
                    $issue->setPaymentMethod('CA', 0);
                }
                else
                {
                    $cards = array(
                        'CR_VS' => 'VI',
                        'CR_AM' => 'AX',
                        'RM_MC' => 'CA'
                    );

                    $issue->setPaymentMethod('CC', 0, array(	'card'     => $p2p->getCreditCardNumber(),
																'code'     => $cards[$p2p->getFranchise()],
																'approval' => $p2p->getAuthorization()));
                }

                if(!$issue->issue($recloc, array(	'card'     => $p2p->getCreditCardNumber(),
													'code'     => $cards[$p2p->getFranchise()],
													'approval' => $p2p->getAuthorization())))
                {
                    $data = array(
                        'status' => $error
                    );

                    $note = JText::_('AOM_ISSUE_ERROR').$issue->getMessage();
                }
                else
                {
                    $data = array(
                        'status' => $ok
                    );

                    $tickets = $issue->getTickets();
                    $note = JText::_('AOM_ISSUE_OK').$tickets;
                }

                if(!$order->updateOrder($data, $note, -1))
                {
                    return false;
                }
            }

            // Si se ejecuta mediante el cron, no redirecciona a ningun lado
            if(php_sapi_name() == 'cli')
            {
                return true;
            }

            $config = JFactory::getConfig();
            $app    = JFactory::getApplication();
            $id     = $order->getOrder()->id;
            $code   = sha1($id.$config->getValue('config.secret'));
            $link   = JRoute::_('index.php?option=com_asom&task=orders.resume&order='.$id, false);

            if($app->setUserState('asom.code', $code) == null)
            {
                // Fallback si no se puede guardar el dato en el registro
                $_SESSION['asom'] = array('code' => $code);
            }
            $app->redirect($link);

            break;
        }

        return true;
    }

}

class PaymentP2P
{

    private $_paymentObject = null;

    private $_params = null;

    public function __construct($payment)
    {
        $this->_paymentObject = $payment;
    }

    public function redirect()
    {
        if(!$this->_setParams())
        {
            //Send error
            return false;
        }

        $url = $this->_getP2PURL();

        if(!$url)
        {
            // send error
            return false;
        }

        // Se redirecciona y se termina el flujo
        $app = JFactory::getApplication();
        $app->redirect($url);

        return true;
    }

    private function _setParams()
    {
        $params = JComponentHelper::getParams('com_p2p');
        if($params->get('passprhase', '') == '')
        {
            // Error, componente no existe o no esta configurado
            return false;
        }

        $this->_params = $params;

        return true;
    }

    private function _getP2PURL()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Validar orden pendiente mams827
        // validar que la orden este activa para pagar mams827

        // Se obtiene el codigo de la tarjeta que requiere P2P,
        // de acuerdo si los vuelos son nacionales o internacionales
        $national = $this->_paymentObject->isNational ? 1 : 0;

        $query->select('code');
        $query->from('#__p2p_airlines');
        $query->where('iata = '.$db->Quote($this->_paymentObject->AirlineCode));
        $query->where('national = '.$db->Quote($national));
        $query->order('code ASC');

        $db->setQuery($query);

        // Se coloca el codigo en el campo de la Aerolinea, para
        // continuar con el proceso
        $this->_paymentObject->AirlineCode = (int) $db->loadResult();

        // Se organizan los valores
        $Reference = $this->_paymentObject->Reference;
        $ServiceFee = '';
        $AirportTax = '';

        // De acuerdo al codigo obtenido, se valida si hay dispersion
        // o no de fondos (habrá dispersión siempre y cuando el codigo
        // exista o sea mayor a cero)
        if($this->_paymentObject->AirlineCode > 0)
        {
            $TotalAmount          = $this->_paymentObject->TotalAmount;
            $TaxAmount            = $this->_paymentObject->TaxAmount;
            $DevolutionBaseAmount = $this->_paymentObject->DevolutionBaseAmount;
            $ServiceFee           = $this->_paymentObject->ServiceFee;
            $ServiceFeeTax        = $this->_paymentObject->ServiceFeeTax;
            $ServiceFeeDevolution = $this->_paymentObject->ServiceFeeTax;
            $ServiceFeeCode       = '99'; //mams827  P2P says it's mandatory for servicefee
            $AirlineCode          = $this->_paymentObject->AirlineCode;
            $AirportTax           = $this->_paymentObject->AirportTax;
        }
        else
        {
            $TotalAmount          = $this->_paymentObject->TotalAmount
                                    + $this->_paymentObject->ServiceFee
                                    + $this->_paymentObject->AirportTax;
            $TaxAmount            = $this->_paymentObject->TaxAmount
                                    + $this->_paymentObject->ServiceFeeTax;
            $DevolutionBaseAmount = $TaxAmount > 0 ? $TotalAmount - $TaxAmount : 0;
        }

        // Registrando la clase de Place To Pay para la encriptacion
        JLoader::register('PlacetoPay', JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_p2p'.DS.'library'.DS.'EGM'.DS.'PlacetoPay.php');

        // Obteniendo el objeto de P2P, para enctriptar los datos y
        // generar el enlace
        $p2p = new PlacetoPay();
        $p2p->setGPGProgramPath($this->_params->get('gpg_path', '/usr/bin/gpg'));
        $p2p->setGPGHomeDirectory($this->_params->get('gpg_dir', ''));

        $p2p->setCurrency('COP');
        $p2p->setLanguage('ES');

        // Enviando la informacion de la TA
        if($ServiceFee != '')
        {
            $p2p->setExtraData($ServiceFee.'|'.$AirportTax);
            $p2p->setServiceFee($ServiceFee, $ServiceFeeTax, $ServiceFeeDevolution, $ServiceFeeCode);
            $p2p->setAirlineCode($AirlineCode);
            $p2p->setAirportTax($AirportTax);
        }
        else
            $p2p->setExtraData('');


        /**
         * Informacion del Pagador
         */
        if(!isset($_POST['wsform']))
        {
            $user = JFactory::getUser();

            $_POST['wsform']['contactemail']        = $user->get('email', '');
            $_POST['wsform']['contactname']         = $user->get('name', '');
            $_POST['wsform']['contactdocumenttype'] = $user->get('profile.documenttype', '');
            $_POST['wsform']['contactdocumentid']   = $user->get('profile.documentnumber', '');
        }

        $ShopperIDType = $_POST['wsform']['contactdocumenttype'];
        $ShopperID     = $_POST['wsform']['contactdocumentid'];
        $ShopperName   = utf8_decode($_POST['wsform']['contactname']);
        $ShopperEmail  = utf8_decode($_POST['wsform']['contactemail']);

        $p2p->setPayerInfo($ShopperIDType, $ShopperID, $ShopperName, $ShopperEmail, '', '', '', '', '', '');

        /**
         * Se obtiene los datos de la llave para poder encriptar la informacion
         */
        $CustomerSiteID = $this->_params->get('site_code', '');
        $KeyID          = $this->_params->get('cust_keyid', '');
        $Passprhase     = $this->_params->get('passprhase', '');
        $RecipientKeyID = $this->_params->get('p2p_keyid', '');
 
        $p2p->setOverrideReturn($this->_params->get('url_return', ''));

        // Se obtiene la url a usar para redireccionar a P2P
        $paymentRequest = $p2p->getPaymentRedirect($KeyID, $Passprhase, $RecipientKeyID, $CustomerSiteID,
                                                   $Reference, $TotalAmount, $TaxAmount, $DevolutionBaseAmount);

        if(!$paymentRequest)
        {
            // send error
            return false;
        }

        return $paymentRequest;
    }

}
