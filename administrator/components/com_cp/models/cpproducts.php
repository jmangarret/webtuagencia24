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

jimport('joomla.application.component.modeladmin');

/**
 * Cp Model
 *
 * @package    Joomla.Components
 * @subpackage 	Cp
*/
class CPModelCPProducts extends JModelAdmin {

	protected $text_prefix = 'COM_CP';

	/**
	 * Method to get a record
	 * @return object with data
	 */
	public function getItem($pk = null) {
		if (empty($pk)) {
			$cid = JRequest::getVar('cid', array(0), null, 'array');
			$pk = $cid[0];
		}
		if (empty($pk)) {
			$pk = JRequest::getInt('product_id');
		}
		if ($item = parent::getItem($pk)) {
			$today = JFactory::getDate();
			if (empty($pk)) {
				$item->tag_name1 = JText::_('COM_CP_TAG1_DESC');
				$item->tag_name2 = JText::_('COM_CP_TAG2_DESC');
				$item->tag_name3 = JText::_('COM_CP_TAG3_DESC');
				$item->tag_name4 = JText::_('COM_CP_TAG4_DESC');
				$item->tag_name5 = JText::_('COM_CP_TAG5_DESC');
				$item->tag_name6 = JText::_('COM_CP_TAG6_DESC');
				$item->publish_up = date('Y-m-d');
			}
			$item->publish_up = substr($item->publish_up, 0, 10);
			$item->publish_down = substr($item->publish_down, 0, 10);
			$query = 'SELECT * FROM `#__cp_product_files` WHERE `product_id` = ' . $pk . ' ORDER BY `ordering` ASC';
			$this->_db->setQuery($query);
			$item->media = $this->_db->loadObjectList();
			$query = 'SELECT tourismtype_id FROM `#__cp_product_tourismtype` WHERE `product_id` = ' . $pk;
			$this->_db->setQuery($query);
			$item->tourismtype_id = $this->_db->loadColumn();
		}
		return $item;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	public function save($data) {
		$user = JFactory::getUser();
		$today = JFactory::getDate();
		if (intval($data['product_id']) < 1) {
			$data['created'] = $today->toFormat('%Y-%m-%d %H:%M:%S');
			$data['created_by'] = $user->get('id');
		}
		$data['modified'] = $today->toFormat('%Y-%m-%d %H:%M:%S');
		$data['modified_by'] = $user->get('id');
		if (parent::save($data)) {

			$product_id = (int) $this->getState($this->getName().'.id');
			// If you are editing the product, clean and save its tourism types
			if ($product_id) {
				$query = 'DELETE FROM #__cp_product_tourismtype WHERE product_id = ' . $product_id;
				$this->_db->setQuery($query);
				if (!$this->_db->query()) {
					JError::raiseError(500, $this->_db->getErrorMsg());
					return false;
				}
				if (key_exists('tourismtype_id', $data) && is_array($data['tourismtype_id'])) {
					$tourismtype_id = $data['tourismtype_id'];
					foreach ($tourismtype_id as $key=>$value) {
						$query = 'INSERT INTO #__cp_product_tourismtype (product_id, tourismtype_id) VALUES (' . $product_id . ', ' . (int) $value . ')';
						$this->_db->setQuery($query);
						if (!$this->_db->query()) {
							JError::raiseError(500, $this->_db->getErrorMsg());
							return false;
						}
					}
				}

				$app = JFactory::getApplication();
				if (count($app->input->get('mediafiles', null, 'array'))) {
					$mediafiles = $app->input->get('mediafiles', null, 'array');
					$files = "'" . implode("','", $mediafiles) . "'";

					// Delete the files not included
					$query = 'DELETE FROM #__cp_product_files WHERE `file_url` NOT IN (' . $files . ') AND `product_id` = ' . $product_id;
					$this->_db->setQuery($query);
					if (!$this->_db->query()) {
						JError::raiseError(500, $this->_db->getErrorMsg());
						return false;
					}

					$i = 1;
					foreach ($mediafiles as $file) {
						// Check if the file is already related to the product, if it's not, insert.
						$query = 'SELECT `file_id` FROM #__cp_product_files WHERE `file_url` = \'' . $file . '\' AND `product_id` = ' . $product_id;
						$this->_db->setQuery($query);
						$this->_db->query();
						if (!$this->_db->getNumRows()) {
							$query = 'INSERT INTO #__cp_product_files (`product_id`, `file_url`, `ordering`) VALUES (' . $product_id . ', \'' . $file . '\', ' . $i . ')';
						} else {
							$query = 'UPDATE #__cp_product_files SET `ordering` = ' . $i . ' WHERE `file_url` = \'' . $file . '\' AND `product_id` = ' . $product_id;
						}
						$this->_db->setQuery($query);
						if (!$this->_db->query()) {
							JError::raiseError(500, $this->_db->getErrorMsg());
							return false;
						}

						$i++;
					}
				} else {
					$query = 'DELETE FROM #__cp_product_files WHERE product_id = ' . $product_id;
					$this->_db->setQuery($query);
					if (!$this->_db->query()) {
						JError::raiseError(500, $this->_db->getErrorMsg());
						return false;
					}
				}
			}
		}

		return true;
	}

	/**
	 * Method to toggle the featured setting of products.
	 *
	 * @param	array	The ids of the items to toggle.
	 * @param	int		The value to toggle to.
	 *
	 * @return	boolean	True on success.
	 */
	public function featured($pks, $value = 0) {
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks)) {
			$this->setError(JText::_('COM_CP_NO_ITEM_SELECTED'));
			return false;
		}

		$table = $this->getTable();

		try {
			$db = $this->getDbo();

			$db->setQuery(
					'UPDATE #__cp_products' .
					' SET featured = '.(int) $value.
					' WHERE product_id IN ('.implode(',', $pks).')'
			);
			if (!$db->query()) {
				throw new Exception($db->getErrorMsg());
			}

		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		$table->reorder();

		$this->cleanCache();

		return true;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_cp.product', 'product', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		$user = JFactory::getUser();

		// Check for existing product.
		// Modify the form based on Edit State access controls.
		if (!$user->authorise('core.edit.state', 'com_cp')) {
			// Disable fields for display.
			$form->setFieldAttribute('featured', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an article you can edit.
			$form->setFieldAttribute('featured', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
		}

		return $form;
	}

	public function getTable($type = 'CPProducts', $prefix = 'Table', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 */
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_cp.edit.cpproducts.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
}