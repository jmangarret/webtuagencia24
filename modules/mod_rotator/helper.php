<?php
/**
 * @file mod_rotator/helper.php
 * Archivo que contiene la clase que genera el HTML del módulo
 */
defined('_JEXEC') or die('Restricted access');

/**
 * @brief Clase que genera el HTML que se incrusta en el sitio, para recrear
 * el módulo
 */
class modRotatorHelper
{

    /**
     * @brief Método inicial encargado de coordinar la generación del
     * HTML y javascript del módulo.
     * @param object $params Parámetros configurados en el módulo
     */
    function init(&$params)
    {
        $rotator = $params->get('rotator', '');

        if($rotator=='') return;

        jimport('Amadeus.Util.Database');
        $rotator = AmadeusUtilDatabase::getData('am_rotadores', '*', $rotator, 'id', true);

        self::putResource($rotator);
        self::makeStructure($rotator, $params);
        self::putJavascript($rotator, $params);
    }

    /**
     * @brief Coloca los recursos externos, que permiten el funcionamiento del
     * rotador.
     * @param object $rotator Objeto que representa el rotador
     */
    function putResource(&$rotator)
    {
        jimport('Amadeus.Util.Html');
        AmadeusUtilHtml::includeResource('/modules/mod_rotator/js/jquery.nivo.slider.pack.js');
        AmadeusUtilHtml::includeResource('/modules/mod_rotator/css/nivo-slider.css');

        $document =& JFactory::getDocument();
        $document->addStyleDeclaration($rotator->css);
    }

    /**
     * @brief Crea la estructura del contenedor del rotador e incrusta el
     * codigo necesario para su ejecución.
     * @param object $rotator Objeto que representa el rotador
     * @param object $params Parametros configurados en el módulo
     */
    function makeStructure(&$rotator, &$params)
    {

        $vtitle       = $params->get('visibilityTitle', 1);
        $vdescription = $params->get('visibilityDescription', 1);
        $vbutton      = $params->get('visibilityButton', 1);
        $target       = $params->get( 'target', 1 );

        $description = '';
        $folder = 'media/rotator_img/';
        $banners = AmadeusUtilDatabase::getData('am_banners', '*', 'rotator = '.$rotator->id.' AND published = 1', 'orden, id');
        $link  = '';
        $clink = '';
        $thumb = '';
        $css_thumb = '';

        if(!$banners)
            return;

        if($rotator->thumb==1)
            $css_thumb = 'controlnav-thumbs';

        echo '<div class="slider-wrapper '.$rotator->class.' '.$css_thumb.'">';
        echo '<div class="ribbon"></div>';
        echo '<div id="slider-'.$rotator->id.'" class="nivoSlider" style="width:'.$rotator->width.'px;height:'.$rotator->height.'px;">';

        $caption = '';
        foreach($banners as $banner)
        {
            if($vtitle==1 || $vdescription==1 || $vbutton==1)
            {
                $caption = 'title="#htmlcaption-'.$banner->id.'"';
                $description .= '<div id="htmlcaption-'.$banner->id.'" class="nivo-html-caption">';
            }

            if($vtitle==1)
                $description .= '<h3>'.$banner->title.'</h3>';

            if($vdescription==1)
                $description .= '<div class="am-description">'.$banner->description.'</div>';

            $objLink = '';
            if($banner->link!='#')
            {
                switch ($target)
                {
                    case 1:
                        $objLink .= '<a href="'.$banner->link.'" target="_blank">';
                        break;
                    case 2:
                        $objLink .= "<a href=\"javascript:void(0)\" onclick=\"window.open('".$banner->link;
                        $objLink .= "', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes";
                        $objLink .= ",resizable=yes,width=800,height=550'); return false\">";
                        break;
                    default:
                        $objLink .= '<a href="'.$banner->link.'">';
                        break;
                }
            }

            if($vbutton==1)
            {
                $description .= '<div class="am-link">';
                $description .= $objLink.$banner->button.'</a>';
                $description .= '</div>';
            }
            else
            {
                $link = $objLink;
                $clink = '</a>';
            }

            if($description!='')
                $description .= '</div>';

            if($banner->thumb!='')
                $thumb = 'rel="'.$folder.$banner->thumb.'"';

            echo $link.'<img src="'.$folder.$banner->image.'" '.$caption.' '.$thumb.' alt="'.$banner->title.'" />'.$clink;
        }

        echo '</div>';
        echo $description;
        echo '</div>';
    }

    /**
     * @brief Crea el javascript que inicia el rotador.
     * @param object $rotator Objeto que representa una fila de la tabla rotadores
     * @param object $params Parametros configurados en el módulo
     */
    function putJavascript(&$rotator, &$params)
    {
        // Obtiene todos los parametros configurados.
        $animSpeed        = $params->get('animSpeed', 500);
        $pauseTime        = $params->get('pauseTime', 7000);
        $manualAdvance    = $params->get('manualAdvance', 0) == 1 ? 'true' : 'false';
        $slices           = $params->get('slices', 15);
        $boxCols          = $params->get('boxCols', 8);
        $boxRows          = $params->get('boxRows', 4);
        $startSlide       = $params->get('startSlide', 0);
        $captionOpacity   = $params->get('captionOpacity', 0.8);
        $directionNav     = $params->get('directionNav', 1) == 1 ? 'true' : 'false';
        $directionNavHide = $params->get('directionNavHide', 1) == 1 ? 'true' : 'false';
        $controlNav       = $params->get('controlNav', 1) == 1 ? 'true' : 'false';
        $pauseOnHover     = $params->get('pauseOnHover', 1) == 1 ? 'true' : 'false';

        if($params->get('random', 1)==1)
            $effect = "random";
        else
        {
            $effect = array();

            // Efecto de transparencia
            if($params->get('fade', 0)==1)
                $effect[] = 'fade';

            // Efecto de slide desde la izquierda a la derecha
            if($params->get('slideInRight', 0)==1)
                $effect[] = 'slideInRight';

            // Efecto de slide desde la derecha a la izquierda
            if($params->get('slideInLeft', 0)==1)
                $effect[] = 'slideInLeft';

            // Efecto de barras cayendo de izquierda a derecha
            if($params->get('sliceDownRight', 0)==1)
                $effect[] = 'sliceDownRight';

            // Efecto de barras cayendo de derecha a izquierda 
            if($params->get('sliceDownLeft', 0)==1)
                $effect[] = 'sliceDownLeft';

            // Efecto de barras emergiendo de izquierda a derecha
            if($params->get('sliceUpRight', 0)==1)
                $effect[] = 'sliceUpRight';

            // Efecto de barras emergiendo de derecha a izquierda 
            if($params->get('sliceUpLeft', 0)==1)
                $effect[] = 'sliceUpLeft';

            // Efecto de barras intercalandoce de izquierda a derecha
            if($params->get('sliceUpDown', 0)==1)
                $effect[] = 'sliceUpDown';

            // Efecto de barras intercalandoce de derecha a izquierda 
            if($params->get('sliceUpDownLeft', 0)==1)
                $effect[] = 'sliceUpDownLeft';

            // Efecto de plieges
            if($params->get('fold', 0)==1)
                $effect[] = 'fold';

            // Efecto de cajas aleatorias
            if($params->get('boxRandom', 0)==1)
                $effect[] = 'boxRandom';

            // Efecto de lluvia de cajas de izquierda a derecha
            if($params->get('boxRain', 0)==1)
                $effect[] = 'boxRain';

            // Efecto de lluvia de cajas de derecha a izquierda 
            if($params->get('boxRainReverse', 0)==1)
                $effect[] = 'boxRainReverse';

            // Efecto de lluvia de cajas con crecimiento de izquierda a derecha
            if($params->get('boxRainGrow', 0)==1)
                $effect[] = 'boxRainGrow';

            // Efecto de lluvia de cajas con crecimiento de derecha a izquierda 
            if($params->get('boxRainGrowReverse', 0)==1)
                $effect[] = 'boxRainGrowReverse';

            $effect = join(',', $effect);
        }

        $thumb = '';
        if($rotator->thumb==1)
        {
            $thumb .= 'controlNavThumbs: true,';
            $thumb .= 'controlNavThumbsFromRel: true,';
        }


        // Colocando el javascript para ejecutar el carrusel
        echo '<script type="text/javascript">';
        echo 'jQuery(window).load(function(){';
        echo     'jQuery("#slider-'.$rotator->id.'").nivoSlider({';
        echo         'effect: "'.$effect.'",';
        echo         'slices: "'.$slices.'",';
        echo         'boxCols: "'.$boxCols.'",';
        echo         'boxRows: "'.$boxRows.'",';
        echo         'startSlide: "'.$startSlide.'",';
        echo         'directionNav: '.$directionNav.',';
        echo         'directionNavHide: '.$directionNavHide.',';
        echo         'controlNav: '.$controlNav.',';
        echo         'pauseOnHover: '.$pauseOnHover.',';
        echo         'captionOpacity: "'.$captionOpacity.'",';
        echo         'animSpeed: '.$animSpeed.',';
        echo         'pauseTime: '.$pauseTime.',';
        echo         $thumb;
        echo         'manualAdvance: '.$manualAdvance;
        echo     '});';
        echo '});';
        echo '</script>';
    }

}
