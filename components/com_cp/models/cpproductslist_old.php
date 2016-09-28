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

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Cp Model
 *
 * @package    Joomla.Components
 * @subpackage 	Cp
*/
class CPModelCPProductsList extends JModelList {
	/*
	 * Constructor
	*
	*/
	function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				't1.product_id', 't1.product_name', 't1.product_code',
				'product_id', 'product_name', 'created',
				't1.created', 'ttj.tourismtype_id', 't1.featured',
				't1.published', 't1.access', 'featured',
				'published', 'access', 'ag.title',
				'c.category_name', 't1.price', 'ordering',
				'category_name', 'price', 't1.product_desc',
				'product_desc', 't1.ordering', 't1.product_desc',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 'ordering', $direction = 'ASC') {
		$app = JFactory::getApplication();

		// List state information
		$value = JRequest::getUInt('limit', $app->getCfg('list_limit', 0));
		$this->setState('list.limit', $value);

		$value = JRequest::getUInt('limitstart', 0);
		$this->setState('list.start', $value);

		$orderCol	= JRequest::getCmd('order_field', $ordering);
		if (!in_array($orderCol, $this->filter_fields)) {
			$orderCol = 'ordering';
		}
		$this->setState('list.ordering', $orderCol);

		$country_code = $this->getUserStateFromRequest($this->context.'.filter.country_code', 'country_code');
		$this->setState('filter.country_code', $country_code);

		$city = $this->getUserStateFromRequest($this->context.'.filter.access', 'city');
		$this->setState('filter.city', $city);

		$featured = $app->getUserStateFromRequest($this->context.'.filter.featured', 'filter_featured');
		$this->setState('filter.featured', $featured);

		$tourismTypeId = $this->getUserStateFromRequest($this->context.'.filter.tourismtype_id', 'tourismtype_id', '');
		$this->setState('filter.tourismtype_id', $tourismTypeId);

		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'category_id');
		$this->setState('filter.category_id', $categoryId);
		// List state information.
		//parent::populateState($ordering, 'ASC');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string      $id A prefix for the store id.
	 *
	 * @return  string      A store id.
	 */
	protected function getStoreId($id = '') {
		// Compile the store id.
		$id .= ':'.$this->getState('filter.featured');
		$id .= ':'.$this->getState('filter.country_code');
		$id .= ':'.$this->getState('filter.city');
		$id .= ':'.$this->getState('filter.category_id');
		$id .= ':'.$this->getState('filter.tourismtype_id');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 */
	protected function getListQuery() {
		// Create a new query object.
		$db     = $this->getDbo();
		$query  = $db->getQuery(true);
		$user   = JFactory::getUser();

		// Select the required fields from the table.
		$query->select(
				$this->getState(
						'list.select',
						't1.`product_id`, t1.`product_name`, t1.`product_desc`, t1.`price`, t1.`featured`, t1.`access`'
				)
		);
		$query->from('#__cp_products AS t1');

		// Tourism type
		$query->join('LEFT', '#__cp_product_tourismtype AS ttj ON ttj.product_id = t1.product_id');

		// Product images
		$query->select('f.file_url AS file_url');
		$query->join('LEFT', '#__cp_product_files AS f ON f.product_id = t1.product_id AND f.ordering = 1');

		// Filter by country
		if ($country_code = $this->getState('filter.country_code')) {
			$query->where('t1.country_code = \'' . $country_code . '\'');
		}

		// Filter by city
		if ($city = $this->getState('filter.city')) {
			$query->where('t1.city = \'' . $city . '\'');
		}

		// Filter by category
		if ($category_id = $this->getState('filter.category_id')) {
			$query->where('t1.category_id = ' . (int) $category_id);
		}

		// Filter by Tourism Type
		if ($tourismtype_id = $this->getState('filter.tourismtype_id')) {
			$query->where('ttj.tourismtype_id = ' . (int) $tourismtype_id);
		}

		// Implement View Level Access
		$viewLevels = array_unique($user->getAuthorisedViewLevels());
		if (count($viewLevels) > 1) {
			$groups = implode(',', $viewLevels);
			$query->where('t1.access IN (' . $groups . ')');
		} else {
			$query->where('t1.access = ' . (int) $viewLevels[0]);
		}

		// Conditions
		$query->where('t1.published = 1');
		$query->where('(t1.`publish_up` = \'0000-00-00 00:00:00\' OR t1.`publish_up` <= \'' . date('Y-m-d') . '\')');
		$query->where('(t1.`publish_down` = \'0000-00-00 00:00:00\' OR DATE_FORMAT(t1.`publish_down`, \'%Y-%m-%d\') >= CURDATE())');

		// Add the list ordering clause.
		$orderCol   = 't1.' . $this->getState('list.ordering', 'ordering');
		$orderDirn  = $this->getState('list.direction', 'ASC');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		//echo $query;
		return $query;
	}

	/**
	 * Method to get a list of articles.
	 * Overridden to add a check for access levels.
	 *
	 * @return  mixed   An array of data items on success, false on failure.
	 * @since   1.6.1
	 */
	public function getItems() {
		$items  = parent::getItems();
		$app    = JFactory::getApplication();
		if ($app->isSite()) {
			$user   = JFactory::getUser();
			$groups = array_unique($user->getAuthorisedViewLevels());

			for ($x = 0, $count = count($items); $x < $count; $x++) {
				//Check the access level. Remove products the user shouldn't see
				if (!in_array($items[$x]->access, $groups)) {
					unset($items[$x]);
				}
			}
		}
		return $items;
	}

	public function formatNumber($value, $currency) {
		// Plugin to format prices.
		JPluginHelper::importPlugin('amadeus', 'numberformat');
		$dispatcher = JDispatcher::getInstance();
		return $dispatcher->trigger('numberFormatWithCurrency', array($value, $currency));
	}

}
