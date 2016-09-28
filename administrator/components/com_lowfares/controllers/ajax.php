<?php
/**
 * @file com_asom/admin/controllers/orders.php
 * @defgroup _comp_adm Componente (Administración)
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');


class LowFaresControllerAjax extends JController
{

    public function getItinerariesFromCategory()
    {
        $category = JRequest::getVar('category', 0, 'post', 'int');

        if($category == 0)
        {
            echo json_encode(array());
        }
        else
        {
            $db    = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('id, originname, destinyname')
                  ->from('#__lf_flights')
                  ->where('category = '.$db->Quote($category));

            $db->setQuery($query);

            echo json_encode($db->loadObjectList());
        }

        JFactory::getApplication()->close();
    }

    public function getItineraries()
    {
        $category  = JRequest::getVar('category', 0, 'post', 'int');
        $state     = JRequest::getVar('state', '', 'post', 'string');
        $itinerary = JRequest::getVar('itinerary', 0, 'post', 'int');

        JLoader::register('ProcessHelper', JPATH_COMPONENT.DS.'helpers'.DS.'process.php');

        $itineraries = ProcessHelper::getItineraries($category, $itinerary, $state);

        echo json_encode($itineraries);

        JFactory::getApplication()->close();
    }

    public function processItinerary()
    {
        $id = JRequest::getVar('id', 0, 'post', 'int');

        // Check if the user is authorized to do this.
        if (!JFactory::getUser()->authorise('core.edit.state', $option))
        {
            echo json_encode(array('type' => 'error', 'msg' => JText::_('JERROR_ALERTNOAUTHOR')));
            JFactory::getApplication()->close();
        }

        JLoader::register('ProcessHelper', JPATH_COMPONENT.DS.'helpers'.DS.'process.php');

        $result = ProcessHelper::processItinerary($id);

        echo json_encode($result);


        JFactory::getApplication()->close();
    }

}
