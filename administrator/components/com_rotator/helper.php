<?php
/**
 * @file com_rotator/admin/helper.php
 * @ingroup _comp_adm
 * Contiene la clase de ayudas.
 * los demas controladores
 */
defined( '_JEXEC' ) or die( 'Restricted access' ); 

jimport('Amadeus.Util.Html');

/**
 * @brief Clase qeu permite crear campos personalizados como el de
 * edicion de imagen.
 */
class RotatorHelper
{

    /**
     * @brief Crea un campo que permite capturar una imagen y mostrarla de
     * inmediato en modo de edición.
     *
     * De acuerdo al navegador, se podra arrastrar un archivo sobre el area, para
     * subir la imagen, o se debe usar un boton para esto. La imagen se cargar
     * y se presentara en edición, donde el objetivo es usar la parte de la imagen
     * que mas se acomode a la necesidad.
     *
     * El arreglo de configuracion puede tomar los siguientes parametros:
     *  + width   => Ancho del div donde se mostrara la imagen.
     *  + height  => Alto del div donde se mostrara la imagen.
     *  + action  => Accion encargada de manipular la imagen cuando se carga.
     *  + preview => ID del elemento donde se va a mostrar la previsualizacion.
     *  + thumb   => Arreglo con las dimensiones del Thumb.
     */
    function imageCropField($name, $config = array())
    {
        $_value = AmadeusUtilHtml::_getValue($name, $config);
        $_id = AmadeusUtilHtml::_getID($name, $config);
        $_rnd = mt_rand();
        $size = '';

        if(isset($config['width']))
            $size .= 'width: "'.$config['width'].'"';

        $height = 0;
        if(isset($config['height']))
        {
            $size .= $size != '' ? ',' : '';
            $size .= 'height: "'.$config['height'].'"';
        }

        $html = '';
        $html .= '<div id="'.$_id.'-image" class="upload-field" style="'.$style.'"></div>';
        
        $html .= '<script>';
        $html .= 'jQuery(document).ready(function(){';
        $html .= '    new _jCrop({';
        $html .= '        id: "'.$_id.'",';
        $html .= '        extensions: ["'.join('", "', array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG')).'"],';
        $html .= '        action: "'.$config['action'].'",';
        $html .= '        labels: {';
        $html .= '            "labelButton": "'.JText::_('ICF_LABELBUTTON').'",';
        $html .= '            "labelDrop": "'.JText::_('ICF_LABELDROP').'",';
        $html .= '            "labelCancel": "'.JText::_('ICF_LABELCANCEL').'",';
        $html .= '            "labelFailed": "'.JText::_('ICF_LABELFAILED').'"';
        $html .= '        },';
        $html .= '        upload: true,';
        $html .= '        aspectratio: "'.$config['aspectratio'].'",';
        $html .= '        preview: "'.$config['preview'].'",';
        $html .= '        thumb: {width: '.$config['thumb']['width'].', height: '.$config['thumb']['height'].'},';
        $html .= '        size: {'.$size.'}';

        if(isset($config['height']))
            $html .= '    ,height: "'.$config['height'].'"';

        $html .= '    })';
        $html .= '});';
        $html .= '</script>';

        return $html;
    }

    /**
     * @brief Se usa conjuntamente con AmadeusUtilHtml::imageCropField, ya que
     * permite ver el resultado de lo que se esta seleccionanado en la imagen.
     *
     * Entre los parametros de las configuracion se encuentran los siguientes:
     *  + style     => Corresponde al estilo que se le aplicara
     *  + img       => Es el nombre de la imagen que se va a mostrar
     *
     * @param string $name Nombre que se usara para crear el elemento.
     * @param array $config Configuracion adicional que se puede agregar.
     * @return string
     */
    function areaThumbnail($name, $config)
    {
        $img = '';

        if(isset($config['img']))
        {
            $img = $config['img'];
            unset($config['img']);
        }

        $code  = '<div id="'.$name.'" class="preview-img"';
        $code .= AmadeusUtilHtml::_getAttributesHTML($config);
        $code .= '>';

        if($img)
            $code .= '<img src="'.$img.'" />';

        $code .='</div>';

        return $code;
    }

    /**
     * @brief Crea la pantalla para listar los rotadores actualmente configurados en el
     * sistema.
     * @param string $action Corresponde a la URL, que se usara para agregar baaners a cada carrusel
     * @return string
     */
    function listRotators($action)
    {
        $html = '';

        $db =& JFactory::getDBO();

        $query  = 'SELECT id,nombre,width, height,published ';
        $query .= '  FROM #__am_rotadores ORDER BY nombre';

        $db->setQuery($query);
        $data = $db->loadObjectList();

        $html .= '<div class="listrotator">';
        $query = 'SELECT COUNT(*) as total FROM #__am_banners WHERE rotator = ';

        foreach($data as $rotator)
        {
            $published = JHTML::_('grid.published', $rotator, 0 );
            $public = $rotator->published == 1 ? 'Publicado' : 'Despublicado';
            $published = preg_replace('/title=".*"/', '/title="'.$public.'"/', $published);

            $_action = JRoute::_($action.'&cid[]='.$rotator->id, false);

            $db->setQuery($query.$db->Quote($rotator->id));
            $number = $db->loadObject();

            $html .= '<div class="item">';
            $html .= '<div class="image"><a href="'.$_action.'">'.$number->total.'</a></div>';
            $html .= '<div class="title"><a href="'.$_action.'">'.$rotator->nombre.'</a></div>';
            $html .= '<div class="extra">';
            $html .= $rotator->width.'<span class="sep">x</span>'.$rotator->height;
            $html .= $published.'</div>';
            $html .= '</div>';
        }

        $html .= '<br style="clear: both"/></div>';

        return $html;
    }

    /**
     * @brief Genera el javascript necesario para generar el Jcrop en la
     * edicion de banners.
     * @param string $_id Nombre del campo, para ajustar el Javascript
     * @param array $config COnfiguracion para el Javascript
     * @return string
     */
    function doJcrop($_id, $config)
    {
        $html .= "\n".'<script>';
        $html .= 'jQuery(document).ready(function(){';
        $html .= '    new _jCrop({';
        $html .= '        id: "'.$_id.'",';
        $html .= '        upload: false,';
        $html .= '        aspectratio: "'.$config['aspectratio'].'",';
        $html .= '        preview: "'.$config['preview'].'",';
        $html .= '        thumb: {width: '.$config['thumb']['width'].', height: '.$config['thumb']['height'].'}';
        $html .= '    });';
        $html .= '});';
        $html .= '</script>';

        return $html;
    }

}
