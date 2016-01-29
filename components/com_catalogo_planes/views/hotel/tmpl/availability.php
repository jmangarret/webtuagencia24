<?php
//Contador de orden por defecto
$contOrder=0;
//Precio minimo para filtros
$minPrice = 0;
//Precio maximo para filtros
$maxPrice = 0;
//Arreglo de zonas para filtros
$arrayZones = array();
//Arreglo de estrellas para filtros
$arrayStars = array();
//Arreglo de tipo de alojamiento para filtros
$arrayAccommodations = array();
//Arreglo de tipos de turismo para filtros
$arrayTourismType = array();
$extraUrl = "";
if(isset($this->params["qsearch"]) && $this->params["qsearch"]==1){
  $extraUrl = "&qs=1";
}
$app =& JFactory::getApplication();
//die(print_r($this->data));

?>
<div
	class="centro_disponibilidad_planes">
	<!-- Abro div centro_disponibilidad_planes -->
	<div class="top_titulo_cp" id="scrollToHere">
		<!-- Abro div top_zona_verde -->
		<span><?php echo JText::_('CP.HOTEL.SEARCHRESULT'); ?> </span> <span
			class="datos_espe"> <?php echo JText::_('CP.HOTEL.ORDER.LABEL'); ?>&nbsp;
			<select id="order_field" class="campo_lista_pasajeros"
			name="order_field">
				<option value="0">
				<?php echo JText::_('CP.HOTEL.ORDER.OPTION.SELECT'); ?>
				</option>
				<option value="1">
				<?php echo JText::_('CP.HOTEL.ORDER.OPTION.PRICE'); ?>
				</option>
				<option value="2">
				<?php echo JText::_('CP.HOTEL.ORDER.OPTION.PRODUCTNAME'); ?>
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
	//Arreglo de estrellas para los filtros
	$arrayStars[$product["stars"]] = $product["stars"];
	//Arreglo de tipo de alojamiento para los filtros
	if($product["category"]["name"]!=""){
	  $arrayAccommodations[$product["category"]["id"]] = $product["category"]["name"];
	}else{
	  $arrayAccommodations[0] = JText::_("CP.HOTEL.FILTER.CATEGORY.OPTION.OTHER");
	}
	//Formateamos la lista de tipos de turismo
	$listTourismType = $this->getTourismTypeArray($product["tourismtype"]);

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
	if (empty($product["image"])) $product["image"] = $this->catalogoComponentConfig->cfg_no_image_url;
	?>

		<div class="fila_resultado_planes"
			price="<?php echo $product["price"]["value"]?>"
			name="<?php echo $product["name"]; ?>"
			default_order="<?php echo $contOrder?>"
			stars="<?php echo $product["stars"];?>"
			zone="<?php echo $product["zone"]["id"]?>"
			accomodation="<?php echo ($product["category"]["name"]!="")?$product["category"]["id"]:"0";?>">
			<!-- Abro div fila_resultado_planes -->
			<?php if(is_array($listTourismType["item"]) && count($listTourismType["item"])>0):
			foreach($listTourismType["item"] as $tourismType):
			//Arreglo de tipo de turismo para los filtros
			$arrayTourismType[$tourismType["id"]] = $tourismType["name"];?>
			<input type="hidden" name="tourismType" class="tourismType_param"
				value="<?php echo $tourismType["id"]?>" />
				<?php endforeach;
				endif;?>
			<div class="tit_precio_plan_dispo">
				<div class="rating_titulo">
					<div class="seccion_titulo">
						<a href="<?php echo $link; ?>"><?php echo $product["name"]; ?> </a>&nbsp;
						<?php echo ($product["featured"]==1)?"<img src='".JURI::base()."images/paquete_recomendado_star_red.png' title='".JText::_("CP.FEATURED.TITLE")."'/>":"";?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="precio_plan_dispo_planes">
					<div class="esquina_left_dias_plan"></div>
					<div class="center_precio_dispo_plan">
						<span class="txt_small_desde"><?php echo JText::_('CP.HOTEL.FROM'); ?>
						</span> <span class="precio_dispo_plan"><?php echo $productPrice; ?>
						</span> <span class="txt_small_desde"><?php echo JText::_('CP.HOTEL.PRICE.DESCRIPTION'); ?>
						</span>
					</div>
					<div class="esquina_right_dias_plan"></div>
					<div class="clear"></div>
				</div>
			</div>


			<div class="zona_info_plan_dispo">
			<?php
			$imginfo = @getimagesize($product["image"]);
			if($imginfo):
			?>
				<div class="foto_zona_info_plan_dispo">
					<a href="<?php echo $link; ?>"> <img width="170" height="120"
						src="<?php echo $product["image"]; ?>" /> </a>
					<div class="clear"></div>
				</div>
				<?php endif;?>
				<div
					class="texto_zona_info_plan_dispo<?php echo ($imginfo)?'':' without_foto_dispo' ?>">
					<h3>
					<?php echo JText::_('CP.HOTEL.DESCRIPTION');?>
					</h3>
					<?php for($star=0; $star<$product["stars"]; $star++):?>
					<img src="images/star.png" />
					<?php endfor;?>
					<?php if($product["rating"]>0):?>
					<div class="seccion_rating">
					<?php echo $product["rating"]." ".JText::_("CP.PLAN.PRODUCT.POINTS");?>
					</div>
					<?php endif;?>
					<div class="seccion_destino">
					<?php echo $product["country"]["name"]; ?>
						-
						<?php echo $product["city"]["name"]; ?>
					</div>

					<div class="seccion_detalles">
					<?php echo $product["description"]; ?>
					</div>
				</div>
				<div class="seleccionar_zona_info_plan_dispo">
				<?php if($this->catalogoComponentConfig->cfg_price_promo && $product['price']['previous_value']): ?>
					<div class="txt_price_antes">
					<?php echo JText::_('CP.PLAN.BEFORE.PRICE');?>
						: <span class="txt_price_antes_tac"><?php echo $this->getFormatPrice($product['price']['previous_value'],$currency);?>
						</span>
					</div>
					<?php endif;?>
					<?php if($product["latitude"]!="" && $product["longitude"]!=""):?>
					<div class="seccion_mapa">
						<a href="javascript: void(0)" class="link_map"
							rel="<?php echo $product["latitude"].":".$product["longitude"]?>">
							<?php echo JText::_('CP.HOTEL.MAP');?> </a>
					</div>
					<?php elseif($product["url"]!="" && preg_match('/^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,4}|travel)(:\d{2,5})?(\/.*)?$/i',  base64_decode($product["url"]))):?>
					<div class="seccion_mapa">
						<a href="javascript: void(0)" class="url_map"
							rel="<?php echo  base64_decode($product["url"])?>"> <?php echo JText::_('CP.HOTEL.MAP');?>
						</a>
					</div>
					<?php endif;?>
					<div class="boton_seleccionar_div">
						<a href="<?php echo $link;?>" class="boton_seleccionar"> <?php echo JText::_('CP.HOTEL.SELECT');?>
						</a>
					</div>

				</div>
				<div class="clear"></div>
			</div>
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
	<span class="txt_filtros"><?php echo JText::_('CP.HOTEL.FILTER.TITLE');?>
	</span>
	<div class="interno">
	<?php if($minPrice!=$maxPrice):?>
		<div class="conte_filtros">
			<span class="title_filter"><?php echo JText::_('CP.HOTEL.FILTER.PRICE.TITLE');?>
			</span>
			<div class="priceSliderContainer">
			<?php $minPriceFormat = $productPrice = $this->getFormatPrice($minPrice,$currency);?>
				<span id="izqPrice" class="izqFilter"><?php echo $minPriceFormat?> </span>
				<?php $maxPriceFormat = $productPrice = $this->getFormatPrice($maxPrice,$currency);?>
				<span id="derPrice" class="derFilter"><?php echo $maxPriceFormat?> </span>
				<div class="clear"></div>
				<div id="priceSlider"></div>
				<script>setFilterPrice('priceSlider',<?php echo $minPrice?>, <?php echo $maxPrice?>, '<?php echo $currency?>')</script>
			</div>
			<div class="clear"></div>
			<div class="clear"></div>
		</div>
		<?php endif;?>
		<?php if($this->params["HOTEL_CITY"]!="0"):?>
		<div class="conte_filtros">
			<span class="title_filter"><?php echo JText::_('CP.HOTEL.FILTER.ZONE.TITLE');?>
			</span>
			<div>
				<select name="zone" id="filter_zone" class="select_filter">
					<option value="">
						-
						<?php echo JText::_("CP.HOTEL.FILTER.ZONE.VALUE.ALL")?>
						-
					</option>
					<?php foreach($arrayZones as $index=>$zone):?>
					<option value="<?php echo $index?>">
					<?php echo $zone?>
					</option>
					<?php endforeach;?>
				</select>
				<script type="text/javascript">setFilterZone('filter_zone')</script>
			</div>
		</div>
		<?php endif;?>
		<?php if($this->params["HOTEL_ACCOMMODATION_TYPE"]=="0"):?>
		<div class="conte_filtros">
			<span class="title_filter"><?php echo JText::_('CP.HOTEL.FILTER.ACCOMODATIONTYPE.TITLE');?>
			</span>
			<div>
			<?php
			foreach($arrayAccommodations as $index=>$accomodation):?>
				<div>
					<input type="checkbox" name="accomodation"
						class="filter_accomodation"
						id="filter_accomodation<?php echo $index?>"
						value="<?php echo $index?>" checked /> <label
						for="filter_accomodation<?php echo $index?>"> <?php echo ($accomodation=="")?JText::_("CP.HOTEL.FILTER.CATEGORY.OPTION.OTHER"):$accomodation;?>
					</label>
				</div>
				<?php endforeach;?>
				<script type="text/javascript">setFilterAccomodation('filter_accomodation')</script>
			</div>
		</div>
		<?php endif;?>
		<div class="conte_filtros">
			<span class="title_filter"><?php echo JText::_('CP.HOTEL.FILTER.CATEGORY.TITLE');?>
			</span>
			<div>
			<?php rsort($arrayStars);
			foreach($arrayStars as $stars):?>
				<div class="start_filter">
					<input type="checkbox" name="stars" class="filter_star"
						id="filter_star<?php echo $stars?>" value="<?php echo $stars?>"
						checked /> <label for="filter_star<?php echo $stars?>"> <?php if($stars>0):?>
						<?php for($star=0; $star<$stars; $star++):?> <img
						src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/stars_1.png" />
						<?php endfor;?> <?php else:
						echo JText::_("CP.HOTEL.FILTER.CATEGORY.OPTION.OTHER");
						endif;?> </label>
				</div>
				<?php endforeach;?>
				<script type="text/javascript">setFilterStar('filter_star')</script>
			</div>
		</div>
		<?php if($this->params["HOTEL_TOURISMTYPE"]=="0"):?>
		<div class="conte_filtros">
			<span class="title_filter"><?php echo JText::_('CP.HOTEL.FILTER.TOURISMTYPE.TITLE');?>
			</span>
			<div>
				<select name="tourismtype" id="filter_tourismtype"
					class="select_filter">
					<option value="">
						-
						<?php echo JText::_("CP.HOTEL.FILTER.TOURISMTYPE.VALUE.ALL")?>
						-
					</option>
					<?php foreach($arrayTourismType as $index=>$tourismType):
					if($tourismType!=""):?>
					<option value="<?php echo $index?>">
					<?php echo $tourismType?>
					</option>
					<?php endif;
					endforeach;?>
				</select>
				<script type="text/javascript">setFilterTourismType('filter_tourismtype')</script>
			</div>
		</div>
		<?php endif;?>
	</div>
</div>
<div class="clear"></div>
