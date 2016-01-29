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
class AsomModelEditAir extends JModel
{
 
    public function getData($order_id)
    {
        $library = JPATH_COMPONENT.DS.'library';

        // Se registra el directorio, para que dinamicamente cargue las clases necesarias.
        JLoader::registerPrefix('Asom', $library);

        return new AsomClassOrder($order_id);
    }

    public function getUrl()
    {
        $option = JRequest::getCmd('option');
        $task   = JRequest::getCmd('task');

        return 'index.php?option='.$option.'&task='.$task;
    }

    public function getStatuses($id='')
    {
        $db = $this->getDBO();

        $query  = 'SELECT id, name, color ';
        $query .= '  FROM #__aom_statuses '; 
        if ($id!==''){
            if ($id==1) $query .= '  WHERE id in (1,2,6) '; //de Reservado solo puede cambiar a Cancelado y Pago por Confirmar
            if ($id==2) $query .= '  WHERE id in (2,5) '; //de Pago por Confirmar solo puede cambiar a Facturado
            if ($id==3) $query .= '  WHERE id in (3,2,6) '; //de Por Confirmar pago y cupo solo puede cambiar a Pago por Confirmar y Cancelado
            if ($id==4) $query .= '  WHERE id in (4,2,6) '; //de Reservado Por confirmar cupo solo puede cambiar a Pago por Confirmar y Cancelado
            if ($id==5) $query .= '  WHERE id in (5,6) '; //de Facturado solo puede cambiar a Cancelado
            if ($id==6) $query .= '  WHERE id = 6 '; //de Cancelado solo puede cambiar a Cancelado
        }
            
        $db->setQuery($query);

        return $db->loadObjectList();
    }

}
