<?php
/**
 * @file com_geoplanes/admin/models/geoplanes.php
 * @ingroup _compadmin
 * Clase que administra los datos relativos a la administración del componente,
 * como el filtrado de registros, las columnas y sus ordenes,
 * cantidad de registros visibles por pantalla, entre otros.
 */

defined('_JEXEC') or die("Invalid access");

jimport('joomla.application.component.model');

/**
 * @brief Clase que administra la obtencion de los datos.
 *
 * Esta clase se encarga de administrar tanto los datos relacionados con el
 * componente, como los registros, como los datos relacionados con el usuario
 * como el orden o filtro que tiene actualmente aplicados.
 */
class BaseModel extends JModel
{
    // Constante para usar group by
    const USING_NONE     = 0;

    // Constante para usar group by
    const USING_GROUP_BY = 1;

    // Constante para usar order by
    const USING_ORDER_BY = 2;


    /// Almacena la cantidad de registros
    private $_count = null;

    /// Almacena los registros, de acuerdo a las condiciones dadas
    private $_data  = null;

    /// Contiene el tipo de modelo, si es un listado o un detalle
    protected $_type  = 'list';

    /// Corresponde al campo por el cual se ordenara por defecto
    protected $_order_field = 'od.fecsis';

    /// Indica la direccion de ordenameinto por defecto
    protected $_order_dir   = 'desc';

    /// Indica el nombre del modelo, para uso interno
    private $_model = null;

    // Indica si se usa group y order by
    private $_query_stament = 3;

    /**
     * @brief Constructor de la clase, recoge o inicializa y guarda las variables de sesión del
     * usuario, como el filtro, entre otras
     */
    public function __construct()
    {
        parent::__construct();

        if($this->_type == 'list')
        {
            $mainframe  = JFactory::getApplication();
            $option     = JRequest::getCmd('option');
            $model      = strtolower(substr(get_class($this), strlen('AsomModel')));

            $filter_order     = $mainframe->getUserStateFromRequest($option.'.'.$model.'.'.'filter_order', 'filter_order', $this->_order_field, 'string');
            $filter_order_Dir = $mainframe->getUserStateFromRequest($option.'.'.$model.'.'.'filter_order_Dir', 'filter_order_Dir', $this->_order_dir, 'word');
            $limitstart       = $mainframe->getUserStateFromRequest($option.'.'.$model.'.'.'limitstart', 'limitstart', 0, 'int');
            $limit            = $mainframe->getUserStateFromRequest($option.'.'.$model.'.'.'limit', 'limit', $mainframe->getCfg('list_limit'), 'int');

            $this->setState('filter_order'     , $filter_order);
            $this->setState('filter_order_Dir' , $filter_order_Dir);
            $this->setState('limitstart'       , $limitstart);
            $this->setState('limit'            , $limit);

            foreach($this->getFilters() as $filter)
            {
                $data = $mainframe->getUserStateFromRequest($option.'.'.$model.'.'.$filter, $filter, '', 'string');
                $this->setState($filter, $data);
            }

            $this->_model = $model;
        }
    }

    private function _getQuery($fields)
    {
        $filter_order     = $this->_type == 'list' ? $this->getState('filter_order') : '';
        $filter_order_Dir = $this->_type == 'list' ? $this->getState('filter_order_Dir') : '';

        $tables = $this->getTables();
        $cond   = $this->getConditions();

        $group  = ($this->_query_stament & self::USING_GROUP_BY) ? $this->getGroupBy() : '';

        if(count($fields)>0)
            $fields = join($fields, ', ');
        else
            $fields = '*';

        $ord = $filter_order.' '.$filter_order_Dir;

        $query  = 'SELECT '.$fields;
        $query .= '  FROM '.$tables;
        $query .= ' WHERE '.$cond[0];

        if($group != '')
            $query .= ' GROUP BY '.$group;
        

        if($ord != ' ')
            $query .= ' ORDER BY '.$ord;

        return $query;
    }

    /**
     * @brief Obtiene un arreglo, con los objetos coincidentes de acuerdo al filtrado.
     * @return array|bool
     */
    public function getData($force = false)
    {
        if(!$this->_data || $force)
        {
            $ini              = $this->_type == 'list' ? $this->getState('limitstart') : 0;
            $fin              = $this->_type == 'list' ? $this->getState('limit') : 0;

            $db     = $this->getDBO();
            $query  = $this->_getQuery($this->getFields());

            $db->setQuery($query, $ini, $fin);
//echo $query.'<br>';
            if($this->_type == 'list')
                $this->_data = $db->loadObjectList();
            else
                $this->_data = $db->loadObject();

            if ($db->getErrorNum()) {
                echo $db->stderr();
                return false;
            }
        }

        return $this->_data;
    }

    /**
     * @brief Retorna el numero de registros de acuerdo
     * a la consicion dada
     * @param string $condition Cadena de texto que representa la condicion
     * @return integer
     */
    public function getTotal($force = false)
    {
        if(!$this->_count || $force)
        {
            $db     = $this->getDBO();
            $this->setMode(self::USING_NONE);
            $query = $this->_getQuery(array('COUNT(1)'));
//echo $query;
            $db->setQuery($query);

            $this->_count = $db->loadResult();
        }

        return $this->_count;
    }

    public function getPagination()
    {
        jimport('joomla.html.pagination');

        $total       = $this->getTotal();
        $limitstart  = $this->getState('limitstart');
        $limit       = $this->getState('limit');

        return new JPagination($total, $limitstart, $limit);
    }

    public function getUrl($task = '', $params = array())
    {
        $option = JRequest::getCmd('option');

        if($task != '')
            $task = '&task='.$task;
        else
            $task = '&task='.$this->_model.'.display';

        $extra = '';
        foreach($params as $param => $value)
            $extra = '&'.$param.'='.$value;

        return 'index.php?option='.$option.$task.$extra;
    }

    public function getConditions()
    {
        return array();
    }

    public function getFields()
    {
        return array();
    }

    public function getTables()
    {
        return '';
    }

    public function getGroupBy()
    {
        return '';
    }

    public function getFilters()
    {
        return array();
    }

    protected function setMode($mode)
    {
        $this->_query_stament = $mode;
    }

}
