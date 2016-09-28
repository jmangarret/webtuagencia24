<?php
/**
 * @file com_rotator/admin/views/editrotator/view.html.php
 * @ingroup _comp_adm
 * Vista para editar los datos del rotador
 */
defined( '_JEXEC') or die( 'Restricted access');

jimport( 'Amadeus.View.Edit');

/**
 * @brief Clase que genera la vista de edicion de los rotadores.
 */
class RotatorViewEditRotator extends AmadeusViewEdit
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
     * @brief Obtiene la estructura de la table que va a contener la captura.
     * @return array
     */
    function getStructure()
    {
        $_table = array(
            'width'  => '100%',
            'rows'   => '2',
            'cols'   => '2',
            'config' => array(
                '1' => array(
                    '0' => array(
                        'colspan' => '2'
                    )
                ),
                '0' => array(
                    '0' => array(
                        'width'  => '49%',
                        'valign' => 'top'
                    ),
                    '1' => array(
                        'valign' => 'top'
                    )
                )
            )
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

        $disabled = array();
        $size = array('size' => 40);
        $size2 = array('size' => 7);

        if($this->task=='edit')
            $disabled = array('disabled' => 'disabled', 'class' => 'disabled');

        $st_thumbs = array('disabled' => 'disabled', 'class' => 'disabled');
        $thumb = JRequest::getVar('thumb', '0', 'post');
        if(($this->task=='apply' || $this->task=='save') && $thumb=='1')
            $st_thumbs = array();

        $size2 = array_merge($size2, $disabled);
        $configth = array_merge($size2, $st_thumbs);

        $confthumb = array(
            'onchange' => 'document.adminForm.twidth.disabled = this.value=="0"; document.adminForm.theight.disabled = this.value=="0";'
        );
        $confthumb = array_merge($confthumb, $disabled);

        $_fields = array(
            array(
                'title'  => JText::_('GENERAL_CONFIGURATION'),
                'fields' => array(
                    'id' => array(
                        'hidden'  => true,
                        'type'    => 'integer',
                        'html'    => AmadeusUtilHtml::inputHidden('id', array('value' => 0))
                    ),
                    'nombre' => array(
                        'label'   => JText::_('LABELNAME'),
                        'tooltip' => JText::_('TOOLTIPNAME'),
                        'type'    => 'string',
                        'html'    => AmadeusUtilHtml::inputText('nombre', $size)
                    ),
                    'class' => array(
                        'label'   => JText::_('LABELCLASS'),
                        'tooltip' => JText::_('TOOLTIPCLASS'),
                        'type'    => 'string',
                        'html'    => AmadeusUtilHtml::inputText('class', $size)
                    ),
                    'published' => array(
                        'label'   => JText::_('LABELPUBLISHED'),
                        'tooltip' => JText::_('TOOLTIPPUBLISHED'),
                        'type'    => 'include',
                        'include' => array('1', '0'),
                        'html'    => AmadeusUtilHtml::radioButton('published',
                                         array(0 => JText::_('UNPUBLISH'), 1 => JText::_('PUBLISH')),
                                         array('value' => 1))
                    )
                )
            ),

            array(
                'title'  => JText::_('LOOK_CONFIGURATION'),
                'fields' => array(
                    'thumb' => array(
                        'label'   => JText::_('LABELTHUMB'),
                        'tooltip' => JText::_('TOOLTIPTHUMB'),
                        'type'    => 'include',
                        'include' => array('1', '0'),
                        'html'    => AmadeusUtilHtml::radioButton('thumb',
                                         array(0 => JText::_('JNO'), 1 => JText::_('JYES')),
                                         array_merge(array('value' => 0), $confthumb))
                    ),
                    'twidth' => array(
                        'label'   => JText::_('LABELTWIDTH'),
                        'tooltip' => JText::_('TOOLTIPTWIDTH'),
                        'type'    => 'integer',
                        'html'    => AmadeusUtilHtml::inputText('twidth', $configth).'&nbsp;&nbsp;px'
                    ),
                    'theight' => array(
                        'label'   => JText::_('LABELTHEIGHT'),
                        'tooltip' => JText::_('TOOLTIPTHEIGHT'),
                        'type'    => 'integer',
                        'html'    => AmadeusUtilHtml::inputText('theight', $configth).'&nbsp;&nbsp;px'
                    ),
                    'width' => array(
                        'label'   => JText::_('LABELWIDTH'),
                        'tooltip' => JText::_('TOOLTIPWIDTH'),
                        'type'    => 'integer',
                        'html'    => AmadeusUtilHtml::inputText('width', $size2).'&nbsp;&nbsp;px'
                    ),
                    'height' => array(
                        'label'   => JText::_('LABELHEIGHT'),
                        'tooltip' => JText::_('TOOLTIPHEIGHT'),
                        'type'    => 'integer',
                        'html'    => AmadeusUtilHtml::inputText('height', $size2).'&nbsp;&nbsp;px'
                    )
                )
            ),

            array(
                'title'  => JText::_('CSS'),
                'html'   => AmadeusUtilHtml::inputArea('css', array('style' => 'width:99%', 'rows' => 20)),
                'fields' => array(
                    'css' => array(
                        'label'     => JText::_('LABELCSS'),
                        'type'      => 'raw'
                    )
                )
            )
        );

        return $_fields;
    }

}
