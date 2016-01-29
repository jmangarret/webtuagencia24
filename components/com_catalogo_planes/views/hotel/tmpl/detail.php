<?php $data = $this->data;
//Get the limits of adults and childs
$ageAdult = explode("-", $this->configQS["adults"]);
$ageChild = explode("-", $this->configQS["childs"]);
$app =& JFactory::getApplication();

?>
<div class="conte_plan">
	<input type="hidden" id="product_id" value="<?php echo $data["id"]; ?>" />
	<div class="top_titulo_cp_detail">
	<?php echo $data["name"]; ?>
		&nbsp;
		<?php echo ($data["featured"]==1)?"<img src='".JURI::base()."images/paquete_recomendado_star_red.png' title='".JText::_("CP.FEATURED.TITLE")."'/>":"";?>
	</div>
	<?php if($data["stars"]>0):?>
	<div class="precio_tit_precio_plan_dispo">
		<div class="esquina_left_dias_plan"></div>
		<div class="center_dias_plan">
			<div class="derecha_desc_plan">
			<?php for($star=0; $star<$data["stars"]; $star++):?>
				<img
					src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/stars_1b.png" />
					<?php endfor;?>
			</div>

		</div>
		<div class="esquina_right_dias_plan"></div>
		<div class="clear"></div>
	</div>
	<?php endif; ?>
	<div class="ciudad_pais_precio_plan">
		<div class="ciudad_pais_plan">
			<div>
				<strong><?php echo JText::_( 'CP.HOTEL.DETAIL.COUNTRY' ); ?>:</strong>&nbsp;
				<?php echo $data["country"]["name"]; ?>
			</div>
			<div>
				| <strong><?php echo JText::_( 'CP.HOTEL.DETAIL.CITY' ); ?>:</strong>&nbsp;
				<?php echo $data["city"]["name"]; ?>
			</div>
			<?php if(isset($data["region"]["name"]) && $data["region"]["name"]!="" && $this->catalogoComponentConfig->cfg_use_regions_hotel==1):?>

			<div>
				| <strong><?php echo JText::_( 'CP.HOTEL.DETAIL.REGION' ); ?>:</strong>&nbsp;
				<?php echo $data["region"]["name"];?>
			</div>
			<?php endif;?>

		</div>

		<div class="clear"></div>
		<div class="descripcion_corta_plan">
		<?php echo $data["description"]; ?>
		</div>
		<?php if(is_array($data["amenities"]) && count($data["amenities"])>0):?>
		<div class="amenities_plan">
		<?php foreach($data["amenities"]["amenity"] as $amenity): ?>
		<?php
		//$imginfo = @getimagesize($this->getImage("amenity", $amenity["image"]));
		//if($imginfo):
		?>
			<img src="<?php echo $this->getImage("amenity", $amenity["image"])?>"
				title="<?php echo $amenity["name"]?>" width="20" />
				<?php //endif;?>
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
				echo '<li filter="map"><a href="#tab' . $j . '" id="link_tab' . $j . '"  >' . JText::_("CP.HOTEL.DETAIL.MAP") . '</a></li>';
				$this->setMap("map_canvas", $data["latitude"], $data["longitude"]);
				$j++;
				elseif($data["url"]!="" && preg_match('/^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,4}|travel)(:\d{2,5})?(\/.*)?$/i',  base64_decode($data["url"]))):
				echo '<li filter="map"><a href="#tab' . $j . '" id="link_tab' . $j . '"  >' . JText::_("CP.PLAN.DETAIL.MAP") . '</a></li>';
				$j++;
				endif;?>
				<?php if(!empty($data["comments"])):
				echo '<li><a href="#tab' . $j . '" id="link_tab' . $j . '">' . JText::_("CP.HOTEL.DETAIL.COMMENTS") . '</a></li>';
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
	<?php echo JText::_("CP.HOTEL.DETAIL.PRICE.PER.NIGHT")?>
	</div>
	<div class="detalles_precio_plan">
		<form name="frmDetails" id="frm_details"
			action="<?php echo JRoute::_("index.php?option=com_catalogo_planes&view=hotel&layout=guest")?>"
			method="POST" />
		<input type="hidden" name="product_id"
			value="<?php echo $data["id"]; ?>" />
		<div id="conteCalendar">
			<h3 class="seccion_title" id="head1">
				<img
					src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_calendar_int.png" />
					<?php echo JText::_("CP.HOTEL.DATE.CHECKIN")?>
				<span id="checkin"> <?php echo (isset($this->params["HOTEL_DATE_START"]) && $this->params["HOTEL_DATE_START"]!="")?"(".$this->params["HOTEL_DATE_START"].")":"";?>
				</span>
			</h3>
			<div class="conte_calendar">
				<input type="hidden" name="checkin" id="hdnCheckin"
					value="<?php echo (isset($this->params["HOTEL_DATE_START"]) && $this->params["HOTEL_DATE_START"]!="")?$this->params["HOTEL_DATE_START"]:"";?>" />
				<div id="date_start"></div>
			</div>
			<h3 class="seccion_title" id="head2">
				<img
					src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_calendar_int.png" />
					<?php echo JText::_("CP.HOTEL.DATE.CHECKOUT")?>
				<span id="checkout"> <?php echo (isset($this->params["HOTEL_DATE_FINISH"]) && $this->params["HOTEL_DATE_FINISH"]!="")?"(".$this->params["HOTEL_DATE_FINISH"].")":"";?>
				</span>
			</h3>
			<div class="conte_calendar">
				<input type="hidden" name="checkout" id="hdnCheckout"
					value="<?php echo (isset($this->params["HOTEL_DATE_FINISH"]) && $this->params["HOTEL_DATE_FINISH"]!="")?$this->params["HOTEL_DATE_FINISH"]:"";?>" />
				<div id="date_finish"></div>
			</div>

			<h3 class="seccion_title" id="head3">
				<img
					src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_viajeros.png" />
					<?php echo JText::_("CP.HOTEL.GUESTS")?>
			</h3>
			<div class="conte_calendar">
				<div id="pass_select">
					<div class="adult">
					<?php echo JText::_("CP.HOTEL.ADULTS")?>
						<select name="adults" id="adults">
						<?php for($contAdult=$ageAdult[0]; $contAdult<=$ageAdult[1]; $contAdult++):?>
							<option value="<?php echo $contAdult?>"
							<?php echo (isset($this->params["HOTEL_ADULTS"]) && $this->params["HOTEL_ADULTS"]==$contAdult)?"selected":"";?>>
								<?php echo $contAdult?>
							</option>
							<?php endfor;?>
						</select>
					</div>
					<div id="childsSelect">
					<?php echo JText::_("CP.HOTEL.CHILDS")?>
						<select name="childs" id="childs">
						<?php for($contChild=$ageChild[0]; $contChild<=$ageChild[1]; $contChild++):?>
							<option value="<?php echo $contChild?>"
							<?php echo (isset($this->params["HOTEL_CHILDREN"]) && $this->params["HOTEL_CHILDREN"]==$contChild)?"selected":"";?>>
								<?php echo $contChild?>
							</option>
							<?php endfor;?>
						</select>
					</div>
				</div>
				<div id="message_error" style="display: none">
				<?php echo JText::_("CP.HOTEL.NO.RATE.MESSAGE")?>
				</div>
				<div id="conte_loader" style="display: none">
				<?php echo JText::_("CP.HOTEL.LOADER.MESSAGE")?>
					<div id="loader">
						<img src="<?php echo $this->root_path?>images/ajax-loader.gif" />
					</div>
				</div>
			</div>
		</div>


		<div id="content_room" style="display: none;">
			<h3 class="seccion_title">
				<img
					src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_hotel.png" />
					<?php echo JText::_("CP.HOTEL.SELECT.ROOM")?>
			</h3>
			<div class="conte_rooms" id="content_params">
			<?php //Recorro el listado de tipos de habitacion
			foreach($data["params1"]["param"] as $param1):?>
				<div id="content_param1<?php echo $param1["id"]?>"
					class="content_param1">
					<h2 class="title_params" rel="<?php echo $param1["id"]?>">
					<?php echo $param1["name"]?>
					</h2>
					<div class="contain_params" rel="<?php echo $param1["id"]?>"
						id="contain_param<?php echo $param1["id"]?>" style="display: none">
						<div class="param2 title">
							<div class="param1_value1">
							<?php echo JText::_("CP.HOTEL.PARAM2.TITLE1")?>
							</div>
							<div class="param1_value3">
							<?php echo JText::_("CP.HOTEL.PARAM2.TITLE3")?>
							</div>
							<div class="param1_value2">
							<?php echo JText::_("CP.HOTEL.PARAM2.TITLE2")?>
							</div>
						</div>
						<?php //Recorro el listado de tipo de acomodacion
						foreach($data["params2"]["param"] as $param2):?>

						<div class="param2 detail_param2"
							id="content_param2<?php echo $param1["id"]?><?php echo $param2["id"]?>"
							capacity="<?php echo $param2["capacity"]?>">

							<div class="param1_value1">
							<?php echo $param2["name"]?>
							</div>
							<div class="param1_value3">
								<select
									name="cantParams[<?php echo $param1["id"]?>][<?php echo $param2["id"]?>]"
									id="cant_params<?php echo $param1["id"]?><?php echo $param2["id"]?>"
									param1="<?php echo $param1["id"]?>"
									param2="<?php echo $param2["id"]?>"
									capacity="<?php echo $param2["capacity"]?>" class="cant_params">
									<?php for($countH=0; $countH<9; $countH++):?>
									<option value="<?php echo $countH?>">
									<?php echo $countH?>
									</option>
									<?php endfor;?>
								</select> <input type="hidden"
									name="price[<?php echo $param1["id"]?>][<?php echo $param2["id"]?>]"
									class="hdn_param_price"
									id="hdn_param_price<?php echo $param1["id"]?><?php echo $param2["id"]?>"
									value="0" />
							</div>
							<div class="param1_value2">
								<select
									name="params[<?php echo $param1["id"]?>][<?php echo $param2["id"]?>]"
									id="select_param3<?php echo $param1["id"]?><?php echo $param2["id"]?>"
									class="selectFeed" param1="<?php echo $param1["id"]?>"
									param2="<?php echo $param2["id"]?>">
									<?php //Recorro el listado de tipos de alimentacion
									foreach($data["params3"]["param"] as $param3):?>
									<option id="<?php echo $param3["id"]?>">
									<?php echo $param3["name"]?>
									</option>
									<?php endforeach;?>
								</select>
							</div>

							<div class="param1_value4"
								id="price<?php echo $param1["id"]?><?php echo $param2["id"]?>">

							</div>
							<div class="param1_value4_label">
							<?php echo JText::_("CP.HOTEL.PARAM2.TITLE4")?>
							</div>
							<div class="clear"></div>


						</div>


						<?php endforeach;?>
						<div class="clear"></div>
					</div>
				</div>
				<?php endforeach;?>
			</div>
			<div id="seccion_childs"
			<?php echo (isset($this->params["HOTEL_CHILDREN"]) && $this->params["HOTEL_CHILDREN"]>0)?"":'style="display:none"';?>>
				<h3 class="seccion_title">
					<img
						src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_viajeros.png" />
						<?php echo JText::_("CP.HOTEL.CHILDS")?>
				</h3>
				<div class="conte_rooms">
					<div class="contain_params">
						<div class="param2 title">
							<div class="child_value1">
							<?php echo JText::_("CP.HOTEL.PARAM2.TITLE5")?>
							</div>
							<div class="child_value2">
							<?php echo JText::_("CP.HOTEL.PARAM2.TITLE2")?>
							</div>
							<div class="child_value3">
							<?php echo JText::_("CP.HOTEL.PARAM2.TITLE4")?>
							</div>
						</div>
						<div class="param2">
							<div class="child_value1" id="cant_child">
							<?php echo (isset($this->params["HOTEL_CHILDREN"]) && $this->params["HOTEL_CHILDREN"]>0)?$this->params["HOTEL_CHILDREN"]:'';?>
							</div>
							<div class="child_value2">
								<select name="child_feed" id="select_childs"
									class="selectFeedChild">
									<?php foreach($data["params3"]["param"] as $param3):?>
									<option id="<?php echo $param3["id"]?>">
									<?php echo $param3["name"]?>
									</option>
									<?php endforeach;?>
								</select> <input type="hidden" name="priceChild"
									class="hdn_param_price" id="hdn_param_price_child" value="0" />
							</div>
							<div class="child_value3" id="price_child"></div>
						</div>
						<div>
						<?php echo $data["additional_description"]?>
						</div>
					</div>
				</div>
			</div>
			<?php
			if(!empty($data["supplements"]["supplement"])):?>
			<div id="seccion_supplements">
				<h3 class="seccion_title">
					<img
						src="<?php echo JURI::base().'templates/'.$app->getTemplate(); ?>/images/ico_suplementos.png" />

						<?php echo JText::_("CP.HOTEL.SELECT.SUPPLEMENT")?>
				</h3>
				<div class="conte_rooms" id="content_supplement">
					<div class="contain_params">
						<div class="param2 title">
							<div class="supplement_value1">
							<?php echo JText::_("CP.HOTEL.SUPPLEMENT.NAME")?>
							</div>
							<div class="supplement_value3">
							<?php echo JText::_("CP.HOTEL.SUPPLEMENT.QUANTITY")?>
							</div>
							<div class="supplement_value2">
							<?php echo JText::_("CP.HOTEL.SUPPLEMENT.PRICE")?>
							</div>
						</div>
						<div>
						<?php foreach($data["supplements"]["supplement"] as $supplement):?>
							<div
								class="supplement_info<?php echo ($supplement["apply_once"]==0)?"":"2";?> supplement_div"
								id="supplement_div<?php echo $supplement["id"]?>">
								<div class="supplement_value1">
								<?php echo $supplement["name"]?>
								</div>
								<?php if($supplement["apply_once"]==0):?>
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
									<?php else:?>
								<div id="ConteSupplement<?php echo $supplement["id"]?>"></div>
								<?php endif;?>
								<div class="clear"></div>
								<?php $imginfo = @getimagesize($supplement["image"]);
								if($imginfo || $supplement["description"]): ?>
								<div class="mas_info_suple">
									<div class="link_mas_info_suple">
										<a href="javascript:void(0)" class="view_more_supp"
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
												<?php endif;?>
										</div>
										<?php endif;?>
										<?php if($supplement["description"]):?>
										<div
											class="txt_conte_mas_info_suple <?php if (!$imginfo):?> txt_conte_only_des<?php endif;?>">
											<?php echo $supplement["description"]; ?>
										</div>
										<?php endif;?>
										<div class="clear"></div>
									</div>
								</div>
								<?php endif;?>
							</div>
							<?php endforeach;?>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
			<?php endif;?>
			<div id="seccion_subtotal">
			<?php echo JText::_("CP.HOTEL.TOTAL")?>
				<span id="subtotal">COP 1500</span>
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
			<a href="javascript: void(0)" class="button_next" id="button_next"> <?php echo JText::_("CP.HOTEL.BUTTON.DETAIL.SELECT")?>
			</a>
		</div>


		</form>
	</div>
</div>
