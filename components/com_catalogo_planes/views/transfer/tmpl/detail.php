<?php $data = $this->data;

//Get the limits of adults and childs
$ageAdult = explode("-", $this->configQS["adults"]);

?>
<div class="conte_plan">
	<input type="hidden" id="product_id" value="<?php echo $data["id"]; ?>" />
	<div class="top_titulo_cp_detail">
	<?php echo $data["name"]; ?>
		&nbsp;
		<?php echo ($data["featured"]==1)?"<img src='".JURI::base()."/components/com_catalogo_planes/assets/images/featured_icon.png' title='".JText::_("CP.FEATURED.TITLE")."'/>":"";?>
	</div>
	<div class="ciudad_pais_precio_plan">
		<div class="ciudad_pais_plan">
			<div>
				<strong><?php echo JText::_( 'CP.TRANSFER.DETAIL.COUNTRY' ); ?>:</strong>&nbsp;
				<?php echo $data["country"]["name"]; ?>
			</div>
			<?php if(isset($data["region"]["name"]) && $data["region"]["name"]!="" && $this->catalogoComponentConfig->get("cfg_use_regions")==1):?>
			<div>
				<strong><?php echo JText::_( 'CP.TRANSFER.DETAIL.REGION' ); ?>:</strong>&nbsp;
				<?php echo $data["region"]["name"];?>
			</div>
			<?php endif;?>
			<div>
				<strong><?php echo JText::_( 'CP.TRANSFER.DETAIL.CITY' ); ?>:</strong>&nbsp;
				<?php echo $data["city"]["name"]; ?>
			</div>
		</div>
		<div class="clear"></div>
		<div class="descripcion_corta_plan">
		<?php echo $data["description"]; ?>
		</div>

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
			echo '<li filter="map"><a href="#tab' . $j . '" id="link_tab' . $j . '"  >' . JText::_("CP.TRANSFER.DETAIL.MAP") . '</a></li>';
			$this->setMap("map_canvas", $data["latitude"], $data["longitude"]);
			$j++;
			elseif($data["url"]!="" && preg_match('/^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,4}|travel)(:\d{2,5})?(\/.*)?$/i',  base64_decode($data["url"]))):
			echo '<li filter="map"><a href="#tab' . $j . '" id="link_tab' . $j . '"  >' . JText::_("CP.PLAN.DETAIL.MAP") . '</a></li>';
			$j++;
			endif;?>
			<?php if(!empty($data["comments"])):
			echo '<li><a href="#tab' . $j . '" id="link_tab' . $j . '">' . JText::_("CP.TRANSFER.DETAIL.COMMENTS") . '</a></li>';
			$j++;
			endif;?>
			</ul>
			<!-- Cierra Lista que forma las pestañas para la navegacion -->

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
	<?php echo JText::_("CP.TRANSFER.DETAIL.PRICE.PER.NIGHT")?>
	</div>
	<div class="detalles_precio_plan">
		<form name="frmDetails" id="frm_details"
			action="<?php echo JRoute::_("index.php?option=com_catalogo_planes&view=transfer&layout=guest")?>"
			method="POST" />
		<input type="hidden" name="product_id"
			value="<?php echo $data["id"]; ?>" /> <input type="hidden"
			name="type" id="type" value="<?php echo $this->params["type"]; ?>" />
		<div id="conteCalendar">
			<h3 class="seccion_title" id="head1">
				<img src="<?php echo $this->getImage("icon", "calendar.png")?>" />
				<?php echo JText::_("CP.TRANSFER.DATE.CHECKIN")?>
				<span id="checkin" style="font-size: 11px;"> <?php echo (isset($this->params["date_start"]) && $this->params["date_start"]!="")?"(".$this->params["date_start"].")":"";?>
				</span>
			</h3>
			<div class="conte_calendar">
				<input type="hidden" name="checkin" id="hdnCheckin"
					value="<?php echo (isset($this->params["date_start"]) && $this->params["date_start"]!="")?$this->params["date_start"]:"";?>" />
				<div id="date_start"></div>
				<div class="hour_car">
				<?php echo JText::_("CP.TRANSFER.HOUR.CHECKIN")?>
					<select name="checkin_hour" id="checkin_hour"
						class="hour_car_select">
						<?php for($i=1; $i<=12; $i++):?>
						<option value="<?php echo $i?>"
						<?php echo (isset($this->params["hour_start"]) && $this->params["hour_start"]==$i)?"selected":"";?>>
							<?php echo $i?>
							:00
						</option>
						<?php endfor;?>
					</select> <select name="checkin_hour_ap" id="checkin_hour_ap"
						class="hour_car_select">
						<option value="1"
						<?php echo (isset($this->params["time_start"]) && $this->params["time_start"]==1)?"selected":"";?>>
							<?php echo JText::_("CP.CAR.HOUR.AM")?>
						</option>
						<option value="2"
						<?php echo (isset($this->params["time_start"]) && $this->params["time_start"]==2)?"selected":"";?>>
							<?php echo JText::_("CP.CAR.HOUR.PM")?>
						</option>
					</select>
				</div>
			</div>
			<?php if($this->params["type"]==2):?>
			<h3 class="seccion_title" id="head2">
				<img src="<?php echo $this->getImage("icon", "calendar.png")?>" />
				<?php echo JText::_("CP.TRANSFER.DATE.CHECKOUT")?>
				<span id="checkout" style="font-size: 11px;"> <?php echo (isset($this->params["date_finish"]) && $this->params["date_finish"]!="")?"(".$this->params["date_finish"].")":"";?>
				</span>
			</h3>
			<div class="conte_calendar">
				<input type="hidden" name="checkout" id="hdnCheckout"
					value="<?php echo (isset($this->params["date_finish"]) && $this->params["date_finish"]!="")?$this->params["date_finish"]:"";?>" />
				<div id="date_finish"></div>
				<div class="hour_car">
				<?php echo JText::_("CP.TRANSFER.HOUR.CHECKOUT")?>
					<select name="checkout_hour" id="checkout_hour"
						class="hour_car_select">
						<?php for($i=1; $i<=12; $i++):?>
						<option value="<?php echo $i?>"
						<?php echo (isset($this->params["hour_finish"]) && $this->params["hour_finish"]==$i)?"selected":"";?>>
							<?php echo $i?>
							:00
						</option>
						<?php endfor;?>
					</select> <select name="checkout_hour_ap" id="checkout_hour_ap"
						class="hour_car_select">
						<option value="1"
						<?php echo (isset($this->params["time_finish"]) && $this->params["time_start"]==1)?"selected":"";?>>
							<?php echo JText::_("CP.CAR.HOUR.AM")?>
						</option>
						<option value="2"
						<?php echo (isset($this->params["time_finish"]) && $this->params["time_finish"]==2)?"selected":"";?>>
							<?php echo JText::_("CP.CAR.HOUR.PM")?>
						</option>
					</select>
				</div>
			</div>
			<?php endif;?>
			<h3 class="seccion_title" id="head3">
				<img src="<?php echo $this->getImage("icon", "guest.png")?>" />
				<?php echo JText::_("CP.TRANSFER.GUESTS")?>
			</h3>
			<div class="conte_calendar">
				<div id="pass_select">
					<div class="adult">
					<?php echo JText::_("CP.TRANSFER.ADULTS")?>
						<select name="adults" id="adults">
						<?php for($contAdult=$ageAdult[0]; $contAdult<=$ageAdult[1]; $contAdult++):?>
							<option value="<?php echo $contAdult?>"
							<?php echo (isset($this->params["adults"]) && $this->params["adults"]==$contAdult)?"selected":"";?>>
								<?php echo $contAdult?>
							</option>
							<?php endfor;?>
						</select> <input type="hidden" name="destiny_id" id="hdn_param3" />
					</div>
				</div>
				<br /> <br />
				<div id="message_error" style="display: none">
				<?php echo JText::_("CP.TRANSFER.NO.RATE.MESSAGE")?>
				</div>
				<div id="conte_loader" style="display: none">
				<?php echo JText::_("CP.TRANSFER.LOADER.MESSAGE")?>
					<div id="loader">
						<img src="<?php echo $this->root_path?>images/ajax-loader.gif" />
					</div>
				</div>
				<div id="msg_error_hour" style="display: none;">
				<?php echo JText::_("CP.TRANSFER.HOUR.ERROR.MESSAGE")?>
				</div>
			</div>
		</div>

		<div id="content_room" style="display: none;">
			<h3 class="seccion_title">
				<img src="<?php echo $this->getImage("icon", "car.png")?>" />
				<?php echo JText::_("CP.TRANSFER.SELECT.DESTINY")?>
			</h3>
			<div class="conte_rooms" id="content_params">
			<?php //Recorro el listado de tipos de habitacion
			foreach($data["params3"]["param"] as $param3):?>
				<div id="content_param3<?php echo $param3["id"]?>"
					class="content_param1">
					<h3 class="title_params" rel="<?php echo $param3["id"]?>">
					<?php echo $param3["name"]?>
					</h3>
					<div class="contain_params contain_destiny"
						rel="<?php echo $param3["id"]?>"
						id="contain_param<?php echo $param3["id"]?>" style="display: none">
						<div class="param2 title">
							<div class="param1_value2"
								style="padding-left: 23px; width: 170px;">
								<?php echo JText::_("CP.TRANSFER.PARAM2.TITLE2")?>
							</div>
							<div class="param1_value1">
							<?php echo JText::_("CP.TRANSFER.PARAM2.TITLE3")?>
							</div>
						</div>
						<div class="param2 detail_param2"
							id="content_param2<?php echo $param1["id"]?><?php echo $param1["id"]?>">
							<div class="param1_value2"
								style="padding-left: 23px; width: 170px;">
								<select name="param1[<?php echo $param3["id"]?>]"
									id="select_param1<?php echo $param3["id"]?>"
									class="selectParam1" param3="<?php echo $param3["id"]?>">
									<?php foreach($data["params1"]["param"] as $param1):?>
									<option value="<?php echo $param1["id"]?>">
									<?php echo $param1["name"]?>
									</option>
									<?php endforeach;?>
								</select> <input type="hidden" name="price"
									id="hdn_param_price<?php echo $param3["id"]?>"
									class="hdn_param_price" />
							</div>
							<div class="param1_value1" id="price<?php echo $param3["id"]?>">
							</div>
						</div>
					</div>
				</div>
				<?php endforeach;?>
			</div>
			<?php
			if(!empty($data["supplements"]["supplement"])):?>
			<div id="seccion_supplements">
				<h3 class="seccion_title">
				<?php echo JText::_("CP.TRANSFER.SELECT.SUPPLEMENT")?>
				</h3>
				<div class="conte_rooms" id="content_supplement">
					<div class="contain_params">
						<div class="param2 title">
							<div class="supplement_value1">
							<?php echo JText::_("CP.TRANSFER.SUPPLEMENT.NAME")?>
							</div>
							<div class="supplement_value2">
							<?php echo JText::_("CP.TRANSFER.SUPPLEMENT.PRICE")?>
							</div>
							<div class="supplement_value3">
							<?php echo JText::_("CP.TRANSFER.SUPPLEMENT.QUANTITY")?>
							</div>
						</div>
						<div>
						<?php foreach($data["supplements"]["supplement"] as $supplement):?>
							<div class="supplement_info supplement_div"
								id="supplement_div<?php echo $supplement["id"]?>">
								<div class="supplement_value1">
								<?php echo $supplement["name"]?>
								</div>
								<?php if($supplement["apply_once"]==0):?>
								<div class="supplement_value2"
									id="supplementPrice<?php echo $supplement["id"]?>"></div>
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
										class="hdn_param_price_supplement"
										id="hdn_param_price_supplement1<?php echo $supplement["id"]?>"
										value="0" />
								</div>
								<?php else:?>
								<div id="ConteSupplement<?php echo $supplement["id"]?>"></div>
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
			<?php echo JText::_("CP.TRANSFER.TOTAL")?>
				<span id="subtotal">COP 1500</span>
				<?php if($data["disclaimer"]!=""):?>
				<div>
					<a href="javascript: void(0)" id="note"><?php echo JText::_("CP.TRANSFER.NOTE")?>
					</a>
				</div>
				<?php endif;?>
			</div>
			<div id="conte_note" style="display: none;">
			<?php echo $data["disclaimer"]?>
			</div>
		</div>
		<div id="conte_button" style="display: none;">
			<a href="javascript: void(0)" class="button_next" id="button_next"> <?php echo JText::_("CP.TRANSFER.BUTTON.DETAIL.SELECT")?>
			</a>
		</div>


		</form>
	</div>
</div>
