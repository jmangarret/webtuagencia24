<?php $data = $this->data;
//die(print_r($data));
$product = 'tour';
//Get the limits of adults and childs
$ageAdult = explode("-", $this->configQS->get($product.'_maxadults','1-20'));
$ageChild = explode("-", $this->configQS->get($product.'_maxchilds','0-10'));
$app =& JFactory::getApplication();
?>

<div class="conte_plan">
	<div class="top_titulo_cp_detail">
	<?php echo $data["name"]; ?>
		&nbsp;
		<?php echo ($data["featured"]==1)?"<img src='".JURI::base()."images/paquete_recomendado_star_red.png' title='".JText::_("CP.FEATURED.TITLE")."'/>":"";?>
	</div>

	<div class="precio_tit_precio_plan_dispo">
		<div class="esquina_left_dias_plan"></div>
		<div class="center_dias_plan">
			<div class="derecha_desc_plan">
			<?php echo $data["duration_text"];?>
			</div>
		</div>
		<div class="esquina_right_dias_plan"></div>
		<div class="clear"></div>
	</div>
	<div class="ciudad_pais_precio_plan">
		<div class="ciudad_pais_plan">
			<div>
				<strong><?php echo JText::_( 'CP.TOUR.DETAIL.COUNTRY' ); ?>:</strong>&nbsp;
				<?php echo $data["country"]["name"]; ?>
			</div>
			<?php if(isset($data["region"]["name"]) && $data["region"]["name"]!="" && $this->catalogoComponentConfig->cfg_use_regions==1):?>
			<div>
				| <strong><?php echo JText::_( 'CP.TOUR.DETAIL.REGION' ); ?>:</strong>&nbsp;
				<?php echo $data["region"]["name"];?>
			</div>
			<?php endif;?>
			<div>
				| <strong><?php echo JText::_( 'CP.TOUR.DETAIL.CITY' ); ?>:</strong>&nbsp;
				<?php echo $data["city"]["name"]; ?>
			</div>
		</div>

		<div class="clear"></div>
		<div class="descripcion_corta_plan">
		<?php echo $data["description"]; ?>
		</div>
		<?php if(is_array($data["amenities"]) && count($data["amenities"])>0):?>
		<div class="amenities_plan">
		<?php foreach($data["amenities"]["amenity"] as $amenity):?>
			<img src="<?php echo $this->getImage("amenity", $amenity["image"])?>"
				title="<?php echo $amenity["name"]?>" width="20" />
				<?php endforeach;?>
		</div>
		<?php endif;?>
		<div class="clear"></div>
	</div>
	<div class="conte_zona_plan">
	<?php if (!empty($data["images"]) && count($data["images"])>0):?>
		<div class="conte_slider_fotos" id="galleriaProduct">
			<!-- Open div conte_slider_fotos  -->
		<?php foreach ($data["images"]["image"] as $img):?>
		<?php if (file_exists($img["url"]) || preg_match('/^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,4}|travel)(:\d{2,5})?(\/.*)?$/i',  $img["url"])) :?>
			<img src="<?php echo $img["url"]?>" border="0" />
			<?php endif;?>
			<?php endforeach; ?>
		</div>
		<?php endif;?>


		<?php if (!empty($data["details"]) || ($data["latitude"]!="" && $data["longitude"]!="") || !empty($data["comments"]) || $data["url"]!=""): ?>
		<div class="conte_info_plan">
			<!-- Open div class conte_info_plan  -->
			<div class="izq_arrow">&nbsp;</div>
			<div class="der_arrow">&nbsp;</div>
			<div class="conte_tags_arrows">
				<ul class="tabs">
					<!-- Inicia Lista que forma las pestañas para la navegacion -->
				<?php $j=0;
				if(!empty($data["details"])):
				foreach($data["details"]["tag"] as $tag):
				echo '<li><a href="#tab' . $j . '" id="link_tab' . $j . '">' . $tag["name"] . '</a></li>';
				$j++;
				endforeach;
				endif;?>

				<?php if($data["latitude"]!="" && $data["longitude"]!=""):
				echo '<li filter="map"><a href="#tab' . $j . '" id="link_tab' . $j . '"  >' . JText::_("CP.TOUR.DETAIL.MAP") . '</a></li>';
				$this->setMap("map_canvas", $data["latitude"], $data["longitude"]);
				$j++;
				elseif($data["url"]!="" && preg_match('/^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,4}|travel)(:\d{2,5})?(\/.*)?$/i',  base64_decode($data["url"]))):
				echo '<li filter="map"><a href="#tab' . $j . '" id="link_tab' . $j . '"  >' . JText::_("CP.TOUR.DETAIL.MAP") . '</a></li>';
				$j++;
				endif;?>
				<?php if(!empty($data["comments"])):
				echo '<li><a href="#tab' . $j . '" id="link_tab' . $j . '">' . JText::_("CP.TOUR.DETAIL.COMMENTS") . '</a></li>';
				$j++;
				endif;?>
				</ul>
				<!-- Cierra Lista que forma las pestañas para la navegacion -->
			</div>
			<div class="tab_container">
				<!-- Abro div class tab_container -->
			<?php $j=0;
			if(!empty($data["details"])):
			foreach($data["details"]["tag"] as $tag):
			echo '<div class="tab_content" id="tab' . $j . '">' .$tag["content"] . '</div>';
			$j++;
			endforeach;
			endif;?>
			<?php if($data["latitude"]!="" && $data["longitude"]!=""):
			echo '<div class="tab_content" id="tab' . $j . '"><div id="map_canvas"></div></div>';
			$j++;
			elseif($data["url"]!="" && preg_match('/^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,4}|travel)(:\d{2,5})?(\/.*)?$/i',  base64_decode($data["url"]))):
			echo '<div class="tab_content" id="tab' . $j . '"><iframe width="626px" id="map_canvas" src="'.base64_decode($data["url"]).'" frameborder="0"></iframe></div>';
			$j++;
			endif;?>
			<?php if(!empty($data["comments"])):?>
				<div class="tab_content" id="tab<?php echo $j?>">
				<?php foreach($data["comments"]["comment"] as $comment):?>
					<div>
						<div class="comment_image">
							<img src="<?php echo $this->root_path;?>/images/comment.jpg" />
						</div>
						<div class="comment">
						<?php echo $comment["created_date"]?>
							<br />
							<?php echo $comment["text"]?>
						</div>
						<div class="clear"></div>
					</div>
					<?php endforeach;?>
				</div>
				<?php
				$j++;
				endif;?>
			</div>
			<!-- Cierro div class tab_container -->
			<div class="clear"></div>
		</div>
		<?php endif;?>
	</div>
</div>


		<?php //seccion de tarificacion?>
<div class="conte_tarif">
	<div class="top_titulo_cp_detail">
	<?php echo JText::_("CP.TOUR.DETAIL.PRICE.PER.NIGHT")?>
	</div>
	<div class="detalles_precio_plan">
		<form name="frmDetails" id="frm_details"
			action="<?php echo JRoute::_("index.php?option=com_catalogo_planes&view=tour&layout=guest")?>"
			method="POST" />
		<input type="hidden" name="product_id" id="product_id"
			value="<?php echo $data["id"]; ?>" />
		<div id="conteCalendar">
			<h3 class="seccion_title" id="head1">
				<img
					src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_calendar_int.png" />
					<?php echo JText::_("CP.TOUR.DATE.CHECKIN")?>
				<span id="checkin"> <?php echo (isset($this->data["selectedDate"]) && $this->data["selectedDate"]!="")?"(".$this->data["selectedDate"].")":"";?>
				</span>
			</h3>
			<div class="conte_calendar">
			<?php if($data["specialSeasons"])://echo JFactory::getLanguage()->_lang; ?>
				<div class="specialSeasons_date">
				<?php echo JText::_("CP.TOUR.CHECKIN.DATE.SELECT")?>
					: <select name="checkin" id="hdnCheckin" class="dateSelect">
						<option value="">
							-
							<?php echo JText::_("CP.TOUR.CHECKIN.DATE.SELECT.OPTION.SELECT")?>
							-
						</option>
						<?php
						foreach($data["avaibleDates"] as $date):
						$timestamp_date = strtotime(str_replace('/', '-',$date));
						$format_date = iconv('ISO-8859-2', 'UTF-8',strftime(JText::_("CP.TOUR.STRFTIME.FORMAT"),$timestamp_date));
						$a = array("ĂĄ","ĂŠ");
						$b = array("á","é");
						$format_date = str_replace($a, $b,$format_date);
						?>
						<option value="<?php echo $date?>"
						<?php echo (isset($this->data["selectedDate"]) && $this->data["selectedDate"]!="")?"selected":"";?>>
							<?php echo $this->ucwordss($format_date, array('de')); ?>
						</option>
						<?php endforeach;?>
					</select>
				</div>
				<?php else:?>
				<input type="hidden" name="checkin" id="hdnCheckin"
					value="<?php echo (isset($this->data["selectedDate"]) && $this->data["selectedDate"]!="")?$this->data["selectedDate"]:"";?>" />
				<div id="date_start"></div>
				<?php endif;?>

			</div>
			<h3 class="seccion_title" id="head3">
				<img
					src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_viajeros.png" />
					<?php echo JText::_("CP.TOUR.GUESTS")?>
			</h3>
			<div class="conte_calendar">
				<div id="pass_select">
					<div class="adult" class="selectGuestsTour"
						param="<?php echo $data["param2Adults"]?>">
						<?php echo JText::_("CP.TOUR.ADULTS")?>
						<select name="adults" id="adults">
						<?php for($contAdult=0; $contAdult<=$ageAdult[1]; $contAdult++):?>
							<option value="<?php echo $contAdult?>"
							<?php echo (isset($this->params["PLAN_ADULTS"]) && $this->params["PLAN_ADULTS"]==$contAdult)?"selected":"";?>>
								<?php echo $contAdult?>
							</option>
							<?php endfor;?>
						</select>
					</div>
					<div id="childsSelect" class="selectGuestsTour"
						param="<?php echo $data["param2Childs"]?>">
						<?php echo JText::_("CP.TOUR.CHILDS")?>
						<select name="childs" id="childs">
						<?php for($contChild=$ageChild[0]; $contChild<=$ageChild[1]; $contChild++):?>
							<option value="<?php echo $contChild?>"
							<?php echo (isset($this->params["PLAN_CHILDREN"]) && $this->params["PLAN_CHILDREN"]==$contChild)?"selected":"";?>>
								<?php echo $contChild?>
							</option>
							<?php endfor;?>
						</select>
					</div>

					<input type="hidden" name="priceGuestsTour" class="hdn_param_price"
						id="hdn_param_price_GuestsTour" value="0" />
				</div>
				<div id="message_error" style="display: none">
				<?php echo JText::_("CP.TOUR.NO.RATE.MESSAGE")?>
				</div>
				<div id="conte_loader" style="display: none">
				<?php echo JText::_("CP.TOUR.LOADER.MESSAGE")?>
					<div id="loader">
						<img src="<?php echo $this->root_path?>images/ajax-loader.gif" />
					</div>
				</div>
			</div>
		</div>


		<div id="content_room" style="display: none;">
		<?php if(!empty($data["supplements"]["supplement"])):?>

			<div id="seccion_supplements">
				<h3 class="seccion_title">
					<img
						src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_suplementos.png" />
						<?php echo JText::_("CP.TOUR.SELECT.SUPPLEMENT")?>
				</h3>
				<div class="conte_rooms" id="content_supplement">
					<div class="contain_params">
						<div class="param2 title">
							<div class="supplement_value1">
							<?php echo JText::_("CP.HOTEL.SUPPLEMENT.NAME")?>
							</div>
							<div class="supplement_value2">
							<?php echo JText::_("CP.HOTEL.SUPPLEMENT.PRICE")?>
							</div>
							<div class="supplement_value3">
							<?php echo JText::_("CP.HOTEL.SUPPLEMENT.QUANTITY")?>
							</div>
						</div>
						<div>
						<?php foreach($data["supplements"]["supplement"] as $supplement): ?>
							<div
								class="supplement_info<?php echo ($supplement["apply_once"]==0)?"":"2";?> supplement_div"
								id="supplement_div<?php echo $supplement["id"]?>">
								<div class="supplement_value1">
								<?php echo $supplement["name"]?>
								</div>
								<div class="supplement_value3">
									<select name="cantSupplement[<?php echo $supplement["id"]?>]"
										id="cant_supplement<?php echo $supplement["id"]?>"
										class="cant_supplement" rel="<?php echo $supplement["id"]?>">
										<?php for($i=0; $i<10; $i++):?>
										<option value="<?php echo $i?>">
										<?php echo $i?>
										</option>
										<?php endfor;?>
									</select> <input type="hidden"
										name="priceSupplement1<?php echo $supplement["id"]?>"
										class="hdn_param_price"
										id="hdn_param_price_supplement1<?php echo $supplement["id"]?>"
										value="0" />
								</div>
								<div class="supplement_value2"
									id="supplementPrice<?php echo $supplement["id"]?>"></div>
								<div class="clear"></div>
								<?php
								$imginfo = @getimagesize($supplement["image"]);
								if($imginfo || $supplement["description"]):
								?>
								<div class="mas_info_suple">
									<div class="link_mas_info_suple">
										<a href="javascript:void(0)"
											id="_div<?php echo $supplement["id"]?>"><?php echo JText::_("CP.TXT.LINK.VIEW.MORE")?>
										</a></a>
									</div>
									<div
										class="conte_mas_info_suple  despSupl_div<?php echo $supplement["id"]?>">
										<?php if($supplement["image"]):?>
										<div class="foto_conte_mas_info_suple">
										<?php if ($imginfo):?>
											<img width="80" height="80"
												title="<?php echo $supplement["name"] ?>"
												alt="<?php echo $supplement["name"] ?>"
												src="<?php echo $supplement["image"]; ?>">
												<?php endif; ?>
										</div>
										<?php endif;?>
										<?php if($supplement["description"]):?>
										<div
											class="txt_conte_mas_info_suple<?php if (!$imginfo):?> txt_conte_only_des<?php endif;?>">
											<?php echo $supplement["description"]; ?>
										</div>
										<?php endif;?>
										<div class="clear"></div>
									</div>
								</div>
								<?php endif;?>
								<div class="clear"></div>
							</div>
							<?php endforeach;?>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
			<?php endif;?>
			<div id="seccion_subtotal">
			<?php echo JText::_("CP.TOUR.TOTAL")?>
				<span id="subtotal">0</span>
				<?php if($data["disclaimer"]!=""):?>
				<div>
					<a href="javascript: void(0)" id="note"><?php echo JText::_("CP.HOTEL.NOTE")?>
					</a>
				</div>
				<?php endif;?>
			</div>
			<div id="conte_note" style="display: none;">
			<?php echo $data["disclaimer"]?>
			</div>
		</div>


		<div id="conte_button" style="display: none;">
			<a href="javascript: void(0)" class="button_next" id="button_next"> <?php echo JText::_("CP.TOUR.BUTTON.DETAIL.SELECT")?>
			</a>
		</div>

		</form>
	</div>
</div>
