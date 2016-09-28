<?php
/**
 * @file com_rotator/admin/controllers/rotator.php
 * @ingroup _comp_adm
 * Archivo con la definicion de la clase para administrar los diferentes
 * rotadores que administra el componente.
 */
defined( '_JEXEC' ) or die( 'Restricted access' ); 

/**
 * @brief Clase del rotador, el cual permite crear diferentes rotadores
 * para después desplegar dentro los módulos en el sitio.
 */
class RotatorControllerRotator extends RotatorController
{

    /// Modelo basico para este controlador.
    var $_model = 'rotator';

    /// Vista que contiene los campos a guardar
    var $_editView = 'editrotator';

    /**
     * @brief Constructor de la clase. Se define el nombre del controlador,
     * para usarlo en otras clases.
     */
    function __construct()
    {
        parent::__construct();

        $user = JFactory::getUser();
        if(!$user->authorise('core.manage.rotator', 'com_rotator'))
            $this->setRedirect(JRoute::_('index.php'), JText::_('COM_ROTATOR_ACCESS_ERROR'), 'error');
    }

    /**
     * @brief Funcion que despliega el contenido, esta se reescribe
     * en los controladores hijos.
     */
    function display()
    {
        Toolbar::listRotator();
        JRequest::setVar('view', 'rotator');

        $model =& $this->getModel('rotator');

        $view  =& $this->getView('rotator', 'html');

        $view->setModel($model, true);

        parent::display();
    }

    /**
     * @brief Renderiza la vista de edicion del componente
     */
    function seeViewEdit()
    {
        Toolbar::editRotator();
        JRequest::setVar('view', 'editrotator');

        $css = JRequest::getVar('css', '', 'post', '', JREQUEST_ALLOWHTML);
        if($css=='')
        {
            jimport('joomla.filesystem.file');
            $css = JFile::read(JPATH_SITE.'/administrator/components/com_rotator/resource/default.txt');
            JRequest::setVar('css', $css, 'post');
        }

        $class = JRequest::getVar('class', '', 'post');
        if($class=='')
        {
            JRequest::setVar('class', 'theme-default', 'post');
        }

        $view =& $this->getView('editrotator', 'html');

        $view->assign('task', $this->_task);

        parent::display();
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

        if($id)
        {
            unset($fields['thumb']);
            unset($fields['width']);
            unset($fields['height']);
            unset($fields['twidth']);
            unset($fields['theight']);
        }
        elseif(JRequest::getVar('thumb', '0', 'post')==0)
        {
            unset($fields['twidth']);
            unset($fields['theight']);
        }

        return true;
    }

    /**
     * @brief Valida que el carrusel a borrar no contenga imágenes.
     * @param array $cid Arreglo ed ID's a borrar.
     * @return bool
     */
    function beforeRemove($cid)
    {
        $model =& $this->getModel();

        $banners =& $this->getModel('banner');

        foreach($cid as $id)
        {
            $count = $banners->getCount('rotator = '.$id);

            if($count != 0)
            {
                $this->setRedirect($this->getURLBase(), JText::_('ERROR_REFERENCES').$count, 'error');
                return false;
            }
        }

        return true;
    }

}
