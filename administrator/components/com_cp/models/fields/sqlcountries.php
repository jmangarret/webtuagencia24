<?php
// Protect from unauthorized access
defined('_JEXEC') or die();

if (!class_exists('JFormFieldSql')) {
	require_once JPATH_LIBRARIES . '/joomla/form/fields/sql.php';
}

/**
 * Form Field class for Countries
 *
 * @package  com_cp
 */
class JFormFieldSQLCountries extends JFormFieldSql {

	public $type = 'SQLCountries';

	protected function getOptions() {
		$lg = JFactory::getLanguage();
		$lang = substr($lg->getTag(), 0, 2);
		$this->element['query'] = str_ireplace('__lang__', $lang, (string) $this->element['query']);

		return parent::getOptions();
	}
}
