<?php
defined('_JEXEC') or die('Restricted access');

class CPHelper {

	function getProductTourismTypes($product_id) {
		$query = 'SELECT `tourismtype_id` FROM `#__cp_product_tourismtype` WHERE product_id = '. (int) $product_id;

		// Initialize variables
		$db	= JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadColumn();
	}

	function listCountries($active = NULL) {
		$lg = JFactory::getLanguage();
		$lang = substr($lg->getTag(), 0, 2);
		$query = 'SELECT DISTINCT co.`Code2` as value, co.`Name` as text FROM `#__cp_country` co JOIN `#__cp_cities` ci ON co.`Code2` = ci.`countryCode` WHERE ci.`language` = \'' . $lang. '\' ORDER BY co.`Name`';

		// Initialize variables
		$db	= JFactory::getDBO();

		$countries = array();
		$countries[] = JHtml::_('select.option', '-1', '- ' . JText::_('COM_CP_SELECT_COUNTRY').' -');
		$db->setQuery($query);
		$countries = array_merge($countries, $db->loadObjectList());

		$country = JHtml::_('select.genericlist', $countries, 'country_code', 'class="inputbox" style="width:150px;" size="1"', 'value', 'text', $active);

		return $country;
	}

	function listCities($country, $active = NULL, $name = 'city', $id = 'city') {
		$cities = array();
		$cities[] = JHtml::_('select.option', '', '- '.JText::_('COM_CP_SELECT_CITY').' -');
		if ($country) {
			$lg = JFactory::getLanguage();
			$lang = substr($lg->getTag(), 0, 2);

			$query = 'SELECT DISTINCT `airportcode` as value, `city` as text FROM `#__cp_cities` WHERE `countryCode` = \'' . $country . '\' AND `language` = \'' . $lang. '\' AND (`regionname` IS NULL OR LENGTH(`regionname`) = 0) GROUP BY city ORDER BY city';

			// Initialize variables
			$db	= JFactory::getDBO();

			$db->setQuery($query);
			$cities = array_merge($cities, $db->loadObjectList());
		}

		return JHtml::_('select.genericlist', $cities, $name, 'class="inputbox required" style="width:150px;" size="1"', 'value', 'text', $active, $id);
	}

	function getCities($country, $active = NULL) {
		$cities = array();
		$cities[] = JHtml::_('select.option', '', '- '.JText::_('COM_CP_SELECT_CITY').' -');
		if ($country) {
			$lg = JFactory::getLanguage();
			$lang = substr($lg->getTag(), 0, 2);

			$query = 'SELECT DISTINCT `airportcode` as value, `city` as text FROM `#__cp_cities` WHERE `countryCode` = \'' . $country . '\' AND `language` = \'' . $lang. '\' AND (`regionname` IS NULL OR LENGTH(`regionname`) = 0) GROUP BY city ORDER BY city';

			// Initialize variables
			$db	= JFactory::getDBO();

			$db->setQuery($query);
			$cities = array_merge($cities, $db->loadObjectList());
		}

		return $cities;
	}

	function filterCategory($active = NULL) {
		$query = 'SELECT cc.`category_id` AS `value`, if (cc.published = 1, cc.`category_name`, CONCAT(cc.`category_name`, \'*\')) AS `text`' .
				' FROM `#__cp_category` AS cc' .
				' ORDER BY cc.`category_name`';

		// Initialize variables
		$db	= JFactory::getDBO();

		$categories = array();
		$categories[] = JHtml::_('select.option', '', '- '.JText::_('COM_CP_SELECT_CATEGORY').' -');
		$db->setQuery($query);
		$categories = array_merge($categories, $db->loadObjectList());

		$category = JHtml::_('select.genericlist', $categories, 'filter_category_id', 'class="inputbox" size="1" onchange="this.form.submit();"', 'value', 'text', $active);

		return $category;
	}

	function filterTourismType($active = NULL) {
		$query = 'SELECT t.`tourismtype_id` AS `value`, if (t.published = 1, t.`tourismtype_name`, CONCAT(t.`tourismtype_name`, \'*\')) AS `text`' .
				' FROM `#__cp_tourismtype` AS t' .
				' ORDER BY t.`tourismtype_name`';

		// Initialize variables
		$db	= JFactory::getDBO();

		$tourismtypes = array();
		$tourismtypes[] = JHtml::_('select.option', '', '- '.JText::_('COM_CP_SELECT_TOURISM_TYPE').' -');
		$db->setQuery($query);
		$tourismtypes = array_merge($tourismtypes, $db->loadObjectList());

		$tourismtypes = JHtml::_('select.genericlist', $tourismtypes, 'filter_tourismtype_id', 'class="inputbox" size="1" onchange="this.form.submit();"', 'value', 'text', $active);

		return $tourismtypes;
	}

	/**
	 * @param   int $value  The state value
	 * @param   int $i
	 */
	function featured($value = 0, $i, $canChange = true) {
		// Array of image, task, title, action
		$states = array(
			0   => array('disabled.png', 'cpproductslist.featured', 'COM_CP_UNFEATURED', 'COM_CP_TOGGLE_TO_FEATURE'),
			1   => array('featured.png', 'cpproductslist.unfeatured', 'COM_CP_FEATURED', 'COM_CP_TOGGLE_TO_UNFEATURE'),
		);
		$state  = JArrayHelper::getValue($states, (int) $value, $states[1]);
		$html   = JHtml::_('image', 'admin/' . $state[0], JText::_($state[2]), NULL, true);
		if ($canChange) {
			$html   = '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'
					. $html.'</a>';
		}

		return $html;
	}
}
