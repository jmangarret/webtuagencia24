<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */

jimport('joomla.html.pane');
$objUser = new JObject();
$objUser->set('name', JText::_('AOM_SYSTEM_USER'));
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
/* <![CDATA[ */
Joomla.submitbutton = function(pressbutton) {
    if (pressbutton === 'orders.cancel') {<?php $id='';?>
        submitform(pressbutton);
    }else{ 
        var err=false;
        var msg = new Array();
        if (jQuery('#nota').val()===''){
            msg.push ('<?php echo JText::_('AOM_ERROR_OBSERV')?>');
            err = true;
        }
        if (jQuery('#old_state').val()===jQuery('select#status option:selected').val()){
            msg.push ('<?php echo JText::_('AOM_ERROR_ESTADO_IGUAL')?>');
            err = true;
        }
         
        if (!err) {
            submitform(pressbutton);    
        }else{
            alert (msg.join('\n'));
            return false;
        }
    }    
}
/* ]]> */
</script>
<form action="<?php echo $this->url; ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate">
  <div class="width-50 fltlft">
    <div class="width-100">
      <?php echo $this->loadTemplate('order'); ?>
    </div>
  </div>
  <div class="width-50 fltrt">
    <div class="width-100">
      <?php echo $this->loadTemplate('passengers'); ?>
    </div>
  </div>
  <div class="width-100 clrlft clrrt">
    <div class="width-100">
      <?php echo $this->loadTemplate('details'); ?>
    </div>
  </div>
  <input type="hidden" name="task" value="">
  <input type="hidden" name="id" id="id" value="<?php echo $this->data->getOrder()->id; ?>">
</form>
