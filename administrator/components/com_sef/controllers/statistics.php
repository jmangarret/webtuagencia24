<?php
/**
 * SEF component for Joomla!
 * 
 * @package   JoomSEF
 * @version   4.5.6
 * @author    ARTIO s.r.o., http://www.artio.net
 * @copyright Copyright (C) 2014 ARTIO s.r.o. 
 * @license   GNU/GPLv3 http://www.artio.net/license/gnu-general-public-license
 */
 
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

class SEFControllerStatistics extends SEFController {
    function display()
    {
        JRequest::setVar('view', 'statistics');
        
        parent::display();
    }
    
}
?>