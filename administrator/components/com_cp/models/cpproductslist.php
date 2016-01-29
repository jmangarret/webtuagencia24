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
                'category_name', 'price',
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
     * @since   1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $access = $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
        $this->setState('filter.access', $access);

        $published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        $tourismTypeId = $this->getUserStateFromRequest($this->context.'.filter.tourismtype_id', 'filter_tourismtype_id', '');
        $this->setState('filter.tourismtype_id', $tourismTypeId);

        $categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id');
        $this->setState('filter.category_id', $categoryId);

        // List state information.
        parent::populateState('product_id', 'DESC');
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
     * @since   1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id .= ':'.$this->getState('filter.search');
        $id .= ':'.$this->getState('filter.published');
        $id .= ':'.$this->getState('filter.category_id');
        $id .= ':'.$this->getState('filter.tourismtype_id');

        return parent::getStoreId($id);
    }

	/**
     * Build an SQL query to load the list data.
     *
     * @return  JDatabaseQuery
     * @since   1.6
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
                't1.product_id, t1.product_name, t1.product_code, t1.published, t1.price, t1.created, t1.created_by, t1.modified, t1.access, t1.featured, t1.ordering, t1.publish_up, t1.publish_down'
            )
        );
        $query->from('#__cp_products AS t1');

        // Product category
        $query->select('c.category_name AS category_name');
        $query->join('INNER', '#__cp_category c ON c.category_id = t1.category_id');

        // Last update user
        $query->select('uc.name AS editor');
        $query->join('LEFT', '#__users AS uc ON uc.id = t1.modified_by');

        // Join over the asset groups.
        $query->select('ag.title AS access_level');
        $query->join('LEFT', '#__viewlevels AS ag ON ag.id = t1.access');

        // Tourism type
        $query->select('tt.tourismtype_name AS tourismtype_name');
        $query->join('LEFT', '#__cp_product_tourismtype AS ttj ON ttj.product_id = t1.product_id');
        $query->join('LEFT', '#__cp_tourismtype AS tt ON tt.tourismtype_id = ttj.tourismtype_id');

        // Join over the users for the author.
        $query->select('ua.name AS creator');
        $query->join('LEFT', '#__users AS ua ON ua.id = t1.created_by');

        // Filter by category
        if ($category_id = $this->getState('filter.category_id')) {
            $query->where('t1.category_id = ' . (int) $category_id);
        }

        // Filter by category
        if ($tourismtype_id = $this->getState('filter.tourismtype_id')) {
            $query->where('ttj.tourismtype_id = ' . (int) $tourismtype_id);
        }

        // Implement View Level Access
        if (!$user->authorise('core.admin')) {
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $query->where('t1.access IN (' . $groups . ')');
        }

        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('t1.published = ' . (int) $published);
        }

        // Filter by search in product_name.
        $search = $this->getState('filter.search');
        if (!empty($search)) {
        	$search = $db->Quote('%'.$db->escape($search, true).'%');
        	$query->where('(t1.product_name LIKE ' . $search . ' OR t1.product_code LIKE ' . $search . ')');
        }

        // Add the list ordering clause.
        $orderCol   = $this->getState('list.ordering', 'product_id');
        $orderDirn  = $this->getState('list.direction', 'DESC');
        switch ($orderCol) {
        	case 'access_level':
        		$orderCol = 'ag.title';
        		break;
        	case 'category_name':
        		$orderCol = 'c.category_name';
        		break;
        	default:
        		$orderCol = 't1.' . $orderCol;
        		break;
        }
		$query->group('t1.product_id');
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
            $user = JFactory::getUser();
            $groups = $user->getAuthorisedViewLevels();

            for ($x = 0, $count = count($items); $x < $count; $x++) {
                //Check the access level. Remove products the user shouldn't see
                if (!in_array($items[$x]->access, $groups)) {
                    unset($items[$x]);
                }
            }
        }
        return $items;
    }
}