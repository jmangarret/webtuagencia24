<?php
/**
 * Default Template
 *
 * @autor Dora Peña
 * @email   dora.pena@periferia-it.com
 * @date    November 2013
 */
// No direct access to this file
defined('_JEXEC') or die;

$discountPath = JURI::root().'modules/mod_discountflights/js/';
$discountDoc = JFactory::getDocument();
$discountDoc->addScript($discountPath.'load-tabs.js');
$tabs= moddiscountflightsHelper::getDiscountFlightsTabs();
$discountFlights = moddiscountflightsHelper::getDiscountFlights($tabs);
$discountRegions = moddiscountflightsHelper::getRegions();
$module_aaws_qs = JModuleHelper::getModule('mod_aaws_qs');
$module_aaws_qs_params = new JRegistry();
$module_aaws_qs_params->loadString($module_aaws_qs->params);
$max_flights = 100;
$max_regions = 100;
$discountOffsetDays = (int)$module_aaws_qs_params->get('offset_days');
$discountCheckinDate = strtotime('+'.$discountOffsetDays.' day', strtotime(date('Y-m-d')));
$discountCheckinDate = date('Y-m-d', $discountCheckinDate);

/*-----------------------------------------------------------------------------------------------------*/
/*MICOD
En éste lugar capturo las URL con parametros GET para realizar la búsqueda de ofertas de vuelo.
Solo funciona con URL externas (Pubilicades).*/
$id=$_GET["id_vuelo"];
if (isset($id)){ //Comprobamos si existe.

if (is_numeric($id)) { //Comprobamos si es numérico.

foreach ($tabs as $tab) {
  foreach ($discountRegions as $regions) {
    foreach ($discountFlights[$tab["id"]][(int)$regions->id_secondlevel] as $pasaje) {
      if ($pasaje->id == $id) {

               $dateArrival= $pasaje->departure;
               $fecha = date_create($dateArrival);
                       
        $ffinal= date("Y-m-d", strtotime("$dateArrival + ".$pasaje->duration.' days')); 
        /*-----------------------------------------------------------------------------------------------------------------------------------------*/
        /*MICOD
        Lugar donde capturo los datos de los días periódicos para las fechas y la convierto en una fecha real.
        Para ello verifico si está habilitado ésa opción con la condición de abajo y luego realizo el proceso.*/
        if ($pasaje->offset != NULL) { //Verifico si tengo datos en el atributo "offset" de la base de datos
          $dateArrival = date('Y-m-d', strtotime('+'.$pasaje->offset.' days')); //Convierto el dato a un valor tipo "date" con los días ya sumados
          $ffinal= date("Y-m-d", strtotime("$dateArrival + ".$pasaje->duration.' days')); //Calculo el día de regreso sumando los días de estadía 
        }

        $data = array(); 
        $data['id']                 = $pasaje->id;          
        $data['TRIP_TYPE']          = 'R';
        $data['B_LOCATION_1']       = $pasaje->origin;
        $data['E_LOCATION_1']       = $pasaje->destiny;
        $data['B_DATE_1']           = $dateArrival; //Asigno la fecha inicial, sea fija o periódica
        $data['B_DATE_2']           = $ffinal; //Asigno lafecha final, sea fija o periódica
        $data['TRAVELLER_TYPE_ADT'] = '1';
        $data['TRAVELLER_TYPE_CHD'] = '0';
        $data['TRAVELLER_TYPE_INF'] = '0';
        $data['TRAVELLER_TYPE_YCD'] = '0';
        $data['CABIN']              = 'Economy';
        $data['AIRLINE']            = '';
        $data['MAX_CONNECTIONS']    = '';
        $data['ajax']               = '0';
        $action = JRoute::_(AawsHelperRoute::getFlowRoute('air.availability'), false);
        /*-----------------------------------------------------------------------------------------------------------------------------------------*/
        $html   = '<form action="'.$action.'" method="post" id="valores" >';
        foreach($data as $key => $value)
        {
            $html .= '<input type="hidden" name="wsform['.$key.']" value="'.$value.'" />';
        }
        $html .= '</form>';  
        echo $html;
      }
    }
  }
}
}
?>

<script type="text/javascript">
/*MICOD
Enviamos el formulario tan rápidamente como cargue los datos utilizando jQuery*/
    $("#valores").submit(); 
</script>

<?php
}else{
/*-----------------------------------------------------------------------------------------------------*/
?>
<!--/////////////////////////////////////////////////////////////////////////////////////////////////-->
<!--MICOD
Estilo del carusel de ofertas de vuelo-->
<style type="text/css">
  #ciudades{
  vertical-align: middle;
  text-align: left;
  font-family: 'Roboto Condensed', sans-serif;
  color: #FFFFFF;
  padding-top: 4px;
  padding-bottom: 7px;
  padding-left: 7px;
  font-size: 15px;
  height: 40px;
  margin-bottom: -40px;
  position: relative;
  font-weight: bold;
}
  #precio{
  font-size: 16px;
  font-size: 1rem;
  color: #FFF;
  letter-spacing: 0;
  text-shadow: 0 0 2px #000;
  background-color: rgba(0,0,0,0.75);
  padding: 10px;
  position: relative;
  left: 0px;
  right: 0;
  bottom: 0;
  width: 210px;
  text-align: right;
  margin-bottom: -40px;
  top: -43px;
  }
  .money{
    color: #FFFFFF;
  }
  #slider{
     overflow: hidden;
  }

   /*MICOD Clases para las felchas de dirección del carrusel.
   LLamados desde Jquery*/
   .global1{
    margin:0;
   padding:0;
   display:block;
   overflow:hidden;
   text-indent:-8000px;
   }

    .global2{
      display:block;
    width:30px;
    height:77px;
    position:absolute;
    left: 8px;
    margin-top: -130px;
    z-index:1000;
    }

    .solonext{
      left:947px;
    }

    .botonprev{
    display:block;
    position:relative;
    width:30px;
    height:77px;
    background:url(images/atras.png) no-repeat 0 0;
    background-size: 25px 57px;
    }

    .botonnext{
      background:url(images/adelante.png) no-repeat 0 0;
    background-size: 25px 57px; 
    }
</style>
<!--/////////////////////////////////////////////////////////////////////////////////////////////////-->

<div id="discount-flights" style="min-height: 260px;"><!--MICOD: nuevo style agregado-->
  <ul class="tabs">
  <?php foreach($tabs as $tab): ?>
    <li class="<?php echo $tab['name']; ?>"><a
      href="#tab-<?php echo $tab['name']; ?>"><?php echo $tab['label']; ?>
    </a>
    </li>
    <?php endforeach; ?>
  </ul>
  <div class="contenedor_tab">
      <?php 
      $i = 1; //MICOD: Contador de Tabs
      foreach($tabs as $id_firstlevel => $tab):?>
      <div id="tab-<?php echo $tab['name']; ?>" class="contenido_tab">
          <?php
          $counter_regions = 0;
          $o=1; //MICOD: Contador de contenido
          foreach($discountRegions as $region):?>
            <?php if((int)$region->id_firstlevel==$id_firstlevel && $counter_regions<$max_regions):?>
              <div id="tab-<?php echo $tab['id']; ?>" class="destination">
              <h4><?php echo (string)$region->title_secondlevel;?></h4>
              <div id="slider<?php echo $i; echo $o; ?>"><!--MICOD: nuevo div agregado para que el carrusel funcione-->
              <ul><!--MICOD: div cambiado por UL y LI para el funcionamiento del carrusel-->
                <?php
                $counter_flights = 0;
                foreach($discountFlights[$id_firstlevel][(int)$region->id_secondlevel] as $flight):
                  if($counter_flights<$max_flights):
               $dateArrival= $flight->departure;
                  $fecha = date_create($dateArrival);
                  
        $ffinal= date("Y-m-d", strtotime("$dateArrival + ".$flight->duration.' days')); 
        /*-----------------------------------------------------------------------------------------------------------------------------------------*/
        /*MICOD
        Lugar donde capturo los datos de los días periódicos para las fechas y la convierto en una fecha real.
        Para ello verifico si está habilitado ésa opción con la condición de abajo y luego realizo el proceso.*/
        if ($flight->offset != NULL) { //Verifico si tengo datos en el atributo "offset" de la base de datos
          $dateArrival = date('Y-m-d', strtotime('+'.$flight->offset.' days')); //Convierto el dato a un valor tipo "date" con los días ya sumados
          $ffinal= date("Y-m-d", strtotime("$dateArrival + ".$flight->duration.' days')); //Calculo el día de regreso sumando los días de estadía 
        }

                  ?>
                    <li class="journey" style="cursor:pointer; float: left; margin-right: 60px; margin-left: -40px;"> 
                              <?php 
                       $data = array();
        $data['id']                 = $flight->id;               
        $data['TRIP_TYPE']          = 'R';
        $data['B_LOCATION_1']       = $flight->origin;
        $data['E_LOCATION_1']       = $flight->destiny;
        $data['B_DATE_1']           = $dateArrival; //Asigno la fecha inicial, sea fija o periódica
        $data['B_DATE_2']           = $ffinal; //Asigno lafecha final, sea fija o periódica
        $data['TRAVELLER_TYPE_ADT'] = '1';
        $data['TRAVELLER_TYPE_CHD'] = '0';
        $data['TRAVELLER_TYPE_INF'] = '0';
        $data['TRAVELLER_TYPE_YCD'] = '0';
        $data['CABIN']              = 'Economy';
        $data['AIRLINE']            = '';
        $data['MAX_CONNECTIONS']    = '';
        $data['ajax']               = '0';
        $action = JRoute::_(AawsHelperRoute::getFlowRoute('air.availability'), false);
        /*-----------------------------------------------------------------------------------------------------------------------------------------*/
        $html   = '<form action="'.$action.'" method="post" >';
        foreach($data as $key => $value)
        {
            $html .= '<input type="hidden" name="wsform['.$key.']" value="'.$value.'" />';
        }
        $html .= '</form>';  
           
        echo $html;        
                     ?>
                      <div class="cities" id="ciudades" style="max-width:210px;">
                           <?php echo $flight->originname ?> - <?php echo $flight->destinyname ?>
                     </div>
                     <div><!--MICOD: div que contiene la imagen de la oferta-->
                      <img src="<?php echo JURI::root().$flight->image; ?>" border="0" width="210" height="180" />
                     </div>
                      <div class="price" id="precio">
                      <div><span class="from" style="color: #FFFFFF;"><?php echo JText::_('MOD_DISCOUNTFLIGHTS_PRICE_FROM')?></span> <span class="money"><?php echo $module_aaws_qs_params->get('default_currency') ?> <?php echo number_format($flight->value,0,',','.'); ?></span></div>
                      </div>
                    </li>
                  <?php
                  $counter_flights++;
                  endif;
                  endforeach;?>
                </ul>
               </div>
              </div>
            <?php $counter_regions++; endif;?>
          <?php 
          $o++;
          endforeach;?>
          <script type="text/javascript">
      var num2 = "<?php echo $o; ?>"; //MICOD: Asignamos el contador de contenido a una variable JavaScript
      </script>
      </div>
      <?php $i++;
      endforeach; ?>
      <script type="text/javascript">
      var num1 = "<?php echo $i; ?>"; //MICOD: Asignamos el contador de Tabs a una variable JavaScript
      </script>
  </div>
</div>
<!--/////////////////////////////////////////////////////////////////////////////////////////////////-->
<!--MICOD
Script que contiene el funcionamiento completo del carrusel de ofertas-->
<script type="text/javascript">
//MICOD
//Aquí coloco cuantos Sliders hay en la página para hacerle la animación
    $(document).ready(function(){ 
      for (var i = 1; i <= num1; i++) {
        for (var o = 1; o <= num2; o++) {
          //alert("i: "+i+" o: "+o);
            localStorage["i"] = i;
            localStorage["o"] = o;
          $("#slider"+i+o).easySlider({ //Por cada interacción, capturamos el Div con el slider para hacer el siguiente trabajo JS
            auto: true, 
            continuous: true
          });
          /*MICOD: IMPORTANTE.
          Ésta función jQuery aplico los estilos CSS para cada una de las flechas de dirección del carrusel*/
          $("#prevBtn"+i+o).addClass("global1 global2");
          $("#prev"+i+o).addClass("botonprev");
          $("#nextBtn"+i+o).addClass("global1 global2 solonext");
          $("#next"+i+o).addClass("botonprev botonnext");
          /*MICOD
          Compensar estilo incompatible con Firefox*/
          if($.browser.mozilla){
          $(".journey").css("margin-top", "-10px");
          $(".cities").css("margin-top", "20px");
          }
          /*MICOD
          Compensar estilo incompatible con Internet Explorer*/
          var isIE11 = !!navigator.userAgent.match(/Trident.*rv\:11\./); //Método exclusivo para detectar Internet Explorer 11
          if(isIE11){
            $(".cities").css("margin-right", "-210px");
            $(".cities").css("margin-top", "0px");
          }
          /*MICOD
          Compensar estilo incompatible con Internet Explorer*/
          if($.browser.msie){
          $(".journey").css("margin-top", "-10px");
          }
        };
      };
    });

  (function($) {

  $.fn.easySlider = function(options){
    
    // default configuration properties
    var defaults = {      
      prevId:     'prevBtn',
      prevText:     'Previous',
      nextId:     'nextBtn',  
      nextText:     'Next',
      controlsShow: true,
      controlsBefore: '',
      controlsAfter:  '', 
      controlsFade: true,
      firstId:    'firstBtn',
      firstText:    'First',
      firstShow:    false,
      lastId:     'lastBtn',  
      lastText:     'Last',
      lastShow:   false,        
      vertical:   false,
      speed:      800,
      auto:     false,
      pause:      2000,
      continuous:   false, 
      numeric:    false,
      numericId:    'controls'
    }; 
    
    var options = $.extend(defaults, options);  
    //alert(localStorage["i"] + localStorage["o"]);
    this.each(function() {
      var obj = $(this);
      var s = $("li", obj).length;
      var w = 900;
      var h = 200;
      //alert("logitud: "+s+" / ancho: "+w+" / alto: "+h);
      var clickable = true;
      obj.width(w); 
      obj.height(h); 
      obj.css("overflow","hidden");
      var ts = s-1;
      var t = 0;
      $("ul", obj).css('width',s*500);      
      
      //MICOD
      /*Efecto de continuidad, copia el primer elemento y lo pega en el último
      Cuando se visualice la última posición (justo después de deslizarse), de dirigirá de forma instantánea 
      al primer elemento, dando la ilución de continuidad*/
      if(options.continuous){  
        $("ul", obj).prepend($("ul li:last-child", obj).clone().css("margin-left","-"+ w +"px")); //ANCHO DEL UL QUE SE DESLIZA
          if(s >= 4){//MICOD: Solo cumplirá la función si las ofertas mostradas en pantalla es igual o superior a 4
            $("ul", obj).append($("ul li:nth-child(2)", obj).clone());
            $("ul", obj).append($("ul li:nth-child(3)", obj).clone());
            $("ul", obj).append($("ul li:nth-child(4)", obj).clone());
            $("ul", obj).append($("ul li:nth-child(5)", obj).clone());
          }
        $("ul", obj).css('width',(s+1)*w); //ANCHO TOTAL DEL BLOQUE SLIDER
      };     
      
      if(!options.vertical) $("li", obj).css('float','left');
                
      if(options.controlsShow){
        var html = options.controlsBefore;       
        if(options.numeric){
          html += '<ol id="'+ options.numericId +'"></ol>';
        } else {
          /*MICOD
          Es aquí donde se generan las flechas de direccionamiento del carrusel, cada elemento tiene un ID único generado por el easySlider.
          También está asociado a la función de agregar clases de estilo CSS para cada uno.*/
          if(options.firstShow) html += '<span id="'+ options.firstId+localStorage["i"]+localStorage["o"] +'"><a href=\"javascript:void(0);\">'+ options.firstText +'</a></span>';
          html +=  '<span id="'+ options.prevId+localStorage["i"]+localStorage["o"] +'"><a id="prev'+localStorage["i"]+localStorage["o"] +'" href=\"javascript:void(0);\">'+ options.prevText +'</a></span>';
          html +=  '<span id="'+ options.nextId+localStorage["i"]+localStorage["o"] +'"><a id="next'+localStorage["i"]+localStorage["o"] +'" href=\"javascript:void(0);\">'+ options.nextText +'</a></span>';
          if(options.lastShow) html +=  '<span id="'+ options.lastId+localStorage["i"]+localStorage["o"] +'"><a href=\"javascript:void(0);\">'+ options.lastText +'</a></span>';
        };
        
        html += options.controlsAfter;            
        $(obj).after(html);                   
      };

      if(options.numeric){
        for(var i=0;i<s;i++){
          $(document.createElement("li"))
            .attr('id',options.numericId + (i+1))
            .html('<a rel='+ i +' href=\"javascript:void(0);\">'+ (i+1) +'</a>')
            .appendTo($("#"+ options.numericId))
            .click(function(){
              animate($("a",$(this)).attr('rel'),true);
            });
        };
      } else {
        $("a","#"+options.nextId+localStorage["i"]+localStorage["o"]).click(function(){   
          animate("next",true);
        });
        $("a","#"+options.prevId+localStorage["i"]+localStorage["o"]).click(function(){   
          animate("prev",true);       
        }); 
        $("a","#"+options.firstId+localStorage["i"]+localStorage["o"]).click(function(){    
          animate("first",true);
        });       
        $("a","#"+options.lastId+localStorage["i"]+localStorage["o"]).click(function(){   
          animate("last",true);       
        });   
      };
      
      function setCurrent(i){
        i = parseInt(i)+1;
        $("li", "#" + options.numericId).removeClass("current");
        $("li#" + options.numericId + i).addClass("current");
      };
      
      function adjust(){
        if(t>ts) t=0;
        if(t<0) t=ts;
        if(!options.vertical) {
          $("ul",obj).css("margin-left",(t*230*-1)); //NUEVA POSICIÓN QUE TOMARÁ JUSTO DESPUÉS DE DESLIZARSE
        } else {
          $("ul",obj).css("margin-left",(t*h*-1));
        }
        clickable = true;
        if(options.numeric) setCurrent(t);
      };
      
      function animate(dir,clicked){
        if (clickable){
          clickable = false;
          var ot = t;
          //alert("ot: "+ot+" t: "+t+" ts: "+ts);
          /*MICOD
          Autorizo la animación si las ofertas motradas en pantalla es mayor o igual a 4*/
          if (ts >= 4){ //Verifico si la cantidad de ofertas mostradas en pantalla es igual o mayor a 4
            switch(dir){
            case "next":
              t = (ot>=ts) ? (options.continuous ? t+1 : ts) : t+1;
              break;
            case "prev":
              t = (t<=0) ? (options.continuous ? t-1 : 0) : t-1;
              break; 
            case "first":
              t = 0;
              break;
            case "last":
              t = ts;
              break;
            default:
              t = dir;
              break;
          };
          }
          
          var diff = Math.abs(ot-t);
          var speed = diff*options.speed;
          if(!options.vertical) {
            p = (t*230*-1); //VELOCIDAD/DISTANCIA QUE SE DESLIZARÁ
            $("ul",obj).animate(
              { marginLeft: p },
              { queue:false, duration:speed, complete:adjust }
            );
          } else {
            p = (t*h*-1);
            $("ul",obj).animate(
              { marginTop: p },
              { queue:false, duration:speed, complete:adjust }
            );
          };
          
          if(!options.continuous && options.controlsFade){
            if(t==ts){
              $("a","#"+options.nextId+localStorage["i"]+localStorage["o"]).hide();
              $("a","#"+options.lastId+localStorage["i"]+localStorage["o"]).hide();
            } else {
              $("a","#"+options.nextId+localStorage["i"]+localStorage["o"]).show();
              $("a","#"+options.lastId+localStorage["i"]+localStorage["o"]).show();
            };
            if(t==0){
              $("a","#"+options.prevId+localStorage["i"]+localStorage["o"]).hide();
              $("a","#"+options.firstId+localStorage["i"]+localStorage["o"]).hide();
            } else {
              $("a","#"+options.prevId+localStorage["i"]+localStorage["o"]).show();
              $("a","#"+options.firstId+localStorage["i"]+localStorage["o"]).show();
            };          
          };        
          
          if(clicked) clearTimeout(timeout);
          if(options.auto && dir=="next" && !clicked){;
            timeout = setTimeout(function(){
              animate("next",false);
            },diff*options.speed+options.pause);
          };
      
        };
        
      };
      // init
      var timeout;
      /*MICOD
      Evento se animación automática*/
      if(options.auto){;
        timeout = setTimeout(function(){
          animate("next",false);
        },options.pause);
      };   
      
      if(options.numeric) setCurrent(0);
    
      if(!options.continuous && options.controlsFade){          
        $("a","#"+options.prevId+localStorage["i"]+localStorage["o"]).hide();
        $("a","#"+options.firstId+localStorage["i"]+localStorage["o"]).hide();        
      };        
      
    });
    
  };

})(jQuery);
</script>
<?php
//MICOD
} //Fin de la condición de captura de URL
?>
<!--/////////////////////////////////////////////////////////////////////////////////////////////////-->