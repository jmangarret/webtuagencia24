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
class TableCPProducts extends JTable {
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $product_id = null;
	/**
	 *
	 * @var string
	 */
	var $product_name = null;
	/**
	 *
	 * @var string
	 */
	var $product_code = null;
	/**
	 *
	 * @var string
	 */
	var $product_desc = null;
	/**
	 *
	 * @var int
	 */
	var $category_id = null;
	/**
	 *
	 * @var int
	 */
	var $price = 0;
	/**
	 *
	 * @var int
	 */
	var $quota = 0;
	/**
	 *
	 * @var string
	 */
	var $country_code = "0";
	/**
	 *
	 * @var string
	 */
	var $city = '';
	/**
	 *
	 * @var int
	 */
	var $featured = 0;
	/**
	 *
	 * @var string
	 */
	var $latitude = null;
	/**
	 *
	 * @var string
	 */
	var $longitude = null;
	/**
	 *
	 * @var int
	 */
	var $ordering = 0;
	/**
	 *
	 * @var datetime
	 */
	var $publish_up = "0000-00-00 00:00:00";
	/**
	 *
	 * @var datetime
	 */
	var $publish_down = "0000-00-00 00:00:00";
	/**
	 *
	 * @var datetime
	 */
	var $created = "0000-00-00 00:00:00";
	/**
	 *
	 * @var int
	 */
	var $created_by = 0;
	/**
	 *
	 * @var datetime
	 */
	var $modified = "0000-00-00 00:00:00";
	/**
	 *
	 * @var int
	 */
	var $modified_by = 0;
	/**
	 *
	 * @var int
	 */
	var $access = 0;
	/**
	 *
	 * @var int
	 */
	var $published = 1;
	/**
	 *
	 * @var string
	 */
	var $tag_name1 = null;
	/**
	 *
	 * @var string
	 */
	var $tag_content1 = null;
	/**
	 *
	 * @var string
	 */
	var $tag_name2 = null;
	/**
	 *
	 * @var string
	 */
	var $tag_content2 = null;
	/**
	 *
	 * @var string
	 */
	var $tag_name3 = null;
	/**
	 *
	 * @var string
	 */
	var $tag_content3 = null;
	/**
	 *
	 * @var string
	 */
	var $tag_name4 = null;
	/**
	 *
	 * @var string
	 */
	var $tag_content4 = null;
	/**
	 *
	 * @var string
	 */
	var $tag_name5 = null;
	/**
	 *
	 * @var string
	 */
	var $tag_content5 = null;
	/**
	 *
	 * @var string
	 */
	var $tag_name6 = null;
	/**
	 *
	 * @var string
	 */
	var $tag_content6 = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__cp_products', 'product_id', $db);
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
			$query->where('product_id = ' . $this->_db->quote($pk));
			$this->_db->setQuery($query);

			// Check for a database error.
			if (!$this->_db->execute()) {
				$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_DELETE_FAILED', get_class($this), $this->_db->getErrorMsg()));
				$this->setError($e);
				return false;
			}

			$query = $this->_db->getQuery(true);
			$query->delete();
			$query->from('#__cp_product_files');
			$query->where('product_id = ' . $this->_db->quote($pk));
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