<?php
/**
 * Module parameters
 */
 /*---------------------------------------------------------------*/
 //MICOD
 /*En éste espacio capturo la URL actual de la página para determinar mis acciones.
 Si estoy ubicadio en la págin "disponibilidad" quiere decir que estoy mostrado
 los resultados de la búsqueda de vuelos y debo deshabilitar el mini-buscador de hoteles
 del módulo de vuelos, de no ser así entonces estoy en cualquier otra página y puedo
 habilitar el mini-buscador de vuelos.*/
$url=$_SERVER['REQUEST_URI'];//Obtenemos la URL de la página actual.
 $ubicación= substr($url, -14);
 /*---------------------------------------------------------------*/
//micod
//Le asigno el valor "1" a la posición para que habilite el Tab del hotel
$params->set('hotel_vsb',"1"); //La variable params es un archivo Json ".XML" y puedo tratarlo como objeto
$class_sfx = htmlspecialchars($params->get('class_sfx'));
$tabs      = AawsQsHelper::getTabs($params);

 if($params->get('qs_style')==1){
  $plan='';
 }else{
  $plan="_".$params->get('qs_style');
 }
 /*---------------------------------------------------------------*/
 //MICOD
 /*Si de la variable "$ubicación un valor diferente a "disponibilidad" entonces
 no estoy en la pantalla de los resultados de vuelos, lo que quiere decir que puedo mostrar
 todos los tabs*/
 if ($ubicación!="disponibilidad") { //Si no cumple con la condición entonces muestro todos los tabs
 $titulos = array('Vuelos', 'Hoteles'); //MICOD - Arreglo donde cargo los nombres de olos tabs en los mini-buscadores de la página de inicio
 }else{
  $titulos = array('Vuelos'); //Solo mostrará el TAB de Vuelos
 }
 /*---------------------------------------------------------------*/
 $i=0;
?>
<div id="quick-search<?php echo $class_sfx; ?>">
  <div class="tabs">
    <ul>
    <?php
      /*---------------------------------------------------------------*/
      //MICOD
      /*Hay un problema con los tabs, si elimino el de hoteles, el módulo de buscador de vuelos no aparece,
      pero su respectivo Tab si aparece, el siguiente código repara éste error.*/
      if ($ubicación!="disponibilidad") { //Se verifica la condición, sino cumple, aplico el FOREACH para los tabs
      foreach($tabs as $tab){ ?>
      <li class="<?php echo $tab[1]; ?>">
        <a href="#tab-<?php echo $tab[1]; ?>"><?php echo $titulos[$i]; ?></a><!--MICOD - Originalmente en "echo $tab[0]"-->
      </li>
      <?php 

      $i++; 

      }

      }else{ //Sino solo imprimo las etiquetas HTML con la posición manual del titulo del Tab?>
      <li class="<?php echo $tab[1]; ?>">
        <a href="#tab-<?php echo $tab[1]; ?>"><?php echo $titulos[0]; ?></a><!--MICOD - Originalmente en "echo $tab[0]"-->
      <?php }
      /*---------------------------------------------------------------*/
      ?>
    </ul>
    <?php foreach($tabs as $tab): ?>
    <?php if($tab[1]!='air'){  
      /*---------------------------------------------------------------*/
      //MICOD
      /*En éste espacio verifico si la variable cumple o no con la condición, si no cumple
      la condición se encargará de no renderizar el mini-buscador de hoteles en el módulo
      de vuelos, en caso contrario lo mostrará. Todo está sujeto a la ubicación actual
      de la URL de la página*/
      if ($ubicación!="disponibilidad") { 
        ?>
       <div id="tab-<?php echo $tab[1]; ?>">
      <?php
         $nombre_fichero ='modules/mod_jhotelreservation/tmpl/default-vertical.php';
          $link = fopen($nombre_fichero, "r");
          if($link){
            //micod
            //Importar la función de módulos de Joomla para renderizar el módulo de hoteles
            jimport( 'joomla.application.module.helper' ); //Importar la aplicación
            $module = JModuleHelper::getModule( 'mod_jhotelreservation'); //Llamar la función de renderizar módulos
              
              //MICOD
              $error= substr($url, -11);
              if ($error != 'index.php?e'){//Realizamos la condición pra verificar si el error existe.
              echo JModuleHelper::renderModule( $module ); //Mostrarlo en pantalla
              }
          }
          ?>
    </div>
    <?php } 
    /*---------------------------------------------------------------*/
    }else{  ?>
       <div id="tab-<?php echo $tab[1]; ?>">
      <?php 
   $nombre_fichero ='modules/mod_aaws_qs/tmpl/default_'.$tab[1].$plan.'.php';
$link = fopen($nombre_fichero, "r");
if($link){
require JModuleHelper::getLayoutPath('mod_aaws_qs', 'default_'.$tab[1].$plan);
} else {
require JModuleHelper::getLayoutPath('mod_aaws_qs', 'default_'.$tab[1]);
} 


      ?>
    </div>
    <?php }?>
 
    <?php endforeach; ?>
  </div>
</div>
