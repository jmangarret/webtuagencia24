<?php
/**
 * @file mod_random_planes/helper.php
 * @ingroup _module
 * Archivo que organiza los datos para presentar los planes
 */
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$mainframe = JFactory::getApplication();
//$document->addStyleSheet(JURI::base().'templates/'.$mainframe->getTemplate().'/css/carrusel.css');
$document->addStyleSheet(JURI::base().'modules/mod_random_planes/css/carrusel.css');

//Obtenemos las imagenes del webservice a traves del plugin

JPluginHelper::importPlugin('amadeus');
JPluginHelper::importPlugin('amadeus', 'PlansEngine');
JPluginHelper::importPlugin('amadeus', 'numberFormat');
$dispatcher = & JDispatcher::getInstance();

/**
 * @brief Organiza los datos a mostrar en la vista
 */
class modRandomPlanesHelper {

    /**
     * @brief Organiza y presenta los datos a desplegar en el carrusel
     * @param object $params Parametros configurados por el usuario
     */
    function init(&$params)
    {
	
        $class_sfx       = $params->get('class_sfx', '');
        $categories = $params->get('categories', '');

      /*  $parser = 'components/com_virtuemart/virtuemart_parser.php';
        if( file_exists(dirname(__FILE__).'/../../'.$parser ))
            require_once( dirname(__FILE__).'/../../'.$parser );
        else
            require_once( dirname(__FILE__).'/../'.$parser );
	*/
        self::includeJS();

        $planes = array();
        $useTabs = false;
        if($categories!='')
        {
		
            if(preg_match('/^\d+$/', $categories))
            {
                $planes[] = self::getPlanes($params, (int)$categories);
            }
            else
            {
                $useTabs = true;
                $categories = explode(';', $categories);
                foreach($categories as $category)
                {
                    $category = explode('|', $category);
					//$planes[] = self::getPlanes($params, (int)$category[1]);
                }
            }
        }
        else
        {
            // $planes[] = self::getPlanes($params, 0);

            //Obtenemos las imagenes del webservice a traves del plugin
            JPluginHelper::importPlugin('amadeus', 'PlansEngine');
            JPluginHelper::importPlugin('amadeus', 'numberFormat');
            $dispatcher = & JDispatcher::getInstance();

            //Obtenemos las ofertas
            $retval = $dispatcher->trigger('getOfferSpecial', array("GetGeographicSpecial" ));
			
            $productos = $retval[0];
			
            $paramsCP = &JComponentHelper::getParams('com_cp');
            $i=0;
            foreach ($productos as $producto){

                $url = JRoute::_("index.php?option=com_cp&view=cpproducts&id=".$producto->product_id);
                $precio = $dispatcher->trigger('numberFormatWithCurrency', array($producto->price, $paramsCP->get("cfg_currency")));
                $plan[$i]['product_id']          = $producto->file_url;
                $plan[$i]['product_parent_id']   = $producto->file_url;
                $plan[$i]['product_name']        = $producto->product_name;
                $plan[$i]['product_thumb_image'] = JURI::root().$producto->file_url;
                $plan[$i]['product_s_desc']      = $producto->product_desc;
                $plan[$i]['url']      = JRoute::_("index.php?option=com_cp&view=cpproducts&id=".$producto->product_id);
                $i++;

            }

            $planes=array();

            $planes[]=$plan;

        }

        $tabs = $params->get('categories', '');
        $unique = mt_rand();
        echo '<div id="vmRP-'.$unique.'" class="vm-planes-aleatorios">';

        if($useTabs)
        {
            $tabs = explode(';', $tabs);

            echo '<div class="vm-random-tabs">';
            $i = 1;
            $select = ' selected';
            echo '<ul>';
            foreach($tabs as $tab)
            {
                $tab = explode('|', $tab);
                echo '<li id="rnd-'.$i.'" class="vm-tab'.$select.'">'.$tab[0].'</li>';
                $select = '';
                $i++;
            }
            echo '</ul>';
            echo '</div>';
        }

        echo '<div class="rnd-content">';
        $i = 1;
        $style = '';
        $script = '_fn_start = {';

        foreach($planes as $plan)
        {
            echo '<div id="cnt-rnd-'.$i.'" style="'.$style.'">';
            $random = mt_rand();
            $script .= 'fn_'.$i.':'.self::getScripts($random, $params).',';
            self::putPlanes($plan, $random, $params);
            $i++;
            $style = 'display: block;';
            echo '</div>';
        }
        $script .= 'fn_: function(){}}'."\n";
        $script .= 'jQuery(document).ready(function(){';
        $script .= ' var $ = jQuery;';
        $script .= ' _fn_start["fn_1"]();';
        $script .= ' var goRandomPlans = function(){';
        $script .= '  if($(this).hasClass(\'selected\')) return false;';
        $script .= '  var cl = $(this).attr(\'id\');';
        $script .= '  var clo = $(\'#vmRP-'.$unique.' div.vm-random-tabs ul li.selected\').attr(\'id\');';
        $script .= '  $(\'#vmRP-'.$unique.' div.rnd-content > div#cnt-\'+clo+\' div.jcarousel-skin-'.$class_sfx.' ul\').data(\'jcarousel\').stopAuto();';
        $script .= '  $(\'#vmRP-'.$unique.' div.vm-random-tabs ul li.selected\').removeClass(\'selected\');';
        $script .= '  $(this).addClass(\'selected\');';
        $script .= '  $(\'#vmRP-'.$unique.' div.rnd-content > div\').hide();';
        $script .= '  $(\'#vmRP-'.$unique.' div.rnd-content > div#cnt-\'+cl).show();';
        $script .= '  if($(\'#vmRP-'.$unique.' div.rnd-content > div#cnt-\'+cl+\' div.jcarousel-skin-'.$class_sfx.'\').length==0){';
        $script .= '   _fn_start["fn_"+cl.split("-")[1]]();';
        $script .= '  }';
        $script .= '  $(\'#vmRP-'.$unique.' div.rnd-content > div#cnt-\'+cl+\' div.jcarousel-skin-'.$class_sfx.' ul\').data(\'jcarousel\').startAuto();';
        $script .= ' };';
        $script .= ' $(\'#vmRP-'.$unique.' div.vm-random-tabs ul > li\').click(goRandomPlans);';
        $script .= '});';

        echo '</div>';
        echo '</div>';

        $document =& JFactory::getDocument();
        $document->addScriptDeclaration($script);
    }

    /**
     * @brief Obtiene y carga los scripts necesarios para ejecutar el módulo
     */
    function includeJS()
    {
        $include_jquery = true;
        $include_jcarrusel = true;

        $document =& JFactory::getDocument();

        $headerstuff=$document->getHeadData();
        reset($headerstuff['scripts']);
        foreach($headerstuff['scripts'] as $key=>$value)
        {
            if (preg_match("/jquery-1.4.2.min.js$/",$key))
                $include_jquery = false;

            if (preg_match("/jquery.min.js$/",$key))
                $include_jquery = false;

            if (preg_match("/jquery.jcarousel.min.js/",$key))
                $include_jcarrusel = false;
        }

		/*if($include_jquery)
            $document->addScript(JURI::root(true).'/media/system/js/jquery-1.4.2.min.js');*/

        if($include_jcarrusel)
			$document->addScript(JURI::root(true).'/modules/mod_random_planes/js/jquery.jcarousel.js');

        //$script  = 'jQuery.noConflict(); ';
        //$document->addScriptDeclaration($script);
    }

    /**
     * @brief Obtiene cada uno de los carruseles
     * @param integer $random Numero unico que identifica ese carrusel
     * @param object $params Parametros configurados por el usuario
     */
    function getScripts($random, &$params)
    {
        $document =& JFactory::getDocument();

        $wrap = $params->get('wrap','null');
        if($wrap!='null')
            $wrap = '"'.$wrap.'"';

        $script  = 'function(){';
        $script .= ' function loadItem'.$random.'(carousel, state){';
        $script .= '  for(var i=carousel.first; i<=carousel.first+'.$params->get('visible','1').'-1; i++){';
        $script .= '   var _x = i<1 ? _data'.$random.'.length - ((i * -1) % _data'.$random.'.length) : i;';
        $script .= '   if(carousel.has(i)) continue;';
        $script .= '   if(_x>_data'.$random.'.length) break;';
        $script .= '   carousel.add(i, _data'.$random.'[_x - 1].html);';
        $script .= '  }';
        $script .= ' }';
        $script .= ' jQuery("#planes'.$random.'").jcarousel({';
        $script .= '  size: _data'.$random.'.length,';
        $script .= '  visible: '.$params->get('visible','1').',';
        $script .= '  itemLoadCallback: {onBeforeAnimation: loadItem'.$random.'},';
        $script .= '  rtl: '.$params->get('rtl','0').',';
        $script .= '  scroll: '.$params->get('scroll','1').',';
        $script .= '  animation: '.$params->get('animation','400').',';
        $script .= '  auto: '.$params->get('auto','0').',';
        $script .= '  wrap: '.$wrap;
        $script .= ' });';
        $script .= '}';

        return $script;
    }

    /**
     * @brief Obtiene los datos de los planes a mostrar
     * @param Object $params Parametros configurados por el usuario
     * @param integer $categories Categoria de donde obtener los planes
     * @return array
     */
    function getPlanes(&$params, $categories)
    {
        $class_sfx       = $params->get('class_sfx', '');
		$db = & JFactory::getDBO();
        //$db=new ps_DB;

        $query  = 'SELECT #__{vm}_product.product_id, #__{vm}_product.product_parent_id, ';
        $query .= '#__{vm}_product.product_name, #__{vm}_product.product_thumb_image, ';
        $query .= '#__{vm}_product.product_s_desc';
        $query .= ' FROM #__{vm}_product ';

        if($categories)
        {
            $query .= 'INNER JOIN #__{vm}_product_category_xref ';
            $query .= 'ON #__{vm}_product.product_id = #__{vm}_product_category_xref.product_id ';
            $query .= 'INNER JOIN #__{vm}_category ';
            $query .= 'ON #__{vm}_category.category_id = #__{vm}_product_category_xref.category_id ';
            $query .= 'WHERE #__{vm}_category.category_id="'.$categories.'" ';
        }
        else
        {
            $query .= "WHERE 1 = 1 ";
        }
        $query .= 'AND #__{vm}_product.product_parent_id="" ';
        $query .= 'AND #__{vm}_product.product_publish="Y" ';
        if( CHECK_STOCK && PSHOP_SHOW_OUT_OF_STOCK_PRODUCTS != "1") {
            $query .= " AND #__{vm}_product.product_in_stock > 0 ";
        }
        $query .= "ORDER BY RAND()";

		$db->setQuery($query);
		
        $i=0;
        $planes = array();
        while($db->next_record())
        {
            $planes[$i] = array();

            $planes[$i]['product_id']          = $db->f("product_id");
            $planes[$i]['product_parent_id']   = $db->f("product_parent_id");
            $planes[$i]['product_name']        = $db->f("product_name");
            $planes[$i]['product_thumb_image'] = $db->f("product_thumb_image");
            $planes[$i]['product_s_desc']      = str_replace(array("\r\n","\n","\r"), ' ', $db->f("product_s_desc"));

            $i++;
        }
		
		return $planes;
    }

    /**
     * @brief Genera el HTML para colocar los planes en el documento
     * @param array $planes Datos de los planes que se van a agregar
     * @param integer $random Numero unico que identifica ese carrusel
     * @param object $params Parametros configurados por el usuario
     */
    function putPlanes($planes, $random, &$params)
    {
        
        $show_name        = (bool)$params->get( 'show_name', 1 );
        $show_price       = (bool)$params->get( 'show_price', 1 );
        $show_detail      = (bool)$params->get( 'show_detail', 1 );
        $show_description = (bool)$params->get( 'show_description', 1 );
        $show_image       = (bool)$params->get( 'show_image', 1 );

        $class_sfx = $params->get('class_sfx', '');

        //require_once(CLASSPATH.'ps_product_category.php');
        //$ps_product_category = new ps_product_category;

        //require_once(CLASSPATH.'ps_product.php');
        //$ps_product = new ps_product;

        echo '<script type="text/javascript"> var _data'.$random.'=[';
        $first = true;

        for($i=0, $l=count($planes); $i<$l; $i++)
        {
            if(!$first) echo ',';

            $first = false;

            $product_id = $planes[$i]['product_id'];
            $cid = $product_id ;
            $link=$planes[$i]['url'];

            if ($planes[$i]['product_parent_id'])
            {
                $url  = "?page=shop.product_details&category_id=$cid&flypage=";
                $url .= $planes[$i]['product_parent_id'];
                $url .= "&product_id=" . $planes[$i]['product_parent_id'];
            }
            else
            {
                $url  = "?page=shop.product_details&category_id=$cid&flypage=";
                $url .= $planes[$i]['product_id'];
                $url .= "&product_id=" . $planes[$i]['product_id'];
            }

            $price = 0;

            $item = '';

             

            if($show_image && $planes[$i]['product_thumb_image']!='')
            {
                $img=$planes[$i]['product_thumb_image'];
                $item .= '<div class="vm-image">';
                $item .= '<a title="'.$planes[$i]['product_name'].'" ';
                $item .= 'href="'.$link.'">';
                $item .= '<img width="150" height="115" src="'.$img.'"/>';
                $item .= '</a></div>';
            }

            if($show_name)
               $item .= '<div class="vm-title">'.$planes[$i]['product_name'].'</div>';

            if($show_description)
                $item .= '<div class="vm-description">'.str_replace(array("\r\n","\n","\r"), ' ', nl2br($planes[$i]['product_s_desc'])).'</div>';

            if($show_detail)
            {
                global $VM_LANG, $modulename;
                $modulename = 'shop';
                //$VM_LANG->load('shop');

                $item .= '<div class="vm-details"><a title="'.$planes[$i]['product_name'];
                $item .= '" href="'.$link.'">';
                $item .= '</a></div>';
            }

           // if($show_price)
                $item .= '<div class="vm-price"><a id="replaced_ver_mas" href="'.$link.'"> Ver detalles</a></div>';

            echo '{html: \''.$item.'\'}';
        }

        echo '];</script>';

        echo '<ul id="planes'.$random.'" class="jcarousel-skin-'.$class_sfx.'">';
        echo '</ul>';
    }

}
