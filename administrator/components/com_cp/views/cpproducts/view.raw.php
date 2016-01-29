<?php
defined('_JEXEC') or die;

/**
 * View class for a list of tracks.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cp
 */
class CPViewCPProducts extends JViewLegacy {
	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		require_once(JPATH_COMPONENT . DS . 'helper.php');

		$country_code = JRequest::getVar('country_code');
		$helper = new CPHelper();
		$result = $helper->listCities($country_code, null, 'jform[city]', 'jform_city');
		echo ($result);
	}
}
