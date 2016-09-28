<?php
/**
 * @file com_rotator/admin/views/banner/view.html.php
 * @ingroup _comp_adm
 * Vista para la visualización de los banners almacenados en el sistema.
 */
defined( '_JEXEC') or die( 'Restricted access');

jimport( 'Amadeus.View.List');

/**
 * @brief Clase que genera la vista de visualización de banners.
 */
class RotatorViewBanner extends AmadeusViewList
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
            'image' => array(
                'legend' => JText::_('IMAGE_BANNER'),
                'size'   => 150
            ),
            'title' => array(
                'legend' => JText::_('TITLE_BANNER'),
                'order'  => true,
                'edit'   => true
            ),
            'rotator' => array(
                'legend' => JText::_('ROTATOR'),
                'size'   => 150
            ),
            'orden' => array(
                'legend' => JText::_('ORDEN'),
                'size'   => 70,
                'align'  => 'center',
                'modify' => true,
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
     * @brief Obtiene la accion a la cual debe redireccionar el formulario
     * de la lista
     */
    function getAction()
    {
        $controller = JRequest::getCmd('controller');
        return parent::getAction().'&controller='.$controller;
    }

    /**
     * @brief Obtiene la imagen a ser mostrada en la lista
     */
    function getImage($row)
    {
        $html  = '<td>';
        $html .= '<a href="index.php?'.$this->getAction().'&task=edit&cid[]='.$row->id.'">';
        $html .= '<img src="../media/rotator_img/'.$row->image.'" ';
        $html .= 'height="30" /></a>';
        $html .= '</td>';

        return $html;
    }

    /**
     * @brief Obtiene el nombre del rotador al que pertenece
     */
    function getRotator($row)
    {
        jimport('Amadeus.Util.Database');
        $rotator = AmadeusUtilDataBase::getData('am_rotadores', '*', $row->rotator, 'id', true);

        $html  = '<td>';
        $html .= '<a href="index.php?option=com_rotator&task=edit&cid[]='.$row->rotator.'">';
        $html .= $rotator->nombre.'</a>';
        $html .= '</td>';

        return $html;
    }

    /**
     * @brief Adiciona mas filtros, a los filtros existentes.
     */
    function getFilters($tds = true)
    {
        $html = '';
        $model =& $this->getModel();
        $data = array('' => '- Seleccione el Rotador -');

        jimport('Amadeus.Util.Database');
        $rotators = AmadeusUtilDataBase::getData('am_rotadores', 'id,nombre', '1=1');

        if($rotators==false)
            $rotators = array();

        foreach($rotators as $rotator)
        {
            $data[$rotator->id] = $rotator->nombre;
        }

        jimport('Amadeus.Util.Html');

        $config = array(
            'value'   => $model->getState('filter_rotator'),
            'onchange' => 'document.adminForm.submit();',
            'style'   => 'display: inline;'
        );

        $html .= '<td align="right">';
        $html .= AmadeusUtilHtml::select('filter_rotator', $data, $config);
        $html .= '</td>';
        $html .= parent::getFilters();

        return $html;
    }

    /**
     * @brief Resetea las condiciones de busqueda o filtros aplicados.
     * @return string
     */
    function getClearOfFilters()
    {
        return parent::getClearOfFilters().'document.getElementById(\'filter_rotator\').value=\'\';';
    }

}
