<?php
/**
 * @file Amadeus/Controller/Basic.php
 * @ingroup _library
 * Controlador basico, que implementa las funciones comunes en la administracion.
 */
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.application.component.controller' );

/**
 * @brief Clase con las funciones basicas de cualquier controlador
 * de la administracion, como el de publicar y despubklicar entre otras.
 */
class AmadeusController extends JController
{

    /// Nombre del modelo, el cual relaciona basicamente al modelo
    /// que administra la tabla de la base de datos.
    var $_model = '';

    /// Nombre de la vista usada para obtener los campos y validar
    var $_editView = '';

    /**
     * @brief Constructor de la clase.
     */
    function __construct()
    {
        parent::__construct();
        $this->registerTask('unpublish', 'publish');
        $this->registerTask('apply', 'save');
    }

    /**
     * @brief Funcion que despliega el contenido, esta se reescribe
     * en los controladores hijos.
     */
    function display()
    {
        parent::display();
    }

    /**
     * @brief Retorna la URL base para los redireccionamientos. En el caso básico
     * retorna solo la opcion, pero puede retornar mas parametros.
     * @return string
     */
    function getURLBase()
    {
        $option = JRequest::getCmd('option', '');
        return 'index.php?option='.$option;
    }

    /**
     * @brief Publica o despublica varios registros en la base de datos.
     * 
     * El proceso consiste en actualizar el valor del campo \a publisehd,
     * a 1 o 0 según se desee publicar o despublicar.
     */
    function publish()
    {
        $link = JRoute::_($this->getURLBase(), false);

        $model =& $this->getModel($this->_model);

        $cid = JRequest::getVar( 'cid', array(0), '', 'array' );

        jimport('Amadeus.Util.Validation');

        if(!AmadeusUtilValidation::isArrayOf('integer', $cid))
            JError::raiseError( 500, 'ERROR: El arreglo no contiene valores enteros.');

        if($this->_task=='publish')
            $published = array('published' => 1);
        else
            $published = array('published' => 0);

        $conditions = 'id IN ( \''.join('\' , \'', $cid).'\' )';
        $model->update($published, $conditions);

        $this->setRedirect($link, JText::_('UPDATE_SUCCESS'));
    }

    /**
     * @brief Accion que se ejecuta antes de borrar registros en la
     * base de datos.
     * @param array $cid Arreglo de los ID's a borrar
     */
    function beforeRemove($cid)
    {
        return true;
    }

    /**
     * @brief Accion que se ejecuta después de borrar registros en la
     * base de datos.
     */
    function afterRemove()
    {
        return true;
    }

    /**
     * @brief Borra de la base de datos, los registros seleccionados.
     * @return bool
     */
    function remove()
    {
        $link = JRoute::_($this->getURLBase(), false);

        $model =& $this->getModel($this->_model);

        $cid = JRequest::getVar( 'cid', array(0), '', 'array' );

        jimport('Amadeus.Util.Validation');

        if(!AmadeusUtilValidation::isArrayOf('integer', $cid))
            JError::raiseError( 500, 'ERROR: El arreglo no contiene valores enteros.');

        if($this->beforeRemove($cid)===false)
        {
            return false;
        }

        $conditions = 'id IN ( \''.join('\' , \'', $cid).'\' )';
        $model->delete($conditions);

        if($this->afterRemove()===false)
        {
            return false;
        }

        $this->setRedirect($link, JText::_('DELETE_SUCCESS'));

        return true;
    }

    /**
     * @brief Seleciiona la vista a mostrar en el editar, esta funcion debe sobreescribirce
     * por la clase hija.
     */
    function seeViewEdit()
    {
    }

    /**
     * @brief Funcion a ejecutarse antes de realizar las validaciones para guardar el registro.
     *
     * Se recibe por referencia un arreglo con los campos a usar para obtener la informacion
     * y hacer el guardado, si se desea cancelar con el proceso, se debe retornar \a false.
     * @param array $fields Arreglo con los campos usados para el guardado
     * @return bool
     */
    function beforeSave(&$fields)
    {
        return true;
    }

    /**
     * @brief Funcion a ejecutarse despues de realizar el guardado en la base de datos
     *
     * Se recibe el ID del registro guardado , si se desea cancelar con el proceso,
     * se debe retornar \a false; pero el registro guardado ya queda almacenado, tiene que 
     * borrarse manualmente.
     * @param integer $id ID del registro guardado
     * @return bool
     */
    function afterSave($id)
    {
        return true;
    }

    /**
     * @brief Guarda los datos recogidos en la tabla designada, haciendo
     * previemente una validacion.
     * @return bool
     */
    function save()
    {
        JRequest::checkToken() or jexit( 'Invalid Token' );

        $fields = $this->getFields();

        if($this->beforeSave($fields)===false)
        {
            return false;
        }

        if(!$this->runValidations($fields))
        {
            $this->seeViewEdit();
            return false;
        }

        if(!($_row=$this->storeFields($fields)))
        {
            $this->seeViewEdit();
            return false;
        }

        if($this->afterSave($_row)===false)
        {
            return false;
        }

        $msg = JText::_('SAVE_SUCCESS');
        if($this->_task=='apply')
        {
            $link = JRoute::_($this->getURLBase().'&task=edit&cid[]='.$_row->id, false);
        }
        else
        {
            $link = JRoute::_($this->getURLBase(), false);
        }

        $this->setRedirect($link, $msg);

        return true;
    }

    /**
     * @brief Redirecciona al formulario de edicion, para capturar los nuevos
     * datos
     */
    function add()
    {
        $this->seeViewEdit();
    }

    /**
     * @brief Obtiene los campos y sus tipos, para realizar las validaciones.
     * @return array
     */
    function getFields()
    {
        $_fields = array();

        $view = $this->getView($this->_editView, 'html');
        $tmp = $view->getFields(false);

        foreach($tmp as $field)
        {
            if(isset($field['fields']))
            {
                foreach($field['fields'] as $k => $n)
                {
                    $_fields[$k]['label'] = $n['label'];
                    $_fields[$k]['type'] = $n['type'];
                    if($n['type']=='include')
                    {
                        $_fields[$k]['include'] = $n['include'];
                    }
                    if(isset($n['mandatory']) && $n['mandatory']==false)
                    {
                        $_fields[$k]['mandatory'] = $n['mandatory'];
                    }
                }
            }
        }

        return $_fields;
    }

    /**
     * @brief Valida los datos recogidos en el arreglo.
     *
     * La estructura de dicho arreglo es:
     *  + Como llave el nombre del campo a validar.
     *  + Como valor un arreglo con los siguientes campos:
     *    - type    => Indica el tipo de validacion a ejecutar
     *    - include => En caso de ser una validacion tipo include, el rango a tener en cuenta.
     *    - format  => Formato sobre el cual contrarestar
     * @param array $fields Arreglo de campos a validar.
     * @return bool
     */
    function runValidations($fields)
    {
        jimport('Amadeus.Util.Validation');

        $mainframe =& JFactory::getApplication();
        $_success = true;

        foreach($fields as $key => $config)
        {
            $value = JRequest::getVar($key, '', 'POST');
            $isValid = true;

            switch($config['type'])
            {
                case 'integer':
                    $isValid = AmadeusUtilValidation::isInteger($value);
                    break;
                case 'float':
                    $isValid = AmadeusUtilValidation::isFloat($value);
                    break;
                case 'format':
                    $isValid = AmadeusUtilValidation::isFormat($value, $config['format']);
                    break;
                case 'include':
                    $isValid = AmadeusUtilValidation::isOneOfThese($value, $config['include']);
                    break;
                case 'string':
                    $isValid = AmadeusUtilValidation::isString($value);
                    break;
                default:
            }

            $mandatory = true;
            if(isset($config['mandatory']))
                $mandatory = $config['mandatory'];

            if(!$mandatory && ($value==null || $value==''))
                continue;

            if(!$isValid)
            {
                $msg  = JText::_('ERROR_THE_FIELD');
                $msg .= $config['label'];
                $msg .= JText::_('ERROR_'.strtoupper($config['type']));
                $mainframe->enqueueMessage($msg, 'error');
                $_success = false;
            }
        }

        return $_success;
    }

    /**
     * @brief Guarda el valor de los campos especificados por la funcion
     * getFields en la base de datos.
     * @param array $fields Arreglo con el nombre de todos los campos a guardar.
     */
    function storeFields($fields)
    {
        $names = array_keys($fields);
        $data = array();

        foreach($names as $f)
        {
            if(!isset($_POST[$f])) continue;

            if($fields[$f]['type']=='raw')
                $data[$f] = JRequest::getVar($f, '', 'POST', '', JREQUEST_ALLOWHTML);
            else
                $data[$f] = JRequest::getVar($f, '', 'POST');
        }

        $model =& $this->getModel($this->_model);
        $row = $model->save($data);

        return $row;
    }

    /**
     * @brief Redirecciona a la vista de edicion, para editar la informacion y
     * almacenarla
     */
    function edit()
    {
        $cid = JRequest::getVar('cid', array());

        jimport('Amadeus.Util.Validation');

        if(!AmadeusUtilValidation::isArrayOf('integer', $cid))
            JError::raiseError( 500, 'ERROR: El arreglo no contiene valores enteros.');

        $_id = $cid[0];

        $model =& $this->getModel($this->_model);

        $model->setRegisterToPost($_id);

        $this->seeViewEdit();
    }

    /**
     * @brief Codifica un arreglo con formato JSON, para enviarlo al cliente y
     * pueda ser interpretado por el javascript.
     *
     * Esta funcion es sacada de este link http://www.php.net/manual/es/function.json-encode.php#104278,
     * y modificada para las necesidades y requerimientos internos
     */
    function encodeJSON($in) { 
        $out = ""; 
        if (is_object($in)) { 
            $class_vars = get_object_vars(($in)); 
            $arr = array(); 
            foreach ($class_vars as $key => $val) { 
                $arr[$key] = "\"".addcslashes($key, "\v\t\n\r\f\"\\/")."\":\"{$val}\""; 
            } 
            $val = implode(',', $arr); 
            $out .= "{{$val}}"; 
        }elseif (is_array($in)) { 
            $obj = false; 
            $arr = array(); 
            foreach($in AS $key => $val) { 
                if(!is_numeric($key)) { 
                    $obj = true; 
                } 
                $arr[$key] = $this->encodeJSON($val); 
            } 
            if($obj) { 
                foreach($arr AS $key => $val) { 
                    $arr[$key] = "\"".addcslashes($key, "\v\t\n\r\f\"'\\/")."\":{$val}"; 
                } 
                $val = implode(',', $arr); 
                $out .= "{{$val}}"; 
            }else { 
                $val = implode(',', $arr); 
                $out .= "[{$val}]"; 
            } 
        }elseif (is_bool($in)) { 
            $out .= $in ? 'true' : 'false'; 
        }elseif (is_null($in)) { 
            $out .= 'null'; 
        }elseif (is_string($in)) { 
            $out .= "\"".addcslashes($in, "\v\t\n\r\f\"\\/")."\""; 
        }else { 
            $out .= $in; 
        } 
        return "{$out}"; 
    } 

}
