<?php
defined( '_JEXEC' ) or die( 'Acceso Restringido' );

$mainframe = JFactory::getApplication();
//$mainframe->registerEvent( 'numberFormat', 'setNumberFormat' );
$mainframe->registerEvent( 'getOffers', 'getOffers' );
$mainframe->registerEvent( 'getOfferSpecial', 'getOfferSpecial' );
$mainframe->registerEvent( 'getTourismType', 'getTourismType' );
$mainframe->registerEvent( 'getPlanCountries', 'getPlanCountries' );
$mainframe->registerEvent( 'getPlanCitiesByCountry', 'getPlanCitiesByCountry' );
$mainframe->registerEvent( 'getPlanCategories', 'getPlanCategories' );
$mainframe->registerEvent( 'getPlanTurismTypes', 'getPlanTurismTypes' );

/**
 * Retorna la moneda con el formato establecido
 */
/*function setNumberFormat($value){
 return number_format((double)$value,0,',','.');
 }*/

/**
 * Retorna las ofertas marcadas como especial
 */
function getOfferSpecial() {
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

function getWhere(){
	$user =& JFactory::getUser();
	// Standard filters: product and category active, at least one image and publish date not over
	$where[] = 'p.`published` = 1';
	$where[] = 'c.`published` = 1';
	$where[] = '( p.`publish_up` = \'0000-00-00 00:00:00\' OR p.`publish_up` <= \'' . date('Y-m-d') . '\' )';
	$where[] = '( p.`publish_down` = \'0000-00-00 00:00:00\' OR DATE_FORMAT(p.`publish_down`, \'%Y-%m-%d\') >= CURDATE() )';
	//$where[] = 'p.`access` <= ' . $user->get('aid', 0);
	return ' AND ' . implode(' AND ', $where);
}

/**
 * Retorna las ofertas por tipo de turismo
 */
function getOffers($tourismType){
	$db =& JFactory::getDBO();
	//$query = "SELECT p.* FROM
	$query = "SELECT p.*,(select pf.file_url from #__cp_product_files pf where pf.product_id = p.product_id limit 1 ) as img FROM
		#__cp_category c JOIN	
		(#__cp_products p JOIN
		#__cp_product_tourismtype ptt ON 
		ptt.product_id=p.product_id JOIN
		#__cp_tourismtype tt ON 
		ptt.tourismtype_id=tt.tourismtype_id)
		ON c.category_id=p.category_id		
		WHERE 1 ";
	if($tourismType != ''){
		$query .= " AND ptt.tourismtype_id=" . $tourismType;
	}
	$query .= "	AND p.`published` = 1 ".getWhere()." AND tt.`published` = 1";
	
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	return $rows;
}

/**
 * Retorna el listado de tipos de turismo
 */
function getTourismType($active_only = false) {
	$db =& JFactory::getDBO();
	$query = "SELECT * FROM #__cp_tourismtype";
	if ($active_only) {
		$query .= ' WHERE published = 1';
	}
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	return $rows;
}

/**
 * Retorna el listado de paises con planes asociados
 */
function getPlanCountries($active_only = true) {
	$db =& JFactory::getDBO();
	$user =& JFactory::getUser();
	$query = "SELECT c.code2, c.name FROM #__cp_products p JOIN #__cp_country c ON ";
	$query .= " p.country_code = c.code2 JOIN #__cp_category ca ON p.category_id = ca.category_id";
	$query .= " JOIN #__cp_product_tourismtype ptt ON p.product_id = ptt.product_id";
	$query .= " JOIN #__cp_tourismtype tt ON ptt.tourismtype_id = tt.tourismtype_id";
	if ($active_only) {
		$query .= ' WHERE p.published = 1 AND ca.published = 1 AND tt.published = 1';
	}
	$query .= ' AND ( p.`publish_up` = \'0000-00-00 00:00:00\' OR p.`publish_up` <= \'' . date('Y-m-d') . '\' ) AND ';
	$query .= ' ( p.`publish_down` = \'0000-00-00 00:00:00\' OR DATE_FORMAT(p.`publish_down`, \'%Y-%m-%d\') >= CURDATE() ) AND p.`access` <= ' . $user->get('aid', 0);
	$query .= ' GROUP BY c.code2 ORDER BY c.name ASC';
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	return $rows;
}

/**
 * Retorna el listado de ciudades con planes asociados
 */
function getPlanCitiesByCountry($country, $active_only = true) {
	$db =& JFactory::getDBO();
	$user =& JFactory::getUser();
	$query = "SELECT c.airportcode, c.city FROM #__cp_cities c JOIN #__cp_products p ON ";
	$query .= " c.airportcode = p.city WHERE p.country_code = '" . $country . "'";
	if ($active_only) {
		$query .= ' AND p.published = 1';
	}
	$query .= ' AND ( p.`publish_up` = \'0000-00-00 00:00:00\' OR p.`publish_up` <= \'' . date('Y-m-d') . '\' ) AND ';
	$query .= ' ( p.`publish_down` = \'0000-00-00 00:00:00\' OR DATE_FORMAT(p.`publish_down`, \'%Y-%m-%d\') >= CURDATE() ) AND p.`access` <= ' . $user->get('aid', 0);
	$query .= ' GROUP BY c.airportcode ORDER BY c.city ASC';
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	return $rows;
}

/**
 * Retorna el listado de categorías con planes asociados
 */
function getPlanCategories($active_only = true) {
	$db =& JFactory::getDBO();
	$user =& JFactory::getUser();
	$query = "SELECT c.category_id, c.category_name FROM #__cp_category c JOIN #__cp_products p ON ";
	$query .= " c.category_id = p.category_id";
	$query .= " JOIN #__cp_product_tourismtype ptt ON p.product_id = ptt.product_id";
	$query .= " JOIN #__cp_tourismtype tt ON ptt.tourismtype_id = tt.tourismtype_id";
	if ($active_only) {
		$query .= ' WHERE p.published = 1 AND c.published = 1';
	}
	$query .= ' AND ( p.`publish_up` = \'0000-00-00 00:00:00\' OR p.`publish_up` <= \'' . date('Y-m-d') . '\' ) AND ';
	$query .= ' ( p.`publish_down` = \'0000-00-00 00:00:00\' OR DATE_FORMAT(p.`publish_down`, \'%Y-%m-%d\') >= CURDATE() ) AND p.`access` <= ' . $user->get('aid', 0);
	$query .= ' GROUP BY c.category_id ORDER BY c.category_name ASC';
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	return $rows;
}

/**
 * Retorna el listado de paises con planes asociados
 */
function getPlanTurismTypes($active_only = true) {
	$db =& JFactory::getDBO();
	$user =& JFactory::getUser();
	$query = "SELECT tt.tourismtype_id, tt.tourismtype_name FROM #__cp_tourismtype tt JOIN #__cp_product_tourismtype ptt ON ";
	$query .= " tt.tourismtype_id = ptt.tourismtype_id JOIN #__cp_products p ON ptt.product_id = p.product_id";
	$query .= " JOIN #__country c ON p.country_code = c.code2 JOIN #__cp_category ca ON p.category_id = ca.category_id";
	if ($active_only) {
		$query .= ' WHERE p.published = 1 AND ca.published = 1 AND tt.published = 1';
	}
	$query .= ' AND ( p.`publish_up` = \'0000-00-00 00:00:00\' OR p.`publish_up` <= \'' . date('Y-m-d') . '\' ) AND ';
	$query .= ' ( p.`publish_down` = \'0000-00-00 00:00:00\' OR DATE_FORMAT(p.`publish_down`, \'%Y-%m-%d\') >= CURDATE() ) AND p.`access` <= ' . $user->get('aid', 0);
	$query .= ' GROUP BY tt.tourismtype_id ORDER BY tt.tourismtype_name ASC';
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	return $rows;
}
