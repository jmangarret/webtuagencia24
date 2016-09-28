<?php
/**
 * @file com_geoplanes/admin/models/geoplanes.php
 * @ingroup _compadmin
 * Clase que administra los datos relativos a la administración del componente,
 * como el filtrado de registros, las columnas y sus ordenes,
 * cantidad de registros visibles por pantalla, entre otros.
 */

defined('_JEXEC') or die("Invalid access");

/**
 * @brief Clase que administra la obtencion de los datos.
 *
 * Esta clase se encarga de administrar tanto los datos relacionados con el
 * componente, como los registros, como los datos relacionados con el usuario
 * como el orden o filtro que tiene actualmente aplicados.
 */
class AsomModelStatuses extends BaseModel
{

    protected $_order_field = 'st.name';

    protected $_order_dir = 'asc';
 
    /**
     * @brief Constructor de la clase, recoge o inicializa y guarda las variables de sesión del
     * usuario, como el filtro, entre otras
     */
    function __construct()
    {
        parent::__construct();
    }

    public function getFilters()
    {
        return array('search');
    }

    public function getFields()
    {
        return array('*');
    }

    public function getConditions()
    {
        $db     = $this->getDBO();
        $search = '%'.$this->getState('search').'%';

        $where  = '(';
        $where .= 'st.name LIKE '.$db->Quote($search).' OR ';
        $where .= 'st.color LIKE '.$db->Quote($search);
        $where .= ')';

        return array($where);
    }

    public function getTables()
    {
        return '#__aom_statuses AS st';
    }

}
