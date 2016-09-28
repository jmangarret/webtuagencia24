<?php
/**
 * @file com_rotator/admin/views/rotator/view.html.php
 * @ingroup _comp_adm
 * Vista para la visualización de los rotadores almacenados en el sistema.
 */
defined( '_JEXEC') or die( 'Restricted access');

jimport( 'Amadeus.View.List');

/**
 * @brief Clase que genera la vista de visualización de rotadores.
 */
class RotatorViewRotator extends AmadeusViewList
{

    /**
     * @brief Administra los elementos y datos a mostrar en la vista
     * @param string $tpl Template a usar, el Joomla usa por defecto \a default
     */
    function display($tpl = null)
    {
        $this->doList();
    }

    /**
     * @brief Retorna los campos que deben ir en la cabecera de la tabla
     * @return array
     */
    function getHeaders()
    {
        $fields = array(
            'nombre' => array(
                'legend' => JText::_('NAME_OF_CARRUSEL'),
                'order'  => true,
                'edit'   => true
            ),
            'width'  => array(
                'size'   => '100',
                'legend' => JText::_('WIDTH'),
                'order'  => true,
                'align'  => 'right'
            ),
            'height' => array(
                'size'   => '100',
                'legend' => JText::_('HEIGHT'),
                'order'  => true,
                'align'  => 'right'
            ),
            'class'  => array(
                'size'   => '150',
                'legend' => JText::_('CLASS'),
                'order'  => true
            ),
            'thumb'  => array(
                'size'   => '100',
                'legend' => JText::_('USE_THUMB'),
                'order'  => true
            ),
            'published'  => array(
                'size'   => '70',
                'legend' => JText::_('STATUS'),
                'order'  => true
            )
        );
        return $fields;
    }

    /**
     * @brief Obtiene los campos ocultos que se dese
     * @return string
     */
    function getFieldsHidden()
    {
        $controller = JRequest::getCmd('controller');
        return '<input type="hidden" name="controller" value="'.$controller.'" />';
    }

    /**
     * @brief Manipula la presentacion del campo de Thubnail
     * return string
     */
    function getThumb($val)
    {
        return '<td align="center">'. ($val->thumb != 0 ? JText::_( 'YES' ) : JText::_( 'NO' )) . '</td>';
    }

}
