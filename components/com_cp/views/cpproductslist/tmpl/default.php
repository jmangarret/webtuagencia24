<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$featuredText = JText::_('COM_CP_FEATURED');
$item = JRequest::getInt('Itemid')? '&Itemid=' . JRequest::getInt('Itemid'): '';
$currency = $this->params->get('cfg_currency');
?>
<form name="searchproducts" id="searchproducts" action="<?php echo JRoute::_( 'index.php' ); ?>" method="post">
	<div class="centro_disponibilidad_planes"><!-- Abro div centro_disponibilidad_planes -->
		<div class="top_zona_verde_dos"><!-- Abro div top_zona_verde -->
			<span><?php echo JText::_('COM_CP_SEARCH_RESULTS'); ?></span>
			<span class="datos_espe">
				<?php echo JText::_('COM_CP_ORDER_LABEL'); ?>&nbsp;
				<select id="order_field" class="campo_lista_pasajeros" name="order_field" onchange="document.searchproducts.submit();">
				  <option value="ordering"<?php if ($this->order_field == 'ordering') echo ' selected="selected"'; ?>><?php echo JText::_('COM_CP_SELECT'); ?></option>
				  <option value="price"<?php if ($this->order_field == 'price') echo ' selected="selected"'; ?>><?php echo JText::_('COM_CP_PRICE'); ?></option>
				  <option value="product_name"<?php if ($this->order_field == 'product_name') echo ' selected="selected"'; ?>><?php echo JText::_('COM_CP_PRODUCT_NAME'); ?></option>
				</select>
			</span>
			<div class="clear"></div>
			<input type="hidden" value="<?php echo JRequest::getVar('country_code'); ?>" name="country_code" />
			<input type="hidden" value="<?php echo JRequest::getVar('city'); ?>" name="city" />
			<input type="hidden" value="<?php echo JRequest::getVar('tourismtype_id'); ?>" name="tourismtype_id" />
			<input type="hidden" value="<?php echo JRequest::getVar('category_id'); ?>" name="category_id" />
		</div><!-- Cierro div top_zona_verde -->


		<div class="num_link_resultados_vuelos"><!-- Abro div seleccione_vuelos -->
			<div class="num_resultados"><?php echo $this->pagination->getPagesCounter(); ?></div>
			<div class="clear"></div>
		</div>
		<!-- Cierro div seleccione_vuelos -->

		<?php foreach($this->data as $dataItem): ?>
		<?php
			$link = JRoute::_( "index.php?option=com_cp&view=cpproducts&id={$dataItem->product_id}$item" );
			$resultprice = $this->getModel()->formatNumber($dataItem->price, $currency);
			if (empty($resultprice)) {
				$price = $dataItem->price;
			} else {
				$price = $resultprice[0];
			}
			if (empty($dataItem->file_url)) {
				$dataItem->file_url = $this->params->get('cfg_no_image_url');
			}
			if ($dataItem->featured) $dataItem->product_name .= '&nbsp;<img width="16" height="16" title="' . $featuredText . '" alt="' . $featuredText . '" src="' . JURI::base(true) . '/components/com_cp/assets/images/featured_icon.png" />';
		?>
		<div class="fila_resultado_planes"><!-- Abro div fila_resultado_planes -->
			<div class="conte_dispo_planes2" style="float:left; width:80%;"><!-- Abro div conte_dispo_planes -->
				<table cellspacing="5" cellpadding="5" border="0">
				  <tbody>
				  <tr>
				    <td class="foto_dispo_plan"><a href="<?php echo $link; ?>"><img width="140" height="90" src="<?php echo $dataItem->file_url; ?>"></a></td>
				    <td><h2><a href="<?php echo $link; ?>"><?php echo $dataItem->product_name; ?></a></h2><?php echo $dataItem->product_desc; ?></td>
				  </tr>
				</tbody>
				</table>
			</div><!-- Cierro div conte_dispo_planes -->

			<div class="tarifas_vuelos2"  style="float:left; width:20%;"><!-- Abro div tarifas_vuelos -->
			  <table cellspacing="2" cellpadding="2" border="0" id="cp_details">
			    <tbody>
			    	<tr><td colspan="2"><?php echo JText::_('COM_CP_FROM'); ?></td></tr>
			    	<tr><td><span class="precio_tarifas_vuelos" colspan="2">Bs.&nbsp;<?php echo number_format($price, 0, ',', '.'); ?></span></td>
			      </tr>
				    <tr>
				      <td>&nbsp;</td>
				      <td>&nbsp;</td>
				    </tr>
				    <tr>
				      <td colspan="2"><input type="button" onclick="location.href='<?php echo $link; ?>';" value="Seleccionar" id="button2" class="boton_buscar" name="button2"></td>
				    </tr>
			    </tbody>
			  </table>
			</div><!-- Cierro div tarifas_vuelos -->
			<div class="clear"></div>
		</div><!-- Cierro div fila_resultado_planes -->
		<?php endforeach; ?>
		<?php if (count($this->data) < $this->getModel()->getTotal()): ?>
		<div style="text-align:right;" class="paginacion_dispo_planes"><?php echo $this->pagination->getPagesLinks(); ?></div>
		<?php endif; ?>
		<div class="clear"></div>
	</div>

	<div class="clear"></div>

	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />
	<input type="hidden" name="option" value="com_cp" />
	<input type="hidden" name="view" value="cpproductslist" />
	<input type="hidden" name="isnewsearch" value="<?php echo $this->isnewsearch; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>