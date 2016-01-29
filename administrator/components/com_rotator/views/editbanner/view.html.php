<?php
/**
 * @file com_rotator/admin/views/editbanner/view.html.php
 * @ingroup _comp_adm
 * Vista para editar los datos del banner
 */
defined( '_JEXEC') or die( 'Restricted access');

jimport( 'Amadeus.View.Edit');

/**
 * @brief Clase que genera la vista de edicion de los rotadores.
 */
class RotatorViewEditBanner extends AmadeusViewEdit
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
            'rows'   => '4',
            'cols'   => '2',
            'config' => array(
                '0' => array(
                    '0' => array(
                        'colspan' => '2'
                    )
                ),
                '1' => array(
                    '0' => array(
                        'colspan' => '2'
                    )
                ),
                '2' => array(
                    '0' => array(
                        'colspan' => '2'
                    )
                ),
                '3' => array(
                    '0' => array(
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

        require_once( JPATH_COMPONENT.DS.'helper.php' );

        $visible = false;
        if($this->task=='add')
            $visible = true;

        $disabled = array();
        if($visible)
            $disabled = array('disabled' => 'disabled');

        AmadeusUtilHtml::includeResource('/administrator/components/com_rotator/js/jquery.Jcrop.min.js');
        AmadeusUtilHtml::includeResource('/administrator/components/com_rotator/js/fileuploader.min.js');
        AmadeusUtilHtml::includeResource('/administrator/components/com_rotator/css/fileuploader.min.css');
        AmadeusUtilHtml::includeResource('/administrator/components/com_rotator/css/jquery.Jcrop.min.css');

        jimport('Amadeus.Util.Database');
        $orden = AmadeusUtilDataBase::countData('am_banners', 'rotator = '.$this->rotator->id) + 1;

        $_fields = array(
            array(
                'html'   => '<h2 class="main-title">'.$this->rotator->nombre.'</h2>'
            ),
            $this->getAreaUploadImages($visible),
            $this->getAreaPreviewImage($visible),
            array(
                'title'  => JText::_('GENERAL_INFORMATION'),
                'fields' => array(
                    'id' => array(
                        'hidden'  => true,
                        'type'    => 'integer',
                        'html'    => AmadeusUtilHtml::inputHidden('id', array('value' => 0)).'<input name="step" type="hidden" value="error" />'
                    ),
                    'rotator' => array(
                        'hidden'  => true,
                        'type'    => 'integer',
                        'html'    => AmadeusUtilHtml::inputHidden('rotator', array('value' => $this->rotator->id))
                    ),
                    'title' => array(
                        'label'   => JText::_('LABELTITLE'),
                        'tooltip' => JText::_('TOOLTIPTITLE'),
                        'type'    => 'string',
                        'html'    => AmadeusUtilHtml::inputText('title', array_merge(array('size' => 60), $disabled))
                    ),
                    'button' => array(
                        'label'   => JText::_('LABELBUTTON'),
                        'tooltip' => JText::_('TOOLTIPBUTTON'),
                        'type'    => 'string',
                        'mandatory' => false,
                        'html'    => AmadeusUtilHtml::inputText('button', $disabled)
                    ),
                    'link' => array(
                        'label'   => JText::_('LABELLINK'),
                        'tooltip' => JText::_('TOOLTIPLINK'),
                        'type'    => 'string',
                        'html'    => AmadeusUtilHtml::inputText('link', array_merge(array('size' => 60), $disabled))
                    ),
                    'orden' => array(
                        'label'   => JText::_('LABELORDEN'),
                        'tooltip' => JText::_('TOOLTIPORDEN'),
                        'type'    => 'integer',
                        'html'    => AmadeusUtilHtml::inputText('orden', array_merge(array('size' => '10', 'value' => $orden), $disabled))
                    ),
                    'published' => array(
                        'label'   => JText::_('LABELPUBLISHED'),
                        'tooltip' => JText::_('TOOLTIPPUBLISHED'),
                        'type'    => 'include',
                        'include' => array('1', '0'),
                        'html'    => AmadeusUtilHtml::radioButton('published',
                                         array(0 => JText::_('UNPUBLISH'), 1 => JText::_('PUBLISH')),
                                         array_merge(array('value' => 1), $disabled))
                    )
                )
            ),
            array(
                'title'  => JText::_('BANNER_INFORMATION'),
                'fields' => array(
                    'description' => array(
                        'label'   => JText::_('LABELDESCRIPTION'),
                        'tooltip' => JText::_('TOOLTIPDESCRIPTION'),
                        'type'    => 'raw',
                        'html'    => AmadeusUtilHtml::inputArea('description', array_merge(array('rows' => 10, 'cols' => 40, 'raw' => true), $disabled))
                    )
                )
            )
        );

        return $_fields;
    }

    /**
     * @brief Obtiene el area para cargar imágenes, de acuerdo
     * a la accion ejecutada; en edicion no se necesita el area.
     * @param bool $visible Indica si es visible o no el elemento
     * @return bool;
     */
    function getAreaUploadImages($visible)
    {
        if($visible)
        {
            $config = array(
                'height'  => '100%',
                'action'  => 'index.php?option=com_rotator&controller=banner&task=upload&format=raw&only_image=1',
                'preview' => 'preview',
                'aspectratio' => 'ratio'
            );

            $thumb = array(
                'thumb' => array(
                    'width'  => 0,
                    'height' => 0
                )
            );

            if($this->rotator->thumb)
            {
                $thumb = array(
                    'thumb' => array(
                        'width'  => $this->rotator->twidth,
                        'height' => $this->rotator->theight
                    )
                );
            }

            $config = array_merge($config, $thumb);

            $area= RotatorHelper::imageCropField('image', $config);
            $area.= '<div class="d-ratio">';
            $area.= AmadeusUtilHtml::checkButton('ratio',
                        array('1' => '<b>Selecci&oacute;n proporcional</b>'),
                        array('value' => '1', 'disabled' => 'disabled')
                    );
            $area.= '</div>';

            return array(
                    'title'  => JText::_('IMAGE_ORIGINALLY'),
                    'html'   => $area
                    );
        }

        return false;

    }

    /**
     * @brief Crea el arreglo con el area del preview de la imagen.
     * @param bool $visible Indica si es visible o no el elemento
     * @return bool;
     */
    function getAreaPreviewImage($visible)
    {
        $confarea = array(
            'style'     => 'width: '.$this->rotator->width.'px; height: '.$this->rotator->height.'px; overflow: hidden;'
        );

        if($visible)
        {
            $area= RotatorHelper::areaThumbnail('preview', $confarea);
            $area.= '<div class="qq-upload-button save-image" style="display:none;">Seleccionar</div>';

            return array(
                      'title'  => JText::_('IMAGE_FINAL'),
                      'html'   => $area
                   );
        }
        else
        {
            $image = JRequest::getVar('seeImage', '', 'post');
            $thumb = JRequest::getVar('seeThumb', '', 'post');
            $confarea = array_merge($confarea, array('img' => $image));

            $area = AmadeusUtilHtml::inputHidden('image', array('value' => $image));

            if($this->rotator->thumb)
            {

                $config = array(
                    'thumb' => array(
                        'width'  => $this->rotator->twidth,
                        'height' => $this->rotator->theight
                    ),
                    'aspectratio' => 'ratio',
                    'preview' => 'preview'
                );

                $area .= '<table class="banner-thumb" width="100%"><tr><td width="60%">Banner</td>';
                $area .= '<td>Thumb</td></tr><tr><td class="img-banner">';
                $area .= RotatorHelper::areaThumbnail('preview', $confarea).'</td>';
                $area .= '<td><div id="preview-thumb" class="preview-img" ';
                $area .= 'style="height:'.$this->rotator->theight.'px;';
                $area .= 'width:'.$this->rotator->twidth.'px;overflow:hidden;">';
                $area .= '<img src="'.$thumb.'" width="'.$this->rotator->twidth.'"';
                $area .= ' height="'.$this->rotator->theight.'" />';
                $area .= '</div></td></tr>';
                $area .= '<tr><td colspan="2">';
                $area .= '<div class="d-ratio">';
                $area .= AmadeusUtilHtml::checkButton('ratio',
                            array('1' => '<b>Selecci&oacute;n proporcional</b>'),
                            array('value' => '1')
                         );
                $area .= '</div></td></tr></table>';
                $area .= RotatorHelper::doJcrop('image', $config);
            }
            else
            {
                $area .= RotatorHelper::areaThumbnail('preview', $confarea).'</td>';
            }

            return array(
                      'title'  => JText::_('IMAGE_FINAL'),
                      'html'   => $area
                   );
        }
    }

}
