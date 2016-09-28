<?php
/**
 *
 */

defined('_JEXEC') or die("Invalid access");

/**
 *
 */
class FeesModelGroups extends BaseModel
{

    protected $_order_field = 'g.title';

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
            'fg.id',
            'g.title',
            'fg.discount',
            'fg.feetype',
            'fg.fee'
        );

        $this->query->select($fields);
    }

    public function Tables()
    {
        $this->query->from('#__fee_groups AS fg')
                    ->join('inner', '#__usergroups AS g on fg.usergroupid = g.id');
    }

    public function Conditions()
    {
        
        $db = JFactory::getDBO();

        // Caja de busqueda
        $search = $db->Quote('%'.$this->getState('search').'%');
        $parts  = array(
            'g.title LIKE '.$search
        );
        
        $this->query->where('('.join(' OR ', $parts).')');

        /*
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
