<?php
/**
 * @file Amadeus/Model/Database.php
 * @ingroup _library
 * Modelo base para los modelos que usan listado de datos.
 */
defined('_JEXEC') or die("Invalid access");
jimport('joomla.application.component.model');

/**
 * @brief Clase que manipula los datos basicos generales para todos los modelos.
 */
class AmadeusModelDatabase extends JModel
{

    /// Almacena todos los valores que se van a guardar en la sesion del usuario
    var $_varOfSession = array();

    /// Almacena el nombre de la tabla sobre la cual se apoya el modelo
    var $_table = '';

    /**
     * @brief Constructor del modelo.
     *
     * Recoge y guarda en la sesion los valores de configuracion de los usuarios,
     * como el numero de registros vistos, el orden y filtro, entre otros;
     * usa una llave compuesta por el componente, controlador y valor, para evitar
     * colision de datos.
     */
    function __construct()
    {
        $mainframe =& JFactory::getApplication();

        parent::__construct();

        $context = $this->getContext();

        foreach( $this->_varOfSession as $key => $value)
        {
            $val = $mainframe->getUserStateFromRequest($context.$key, $key, $value);
            $this->setState($key, $val);
        }
    }

    /**
     * @brief Retorna el contexto sobre el cual se alamcena las variables del
     * usuario
     * @return string
     */
    function getContext()
    {
        $option = JRequest::getCmd('option', '');
        return $option;
    }

    /**
     * @brief Inicializa las variables de sesion basica en todas las listas, como el orden,
     * el limete de registros visibles, entre otros.
     */
    function initVariables()
    {
        $mainframe =& JFactory::getApplication();

        $this->_varOfSession = array(
            'filter_order'     => '',
            'filter_order_Dir' => 'asc',
            'limitstart'       => 0,
            'limit'            => $mainframe->getCfg('list_limit'),
            'filter_search'    => ''
        );
    }

    /**
     * @brief Permite asignar nuevas variales para almacenar en la sesion, ademas
     * de las ya guardadas por defecto.
     * @param string $key Nombre de la variable que se va a guardar
     * @param string $value Valor que se usara por defecto
     */
    function setVarOfSession($key, $value = '')
    {
        $this->_varOfSession[$key] = $value;
    }

    /**
     * @brief Obtiene los registros de acuerdo a los parametros actuales del usuario,
     * es decir de cuantos registros ve, el orden y el filtro.
     * return Object
     */
    function getData(){
        $ini = $this->getState('limitstart');
        $fin = $this->getState('limit');
        $ord = $this->getState('filter_order');
        $dir = $this->getState('filter_order_Dir');

        $db =& $this->getDBO();

        $condition = $this->getConditions();

        $orden = $ord.' '.$dir;

        $query = 'SELECT * FROM #__'.$this->_table.' WHERE '.$condition.' ORDER BY '.$orden;

        $db->setQuery($query, $ini, $fin);

        $_data = $db->loadObjectList();

        if ($db->getErrorNum())
            JError::raiseError( 500, 'ERROR SELECT ('.$query.') NUMBER ('.$db->getErrorNum().')');

        return $_data;
    }

    /**
     * @brief Obtiene el nombre de la tabla asociada a este modelo.
     * @return string
     */
    function getTable()
    {
        return $this->_table;
    }

    /**
     * @brief Obtiene las condiciones a aplicar en la consulta.
     * Esta funcion se debe reescribir con las condiciones del desarrollo
     * @return string
     */
    function getConditions()
    {
        return '1 = 1';
    }

    /**
     * @brief Obtiene la cantidad de registros que hay en la base de datos,
     * de acuerdo a una condicion dada.
     * @param string $condition Condicion para realizar la busqueda
     * @return integer
     */
    function getCount($condition = '1 = 1')
    {
        jimport('Amadeus.Util.Database');
        return AmadeusUtilDataBase::countData($this->_table, $condition);
    }

    /**
     * @brief Obtiene el valor del campo \a search, para realizar las busquedas
     * con el valor que digita el usuario
     * @return string
     */
    function getValueOfSearch()
    {
        $search = $this->getState('filter_search');

        if (strpos($search, '"') !== false) {
            $search = str_replace(array('=', '<', '>'), '', $search);
        }
        $search = JString::strtolower($search);

        return $search;
    }

    /**
     * @brief Asigna los valores del registro con el ID suministrado al arreglo POST
     */
    function setRegisterToPost($id)
    {
        jimport('Amadeus.Util.Database');
        AmadeusUtilDataBase::setRegisterToPost($this->_table, $id);
    }

    /**
     * @brief Guarda en la base de datos el arreglo enviado.
     *
     * El arreglo debe ser un arreglo asociativo, donde la clave es el nombre
     * del campo y el valor el valor del campo.
     * @param array $data Datos a ser guardados
     * @return object
     */
    function save($data)
    {
        jimport('Amadeus.Util.Database');
        return AmadeusUtilDataBase::saveData($this->_table, $data);
    }

    /**
     * @brief Actualiza uno, o varios registros de la base de datos, de acuerdo a los
     * parametros dados.
     * @param array $values Arreglo de llave-valor, para actualizar las llaves con los valores.
     * @param string $condition Condiciones para filtrar que registros deben ser actualizados.
     */
    function update($values, $condition)
    {
        jimport('Amadeus.Util.Database');
        AmadeusUtilDataBase::updateData($this->_table, $values, $condition);
    }

    /**
     * @brief Borra los registros coincidentes con la condicion dada, de la
     * tabla especificada.
     * @param string $condition Condiciones para filtrar que registros deben ser borrados.
     */
    function delete($condition)
    {
        jimport('Amadeus.Util.Database');
        AmadeusUtilDataBase::deleteData($this->_table, $condition);
    }

    /**
     * @brief Obtiene un arreglo de objetos conincidente con las condiciones dadas.
     * @param array $fields Arreglo de campos a obtener.
     * @param string $conditions Condiciones para filtrar el resultado de la busqueda.
     * @param string $order Campo por el cual se ordena el resultado.
     * @param bool $first Indica si solo se requiere el primer registro o todos.
     * @return bool/array/object
     */
    function data($fields='*', $conditions='1=1', $order= 'id', $first=false)
    {
        jimport('Amadeus.Util.Database');
        return AmadeusUtilDataBase::getData($this->_table, $fields, $conditions, $order, $first);
    }

}
