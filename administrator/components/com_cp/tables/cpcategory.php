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

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');

/**
 * Cp Table
 *
 * @package    Joomla.Components
 * @subpackage 	Cp
 */
class TableCPCategory extends JTable {
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $category_id = null;
	/**
	 *
	 * @var string
	 */
	var $category_name = null;
	/**
	 *
	 * @var int
	 */
	var $published = 1;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__cp_category', 'category_id', $db);
	}

	function check() {
		// write here data validation code
		return parent::check();
	}

	function delete($pk = null) {
		// Initialise variables.
		$k = $this->_tbl_key;
		$pk = (is_null($pk)) ? $this->$k : $pk;

		// If no primary key is given, return false.
		if ($pk === null) {
			$e = new JException(JText::_('JLIB_DATABASE_ERROR_NULL_PRIMARY_KEY'));
			$this->setError($e);
			return false;
		}

		// Check if the category has products related
		$query = 'SELECT product_id FROM #__cp_products WHERE category_id = ' . $pk;
		$this->_db->setQuery($query);
		if (!$this->_db->execute()) {
			$this->db_error = true;
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if ($this->_db->getNumRows() > 0) {
			$e = new JException(JText::_('COM_CP_ERROR_DELETING_CATEGORIES'));
			$this->setError($e);
			return false;
		}

		// Delete associations
		return parent::delete($pk);
	}
}
