<?php
/**
 * com_cp default controller
 * 
 * @package    Cp
 * @subpackage com_cp
 *
 *
 */
defined('_JEXEC') or die;

/**
 * cp Component Controller
 *
 * @package	Cp
 */
class CPController extends JControllerLegacy {
	protected $default_view = 'cpproductslist';

    /**
     * Returns a select of cities with plans
     * @return cities 
     */
    function selectListPlanCitiesByCountry() {
		global $mainframe;

		$cities = array();
        $cities[] = JHTML::_('select.option', "", JText::_("COM_CP_SELECT"));
        $cities[] = JHTML::_('select.option', "0", JText::_("COM_CP_ALL_CITIES"));

        $country = JRequest::getVar('country_code', '');
        if ($country) {
	        //se instancia y ejecuta plugin
	        JPluginHelper::importPlugin('amadeus', 'plansEngine');
	        $dispatcher = & JDispatcher::getInstance();
	        $resultsFind = $dispatcher->trigger('getPlanCitiesByCountry', array($country, true));
	        foreach ($resultsFind[0] as $result) {
	            $cities[] = JHTML::_('select.option', $result->airportcode, $result->city);
	        }
        }
        echo JHTML::_('select.genericlist', $cities, "city", 'class="campo_lista_planes"');
    }

    /**
     * Saves request in component History Order and sends email to admin and client with response.
     * @return messages 
     */
    function saveOrder() {
    	// Se obtiene la información ingresada por el cliente
		$country = JRequest::getVar('country_code', '');
		$product_id = JRequest::getVar('id');
		$client_name = JRequest::getVar('client_name');
		$client_city = JRequest::getVar('client_city');
		$client_phone = JRequest::getVar('client_phone');
		$client_email = filter_var(JRequest::getVar('client_email'), FILTER_VALIDATE_EMAIL);
		$total_adults = JRequest::getInt('total_adults');
		$total_children = JRequest::getInt('total_children');
		$booking_date = JRequest::getVar('booking_date');

		// Se valida la información requerida
		if (
			!$product_id ||
			!$client_name ||
			!$client_city ||
			!$client_phone ||
			!$client_email ||
			(($total_adults + $total_children) < 1) ||
			!strtotime($booking_date)
		) {
			$app = JFactory::getApplication();
			$app->redirect(JRoute::_('index.php'), JText::_('COM_CP_WRONG_INFORMATION'), 'error');
		}

		$config = JComponentHelper::getParams('com_cp');
		$model = $this->getModel('cpproducts');
		$product_info = $model->getItem($product_id);
		// Definir la imagen del producto.
		// Si no tiene, usar la que esté configurada como No Disponible
		if (empty($product_info->media)) {
			$product_image = JURI::base() . $config->get('cfg_no_image_url');
		} else {
			$product_image = JURI::base() . $product_info->media[0]->file_url;
		}
		// Codificación del nombre de la imagen
		$product_image = dirname($product_image) . '/' . rawurlencode(basename($product_image));
		// Se obtiene la información de las pestañas del producto
		$tabs = '';
		for ($i = 1; $i < 7; $i++) {
			$tag_name = 'tag_name' . $i;
			$tag_content = 'tag_content' . $i;
			if ($product_info->$tag_name && strlen($product_info->$tag_content) > 20) {
				$tabs .= '<b>' . $product_info->$tag_name . ':</b><p>'. $product_info->$tag_content . '</p><br />';
			}
		}

		// Se agrega la miga de pan
		$app = JFactory::getApplication();
		$pathway = $app->getPathway();
		$pathway->addItem($product_info->product_name, JRoute::_( "index.php?option=com_cp&view=cpproducts&id=" . $product_id ));
		$pathway->addItem(JText::_('COM_CP_ORDERS.ORDER.REQUEST.CONFIRMATION.LABEL'), '');
		// Se organiza la información a enviar al plugin
		$datos = array(
			"quantity" => "1",  // Cantidad del producto
			"product_id" => $product_id, // ID del producto
			"product_type_name" => JText::_('COM_CP_BOOKING_PRODUCT_NAME'), // Nombre del tipo de producto
			"product_name" => $product_info->product_name, // Nombre del producto
			"product_image" => $product_image,  // Imagen principal del producto
			"product_desc" => $product_info->product_desc, // Descripción corta del producto
			"product_extra_desc" => $tabs, // Contenido pestañas del producto
			"product_price" => $product_info->price, // Precio
			"product_city" => $product_info->city_name, // Ciudad
			"product_country" => $product_info->country_name, // País
			"client_name" => $client_name, // Nombre del cliente
			"client_city" => $client_city, // Ciudad de residencia del cliente
			"client_phone" => $client_phone, // Teléfono del cliente
			"client_email" => $client_email, // Email del cliente
			"total_adults" => $total_adults, // Número de adultos
			"total_children" => $total_children, // Número de niños
			"booking_date" => $booking_date, // Fecha de salida
			"comments" => JRequest::getVar('comments'), // Observaciones del cliente
			"option" => "com_cp", // Componente
			"currency" => $config->get('cfg_currency') // Moneda
		);

		// Se llama al plugin para que maneje la información
		JPluginHelper::importPlugin('amadeus');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('insertPlan', $datos);
		// return true;

		$this->_sendMailAdmin($datos);
		$this->_sendMailUser($datos);
		header('Location: '.JRoute::_( "index.php?option=com_cp&view=cpproducts&id=" . $product_id."#mailsuccess" ));
    }

    private function _sendMailUser($data)
    {
    	$to = array();
    	$to['email'] = $data['client_email'];

    	$email = '<p><b>'.$data['client_name'].'</b>, usted ha registrado el plan de <b>'.$data['product_name'].'</b>.</p><p>'.JText::_('COM_CP_CUSTOM_USER_MESSAGE').'</p><p><img src="'.JURI::base().'/images/foot-email.png"></p>';

    	$this->_sendMail($to,$email);
    }

    private function _sendMailAdmin($data)
    {
    	$to = array();
        $config = JFactory::getConfig();
        $to['email']   = $config->get('mailfrom');

        $email = '<p><b>'.$data['client_name'].'</b>, ha registrado el plan <b>'.$data['product_name'].'</b></p>';
        $email .= '<h3>Datos del cliente</h3>';
        $email .= '<b>Nombre:</b> '.$data['client_name'].'<br>';
        $email .= '<b>Teléfono:</b> '.$data['client_phone'].'<br>';
        $email .= '<b>Email:</b> '.$data['client_email'].'<br>';
        $email .= '<b>Ciudad:</b> '.$data['client_city'].'<br>';

        $email .= '<h3>Datos del plan</h3>';
        $email .= '<b>Nombre del plan:</b> '.$data['product_name'].'<br>';
        $email .= '<b>Ciudad:</b> '.$data['product_city'].'<br>';
        $email .= '<b>País:</b> '.$data['product_country'].'<br>';
        $email .= '<b>Fecha de salida:</b> '.$data['booking_date'].'<br>';
        $email .= '<b>Adultos:</b> '.$data['total_adults'].'<br>';
        $email .= '<b>Niños:</b> '.$data['total_children'].'<br>';
        $email .= '<b>Comentarios:</b> '.$data['comments'].'<br>';

        $this->_sendMail($to,$email);
    }

    private function _sendMail($to,$email)
    {
    	$config = JFactory::getConfig();
        $from['name']   = $config->get('fromname');
        $from['email']   = $config->get('mailfrom');

        $subject = 'Registro de plan exitoso!';

        $mailer =& JFactory::getMailer();
        $mailer->setSender($from);
        $mailer->addRecipient($to['email']);
        $mailer->setSubject($subject);
        $mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($email);
        $mailer->Send();
    }
}
