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
class AsomModelOrders extends BaseModel
{

    protected $_order_field = 'od.fecsis';

    protected $_status = array();

    protected $_origins = array();
 
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
        return array('id','recloc','contact','product_type','fec_ini','fec_fin','filter_status');
    }

    public function getFields()
    {
        return array('us.username, ai.nombre AS airname, od.*, his.fecsis as fecmod ');
    }

    public function getConditions()
    {
        $db     = $this->getDBO();

        // Campo de busqueda
        $where1 = '';
        if ($this->getState('contact')){
            $contact="%".$this->getState('contact')."%";
            $where1 .= 'od.firstname LIKE '.$db->Quote($contact);
        }
        
        if ($this->getState('recloc')){      if ($where1!=''){$where1 .= ' AND '; } $where1 .= 'od.recloc = '.$db->Quote($this->getState('recloc'));}
        if ($this->getState('id'))    {      if ($where1!=''){$where1 .= ' AND '; } $where1 .= 'od.id = '.$db->Quote($this->getState('id'));}
        if ($this->getState('product_type')){if ($where1!=''){$where1 .= ' AND '; } $where1 .= 'od.product_type = '.$db->Quote($this->getState('product_type'));}
        if ($this->getState('recloc')) {     if ($where1!=''){$where1 .= ' AND '; } $where1 .= 'od.recloc = '.$db->Quote($this->getState('recloc'));}
        if ($this->getState('fec_ini')){ 
            if ($where1!=''){$where1 .= ' AND '; } 
            $fecini=$this->getState('fec_ini').' 00:00:00';
            $fecfin=$this->getState('fec_fin').' 23:59:59';            
            $where1 .= 'od.fecsis between '.$db->Quote($fecini).'  and '.$db->Quote($fecfin);
        }

        // Filtro de estado
        $status = $this->getState('filter_status');
        
        if($status !== ''){
            if ($where1!=''){$where1 .= ' AND '; } 
            $where1 .= 'od.status = '.$db->Quote($status);
        }
        
        if ($where1===''){$where1 .= ' 1=1 '; } 
        
        $where1 .= ' AND his.fecsis = (select max(fecsis) from #__aom_history aomh where aomh.order_id=od.id) ';
        return array($where1);
    }

    public function getTables()
    {
        $tables  = '#__aom_orders AS od ';
        $tables .= 'INNER JOIN #__aom_statuses AS st ';
        $tables .= '   ON od.status = st.id ';
        $tables .= ' LEFT JOIN #__qs_airlines AS ai ';
        $tables .= '   ON od.provider = ai.codigo ';
        $tables .= ' LEFT JOIN #__users AS us ';
        $tables .= '   ON od.user_id = us.id';
        $tables .= ' LEFT JOIN #__aom_history AS his ';
        $tables .= '   ON od.id = his.order_id ';

        return $tables;
    }

    public function getStatus($id = '')
    {
        if(count($this->_status) == 0)
        {
            $db = $this->getDBO();

            $query  = 'SELECT id, name, color ';
            $query .= '  FROM #__aom_statuses ';

            $query .= ' ORDER BY name ASC ';

            $db->setQuery($query);
            $data = $db->loadObjectList();

            if($data)
            {
                foreach($data as $info)
                    $this->_status[$info->id] = array(
                        'id'    => $info->id,
                        'name'  => $info->name,
                        'color' => $info->color
                    );
            }
        }

        return $id == '' ? $this->_status : $this->_status[$id];
    }
    public function getFecModificacion($id = '')
    {
        $db = $this->getDBO();

        $query  = 'SELECT max(fecsis) AS fecmod ';
        $query .= 'FROM #__aom_history ';
        $query .= 'WHERE order_id='.$id;
//echo $query;
        $db->setQuery($query);
        $data = $db->loadObjectList();

        return $data[0]->fecmod;
    }

}
