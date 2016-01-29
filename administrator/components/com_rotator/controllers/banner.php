<?php
/**
 * @file com_rotator/admin/controllers/banner.php
 * @ingroup _comp_adm
 * Archivo con la definicion de la clase para administrar los diferentes
 * banners que administra el componente.
 */
defined( '_JEXEC' ) or die( 'Restricted access' ); 

/**
 * @brief Clase para los banners, el cual permite administrar las acciones
 * para procesar el contenido de cada uno de los banners almacenados.
 */
class RotatorControllerBanner extends RotatorController
{

    /// Modelo basico para este controlador.
    var $_model = 'banner';

    /// Vista que contiene los campos a guardar
    var $_editView = 'editbanner';

    /**
     * @brief Constructor de la clase. Se define el nombre del controlador,
     * como valor global, para usarlo en otras clases.
     */
    function __construct()
    {
        parent::__construct();

        $user = JFactory::getUser();
        if(!$user->authorise('core.manage.banner', 'com_rotator'))
            $this->setRedirect(JRoute::_('index.php'), JText::_('COM_ROTATOR_ACCESS_ERROR'), 'error');
    }

    /**
     * @brief Retorna la URL base para los redireccionamientos.
     * @return string
     */
    function getURLBase()
    {
        $controller = JRequest::getCmd('controller');
        return parent::getURLBase().'&controller='.$controller;
    }

    /**
     * @brief Funcion que despliega el contenido, esta se reescribe
     * en los controladores hijos.
     * @return bool
     */
    function display()
    {
        Toolbar::listBanner();
        JRequest::setVar('view', 'banner');

        // Se borra el directorio temporal, para no dejar archivos basura almacenados
        if(!$this->deleteTemporary())
        {
            return false;
        }

        $model =& $this->getModel('banner');

        $view  =& $this->getView('banner', 'html');
        $view->setModel($model, true);

        parent::display();
        return true;
    }

    /**
     * @brief Renderiza la vista de edicion del componente
     * @return bool
     */
    function seeViewEdit()
    {
        $step = JRequest::getVar('step', 'select');
        $task = JRequest::getCmd('task');

        if($step=='select' && $task!='edit')
        {
            Toolbar::selectRotator();
            JRequest::setVar('step', 'selectrotator');
            JRequest::setVar('view', 'selectrotator');
        }
        else
        {
            $model =& $this->getModel('banner');

            $modelrotator =& $this->getModel('rotator');

            $cid = JRequest::getVar('cid', array());

            jimport('Amadeus.Util.Validation');

            if(!AmadeusUtilValidation::isArrayOf('integer', $cid))
                JError::raiseError( 500, 'ERROR: El arreglo no contiene valores enteros.');

            $_id = $cid[0];

            if($task=='add' || (JRequest::getVar('id', 0, 'post')==0 && $step=='error'))
            {
                if($step=='error')
                    $_id = JRequest::getVar('rotator', 0, 'post');

                $data = $modelrotator->data("*", $_id, 'id', true);

                if($data==false)
                {
                    $this->setRedirect($this->getURLBase(), JText::_('ERROR_CID_INVALID'), 'error');
                    return false;
                }

                if($step=='error')
                {
                    $image = JRequest::getVar('image', '', 'post');
                    JRequest::setVar('seeImage', '../tmp/'.$image, 'post');

                    if($data->thumb!='')
                        JRequest::setVar('seeThumb', '../tmp/'.$image, 'post');
                }
            }
            else
            {
                $_id = JRequest::getVar('id', 0, 'post');

                $banner = $model->data("*", $_id, "id", true);
                $data = $modelrotator->data("*", $banner->rotator, 'id', true);

                JRequest::setVar('seeImage', '../media/rotator_img/'.$banner->image, 'post');

                if($banner->thumb!='')
                    JRequest::setVar('seeThumb', '../media/rotator_img/'.$banner->thumb, 'post');
            }

            Toolbar::editBanner();
            JRequest::setVar('view', 'editbanner');
            $step =& $this->getView('editbanner', 'html');
            $step->setModel($model, true);
            $step->assign('task', $task);
            $step->assign('rotator', $data);
        }

        parent::display();

        return true;
    }

    /**
     * @brief Valida que exista un rotador valido, al momento de agregar una imagen.
     */
    function add()
    {
        $step = JRequest::getVar('step', 'select');
        $cid = JRequest::getVar('cid', array());

        jimport('Amadeus.Util.Validation');

        if(!AmadeusUtilValidation::isArrayOf('integer', $cid))
            JError::raiseError( 500, 'ERROR: El arreglo no contiene valores enteros.');

        $id = $cid[0];

        if(!$id && $step=='add')
        {
            $this->setRedirect($this->getURLBase(), JText::_('ERROR_CID_INVALID'), 'error');
        }
        else
        {
            $this->seeViewEdit();
        }
    }

    /**
     * @brief Accion ecargada de manipular el archivo que se esta cargando.
     *
     * Las validaciones se hacen con respecto a las configuraciones provistas
     * por el Joomla en su seccion "Configuracion Multimedia". Si la variable
     * \a only_image se encuentra con un valor verdadero, solo se permitira subir
     * archivos de tipo imagen, en caso contraro, archivos validos.
     */
    function upload()
    {
        $media =& JComponentHelper::getParams( 'com_media' );
        $only_image = JRequest::getVar('only_image', '');

        if($only_image==true)
            $allowedExtensions = explode(',', $media->get('image_extensions'));
        else
            $allowedExtensions = explode(',', $media->get('upload_extensions'));

        /// Se selecciona el menor valor para subir archivos, ya sea el configurado por el
        /// servidor o el configurado en el Joomla
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));
        $sizeLimit = (int)$media->get('upload_maxsize') * 1048576;

        $sizeLimit = min($postSize, $uploadSize, $sizeLimit);

        require_once( JPATH_COMPONENT.DS.'uploader.php' );

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
        }

        echo htmlspecialchars($this->encodeJSON($result), ENT_NOQUOTES);
    }

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
     * @brief Redimensiona la imagen de acuerdo a los parametros configurados.
     *
     * En el proceso se crea una nueva imagen y se borra la imagen original, con el
     * fin de ahorrar espacio en disco. Tambien en el resultado final se retorna el
     * nombre del archivo a ser guardado.
     *
     * En caso de no proporcionar medidas, se redimensionara la imagen
     * original al tamaño necesario.
     *
     * La imagen resultante siempre sera una imagen conservando el formato de enetrada,
     * para ahorrar espacio en disco y mantener la calidad.
     */
    function saveImage()
    {
        $rotator = JRequest::getVar('rotator', '0', 'post', 'int');

        $rotator = $this->valideRotator($rotator);
        if(is_array($rotator))
        {
            echo $this->encodeJSON($rotator);
            return false;
        }

        // Asignando valores para la imagen resultante
        $folderTmp  = JPATH_SITE.'/tmp/';
        $sizeW      = $rotator->width;
        $sizeH      = $rotator->height;
        $pathImage  = $folderTmp.JRequest::getVar('image', '', 'post');
        $nomImg     = 'IMG_'.md5(time());
        $output     = $folderTmp.$nomImg;

        // Obteneinedo las coordenadas de la imagen resultante
        $coor_x = JRequest::getVar('image_x', 0, 'post', 'int');
        $coor_y = JRequest::getVar('image_y', 0, 'post', 'int');
        $dist_w = JRequest::getVar('image_w', 0, 'post', 'int');
        $dist_h = JRequest::getVar('image_h', 0, 'post', 'int');

        $result = $this->createImage($pathImage, $output, 0, 0, $coor_x, $coor_y, $sizeW, $sizeH, $dist_w, $dist_h);
        if(is_array($result))
        {
            echo $this->encodeJSON($result);
            return false;
        }

        jimport('joomla.filesystem.file');
        if(!JFile::delete($pathImage))
        {
            echo $this->encodeJSON(array('error' => JText::_('DELETEERROR')));
            return false;
        }

        echo $this->encodeJSON(array('success' => $nomImg.'.'.$result));
        return true;
    }

    /**
     * @brief Crea una imagen de las medidas dadas de acuerdo a otra.
     *
     * Retorna la extension del archivo guardado
     * @param string $pathImage Ruta de la imagen original
     * @param string $output Ruta donde se guarda la imagen resultante
     * @param integer $cini_x Coordenada X inicial de la nueva imagen
     * @param integer $cini_y Coordenada Y inicial de la nueva imagen
     * @param integer $coor_x Coordenada X de la imagen fuente
     * @param integer $coor_y Coordenada Y de la imagen fuente
     * @param integer $sizeW Ancho de la nueva imagen
     * @param integer $sizeH Alto de la nueva imagen
     * @param integer $dist_w Ancho de la imagen fuente
     * @param integer $dist_h Alto de la imagen fuente
     * @return array/string
     */
    function createImage($pathImage, $output, $cini_x, $cini_y, $coor_x, $coor_y, $sizeW, $sizeH, $dist_w, $dist_h)
    {
        // Valida el archivo
        jimport('joomla.filesystem.file');
        if(!JFile::exists($pathImage))
        {
            return array('error' => JText::_('ERROR_NO_FOUND_FILE'));
        }

        // Obteniendo la informacion de la imagen
        $info = @getimagesize($pathImage);
        if(!$info)
        {
            return array('error' => JText::_('ERROR_NO_INFO'));
        }

        // Tipos de imagen soportadas
        $functionForImage = array(
            IMAGETYPE_JPEG => 'imagecreatefromjpeg',
            IMAGETYPE_PNG  => 'imagecreatefrompng'
        );

        // Validando que la imagen sea de los tipos soportados
        if(!$functionForImage[$info[2]])
        {
            return array('error' => JText::_('ERROR_NO_INFO'));
        }

        // Guardando el tipo de imagen
        $typeImage = $info[2];

        // Comprobando que el PHP tenga las funciones habilitadas
        if(!function_exists($functionForImage[$typeImage]))
        {
            return array('error' => JText::_('ERROR_NO_SUPPORT'));
        }

        // Se crea la imagen a partir del archivo
        $source = $functionForImage[$typeImage]($pathImage);

        // Se crea la imagen donde se va a guardar el resultado
        $destiny = imagecreatetruecolor($sizeW, $sizeH);
        if($typeImage==IMAGETYPE_PNG)
        {
            imagealphablending($destiny, false);
            imagesavealpha($destiny, true);
        }

        // Validando que las coordenadas sean validas, en caso contrario solo
        // se redimensiona la imagen.
        if($dist_w==0)
            $dist_w = $info[0];
        if($dist_h==0)
            $dist_h = $info[1];

        // Redimensionando la imagen
        imagecopyresampled($destiny, $source, $cini_x, $cini_y, $coor_x, $coor_y, $sizeW, $sizeH, $dist_w, $dist_h);

        // Guardando la nueva imagen
        if($typeImage==IMAGETYPE_PNG)
        {
            $ext = 'png';
            imagepng($destiny, $output.'.'.$ext, 8);
        }
        else
        {
            $ext = 'jpg';
            imagejpeg($destiny, $output.'.'.$ext, 90);
        }

        return $ext;
    }

    /**
     * @brief Valida para que en el editar no guarde los datos del ancho,
     * alto y thumb, ya que estos afectan la presentacion de las imagenes y se debe
     * evitar.
     * @param array $fields Campos que se van a validar para guardar en la BD
     * @return bool
     */
    function beforeSave(&$fields)
    {
        $id = JRequest::getVar('id', 0, 'post');

        // Obteneiindo el rotador
        $rotator = JRequest::getVar('rotator', '0', 'post', 'int');
        $rotator = $this->valideRotator($rotator);
        if(is_array($rotator))
        {
            echo $this->encodeJSON($rotator);
            return false;
        }

        // Obteneinedo las coordenadas de la imagen resultante para el Thumbnail
        $coor_x = JRequest::getVar('image_x', 0, 'post', 'int');
        $coor_y = JRequest::getVar('image_y', 0, 'post', 'int');
        $dist_w = JRequest::getVar('image_w', 0, 'post', 'int');
        $dist_h = JRequest::getVar('image_h', 0, 'post', 'int');

        $saveThumb = false;

        if(!$id)
        {
            // Si es nuevo se debe guardar la imagen del banner
            $fields['image'] = array(
                'label' => JText::_('LABELIMAGE'),
                'type'  => 'string'
            );

            $saveThumb = true;
            $folderTmp  = JPATH_SITE.'/tmp/';

        }
        else
        {
            // Si esta editando se debe verificar que se halla modificado el thumbnail
            if($coor_x || $coor_y || $dist_w || $dist_h)
            {
                $saveThumb = true;
                $folderTmp  = JPATH_SITE.'/media/rotator_img/';
            }
        }

        if($rotator->thumb && $saveThumb)
        {
            $fields['thumb'] = array(
                'label' => JText::_('LABELATHUMB'),
                'type'  => 'string'
            );


            // Asignando valores para la imagen resultante
            $sizeW      = $rotator->twidth;
            $sizeH      = $rotator->theight;
            $img        = JRequest::getVar('image', '', 'post');
            $pathImage  = $folderTmp.$img;
            $nomImg     = 'THUMB_'.preg_replace('/^IMG_([^.]*).\w*$/', '$1', $img);
            $ext        = preg_replace('/^IMG_[^.]*.(\w*)$/', '$1', $img);
            $output     = $folderTmp.$nomImg;

            $result = $this->createImage($pathImage, $output, 0, 0, $coor_x, $coor_y, $sizeW, $sizeH, $dist_w, $dist_h);
            if(is_array($result))
            {
                echo $this->encodeJSON($result);
                $this->setRedirect($this->getURLBase(), $result['error'], 'error');
                return false;
            }

            JRequest::setVar('thumb', $nomImg.'.'.$result.'?thumb=true', 'post');
        }

        return true;
    }

    /**
     * @brief Valida que el id dado, represente un rotador
     * @param integer $rotator ID a buscar
     * @return object/array
     */
    function valideRotator($rotator)
    {
        // Validando que llegue un id valido
        if($rotator==0)
        {
            return array('error' => JText::_('ERROR_NO_FOUND_ROTATOR'));
        }

        // Obteniendo el modelo, para validar que exista en la BD
        $model =& $this->getModel('rotator');
        $rotator = $model->data("*", $rotator, 'id', true);
        if($rotator==false)
        {
            return array('error' => JText::_('ERROR_NO_FOUND_ROTATOR'));
        }

        return $rotator;
    }

    /**
     * @brief Mueve los archivos de las imágenes a la carpeta indicada
     *
     * Este proceso debe ocurrir despues del guardado en la base de datos,
     * para asegurar la integridad de los datos.
     * @param object $banner Objeto que representa el registro guardado
     * @return bool
     */
    function afterSave(&$banner)
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        $folder = JPATH_SITE.'/media/rotator_img/';
        $tmp = JPATH_SITE.'/tmp/';

        if(!JFolder::exists($folder))
        {
            if(!JFolder::create($folder, 0700))
            {
                $this->setRedirect($this->getURLBase(), JText::_('ERROR_CREATE_FOLDER'), 'error');
                return false;
            }
        }

        // Validando que la imagen no se encuentre ya almacenada
        if(!JFile::exists($folder.$banner->image))
        {
            // Moviendo la imagen
            if(!JFile::move($tmp.$banner->image, $folder.$banner->image))
            {
                    $this->setRedirect($this->getURLBase(), JText::_('ERROR_COPY_IMAGE'), 'error');
                    return false;
            }

            if($banner->thumb!=''||$banner->thumb!=null)
            {
                // Obteniendo el tumbnail, sin el parametro.
                $thumb = explode('?', $banner->thumb);
                $thumb = $thumb[0];

                // Moviendo el thumbnail
                if(!JFile::move($tmp.$thumb, $folder.$thumb))
                {
                    $this->setRedirect($this->getURLBase(), JText::_('ERROR_COPY_IMAGE'), 'error');
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * @brief Al cancelar una accion, borra las imagenes de los temporales.
     * @return bool
     */
    function deleteTemporary()
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        $exts = array('jpeg', 'JPEG', 'jpg', 'JPG', 'png', 'PNG');
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

    /**
     * @brief Antes de borrar el registro, borra los archivos de las imagenes.
     * @param array $cid Arreglo de los ID's a borrar
     * @return bool
     */
    function beforeRemove($cid)
    {
        jimport('joomla.filesystem.file');

        $folder = JPATH_SITE.'/media/rotator_img/';
        $model =& $this->getModel('banner');

        foreach($cid as $id)
        {
            $banner = $model->data("*", $id, 'id', true);
            $file = $banner->image;

            if(!JFile::delete($folder.$file))
            {
                $this->setRedirect($this->getURLBase(), JText::_('ERROR_DELETE_FILE'), 'error');
            }

            if($banner->thumb!='')
            {
                // Obteniendo el tumbnail, sin el parametro.
                $file = explode('?', $banner->thumb);
                $file = $thumb[0];

                if(!JFile::delete($folder.$file))
                {
                    $this->setRedirect($this->getURLBase(), JText::_('ERROR_DELETE_FILE'), 'error');
                }
            }
        }

        return true;
    }

}
