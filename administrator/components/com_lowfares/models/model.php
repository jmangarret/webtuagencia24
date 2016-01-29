<?php
/**
 *
 */

defined('_JEXEC') or die("Invalid access");


/**
 *
 */
class BaseModel extends JModel
{

    /// Prefijo usado por el modelo
    private $_model_prefix = 'LowFaresModel';

    /// Almacena la cantidad de registros
    private $_count        = null;

    /// Almacena los registros, de acuerdo a las condiciones dadas
    private $_data         = null;

    /// Indica el nombre del modelo, para uso interno
    private $_model        = null;

    /// Contiene el tipo de modelo, si es un listado o un detalle
    protected $_type       = 'list';

    /// Almacena el objeto query, para que las subclases lo puedan usar
    protected $query       = null;

    /**
     * @brief Constructor de la clase, recoge o inicializa y guarda las variables de sesión del
     * usuario, como el filtro, entre otras
     */
    public function __construct()
    {
        parent::__construct();

        $db          = JFactory::getDBO();
        $this->query = $db->getQuery(true);

        if($this->_type == 'list')
        {
            $mainframe  = JFactory::getApplication();
            $option     = JRequest::getCmd('option');
            $model      = strtolower(substr(get_class($this), strlen($this->_model_prefix)));

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

    private function _getQuery()
    {
        $this->query->clear();

        $filter_order     = $this->_type == 'list' ? $this->getState('filter_order') : '';
        $filter_order_Dir = $this->_type == 'list' ? $this->getState('filter_order_Dir') : '';

        $this->Fields();       // Se encarga de seleccionar los campos a listar (SELECT)
        $this->Tables();       // Selecciona las tablas involucradas en el select (FROM, JOIN)
        $this->Conditions();   // Coloca las condiciones por las cuales filtra (WHERE)
        $this->Group();        // Indica los campos sobre los cuales se agrupa el select (GROUP, HAVING)

        // La seccion ORDER la maneja el modelo, ya que es este el que tiene la informacion para ordenar
        $order = $filter_order.' '.$filter_order_Dir;
        if($order != '')
        {
            $this->query->order($order);
        }
    }

    /**
     * @brief Obtiene un arreglo, con los objetos coincidentes de acuerdo al filtrado.
     * @return array|bool
     */
    public function getData($force = false)
    {
        if(!$this->_data || $force)
        {
            $ini = $this->_type == 'list' ? $this->getState('limitstart') : 0;
            $fin = $this->_type == 'list' ? $this->getState('limit') : 0;

            $db = $this->getDBO();

            $this->_getQuery();

            $db->setQuery($this->query, $ini, $fin);

            if($this->_type == 'list')
            {
                $this->_data = $db->loadObjectList();
            }
            else
            {
                $this->_data = $db->loadObject();
            }

            if ($db->getErrorNum())
            {
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
            $db = $this->getDBO();

            $this->_getQuery();

            $query = clone $this->query;
            $query->clear('select');
            $query->select('COUNT(1)');

            if($query->group != NULL)
            {
                $query->select($query->group);
            }

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

    public function Conditions()
    {
    }

    public function Fields()
    {
    }

    public function Tables()
    {
    }

    public function Group()
    {
    }

    public function getFilters()
    {
        return array();
    }

}
