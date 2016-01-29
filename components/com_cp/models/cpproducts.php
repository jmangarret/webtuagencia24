<?php
/**
 * Cp Model for Cp Component
 * 
 * @package    Cp
 * @subpackage com_cp
 * @license  GNU/GPL v2
 *
 *
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.modelitem');

/**
 * Cp Model
 *
 * @package    Joomla.Components
 * @subpackage 	Cp
 */
class CPModelCPProducts extends JModelItem {

	protected $_context = 'com_cp';
	
	/**
	 * Gets the data
	 * @return mixed The data to be displayed to the user
	 */
	public function getItem() {
		if (empty( $this->_item)) {
			$id = JRequest::getInt('id',  0);
			$db = $this->getDbo();
			$query = "SELECT p.*, co.name as country_name, ci.city as city_name";
			$query .= " FROM `#__cp_products` p JOIN #__cp_country co ON p.country_code = co.code2";
			$query .= " JOIN #__cp_cities ci ON p.city = ci.airportcode";
			$query .= " WHERE p.`product_id` = {$id} AND p.`published` = 1";
			$db->setQuery( $query );
			$this->_item = array($db->loadObject());

			// Load its pictures
			$query = 'SELECT * FROM `#__cp_product_files` WHERE `product_id` = ' . $id . ' ORDER BY `ordering` ASC';
			$db->setQuery( $query );
			$this->_item[0]->media = $db->loadObjectList();
		}
		return $this->_item[0];
	}

	public function formatNumber($value, $currency) {
		// Plugin to format prices.
		JPluginHelper::importPlugin('amadeus', 'numberformat');
		$dispatcher = JDispatcher::getInstance();
		return $dispatcher->trigger('numberFormatWithCurrency', array($value, $currency)); 
	}

	/**
	 * Method to decrease a product quota after an order.
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	public function decreaseQuota($product_id) {
		$db = $this->getDbo();
		$query = "UPDATE `#__cp_products` SET `quota` = `quota` - 1 WHERE `product_id` = {$product_id} AND `quota` > 0";
		$db->setQuery( $query );

		if (!$db->query()) {
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}

		return true;
	}
}
