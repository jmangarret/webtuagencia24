<?php
//Contador de orden por defecto
$contOrder=0;
//Precio minimo para filtros
$minPrice = 0;
//Precio maximo para filtros
$maxPrice = 0;
//Arreglo de zonas para filtros
$arrayZones = array();

//Arreglo de tipo de alojamiento para filtros
$arrayCategory = array();

$extraUrl = "";
if(isset($this->params["qsearch"]) && $this->params["qsearch"]==1){
  $extraUrl = "&qs=1";
}
?>
<div
	class="centro_disponibilidad_planes">
	<!-- Abro div centro_disponibilidad_planes -->
	<div class="top_titulo_cp" id="scrollToHere">
		<!-- Abro div top_zona_verde -->
		<span><?php echo JText::_('CP.CAR.SEARCHRESULT'); ?> </span> <span
			class="datos_espe"> <?php echo JText::_('CP.CAR.ORDER.LABEL'); ?>&nbsp;
			<select id="order_field" class="campo_lista_pasajeros"
			name="order_field">
				<option value="0">
				<?php echo JText::_('CP.CAR.ORDER.OPTION.SELECT'); ?>
				</option>
				<option value="1">
				<?php echo JText::_('CP.CAR.ORDER.OPTION.PRICE'); ?>
				</option>
				<option value="2">
				<?php echo JText::_('CP.CAR.ORDER.OPTION.PRODUCTNAME'); ?>
				</option>
		</select> </span>
		<div class="clear"></div>
	</div>
	<!-- Cierro div top_zona_verde -->
	<div id="contentProducts">
	<?php foreach($this->data["products"]["product"] as $product):
	$currency = $product["price"]["currency"]["symbol"];
	//Obtenemos el menor precio
	if($minPrice==0):
	$minPrice = (double)$product["price"]["value"];
	elseif($minPrice>(double)$product["price"]["value"]):
	$minPrice = (double)$product["price"]["value"];
	endif;
	//obtenemos el mayor precio
	if($maxPrice<(double)$product["price"]["value"]):
	$maxPrice = (double)$product["price"]["value"];
	endif;
	//Arreglo de zonas para los filtros
	$arrayZones[$product["zone"]["id"]] = $product["zone"]["name"];
	//Arreglo de tipo de alojamiento para los filtros
	if($product["category"]["name"]!=""){
	  $arrayCategory[$product["category"]["id"]] = $product["category"]["name"];
	}else{
	  $arrayCategory[0] = JText::_("CP.CAR.FILTER.CATEGORY.OPTION.OTHER");
	}

	//Link de detalles
	$link = JRoute::_("index.php?option=com_catalogo_planes&view=".$this->viewName."&layout=detail&productId=".$product["id"].$extraUrl);
	//Aumentamos en uno el contador para el orden por defecto
	$contOrder++;
	//Llamamos a la funcion de la vista que se encarga de darle formato a la moneda
	$productPrice = $this->getFormatPrice((double)$product["price"]["value"],$currency);

	$previousPrice = 0;
	//Validamos si existe precio anterior
	if($product["price"]["previous_value"] !="" && $product["price"]["previous_value"]>0){
	  //Llamamos a la funcion de la vista que se encarga de darle formato a la moneda
	  $previousPrice = $this->getFormatPrice((double)$product["price"]["previous_value"],$currency);
	}
	//Validamos si retorna imagen
	if (empty($product["image"])) $product["image"] = $this->catalogoComponentConfig->get("cfg_no_image_url");
	?>

		<div class="fila_resultado_planes"
			price="<?php echo $product["price"]["value"]?>"
			name="<?php echo $product["name"]; ?>"
			default_order="<?php echo $contOrder?>"
			stars="<?php echo $product["stars"];?>"
			zone="<?php echo $product["zone"]["id"]?>"
			accomodation="<?php echo ($product["category"]["name"]!="")?$product["category"]["id"]:"0";?>">
			<!-- Abro div fila_resultado_planes -->

			<div class="conte_dispo_planes">
				<!-- Abro div conte_dispo_planes -->
				<table width="100%" cellspacing="5" cellpadding="5" border="0">
					<tbody>
						<tr>
							<td class="foto_dispo_plan"><a href="<?php echo $link; ?>"> <img
									width="170" height="120" src="<?php echo $product["image"]; ?>" />
							</a>
							</td>
							<td>
								<div class="rating_titulo">
								<?php if($product["rating"]>0):?>
									<div class="seccion_rating">
									<?php echo $product["rating"]." ".JText::_("CP.CAR.PRODUCT.POINTS");?>
									</div>
									<?php endif;?>
									<div class="seccion_titulo">
										<a href="<?php echo $link; ?>"><?php echo $product["name"]; ?>
										</a>&nbsp;
										<?php echo ($product["featured"]==1)?"<img src='".JURI::base()."/components/com_catalogo_planes/assets/images/featured_icon.png' title='".JText::_("CP.FEATURED.TITLE")."'/>":"";?>
									</div>
									<div class="clear"></div>
								</div>
								<div class="seccion_destino">
								<?php echo $product["country"]["name"]; ?>
									-
									<?php echo $product["city"]["name"]; ?>
								</div>
								<div class="seccion_detalles">
								<?php echo $product["description"]; ?>
								</div> <?php if($product["latitude"]!="" && $product["longitude"]!=""):?>
								<div class="seccion_mapa">
									<a href="javascript: void(0)" class="link_map"
										rel="<?php echo $product["latitude"].":".$product["longitude"]?>">
										<?php echo JText::_('CP.CAR.MAP');?> </a>
								</div> <?php elseif($product["url"]!="" && preg_match('/^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,4}|travel)(:\d{2,5})?(\/.*)?$/i',  base64_decode($product["url"]))):?>
								<div class="seccion_mapa">
									<a href="javascript: void(0)" class="url_map"
										rel="<?php echo  base64_decode($product["url"])?>"> <?php echo JText::_('CP.CAR.MAP');?>
									</a>
								</div> <?php endif;?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- Cierro div conte_dispo_planes -->

			<div class="tarifas_vuelos">
				<!-- Abro div tarifas_vuelos -->
				<table width="100%" cellspacing="2" cellpadding="2" border="0">
					<tbody>
						<tr>
							<td style="text-align: center" colspan="2"><?php echo JText::_('CP.CAR.PRICE.DESCRIPTION'); ?>
							</td>
						</tr>
						<tr>
							<td style="text-align: center" colspan="2"><?php echo JText::_('CP.CAR.FROM'); ?>
							</td>
						</tr>
						<tr>
							<td class="precio_tarifas_vuelos" colspan="2"><?php echo $productPrice; ?>
							</td>
						</tr>
						<?php if($this->catalogoComponentConfig->get("cfg_price_promo") && $previousPrice!=0):?>
						<tr>
							<td colspan="2" style="text-align: center"><?php echo JText::_("CP.AVAILABILITY.PREVIOUS.PRICE")?>:
								<strike><?php echo $previousPrice?> </strike>
							</td>
						</tr>
						<?php else:?>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<?php endif;?>
						<tr>
							<td style="text-align: center; height: 40px;" colspan="2">
								<div class="boton_seleccionar_div">
									<a href="<?php echo $link;?>" class="boton_seleccionar"> <?php echo JText::_('CP.CAR.SELECT');?>
									</a>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- Cierro div tarifas_vuelos -->
			<div class="clear"></div>
		</div>
		<!-- Cierro div fila_resultado_planes -->
		<?php endforeach;?>

	</div>
	<div id="message_not_found" style="display: none">No se encontraron
		coincidencias</div>
	<div class="clear"></div>

	<div id="dialog" style="display: none">
		<div id="mapDialog" style="width: 800px; height: 500px"></div>
	</div>
</div>
<!-- Filtros de disponibilidad -->
<div class="izq_disponibilidad_planes">
	<span class="txt_filtros"><?php echo JText::_('CP.CAR.FILTER.TITLE');?>
	</span>
	<div class="interno">
	<?php if($minPrice!=$maxPrice):?>
		<div class="conte_filtros">
			<span class="title_filter"><?php echo JText::_('CP.CAR.FILTER.PRICE.TITLE');?>
			</span>
			<div class="priceSliderContainer">
			<?php $minPriceFormat = $productPrice = $this->getFormatPrice($minPrice,$currency);?>
				<span id="izqPrice" class="izqFilter"><?php echo $minPriceFormat?> </span>
				<?php $maxPriceFormat = $productPrice = $this->getFormatPrice($maxPrice,$currency);?>
				<span id="derPrice" class="derFilter"><?php echo $maxPriceFormat?> </span>
				<div id="priceSlider"></div>
				<script>setFilterPrice('priceSlider',<?php echo $minPrice?>, <?php echo $maxPrice?>, '<?php echo $currency?>')</script>
			</div>
		</div>
		<?php endif;?>
		<?php if($this->params["category"]=="0"):?>
		<div class="conte_filtros">
			<span class="title_filter"><?php echo JText::_('CP.CAR.FILTER.CATEGORY.TITLE');?>
			</span>
			<div>
			<?php
			foreach($arrayCategory as $index=>$category):?>
				<div>
					<input type="checkbox" name="accomodation"
						class="filter_accomodation"
						id="filter_accomodation<?php echo $index?>"
						value="<?php echo $index?>" checked /> <label
						for="filter_accomodation<?php echo $index?>"> <?php echo ($category=="")?JText::_("CP.CAR.FILTER.CATEGORY.OPTION.OTHER"):$category;?>
					</label>
				</div>
				<?php endforeach;?>
				<script type="text/javascript">setFilterAccomodation('filter_accomodation')</script>
			</div>
		</div>
		<?php endif;?>

	</div>
</div>
<div class="clear"></div>
