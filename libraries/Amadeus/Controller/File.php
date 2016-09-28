<?php
/**
 * @file Amadeus/Controller/File.php
 * @ingroup _library
 * Controlador basico, que implementa las funciones comunes en la administracion,
 * al momento de suvir imagenes al servidor.
 */
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'Amadeus.Controller.Basic' );

/**
 * @brief Clase con las funciones basicas de cualquier controlador
 * de la administracion, como el de publicar y despubklicar entre otras.
 */
class AmadeusControllerFile extends AmadeusController
{

    /// Nombre del archivo manipulado
    var $_file       = null;

    /// Nombres de los campos que almacenan archivos
    var $_fileFields = array();

    /**
     * @brief Convierte una variable de configuracion del PHP, en su
     * cantidad en bytes. Solo aplica para valores numericos
     * @param string $str Cadena de texto que representa el valor a convertir
     * @return integer
     */
    function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }

    /**
     * @brief Accion que se encarga de manipular el proceso de guardar
     * un archivo en el servidor
     * @param array $allowedExtensions Arreglo de extensiones validas para el archivo
     * @param int $sizeLimit Tamaño maximo permitido del fichero que se sube
     */
    function upload($allowedExtensions, $sizeLimit = 10485760)
    {
        jimport( 'Amadeus.Util.Uploader' );

        $media      =& JComponentHelper::getParams( 'com_media' );

        $postSize   = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));
        $mediaSize  = (int)$media->get('upload_maxsize');

        // Se selecciona el minimo tamaño válido para un fichero, de acuerdo
        // a los valores configurados en el php, el joomla y el componente.
        $sizeLimit = min($postSize, $uploadSize, $mediaSize, $sizeLimit);

        // Se alamacena la imagen en la ruta especificada
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload(JPATH_SITE.'/tmp/', true);

        if(!isset($result['error']))
        {
            jimport('Amadeus.Util.Validation');
            $file = $uploader->getName();
            $result = AmadeusUtilValidation::isValidFile($file, $only_image);

            if(isset($result['error']))
            {
                jimport('joomla.filesystem.file');
                if(!JFile::delete($file))
                    $result = array('error' => JText::_('DELETEERROR'));
            }
            else
            {
                $this->_file = $file;
            }
        }

        return $result;
    }

    /**
     * @brief Se encarga de almacenar un archivo de tipo imagen
     * @param string $path Ruta donde se alamcena la imagen
     */
    function uploadImage($sizeLimit = 10485760)
    {
        $allowedExtensions = array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'gif', 'GIF');
        $result = $this->upload($allowedExtensions, $sizeLimit);

        if($this->_file!='')
        {
            $size = getimagesize($this->_file);
            $result['width']  = $size[0];
            $result['height'] = $size[1];
        }

        echo htmlspecialchars($this->encodeJSON($result), ENT_NOQUOTES);
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

        if(!$this->__validateFile($fields))
        {
            $this->seeViewEdit();
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

        if(!$this->__moveFile($_row))
        {
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
     * @brief Valida el archivo, para prevenir problemas.
     *
     * Reviza que el archivo este en en la carpeta temporal (en caso de ser nuevo) o de
     * existir en el arreglo POST. En caso de no cumplir, muestra un error en pantalla.
     * @param array $fields Arreglo con los campos usados para el guardado
     * @return bool
     */
    function __validateFile(&$fields)
    {
        jimport('joomla.filesystem.file');

        foreach($this->_fileFields as $field => $config)
        {
            $mainframe =& JFactory::getApplication();

            $file     = JRequest::getVar($field, '', 'post');
            $folderTmp = JPATH_SITE.'/tmp/';

            if($file != '' && JFile::exists($folderTmp.$file))
            {
                $info     = @getimagesize($folderTmp.DS.$file);

                if(is_array($info))
                {
                    $filename = 'IMG_'.md5(time()).image_type_to_extension($info[2]);
                    if(!JFile::move($folderTmp.$file, $folderTmp.$filename))
                    {
                        $mainframe->enqueueMessage(JText::_('ERROR_COPY_FILE'), 'error');
                        return false;
                    }
                }
                else
                    $filename = $file;
            }
            else
            {
                if($file!='')
                    $filename = $file;
                else
                {
                    $mainframe->enqueueMessage(JText::_('ERROR_EMPTY_FILE'), 'error');
                    return false;
                }
            }

            JRequest::setVar($field, $filename, 'post');
            $fields[$field] = $config;
        }

        return true;
    }

    /**
     * @brief Mueve los archivos de la carpeta temporal a la carpeta destino
     *
     * Este proceso debe ocurrir despues del guardado en la base de datos,
     * para asegurar la integridad de los datos.
     * @param object $record Objeto que representa el registro guardado
     * @return bool
     */
    function __moveFile(&$record)
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        $folder = JPATH_SITE.$this->_folder;
        $tmp = JPATH_SITE.'/tmp/';

        if(!JFolder::exists($folder))
        {
            if(!JFolder::create($folder, 0700))
            {
                $this->setRedirect($this->getURLBase(), JText::_('ERROR_CREATE_FOLDER'), 'error');
                return false;
            }
        }

        foreach($this->_fileFields as $field => $config)
        {
            // Validando que el archivo no exista
            if(!JFile::exists($folder.$record->$field))
            {
                // Moviendo el archivo a la carpeta final
                if(!JFile::move($tmp.$record->$field, $folder.$record->$field))
                {
                    $this->setRedirect($this->getURLBase(), JText::_('ERROR_COPY_FILE'), 'error');
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * @brief Antes de borrar el registro, borra los archivos.
     * @param array $cid Arreglo de los ID's a borrar
     * @param object $model Modelo que se usa para obtener los registros
     * @return bool
     */
    function __removeFile($cid, &$model)
    {
        jimport('joomla.filesystem.file');

        $folder = JPATH_SITE.$this->_folder;

        foreach($cid as $id)
        {
            $record = $model->data("*", $id, 'id', true);

            foreach($this->_fileFields as $field => $config)
            {
                $file = $record->$field;

                if(JFile::exists($folder.$file))
                {
                    if(!JFile::delete($folder.$file))
                    {
                        $this->setRedirect($this->getURLBase(), JText::_('ERROR_DELETE_FILE'), 'error');
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * @brief Borra de la base de datos, los registros seleccionados, admás de sus archivos.
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

        if(!$this->__removeFile($cid, $model))
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
     * @brief Borra los archivos temporales guardados en el directorio tmp/,
     * para ahorrar espacio en disco
     * @return bool
     */
    function deleteTemporary()
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        $exts = array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'gif', 'GIF');
        $folder = JPATH_SITE.'/tmp/';

        foreach(JFolder::files($folder) as $file)
        {
            $ext = JFile::getExt($file);
            if(in_array($ext, $exts))
            {
                if((time() - filemtime($folder.$file)) > 900 )
                {
                    if(!JFile::delete($folder.$file))
                    {
                        $this->setRedirect($this->getURLBase(), JText::_('ERROR_DELETE_TMP'), 'error');
                        return false;
                    }
                }
            }
        }

        return true;
    }

}
