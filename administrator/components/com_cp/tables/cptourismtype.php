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

/**
 * Cp Table
 *
 * @package    Joomla.Components
 * @subpackage 	Cp
 */
class TableCPTourismType extends JTable {
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $tourismtype_id = null;
	/**
	 *
	 * @var string
	 */
	var $tourismtype_name = null;
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
		parent::__construct('#__cp_tourismtype', 'tourismtype_id', $db);
	}
	
	function check() {
		// write here data validation code
		return parent::check();
	}

	function delete($pk = null) {
		// Delete associations
		if (parent::delete($pk)) {
			$query = $this->_db->getQuery(true);
			$query->delete();
			$query->from('#__cp_product_tourismtype');
			$query->where('tourismtype_id = ' . $this->_db->quote($pk));
			$this->_db->setQuery($query);

			// Check for a database error.
			if (!$this->_db->execute()) {
				$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_DELETE_FAILED', get_class($this), $this->_db->getErrorMsg()));
				$this->setError($e);
				return false;
			}

			return true;
		}
	}
}