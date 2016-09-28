<?php
/**
 *
 */

defined('_JEXEC') or die("Invalid access");

/**
 *
 */
class LowFaresModelFlights extends BaseModel
{

    protected $_order_field = 'lf.originname';

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
        return array('search', 'filter_categories', 'filter_published');
    }

    public function Fields()
    {
        $fields = array(
            'lf.id',
            'lf.originname',
            'lf.destinyname',
            'lf.offset',
            'lf.departure',
            'lf.duration',
            'lf.value',
            'ct.title AS category',
            'lf.published'
        );

        $this->query->select($fields);
    }

    public function Tables()
    {
        $this->query->from('#__lf_flights AS lf')
                    ->join('left', '#__categories AS ct on lf.category = ct.id');
    }

    public function Conditions()
    {
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
    }

}
