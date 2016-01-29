<?php
/**
 *
 */

defined('_JEXEC') or die("Invalid access");

/**
 *
 */
class FeesModelAdminFares extends BaseModel
{

    protected $_order_field = 'af.airline';

    protected $_order_dir = 'asc';
 
    /**
     *
     */
    function __construct()
    {
        parent::__construct();
    }

    public function getFilters()
    {
        return array('search');
    }

    public function Fields()
    {
        $fields = array(
            'af.id',
            'af.airline',
            'a.nombre',
            'af.valuetype',
            'af.published'
        );

        $this->query->select($fields);
    }

    public function Tables()
    {
        $this->query->from('#__fee_adminfare AS af')
                    ->join('inner', '#__qs_airlines AS a ON a.codigo = af.airline');
    }

    public function Conditions()
    {
    	
    	$db = JFactory::getDBO();

        // Caja de busqueda
        $search = $db->Quote('%'.$this->getState('search').'%');
        $parts  = array(
            'af.airline LIKE '.$search,
            'a.nombre LIKE '.$search
        );
      
        $this->query->where('('.join(' OR ', $parts).')');
    	
        /*
        $db = JFactory::getDBO();

        // Caja de busqueda
        $search = $db->Quote('%'.$this->getState('search').'%');
        $parts  = array(
            'lf.originname LIKE '.$search,
            'lf.destinyname LIKE '.$search,
            'ct.title LIKE '.$search,
            'lf.value LIKE '.$search
        );
        $this->query->where('('.join(' OR ', $parts).')');

        //Filtro de categorias
        $category = (int)$this->getState('filter_categories');
        if($category > 0)
        {
            $this->query->where('ct.id = '.$db->Quote($category));
        }

        //Filtro de publicado o no
        $published = $this->getState('filter_published');
        if($published != '')
        {
            $this->query->where('lf.published = '.$db->Quote($published));
        }
*/
    }

}
