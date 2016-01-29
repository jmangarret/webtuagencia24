<?php
/**
 * @file Amadeus/Utils/Html.php
 * @ingroup _library
 * Archivo con utilidades para generar codigo HTML
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * @brief Genera estructuras HTML de una manera sencilla
 */
class AmadeusUtilHtml
{

    /**
     * @brief Construye las propiedades de cualquier elemento, usando un arreglo
     * de clave valor.
     *
     * Retorna un string de la forma " key='valor'", segun el numero de elementos
     * enviados.
     * @param array $config Configuraciones a transformar
     * @return string
     */
    function _getAttributesHTML($config)
    {
        $code = '';

        foreach($config as $key => $value)
            $code .= " $key='$value'";

        return $code;
    }

    /**
     * @brief Retorna el ID del campo configurado, ademas de modificar directamente
     * el arreglo de configuraciones pasado como parametro.
     * @param string $name Nombre a colocar en el id en caso de no proveer uno.
     * @param array $config Arreglo pasado por referencia y el cual se usa para extraer la informacion
     * @return string
     */
    function _getID($name, &$config)
    {
        if(isset($config['id']))
        {
            $_id = $config['id'];
            unset($config['id']);
        }
        else
        {
            $_id = $name;
        }

        return $_id;
    }

    /**
     * @brief Retorna el valor del campo.
     *
     * Por defecto busca en el arreglo $_REQUEST, para obtener el valor,
     * en segunda instancia busca en los valores dados, pero este comportamineto
     * se puede cambiar, enviando en el arreglo de configuración el parametro
     * \a useRequestValue a false.
     * @param string $name Nombre para buscar y obtener el valor
     * @param array $config Arreglo pasado por referencia y el cual se usa para extraer la informacion
     * @return string|mixed
     */
    function _getValue($name, &$config)
    {
        $_value = '';

        $useRequestValue = true;
        if(isset($config['useRequestValue']))
        {
            $useRequestValue = $config['useRequestValue'];
            unset($config['useRequestValue']);
        }

        if($useRequestValue)
        {
            if(isset($config['raw']))
            {
                if($config['raw'])
                    $_value = JRequest::getVar($name, '', 'POST', '', JREQUEST_ALLOWHTML);
                unset($config['raw']);
            }
            else
                $_value = JRequest::getVar($name, '', 'POST');
        }

        if(!$useRequestValue || ($useRequestValue && $_value==''))
        {
            if(isset($config['value']))
            {
                $_value = $config['value'];
                unset($config['value']);
            }
        }

        return $_value;
    }

    /**
     * @brief Valida e incluye la existencia de un recurso como javascript o css,
     * para incluirlo en la pagina.
     *
     * La formade validar la existencia del script, es comprobar que el nombre del
     * archivo no se encuentre ya entre los archivos a incluir. De acuerdo al tipo
     * de extension, se incluye el archivo, \a .css para hojas de estilo, y \a .js
     * para archivos javascript.
     *
     * La ubicacion del recurso debe ser solo la ruta desde la carpeta del sitio,
     * sin incluir la carpeta. Por ejemplo si voy a incluir el archivo que esta en
     * \a media, del sitio Joomla, uso AmadeusUtilHtml::includeResource('/media/archivo.js'),
     * sin nombrar la carpeta Joomla.
     * @param string $resource Ubicacion del recurso a incluir.
     */
    function includeResource($resource)
    {
        $document =& JFactory::getDocument();
        $headerstuff = $document->getHeadData();
        reset($headerstuff['scripts']);

        $include  = true;
        $name = end(preg_split('/\//', $resource));
        $ext = end(preg_split('/\./', $name));
        $index = $ext == 'js' ? 'scripts' : 'styleSheets';

        foreach($headerstuff[$index] as $key => $value)
        {
            if (preg_match("/\/".$name."$/", $key))
                $include = false;
        }

        if($include)
        {
            if($ext=='js')
                $document->addScript(JURI::root(true).$resource);
            else
                $document->addStyleSheet(JURI::root(true).$resource);
        }
    }

    /**
     * @brief Construye un elemento select en HTML.
     *
     * Permite en el arreglo $config, usar cualquier atributo
     * comun en un select, como value, id, class, onclick entre otros.
     * @param string $name Nombre del elemento, por defecto usa este para el `id` y `name`
     * @param array $options Valores disponibles en el select las llaves son los valores verdaderos
     * @param array $config Valores de configuración
     * @return string
     */
    function select($name, $options=array(), $config=array())
    {
        $_value = self::_getValue($name, $config);
        $_id = self::_getID($name, $config);

        $tmp = '';
        foreach($options as $key => $value)
        {
            $tmp .= "<option value='$key'";
            if($_value==$key && $_value!=='')
                $tmp .= " selected";
            $tmp .= " >$value</option>";
        }

        $code = "<select name='$name' id='$_id'";
        $code .= self::_getAttributesHTML($config);
        $code .= ">$tmp</select>";

        return $code;
    }

    /**
     * @brief Construye un elemento input[type=text], para capturar información
     *
     * Recibe el nombre del element, además de los parametros de configuración.
     * @param string $name Nombre del elemento, por defecto usa este para el `id` y `name`
     * @param array $config Valores de configuración
     * @return string
     */
    function inputText($name, $config = array())
    {
        $_value = self::_getValue($name, $config);
        $_id = self::_getID($name, $config);

        $code = "<input type='text' name='$name' id='$_id' value='$_value'";
        $code .= self::_getAttributesHTML($config);
        $code .= " />";

        return $code;
    }

    /**
     * @brief Construye un elemento input[type=hidden], para almacenar información
     *
     * Recibe el nombre del element, además de los parametros de configuración.
     * @param string $name Nombre del elemento, por defecto usa este para el `id` y `name`
     * @param array $config Valores de configuración
     * @return string
     */
    function inputHidden($name, $config = array())
    {
        $_value = self::_getValue($name, $config);
        $_id = self::_getID($name, $config);

        $code = "<input type='hidden' name='$name' id='$_id' value='$_value'";
        $code .= self::_getAttributesHTML($config);
        $code .= " />";

        return $code;
    }

    /**
     * @brief Construye un elemento del tipo radio button, para los formularios
     *
     * Ademas del nombre del campo, también recibe parametros de configuración del campo
     * @param string $name Nombre del elemento, por defecto usa este para el `id` y `name`
     * @param array $config Valores de configuración
     * @return string
     */
    function radioButton($name, $options, $config = array())
    {
        $_value = self::_getValue($name, $config);
        $_id = self::_getID($name, $config);

        $code = '<fieldset class="radio">';
        $i = 1;
        foreach($options as $key => $value)
        {
            $checked = '';
            if($_value==$key && $_value!=='')
                $checked .= "checked='on' ";
            $code .= "<input type='radio' name='$name' id='$_id-$i' value='$key' $checked";
            $code .= self::_getAttributesHTML($config);
            $code .= " /><label for='$_id-$i'>".$value."</label>";
            $i++;
        }
        $code .= '</fieldset>';

        return $code;
    }

    /**
     * @brief Construye un campo tipo text area, para ingresa contenido.
     *
     * Solo se usa el nombre y demas configuraciones, se envian por medio de un arreglo
     * @param string $name Nombre del elemento, por defecto usa este para el `id` y `name`
     * @param array $config Valores de configuración
     */
    function inputArea($name, $config = array())
    {
        $_value = self::_getValue($name, $config);
        $_id = self::_getID($name, $config);

        $code = "<textarea name='$name' id='$_id'";
        $code .= self::_getAttributesHTML($config);
        $code .= ">$_value</textarea>";

        return $code;
    }

    /**
     * @brief Construye un elemento del tipo check button, para los formularios
     *
     * Ademas del nombre del campo, también recibe parametros de configuración del campo
     * @param string $name Nombre del elemento, por defecto usa este para el `id` y `name`
     * @param array $config Valores de configuración
     * @return string
     */
    function checkButton($name, $options, $config = array())
    {
        $_value = self::_getValue($name, $config);
        $_id = self::_getID($name, $config);

        $code = '';
        $i = 1;
        foreach($options as $key => $value)
        {
            $checked = '';
            if($_value==$key && $_value!=='')
                $checked .= "checked='on' ";
            $code .= "<input type='checkbox' name='$name' id='$_id-$i' value='$key' $checked";
            $code .= self::_getAttributesHTML($config);
            $code .= " /><label for='$_id-$i'>".$value."</label>";
            $i++;
        }

        return $code;
    }

    /**
     * @brief Construye un area que permite en navegadores modernos arrastrar y
     * subir archivos al servidor, en caso de utilizar un navegador no compatible,
     * usa el clasico boton.
     *
     * @param string $name Nombre del elemento (div que se activará)
     */
    function uploadFile($name, $config=array())
    {
        $options = array(
            'action' => 'upload',
            'params' => '{"format": "raw"}',
            'allowedExtensions' => array('txt', 'TXT'),
            'onComplete' => 'function(){}'
        );

        $options = array_merge($options, $config);

        $code = '';
        self::includeResource('/media/system/js/fileuploader.js');

        $code .= '<div class="img-pointer">';
        $code .= '<div id="'.$name.'-0">';
        $code .= '</div></div>';

        $inputConfig = array();
        foreach($options as $key => $value)
        {
            if(!in_array($key, array('action', 'params', 'allowedExtensions', 'onComplete')))
                $inputConfig[$key] = $value;
        }

        $code .= self::inputHidden($name, $inputConfig);

        $document =& JFactory::getDocument();
        $_value   = self::_getValue($name, $config);

        if($_value=='')
        {
            $script  = 'jQuery(document).ready(function(){';
            $script .= ' new qq.FileUploader({';
            $script .= '  multiple: false,';
            $script .= '  allowedExtensions: ["'.join('", "', $options['allowedExtensions']).'"],';
            $script .= '  inputname: "'.$name.'",';
            $script .= '  element: jQuery("#'.$name.'-0")[0],';
            $script .= '  action: "'.$options['action'].'",';
            $script .= '  params: '.$options['params'].',';
            $script .= '  messages: {';
            $script .= '    typeError: "'.JText::_('TYPE_ERROR').'",';
            $script .= '    sizeError: "'.JText::_('SIZE_ERROR').'",';
            $script .= '    minSizeError: "'.JText::_('MIN_SIZE_ERROR').'",';
            $script .= '    emptyError: "'.JText::_('EMPTY_ERROR').'",';
            $script .= '    onLeave: "'.JText::_('ON_LEAVE').'"';
            $script .= '  },';
            $script .= '  onComplete: '.$options['onComplete'].',';
            $script .= '  lbdroparea: "'.JText::_('DROP_AREA').'",';
            $script .= '  lbbutton: "'.JText::_('UPLOAD_BUTTON').'"';
            $script .= ' });';
            $script .= '});';
        }
        else
        {
            $script  = 'jQuery(document).ready(function(){';
            $script .= '  var response = {success: true};';
            $script .= '  ('.$options['onComplete'].')(0, "'.$_value.'", response);';
            $script .= '});';
        }

        $document->addScriptDeclaration($script);

        return $code;
    }

    /**
     * @brief Construye un area que permite subir solo imagenes al servidor,
     * se basa en el uploadFile
     *
     * @param string $name Nombre del elemento
     */
    function uploadImage($name, $config=array())
    {
        $options = array(
            'allowedExtensions' => array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'gif', 'GIF'),
            'params' => '{"task": "uploadImage", "format": "raw"}'
        );

        $options = array_merge($options, $config);

        return self::uploadFile($name, $options);
    }

}
