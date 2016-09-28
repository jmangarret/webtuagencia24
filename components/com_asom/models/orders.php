<?php
/**
 * @file com_geoplanes/admin/models/geoplanes.php
 * @ingroup _compadmin
 * Clase que administra los datos relativos a la administración del componente,
 * como el filtrado de registros, las columnas y sus ordenes,
 * cantidad de registros visibles por pantalla, entre otros.
 */

defined('_JEXEC') or die("Invalid access");

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'model.php');

class AsomModelOrders extends BaseModel
{
 
    /// Contiene el tipo de modelo, si es un listado o un detalle
    protected $_type  = 'list';

    public function getFields()
    {
        $fields = array(
            'od.id',
            'od.recloc',
        	'od.product_name',
        	'od.product_type',
            'od.lastname as lastname',
            'od.firstname as firstname',
            'st.name as statusTkt',
        	"DATE_FORMAT(od.fecsis,'%d/%m/%Y')  as fectrans",
            "DATE_FORMAT(MAX(py.fecsis),'%d/%m/%Y')   as fecmod"
        );

        return $fields;
    }

    public function getConditions()
    {
        $db   = $this->getDBO();
        $user = JFactory::getUser();
        //Se colocan los filtros
        $filter='';
        
       if($_POST['search']['order_number']!=''){
     	   	$filter.=' and  od.id='.$_POST['search']['order_number'];
        }
    	if($_POST['search']['record']!=''){
        	$filter.=" and  od.recloc='".$_POST['search']['record']."'";
        }
    	if($_POST['search']['product_state']!=''){
        	$filter.=' and  od.status='.$_POST['search']['product_state'];
        }
                
        if($_POST['search']['from_date']!='' && $_POST['search']['to_date']!='' ){
     	$from= explode('/',$_POST['search']['from_date']);
     	$to=explode('/',$_POST['search']['to_date']);
     	$ffrom= $from[2].'-'.$from[1].'-'.$from[0];
     	$fto=$to[2].'-'.$to[1].'-'.$to[0];
     	
        	 $filter.=" and od.fecsis between '" .$ffrom." 00:00:00' ";
             $filter.=" and   '" .$fto." 23:59:59'";
       
        }else{
        if($_POST['search']['from_date']!=''){
        	$from= explode('/',$_POST['search']['from_date']);
        	$ffrom= $from[2].'-'.$from[1].'-'.$from[0];
        	$filter.="  and   od.fecsis  >= '" .$ffrom." 00:00:00' ";
        }
    	if($_POST['search']['to_date']!=''){
    		$to=explode('/',$_POST['search']['to_date']);
    		$fto=$to[2].'-'.$to[1].'-'.$to[0];
        	$filter.=" and   od.fecsis  <= '" .$fto." 23:59:59'";
        }	
        }
    	if($_POST['search']['product_type']!=''){
        	$filter.=' and  od.product_type='.$_POST['search']['product_type'];
        }
        return array('od.user_id = '.$db->Quote($user->get('id')).$filter);
    }

    public function getGroupBy()
    {
        return 'od.id, od.recloc';
    }

    public function getTables()
    {
        $tables  = '#__aom_orders AS od';
        $tables .= ' INNER JOIN #__aom_statuses AS st';
        $tables .= ' ON st.id = od.status';
        $tables .= ' LEFT JOIN #__aom_history AS py';
        $tables .= ' ON py.order_id = od.id';

        return $tables;
    }

}
