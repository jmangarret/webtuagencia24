<?php
/**
 * @file Amadeus/Utils/Database.php
 * @ingroup _library
 * Archivo con utilidades para Realizar procesos en la base de datos
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * @brief Genera estructuras HTML de una manera sencilla
 */
class AmadeusUtilDataBase
{

    /**
     * @brief Obtiene un arreglo de objetos conincidente con las condiciones dadas.
     * @param string $table Nombre de la tabla a obtener los datos.
     * @param array $fields Arreglo de campos a obtener.
     * @param string $conditions Condiciones para filtrar el resultado de la busqueda.
     * @param string $order Campo por el cual se ordena el resultado.
     * @param bool $first Indica si solo se requiere el primer registro o todos.
     * @return bool/array/object
     */
    function getData($table, $fields='*', $conditions='1=1', $order= 'id', $first=false)
    {
        $db =& JFactory::getDBO();

        $query = 'SELECT ';
        $query .= $fields;
        $query .= ' FROM #__'.$table;

        if(preg_match('/^\d+$/', $conditions))
            $conditions = 'id = '.$conditions;

        $query .= ' WHERE '.$conditions;
        $query .= ' ORDER BY '.$order;

        $db->setQuery($query);

        $_data = $db->loadObjectList();

        if(count($_data)==0)
            return false;
        elseif($first)
            return $_data[0];
        else
            return $_data;
    }

    /**
     * @brief Actualiza uno, o varios registros de la base de datos, de acuerdo a los
     * parametros dados.
     * @param string $table Nombre de la tabla a actualizar.
     * @param array $values Arreglo de llave-valor, para actualizar las llaves con los valores.
     * @param string $condition Condiciones para filtrar que registros deben ser actualizados.
     */
    function updateData($table, $values, $condition)
    {
        $db =& JFactory::getDBO();

        $data = array();
        foreach($values as $k => $v){
            $data[] = $k.' = '.$db->Quote($v);
        }

        $sql  = 'UPDATE #__'.$table;
        $sql .= '   SET '.join(', ', $data);
        $sql .= ' WHERE '.$condition;

        $db->setQuery($sql);
        $db->query();

        if ($db->getErrorNum())
            JError::raiseError( 500, 'ERROR: No se pudo actualizar en la base de datos ('.$db->getErrorNum().')');
    }

    /**
     * @brief Borra los registros coincidentes con la condicion dada, de la
     * tabla especificada.
     * @param string $table Nombre de la tabla para borrar registros.
     * @param string $condition Condiciones para filtrar que registros deben ser borrados.
     */
    function deleteData($table, $condition)
    {
        $db =& JFactory::getDBO();

        $sql  = 'DELETE FROM #__'.$table;
        $sql .= ' WHERE '.$condition;

        $db->setQuery($sql);
        $db->query();

        if ($db->getErrorNum())
            JError::raiseError( 500, 'ERROR: No se pudo borrar en la base de datos ('.$db->getErrorNum().')');
    }

    /**
     * @brief Guarda en la base de datos el arreglo enviado.
     *
     * El arreglo debe ser un arreglo asociativo, donde la clave es el nombre
     * del campo y el valor el valor del campo.
     * @param string $table Nombre de la tabla para guardar los registros
     * @param array $data Datos a ser guardados
     * @return object
     */
    function saveData($table, $data)
    {
        $row =& JTable::getInstance($table);

        if(!$row->save($data))
            JError::raiseError( 500, 'ERROR: No se pudo guardar los datos de la tabla "'.$table.'" ('.$row->getError().')');

        $row->load($row->id);

        return $row;
    }

    /**
     * @brief Obtiene la cantidad de registros que hay en la base de datos,
     * de acuerdo a una condicion dada.
     * @param string $table Nombre de la tabla para guardar los registros
     * @param string $condition Condicion para realizar la busqueda
     * @return integer
     */
    function countData($table, $condition = '1 = 1')
    {
        $query = 'SELECT COUNT(*) FROM #__'.$table.' WHERE '.$condition;
        $db =& JFactory::getDBO();
        $db->setQuery($query);

        return $db->loadResult();
    }

    /**
     * @brief Asigna los valores del registro con el ID suministrado al arreglo POST
     * @param string $table Nombre de la tabla para guardar los registros
     * @param integer $id ID que se desea pasar al arreglo POST
     */
    function setRegisterToPost($table, $id)
    {
        $row =& JTable::getInstance($table);
        $row->load($id);
        
        foreach($row->getProperties() as $k => $v)
        {
            JRequest::setVar($k, $v, 'POST');
        }
    }

}
