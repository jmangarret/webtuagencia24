<?php
defined('_JEXEC') or die('Restricted access');

/**
 * Renders a Calendar element
 *
 * @package		Joomla
 * @subpackage	Banners
 * @since		1.5
 */
class JElementCalendar extends JElement {

  /**
   * Element name
   *
   * @access	protected
   * @var		string
   */
  var $_name = 'Calendar';

  function fetchElement($name, $value, &$node, $control_name) {
    $formatDate = '%d/%m/%Y';
    if(isset($node->_attributes["formatDate"]) && $node->_attributes["formatDate"]!=""){
      $formatDate = $node->_attributes["formatDate"];
    }
    return JHTML::_('calendar', $value, '' . $control_name . '[' . $name . ']', 'calendar',  $formatDate, $arrayConfigData);
  }

}