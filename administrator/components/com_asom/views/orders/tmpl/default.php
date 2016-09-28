<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */
JHTML::_('behavior.calendar');  
?>
<script type="text/javascript">
/* <![CDATA[ */
window.addEvent('domready', function() {PrepareCalendars();});
function PrepareCalendars(){
    jQuery(function($){
        $.datepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: '&#x3c;Ant',
                nextText: 'Sig&#x3e;',
                currentText: 'Hoy',
                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
                'Jul','Ago','Sep','Oct','Nov','Dic'],
                dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
                dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''};
        $.datepicker.setDefaults($.datepicker.regional['es']);
});
    jQuery.datepicker.setDefaults(jQuery.datepicker.regional["es"]);
    jQuery( "#fec_ini" ).datepicker({ 
       dateFormat: "yy-mm-dd",
       singleClick: true,
       firstDay:1,
       maxDate: '0',
       regional:'es'
    });
    
    jQuery( "#fec_fin" ).datepicker({ 
       dateFormat: "yy-mm-dd",
       singleClick: true,
       firstDay:1,
       maxDate: '0',
       regional:'es'
   });
}
jQuery("#toolbar-edit").hide();

function validar(){
    if (compare_dates(document.adminForm.fec_ini.value, document.adminForm.fec_fin.value)){  
        alert('<?php echo JText::_('AOM_ERROR'); ?>');
        return false;
    }else{  
      //this.form.submit();
    }  
}
 function compare_dates(fecha, fecha2)  
  {  
    var xMonth=fecha.substring(5, 7);  
    var xDay=fecha.substring(8, 10);  
    var xYear=fecha.substring(0,4);  
    var yMonth=fecha2.substring(5, 7);  
    var yDay=fecha2.substring(8,10);  
    var yYear=fecha2.substring(0,4);  
    if (xYear> yYear)  
    {  
        return(true);
    }  
    else  
    {  
      if (xYear === yYear)  
      {   
        if (xMonth> yMonth)  
        {  
            return(true);  
        }  
        else  
        {   
          if (xMonth === yMonth)  
          {  
            if (xDay> yDay)  
              return(true);  
            else  
              return(false);  
          }  
          else  
            return(false);  
        }  
      }  
      else  
        return(false);  
    }  
}  

/* ]]> */
</script>
<?php
$dir    = $this->direction;
$ord    = $this->order;
$id     = $this->id;
$recloc = $this->recloc;
$contact= $this->contact;
$product_type= $this->product_type;
$fec_ini= $this->fec_ini;
$fec_fin= $this->fec_fin;

$resetFilters = '';
foreach($this->filters as $filter)
    $resetFilters .= 'document.adminForm.'.$filter.'.value=\'\';';
?>
<form action="<?php echo $this->url; ?>" method="post" name="adminForm" autocomplete='off' onsubmit= "return validar();">
  <table width='100%' border="0">
    <tr>
        <td nowrap="nowrap"><?php echo JText::_('AOM_ID_ORDER'); ?>:</td>
        <td nowrap="nowrap"><input type="text" name="id" id="id" value="<?php echo $id; ?>" class="text_area"></td>
        <td nowrap="nowrap"><?php echo JText::_('AOM_RECLOC'); ?>:</td>
        <td nowrap="nowrap"><input type="text" name="recloc" id="recloc" value="<?php echo $recloc; ?>" class="text_area"></td>
        <td nowrap="nowrap"><?php echo JText::_('AOM_CONTACT'); ?>:</td>
        <td nowrap="nowrap"><input type="text" size="60" name="contact" id="contact" value="<?php echo $contact; ?>" class="text_area"></td>      
    </tr><tr>
        <td nowrap="nowrap"><?php echo JText::_('AOM_FEC_INI'); ?>:</td>
        <td nowrap="nowrap"><input type="text" name="fec_ini" readonly="readonly" id="fec_ini" value="<?php echo $fec_ini; ?>" class="text_area"></td>
        <td nowrap="nowrap"><?php echo JText::_('AOM_FEC_FIN'); ?>:</td>
        <td nowrap="nowrap"><input type="Calendar" name="fec_fin" readonly="readonly" id="fec_fin" value="<?php echo $fec_fin; ?>" class="text_area"></td>
        <td nowrap="nowrap">
          <?php echo JText::_('AOM_PRODUCT_TYPE'); ?>:
          <select id="product_type" name="product_type">
            <option value=""><?php echo JText::_('AOM_SELECT'); ?>:</option>
            <option value="<?php echo JText::_('AOM_PRODUCT_TYPE_AIR_VALUE'); ?>" <?php if (JText::_('AOM_PRODUCT_TYPE_AIR_VALUE')==$product_type) echo 'selected';?>>
                <?php echo JText::_('AOM_PRODUCT_TYPE_AIR'); ?></option>
            <option value="<?php echo JText::_('AOM_PRODUCT_TYPE_HOTEL_VALUE'); ?>" <?php if (JText::_('AOM_PRODUCT_TYPE_HOTEL_VALUE')==$product_type) echo 'selected';?>>
                <?php echo JText::_('AOM_PRODUCT_TYPE_HOTEL'); ?></option>
            <option value="<?php echo JText::_('AOM_PRODUCT_TYPE_PLAN_VALUE'); ?>" <?php if (JText::_('AOM_PRODUCT_TYPE_PLAN_VALUE')==$product_type) echo 'selected';?>>
                <?php echo JText::_('AOM_PRODUCT_TYPE_PLAN'); ?></option>
            <option value="<?php echo JText::_('AOM_PRODUCT_TYPE_TOUR_VALUE'); ?>" <?php if (JText::_('AOM_PRODUCT_TYPE_TOUR_VALUE')==$product_type) echo 'selected';?>>
                <?php echo JText::_('AOM_PRODUCT_TYPE_TOUR'); ?></option>
            <option value="<?php echo JText::_('AOM_PRODUCT_TYPE_AUTO_VALUE'); ?>" <?php if (JText::_('AOM_PRODUCT_TYPE_AUTO_VALUE')==$product_type) echo 'selected';?>>
                <?php echo JText::_('AOM_PRODUCT_TYPE_AUTO'); ?></option>
            <option value="<?php echo JText::_('AOM_PRODUCT_TYPE_TRAS_VALUE'); ?>" <?php if (JText::_('AOM_PRODUCT_TYPE_TRAS_VALUE')==$product_type) echo 'selected';?>>
                <?php echo JText::_('AOM_PRODUCT_TYPE_TRAS'); ?></option>
          </select></td>
        <td nowrap="nowrap"><?php echo JText::_('AOM_STATUS').':'.$this->filterStatus(''); ?></td>
     </tr>
        <td align="right" colspan="6" nowrap="nowrap">
          <button onclick="validar();"><?php echo JText::_('GO'); ?></button>
          <button onclick="<?php echo $resetFilters; ?>"><?php echo JText::_('RESET'); ?></button>
        </td>
    </tr>
  </table>
  <table class="adminlist">
    <thead>
      <tr>
        <th width="20" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AOM_ID'), 'od.id', $dir, $ord ); ?></th>
        <th width="100" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AOM_RECLOC'), 'od.recloc', $dir, $ord ); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AOM_CUSTOMER_NAME'), 'od.lastname', $dir, $ord ); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AOM_STATUS'), 'st.name', $dir, $ord ); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AOM_PRODUCT_TYPE'), 'od.product_type', $dir, $ord ); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AOM_PRODUCT_NAME'), 'od.product_name', $dir, $ord ); ?></th>
        <th width="20%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AOM_FECSIS'), 'od.fecsis', $dir, $ord ); ?></th>
        <th width="20%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AOM_FECMOD'), 'his.fecsis', $dir, $ord ); ?></th>
      </tr>
    </thead>
    <tfoot>
      <td colspan="12"><?php echo $this->pagination->getListFooter();?></td>
    </tfoot>
    <tbody>
    <?php
    $k = 0;
    $page = $this->pagination;
    if (count($this->data)>0){
        for ($i = 0, $n = count($this->data); $i < $n; $i++) {
            $row       = &$this->data[$i];
            $published = JHTML::_('grid.published', $row, $i );
            $edit      = JRoute::_($this->url.'&task=orders.edit&cid[]='.$row->id, false);
            $status    = $this->getStatus($row->status);
            $fec_modi  = $this->getFecModificacion($row->id);
            if ($row->product_type==1)$tipo=JText::_('AOM_PRODUCT_TYPE_AIR');
            if ($row->product_type==2)$tipo=JText::_('AOM_PRODUCT_TYPE_HOTEL');
            if ($row->product_type==3)$tipo=JText::_('AOM_PRODUCT_TYPE_PLAN');
            if ($row->product_type==4)$tipo=JText::_('AOM_PRODUCT_TYPE_TOUR');
            if ($row->product_type==5)$tipo=JText::_('AOM_PRODUCT_TYPE_AUTO');
            if ($row->product_type==6)$tipo=JText::_('AOM_PRODUCT_TYPE_TRAS');
         ?>
           <tr class="<?php echo "row$k"; ?>">
             <td align="right"><a href='<?php echo $edit; ?>'><?php echo $row->id; ?></a></td>
             <td align="center"><?php echo $row->recloc; ?></td>
             <td class="customer"><?php echo $row->firstname; ?></td>
             <td><?php echo $status['name']; ?></td>
             <td align='left'><?php echo $tipo; ?></td>
             <td align='left'><?php echo $row->product_name; ?></td>
             <td align='center'><?php echo JHTML::date($row->fecsis, 'l, d F Y g:i a'); ?></td>
             <td align='center'><?php echo JHTML::date($row->fecmod, 'l, d F Y g:i a'); ?></td>
           </tr>
        <?php $k = 1 - $k; } 
     }else{
     ?>
           <tr><td colspan="8"><?php echo JText::_('AOM_RESULT_0'); ?></td></tr>    
         <?php
     } ?>
    </tbody>
  </table>
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="status" value="" />
  <input type="hidden" name="note" value="" />
  <input type="hidden" name="task" value="orders.display" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $dir; ?>" />
  <input type="hidden" name="filter_order" value="<?php echo $ord; ?>" />
</form>