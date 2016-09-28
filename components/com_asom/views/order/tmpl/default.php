<div id="resume-order">
  <div class="resume-description">
   
      <?php echo $this->loadTemplate('description'); ?>
    
  </div>

  <div id="resume-flights"  >
  <?php //style="display:none"
  //Plantilla de detalle para aereo
  if($this->Order->product_type==1){ echo $this->loadTemplate('flights'); }?>
  <div class="clear"></div>
  </div>

  <div class="clear"></div>
 
</div>
