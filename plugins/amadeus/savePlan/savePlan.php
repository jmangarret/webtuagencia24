<?php
defined( '_JEXEC' ) or die( 'Acceso Restringido' );

$mainframe = JFactory::getApplication();
$mainframe->registerEvent( 'insertPlan', 'insertPlan' );
$mainframe->registerEvent( 'getOfferSpecial2', 'getOfferSpecial2' );

/**
 * Retorna las ofertas marcadas como especial
 */
function getOfferSpecial2() {
	$db =& JFactory::getDBO();
	$query = "SELECT p.*, pf.file_url FROM
		#__cp_category c JOIN 
		(#__cp_products p JOIN
		#__cp_product_files pf ON pf.product_id = p.product_id)
		ON c.category_id=p.category_id 
		WHERE p.`featured` = 1 AND p.`published` = 1 ".getWhere()." GROUP BY p.product_id";

	$db->setQuery($query);
	$rows = $db->loadObjectList();
	return $rows;
}


/**
 * Inserta un Plan en el Administrador de ordenes
 */
function insertPlan($datos){
	$db =& JFactory::getDBO();
	$user =& JFactory::getUser();
	if($user->id != ''){
		$userId = $user->id;
	}
	else{
		$userId = 0;
	}
	
	$reloc = $datos['product_id'] . "-" . $userId;
	$consulta = "SELECT  *  FROM  #__aom_orders WHERE  email = '" . $datos['client_email'] . "'" . " AND extra = " . $datos['product_id'];
	$db->setQuery($consulta);
	$rows = $db->loadObjectList();
	
	if(count($rows) == 0){
	
	$query = "INSERT INTO #__aom_orders (`user_id`,                               `recloc`,         `product_type`,       `firstname`,      `lastname`,           `email`,                          `phone`,                         `total`,                           `fare`,                `taxes`, `fare_ta`, `taxes_ta`, `provider`, `status`, `fecsis`,            extra)
							 VALUES ('" . $userId . "', '" . $datos['product_id'] . "-" . $userId . "', 'plan', '" . $datos['client_name'] . "', '', '" . $datos['client_email'] . "', '" . $datos['client_phone'] . "', '" . $datos['product_price'] . "', '" . $datos['product_price'] . "',    '0',      '0',        '0',       '2C',       '1',    now(), '" . $datos['product_id'] . "' ) ";
	$db->setQuery($query);
	if (!$db->query()) {
		JError::raiseError(500, $db->getErrorMsg());
		return false;
	}
	$idOrden = $db->insertid();
	
	$query= "UPDATE  #__aom_orders  SET recloc = '" . $idOrden .  "-" . $datos['product_id']. "' WHERE id =" . $idOrden;
	$db->setQuery($query);
	if (!$db->query()) {
		JError::raiseError(500, $db->getErrorMsg());
		return false;
	}
	
	$queryHistory = "INSERT INTO `#__aom_history` (    `order_id`,         `user_id`,                 `note`,               `status`, `fecsis`) 
			                               VALUES ('" . $idOrden . "', '" . $userId . "',  'Se Crea Nueva Solicitud de Plan',  '1',     now() )";
	
	$db->setQuery($queryHistory);
	if (!$db->query()) {
		JError::raiseError(500, $db->getErrorMsg());
		return false;
	}
	
	$xml = "<Detalle_plan>
				<nombre_plan>" . $datos['product_name'] . "</nombre_plan>
				<pais_plan>" . $datos['product_country'] . "</pais_plan>
				<ciudad_plan>" . $datos['product_city'] . "</ciudad_plan>
				<descripcion_corta>" . $datos['product_desc'] . "</descripcion_corta>
				<nombre_solicitante>" . $datos['client_name'] . "</nombre_solicitante>
				<ciudad_solicitante>" . $datos['client_city'] . "</ciudad_solicitante>
				<telefono_solicitante>" . $datos['client_phone'] . "</telefono_solicitante>
				<fecha_salida>" . $datos['booking_date'] . "</fecha_salida>
				<correo_solicitante>" . $datos['client_email'] . "</correo_solicitante>
				<numero_adultos>" . $datos['total_adults'] . "</numero_adultos>
				<numero_ninos>" . $datos['total_children'] . "</numero_ninos>
				<observaciones>" . $datos['comments'] . "</observaciones>
			</Detalle_plan>";
			
	$queryDetalle = "INSERT INTO `#__aom_source` (`order_id`, `data`) VALUES ('" . $idOrden . "', '" . $xml . "')";
	
	$db->setQuery($queryDetalle);
	if (!$db->query()) {
		JError::raiseError(500, $db->getErrorMsg());
		return false;
	}
	
	}
	else{
		$app = JFactory::getApplication();
		$app->redirect('index.php', JText::_('Ya existe una solicitud abierta para este plan realizada por el mismo usuario'), 'error');
	}
	
	
	return true;
}

