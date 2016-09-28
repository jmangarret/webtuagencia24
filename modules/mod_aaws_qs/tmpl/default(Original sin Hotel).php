<?php
/**
 * Module parameters
 */
$class_sfx = htmlspecialchars($params->get('class_sfx'));
$tabs      = AawsQsHelper::getTabs($params);
 if($params->get('qs_style')==1){
 	$plan='';
 }else{
 	$plan="_".$params->get('qs_style');
 }
 
?>
<div id="quick-search<?php echo $class_sfx; ?>">
  <div class="tabs">
    <ul>
      <?php foreach($tabs as $tab): ?>
      <li class="<?php echo $tab[1]; ?>">
        <a href="#tab-<?php echo $tab[1]; ?>"><?php echo $tab[0]; ?></a>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php foreach($tabs as $tab): ?>
    <?php if($tab[1]=='air'){  ?>
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
    <?php }else{  ?>
       <div id="tab-<?php echo $tab[1]; ?>">
      <?php require JModuleHelper::getLayoutPath('mod_aaws_qs', 'default_'.$tab[1]); ?>
    </div>
    <?php }?>
 
    <?php endforeach; ?>
  </div>
</div>
