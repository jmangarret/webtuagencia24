<?php
/**
 * @file com_rotator/admin/views/selectrotator/view.html.php
 * @ingroup _comp_adm
 * Vista para editar los datos del banner
 */
defined( '_JEXEC') or die( 'Restricted access');

jimport( 'Amadeus.View.Edit');

/**
 * @brief Clase que genera la vista de edicion de los rotadores.
 */
class RotatorViewselectRotator extends AmadeusViewEdit
{

    /**
     * @brief Método de entrada para visualizar los campos a editar.
     * @param string $tpl Template a usar, el Joomla usa por defecto \a default
     */
    function display($tpl = null)
    {
        $this->doForm();
    }

    /**
     * @brief Indica la accion a la cual se redirecciona el formulario de
     * la vista
     */
    function getAction()
    {
        $controller = JRequest::getCmd('controller');
        return parent::getAction().'&controller='.$controller;
    }

    /**
     * @brief Obtiene la estructura de la table que va a contener la captura.
     * @return array
     */
    function getStructure()
    {
        $_table = array(
            'width'  => '100%',
            'rows'   => '1',
            'cols'   => '1'
        );

        return $_table;
    }

    /**
     * @brief Obtiene la configuración de los campos a ser desplegados
     * en el formulario.
     * @return array
     */
    function getFields()
    {

        jimport('Amadeus.Util.Html');

        AmadeusUtilHtml::includeResource('/administrator/components/com_rotator/css/fileuploader.css');

        require_once( JPATH_COMPONENT.DS.'helper.php' );

        $content  = '<div style="font-size: 12px; font-weight: bold;padding: 2px 0 10px 20px;">';
        $content .= 'Seleccione un rotador para agregarle m&aacute;s im&aacute;genes o banners:</div>';
        $content .= RotatorHelper::listRotators('index.php?'.$this->getAction().'&task=add&step=add');

        $_fields = array(
            array(
                'title'  => JText::_('ALL_ROTATOR'),
                'html'   => $content
            )
        );

        return $_fields;
    }

}
