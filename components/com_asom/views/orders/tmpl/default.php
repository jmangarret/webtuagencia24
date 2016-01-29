<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */

$dir = $this->model->getState('filter_order_Dir');
$ord = $this->model->getState('filter_order');
$typeProduct = Array(1 => JText::_('ORDER.FILTER.AIR'),
					3=> JText::_('ORDER.FILTER.PLAN'),
					7 => JText::_('ORDER.FILTER.CRUISE'),
					2 => JText::_('ORDER.FILTER.HOTEL')

);
asort($typeProduct);
$states = 	  Array(1 => JText::_('ORDER.STATUS.1'),
					2=>  JText::_('ORDER.STATUS.2'),
					3 => JText::_('ORDER.STATUS.3'),
					4 => JText::_('ORDER.STATUS.4'),
					5 => JText::_('ORDER.STATUS.5'),
					6 => JText::_('ORDER.STATUS.6')

);
$search=$_POST['search'];
$selectedProductType = $search['product_type'];
$selectedProductState = intval($search['product_state']);
?>
<script type="text/javascript" src="components/com_asom/js/cal_historial.js" ></script>
<div id="aom-bookings">
  <h3><?php echo JText::_('AOM_MY_BOOKINGS'); ?></h3>
  <form action="<?php echo JRoute::_(AsomHelperRoute::getRoute('orders.display')); ?>" method="post" name="adminForm" id="searchOrders" autocomplete='off'>
    <table cellspacing="0" cellpadding="2" width="100%" align="center" border="0">
		<tr class="tr_fondo_blanco_reserva_plan">
			<td width="12%">
				<span class="pie_descripcion_fila_resultados_1"><?php echo JText::_('ORDERS.SEARCH.ORDER.NUMBER'); ?>:</span> <br>
				<input type="text" style="width:85px;" name="search[order_number]"  id="order_number" value="<?php echo htmlspecialchars($search['order_number']);?>" class="pie_descripcion_fila_resultados_1"  maxlength=5/>
			</td>
			<td>
			<span class="pie_descripcion_fila_resultados_1"><?php echo JText::_('ORDERS.SEARCH.RECORD'); ?> :</span> <br>
			<input type="text" style="width:85px;" name="search[record]" id="record" value="<?php echo $search['record'];?>" class="pie_descripcion_fila_resultados_1"  maxlength=9/>
			</td>
			</tr>
			<tr>
			<td width="16%" class="pie_descripcion_fila_resultados_1">
				<span class="pie_descripcion_fila_resultados_1"><?php echo JText::_('ORDERS.ORDER.DETAIL.STATE.LABEL'); ?> :</span> <br>
				<select id="IdEstado" name="search[product_state]" class="pie_descripcion_fila_resultados_1">
					<option value=""><?php echo JText::_('ORDERS.SEARCH.ORDER.TYPE.ALL.SELECT'); ?></option>
                  	<?php
					foreach ($states AS $key => $valor) {
					$selected = ($selectedProductState== (int)$key)?"selected":"";
					?>
						<option value="<?php echo $key; ?>" <?php echo $selected ?> ><?php echo $valor; ?></option>
					<?php
					}
					?>
                
				</select>
			</td>
			<td width="18%" class="td_fondo_reserva_plan_izq">
				<span class="pie_descripcion_fila_resultados_1"><?php echo JText::_('ORDERS.SEARCH.FROM.DATE'); ?>:</span> <br>
				<input type="text" style="width:85px;margin-right:5px;" readonly="readOnly" value="<?php echo $search['from_date']; ?>" id="fechaDesde" class="pie_descripcion_fila_resultados_1" name="search[from_date]">
			</td>
			<td width="18%" class="td_fondo_reserva_plan_izq">
				<span class="pie_descripcion_fila_resultados_1"><?php echo JText::_('ORDERS.SEARCH.TO.DATE'); ?>:</span> <br>
				<input type="text" style="width:85px;margin-right:5px;" readonly="readOnly" value="<?php echo $search['to_date']; ?>" id="fechaHasta" class="pie_descripcion_fila_resultados_1" name="search[to_date]">
			</td>
 
			<td class="td_fondo_reserva_plan_izq" width="15%">
				<span class="pie_descripcion_fila_resultados_1"><?php echo JText::_('ORDERS.SEARCH.ORDER.TYPE.OPTION.TYPEPRODUCT'); ?> :</span> <br>
				<select id="producType" name="search[product_type]" class="pie_descripcion_fila_resultados_1">
					<option value=""><?php echo JText::_('ORDERS.SEARCH.ORDER.TYPE.OPTION.SELECT'); ?></option>
					<?php
					foreach ($typeProduct AS $key => $valor) {
					$selected = ($selectedProductType == (int)$key)?"selected":"";
					?>
						<option value="<?php echo $key; ?>" <?php echo $selected ?> ><?php echo $valor; ?></option>
					<?php
					}
					?>
				</select>
			</td>
			</tr><tr>
			<td colspan="2"></td>
			<td>
				<input type="button" onclick="validateOrderSearch();" class="button" value="<?php echo JText::_('ORDERS.SEARCH.BUTTON.NAME'); ?>" />
			</td><td>
				<input type="button" onclick="limpiar();" class="button" value="<?php echo JText::_('ORDERS.SEARCH.CLEAR.NAME'); ?>" />
			</td>
		</tr>
	</table>
    <br><br>
    <?php   if(count($this->data)<1){
     	echo JText::_('MSN.ERROR.3');
     }else{?>
    <table class="adminlist">
      <thead>
        <tr>
          <th width="20%" class="title"><?php echo JHTML::_('grid.sort', JText::_('AOM_RECORD'), 'od.id', $dir, $ord ); ?></th>
          <th width="20%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AOM_RECLOC'), 'od.recloc', $dir, $ord ); ?></th>
          <th width="20%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AOM_CUSTOMER_NAME'), 'od.firstname', $dir, $ord ); ?></th>
          <th width="20%"><?php echo JHTML::_('grid.sort',JText::_('AOM_STATUS'),'od.status',$dir, $ord ); ?></th>
          <th width="20%"><?php echo JHTML::_('grid.sort',JText::_('AOM_PRODUCT'),'od.product_type',$dir, $ord ); ?></th>
          <th width="20%"><?php echo JHTML::_('grid.sort',JText::_('AOM_PRODUCT_NAME'),'od.product_name',$dir, $ord ); ?></th>
          <th width="20%" nowrap="nowrap" class="date"><?php echo JHTML::_('grid.sort', JText::_('AOM_FECSIS'), 'od.fecsis', $dir, $ord ); ?></th>
          <th width="20%" nowrap="nowrap" class="date"><?php echo JHTML::_('grid.sort', JText::_('AOM_FECUP'), 'py.fecsis', $dir, $ord ); ?></th>
        </tr>
       
      </thead>
      <tfoot>
        <td colspan="7"><?php echo $this->pagination->getListFooter();?></td>
      </tfoot>
      <tbody>
      <?php
      $k = 0;
      $page = $this->pagination;
   
      for ($i = 0, $n = count($this->data); $i < $n; $i++) {
          $row  = &$this->data[$i];
          $edit = JRoute::_(AsomHelperRoute::getRoute('orders.select&cid='.$row->id),false);
       ?>
         <tr class="<?php echo "row$k"; ?>">
	       <td align="right" width="20%"><?php echo $row->id; ?></td>
           <td align="center" width="20%"><a href='<?php echo $edit; ?>'><?php echo $row->recloc; ?></a></td>
           <td width="20%"><?php echo $row->lastname.' '.$row->firstname; ?></td>
           <td width="20%"><?php echo $row->statusTkt; ?></td>
           <td width="20%"><?php echo $typeProduct[$row->product_type]; ?></td>
           <td width="20%"><?php echo $row->product_name; ?></td>
           <td width="20%"><?php echo $row->fectrans;   ?></td>
           <td width="20%"><?php echo $row->fecmod;  ?>
           </td>
         </tr>
        <?php $k = 1 - $k; } ?>
      </tbody>
    </table>
    <?php }?>
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $dir; ?>" />
    <input type="hidden" name="filter_order" value="<?php echo $ord; ?>" />
  </form>
</div>

<script type="text/javascript">
function validateOrderSearch() {
 	var forma = document.getElementById('searchOrders');
	forma.submit();
}
function limpiar() {
	document.getElementById('order_number').value= '';
	document.getElementById('record').value= '';
	document.getElementById('fechaDesde').value= '';
	document.getElementById('fechaHasta').value= '';
	document.getElementById('IdEstado').value= '';
	document.getElementById('producType').value= '';
	
 
	var forma = document.getElementById('searchOrders');
	forma.submit();
}

$(function(){
    //Para escribir solo numeros    
    $('#order_number').validCampoFranz('0123456789');    
});
(function(a){a.fn.validCampoFranz=function(b){a(this).on({keypress:function(a){var c=a.which,d=a.keyCode,e=String.fromCharCode(c).toLowerCase(),f=b;(-1!=f.indexOf(e)||9==d||37!=c&&37==d||39==d&&39!=c||8==d||46==d&&46!=c)&&161!=c||a.preventDefault()}})}})(jQuery);
</script>